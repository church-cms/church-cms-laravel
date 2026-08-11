<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Mailtemplate;
use App\Models\Reminder;

class BirthdayReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $queue;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reminder $queue)
    {
        //
        $this->queue = $queue;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = Mailtemplate::where([['name','birthday_reminder'],['status','active']])->first();

        $mail_content = $template->mail_content;

        $subject =  $template->subject;
        
        $mail_content = str_replace(":message",$this->queue->data['message'],$mail_content);
          
        return $this->markdown('emails.mailcontent')
                        ->subject($subject)
                        ->with([
                            'content' => $mail_content,
                            ]);
    }
}
