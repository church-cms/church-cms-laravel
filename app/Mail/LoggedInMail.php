<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Mailtemplate;

class LoggedInMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;



    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $template = Mailtemplate::where([['name', 'login'], ['status', 'active']])->first();
        $subject =  $template->subject;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(":name", $this->user->FullName, $mail_content);


        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
