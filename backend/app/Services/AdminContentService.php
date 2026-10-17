<?php

namespace App\Services;

use App\Models\NewsArticle;
use App\Models\Tender;
use App\Models\EmergencyContact;
use App\Models\Destination;
use App\Models\ContactMessage;
use App\Models\Office;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminContentService
{
    /**
     * Get aggregate municipal dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'news_count' => NewsArticle::count(),
            'published_news' => NewsArticle::where('status', 'published')->count(),
            'pending_drafts' => NewsArticle::where('status', 'pending')->count(),
            'active_tenders' => Tender::where('status', 'open')->count(),
            'total_offices' => Office::count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
            'projects_count' => Project::count(),
            'destinations_count' => Destination::count(),
            'staff_count' => User::count(),
        ];
    }

    /**
     * Fetch news articles with role-based visibility filter
     */
    public function getFilteredNews($user, ?string $status = null, ?string $category = null, int $perPage = 15)
    {
        $query = NewsArticle::with('author')->orderBy('created_at', 'desc');

        // If author, show their own articles + all published ones
        if ($user && $user->role === 'author') {
            $query->where(function ($q) use ($user) {
                $q->where('author_id', $user->id)
                  ->orWhere('status', 'published');
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        return $query->paginate($perPage);
    }

    /**
     * Create news article according to actor role
     */
    public function createNewsArticle($user, array $data): NewsArticle
    {
        // Enforce Municipal Editorial Governance:
        // Authors can only submit 'draft' or 'pending'. Only Super Admin / Editor can directly publish.
        $status = $data['status'] ?? 'draft';
        if ($user->role === 'author' && !in_array($status, ['draft', 'pending'])) {
            $status = 'pending';
        }

        $slugBase = $data['title_en'] ?? $data['title_am'] ?? 'news';
        $slug = Str::slug($slugBase) . '-' . time();

        return NewsArticle::create([
            'author_id' => $user->id,
            'title_am' => $data['title_am'],
            'title_en' => $data['title_en'] ?? null,
            'title_om' => $data['title_om'] ?? null,
            'slug' => $slug,
            'category' => $data['category'] ?? 'General',
            'summary_am' => $data['summary_am'] ?? null,
            'summary_en' => $data['summary_en'] ?? null,
            'summary_om' => $data['summary_om'] ?? null,
            'content_am' => $data['content_am'],
            'content_en' => $data['content_en'] ?? null,
            'content_om' => $data['content_om'] ?? null,
            'thumbnail_path' => $data['thumbnail_path'] ?? null,
            'is_featured' => !empty($data['is_featured']),
            'status' => $status,
            'views_count' => 0,
            'published_at' => ($status === 'published') ? now() : null,
        ]);
    }

    /**
     * Transition article workflow state (Super Admin / Editor approval)
     */
    public function setNewsStatus(NewsArticle $article, string $status): NewsArticle
    {
        $article->status = $status;
        if ($status === 'published' && !$article->published_at) {
            $article->published_at = now();
        }
        $article->save();

        return $article;
    }

    /**
     * Create staff account (Super Admin only)
     */
    public function createStaffUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
        ]);
    }
}
