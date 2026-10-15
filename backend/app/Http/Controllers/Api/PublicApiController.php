<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicApiController extends Controller
{
    // Global Site Identity & Emergency Contacts
    public function getSiteData()
    {
        $emergencyContacts = DB::table('emergency_contacts')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $heroSlides = DB::table('hero_slides')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $subCities = DB::table('sub_cities')
            ->orderBy('order')
            ->get();

        return response()->json([
            'emergency_contacts' => $emergencyContacts,
            'hero_slides' => $heroSlides,
            'sub_cities' => $subCities,
        ]);
    }

    // 4 Sub-Cities (ክፍለ ከተሞች)
    public function getSubCities()
    {
        $subCities = DB::table('sub_cities')->orderBy('order')->get();
        return response()->json($subCities);
    }

    public function getSubCityDetail($slug)
    {
        $subCity = DB::table('sub_cities')->where('slug', $slug)->first();
        if (!$subCity) {
            return response()->json(['message' => 'Sub-city not found'], 404);
        }

        $offices = DB::table('offices')
            ->where('sub_city_id', $subCity->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json([
            'sub_city' => $subCity,
            'offices' => $offices
        ]);
    }

    // 22 Municipal Offices with Search Filter
    public function getOffices(Request $request)
    {
        $query = DB::table('offices')->where('is_active', true);

        if ($request->has('search') && !empty($request->search)) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name_am', 'like', $search)
                  ->orWhere('name_en', 'like', $search)
                  ->orWhere('name_om', 'like', $search)
                  ->orWhere('phone', 'like', $search)
                  ->orWhere('building_location_am', 'like', $search);
            });
        }

        if ($request->has('sub_city_id') && !empty($request->sub_city_id)) {
            $query->where('sub_city_id', $request->sub_city_id);
        }

        $offices = $query->orderBy('order')->get();
        return response()->json($offices);
    }

    // Citizen Services (Text Service vs Step Service)
    public function getServices(Request $request)
    {
        $query = DB::table('services')->where('is_active', true);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $services = $query->orderBy('order')->get();
        return response()->json($services);
    }

    public function getServiceDetail($slug)
    {
        $service = DB::table('services')->where('slug', $slug)->first();
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $steps = DB::table('service_steps')
            ->where('service_id', $service->id)
            ->orderBy('step_number')
            ->get();

        return response()->json([
            'service' => $service,
            'steps' => $steps
        ]);
    }

    // Public Tenders (Procurement notices with live countdown)
    public function getTenders()
    {
        $tenders = DB::table('tenders')
            ->orderByRaw("CASE WHEN status = 'open' THEN 1 ELSE 2 END")
            ->orderBy('closing_datetime', 'asc')
            ->get();

        return response()->json($tenders);
    }

    // Vacancies / Jobs
    public function getVacancies()
    {
        $vacancies = DB::table('vacancies')
            ->where('status', 'open')
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($vacancies);
    }

    // News Articles
    public function getNews(Request $request)
    {
        $query = DB::table('news_articles')->where('status', 'published');

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(9);
        return response()->json($news);
    }

    public function getNewsDetail($slug)
    {
        $article = DB::table('news_articles')->where('slug', $slug)->first();
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Increment view count
        DB::table('news_articles')->where('id', $article->id)->increment('views_count');

        return response()->json($article);
    }

    // Mega Projects & Infrastructure Tracker
    public function getProjects(Request $request)
    {
        $query = DB::table('projects');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $projects = $query->orderBy('start_date', 'desc')->get();
        return response()->json($projects);
    }

    // Resources & Document Downloads
    public function getResources(Request $request)
    {
        $query = DB::table('resources');

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('year') && !empty($request->year)) {
            $query->where('year', $request->year);
        }

        $resources = $query->orderBy('year', 'desc')->get();
        return response()->json($resources);
    }

    // Civic Opinion Poll
    public function getActivePoll(Request $request)
    {
        $poll = DB::table('polls')->where('is_active', true)->latest()->first();
        if (!$poll) {
            return response()->json(null);
        }

        $options = DB::table('poll_options')->where('poll_id', $poll->id)->orderBy('order')->get();
        
        // Check if user already voted from this IP
        $ipHash = hash('sha256', $request->ip() . '_' . $request->header('User-Agent'));
        $hasVoted = DB::table('poll_votes')
            ->where('poll_id', $poll->id)
            ->where('voter_ip_hash', $ipHash)
            ->exists();

        return response()->json([
            'poll' => $poll,
            'options' => $options,
            'has_voted' => $hasVoted
        ]);
    }

    public function submitPollVote(Request $request, $id)
    {
        $request->validate([
            'option_id' => 'required|integer|exists:poll_options,id'
        ]);

        $ipHash = hash('sha256', $request->ip() . '_' . $request->header('User-Agent'));

        // Prevent duplicate vote
        $alreadyVoted = DB::table('poll_votes')
            ->where('poll_id', $id)
            ->where('voter_ip_hash', $ipHash)
            ->exists();

        if ($alreadyVoted) {
            return response()->json(['message' => 'You have already voted in this poll.'], 422);
        }

        DB::transaction(function () use ($id, $request, $ipHash) {
            DB::table('poll_votes')->insert([
                'poll_id' => $id,
                'option_id' => $request->option_id,
                'voter_ip_hash' => $ipHash,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('poll_options')->where('id', $request->option_id)->increment('votes_count');
        });

        // Return updated options
        $updatedOptions = DB::table('poll_options')->where('poll_id', $id)->get();
        return response()->json([
            'message' => 'Vote recorded successfully',
            'options' => $updatedOptions
        ]);
    }

    // Contact Form Submission (No Login Required)
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
            'sub_city' => 'nullable|string|max:50'
        ]);

        DB::table('contact_messages')->insert([
            'sender_name' => $validated['name'],
            'sender_phone' => $validated['phone'],
            'sender_email' => $validated['email'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'target_sub_city' => $validated['sub_city'] ?? null,
            'status' => 'unread',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Message submitted successfully.']);
    }

    // Global Search across news, offices, services, and tenders
    public function globalSearch(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([
                'news' => [],
                'offices' => [],
                'services' => [],
                'tenders' => []
            ]);
        }

        $term = '%' . $q . '%';

        $news = DB::table('news_articles')
            ->where('status', 'published')
            ->where(function ($query) use ($term) {
                $query->where('title_am', 'like', $term)
                      ->orWhere('title_en', 'like', $term)
                      ->orWhere('title_om', 'like', $term);
            })
            ->select('id', 'title_am', 'title_en', 'title_om', 'slug', 'category')
            ->limit(5)
            ->get();

        $offices = DB::table('offices')
            ->where('is_active', true)
            ->where(function ($query) use ($term) {
                $query->where('name_am', 'like', $term)
                      ->orWhere('name_en', 'like', $term)
                      ->orWhere('name_om', 'like', $term);
            })
            ->select('id', 'name_am', 'name_en', 'name_om', 'slug', 'phone')
            ->limit(5)
            ->get();

        $services = DB::table('services')
            ->where('is_active', true)
            ->where(function ($query) use ($term) {
                $query->where('title_am', 'like', $term)
                      ->orWhere('title_en', 'like', $term)
                      ->orWhere('title_om', 'like', $term);
            })
            ->select('id', 'title_am', 'title_en', 'title_om', 'slug', 'type')
            ->limit(5)
            ->get();

        $tenders = DB::table('tenders')
            ->where(function ($query) use ($term) {
                $query->where('title_am', 'like', $term)
                      ->orWhere('title_en', 'like', $term)
                      ->orWhere('title_om', 'like', $term)
                      ->orWhere('reference_number', 'like', $term);
            })
            ->select('id', 'reference_number', 'title_am', 'title_en', 'title_om', 'status')
            ->limit(5)
            ->get();

        return response()->json([
            'news' => $news,
            'offices' => $offices,
            'services' => $services,
            'tenders' => $tenders
        ]);
    }

    // Mayor & Executive Profile
    public function getMayor()
    {
        $mayor = DB::table('mayors')->where('is_current', true)->first();
        return response()->json($mayor);
    }

    // Cabinet & City Council Leadership
    public function getLeadership(Request $request)
    {
        $query = DB::table('leadership_members');
        if ($request->has('type') && in_array($request->type, ['cabinet', 'council'])) {
            $query->where('type', $request->type);
        }
        $members = $query->orderBy('order')->get();
        return response()->json($members);
    }

    // Internal Staff Bulletin Board
    public function getInternalBoard()
    {
        $topics = DB::table('internal_board_topics')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $posts = DB::table('internal_board_posts')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'topics' => $topics,
            'posts' => $posts
        ]);
    }

    // Gallery Items (Images & Videos)
    public function getGallery(Request $request)
    {
        $query = DB::table('gallery_items');
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->has('media_type') && in_array($request->media_type, ['image', 'video'])) {
            $query->where('media_type', $request->media_type);
        }
        $items = $query->orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();
        return response()->json($items);
    }

    // Hero Carousel Slides
    public function getHeroSlides()
    {
        $slides = DB::table('hero_slides')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        return response()->json($slides);
    }

    // Destinations & Tourism Spotlight
    public function getDestinations(Request $request)
    {
        $query = DB::table('destinations')->where('is_active', true);
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        $items = $query->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return response()->json($items);
    }
}
