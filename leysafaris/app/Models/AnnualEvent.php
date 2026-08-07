<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualEvent extends Model
{
    protected $fillable = [
        'package_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'event_date',
        'early_bird_deadline',
        'early_bird_price',
        'regular_price',
        'currency',
        'hero_image',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'early_bird_deadline' => 'date',
            'early_bird_price' => 'decimal:2',
            'regular_price' => 'decimal:2',
            'is_published' => 'boolean',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
