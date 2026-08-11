<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{

	public function headings(): array
    {
    	$heading = ['name','firstname','lastname','gender','date_of_birth','profession','address','city','state','country','pincode','mobile_no','email'];
        return $heading;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$user = User::with('userprofile')->where('church_id',Auth::user()->church_id)->ByRole(5)->get();
        return $user;
    }
}
