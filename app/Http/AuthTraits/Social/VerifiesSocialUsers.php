<?php
namespace App\Http\AuthTraits\Social;

use App\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Auth;
use App\SocialProvider;
use App\User;

trait VerifiesSocialUsers
{
    private function authUserEmailMatches($socialUser){
        return $socialUser->email == Auth::user()->email;
    }

    private function socialIdAlreadyExists($socialUser){
        return SocialProvider::where('source_id', '=',$socialUser->id)->exists();
    }

    private function socialUserAlreadyLoggedIn()
    {
        return Auth::check();
    }

    private function socialUserHasNoEmail($socialUserEmail){
        return $socialUserEmail == null;
    }

    private function verifyProvider($provider){
        if( ! in_array($provider, $this->approvedProviders)){
            throw new UnauthorizedException;
        }
    }

    private function verifyUserIds($socialUser){
        return (SocialProvider::where('source_id', $socialUser->id)
                                ->where('user_id', Auth::id())
                                ->where('source', $this->provider)
                                ->exists())? true : false;

    }

    private function userWhereEmailMatches($socialUser){
        return User::where('email', $socialUser->email)->first();
    }

    private function matchesIds($socialUser, User $authUser){
        return $authUser->socialProviders()->where('source', $this->provider)
                                            ->where('source_id', $socialUser->id)
                                            ->first();
    }
}