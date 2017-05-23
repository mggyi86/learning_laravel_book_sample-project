<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use DB;
use App\User;
use Redirect;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('user.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile;
        return view('user.show', compact('user', 'profile'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->updateUser($user, $request);

        alert()->success('Congrats!', 'You updated a user');

        return Redirect::route('user.show', ['user' => $user]);
    }


    public function destroy($id)
    {
        User::destroy($id);
        alert()->overlay('Attention!', 'You deleted a user', 'error');
        return Redirect::route('user.index');
    }
}
