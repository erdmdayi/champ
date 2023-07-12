<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $fillable = [
         'home_team', 'away_team', 'home_team_goal', 'away_team_goal', 'week', 'status'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team');
    }
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team');
    }
}
