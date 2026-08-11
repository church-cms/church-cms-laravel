<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\EventProcess;
use App\Models\Userprofile;
use App\Models\User;
use Exception;
use Log;

class CheckBirthday extends Command
{

    use EventProcess;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkbirthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Birthday';

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
            $birthdays = Userprofile::with('user')
                            ->WhereRaw("DATE_FORMAT(date_of_birth, '%m-%d') = DATE_FORMAT(now() + INTERVAL 2 DAY,'%m-%d')")
                            ->ByRole(5)
                            ->get();

            foreach($birthdays as $birthday)
            {   
                $admin = User::where('church_id',$birthday->church_id)->ByRole(3)->first();  
                $church_id = $birthday->church_id;
                $entity_id = $birthday->user->id;
                $user_name = $birthday->user->name;
                $user_email = $birthday->user->email;
                $date_of_birth = date('d-m-Y',strtotime($birthday->date_of_birth));
                $month = date('m-d',strtotime('-2 days',strtotime($birthday->date_of_birth)));
                $current_year=date('Y');
                $birth_date = $current_year.'-'.$month;
                $mail = $admin->email;
                $mobile_no = $admin->mobile_no; 
                $anniversary_date=null; 
                $marriage_start_date=null;

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