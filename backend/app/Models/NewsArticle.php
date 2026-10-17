<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    use HasFactory;

    protected $table = 'news_articles';

    protected $fillable = [
        'author_id',
        'title_am',
        'title_en',
        'title_om',
        'slug',
        'category',
        'summary_am',
        'summary_en',
        'summary_om',
        'content_am',
        'content_en',
        'content_om',
        'thumbnail_path',
        'is_featured',
        'status',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
