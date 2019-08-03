<?php

namespace App\Helpers;

use App\Models\Match;
use Illuminate\Support\Facades\DB;

class CalculationHelper
{
    /**
     * Return a random value from a set favoring those having more weight
     * @param array $weightedValues
     * @return int|string
     */
    static function getWeightedRandVal(array $weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $key => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $key;
            }
        }
    }

    /**
     * Calculate factorial of a number
     * @param $number
     * @return int
     */
    static function factorial($number) {
        if ($number == 0) {
            return 1;
        }
        $factorial = 1;
        for ($i = 1; $i <= $number; $i++){
            $factorial *= $i;
        }
        return $factorial;
    }

}
