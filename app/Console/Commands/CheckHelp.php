<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Help;
use App\Models\User;
use Exception;
use Log;

class CheckHelp extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkhelp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Help';

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
            $now = date('Y-m-d');
            $queuelist = Help::where('status','approve')->where('expired_at','=',$now)->get();

            foreach($queuelist as $queue)
            {
                $user = User::where([['church_id',$queue->church_id],['usergroup_id',3]])->first();
               
                $update['status']='close';
                $update['closed_by']=$user->id;
                Help::where('id',$queue->id)->update($update);
            }       
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}