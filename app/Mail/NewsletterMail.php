<?php

namespace App\Mail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Mailtemplate;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sub, $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $message)
    {
        //
        $this->sub = $subject;
        $this->msg = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = Mailtemplate::where([['name', 'news_letter'], ['status', 'active']])->first();
        $subject = $this->sub;
        $mail_content = $template->mail_content;

        $mail_content = str_replace(":message", $this->msg, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
