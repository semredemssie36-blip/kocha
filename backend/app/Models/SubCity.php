<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCity extends Model
{
    use HasFactory;

    protected $table = 'sub_cities';

    protected $fillable = [
        'name_am',
        'name_en',
        'name_om',
        'slug',
        'slogan_am',
        'slogan_en',
        'slogan_om',
        'chief_executive_am',
        'chief_executive_en',
        'chief_executive_om',
        'phone',
        'email',
        'office_address_am',
        'office_address_en',
        'office_address_om',
        'description_am',
        'description_en',
        'description_om',
        'kebeles',
        'highlights',
        'photo_path',
        'order',
    ];

    protected $casts = [
        'kebeles' => 'array',
        'highlights' => 'array',
    ];

    public function offices()
    {
        return $this->hasMany(Office::class, 'sub_city_id');
    }
}
