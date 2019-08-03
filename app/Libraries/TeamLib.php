<?php

namespace App\Libraries;

use App\Helpers\CalculationHelper;
use App\Models\Match;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamLib
{
    const MAX_SCORE = 10;
    const MIN_SCORE = 0;
    const HOME_TEAM_STRENGTH = 1;

    static public function predictChampProbs(int $week) {
        $winProbs = [];
        $teams = Team::all();
        $totalPlays = 2 * (count($teams) - 1);
        $avgHomeScored = DB::table('matches')->avg('home_score');
        $avgAwayScored = DB::table('matches')->avg('away_score');

        foreach ($teams as $team) {
            $homeAttack = DB::table('matches')->where('home_team_id', $team->id)->avg('home_score') / $avgHomeScored;
            $homeDefence = DB::table('matches')->where('home_team_id', $team->id)->avg('away_score') / $avgAwayScored;
            $awayAttack = DB::table('matches')->where('away_team_id', $team->id)->avg('away_score') / $avgAwayScored;
            $awayDefence = DB::table('matches')->where('away_team_id', $team->id)->avg('home_score') / $avgHomeScored;
            $homeLambda = $homeAttack * $homeDefence * $avgHomeScored;
            $awayLambda = $awayAttack * $awayDefence * $avgAwayScored;

            $homeWeeksPlayed = DB::table('matches')->where('home_team_id', $team->id)->count();
            $homeWeeksLeft =  ($totalPlays / 2) - $homeWeeksPlayed;
            $awayWeeksLeft = $team->plays - $homeWeeksPlayed;
            $homeProb = pow($homeLambda, $homeWeeksLeft) * pow(M_E, -$homeLambda)
                / CalculationHelper::factorial($homeWeeksLeft);
            $awayProb = pow($awayLambda, $awayWeeksLeft) * pow(M_E, -$awayLambda)
                / CalculationHelper::factorial($awayWeeksLeft);

            $winProbs[$team->title] = ($homeProb + $awayProb) / 2;
        }

        return $winProbs;
    }


}
