<?php

namespace App\Rules;

use App\Support\HeroMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PublicVideoPath implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        if (HeroMedia::normalizeVideoPath($value) === null) {
            $fail('Invalid video path. Please upload using the file picker.');

            return;
        }

        if (! HeroMedia::videoExists($value)) {
            $fail('Video file not found. Please upload again.');
        }
    }
}
