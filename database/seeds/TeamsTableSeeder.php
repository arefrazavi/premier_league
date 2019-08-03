<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->insert([
            [
                'title' => 'Chelsea',
                'strength' => 2
            ],
            [
                'title' => 'Arsenal',
                'strength' => 1
            ],
            [
                'title' => 'Manchester City',
                'strength' => 4
            ],
            [
                'title' => 'Liverpool',
                'strength' => 4
            ]
        ]);
    }
}
