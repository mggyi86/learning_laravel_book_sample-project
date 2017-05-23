<?php

namespace App\Http\AuthTraits\Social;

use App\User;
use App\SocialProvider;
use App\Exceptions\EmailAlreadyInSystemException;
use App\Exceptions\CredentialsDoNotMatchException;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TransactionFailedException;

trait FindsOrCreatesUsers{
    // is email already in table?
    private function findOrCreateUser($socialUser)
    {
        if ( $authUser = $this->userWhereEmailMatches($socialUser)) {
            // scenario where email is already in table
            // is the provider source correct and does the
            // social id match?
            // if there is a match, return $authUser,
            // if not throw exception
            if ( ! $this->matchesIds($socialUser, $authUser)) {
                throw new EmailAlreadyInSystemException;
            }
            // if email and id matches, return the $authUser
            return $authUser;
        }
        // scenario where no matching email,
        // but social id already exists
        if ($this->socialIdAlreadyExists($socialUser)) {
            throw new CredentialsDoNotMatchException;
        }
        $authUser = $this->makeNewUser($socialUser);
        return $authUser;
    }

    private function makeNewUser($socialUser)
    {
        //create user if not already exists and email does not
        // already exist.
        $password = $this->makePassword();
        DB::beginTransaction();
        try{
            $authUser = User::create([
                'name' => $this->userName,
                'email' => $socialUser->email,
                'password' => $password,
                'status_id' => 10,
            ]);
            SocialProvider::create([
                'user_id' => $authUser->id,
                'source' => $this->provider,
                'source_id' => $socialUser->id,
                'avatar' => $socialUser->avatar,
            ]);
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            throw new TransactionFailedException();
        }
        return $authUser;
    }

    private function makePassword(){
        $passwordParts = rand(). str_random(12);
        $password =  bcrypt($passwordParts);
        return $password;
    }
}