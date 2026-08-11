<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 
class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key'           => 'sitetitle',
            'name'          => 'Site Title',
            'description'   => 'Site Title to show in Browser Bar',
            'value'         => 'church Social',
            'field'         => '{"name":"value","label":"Value", "title":"Site Title" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'site_description',
            'name'          => 'Site Description',
            'description'   => 'Site Description',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Site Description" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'site_keyword',
            'name'          => 'Meta Keywords',
            'description'   => 'Meta Keywords',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Meta Keywords" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'header_code',
            'name'          => 'Header Code',
            'description'   => 'Header Code',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Header Code" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'footer_code',
            'name'          => 'Footer Code',
            'description'   => 'Footer Code',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Footer Code" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'facebook_title',
            'name'          => 'Facebook Title',
            'description'   => 'Facebook Title',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Facebook Title" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'facebook_description',
            'name'          => 'Facebook Description',
            'description'   => 'Facebook Description',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Facebook Description" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'facebook_url',
            'name'          => 'Facebook Url',
            'description'   => 'Facebook Url',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Facebook Url" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'facebook_image',
            'name'          => 'Facebook Image',
            'description'   => 'Facebook Image',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Facebook Image" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'twitter_title',
            'name'          => 'Twitter Title',
            'description'   => 'Twitter Title',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Twitter Title" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'twitter_description',
            'name'          => 'Twitter Description',
            'description'   => 'Twitter Description',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Twitter Description" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'twitter_url',
            'name'          => 'Twitter Url',
            'description'   => 'Twitter Url',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Twitter Url" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'twitter_image',
            'name'          => 'Twitter Image',
            'description'   => 'Twitter Image',
            'value'         => '',
            'field'         => '{"name":"value","label":"Value", "title":"Twitter Image" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'sitename',
            'name'          => 'Site Name',
            'description'   => 'This site name is used in emails and copyrights',
            'value'         => 'church Social',
            'field'         => '{"name":"value","label":"Value", "title":"Site Title" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'sitelogo',
            'name'          => 'Site Logo',
            'description'   => 'Logo of the website. Recommended Size : 220px (w) x 45px (h)',
            'value'         => 'images/logo.png',
            'field'         => '{"name":"value","label":"Value" ,"type":"browse"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert(
        [
            'key'           => "favicon",
            'name'          => "Favicon",
            'description'   => "Site Favicon",
            'value'         => 'images/favicon.png',
            'field'         => '{"name":"value","label":"Value", "title":"Site Favicon" ,"type":"browse", "disk":"uploads"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),  
        ]);       
      
        DB::table('settings')->insert([
            'key'           => 'maintenance',
            'name'          => 'Maintenance',
            'description'   => 'Maintenance',
            'value'         => 0,
            'field'         => '{"name":"value","label":"Maintenance" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'login_status',
            'name'          => 'login',
            'description'   => 'login',
            'value'         => 1,
            'field'         => '{"name":"value","label":"Userlogin" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);

        DB::table('settings')->insert([
            'key'           => 'register_status',
            'name'          => 'Register Status',
            'description'   => 'Register Status',
            'value'         => 1,
            'field'         => '{"name":"value","label":"Register Status" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 
        ]);
    }
}