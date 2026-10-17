<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_am' => $this->title_am,
            'title_en' => $this->title_en,
            'title_om' => $this->title_om,
            'slug' => $this->slug,
            'category' => $this->category,
            'summary_am' => $this->summary_am,
            'summary_en' => $this->summary_en,
            'summary_om' => $this->summary_om,
            'content_am' => $this->content_am,
            'content_en' => $this->content_en,
            'content_om' => $this->content_om,
            'thumbnail_path' => $this->thumbnail_path,
            'status' => $this->status,
            'views_count' => $this->views_count,
            'published_at' => $this->published_at ? $this->published_at->toIso8601String() : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'role' => $this->author->role,
            ] : null,
        ];
    }
}
