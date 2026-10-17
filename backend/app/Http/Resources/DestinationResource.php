<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_am' => $this->title_am,
            'title_en' => $this->title_en,
            'title_om' => $this->title_om,
            'slug' => $this->slug,
            'category' => $this->category,
            'description_am' => $this->description_am,
            'description_en' => $this->description_en,
            'description_om' => $this->description_om,
            'location_am' => $this->location_am,
            'location_en' => $this->location_en,
            'location_om' => $this->location_om,
            'image_url' => $this->image_url,
            'gallery_images' => is_array($this->gallery_images) ? $this->gallery_images : json_decode($this->gallery_images ?? '[]', true),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'opening_hours' => $this->opening_hours,
            'entry_fee' => $this->entry_fee,
            'contact_phone' => $this->contact_phone,
            'is_featured' => (bool) $this->is_featured,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
