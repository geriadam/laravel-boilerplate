<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Events\User\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Rules\Captcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(homeRoute());
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'max:255', 'string'],
            'password' => array_merge(['max:100'], PasswordRules::login()),
            'g-recaptcha-response' => ['required_if:captcha_status,true', new Captcha],
        ], [
            'g-recaptcha-response.required_if' => __('validation.required', ['attribute' => 'captcha']),
        ]);
    }

    /**
     * Overidden for 2FA
     * https://github.com/DarkGhostHunter/Laraguard#protecting-the-login.
     *
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        try {
            return $this->guard()->attempt(
                $this->credentials($request),
                $request->filled('remember')
            );
        } catch (HttpResponseException $exception) {
            $this->incrementLoginAttempts($request);

            throw $exception;
        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  Request  $request
     * @param $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (! $user->isActive()) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashDanger(__('Your account has been deactivated.'));
        }

        event(new UserLoggedIn($user));

        if (config('boilerplate.access.user.single_login')) {
            auth()->logoutOtherDevices($request->password);
        }
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
        $errors = [$this->username() => ["This email does not seem to exist. <a href='" . route('frontend.auth.register') . "'>Signup</a> or <a href='" . route('frontend.auth.password.request') . "'>reset your password</a>"]];

         // Load user from database
        $user = \App\Domains\Auth\Models\User::where($this->username(), $request->{$this->username()})->first();
    
        if ($user && !\Hash::check($request->password, $user->password)) {
            $errors = ['password' => ["That’s not the right password. <a href='" . route('frontend.auth.password.request') . "'>Forgot Password</a>"]];
        }

        throw ValidationException::withMessages($errors);
    }
}
