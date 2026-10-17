<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_name' => $this->sender_name,
            'sender_phone' => $this->sender_phone,
            'sender_email' => $this->sender_email,
            'subject' => $this->subject,
            'message' => $this->message,
            'target_sub_city' => $this->target_sub_city,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
