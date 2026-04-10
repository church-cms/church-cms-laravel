<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
    use Common,ImageTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($name)
    {
        //
        $user = User::where('name',$name)->first();
        $church = Church::where('id',Auth::user()->church_id)->first();
          
        return view('/admin/member/membershipcard/create_print',['user' => $user , 'church' => $church]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print($name)
    {
       // dd("GG");
        //
        try
        {
            $user=User::where('name',$name)->first();

            $church=Church::where('id',Auth::user()->church_id)->first(); 

            $array['user']=$user;
            $array['church']=$church;

             $avatarSource = $this->toPdfImageSrc(optional($user->userprofile)->AvatarPath)
                ?: $toPdfImageSrc(url('images/default-user.png'));

            $churchLogoPath = Auth::user()->ChurchLogo['meta_value'] != '-'
                ? Auth::user()->ChurchLogoPath
                : url('images/church_cms_logo.jpg');
              $churchLogoSource = $this->toPdfImageSrc($churchLogoPath);

            $array['avatarSource']=$avatarSource;
            $array['churchLogoSource']=$churchLogoSource;

            $pdf = PDF::loadView('/admin/member/membershipcard/print', $array);

            
            $folder = $church->slug.'/membership_card';

            $filename='Membership_Card_'.$user->FullName.'_'.date('Y').'.pdf';

            

            $file=$this->putContents($folder.'/'.$filename, $pdf->output());

            $message = 'Membership Card Printed Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_PRINT_MEMBERSHIP_CARD,
                $message
            );

            return $pdf->download($filename);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }

public function print_new($name)
{
    try {
        $user = User::where('name', $name)->first();
        $church = Church::where('id', Auth::user()->church_id)->first();

        $array['user'] = $user;
        $array['church'] = $church;

        // Generate PDF
        $pdf = PDF::loadView('/admin/member/membershipcard/print', $array);

        // MAKE SURE TEMP FOLDER EXISTS
        $tempFolder = storage_path('app/temp');
        if (!file_exists($tempFolder)) {
            mkdir($tempFolder, 0777, true);
        }

        // Remove spaces from filename
        $safeName = str_replace(' ', '_', $user->FullName);
        $filename = "Membership_Card_{$safeName}_" . date('Y');

        // Save temporary PDF
        $tempPdfPath = $tempFolder . "/{$filename}.pdf";
        file_put_contents($tempPdfPath, $pdf->output());

        // Check file saved correctly
        if (!file_exists($tempPdfPath)) {
            dd("PDF NOT CREATED: " . $tempPdfPath);
        }

        // Convert PDF → JPG
        $imagick = new \Imagick();
        $imagick->setResolution(300, 300);
        $imagick->readImage($tempPdfPath."[0]");
        $imagick->setImageFormat('jpg');

        $jpgPath = $tempFolder . "/{$filename}.jpg";
        $imagick->writeImage($jpgPath);

        // Check JPG created
        if (!file_exists($jpgPath)) {
            dd("JPG NOT CREATED");
        }

        // Download JPG & delete after sending
        return response()->download($jpgPath, "{$filename}.jpg")
                         ->deleteFileAfterSend(true);

    } catch (Exception $e) {
        Log::info($e->getMessage());
        dd($e->getMessage());
    }
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createAll()
    {
        //
        $church=Church::where('id',Auth::user()->church_id)->first(); 
        $users = User::ByChurch($church->id)->ByRole(5)->ByStatus('active')->get();
      

        return view('/admin/member/membershipcard/create_print_all',['users' => $users , 'church' => $church]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printAll($usertype)
    {


        //
        try
        {
            $church=Church::where('id',Auth::user()->church_id)->first(); 
            $users = User::ByChurch($church->id)->ByRole(5)->ByStatus('active')->get()->chunk(2);


            foreach ($users as $key => $group) {
            foreach ($group as $user) {

                // Avatar
                $user->avatar_src = $this->toPdfImageSrc(
                    optional($user->userprofile)->AvatarPath
                ) ?? $this->toPdfImageSrc(url('images/default-user.png'));

                // Church logo
                $churchLogoPath = auth()->user()->ChurchLogo['meta_value'] != '-'
                    ? auth()->user()->ChurchLogoPath
                    : url('images/church_cms_logo.jpg');

                $user->church_logo_src = $this->toPdfImageSrc($churchLogoPath);
            }
        }

            $array['users']=$users;
            $array['church']=$church;

            $pdf = PDF::loadView('/admin/member/membershipcard/print_all', $array);
            
            $folder = $church->slug.'/membership_card';

            $filename='Membership_Card_'.date('Y').'.pdf';

            $file=$this->putContents($folder.'/'.$filename, $pdf->output());

            $message = 'Membership Card Printed Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_PRINT_MEMBERSHIP_CARD_FOR_ALL,
                $message
            );

            return $pdf->download($filename);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }
}