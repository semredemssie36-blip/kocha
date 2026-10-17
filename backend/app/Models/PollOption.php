<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;

    protected $table = 'poll_options';

    protected $fillable = [
        'poll_id',
        'option_text_am',
        'option_text_en',
        'option_text_om',
        'votes_count',
        'order',
    ];

    protected $casts = [
        'votes_count' => 'integer',
        'order' => 'integer',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }
}
