<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Traits\SettingProcess;
use Illuminate\Http\Request;
use Exception;
use Log;

/**
 * MaintenanceController
 *
 * Manages church system maintenance settings and mode.
 * Handles enabling/disabling maintenance mode for the application.
 * Uses SettingProcess trait for settings configuration.
 *
 * @package App\Http\Controllers\Admin\Setting
 */
class MaintenanceController extends Controller
{
    use SettingProcess;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         return view('admin.settings.maintenancesettings');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try
        {
            if(isset($request->maintenance)){

                if($request->maintenance==1)
               {
                $maintenance=$request->maintenance;
              }
            }else{
               $maintenance=0;
            }

            if(isset($request->register)){
            
            if($request->register==1)
            {
                $register=$request->register;
            }
          } else{
             $register=0;
         }
         if(isset($request->login_status)){
             if($request->login_status==1)
            {
                $login_status=$request->login_status;
            }
          }else{
             $login_status=0;
         }

            $member_web_login  = isset($request->member_web_login)  && $request->member_web_login  == 1 ? 1 : 0;
            $guest_login       = isset($request->guest_login)       && $request->guest_login       == 1 ? 1 : 0;
            $guest_registration= isset($request->guest_registration)&& $request->guest_registration== 1 ? 1 : 0;

            $this->updatesettings('maintenance',$maintenance);
            $this->updatesettings('register_status',1);
            $this->updatesettings('login_status',1);
            $this->updatesettings('member_web_login', (string) $member_web_login);
            $this->updatesettings('guest_login',      (string) $guest_login);
            $this->updatesettings('guest_registration',(string) $guest_registration);

            $message = 'MaintenanceSetting Updated Successfully';


            return redirect('/admin/settings/maintenancesettings')->with(['successmessage' => $message]);

        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
