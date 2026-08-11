<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Subscribers;
use Exception;
use Log;

class SubscribersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        try
        {
            if(count($rows) > 0) 
            {
                foreach ($rows as $row) 
                {
                    $subscriber = Subscribers::where([['email',$row['email']],['church_id',Auth::user()->church_id],['deleted_at',null]])->first();

                    if($subscriber == null)
                    {
                        Subscribers::create([
                            'church_id' =>  Auth::user()->church_id,
                            'firstname' =>  $row['firstname'],
                            'lastname'  =>  $row['lastname'], 
                            'email'     =>  $row['email'],
                            'aff'       =>  $row['aff'],
                            'source'    =>  $row['source'],
                            'is_active' =>  $row['is_active'],
                        ]);
                        $insertedcount++;   
                    }
                }
                \Session::put('insertedcount',$insertedcount);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
}