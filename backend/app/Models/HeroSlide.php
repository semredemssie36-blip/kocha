<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $table = 'hero_slides';

    protected $fillable = [
        'tag_am',
        'tag_en',
        'tag_om',
        'title_am',
        'title_en',
        'title_om',
        'subtitle_am',
        'subtitle_en',
        'subtitle_om',
        'button_text_am',
        'button_text_en',
        'button_text_om',
        'button_url',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
    }
}
