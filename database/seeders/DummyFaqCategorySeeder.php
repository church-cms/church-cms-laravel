<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use Illuminate\Support\Facades\DB;

class DummyFaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $churches = Church::where('status',1)->get();

        foreach ($churches as $church)
        {
        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Bulletins',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Calendar',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Church Details',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Contact Requests',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Email Blaster',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Gallery',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Groups',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Help Requests',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Media Files',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Messages',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Offerings',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Prayer Requests',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Quotes \ Bible Verse',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Reports',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Sermons',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Users',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);

        	DB::table('faq_categories')->Insert([
        		'church_id'    	=> 	$church->id,
        		'name'          => 	'Video Chat Room',
        		'status'        =>	1,
        		'created_at'    => 	date("Y-m-d H:i:s"),
        		'updated_at'	=> 	date("Y-m-d H:i:s"),
      		]);
        }
    }
}
