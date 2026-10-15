<?php

namespace App\Services;

use App\Models\AttributeScore;

class AttributeScoringService
{
    public function calculate(
        float $value,
        AttributeScore $config
    ): ?float {
        $min = $config->min_value;
        $max = $config->max_value;

        if ($min === null || $max === null || $max <= $min) {
            return null;
        }

        $ratio = ($value - $min) / ($max - $min);

        if (! $config->higher_is_better) {
            $ratio = 1 - $ratio;
        }

        $ratio = max(0, min(1, $ratio));

        return round(1 + ($ratio * 9), 2);
    }
}
