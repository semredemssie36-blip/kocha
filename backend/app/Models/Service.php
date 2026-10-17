<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'office_id',
        'type',
        'title_am',
        'title_en',
        'title_om',
        'slug',
        'summary_am',
        'summary_en',
        'summary_om',
        'content_am',
        'content_en',
        'content_om',
        'estimated_time_am',
        'estimated_time_en',
        'estimated_time_om',
        'fee_amount',
        'icon_name',
        'order',
        'is_active',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
