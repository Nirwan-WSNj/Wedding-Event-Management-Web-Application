<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DecorationSeeder extends Seeder
{
    public function run()
    {
        DB::table('decorations')->insert([
            ['name' => 'Floral Arrangements Basic', 'description' => 'Basic floral arrangements', 'price' => 0],
            ['name' => 'Candle Setups', 'description' => 'Elegant candle setups', 'price' => 0],
            ['name' => 'Welcome Board', 'description' => 'Personalized welcome board', 'price' => 0],
            ['name' => 'Aisle Runner', 'description' => 'Aisle runner for ceremonies', 'price' => 0],
            ['name' => 'Floral Arches', 'description' => 'Beautiful floral arches', 'price' => 0],
            ['name' => 'Chair Covers', 'description' => 'Chair covers for guests', 'price' => 0],
        ]);
    }
}
