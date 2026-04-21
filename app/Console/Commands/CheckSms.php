<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\SmsProcess;
use App\Models\Reminder;
use App\Models\Church;
use Exception;
use log;

class CheckSms extends Command
{

    use SmsProcess;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checksms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Sms';

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

        try {
            $now       = date('Y-m-d');
            $queuelist = Reminder::where([['queue_status', '=', 'queue'], ['via', '=', 'sms']])->where('executed_at', '=', $now)->get();

            foreach ($queuelist as $queue) {
                $church = Church::IsActive($queue->church_id)->exists();
                if ($church == TRUE) {
                    if (env('SMS_STATUS') == 'on') {
                        $update['queue_status'] = 'deliver';
                        Reminder::where('id', $queue->id)->update($update);

                        if ($queue->entity_name == "App\\Models\\Events") {
                            $this->sendSmsNotification($queue->to, $queue->data['date'], $queue->data['location']);
                        } elseif ($queue->entity_name == "App\\Models\\GroupLink") {
                            $this->sendUserNotifyGroup($queue->to, $queue->data['message']);
                        }
                    }
                } else {
                    return FALSE;
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}
