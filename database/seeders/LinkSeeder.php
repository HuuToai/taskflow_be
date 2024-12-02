<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('links')->insert([
            ['id' => 1, 'type' => 0, 'source' => 1, 'target' => 2],
            ['id' => 2, 'type' => 0, 'source' => 1, 'target' => 3],
            ['id' => 3, 'type' => 0, 'source' => 1, 'target' => 4],
            ['id' => 4, 'type' => 0, 'source' => 2, 'target' => 5],
            ['id' => 5, 'type' => 0, 'source' => 2, 'target' => 6],
            ['id' => 6, 'type' => 0, 'source' => 3, 'target' => 7],
            ['id' => 7, 'type' => 0, 'source' => 3, 'target' => 8],
        ]);
    }
}
