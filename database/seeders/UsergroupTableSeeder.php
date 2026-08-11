<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsergroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('user_group')->Insert([
        	'id'=>'1',
        	'name'=>'SiteAdmin',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"), 
       ]);


        DB::table('user_group')->Insert([
        	'id'=>'2',
        	'name'=>'SiteSubadmin',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"), 
       ]);

        DB::table('user_group')->Insert([
            'id'=>'3',
            'name'=>'ChurchAdmin',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"), 
       ]);

        DB::table('user_group')->Insert([
            'id'=>'4',
            'name'=>'ChurchSubadmin',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"), 
       ]);

        DB::table('user_group')->Insert([
            'id'=>'5',
            'name'=>'ChurchMember',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"), 
       ]);

         DB::table('user_group')->Insert([
            'id'=>'6',
            'name'=>'Preacher',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"), 
       ]);
    }
}
