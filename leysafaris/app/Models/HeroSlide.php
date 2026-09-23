<?php

namespace App\Models;

use App\Support\HeroMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'media_type',
        'image',
        'video_path',
        'video_url',
        'eyebrow',
        'title',
        'subtitle',
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

    public function isVideo(): bool
    {
        return ($this->media_type ?? HeroMedia::TYPE_IMAGE) === HeroMedia::TYPE_VIDEO;
    }

    public function isEmbed(): bool
    {
        return ($this->media_type ?? HeroMedia::TYPE_IMAGE) === HeroMedia::TYPE_EMBED;
    }

    public function isImage(): bool
    {
        return ($this->media_type ?? HeroMedia::TYPE_IMAGE) === HeroMedia::TYPE_IMAGE;
    }

    public function embedSrc(): ?string
    {
        return HeroMedia::normalizeEmbedUrl($this->video_url);
    }

    public function videoSrc(): ?string
    {
        return HeroMedia::videoUrl($this->video_path);
    }
}
