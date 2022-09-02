<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $dir = opendir('database/seeders');
        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..' || $file == 'DatabaseSeeder.php' || $file == 'DatabaseSeederAlterations.php' || preg_match('=^[^/?*;:{}\\\\]+TableSeeder\.php$=', $file) == 0) {
                continue;
            }
            
            try {
                $this->call('\Database\Seeders\\'.explode('.',$file)[0]);
            } catch (\Throwable $th) {
                echo $th->getMessage();
            }
        }            
        closedir($dir);
    }
}
