<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Mailtemplate;
use App\Models\User;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
      * The token instance.
      *
      * @var Token
      */
    protected $token;

    /**
      * The userdetails instance.
      *
      * @var userdetails
      */
    protected $userdetails;

    /**
      * Create a new message instance.
      *
      * @return void
      */
    public function __construct(User $userdetails, $token)
    {
        $this->userdetails = $userdetails;
        $this->token = $token;
    }

    /**
      * Build the message.
      *
      * @return $this
      */
    public function build()
    {
        $mailtemplate = Mailtemplate::where([['name','reset_password'],['status','active']])->first();
        
        $url = url('/password/reset/'.$this->token);
        $subject =  $mailtemplate->subject;
        $mail_content = $mailtemplate->mail_content;
        $mail_content = str_replace(":name",$this->userdetails->FullName,$mail_content);
        $mail_content = str_replace(":resetlink",$url,$mail_content);
        $mail_content = str_replace(":url",$url,$mail_content);

        return $this->markdown('emails.mailcontent')
                  ->subject($subject)
                  ->with([
                       'content' => $mail_content,
                      ]);
    }
}