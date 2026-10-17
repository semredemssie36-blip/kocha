<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'sender_name',
        'sender_phone',
        'sender_email',
        'subject',
        'message',
        'target_sub_city',
        'status',
        'admin_notes',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }
}
