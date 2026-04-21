<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\SendPushNotification;
use App\Models\Reminder;
use App\Models\Church;
use Exception;
use App\Events\BirthdayPushEvent;
use Log;

class CheckNotification extends Command
{
    use SendPushNotification;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checknotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Notification';

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
            $queuelist = Reminder::where([['queue_status', '=', 'queue'], ['via', '=', 'notification']])->where('executed_at', '=', $now)->get();

            foreach ($queuelist as $queue) {
                $church = Church::IsActive($queue->church_id)->exists();
                if ($church == TRUE) {
                    $update['queue_status'] = 'deliver';
                    Reminder::where('id', $queue->id)->update($update);

                    if ($queue->user->platform_token != null) {
                        event(new BirthdayPushEvent($queue));
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
