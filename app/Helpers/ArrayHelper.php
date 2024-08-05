<?php

namespace App\Helpers;

class ArrayHelper {
    public static function getIndexOfLargestNumber(array $numbers): int {
        if (empty($numbers)) {
            throw new \InvalidArgumentException('The numbers array cannot be empty.');
        }

        $maxIndex = 0;
        $maxValue = $numbers[0];

        foreach ($numbers as $index => $value) {
            if ($value > $maxValue) {
                $maxIndex = $index;
                $maxValue = $value;
            }
        }

        return $maxIndex;
    }
}
