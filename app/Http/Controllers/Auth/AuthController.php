<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\NoActiveAccountException;
use App\Http\AuthTraits\Social\ManagesSocialAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends RegisterController
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

    use AuthenticatesUsers, ManagesSocialAuth;
    protected $redirectTo = '/';
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'redirectToProvider', 'handleProviderCallback']);
    }

    public function checkStatusLevel(){
        if( ! Auth::user()->isActiveStatus() ){
            Auth::logout();
            throw new NoActiveAccountException;
        }
    }

    public function redirectPath()
    {
        if(Auth::user()->isAdmin()){
            return 'admin';
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if($this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($this->attemptLogin($request)){
            $this->checkStatusLevel();

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
}