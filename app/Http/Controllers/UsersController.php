<?php

namespace App\Http\Controllers;

use App\Rules\Passwordmatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    //
    public function index($id)
    {
        if($id != Auth::user()->id)
            return redirect()->route('profile', [ 'id' => Auth::user()->id ]);

        return view('profile');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'password' => 'nullable|min:5|confirmed',
            'password_confirmation' => 'nullable|min:5',
            'current_password' => ['nullable', new Passwordmatch()],
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->current_password != '' && $request->password != '')
        {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('status', 'Your account details were updated successfully.');
    }

    public function delete($id)
    {
        if($id == Auth::user()->id)
        {
            $user = Auth::user();
            Auth::logout();
            $user->delete();

            return redirect('/');
        }
    }

    public function update_pagination(Request $request, $id)
    {
        if(Auth::user()->id === (int)$id)
        {
            $this->validate($request, [
                'pagination' => 'required|integer|min:5|max:100',
            ]);

            $user = Auth::user();
            $user->pagination = $request->pagination;
            $user->save();

            return redirect('/')->withInput();
        }
    }

}
