<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'min:8|confirmed',
            'no_hp' => 'integer',
        ]);

        if ($request->has('no_hp') && !is_numeric($request->no_hp)) {
            Alert::error('Gagal diupdate', 'Error');
            return redirect('profile');
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->alamat = $request->input('alamat');
        $user->no_hp = $request->input('no_hp');

        if(empty($user->name) || empty($user->email) || empty($user->alamat) || empty($user->no_hp)) {
            Alert::error('Gagal diupdate', 'Error');
            return redirect('profile');
        }

        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->update();
        Alert::success('Sukses diupdate', 'Success');
        return redirect('profile');
    }
}


