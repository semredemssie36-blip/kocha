<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $table = 'polls';

    protected $fillable = [
        'question_am',
        'question_en',
        'question_om',
        'description_am',
        'description_en',
        'description_om',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'date',
    ];

    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id')->orderBy('order', 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
