<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'office_id',
        'title_am',
        'title_en',
        'title_om',
        'slug',
        'description_am',
        'description_en',
        'description_om',
        'budget_etb',
        'funding_source',
        'contractor_name',
        'supervising_engineer',
        'progress_percent',
        'start_date',
        'target_completion_date',
        'status',
        'image_before',
        'image_after',
    ];

    protected $casts = [
        'budget_etb' => 'decimal:2',
        'progress_percent' => 'integer',
        'start_date' => 'date',
        'target_completion_date' => 'date',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
