<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Redirect;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $id = Auth::user()->id;

        $user = User::findOrFail($id);

        return view('settings.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $this->validate($request, [
            'name' => 'required|max:20;',
            'email' => 'required|email|max:255|unique:users,email,' .$id,
            'is_subscribed' => 'boolean',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_subscribed' => $request->is_subscribed,
        ]);

        alert()->success('Congrats!', 'You updated your user settings');
        return redirect()->action('SettingsController@edit', [$user]);
    }
}
