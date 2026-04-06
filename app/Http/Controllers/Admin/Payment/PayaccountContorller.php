<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Requests\Payment\PayaccountAddRequest;
use App\Http\Resources\Payment\PaymentgatewayResource;
use App\Http\Resources\Payment\PayaccountResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Paymentgateway;
use App\Models\Payaccount;
use Exception;
use Log;

/**
 * PayaccountContorller
 *
 * Manages payment accounts and payment gateway configurations.
 * Handles CRUD operations for payment account management.
 * Provides payment gateway selection and payment method setup.
 *
 * @package App\Http\Controllers\Admin\Payment
 */
class PayaccountContorller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.payaccount.index');
    }

    public function getlist()
    {
         $payaccounts=Payaccount::where('church_id',Auth::user()->church_id)->get();
         $payaccounts=PayaccountResource::collection($payaccounts);
         return $payaccounts;
    }

    public function addlist()
    {
        $paymentgateways=Paymentgateway::where('status',1)->get();
        $paymentgateways=PaymentgatewayResource::collection($paymentgateways);
        return $paymentgateways;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.payaccount.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayaccountAddRequest $request)
    {
        //
        if($request->paymentgateway_id==='bank')
        {
            $param1=$request->account_name;
            $param2=$request->account_number;
            $param3=$request->bank_name;
            $param4=$request->branch_address;
            $param5=$request->ifsc_code;
            $param6=$request->branch_name;
        }
        if($request->paymentgateway_id==='gpay')
        {
          $param1=$request->gpay_number;
        }
        if($request->paymentgateway_id==='upi')
        {
          $param1=$request->upi_id;
        }
        if($request->paymentgateway_id==='cheque')
        {
          $param1=$request->payee_name;
        }
        $paymentgateway=Paymentgateway::where('gatewayname',$request->paymentgateway_id)->first();

          $data=[
            'church_id'         => Auth::user()->church_id,
            'paymentgateway_id' => $paymentgateway->id,
            'status'            => $request->status,
            'comments'          => $request->comments,
            'param1'            => $param1,
            'param2'            => $param2,
            'param3'            => $param3,
            'param4'            => $param4,
            'param5'            => $param5,
            'param6'            => $param6,
             ];

        $payaccount=Payaccount::create($data);

        if($payaccount->status===1)
        {
            $this->changeStatus($payaccount);
        }

        $message['success']="Payaccount created successfully";

        return $message;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $payaccount=Payaccount::find($id);
        return view('admin.payaccount.edit',['payaccount'=>$payaccount]);
    }

    public function statusUpdate($id)
    {
        $payaccount=Payaccount::find($id);
        $status=$payaccount->status===1 ? 0:1;
        $payaccount->update(['status'=>$status]);
        if($payaccount->status===1)
        {
            $this->changeStatus($payaccount);
        }

        $message['success']="Payaccount status Updated";

        return $message;

    }

    public function changeStatus($payaccount)
    {
        $payaccountsUpdate=Payaccount::where([['id','!=',$payaccount->id],['paymentgateway_id',$payaccount->paymentgateway_id]])->update(['status'=>0]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function editList($id)
    {
        //
        $payaccount=Payaccount::find($id);
        return new PayaccountResource($payaccount);
    }

    public function edit($id)
    {
        //
        $payaccount=Payaccount::find($id);
        return view('admin.payaccount.edit',['payaccount'=>$payaccount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if($request->paymentgateway_id==='bank')
        {
            $param1=$request->account_name;
            $param2=$request->account_number;
            $param3=$request->bank_name;
            $param4=$request->branch_address;
            $param5=$request->ifsc_code;
            $param6=$request->branch_name;
        }
        if($request->paymentgateway_id==='gpay')
        {
          $param1=$request->gpay_number;
        }
        if($request->paymentgateway_id==='upi')
        {
          $param1=$request->upi_id;
        }
        if($request->paymentgateway_id==='cheque')
        {
          $param1=$request->payee_name;
        }
        $paymentgateway=Paymentgateway::where('gatewayname',$request->paymentgateway_id)->first();

          $data=[
            'church_id'         => Auth::user()->church_id,
            'paymentgateway_id' => $paymentgateway->id,
            'status'            => $request->status,
            'comments'          => $request->comments,
            'param1'            => $param1,
            'param2'            => $param2,
            'param3'            => $param3,
            'param4'            => $param4,
            'param5'            => $param5,
            'param6'            => $param6,
             ];

        $payaccount=Payaccount::find($id);
        $payaccount=$payaccount->update($data);

        if($payaccount->status===1)
        {
            $this->changeStatus($payaccount);
        }

        $message['success']="Payaccount created successfully";

        return $message;
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
        $payaccount=Payaccount::find($id);
        $payaccount->delete();

        $message['success']="Payaccount Deleted successfully";

        return $message;

    }
}
