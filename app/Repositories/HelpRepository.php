<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\Help;
use Carbon\Carbon;

class HelpRepository implements HelpRepositoryInterface
{
    /**
     * Get's a post by it's ID
     *
     * @param int
     * @return collection
     */
    public function getHelps($church_id)
    {
        return Help::where('church_id',$church_id);
    }

    public function createHelp($church_id,$data)
    {
            $help = new Help;

            $help->church_id        = $church_id;
            $help->user_id          = Auth::id();
            $help->title            = $data->title;
            $help->description      = $data->description;
            $help->contact_details  = $data->contact_details;
            $help->status           = "pending";
            $help->save();

            return $help;
    }

    public function updateHelp($id,$data)
    {
            $help = Help::find($id);

            $help->status = $data->status;
            if($data->status == 'approve')
            {
                $help->expired_at = Carbon::now()->addDays((int)$data->expired_at)->format('Y-m-d');
                $help->closed_by  = Auth::id();
            }
            else
            {
                $help->comments = $data->comments;
            }

            $help->save();
            
            return $help;
    }

    public function deleteHelp($id)
    {
            $help = Help::find($id);
            $help->delete();
    }

    public function showHelp($id)
    {
            $help = Help::find($id);
            return $help;
    }

    
}