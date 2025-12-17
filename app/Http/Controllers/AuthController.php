<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required', 'password' => 'required']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['email' => 'Salah email atau password']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $params = $request->all();
        $model = new User;
        $params['role'] = 'buyer';
        $params['status'] = 'on';
        $model->validator($params, $model->rules(), [], $model->labels())->validate();
        if ($request->ajax()) {
            return;
        }
        AutoFill::fill($model, params: $params);
        $model->saveOrFail();

        Auth::login($model);
        return redirect()->route('home')->with('success', 'Registrasi Berhasil');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
