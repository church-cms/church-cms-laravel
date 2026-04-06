<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run() {
        // This seeder is now empty
        // User creation happens via the InstallerSeeder -> church:install-data command
        // which creates the admin user based on installer input
        //
        // If you need to create a default system admin user for development,
        // do it manually or create a separate DevSeeder
    }
}
