<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item')->insert([
            'name' => Str::random(10),
            'cost' => 2,
            'available' => 5,
        ]);
        DB::table('item')->insert([
            'name' => Str::random(10),
            'cost' => 2,
            'available' => 5,
        ]);
        DB::table('item')->insert([
            'name' => Str::random(10),
            'cost' => 2,
            'available' => 5,
        ]);
    }
}
