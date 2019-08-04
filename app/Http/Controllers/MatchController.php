<?php

namespace App\Http\Controllers;

use App\Libraries\MatchLib;
use App\Models\Team;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    /**
     * Stimulate all the remaining weekly matches and return the final results
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function playAllWeeks(Request $request) {
        $maxWeek = (Team::count() - 1) * 2;
        $currentWeek = $request->input('week');

        if ($currentWeek > $maxWeek) {
            return response()->json([
                'error' => 'Season has finished',
            ]);
        }

        $weekMatches = [];
        for($week = $currentWeek; $week <= $maxWeek; $week++) {
            $weekMatches = MatchLib::playWeekMatches($week);
        }

        $teams = Team::orderByRaw('pts DESC, goals_scored - goals_conceded DESC, goals_scored DESC')->get();
        $winner = $teams[0]->title;

        return response()->json([
            'matches' => $weekMatches,
            'teams' => $teams,
            'winner' => $winner
        ]);
    }

    /**
     * Play weekly matches and
     * return match results and updated league table to view
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function playWeek(Request $request) {
        $week = $request->input('week');
        $maxWeek = (Team::count() - 1) * 2 ;
        if ($week > $maxWeek) {
            return response()->json([
                'error' => 'Season has finished',
            ]);
        }

        $weekMatches = MatchLib::playWeekMatches($week);
        $teams = Team::orderByRaw('pts DESC, goals_scored - goals_conceded DESC, goals_scored DESC')->get();

        $winner = '';
        if ($week == $maxWeek) {
            $winner = $teams[0]->title;
        }

        return response()->json([
            'matches' => $weekMatches,
            'teams' => $teams,
            'winner' => $winner
        ]);
    }
}
