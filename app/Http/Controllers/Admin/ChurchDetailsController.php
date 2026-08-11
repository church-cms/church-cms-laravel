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
        $churchdetail = ChurchDetail::where('church_id', $church_id)
            ->pluck('meta_value', 'meta_key')
            ->map(fn($value) => $value === '-' ? null : $value)
            ->toArray();

        foreach (['church_logo', 'favicon'] as $fileKey) {
            if (!empty($churchdetail[$fileKey])) {
                $churchdetail[$fileKey] = $this->getFilePath($churchdetail[$fileKey]);
            }
        }

        return view('/admin/churchdetails/edit', compact('churchdetail'));
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

            $textFields = [
                'name', 'short_summary', 'long_summary', 'quotes',
                'phone', 'email', 'address', 'latitude', 'longitude',
                'website', 'facebook', 'twitter', 'instagram',
                'site_title', 'site_description', 'site_keyword',
            ];

            foreach ($textFields as $key) {
                if ($request->has($key)) {
                    $churchdetail = ChurchDetail::where([['church_id', $church_id], ['meta_key', $key]])->first();
                    if ($churchdetail) {
                        $churchdetail->meta_value = $request->input($key) ?? '-';
                        $churchdetail->save();
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
