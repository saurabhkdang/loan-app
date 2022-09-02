<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            0 => [
                'title' => 'Admin',
            ],
            1 => [
                'title' => 'Frontend User',
            ],
        ]);
    }
}
