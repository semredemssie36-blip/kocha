<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_am' => $this->title_am,
            'title_en' => $this->title_en,
            'title_om' => $this->title_om,
            'slug' => $this->slug,
            'reference_number' => $this->reference_number,
            'category' => $this->category,
            'bid_security_amount' => $this->bid_security_amount,
            'description_am' => $this->description_am,
            'description_en' => $this->description_en,
            'description_om' => $this->description_om,
            'document_path' => $this->document_path,
            'opening_date' => $this->opening_date ? $this->opening_date->toIso8601String() : null,
            'closing_date' => $this->closing_date ? $this->closing_date->toIso8601String() : null,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
