<?php

namespace App\Rules;

use App\Support\PublicImage;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PublicImagePath implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        $normalized = PublicImage::normalizeStoredPath($value);

        if ($normalized === null) {
            $fail('Invalid image path. Please upload using the file picker.');

            return;
        }

        if (! PublicImage::exists($normalized)) {
            $fail('Image file not found at '.$normalized.'. Please upload again.');
        }
    }
}
