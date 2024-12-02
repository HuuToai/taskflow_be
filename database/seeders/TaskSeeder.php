<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'text' => 'Dự án 1',
                'start_date' => '2024-04-01 00:00:00',
                'duration' => 5,
                'progress' => 0.8,
                'parent' => 0,
                'open' => true,
                'assignee' => null,
                'status' => 'pending',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-01 00:00:00 + 5 days'))
            ],
            [
                'text' => 'Task #1',
                'start_date' => '2024-04-06 00:00:00',
                'duration' => 4,
                'progress' => 0.5,
                'parent' => 1,
                'open' => true,
                'assignee' => null,
                'status' => 'in_progress',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-06 00:00:00 + 4 days'))
            ],
            [
                'text' => 'Task #2',
                'start_date' => '2024-04-05 00:00:00',
                'duration' => 6,
                'progress' => 0.7,
                'parent' => 1,
                'open' => true,
                'assignee' => null,
                'status' => 'completed',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-05 00:00:00 + 6 days'))
            ],
            [
                'text' => 'Task #3',
                'start_date' => '2024-04-07 00:00:00',
                'duration' => 2,
                'progress' => 0,
                'parent' => 1,
                'open' => true,
                'assignee' => null,
                'status' => 'pending',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-07 00:00:00 + 2 days'))
            ],
            [
                'text' => 'Task #1.1',
                'start_date' => '2024-04-05 00:00:00',
                'duration' => 5,
                'progress' => 0.34,
                'parent' => 2,
                'open' => true,
                'assignee' => null,
                'status' => 'in_progress',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-05 00:00:00 + 5 days'))
            ],
            [
                'text' => 'Task #1.2',
                'start_date' => '2024-04-11 00:00:00',
                'duration' => 4,
                'progress' => 0.5,
                'parent' => 2,
                'open' => true,
                'assignee' => null,
                'status' => 'pending',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-11 00:00:00 + 4 days'))
            ],
            [
                'text' => 'Task #2.1',
                'start_date' => '2024-04-07 00:00:00',
                'duration' => 5,
                'progress' => 0.2,
                'parent' => 3,
                'open' => true,
                'assignee' => null,
                'status' => 'on_hold',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-07 00:00:00 + 5 days'))
            ],
            [
                'text' => 'Task #2.2',
                'start_date' => '2024-04-06 00:00:00',
                'duration' => 4,
                'progress' => 0.9,
                'parent' => 3,
                'open' => true,
                'assignee' => null,
                'status' => 'completed',
                'end_date' => date('Y-m-d H:i:s', strtotime('2024-04-06 00:00:00 + 4 days'))
            ]
        ]);
    }
}
