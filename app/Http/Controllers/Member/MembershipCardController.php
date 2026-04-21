<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Church;
use App\Models\User;
use Exception;
use Log;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Traits\ImageTrait;

class MembershipCardController extends Controller
{
    use LogActivity;
    use Common, ImageTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($name)
    {
        //
        $user = User::where('name', $name)->first();
        $church = Church::where('id', Auth::user()->church_id)->first();

        return view('/admin/member/membershipcard/create_print', ['user' => $user, 'church' => $church]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print($name)
    {

        try {
            $user = User::where('name', $name)->first();

            $church = Church::where('id', Auth::user()->church_id)->first();

            $array['user'] = $user;
            $array['church'] = $church;

            $avatarSource = $this->toPdfImageSrc(optional($user->userprofile)->AvatarPath)
                ?: $toPdfImageSrc(url('images/default-user.png'));

            $churchLogoPath = Auth::user()->ChurchLogo['meta_value'] != '-'
                ? Auth::user()->ChurchLogoPath
                : url('images/church_cms_logo.jpg');
            $churchLogoSource = $this->toPdfImageSrc($churchLogoPath);

            $array['avatarSource'] = $avatarSource;
            $array['churchLogoSource'] = $churchLogoSource;

            $pdf = PDF::loadView('/admin/member/membershipcard/print', $array);


            $folder = $church->slug . '/membership_card';

            $filename = 'Membership_Card_' . $user->FullName . '_' . date('Y') . '.pdf';

            $file = $this->putContents($folder . '/' . $filename, $pdf->output());

            $message = 'Membership Card Printed Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_PRINT_MEMBERSHIP_CARD,
                $message
            );

            return $pdf->download($filename);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }
}
