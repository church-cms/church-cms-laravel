<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\API\Country as CountryResource;
use App\Http\Resources\API\State as StateResource;
use App\Http\Resources\API\City as CityResource;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\RegisterStepOneRequest;
use App\Http\Requests\RegisterStepTwoRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailVerification;
use App\Models\PermissionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Userprofile;
use App\Models\Permission;
use App\Models\Church;
use App\Models\State;
use App\Models\Country;


/**
 * RegisterController
 *
 * Manages user registration process and church account creation.
 * Handles multi-step registration with email verification and recaptcha validation.
 * Creates churchadmin users and manages subscription plans during registration.
 *
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/member/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function list()
    {
        $state = State::get();
        $state = StateResource::collection($state);
        $city = City::get();
        $city = CityResource::collection($city)->groupby('state_id');
        $country = Country::where('status', 1)->get();
        $countrylist = CountryResource::collection($country)->keyBy('short_name');

        $array = [];

        $array['statelist'] = $state;
        $array['citylist']  = $city;
        $array['countrylist']  = $countrylist;


        return $array;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function stepOne(RegisterStepOneRequest $request)
    {
        //
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function stepTwo(RegisterStepTwoRequest $request)
    {
        //
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function create(array $data)
    {
        \DB::beginTransaction();
        try {
            $church = new Church;

            $church->name          = $data['church_name'];
            $church->country_id       = $data['country_id'];
            $church->address       = $data['address'];
            $church->city_id       = $data['city_id'];
            $church->state_id      = $data['state_id'];
            $church->pincode       = $data['pincode'];
            $church->slug          = Str::slug($data['church_name'], '-');
            $church->status        = 1;
            $church->created_at    = Carbon::now();
            $church->updated_at    = Carbon::now();

            $church->save();

            $user = new User;

            $user->church_id                = $church->id;
            $user->usergroup_id             = 3;
            $user->name                     = $data['name'];
            $user->email                    = $data['email'];
            $user->mobile_no                = $data['mobile_no'];
            $user->password                 = Hash::make($data['password']);
            $user->email_verification_code  = Str::random(40);
            $user->created_at               = Carbon::now();
            $user->updated_at               = Carbon::now();

            $user->save();

            $userprofile = new Userprofile;

            $userprofile->user_id           = $user->id;
            $userprofile->church_id         = $church->id;
            $userprofile->firstname         = $data['name'];
            $userprofile->address           = $data['address'];
            $userprofile->city_id           = $data['city_id'];
            $userprofile->state_id          = $data['state_id'];
            $userprofile->membership_type   = "member";
            $userprofile->created_at        = Carbon::now();
            $userprofile->updated_at        = Carbon::now();

            $userprofile->save();

            $permissions = Permission::get();
            foreach ($permissions as $permission) {
                $permissionuser = new PermissionUser;

                $permissionuser->permission_id = $permission->id;
                $permissionuser->user_id       = $user->id;
                $permissionuser->user_type     = get_class($user);
                $permissionuser->created_at    = Carbon::now();
                $permissionuser->updated_at    = Carbon::now();

                $permissionuser->save();
            }
            if (env('MAIL_STATUS') === 'on') {
                Mail::to($user->email)->queue(new EmailVerification($user));
            }

            \DB::commit();

            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) //RegisterRequest
    {
        //
        try {
            if (request('g-recaptcha-response')) {
                event(new Registered($user = $this->create($request->all())));

                $this->guard()->login($user);

                return $this->registered($request, $user) ?: redirect($this->redirectPath());
            } else {
                return redirect()->back()->with('failmessage', 'Captcha Is Required');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
