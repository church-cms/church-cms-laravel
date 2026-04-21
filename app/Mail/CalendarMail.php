<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Mailtemplate;

class CalendarMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $events;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($events)
    {

        $this->events = $events;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        $template = Mailtemplate::where([['name', 'calendar_event'], ['status', 'active']])->first();
        $subject =  $template->subject;
        $mail_content = $template->mail_content;


        $mail_content = str_replace(":title", $this->events->title, $mail_content);
        $mail_content = str_replace(":location", $this->events->location, $mail_content);
        $mail_content = str_replace(":category", $this->events->category, $mail_content);
        $mail_content = str_replace(":start_date", $this->events->start_date, $mail_content);
        $mail_content = str_replace(":end_date", $this->events->end_date, $mail_content);

        return $this->markdown('emails.mailcontent')
            ->subject($subject)
            ->with([
                'content' => $mail_content,
            ]);
    }
}
