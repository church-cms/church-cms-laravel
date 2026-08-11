<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;  
use App\Models\User;
use Exception;

class AttendanceImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    public $event_id;

    public function  __construct($event_id)
	{
	    $this->event_id =$event_id;
	}

    public function collection(Collection $rows)
    {
        //
	    try
	    {
	      $insertedcount = '';
	      $church_id=Auth::user()->church_id;
	      foreach ($rows as $row) 
	      { 
	        $user    = User::where([['church_id',$church_id],['mobile_no',$row['user_mobile']]])->first();
	        $attendance = Attendance::where([
	          ['church_id',$church_id],
	          ['user_id',$user->id],
	          ['entity_id',$this->event_id],
	          ['entity_name','App\Models\Events'],
	        ])->first(); 

	        if($attendance->is_present == 0)
	        { 
	          $update['is_present'] = 1;

	          Attendance::where('id',$attendance->id)->update($update);
	          
	          $insertedcount++;
	        }   
	        \Session::put('insertedcount',$insertedcount);                        
	      }
	    }
	    catch(Exception $e)
	    {
	      //dd($e->getMessage());
	    }       
    }
}
