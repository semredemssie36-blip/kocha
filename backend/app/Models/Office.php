<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $table = 'offices';

    protected $fillable = [
        'sub_city_id',
        'name_am',
        'name_en',
        'name_om',
        'slug',
        'head_name_am',
        'head_name_en',
        'head_name_om',
        'head_title_am',
        'head_title_en',
        'head_education_am',
        'head_education_en',
        'head_email',
        'phone',
        'additional_phones',
        'email',
        'facebook_url',
        'telegram_url',
        'youtube_url',
        'building_location_am',
        'building_location_en',
        'building_location_om',
        'room_number',
        'room_breakdown',
        'facility_network',
        'working_hours_am',
        'working_hours_en',
        'working_hours_om',
        'mandate_summary_am',
        'mandate_summary_en',
        'mandate_summary_om',
        'vision_am',
        'vision_en',
        'vision_om',
        'mission_am',
        'mission_en',
        'mission_om',
        'values_am',
        'values_en',
        'values_om',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'values_am' => 'array',
        'values_en' => 'array',
        'values_om' => 'array',
        'additional_phones' => 'array',
        'facility_network' => 'array',
        'room_breakdown' => 'array',
    ];

    public function subCity()
    {
        return $this->belongsTo(SubCity::class, 'sub_city_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'office_id');
    }
}
