<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    protected $fillable = [
        'label',
        'route_name',
        'url',
        'sort_order',
        'is_active',
        'is_highlight',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_highlight' => 'boolean',
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

    public function isCurrent(): bool
    {
        if (! $this->route_name) {
            return false;
        }

        $pattern = str_ends_with($this->route_name, '.index')
            ? substr($this->route_name, 0, -6).'.*'
            : $this->route_name;

        return request()->routeIs($pattern) || request()->routeIs($this->route_name);
    }
}
