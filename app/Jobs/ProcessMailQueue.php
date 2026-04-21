<?php

namespace App\Jobs;

use App\Models\User;
use App\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\ListingMail;
use Carbon\Carbon;
use App\Models\Smtp;
use App\Models\MailQueue;

class ProcessMailQueue extends Job implements ShouldQueue
{

  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $subject, $from_email, $from_name, $reply_to_email, $content, $to_mail, $id;
  /**
   * Create a new job instance.
   *
   * @param  User  $user
   * @return void
   */
  public function __construct($to_mail, $subject, $from_email, $from_name, $reply_to_email, $content, $id)
  {

    $this->to_mail = $to_mail;
    $this->subject = $subject;
    $this->from_email = $from_email;
    $this->from_name = $from_name;
    $this->reply_to_email = $reply_to_email;
    $this->content = $content;
    $this->id = $id;
  }

  /**
   * Execute the job.
   *
   * @param  Mailer  $mailer
   * @return void
   */
  public function handle()
  {

    try {

      // $mailer->send('emails.maillist', ['content'=>$this->content], function ($m) {
      //     //
      //     $m->subject($this->subject);
      //     $m->from($this->from_email,$this->from_name);
      //           //  ->to($this->to_mail)
      //     $m->replyTo($this->reply_to_email);
      // });

      $smtp = Smtp::whereStatus(1)->inRandomOrder()->first();
      $encryption = null;
      if ($smtp->encryption != '') {

        $encryption = $smtp->encryption;
      }
      //dump($smtp);
      /* $transport = (new \Swift_SmtpTransport($smtp->host, $smtp->port))
                        ->setEncryption($encryption)
                        ->setUsername($smtp->username)
                        ->setPassword($smtp->password);

                    $mailer = app(\Illuminate\Mail\Mailer::class);
                    $mailer->setSwiftMailer(new \Swift_Mailer($transport));*/ //imp


      //                   $subject=$this->subject;
      // $from_email=$this->from_email;
      // $from_name=$this->from_name;
      // $reply_to_email=$this->reply_to_email;
      // $to_email=$this->to_mail;



      //   $mailer->send('emails.maillist', ['content' => $this->content], function ($m1) use ($to_email,$subject,$from_email,$from_name,$reply_to_email) {
      //     $m1->from($from_email,$from_name);
      //     $m1->replyTo($reply_to_email);
      //     $m1->to($to_email)->subject($subject);
      // });
      // $mailer->to($this->to_mail)->send(new ListingMail($this->subject,$this->from_email,$this->from_name,$this->reply_to_email,$this->content));


      //new code
      /*  $configuration = [
                         'smtp_host'        => $smtp->host,
                         'smtp_port'        => $smtp->port,
                         'smtp_username'    => $smtp->username,
                         'smtp_password'    => $smtp->password,
                         'smtp_encryption'  => $encryption,
                         'from_email'       => $this->from_email,
                         'from_name'        => $this->from_name,
                         
                        ]; */
      $configuration = [
        'smtp_host'        => env('MAIL_HOST'),
        'smtp_port'        => env('MAIL_PORT'),
        'smtp_username'    => env('MAIL_USERNAME'),
        'smtp_password'    => env('MAIL_PASSWORD'),
        'smtp_encryption'  => env('MAIL_ENCRYPTION'),
        'from_email'       => env('MAIL_FROM_ADDRESS'),
        'from_name'        => $this->from_name,

      ];

      // dump($configuration);
      $mailer = app()->makeWith('emails.maillist', $configuration);

      $mailer->to($this->to_mail)->send(new ListingMail($this->subject, $this->from_email, $this->from_name, $this->reply_to_email, $this->content, $this->id));
      $update['fired_at'] = Carbon::now();
      //dump($update['fired_at']);
      $update['smtp'] = $smtp->username;
      MailQueue::where('id', $this->id)->update($update);
    } catch (Exception $e) {
      dd($e->getMessage());
    }
  }
}
