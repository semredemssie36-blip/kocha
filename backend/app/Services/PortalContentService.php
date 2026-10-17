<?php

namespace App\Services;

use App\Models\NewsArticle;
use App\Models\Tender;
use App\Models\EmergencyContact;
use App\Models\Mayor;
use App\Models\HeroSlide;
use App\Models\Poll;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PortalContentService
{
    /**
     * Get unified homepage bundle using Eloquent models.
     */
    public function getHomeBundle(Request $request): array
    {
        // 1. Active Hero Slides
        $heroSlides = HeroSlide::active()->get();

        // 2. Current Mayor Keynote
        $mayor = Mayor::current()->first();

        // 3. Top Published News
        $news = NewsArticle::published()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // 4. Active Open Tenders (Auto-close expired)
        Tender::where('status', 'open')
            ->where('closing_datetime', '<', Carbon::now())
            ->update(['status' => 'closed']);

        $tenders = Tender::orderByRaw("CASE WHEN status = 'open' THEN 1 ELSE 2 END")
            ->orderBy('closing_datetime', 'asc')
            ->limit(2)
            ->get();

        // 5. Active Emergency Hotlines
        $emergencyContacts = EmergencyContact::active()->get();

        // 6. Active Citizen Poll
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

        // 7. Sub-cities
        $subCities = DB::table('sub_cities')->orderBy('order')->get();

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
     * Get standalone destination details and related attractions.
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
}
