<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;

class ProfileController extends Controller
{
    public function edit(){
        return view('profile');
    }

    public function update(UpdateProfileRequest $request){

        if (Hash::check($request->old_password, auth()->user()->password)) {
            Auth()->user()->fill([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ])->save();

            Session::flash('success', "done update profile.");
            return redirect()->route('profile');

        } else {
            return back()->withErrors([
                'old_password' => [trans('auth.failed')],
            ]);
        }

    }
}
