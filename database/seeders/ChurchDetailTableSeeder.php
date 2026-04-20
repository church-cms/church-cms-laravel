<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ChurchDetail;
class ChurchDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  
        $churchs = Church::where('status',1)->get();
        foreach ($churchs as $church) 
        {
            $admin = User::where([['church_id',$church->id],['usergroup_id',3]])->first();

        $detail=ChurchDetail::where('church_id',$church->id)->get();

           if(count($detail)>0){
             ChurchDetail::where('church_id',$church->id)->delete();
           }

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'church_logo',
            	'meta_value'	=> '-',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'short_summary',
            	'meta_value'	=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'long_summary',
            	'meta_value'	=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'quotes',
            	'meta_value'	=> '-',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'phone',
            	'meta_value'	=> '9874012563',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'email',
            	'meta_value'	=> $admin->email,
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'address',
            	'meta_value'	=> $church->address,
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'latitude',
            	'meta_value'	=> '9.9252007',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'longitude',
            	'meta_value'	=> '78.1197754',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

            DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'website',
                'meta_value'    => 'https://www.church.com',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'facebook',
            	'meta_value'	=> 'https://www.facebook.com/Test-page-112456983890996/',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'twitter',
            	'meta_value'	=> 'https://twitter.com/Twitter?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

          	DB::table('church_details')->Insert([
            	'church_id'    	=> $church->id,
            	'meta_key'      => 'instagram',
            	'meta_value'	=> 'https://instagram.com/meme_coding?igshid=mw432c7aip81',
            	'created_at'    => date("Y-m-d H:i:s"),
            	'updated_at'    => date("Y-m-d H:i:s"),    
          	]);

            DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'site_title',
                'meta_value'    => 'church Social',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);

            DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'site_description',
                'meta_value'    => 'Site Description',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);

             DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'site_keyword',
                'meta_value'    => 'Site keyword',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);

                DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'favicon',
                'meta_value'    => '-',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);

              DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'header_code',
                'meta_value'    => 'Header Code',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);

            DB::table('church_details')->Insert([
                'church_id'     => $church->id,
                'meta_key'      => 'footer_code',
                'meta_value'    => 'Footer Code',
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),    
            ]);


            
        }
    }
}