<?php

namespace App;

use App\Models\Matches;
use App\Models\Standing;
use App\Models\Team;

class PreditionHelper
{
    public function getTeamPreditions(){
        $teams = Standing::with('team')->get();

        $remainedPoints = 3 * (6 - $teams[1]['played']);
        $firstTeamPoint   = $teams[1]['points'];

        $rawPrediction = [];
        foreach ($teams as $number => $item) {
            $rawPrediction[$item->team->name] = $this->calculateTeamPreditions($item, $number, $remainedPoints, $firstTeamPoint);
        }

        return $this->calculateChanceInPercentage($rawPrediction);
    }

    public function calculateTeamPreditions($item, $number, $remainedPoints, $topTeamPoint)
    {
        if ($remainedPoints + $item['points'] < $topTeamPoint) {
            return 0;
        }
        $homeChance = 0;
        $awayChance = 0;

        $matches = Matches::query()->where('home_team', $item->team_id)->orWhere('away_team', $item->team_id)->get();

        foreach ($matches as $match) {
            if ($match->home_team == $item['team_id']) {
                $homeChance += 2;
            }

            if ($match->away_team == $item['team_id']) {
                $awayChance += 1;
            }
        }

        $chanceByRemainedMatches = ($homeChance + $awayChance);
        $chanceIncludingCurrentRank = $chanceByRemainedMatches - ($number / 2);
        $chanceIncludingPointsDifference = $chanceIncludingCurrentRank - (($topTeamPoint - $item['points']) / 2);
        return $chanceIncludingPointsDifference > 0 ? $chanceIncludingPointsDifference : 0;
    }

    public function calculateChanceInPercentage($rawPrediction)
    {
        $onePointPercent = 100 / array_sum($rawPrediction);

        $chanceInPercentage = [];
        foreach ($rawPrediction as $teamId => $teamChance) {
            $chanceInPercentage[$teamId] = round($teamChance * $onePointPercent, 2);
        }

        return $chanceInPercentage;
    }
}
