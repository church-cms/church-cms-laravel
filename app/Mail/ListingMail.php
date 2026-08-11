<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;


class ListingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject, $from_email, $from_name, $reply_to_email, $content, $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $from_email, $from_name, $reply_to_email, $content, $id = null)
    {
        $this->subject          =   $subject;
        $this->from_email       =   $from_email;
        $this->from_name        =   $from_name;
        $this->reply_to_email   =   $reply_to_email;
        $this->content          =   $content;
        $this->id               =   $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id = $this->id;
        return $this->subject($this->subject)->from($this->from_email, $this->from_name)->replyTo($this->reply_to_email)->markdown('emails.maillist')->with(['content' => $this->content])->withSwiftMessage(function ($message) use ($id) {
            $message->getHeaders()->addTextHeader('X-Model-ID', $id);
        });
    }
}
