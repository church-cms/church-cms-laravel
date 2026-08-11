<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\SermonLink;
use App\Models\Mailtemplate;

class SermonLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $link;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        //dd($link);
       $this->link=$link;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        //dd('jnhk');
          $template = Mailtemplate::where([['name','sermon_link'],['status','active']])->first();
        $subject =  $template->subject;
        $mail_content = $template->mail_content;


        if($this->link->type =='audio')
        {
            $message = "New audio has been published.. Listen Now";

         }
         else if($this->link->type == 'video')
         {
             $message = "New video has been published.. Watch Now";
         }
         else
         {
             $message = "New document has been published.. Read Now";
         }


        $mail_content = str_replace(":message",$message,$mail_content);

        $mail_content = str_replace(":title",$this->link->sermons->title,$mail_content);

        $mail_content = str_replace(":type",$this->link->type,$mail_content);

        $mail_content = str_replace(":location",$this->link->location,$mail_content);
        
       
        return $this->markdown('emails.mailcontent')
                    ->subject($subject)
                    ->with([
                        'content' => $mail_content,
                        ]);
    }
}
