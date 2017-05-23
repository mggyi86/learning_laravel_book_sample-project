<?php

namespace App\Http\AuthTraits\Social;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ConnectionNotAcceptedException;

trait RoutesSocialUser
{
    private function loginAuthUser($authUser){
        Auth::login($authUser, true);
    }

    private function logoutIfUserNotActiveStatus(){
        return $this->checkStatusLevel();
    }

    public function redirectToProvider($provider){
        return Socialite::driver($provider)->redirect();
    }

    private function redirectUser(){
        if(Auth::user()->isAdmin()){
            return redirect()->route('admin');
        }
        return redirect()->route('home');
    }

    private function getUserFromSocialite($provider){
        try{
            $socialUser = Socialite::driver($provider)->user();
            return $socialUser;
        }catch (\Exception $e){
            throw new ConnectionNotAcceptedException;
        }
    }
}