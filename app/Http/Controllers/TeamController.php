<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Team;
use App\Libraries\MatchLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{

    public function viewList(int $week = 0)
    {
        // Start new season by resetting matches
        MatchLib::generateMatches();

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
        return view('team.list', compact('teams', 'matches', 'week'));
    }

}
