<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DetailRequest;
use Illuminate\Http\Request;
use App\Models\ChurchDetail;
use App\Traits\LogActivity;
use App\Traits\Common;
use Exception;
use Log;

/**
 * ChurchDetailsController
 *
 * Manages church organizational details and metadata.
 * Handles church information, settings, and configuration data.
 * Stores church-specific metadata and organizational information.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class ChurchDetailsController extends Controller
{
    use LogActivity;
    use Common;

    public function edit($church_id)
    {
        //
        $churchdetail = [];

        $churchdetails  = ChurchDetail::select('meta_key','meta_value')->where('church_id',$church_id)->get();
        $plucked  = $churchdetails->pluck('meta_value','meta_key');

        $churchdetail['church_logo']     = $plucked['church_logo'] === '-' ? null:$this->getFilePath($plucked['church_logo']);
        $churchdetail['favicon']     = $plucked['favicon'] === '-' ? null:$this->getFilePath($plucked['favicon']);

        
        $churchdetail['name']   = $plucked['name'] === '-' ? null:$plucked['name'];
        $churchdetail['short_summary']   = $plucked['short_summary'] === '-' ? null:$plucked['short_summary'];
        $churchdetail['long_summary']    = $plucked['long_summary'] === '-' ? null:$plucked['long_summary'];
        $churchdetail['quotes']          = $plucked['quotes'] === '-' ? null:$plucked['quotes'];
        $churchdetail['phone']           = $plucked['phone'] === '-' ? null:$plucked['phone'];
        $churchdetail['email']           = $plucked['email'] === '-' ? null:$plucked['email'];
        $churchdetail['address']         = $plucked['address'] === '-' ? null:$plucked['address'];
        $churchdetail['latitude']        = $plucked['latitude'] === '-' ? null:$plucked['latitude'];
        $churchdetail['longitude']       = $plucked['longitude'] === '-' ? null:$plucked['longitude'];
        $churchdetail['website']         = $plucked['website'] === '-' ? null:$plucked['website'];
        $churchdetail['facebook']        = $plucked['facebook'] === '-' ? null:$plucked['facebook'];
        $churchdetail['twitter']         = $plucked['twitter'] === '-' ? null:$plucked['twitter'];
        $churchdetail['instagram']       = $plucked['instagram'] === '-' ? null:$plucked['instagram'];
        $churchdetail['site_title']       = $plucked['site_title'] === '-' ? null:$plucked['site_title'];

        $churchdetail['site_description']       = $plucked['site_description'] === '-' ? null:$plucked['site_description'];
        $churchdetail['site_keyword']       = $plucked['site_keyword'] === '-' ? null:$plucked['site_keyword'];

        return view('/admin/churchdetails/edit',['churchdetail' => $churchdetail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DetailRequest $request,$church_id)
    {

        //dd($request);
        //
        try
        {
            $churchdetail = ChurchDetail::where('church_id',$church_id)->first();

            $file=$request->file('church_logo');
            if($file!=null)
            {
                $path = $this->uploadFile(Auth::user()->church_id.'/church_logo',$file);

                $churchdetail = ChurchDetail::where([['church_id',$church_id],['meta_key','church_logo']])->first();

                $churchdetail->meta_value=$path;

                $churchdetail->save();
            }

             $filefav=$request->file('favicon');
            if($filefav!=null)
            {
                $paths = $this->uploadFile(Auth::user()->church_id.'/church_logo',$filefav);

                $churchdetail = ChurchDetail::where([['church_id',$church_id],['meta_key','favicon']])->first();

                $churchdetail->meta_value=$paths;

                $churchdetail->save();
            }

            foreach($request->request as $key => $value)
            {
                $arrays = ['short_summary' , 'long_summary' , 'quotes' , 'phone' , 'email' , 'address' , 'latitude' , 'longitude' , 'website' , 'facebook' , 'twitter' , 'instagram'];
                foreach($arrays as $array)
                {
                    if($key === $array)
                    {
                        $churchdetail = ChurchDetail::where([['church_id',$church_id],['meta_key',$key]])->first();
                        if($churchdetail)
                        {
                            if($value === null)
                            {
                                $churchdetail->meta_value = $churchdetail->meta_value;
                            }
                            $churchdetail->meta_value = $value;

                            $churchdetail->save();
                        }
                    }
                }
            }

            $message = 'Church Details Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $churchdetail,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_CHURCH_DETAIL,
                $message
            );

            return redirect('/admin/churchdetails/edit/'.$church_id)->with(['successmessage' => $message]);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
