<?php

namespace App\Http\Controllers;

use App\Libraries\MatchLib;
use App\Models\Team;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function playWeek(Request $request) {
        $week = $request->input('week');
        $weekMatches = MatchLib::playWeekMatches($week);
        $teams = Team::orderByDesc('pts')->get();
        return response()->json([
            'matches' => $weekMatches,
            'teams' => $teams
        ]);

    }
}
