<?php

namespace App;

use App\Models\Matches;
use App\Models\Standing;
use App\Models\Team;

class FixtureHelper
{
    public function generateFixture(): array
    {
        $teams = Team::query()->where('status', true)->pluck('name')->toArray();
        $fixture = array();
        $totalTeams = count($teams);
        $weeks = ($totalTeams-1) * 2;

        for ($week = 1; $week <= $weeks; $week++) {
            $matches = array();
            for ($i = 0; $i < $totalTeams / 2; $i++) {
                $homeTeamIndex = $i;
                $awayTeamIndex = $totalTeams - 1 - $i;

                $match['home_team'] = $teams[$homeTeamIndex];
                $match['away_team'] = $teams[$awayTeamIndex];

                if ($week > 3) {
                    $match['home_team'] = $teams[$awayTeamIndex];
                    $match['away_team'] = $teams[$homeTeamIndex];
                }

                $matches[] = $match;
            }

            $lastTeam = array_pop($teams);
            array_splice($teams, 1, 0, $lastTeam);

            $fixture[$week] = $matches;
        }
        $this->resetStandingList();
        $this->generateMatches($fixture);
        return $fixture;
    }

    public function generateMatches($fixture){
        $teams = Team::query()->where('status', true)->get();
        Matches::truncate();
        foreach($fixture as $week => $matches){
            foreach($matches as $match) {
                $homeTeamStr = $match['home_team'];
                $awayTeamStr = $match['away_team'];
                $homeTeam = $teams->first(function ($team) use ($homeTeamStr) {
                    return $team->name === $homeTeamStr;
                });
                $awayTeam = $teams->first(function ($team) use ($awayTeamStr) {
                    return $team->name === $awayTeamStr;
                });
                Matches::create([
                    'home_team' =>  $homeTeam->id,
                    'away_team' =>  $awayTeam->id,
                    'week' => $week
                ]);
            }
        }
        return true;
    }

    public function resetStandingList()
    {
        $standing = Standing::all();
        foreach($standing as $item){
            $item->played = 0;
            $item->won = 0;
            $item->draw = 0;
            $item->lose = 0;
            $item->goal_drawn = 0;
            $item->points = 0;
            $item->save();
        }
    }
}
