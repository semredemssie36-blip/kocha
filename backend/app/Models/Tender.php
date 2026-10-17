<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tender extends Model
{
    use HasFactory;

    protected $table = 'tenders';

    protected $fillable = [
        'reference_number',
        'title_am',
        'title_en',
        'title_om',
        'category',
        'description_am',
        'description_en',
        'description_om',
        'bid_bond_amount',
        'document_pdf_path',
        'closing_datetime',
        'opening_datetime',
        'status',
    ];

    protected $casts = [
        'bid_bond_amount' => 'decimal:2',
        'closing_datetime' => 'datetime',
        'opening_datetime' => 'datetime',
    ];

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where('closing_datetime', '>=', Carbon::now());
    }
}
