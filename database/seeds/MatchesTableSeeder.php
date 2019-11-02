<?php

use Illuminate\Database\Seeder;

class MatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tournamentModel = new \App\Models\Tournament();
        $tournament = $tournamentModel->first();
        $matchTime = Carbon\Carbon::now();
        $status = \App\Enums\MatchStatus::$SCHEDULED;
        $teamIds = array_map(function ($team) {
            return $team['id'];
        }, $tournament->teams->toArray());
        $schedules = \App\Utilities\TournamentMatchScheduler::schedule($teamIds);
        $matches = [];
        foreach ($schedules as $index => $schedule) {
            $match = [
                'id' => $index + 1,
                'team1_id' => $schedule['team1'],
                'team2_id' => $schedule['team2'],
                'tournament_id' => $tournament->id,
                'match_time' => $matchTime,
                'status' => $status
            ];
            array_push($matches, $match);
        }
        \Illuminate\Support\Facades\DB::table('matches')->insert($matches);
    }
}
