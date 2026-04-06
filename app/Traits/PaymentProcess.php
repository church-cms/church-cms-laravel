<?php
/**
 * Trait for processing common
 */
namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\Userprofile;
use App\Models\Plan;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * Trait for payment and subscription processing
 *
 * Provides functionality for:
 * - Creating new payment records
 * - Processing payment approvals
 * - Updating user membership based on payments
 * - Storing payment and plan details
 * - Managing subscription cycles
 *
 * @package App\Traits
 */
trait PaymentProcess
{
    /**
     * Process and create a payment record.
     *
     * Creates a new subscription payment with plan details and membership updates.
     * Handles successful payment approvals and updates user membership status and dates.
     *
     * @param object $data The payment data object containing payment details and status
     * @param int $user_id The user ID making the payment
     * @param int $church_id The church ID associated with the payment
     * @param \App\Models\Subscription $payment The payment/subscription model
     *
     * @return void
     */
    public function CreatePayment(object $data, int $user_id, int $church_id, object $payment): mixed {
        \DB::beginTransaction();
        try {
            if (($data->status == 'success') && ($payment->status == 'pending')) {
                $plan = Plan::where('id', $data->udf1)->first();
                $payment->plan_id = $plan->id;
                $payment->status = "approve";
                $payment->payment_details = array(  'merchant_key'  =>  $data->key ,
                                                    'txnid'         =>  $data->txnid ,
                                                    'amount'        =>  $data->amount ,
                                                    'firstname'     =>  $data->firstname ,
                                                    'email'         =>  $data->email ,
                                                    'hash'          =>  $data->hash ,
                                                    'productinfo'   =>  $data->productinfo ,
                                                    'status'        =>  $data->status ,
                                                    'mode'          =>  $data->mode ,
                                                    'error_Message' =>  $data->error_Message ,
                                                    'addedon'       =>  $data->addedon ,
                                                    'phone'         =>  $data->phone
                                                );
                $payment->plan_details = array( 'no_of_members'     =>  $plan->no_of_members ,
                                                'no_of_events'      =>  $plan->no_of_events ,
                                                'no_of_folders'     =>  $plan->no_of_folders ,
                                                'no_of_files'       =>  $plan->no_of_files ,
                                                'no_of_videos'      =>  $plan->no_of_videos ,
                                                'no_of_bulletins'   =>  $plan->no_of_bulletins ,
                                                'no_of_groups'      =>  $plan->no_of_groups
                                            );
                $payment->end_date = Carbon::parse($data->addedon)->addDays($plan->cycle);

                $userprofile = Userprofile::where([['user_id',$user_id],['church_id',$church_id]])->first();
                $now=date('Y-m-d');

                $userprofile->membership_type = "member";
                $userprofile->membership_start_date = date('Y-m-d',strtotime($data->addedon));
                //$userprofile->membership_end_date = date('Y-m-d',strtotime($now.'+'.$plan->cycle.' days'));

                $userprofile->save();
            }
            elseif( ($data->status == 'success') && ($payment->status == 'expired') )
            {
                $payment = new Subscription;

                $plan = Plan::where('id', $data->udf1)->first();

                $payment->church_id = $church_id;
                $payment->user_id = $user_id;
                $payment->plan_id = $plan->id;
                $payment->status = "approve";
                $payment->payment_details = array(  'merchant_key'  =>  $data->key ,
                                                    'txnid'         =>  $data->txnid ,
                                                    'amount'        =>  $data->amount ,
                                                    'firstname'     =>  $data->firstname ,
                                                    'email'         =>  $data->email ,
                                                    'hash'          =>  $data->hash ,
                                                    'productinfo'   =>  $data->productinfo ,
                                                    'status'        =>  $data->status ,
                                                    'mode'          =>  $data->mode ,
                                                    'error_Message' =>  $data->error_Message ,
                                                    'addedon'       =>  $data->addedon ,
                                                    'phone'         =>  $data->phone
                                                );
                $payment->plan_details = array( 'no_of_members'     =>  $plan->no_of_members ,
                                                'no_of_events'      =>  $plan->no_of_events ,
                                                'no_of_folders'     =>  $plan->no_of_folders ,
                                                'no_of_files'       =>  $plan->no_of_files ,
                                                'no_of_videos'      =>  $plan->no_of_videos ,
                                                'no_of_bulletins'   =>  $plan->no_of_bulletins ,
                                                'no_of_groups'      =>  $plan->no_of_groups
                                            );
                $payment->end_date = Carbon::parse($data->addedon)->addDays($plan->cycle);

                $userprofile = Userprofile::where([['user_id',$user_id],['church_id',$church_id]])->first();
                $now=date('Y-m-d');

                $userprofile->membership_type = "member";
                $userprofile->membership_start_date = date('Y-m-d',strtotime($data->addedon));
                //$userprofile->membership_end_date = date('Y-m-d',strtotime($now.'+'.$plan->cycle.' days'));

                $userprofile->save();
            }

            $payment->save();

            \DB::commit();
            return $payment;
        }
        catch(Exception $e)
        {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
