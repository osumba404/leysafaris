<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PublicImagePath implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            $fail('External image URLs are not allowed. Please upload a file.');

            return;
        }

        if (! str_starts_with($value, 'images/')) {
            $fail('Invalid image path. Please upload using the file picker.');

            return;
        }

        if (! is_file(public_path($value))) {
            $fail('Image file not found. Please upload again.');
        }
    }
}
