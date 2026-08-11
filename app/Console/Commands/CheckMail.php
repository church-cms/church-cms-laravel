<?php

namespace App\Console\Commands;

use App\Events\PrayerRequestReminderMailEvent;
use App\Events\BirthdayReminderMailEvent;
use App\Events\ReminderMailEvent;
use Illuminate\Console\Command;
use App\Traits\EventProcess;
use App\Models\Reminder;
use App\Models\Events;
use App\Models\Church;
use Exception;
use Log;

class CheckMail extends Command
{

    use EventProcess;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Mail';

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
            $now       = date('Y-m-d');
            $queuelist = Reminder::where([['queue_status','=','queue'],['via','=','mail']])->where('executed_at','=',$now)->get();
           
            foreach($queuelist as $queue)
            {  
                $church = Church::IsActive($queue->church_id)->exists();
                if($church)
                {
                    if(env('MAIL_STATUS')=='on')
                    {
                        $update['queue_status']='deliver';
                        Reminder::where('id',$queue->id)->update($update);

                        if($queue->entity_name=="App\\Models\\Events")
                        {  
                            event(new ReminderMailEvent($queue));
                            $events=Events::where('id',$queue->entity_id)->first();
                            if($events->repeats==1)
                            {
                                $this->sendToReminderEvent($events,$now,'next'); 
                            }
                        }
                        elseif($queue->entity_name == "App\\Models\\User")
                        {   
                            event(new BirthdayReminderMailEvent($queue));
                        } 
                        elseif ($queue->entity_name == "App\\Models\\PrayerRequest") 
                        {
                            event(new PrayerRequestReminderMailEvent($queue));
                        }
                    }     
                }
                else
                {
                    return FALSE;
                }
            }  
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }      
}