<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceDocument extends Model
{
    use HasFactory;

    protected $table = 'resources';

    protected $fillable = [
        'office_id',
        'title_am',
        'title_en',
        'title_om',
        'category',
        'year',
        'file_path',
        'file_extension',
        'file_size_kb',
        'download_count',
        'is_public',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'file_size_kb' => 'integer',
        'year' => 'integer',
        'is_public' => 'boolean',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
