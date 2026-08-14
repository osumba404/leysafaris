<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = [
        'group',
        'label',
        'route_name',
        'url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function href(): string
    {
        if ($this->route_name && \Route::has($this->route_name)) {
            return route($this->route_name);
        }

        return $this->url ?? '#';
    }

    public static function groupLabel(string $group): string
    {
        return match ($group) {
            'travel_info' => 'Travel Info',
            default => 'Explore',
        };
    }
}
