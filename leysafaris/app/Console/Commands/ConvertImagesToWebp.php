<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SplFileInfo;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:webp
                            {--quality=82 : WebP quality 1-100}
                            {--jpeg-quality=85 : Optimized JPEG quality}
                            {--max-width=1920 : Max width in pixels}
                            {--force : Regenerate even if outputs already exist}
                            {--dir= : Directory (default: public/images)}
                            {--backup : Copy originals to originals/ subfolder first}';

    protected $description = 'Resize and convert images to WebP + optimized JPEG for faster loads';

    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('PHP GD WebP support is not available.');

            return self::FAILURE;
        }

        $dir = $this->option('dir')
            ? base_path($this->option('dir'))
            : public_path('images');

        if (! is_dir($dir)) {
            $this->error("Directory not found: {$dir}");

            return self::FAILURE;
        }

        $files = collect(array_merge(
            glob($dir.'/*.jpg') ?: [],
            glob($dir.'/*.jpeg') ?: [],
            glob($dir.'/*.JPG') ?: [],
            glob($dir.'/*.JPEG') ?: [],
            glob($dir.'/*.png') ?: [],
            glob($dir.'/*.PNG') ?: [],
        ))->unique()->map(fn ($f) => new SplFileInfo($f));

        if ($files->isEmpty()) {
            $this->warn('No images found.');

            return self::SUCCESS;
        }

        $quality = max(1, min(100, (int) $this->option('quality')));
        $jpegQuality = max(1, min(100, (int) $this->option('jpeg-quality')));
        $maxWidth = max(400, (int) $this->option('max-width'));
        $force = (bool) $this->option('force');

        if ($this->option('backup')) {
            $backupDir = $dir.'/originals';
            if (! is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
        }

        $converted = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($files as $file) {
            $source = $file->getPathname();
            $webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $source);

            if (! $force && is_file($webp) && filemtime($webp) >= filemtime($source)) {
                $skipped++;
                continue;
            }

            if ($this->option('backup')) {
                $backupPath = $dir.'/originals/'.$file->getFilename();
                if (! is_file($backupPath)) {
                    copy($source, $backupPath);
                }
            }

            $before = filesize($source);
            $result = $this->optimize($source, $webp, $maxWidth, $quality, $jpegQuality);

            if ($result) {
                $after = filesize($source);
                $webpSize = filesize($webp);
                $this->line(sprintf(
                    '  %s → %s + %s (JPEG %s KB, WebP %s KB)',
                    $file->getFilename(),
                    basename($source),
                    basename($webp),
                    number_format($after / 1024, 0),
                    number_format($webpSize / 1024, 0),
                ));
                $converted++;
            } else {
                $this->warn('  Failed: '.$file->getFilename().' ('.round($before / 1024 / 1024, 1).' MB)');
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done: {$converted} optimized, {$skipped} skipped, {$failed} failed.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function optimize(string $source, string $webpDest, int $maxWidth, int $webpQuality, int $jpegQuality): bool
    {
        $info = @getimagesize($source);
        if ($info === false) {
            return false;
        }

        [$width, $height] = $info;

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($maxWidth / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $src = $this->loadImage($source, $info[2]);
        if ($src === false) {
            return false;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if ($dst === false) {
            imagedestroy($src);

            return false;
        }

        if ($info[2] === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        $jpegOk = match ($ext) {
            'jpg', 'jpeg' => imagejpeg($dst, $source, $jpegQuality),
            'png' => imagepng($dst, $source, 8),
            default => false,
        };

        $webpOk = imagewebp($dst, $webpDest, $webpQuality);
        imagedestroy($dst);

        if ($webpOk && is_file($webpDest) && filesize($webpDest) === 0) {
            @unlink($webpDest);
            $webpOk = false;
        }

        return $jpegOk && $webpOk;
    }

    private function loadImage(string $path, int $type): \GdImage|false
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            default => false,
        };
    }
}
