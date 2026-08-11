<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\EventProcess;
use App\Models\Userprofile;
use App\Models\User;
use Exception;
use Log;

class CheckAnniversary extends Command
{

    use EventProcess;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkanniversary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Anniversary';

    /**
     * Create a new command instance.
     *
     * @return void
     */
  
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try
        {
            $anniversarys = Userprofile::with('user')
                            ->WhereRaw("DATE_FORMAT(marriage_start_date, '%m-%d') = DATE_FORMAT(now() + INTERVAL 2 DAY,'%m-%d')")
                            ->ByRole(5)
                            ->get();

            foreach($anniversarys as $anniversary)
            {   
                $admin = User::where('church_id',$anniversary->church_id)->ByRole(3)->first();  
                $church_id = $anniversary->church_id;
                $entity_id = $anniversary->user->id;
                $user_name = $anniversary->user->name;
                $user_email = $anniversary->user->email;
                $marriage_start_date = date('d-m-Y',strtotime($anniversary->marriage_start_date));
                $month = date('m-d',strtotime('-2 days',strtotime($anniversary->marriage_start_date)));
                $current_year=date('Y');
                $anniversary_date = $current_year.'-'.$month;
                $mail = $admin->email;
                $mobile_no = $admin->mobile_no;
                $birth_date=null; 
                $date_of_birth=null;

                $this->adminBirthdayReminder($church_id,$user_name,$user_email,$entity_id,$marriage_start_date,$date_of_birth,$mobile_no,$mail,$anniversary_date,$birth_date);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}