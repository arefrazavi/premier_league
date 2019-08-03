<?php

namespace App\Helpers;

use App\Models\Match;
use Illuminate\Support\Facades\DB;

class CalculationHelper
{
    static function getWeightedRandVal(array $weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }

    static function factorial($number){
        $factorial = 1;
        for ($i = 1; $i <= $number; $i++){
            $factorial = $factorial * $i;
        }
        return $factorial;
    }

}
