<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $path = database_path('sql/states.sql');

        if (File::exists($path)) {
            DB::unprepared(File::get($path));
        } else {
            echo "SQL file not found!";
        }
    }
}