<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Resources\Payment\PaymentgatewayResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentgateway;

class PaymentgatewayController extends Controller
{
    public function index()
    {
        return view('admin.paymentgateway.index');
    }

    public function getlist()
    {
        $gateways = Paymentgateway::all();
        return PaymentgatewayResource::collection($gateways);
    }

    public function create()
    {
        return view('admin.paymentgateway.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gatewayname'  => 'required|string|unique:paymentgateways,gatewayname',
            'displayname'  => 'required|string',
            'status'       => 'required|in:0,1',
            'instructions' => 'nullable|string',
            'currency'     => 'nullable|string|max:10',
        ]);

        Paymentgateway::create([
            'gatewayname'  => $request->gatewayname,
            'displayname'  => $request->displayname,
            'status'       => $request->status,
            'instructions' => $request->instructions,
            'currency'     => $request->currency,
        ]);

        return ['success' => 'Payment gateway created successfully'];
    }

    public function edit($id)
    {
        $gateway = Paymentgateway::findOrFail($id);
        return view('admin.paymentgateway.edit', compact('gateway'));
    }

    public function editList($id)
    {
        $gateway = Paymentgateway::findOrFail($id);
        return new PaymentgatewayResource($gateway);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gatewayname'  => 'required|string|unique:paymentgateways,gatewayname,' . $id,
            'displayname'  => 'required|string',
            'status'       => 'required|in:0,1',
            'instructions' => 'nullable|string',
            'currency'     => 'nullable|string|max:10',
        ]);

        $gateway = Paymentgateway::findOrFail($id);
        $gateway->update([
            'gatewayname'  => $request->gatewayname,
            'displayname'  => $request->displayname,
            'status'       => $request->status,
            'instructions' => $request->instructions,
            'currency'     => $request->currency,
        ]);

        return ['success' => 'Payment gateway updated successfully'];
    }

    public function statusUpdate($id)
    {
        $gateway = Paymentgateway::findOrFail($id);
        $gateway->update(['status' => $gateway->status === 1 ? 0 : 1]);
        return ['success' => 'Payment gateway status updated'];
    }

    public function destroy($id)
    {
        $gateway = Paymentgateway::findOrFail($id);
        $gateway->delete();
        return ['success' => 'Payment gateway deleted successfully'];
    }
}
