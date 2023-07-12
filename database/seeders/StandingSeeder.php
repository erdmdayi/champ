<?php

namespace Database\Seeders;

use App\Models\Standing;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::query()->where('status', true)->get();
        foreach ($teams as $team){
            Standing::create([
                'team_id' => $team->id
            ]);
        }
    }
}
