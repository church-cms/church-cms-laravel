<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class CountriesTableSeeder extends Seeder {

    public function run()
    {

         $path = database_path('sql/countries.sql');

        if (File::exists($path)) {
            DB::unprepared(File::get($path));
        } else {
            echo "SQL file not found!";
        }
    }
}
?>