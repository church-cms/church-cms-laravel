<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Fund as FundResource;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\FundRequest;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Fund;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * FundController
 *
 * Manages church fundraising, donations, and financial tracking.
 * Handles fund creation, donation recording, payment processing, and financial reports.
 * Supports multiple payment gateways and fund categorization.
 * Provides fund listing with search and filtering by payment method.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for recording fund transactions
 * @uses Common Trait for helper functions
 */
class FundController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $funds = Fund::where('church_id',Auth::user()->church_id);
        if(\Request::getQueryString() != null)
        {
            $search = $request->search;
            if($search != null)
            {
                $funds = $funds->where('amount','LIKE','%'.$search.'%')->orWhereHas('user',function ($query) use($search)
                {
                    $query->whereHas('userprofile', function ($q) use($search)
                    {
                        $q->where('firstname','LIKE','%'.$search.'%')->orWhere('lastname','LIKE','%'.$search.'%');
                    });
                })->orWhereJsonContains('data', ['first_name' => $search])->orWhereJsonContains('data', ['last_name' => $search]);
            }

            if($request->paymentgateway_id != null)
            {
                $paymentgateway_id=$request->paymentgateway_id;
                $funds = $funds->WhereHas('payaccount',function ($query) use($paymentgateway_id)
                {
                    $query->where('paymentgateway_id',$paymentgateway_id);
                });
            }

            if($request->type != null)
            {
                $funds = $funds->where('method','LIKE','%'.$request->type.'%');
            }

            if($request->status != null)
            {
                $funds = $funds->where('status','LIKE','%'.$request->status.'%');
            }
        }
        $funds = $funds->latest()->paginate(10);

        $funds = FundResource::collection($funds);

        return $funds;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/fund/index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMember()
    {
        $users = User::ByChurch(Auth::user()->church_id)->ByRole(5)->get();

        $users = UserResource::collection($users);

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/fund/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FundRequest $request)
    {
        try
        {
            $fund= new Fund;

            $fund->church_id         = Auth::user()->church_id;
            $fund->authorised_by     = Auth::id();
            $fund->authorised_at     = date('Y-m-d H:i:s');
            $array =[];

            $fund->membership        = $request->membership;
            if($request->membership === 'member')
            {
                $fund->user_id       = $request->selectedUsers['id'];
            }
            else
            {
                $array['first_name']    = $request->first_name;
                $array['last_name']     = $request->last_name;
                $array['address']       = $request->address;
                $array['mobile_number'] = $request->mobile_number;

                $fund->data            = $array;
            }

            $fund->amount            = $request->amount;
            if($request->amount >= '100000')
            {
                $array['pan_number']    = $request->pan_number;
                $fund->data = $array;
            }
            $fund->method            = $request->method;
            $payment_details = [];
            if($request->method === 'cheque')
            {
                $payment_details['cheque_number']   = $request->cheque_number;
                $payment_details['account_number']  = $request->account_number;
                $payment_details['payee_name']      = $request->payee_name;

                $fund->payment_details  = $payment_details;
            }
            elseif($request->method === 'card')
            {
                $payment_details['card_name']   = $request->card_name;
                $payment_details['bank_name']   = $request->bank_name;

                $fund->payment_details = $payment_details;
            }
            elseif($request->method === 'demanddraft')
            {
                $fund->payment_details['payable_at']        = $request->payable_at;
                $fund->payment_details['account_number']    = $request->account_number;

                $fund->payment_details = $payment_details;
            }
            $fund->status            = $request->status;

            if($request->status === 'cancel')
            {
                $fund->comments = $request->comments;
            }
            $fund->uuid=uniqid();
            $fund->save();

            $message= 'Fund Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $fund,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_FUND,
                $message
            );

            $res['success']=$message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function fundDetails($id)
    {
        $fund = Fund::where('id',$id)->first();
        if(Gate::allows('fund',$fund))
        {
            return view('/admin/fund/show',['fund' => $fund]);
        }
        else
        {
            abort(403);
        }
    }

    public function edit($id)
    {
        //
        $fund = Fund::where('id',$id)->first();
        if(Gate::allows('fund',$fund))
        {
            if($fund->status != 'deposited')
            {
                return view('/admin/fund/edit',['fund'=>$fund]);
            }
            else
            {
                abort(403);
            }
        }
        else
        {
            abort(403);
        }
    }

    public function editList($id)
    {
        $fund = Fund::where('id',$id)->first();
        if(Gate::allows('fund',$fund))
        {
            $users = User::ByChurch(Auth::user()->church_id)->ByRole(5)->get();
            $users = FundResource::collection($users);

            $array=[];

            $array['membership']=$fund->membership;
            if($array['membership']==='guest')
            {
                $array['first_name']    = $fund->data['first_name'];
                $array['last_name']     = $fund->data['last_name'];
                $array['address']       = $fund->data['address'];
                $array['mobile_number'] = $fund->data['mobile_number'];
            }
            elseif($array['membership']==='member')
            {
                $user = User::where('id',$fund->user_id)->first();
                $array['selectedUsers']['id']       = $fund->user_id;
                $array['selectedUsers']['name']     = $user->name;
                $array['selectedUsers']['fullname'] = $user->FullName;
            }

            $array['amount']=$fund->amount;
            if($array['amount'] >= '100000')
            {
                $array['pan_number']=$fund->data['pan_number'];
            }

            $array['method']=$fund->method;

            if($array['method'] === 'cheque')
            {
                $array['cheque_number'] = $fund->payment_details['cheque_number'];
                $array['account_number']= $fund->payment_details['account_number'];
                $array['payee_name']    = $fund->payment_details['payee_name'];
            }

            elseif($array['method'] === 'demanddraft')
            {
                $array['payable_at']        = $fund->payment_details['payable_at'];
                $array['account_number']    = $fund->payment_details['account_number'];
            }

            elseif($array['method'] === 'card')
            {
                $array['card_name'] = $fund->payment_details['card_name'];
                $array['bank_name'] = $fund->payment_details['bank_name'];
            }

            $array['status']=$fund->status;
            if($array['status'] === 'cancel')
            {
                $array['comments']=$fund->comments;
            }
            $array['memberlist'] = $users;
            return $array;
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FundRequest $request,$id)
    {
        //
        try
        {
            $fund = Fund::where('id',$id)->first();

            $fund->membership        = $request->membership;
            if($request->membership === 'member')
            {
                if( $request->selectedUsers['id'] != null)
                {
                    $funds->user_id           = $request->selectedUsers['id'];
                }
                else
                {
                    $funds->user_id = $funds->user_id;
                }
            }
            if($request->membership === 'guest')
            {
                $fund->data              = array(
                                            'first_name'    => $request->first_name,
                                            'last_name'     => $request->last_name,
                                            'address'       => $request->address,
                                            'mobile_number' => $request->mobile_number
                                        );
            }

            $fund->amount            = $request->amount;
            if($request->amount >= '100000')
            {
                $fund->data = array('pan_number'=>$request->pan_number);
            }
            $fund->method            = $request->method;

            if($request->method === 'cheque')
            {
                $fund->payment_details  = array(
                                            'cheque_number'     => $request->cheque_number,
                                            'account_number'    => $request->account_number,
                                            'payee_name'        => $request->payee_name
                                        );
            }
            elseif($request->method === 'card')
            {
                $fund->payment_details = array(
                                            'card_name' => $request->card_name,
                                            'bank_name' => $request->bank_name
                                        );
            }
            elseif($request->method === 'demanddraft')
            {
                $fund->payment_details = array(
                                            'payable_at'        => $request->payable_at,
                                            'account_number'    => $request->account_number
                                        );
            }
            $fund->status            = $request->status;
            if($request->status === 'cancel')
            {
                $fund->comments = $request->comments;
            }

            $fund->save();

            $message='Fund Details Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $fund,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_FUND,
                $message
            );
            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $fund = Fund::where('id',$id)->first();
            $fund->delete();

            $message = 'Fund Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $fund,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_FUND,
                $fund
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
