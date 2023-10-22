<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'category 1', 'status' => 1, 'created_at' => Carbon::now()],
            ['id' => 2, 'name' => 'category 2', 'status' => 1, 'created_at' => Carbon::now()],
            ['id' => 3, 'name' => 'category 3', 'status' => 0, 'created_at' => Carbon::now()],
            ['id' => 4, 'name' => 'category 4', 'status' => 1, 'created_at' => Carbon::now()],
        ];
        DB::table('categories')->insert($data);
    }
}
