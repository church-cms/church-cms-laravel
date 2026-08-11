<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\Bulletin;

class BulletinObserver
{
    /**
     * Handle the bulletin "created" event.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return void
     */
    public function created(Bulletin $bulletin)
    {
        //
        $update=[
            'created_by'=>Auth::id(),
            'updated_by'=>Auth::id(),
        ];

        Bulletin::where('id',$bulletin->id)->update($update);
    }

    /**
     * Handle the bulletin "updated" event.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return void
     */
    public function updated(Bulletin $bulletin)
    {
        //
        $update=[
            'updated_by'=>Auth::id(),
        ];
        
        Bulletin::where('id',$bulletin->id)->update($update);
    }

    /**
     * Handle the bulletin "deleted" event.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return void
     */
    public function deleted(Bulletin $bulletin)
    {
        //
    }

    /**
     * Handle the bulletin "restored" event.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return void
     */
    public function restored(Bulletin $bulletin)
    {
        //
    }

    /**
     * Handle the bulletin "force deleted" event.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return void
     */
    public function forceDeleted(Bulletin $bulletin)
    {
        //
    }
}