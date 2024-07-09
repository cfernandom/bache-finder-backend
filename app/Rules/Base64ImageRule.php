<?php

namespace App\Rules;

use App\Helpers\Base64Helper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64ImageRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$value) return;

        $image = Base64Helper::getImage($value);

        if(empty($image)) {
            $fail("The $attribute must be a valid base64 encoded image.");
        }
    }
}
