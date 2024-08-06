<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NumericArrayRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = json_decode($value, true);

        if (!is_array($data)) {
            $fail('The :attribute must be a valid JSON array.');
            return;
        }

        foreach ($data as $item) {
            if (!is_numeric($item)) {
                $fail('The :attribute must be a valid JSON array of numbers.');
                return;
            }
        }
    }
}
