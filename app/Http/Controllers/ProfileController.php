<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\AuthTraits\OwnsRecord;
use App\Profile;
use App\User;
use Redirect;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UnauthorizedException;

class ProfileController extends Controller
{
    use OwnsRecord;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['only' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::paginate(10);

        return view('profile.index', compact('profiles'));
    }

    public function determineProfileRoute(){
        $profileExists = $this->profileExists();

        if($profileExists){
            return Redirect::route('show-profile');
        }
        return view('profile.create');
    }

    public function showProfileToUser(){
        $profile = Profile::where('user_id', Auth::id())->first();

        if( ! $profile){
            return Redirect::route('profile.create');
        }

        $user = User::where('id', $profile->user_id)->first();

        if($this->userNotOwnerOf($profile)){
            throw new UnauthorizedException;
        }
        return view('profile.show', compact('profile', 'user'));
    }

    public function create()
    {
        $profileExists = $this->profileExists();
        if($profileExists){
            return Redirect::route('show-profile');
        }
        return view('profile.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|alpha_num|max:20',
            'last_name' => 'required|alpha_num|max:20',
            'gender' => 'boolean|required',
            'birthdate' => 'date|required',
        ]);

        $profileExists = $this->profileExists();

        if($profileExists){
            return Redirect::route('show-profile');
        }
        $profile = Profile::create([
           'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'user_id' => Auth::user()->id ]);
        $profile->save();

        $user = User::where('id', '=', $profile->user_id)->first();

        alert()->success('Congrats!', 'You made your profile');

        return view('profile.show', compact('profile', 'user'));

    }


    public function show($id)
    {
        $profile = Profile::findOrFail($id);

        $user = User::where('id', $profile->user_id)->first();

        if( ! $this->adminOrCurrentUserOwns($profile)){
            throw new UnauthorizedException;
        }

        return view('profile.show', compact('profile','user'));
    }


    public function edit($id)
    {
        $profile = Profile::findOrFail($id);

        if( ! $this->adminOrCurrentUserOwns($profile)){
            throw new UnauthorizedException;
        }
        return view('profile.edit', compact('profile'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|alpha_num|max:20',
            'last_name' => 'required|alpha_num|max:20',
            'gender' => 'boolean|required',
            'birthdate' => 'date|required',
        ]);

        $profile = Profile::findOrFail($id);

        if($this->userNotOwnerOf($profile)){
            throw new UnauthorizedException;
        }

        $profile->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate
        ]);
        alert()->success('Congrats', 'You updated your profile');

        return Redirect::route('profile.show', [ 'profile' => $profile ]);
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);

        if($this->userNotOwnerOf($profile)){
            throw new UnauthorizedException;
        }

        Profile::destroy($id);

        if(Auth::user()->isAdmin()){
            alert()->overlay('Attention!', 'You deleted a profile', 'error');
            return Redirect::route('profile.index');
        }
        alert()->overlay('Attention!', 'You deleted a profile', 'error');
        return Redirect::route('home');
    }

    private function profileExists(){
        $profileExists = DB::table('profiles')->where('user_id', Auth::id())->exists();
        return $profileExists;
    }
}
