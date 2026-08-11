<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Mailtemplate;
use App\Models\Sermon;
 
class SermonMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sermon;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Sermon $sermon)
    {
        //dump($sermon);
       $this->sermon=$sermon;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        //dump('jnhk');
          $template = Mailtemplate::where([['name','sermon_event'],['status','active']])->first();
        $subject =  $template->subject;
        $mail_content = $template->mail_content;

        
        $mail_content = str_replace(":title",$this->sermon->title,$mail_content);
        $mail_content = str_replace(":description",$this->sermon->description,$mail_content);
        
       
        return $this->markdown('emails.mailcontent')
                    ->subject($subject)
                    ->with([
                        'content' => $mail_content,
                        ]);
    }
}
