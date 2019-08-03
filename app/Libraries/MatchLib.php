<?php

namespace App\Libraries;

use App\Helpers\CalculationHelper;
use App\Models\Match;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class MatchLib
{
    const MAX_SCORE = 10;
    const MIN_SCORE = 0;
    const HOME_TEAM_STRENGTH = 1;

    /**
     * Generate Random matches for the whole season
     * @return int
     */
    static public function generateMatches() {
        DB::table('matches')->truncate();

        $team_ids = [];
        $teams = DB::table('teams')->select('id')->get();
        $weeks = 2 * (count($teams)-1);
        foreach($teams as $team) {
            $team_ids[$team->id] = $team->id;
        }
        for ($w = 1; $w <= $weeks; $w++) {
            $week_team_ids = $team_ids;
            while(count($week_team_ids) > 1) {
                $match = new Match;
                $match->home_team_id = array_pop($week_team_ids);
                $match->away_team_id = array_rand($week_team_ids, 1);
                $match->week = $w;
                unset($week_team_ids[$match->away_team_id]);
                $match->save();
            }
        }

        return 1;
    }

    /**
     * Stimulate playing matches of a given week
     * and return their random results
     * @param $week
     * @return mixed
     */
    static public function playWeekMatches($week) {
        $possibleScores = [];
        $j = self::MAX_SCORE;
        for ($i = self::MIN_SCORE; $i <= self::MAX_SCORE; $i++) {
            $possibleScores[$i] = $j - $i;
        }

        $weekMatches = Match::where('week', $week)->get();
        foreach ($weekMatches as &$match) {
            $firstScore = CalculationHelper::getWeightedRandVal($possibleScores);
            $secondScore = CalculationHelper::getWeightedRandVal($possibleScores);
            $homeTeam = Team::find($match->home_team_id);
            $awayTeam = Team::find($match->away_team_id);
            $possibleWinners = [
                $homeTeam->id => $homeTeam->strength + self::HOME_TEAM_STRENGTH,
                $awayTeam->id => $awayTeam->strength
            ];
            $winnerTeamId = CalculationHelper::getWeightedRandVal($possibleWinners);
            $maxScore = max($firstScore, $secondScore);
            $minScore = min($firstScore, $secondScore);

            // Store match result
            if ($winnerTeamId == $match->home_team_id) {
                $match->home_score = $maxScore;
                $match->away_score = $minScore;
            } else {
                $match->home_score = $minScore;
                $match->away_score = $maxScore;
            }
            $match->save();


            // Update league (team) table
            if ($match->home_score == $match->min_score) {
                $homeTeam->pts++;
                $awayTeam->pts++;
            } elseif ($match->home_score > $match->min_score) {
                $homeTeam->pts += 3;
            } else {
                $awayTeam->pts += 3;
            }
            $homeTeam->goals_scored += $match->home_score;
            $homeTeam->goals_conceded += $match->away_score;
            $awayTeam->goals_scored += $match->away_score;
            $awayTeam->goals_conceded += $match->home_score;

            $homeTeam->plays++;
            $awayTeam->plays++;

            $homeTeam->save();
            $awayTeam->save();
            $match->home_team_title = $homeTeam->title;
            $match->away_team_title = $awayTeam->title;
        }
        unset($match);

        return $weekMatches;
    }

}
