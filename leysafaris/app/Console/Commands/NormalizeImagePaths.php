<?php

namespace App\Console\Commands;

use App\Models\AnnualEvent;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\HeroSlide;
use App\Models\Package;
use App\Models\PackageDay;
use App\Models\Setting;
use App\Support\PublicImage;
use Illuminate\Console\Command;

class NormalizeImagePaths extends Command
{
    protected $signature = 'images:normalize-paths {--dry-run : Show changes without saving}';

    protected $description = 'Convert full image URLs in the database to relative images/ paths';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $changes = 0;

        $updateScalar = function (string $label, ?string $before, ?string $after) use (&$changes, $dryRun): void {
            if ($before === $after || ($before !== null && $after === null)) {
                return;
            }

            $changes++;
            $this->line(($dryRun ? '[dry-run] ' : '').$label);
            $this->line('  before: '.$before);
            $this->line('  after:  '.$after);
        };

        foreach (HeroSlide::query()->cursor() as $slide) {
            $normalized = PublicImage::normalizeStoredPath($slide->image);
            if ($normalized !== $slide->image) {
                $updateScalar("hero_slides #{$slide->id}", $slide->image, $normalized);
                if (! $dryRun && $normalized !== null) {
                    $slide->update(['image' => $normalized]);
                }
            }
        }

        foreach (Package::query()->cursor() as $record) {
            $this->normalizeRecord($record, 'hero_image', $updateScalar, $dryRun);
            $this->normalizeGallery($record, $updateScalar, $dryRun);
        }

        foreach (Destination::query()->cursor() as $record) {
            $this->normalizeRecord($record, 'hero_image', $updateScalar, $dryRun);
            $this->normalizeGallery($record, $updateScalar, $dryRun);
        }

        foreach (Experience::query()->cursor() as $record) {
            $this->normalizeRecord($record, 'image', $updateScalar, $dryRun);
        }

        foreach (BlogPost::query()->cursor() as $record) {
            $this->normalizeRecord($record, 'featured_image', $updateScalar, $dryRun);
        }

        foreach (AnnualEvent::query()->cursor() as $record) {
            $this->normalizeRecord($record, 'hero_image', $updateScalar, $dryRun);
        }

        foreach (PackageDay::query()->whereNotNull('image')->cursor() as $day) {
            $this->normalizeRecord($day, 'image', $updateScalar, $dryRun);
        }

        $logo = Setting::get('site_logo');
        if (is_string($logo)) {
            $normalized = PublicImage::normalizeStoredPath($logo);
            if ($normalized !== $logo && $normalized !== null) {
                $updateScalar('settings site_logo', $logo, $normalized);
                if (! $dryRun) {
                    Setting::query()->where('key', 'site_logo')->update(['value' => $normalized]);
                }
            }
        }

        $this->info($changes === 0 ? 'No image paths needed normalization.' : "Processed {$changes} change(s).");

        return self::SUCCESS;
    }

    private function normalizeRecord(object $record, string $column, callable $updateScalar, bool $dryRun): void
    {
        $before = $record->{$column};
        if (! is_string($before) || $before === '') {
            return;
        }

        $normalized = PublicImage::normalizeStoredPath($before);
        if ($normalized === $before || $normalized === null) {
            return;
        }

        $updateScalar(class_basename($record)." #{$record->id}", $before, $normalized);

        if (! $dryRun) {
            $record->update([$column => $normalized]);
        }
    }

    private function normalizeGallery(object $record, callable $updateScalar, bool $dryRun): void
    {
        if (! isset($record->gallery) || ! is_array($record->gallery)) {
            return;
        }

        $fixed = array_values(array_filter(array_map(
            fn ($item) => is_string($item) ? PublicImage::normalizeStoredPath($item) : null,
            $record->gallery
        )));

        if ($fixed === $record->gallery) {
            return;
        }

        $updateScalar(class_basename($record)." #{$record->id} gallery", json_encode($record->gallery), json_encode($fixed));

        if (! $dryRun) {
            $record->update(['gallery' => $fixed]);
        }
    }
}
