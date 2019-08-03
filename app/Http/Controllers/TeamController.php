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
        $teams = Team::all();
        return view('team.list', compact('teams'));
    }

    public function startSeason() {
        MatchLib::generateMatches();
        MatchLib::playWeekMatches(1);
        //$this->viewList(1);
    }


}
