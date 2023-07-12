<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Standing;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function simulateAllMatches()
    {
        $matches = Matches::query()->where('status', false)->orderBy('week')->get();
        $fixtures = [];
        $weeklyCount = 0;
        foreach ($matches as $match) {
            $homeTeamStanding = Standing::query()->where('team_id', $match->home_team)->first();
            $awayTeamStanding = Standing::query()->where('team_id', $match->away_team)->first();
            $homeTeamScore = rand(0, 5);
            $awayTeamScore = rand(0, 5 - $awayTeamStanding->id);

            $goalDrawn = abs($awayTeamScore - $homeTeamScore);

            $match->home_team_goal = $homeTeamScore;
            $match->away_team_goal = $awayTeamScore;
            $match->status = 1;

            if ($homeTeamScore > $awayTeamScore) {
                $homeTeamStanding->won($goalDrawn);
                $awayTeamStanding->lose($goalDrawn);
            } elseif ($homeTeamScore < $awayTeamScore) {
                $homeTeamStanding->lose($goalDrawn);
                $awayTeamStanding->won($goalDrawn);
            } else {
                $homeTeamStanding->draw();
                $awayTeamStanding->draw();
            }

            $homeTeamStanding->save();
            $awayTeamStanding->save();
            $match->save();

            $fixtures[$match->week][$weeklyCount] = [
                'home_team' =>  $homeTeamStanding->team->name,
                'away_team' =>  $awayTeamStanding->team->name,
                'score' =>  $homeTeamScore . ' : ' . $awayTeamScore,
                'week' => $match->week
            ];

            $weeklyCount  = $weeklyCount == 0 ? ($weeklyCount+1) : 0;
        }

        $data['fixture'] = view('components.result', ['fixture' => $fixtures])->render();

        $standing = Standing::with('team')->orderByDesc('points')->orderByDesc('goal_drawn')->get();
        $data['standing'] = view('components.standing', ['standing' => $standing])->render();

        $prediction = (new \App\PreditionHelper())->getTeamPreditions();
        $data['prediction'] = view('components.prediction', ['prediction' => $prediction])->render();

        $nextMatch = Matches::query()->where('status', 0)->with('awayTeam', 'homeTeam')->orderBy('id')->limit(2)->get();
        $data['nextMatch'] = view('components.week', ['nextMatch' => $nextMatch])->render();


        $champion = Standing::with('team')->orderByDesc('points')->orderByDesc('goal_drawn')->first();
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'All matches have been played! *** CHAMPION '. strtoupper($champion->team->name) .' ***'
        ]);
    }

    public function simulateMatch()
    {
        $matches = Matches::query()->where('status', false)->orderBy('week')->limit(2)->get();
        foreach ($matches as $match) {
            $homeTeamStanding = Standing::query()->where('team_id', $match->home_team)->first();
            $awayTeamStanding = Standing::query()->where('team_id', $match->away_team)->first();
            $homeTeamScore = rand(0, 5);
            $awayTeamScore = rand(0, 5 - $awayTeamStanding->id);

            $goalDrawn = abs($awayTeamScore - $homeTeamScore);

            $match->home_team_goal = $homeTeamScore;
            $match->away_team_goal = $awayTeamScore;
            $match->status = 1;

            if ($homeTeamScore > $awayTeamScore) {
                $homeTeamStanding->won($goalDrawn);
                $awayTeamStanding->lose($goalDrawn);
            } elseif ($homeTeamScore < $awayTeamScore) {
                $homeTeamStanding->lose($goalDrawn);
                $awayTeamStanding->won($goalDrawn);
            } else {
                $homeTeamStanding->draw();
                $awayTeamStanding->draw();
            }

            $homeTeamStanding->save();
            $awayTeamStanding->save();
            $match->save();
        }

        $nextMatch = Matches::query()->where('status', 0)->with('awayTeam', 'homeTeam')->orderBy('id')->limit(2)->get();

        $message = "The matches you specified have been played.";
        $champion = false;
        if (count($nextMatch) < 1){
            $champion = Standing::with('team')->orderByDesc('points')->orderByDesc('goal_drawn')->first();
            $message = 'All matches have been played! *** CHAMPION '. strtoupper($champion->team->name) .' ***';
        }

        $data['nextMatch'] = view('components.week', ['nextMatch' => $nextMatch])->render();

        $standing = Standing::with('team')->orderByDesc('points')->orderByDesc('goal_drawn')->get();
        $data['standing'] = view('components.standing', ['standing' => $standing])->render();

        $prediction = (new \App\PreditionHelper())->getTeamPreditions();
        $data['prediction'] = view('components.prediction', ['prediction' => $prediction])->render();

        $data['champion'] = $champion;

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ]);
    }
}
