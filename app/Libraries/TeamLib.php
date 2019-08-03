<?php

namespace App\Libraries;

use App\Helpers\CalculationHelper;
use App\Models\Match;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamLib
{
    /**
     * Calculate the probability of winning the title for each team
     * based on their current points and scores using Poisson distribution
     * @return array
     */
    static function predictChampProbs() {
        ini_set('precision', 10);

        $teams = Team::all();
        $leagueHomeScored = DB::table('matches')->avg('home_score');
        $leagueAwayScored = DB::table('matches')->avg('away_score');
        $goals = [];
        $estimatePts = [];
        foreach ($teams as $team) {
            $goals[$team->id]['homeScored'] = DB::table('matches')
                ->where('home_team_id', $team->id)
                ->avg('home_score') ;
            $goals[$team->id]['homeConceded'] = DB::table('matches')
                ->where('home_team_id', $team->id)
                ->avg('away_score');
            $goals[$team->id]['awayScored'] = DB::table('matches')
                ->where('away_team_id', $team->id)
                ->avg('away_score');
            $goals[$team->id]['awayConceded'] = DB::table('matches')
                ->where('away_team_id', $team->id)
                ->avg('home_score');
            $estimatePts[$team->id] = $team->pts;
        }

        $leftMatches = Match::whereNull('home_score')->orderBy('week')->get();
        foreach ($leftMatches as $match) {
            $homeAttack =  $leagueHomeScored ? $goals[$match->home_team_id]['homeScored'] / $leagueHomeScored : 0;
            $homeDefence = $leagueAwayScored ? $goals[$match->home_team_id]['homeConceded'] / $leagueAwayScored : 0;
            $awayAttack = $leagueAwayScored ? $goals[$match->home_team_id]['awayScored'] / $leagueAwayScored : 0;
            $awayDefence = $leagueHomeScored ? $goals[$match->home_team_id]['awayConceded'] / $leagueHomeScored : 0;
            $lambdaH = $homeAttack * $awayDefence * $leagueHomeScored;
            $lambdaA = $awayAttack * $homeDefence * $leagueAwayScored;

            // Calculate the probabilities that home and away teams scores from 1 to 10
            $probs = [];
            $maxScore = MatchLib::MAX_SCORE;
            for ($x = 0; $x <= $maxScore; $x++) {

                // Poisson distribution for x occurrences of the event (goal),
                // λ is the average rate and e is the Euler’s constant
                $poisson = (pow($lambdaH, $x) * pow(M_E, -$lambdaH))
                    / CalculationHelper::factorial($x);
                $probs[$match->home_team_id][$x] = $poisson;

                $poisson = (pow($lambdaA, $x) * pow(M_E, -$lambdaA))
                    / CalculationHelper::factorial($x);
                $probs[$match->away_team_id][$x] = $poisson;
            }

            $probHWin = 0;
            $probAWin = 0;
            for($i = 1; $i <= $maxScore; $i++) {
                for($j = 0; $j < $i; $j++) {
                    $probHWin += $probs[$match->home_team_id][$i] * $probs[$match->away_team_id][$j];
                    $probAWin += $probs[$match->away_team_id][$i] * $probs[$match->home_team_id][$j];
                }
            }
            $probDraw = 0;
            for($i = 1; $i <= $maxScore; $i++) {
                $probDraw += $probs[$match->home_team_id][$i] * $probs[$match->away_team_id][$i];
            }
            $estimatePts[$match->home_team_id] += $probHWin * 3 + $probDraw * 1;
            $estimatePts[$match->away_team_id] += $probAWin * 3 + $probDraw * 1;
        }

        $maxPts = array_sum($estimatePts);
        $winProbs = [];
        foreach ($estimatePts as $id => $estimatePt) {
            $winProbs[$id] = round(($estimatePt / $maxPts) * 100, 2);
        }

        return $winProbs;
    }

    static function resetTeamsPoints() {
        DB::table('teams')
            ->update([
                'pts' => 0,
                'plays' => 0,
                'wins' => 0,
                'draws' => 0,
                'loses' => 0,
                'goals_scored' => 0,
                'goals_conceded' => 0,
        ]);
    }

}
