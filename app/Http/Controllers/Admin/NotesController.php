<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NotesRequest;
use Illuminate\Http\Request;
use App\Traits\NotesProcess;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Notes;
use Exception;
use Log;

/**
 * NotesController
 *
 * Manages notes and annotations on entities (events, prayers, etc.).
 * Handles note creation, updates, and deletion for entity-level documentation.
 * Supports polymorphic note attachment to various entity types.
 *
 * @package App\Http\Controllers\Admin
 * @uses NotesProcess Trait for note-related workflows
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class NotesController extends Controller
{
    use NotesProcess;
    use LogActivity;
    use Common;

    public function index(Request $request)
    {
        $note= Notes::where([['entity_id',$request->entity_id],['entity_name',$request->entity_name]])->get();

        return $note;
    }

    public function create()
    {
        return view('admin.notes.add_notes');
    }

    public function store(NotesRequest $request)
    {
        try
        {
            $userid=Auth::id();

            if ($request->id==='')
            {
                $note = $this->createNotes($request->notes,$request->church_id,$request->entity_id,$request->entity_name,$userid,$userid);
            }
            else
            {
                $note= Notes::where('id',$request->id)->first();
                $note->notes=$request->notes;
                $note->save();
            }

            $message=__('notes.notes_message');
            $ip= $this->getRequestIP();
                $this->doActivityLog(
                $note,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_NOTE,
                $message
            );

            $res['message']=__('notes.notes_message');
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function edit($id)
    {
        $notes=Notes::where('id',$id)->first();
        if(Gate::allows('note',$notes))
        {
            return $notes->notes;
        }
        else
        {
            abort(403);
        }
    }

    public function delete($id)
    {
        try
        {
            $notes=Notes::where('id',$id)->first();
            $notes->delete();

            $message = "Notes Deleted Successfully";
            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $notes,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_NOTE,
                $message
            );
            $res['message']='Notes Deleted Successfully';
            return $res;
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
