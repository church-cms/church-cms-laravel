<?php

namespace App\Listeners;

use App\Events\SermonLinkEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SermonLinkMail;
use App\Models\User;


class SermonLinkListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SermonLinkEvent  $link
     * @return void
     */
    public function handle(SermonLinkEvent $link)
    {
      //dd($link->link);
        $users=User::where('church_id',$link->link->church_id)->ByRole('5')->get();
        //dd($link->link->church_id);
         foreach ($users as $user) 
         {
            //dd('kjsdhj');
            //dump($user->email);
            Mail::to($user->email)->queue(new SermonLinkMail($link->link));
                   
        }
    }
}
