<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Mailtemplate;

class RoomInvitationMail extends Mailable implements ShouldQueue
{
   use Queueable, SerializesModels;

    /**
     * The contact instance.
     *
     * @var Contact
     */
    protected $user,$info;

    /**
     * Create a new content instance.
     *
     * @return void
     */
    public function __construct($user,$info)
    {
        $this->user = $user;
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->user->email;

        $mailtemplate = Mailtemplate::where([['name','room_invitation'],['status','active']])->first();
        
        $message= $mailtemplate->mail_content;
        $subject= $mailtemplate->subject;
        $message= str_replace(':name',$this->user->FullName, $message);
        $message = str_replace(':title',$this->info->name, $message);
        $message = str_replace(':description',$this->info->description, $message);
        $message = str_replace(':message','<a href="'.url('/download').'">Download App To Join Video Room</a>', $message);

        return $this->markdown('emails.mailcontent')
                   ->subject($subject)
                   ->with([
                       'content' => $message,
                       ])
                      ->withSwiftMessage(function ($message) use($email) {
                                 $message->getHeaders()
                      ->addTextHeader('user_email', $email);
        });
    }
}