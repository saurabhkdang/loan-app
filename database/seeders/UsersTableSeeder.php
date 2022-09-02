<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(1)->create();
        DB::table('users')->insert([
            0 => [
                'name' => 'Saurabh Dang',
                'email' => 'saurabh.dang@technoscore.net',
                'role' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('saurabhdang'), // password
                'remember_token' => \Str::random(10),
            ],
            /* 1 => [
                'name' => 'Sachin',
                'email' => 'sachin@technoscore.net',
                'role' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make('sachin'), // password
                'remember_token' => \Str::random(10),
                'api_token' => \Str::random(60)
            ],
            2 => [
                'name' => 'Rahul',
                'email' => 'rahul@technoscore.net',
                'role' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make('rahul'), // password
                'remember_token' => \Str::random(10),
                'api_token' => \Str::random(60)
            ] */
        ]);
    }
}
