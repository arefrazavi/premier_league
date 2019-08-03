<?php

namespace App\Http\Controllers;

use App\Libraries\TeamLib;
use App\Models\Match;
use App\Models\Team;
use App\Libraries\MatchLib;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    public function viewList()
    {
        // Start new season by resetting matches and teams points
        MatchLib::generateMatches();
        TeamLib::resetTeamsPoints();

        $teams = [];
        $matches = Match::where('week', 1)->get();
        foreach ($matches as &$match) {
            $homeTeam = Team::find($match->home_team_id);
            $teams[] = $homeTeam;
            $match->home_team_title = $homeTeam->title;
            $awayTeam = Team::find($match->away_team_id);
            $teams[] = $awayTeam;
            $match->away_team_title = $awayTeam->title;
        }
        unset($match);
        return view('team.list', compact('teams', 'matches'));
    }

    /**
     * Call a function to predict championship probability of each team
     * @param Request $request
     * @return array
     */
    public function predictChampion(Request $request) {
        $week = $request->input('week');
        return TeamLib::predictChampProbs($week);
    }

}
