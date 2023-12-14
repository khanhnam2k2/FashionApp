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
            ['id' => 1, 'name' => 'Áo Phông', 'status' => 1, 'created_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Áo Polo', 'status' => 1, 'created_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Quần Jeans', 'status' => 1, 'created_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Áo Sweater', 'status' => 1, 'created_at' => Carbon::now()],
        ];
        DB::table('categories')->insert($data);
    }
}
