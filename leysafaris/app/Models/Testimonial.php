<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'package_id',
        'author_name',
        'author_location',
        'content',
        'rating',
        'source',
        'source_url',
        'is_approved',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
