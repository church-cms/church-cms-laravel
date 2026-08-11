<?php
   
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;  
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Traits\RegisterUser;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use Exception;
    
class UsersImport implements ToCollection , WithHeadingRow
{
    use RegisterUser;

    public function collection(Collection $rows)
    {
        try 
        {
            $church_id = Auth::user()->church_id;
            $user_count = User::ByRole(5)->ByChurch($church_id)->count();
            
            $count =$user_count;

            if( (count($rows)>0) && (count($rows)<=($count)) )
            {
                $church_id = Auth::user()->church_id;
                $user_count = User::ByRole(5)->ByChurch($church_id)->count();
                
                $count = $user_count;

                if( (count($rows)>0) && (count($rows)<=($count)) )
                {
                    foreach ($rows as $row) 
                    {
                        $country = Country::where('name','LIKE','%'.$row['country'].'%')->first();
                        $state = State::where('name','LIKE','%'.$row['state'].'%')->first();
                        $city = City::where('name','LIKE','%'.$row['city'].'%')->first();
                        if($row['membership_type'] == "member")
                        {
                            $membership_start_date  = Carbon::now()->format('Y-m-d');
                            //$membership_end_date    = Carbon::now()->addMonth(1)->format('Y-m-d');
                            $membership_end_date    = null;
                        }
                        else
                        {
                            $membership_start_date = null;
                            $membership_end_date = null;
                        }

                        $request->ref_name              =  $row['ref_name'];
                        $request->firstname             =  $row['firstname'];
                        $request->lastname              =  $row['lastname'];
                        $request->birth_firstname       =  $row['birth_firstname']; 
                        $request->birth_lastname        =  $row['birth_lastname'];
                        $request->gender                =  $row['gender']; 
                        $request->date_of_birth         =  date('Y-m-d',strtotime($row['date_of_birth']));
                        $request->profession            =  $row['occupation']; 
                        $request->sub_occupation        =  $row['sub-category']; 
                        $request->address               =  $row['address'];
                        $request->city_id               =  $city->id;
                        $request->state_id              =  $state->id; 
                        $request->country_id            =  $country->id;
                        $request->pincode               =  $row['pincode'];
                        $request->mobile_no             =  $row['mobile_no'];
                        $request->email                 =  $row['email'];
                        $request->aadhar_number         =  $row['aadhar_number'];
                        $request->membership_type       =  $row['membership_type'];
                        $request->membership_start_date =  $membership_start_date;
                        $request->family                =  $row['family']; 
                        $request->marriage_status       =  $row['marriage_status'];
                        $request->marriage_start_date   =  $row['marriage_start_date'];
                        $request->relation              =  $row['relation'];
                        $request->notes                 =  $row['notes']; 
                        $request->status                =  "active";
                  
                        $mobile_no  = $row['mobile_no'];
                        $avatar = '';
                        $user=User::where(function($q) use($name,$email,$mobile_no)
                        {
                            $q->where('mobile_no',$mobile_no);
                        })->exists();
                        if(!$user)
                        {
                            $this->CreateUser($request,$church_id,$avatar,5);
                            $insertedcount++;   
                        }
                        else
                        {
                            \Session::flash('failmessage','Mobile Number Already Exists');
                        }          
                    } 
                    \Session::put('insertedcount',$insertedcount);      
                }
                else
                {
                    \Session::put('count',$count);
                }
            }
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }
}