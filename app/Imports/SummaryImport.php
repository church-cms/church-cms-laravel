<?php
   
namespace App\Imports;
   
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;  
use App\Models\User;
use Exception;

class SummaryImport implements ToCollection , WithHeadingRow
{
  public function collection(Collection $rows)
  { 
    try
    {
      $insertedcount = '';
      $church_id=Auth::user()->church_id;

      foreach ($rows as $row) 
      { 
        $user    = User::where('mobile_no',$row['user_mobile'])->first();
        $attendance = Attendance::where([
          ['church_id',$church_id],
          ['user_id',$user->id],
          ['title',$row['event_title']],
          ['category',$row['event_category']],
          ['date',date('Y-m-d H:i:s',strtotime($row['date']))]
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