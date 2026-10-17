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
        'phone',
        'email',
        'building_location_am',
        'building_location_en',
        'building_location_om',
        'room_number',
        'working_hours_am',
        'working_hours_en',
        'working_hours_om',
        'mandate_summary_am',
        'mandate_summary_en',
        'mandate_summary_om',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
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
