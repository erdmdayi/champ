<?php

namespace App\Http\Controllers;

use App\FixtureHelper;
use App\Models\Matches;
use App\Models\Standing;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index()
    {
        $teams = Team::query()->where('status', true)->get();
        return view('index', ['teams' => $teams]);
    }

    public function fixture()
    {
        $fixture = (new \App\FixtureHelper)->generateFixture();

        return view('fixture', ['fixture' => $fixture]);
    }

    public function simulation()
    {
        $standing = Standing::with('team')->orderByDesc('points')->orderByDesc('goal_drawn')->get();
        $nextMatch = Matches::query()->where('status', 0)->with('awayTeam', 'homeTeam')->orderBy('id')->limit(2)->get();
        $prediction = (new \App\PreditionHelper())->getTeamPreditions();

        return view('simulation', ['standing' => $standing, 'nextMatch' => $nextMatch, 'prediction' => $prediction]);
    }
}
