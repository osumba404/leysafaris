<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Experience extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'excerpt',
        'description',
        'image',
        'duration_hours',
        'starting_price',
        'currency',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_hours' => 'integer',
            'starting_price' => 'decimal:2',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'experience_package');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
