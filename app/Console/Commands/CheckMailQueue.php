<?php

namespace App\Console\Commands;

use App\Traits\EmailQueueProcess;
use Illuminate\Console\Command;
use App\Jobs\ProcessMailQueue;
use App\Models\MailQueue;
use Exception;
use Log;

class CheckMailQueue extends Command
{
    use EmailQueueProcess;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkmailqueue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Mail Queue';

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
            $now       = date('Y-m-d H:i');

            //  $maillist = MailQueue::whereNull('fired_at')->wheredate('scheduled_at','=',$now)->get();
            $maillist = MailQueue::whereNull('fired_at')->where(\DB::raw("(DATE_FORMAT(scheduled_at,'%Y-%m-%d %H:%i'))"), '<=', $now)->get();

            foreach ($maillist as $list) {
                // $smtp = Smtp::whereStatus(1)->inRandomOrder()->first();

                //   $encryption=null;
                //   if($smtp->encryption!='')
                //   {

                //       $encryption=$smtp->encryption;
                //   }

                //   $transport = (new \Swift_SmtpTransport($smtp->host, $smtp->port))
                //       ->setEncryption($encryption)
                //       ->setUsername($smtp->username)
                //       ->setPassword($smtp->password);

                //   $mailer = app(\Illuminate\Mail\Mailer::class);
                //   $mailer->setSwiftMailer(new \Swift_Mailer($transport));


                //     $mailer->to($list->to_mail)->queue(new ListingMail($list->subject,$list->from_email,$list->from_name,$list->reply_to_email,$list->content));
                ProcessMailQueue::dispatch($list->to_mail, $list->subject, $list->from_email, $list->from_name, $list->reply_to_email, $list->content, $list->id);


                //  $update['fired_at'] = Carbon::now();
                // MailQueue::where('id',$list->id)->update($update);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}
