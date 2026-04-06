<?php

namespace app\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Traits\ThrottlesLogins;
use App\Traits\RedirectsUsers;
use Illuminate\Http\Request;
use App\Models\Church;
use App\Models\User;
use Validator;

/**
 * Trait AuthenticatesUsers
 *
 * Provides user authentication and login functionality including:
 * - Validating user credentials
 * - Handling login attempts with throttling
 * - Logging users in and out
 * - Retrieving the username/email field
 *
 * @package App\Traits
 */
trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Handle a login request to the application.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
*/
    protected function validateLogin(Request $request): void
    {
        $this->registerLoginValidators();

        $this->validate($request, [
            $this->username() => 'required|string',
            'password'        => 'bail|required|string|checkchurch|checkusers|checkactive|checkstatus|checkexit',
        ]);
    }

    private function registerLoginValidators(): void
    {
        Validator::extend('checkchurch',  fn () => $this->checkChurchIsActive(),   'Contact Church Admin for more Details');
        Validator::extend('checkusers',   fn () => $this->checkUserExists(),        'Invalid Credentials or Account does not exist');
        Validator::extend('checkactive',  fn () => $this->checkUserNotSuspended(),  'You are suspended by site admin');
        Validator::extend('checkexit',    fn () => $this->checkUserNotExited(),     'You have exited this church');
        Validator::extend('checkstatus',  fn () => $this->checkLoginAllowed(),      'Invalid Credentials. This account is not allowed to login. Contact Church Admin for more Details');
    }

    private function loginUser(): ?User
    {
        return User::where('email', request('email'))->with('userprofile', 'permissionUser')->first();
    }

    private function checkChurchIsActive(): bool
    {
        $user = User::where('email', request('email'))->first();

        if (!$user || $user->church_id == '') {
            return true;
        }

        return Church::IsActive($user->church_id)->exists();
    }

    private function checkUserExists(): bool
    {
        return User::where('email', request('email'))->exists();
    }

    private function checkUserNotSuspended(): bool
    {
        $user = $this->loginUser();

        return $user && optional($user->userprofile)->status !== 'inactive';
    }

    private function checkUserNotExited(): bool
    {
        $user = $this->loginUser();

        return $user && optional($user->userprofile)->status !== 'exit';
    }

    private function checkLoginAllowed(): bool
    {
        $user = $this->loginUser();

        if (!$user) {
            return false;
        }

        return match (true) {
            $user->usergroup_id == 1                                                               => true,
            $user->usergroup_id == 3 && optional($user->userprofile)->membership_type === 'member' => true,
            $user->usergroup_id == 4                                                               => true,
            $user->usergroup_id == 5                                                               => \Config::get('settings.login_status') == 1,
            $user->usergroup_id == 6                                                               => true,
            default                                                                                => false,
        };
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
