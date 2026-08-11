<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GroupCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('group_category')->Insert([
           'id'			=> '1',
           'category'   => 'bible_studies',
           'name'	    => 'Bible Studies',
           'status'		=> 'active',  
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),   
       ]);

        DB::table('group_category')->Insert([
           'id'			=> '2',
           'category'   => 'clubs',
           'name'	    => 'Clubs',
           'status'		=> 'active',
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),
       ]);

        DB::table('group_category')->Insert([
           'id'			=> '3',
           'category'   => 'committees',
           'name'	    => 'Committees',
           'status'     => 'active',
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),            
       ]);

        DB::table('group_category')->Insert([
           'id'			=> '4',
           'category'   => 'office_bearers',
           'name'	    => 'Office Bearers',
           'status'     => 'active',
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),           
        ]);

        DB::table('group_category')->Insert([
           'id'			=> '5',
           'category'   => 'small_groups',
           'name'	    => 'Small Groups',
           'status'     => 'active',
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),            
        ]);

        DB::table('group_category')->Insert([
           'id'			=> '6',
           'category'   => 'wards/districts',
           'name'	    => 'Wards/Districts',
           'status'     => 'active',
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),           
        ]);

        DB::table('group_category')->Insert([
           'id'			=> '7',
           'category'   => 'others',
           'name'	    => 'Others',
           'status'     => 'active',
           'created_at' => date("Y-m-d H:i:s"),
           'updated_at' => date("Y-m-d H:i:s"),           
        ]);    
    }
}