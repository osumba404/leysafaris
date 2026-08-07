<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'country',
        'region',
        'excerpt',
        'description',
        'best_time',
        'signature_wildlife',
        'hero_image',
        'gallery',
        'facts',
        'latitude',
        'longitude',
        'seo_title',
        'seo_description',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'facts' => 'array',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_destination');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
