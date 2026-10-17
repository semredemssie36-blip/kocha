<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_am' => $this->name_am,
            'name_en' => $this->name_en,
            'name_om' => $this->name_om,
            'phone_number' => $this->phone_number,
            'alternative_phone' => $this->alternative_phone,
            'category' => $this->category,
            'order' => $this->order,
            'is_toll_free' => (bool) $this->is_toll_free,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
