<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportMemberRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RegisterUser;
use App\Imports\UsersImport;
use App\Traits\LogActivity;
use App\Traits\Common;
use League\Csv\Writer;
use Exception;
use Log;
use DB;

/**
 * ImportMemberController
 *
 * Handles bulk member import functionality.
 * Processes CSV file uploads for member data import with validation.
 * Integrates with user registration system for member account creation.
 *
 * @package App\Http\Controllers\Admin
 * @uses RegisterUser Trait for user registration logic
 * @uses LogActivity Trait for activity tracking
 * @uses Common Trait for utility functions
 */
class ImportMemberController extends Controller
{

    use RegisterUser;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin/member/import/import');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function importUsers(ImportMemberRequest $request) //ImportMember
    {
        //
        try {
            Excel::import(new UsersImport, $request->file('import_file'));
            $count = \Session::get('count');

            /*  if($count != 0)
            {
                return back()->with('failmessage','You can add only '.$count.' Members');
            }*/

            $insertedcount = \Session::get('insertedcount');

            if ($insertedcount > 0) {
                $message = ('Member Details Imported Successfully');

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_IMPORT_MEMBER,
                    $message
                );
                return back()->with('successmessage', $insertedcount . ' Records Inserted successfully.');
            } else {
                return back()->with('failmessage', 'No Records Inserted.');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function downloadFormat(Request $request)
    {
        try {
            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertOne(['ref_name', 'firstname', 'lastname', 'birth_firstname', 'birth_lastname', 'gender', 'date_of_birth', 'occupation', 'sub-category', 'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email', 'aadhar_number', 'membership_type', 'family', 'marriage_status', 'marriage_start_date', 'relation', 'notes']);

            $csv->insertOne([
                '(reference_name)',
                '(firstname)',
                '(lastname)',
                '(birth_firstname)',
                '(birth_lastname)',
                '(male,female)',
                '(date_of_birth)',
                '(business,doctor,engineer,goverment_employee,home_maker,lawyer,pastor,police,professionals,self_employed,student,teacher,others)',
                '(sub-occupation_category)',
                '(address)',
                '(city)',
                '(state)',
                '(country)',
                '(pincode)',
                '(mobile_no)',
                '(email)',
                '(aadhar_number)',
                '(member , guest)',
                '(family_name)',
                '(single , married , ended_by_death , ended_by_divorce , separated)',
                '(marriage_start_date)',
                '(relation)',
                '(notes)',
                'Delete this entire row before importing'
            ]);

            $csv->output('Church Social Add Member Format' . date('_d-m-Y_H:i') . '.csv');

            $message = 'Downloaded Sample Format File Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                Auth::user(),
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DOWNLOAD_SAMPLE_FORMAT,
                $message
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
