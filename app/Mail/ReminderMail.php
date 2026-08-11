<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Mailtemplate;
use App\Models\Reminder;

class ReminderMail extends Mailable implements ShouldQueue
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
       //dd($queue);
        $this->queue = $queue;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd('kjhhsdf');
        $template = Mailtemplate::where([['name','event_reminder'],['status','active']])->first();
        //dd($template);
        $subject =  $template->subject;
        //dd($subject);
        $mail_content = $template->mail_content;
       //dd($mail_content);
        $mail_content = str_replace(":church_name",$this->queue->church->name,$mail_content);
        $mail_content = str_replace(":title",$this->queue->events->title,$mail_content);
        $mail_content = str_replace(":description",$this->queue->events->description,$mail_content);
        $mail_content = str_replace(":location",$this->queue->events->location,$mail_content);
        $mail_content = str_replace(":start_date",$this->queue->events->start_date,$mail_content);
        $mail_content = str_replace(":end_date",$this->queue->events->end_date,$mail_content);
             
        return $this->markdown('emails.mailcontent')
                        ->subject($subject)
                        ->with([
                            'content' => $mail_content,
                            ]);
    }
}
