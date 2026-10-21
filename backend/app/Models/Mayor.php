<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mayor extends Model
{
    use HasFactory;

    protected $table = 'mayors';

    protected $fillable = [
        'name_am',
        'name_en',
        'name_om',
        'title_am',
        'title_en',
        'title_om',
        'portrait_path',
        'phone',
        'bio_am',
        'bio_en',
        'bio_om',
        'biography_am',
        'biography_en',
        'biography_om',
        'welcome_message_am',
        'welcome_message_en',
        'welcome_message_om',
        'term_start',
        'term_end',
        'is_current',
        'order',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
