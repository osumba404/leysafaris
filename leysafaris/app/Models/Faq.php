<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'category',
        'question',
        'answer',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public static function categoryLabel(string $category): string
    {
        return match ($category) {
            'booking' => 'Booking & Quotes',
            'travel' => 'Travel Planning',
            'safari' => 'On Safari',
            'payment' => 'Payments & Insurance',
            'practical' => 'Practical Information',
            default => 'General',
        };
    }
}
