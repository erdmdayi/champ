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
        $standing = Standing::with('team')->orderByDesc('points')->get();
        $nextMatch = Matches::query()->where('status', 0)->with('awayTeam', 'homeTeam')->orderBy('id')->limit(2)->get();
        $prediction = (new \App\PreditionHelper())->getTeamPreditions();

        return view('simulation', ['standing' => $standing, 'nextMatch' => $nextMatch, 'prediction' => $prediction]);
    }

    public function simulateAllMatches()
    {
        $matches = Matches::all();
        $fixtures = [];
        foreach ($matches as $match) {
            $homeTeamStanding = Standing::query()->where('team_id', $match->home_team)->first();
            $awayTeamStanding = Standing::query()->where('team_id', $match->away_team)->first();
            $homeTeamScore = rand(0, 5);
            $awayTeamScore = rand(0, 5 - $awayTeamStanding->id);

            $goalDrawn = abs($awayTeamScore - $homeTeamScore);

            $match->home_team_goal = $homeTeamScore;
            $match->away_team_goal = $awayTeamScore;

            if ($homeTeamScore > $awayTeamScore) {
                $homeTeamStanding->points += 3;
                $homeTeamStanding->won($goalDrawn);
                $awayTeamStanding->lose($goalDrawn);
            } elseif ($homeTeamScore < $awayTeamScore) {
                $awayTeamStanding->points += 3;
                $homeTeamStanding->lose($goalDrawn);
                $awayTeamStanding->won($goalDrawn);
            } else {
                $homeTeamStanding->points += 1;
                $awayTeamStanding->points += 1;
                $homeTeamStanding->draw();
                $awayTeamStanding->draw();
            }

            $homeTeamStanding->save();
            $awayTeamStanding->save();
            $match->save();

            $fixtures[$match->id] = [
                'home_team' =>  $homeTeamStanding->team->name,
                'away_team' =>  $awayTeamStanding->team->name,
                'score' =>  $homeTeamScore . ' : ' . $awayTeamScore,
            ];
        }
        $champion = Standing::with('team')->orderByDesc('points')->first();
        return response()->json([
            'success' => true,
            'fixtures' => $fixtures,
            'message' => 'All matches have been played! *** CHAMPION '. strtoupper($champion->team->name) .' ***'
        ]);
    }
}
