<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Mailtemplate;
use App\Models\User;

class ChangePassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
      * The userdetails instance.
      *
      * @var user
      */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailtemplate = Mailtemplate::where([['name','change_password'],['status','active']])->first();
        
        $subject =  $mailtemplate->subject;
        $mail_content = $mailtemplate->mail_content;
        $mail_content = str_replace(":name",$this->user->FullName,$mail_content);

        return $this->markdown('emails.mailcontent')
                  ->subject($subject)
                  ->with([
                       'content' => $mail_content,
                      ]);
    }
}