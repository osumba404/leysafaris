<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'short_description',
        'long_description',
        'duration_days',
        'starting_price',
        'currency',
        'price_note',
        'experience_types',
        'traveler_types',
        'departure_style',
        'highlights',
        'inclusions',
        'exclusions',
        'gallery',
        'hero_image',
        'pricing_notes',
        'practical_info',
        'route_map_image',
        'seo_title',
        'seo_description',
        'is_featured',
        'is_template',
        'status',
        'sort_order',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'starting_price' => 'decimal:2',
            'experience_types' => 'array',
            'traveler_types' => 'array',
            'highlights' => 'array',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_template' => 'boolean',
            'sort_order' => 'integer',
            'view_count' => 'integer',
        ];
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'package_destination');
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class, 'experience_package');
    }

    public function packageDays(): HasMany
    {
        return $this->hasMany(PackageDay::class)->orderBy('sort_order')->orderBy('day_number');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
