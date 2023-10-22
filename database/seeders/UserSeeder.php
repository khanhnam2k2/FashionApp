<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'role' => UserRole::ADMIN,
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'created_at' => Carbon::now()
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $id = $i + 1;
            DB::table('users')->insert([
                'id' => $id,
                'role' => UserRole::USER,
                'name' => "User_" . $i,
                'email' => 'user' . $i . '@test.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
