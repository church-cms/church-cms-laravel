<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\SendMessageEvent;
use App\Events\SinglePushEvent;
use App\Traits\SmsProcess;
use App\Models\SendMail;
use Carbon\Carbon;
use Exception;
use Log;

class CheckSendMail extends Command
{
    use SmsProcess;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checksendmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Send Mail';

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
        //
        try
        {
            $now = date('Y-m-d H:i:s');
            $sendmails = SendMail::where('is_executed',0)->where('executed_at','<=',$now)->get();

            foreach($sendmails as $sendmail)
            {
                $update['is_executed']  = 1;
                $update['executed_at']  = Carbon::now();

                SendMail::where('id',$sendmail->id)->update($update);

                if($sendmail->mode == 'mail')
                {
                    event(new SendMessageEvent($sendmail));
                }
                elseif($sendmail->mode == 'notification')
                {
                    $data=[];

                    $data['school_id']  = $sendmail->church_id;
                    $data['message']    = 'New Message Received';
                    $data['type']       = 'private message';
                        
                    event(new SinglePushEvent($data));
                }
                elseif($sendmail->mode == 'sms')
                {
                    $this->sendPrivateMessage($sendmail->to,$sendmail->message);
                }

                $fired_at['status']     = "delivered";
                $fired_at['fired_at']   = Carbon::now();

                SendMail::where('id',$sendmail->id)->update($fired_at);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}