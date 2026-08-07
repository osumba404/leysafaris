<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageDay extends Model
{
    protected $fillable = [
        'package_id',
        'day_number',
        'title',
        'location',
        'morning',
        'afternoon',
        'evening',
        'narrative',
        'meals',
        'accommodation',
        'accommodation_note',
        'activities',
        'travel_notes',
        'wildlife_highlights',
        'image',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'day_number' => 'integer',
            'meals' => 'array',
            'activities' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
