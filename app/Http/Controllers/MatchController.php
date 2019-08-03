<?php

namespace App\Http\Controllers;

use App\Libraries\MatchLib;
use App\Models\Team;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function playWeek(Request $request) {
        $week = $request->input('week');
        $maxWeek = (Team::count() - 1) * 2 ;
        if ($week > $maxWeek) {
            $results = [
                'error' => 'Season has finished',
            ];
        } else {
            $weekMatches = MatchLib::playWeekMatches($week);
            $teams = Team::orderByRaw('pts DESC, goals_scored - goals_conceded DESC, goals_scored DESC')->get();

            $winner = '';
            if ($week == $maxWeek) {
                $winner = $teams[0]->title;
            }
            $results = [
                'matches' => $weekMatches,
                'teams' => $teams,
                'winner' => $winner
            ];
        }

        return response()->json($results);

    }
}
