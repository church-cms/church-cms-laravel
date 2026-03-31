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
            [
                'church_id'=>'1',
                'usergroup_id'=>'5',
                'ref_id'=>'1',
                'name'=>'MaxPop',
                'email'=>'maxpop@church.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'mobile_no' => '9874561230',

            ] );

            DB::table( 'userprofiles' )->insert(
                [
                    'church_id'=>'1',
                    'usergroup_id'=>'5',
                    'ref_id'=>'1',
                    'name'=>'JenniferPop',
                    'email'=>'jenniferpop@church.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                    'mobile_no' =>'1234567890',

                ] );
                */
            }
        }
