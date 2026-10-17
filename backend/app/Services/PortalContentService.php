<?php

namespace App\Services;

use App\Models\NewsArticle;
use App\Models\Tender;
use App\Models\EmergencyContact;
use App\Models\Mayor;
use App\Models\HeroSlide;
use App\Models\Poll;
use App\Models\Destination;
use App\Models\Office;
use App\Models\Service;
use App\Models\Project;
use App\Models\ResourceDocument;
use App\Models\SubCity;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PortalContentService
{
    /**
     * Get unified homepage bundle using Eloquent models (<80ms single trip).
     */
    public function getHomeBundle(Request $request): array
    {
        $heroSlides = HeroSlide::active()->get();
        $mayor = Mayor::current()->first();

        $news = NewsArticle::published()
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Auto-close expired tenders
        Tender::where('status', 'open')
            ->where('closing_datetime', '<', Carbon::now())
            ->update(['status' => 'closed']);

        $tenders = Tender::orderByRaw("CASE WHEN status = 'open' THEN 1 ELSE 2 END")
            ->orderBy('closing_datetime', 'asc')
            ->limit(2)
            ->get();

        $emergencyContacts = EmergencyContact::active()->get();

        $poll = Poll::with('options')->active()->latest()->first();
        $pollData = null;
        if ($poll) {
            $isExpired = !empty($poll->expires_at) && Carbon::parse($poll->expires_at)->endOfDay()->isPast();
            $ipHash = hash('sha256', $request->ip() . '_' . $request->header('User-Agent'));
            $hasVoted = DB::table('poll_votes')
                ->where('poll_id', $poll->id)
                ->where('voter_ip_hash', $ipHash)
                ->exists();

            $pollData = [
                'poll' => $poll,
                'options' => $poll->options,
                'has_voted' => $hasVoted,
                'is_expired' => $isExpired,
                'is_closed' => $isExpired,
            ];
        }

        $subCities = SubCity::orderBy('order')->get();

        return [
            'hero_slides' => $heroSlides,
            'mayor' => $mayor,
            'news' => $news,
            'tenders' => $tenders,
            'emergency_contacts' => $emergencyContacts,
            'poll' => $pollData,
            'sub_cities' => $subCities,
        ];
    }

    /**
     * Public published news with category/search filters & pagination
     */
    public function getPublishedNews(Request $request)
    {
        $query = NewsArticle::published()->with('author')->orderBy('published_at', 'desc');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('title_am', 'like', $s)
                  ->orWhere('title_en', 'like', $s)
                  ->orWhere('title_om', 'like', $s)
                  ->orWhere('content_am', 'like', $s)
                  ->orWhere('content_en', 'like', $s);
            });
        }

        return $query->paginate($request->input('per_page', 9));
    }

    /**
     * Get single news article by slug or id and increment view count
     */
    public function getNewsBySlug(string $slug): ?NewsArticle
    {
        $article = NewsArticle::where('slug', $slug)->orWhere('id', $slug)->first();
        if ($article) {
            $article->increment('views_count');
        }
        return $article;
    }

    /**
     * Public tenders list with status & category filter
     */
    public function getTenders(Request $request)
    {
        // Auto-close expired tenders
        Tender::where('status', 'open')
            ->where('closing_datetime', '<', Carbon::now())
            ->update(['status' => 'closed']);

        $query = Tender::orderByRaw("CASE WHEN status = 'open' THEN 1 ELSE 2 END")
            ->orderBy('closing_datetime', 'asc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        return $query->paginate($request->input('per_page', 12));
    }

    /**
     * Standalone destination detail with related attractions
     */
    public function getDestinationDetail($idOrSlug): ?array
    {
        $item = null;
        if (is_numeric($idOrSlug)) {
            $item = Destination::find($idOrSlug);
        } else {
            $idPrefix = intval(explode('-', $idOrSlug)[0]);
            if ($idPrefix > 0) {
                $item = Destination::find($idPrefix);
            }
            if (!$item) {
                $item = Destination::where('title_en', 'like', '%' . str_replace('-', ' ', $idOrSlug) . '%')->first();
            }
        }

        if (!$item) {
            return null;
        }

        $related = Destination::where('id', '!=', $item->id)
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->limit(4)
            ->get();

        return [
            'destination' => $item,
            'related' => $related,
        ];
    }

    /**
     * Store citizen inquiry contact message
     */
    public function storeCitizenContact(array $data): ContactMessage
    {
        return ContactMessage::create([
            'sender_name' => $data['sender_name'],
            'sender_phone' => $data['sender_phone'],
            'sender_email' => $data['sender_email'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'target_sub_city' => $data['target_sub_city'] ?? null,
            'status' => 'unread',
        ]);
    }
}
