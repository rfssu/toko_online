<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        $model = $this->user;
        return view('profile.index', get_defined_vars());
    }

    public function update(Request $request, $id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new User;
        $params = $request->all();
        $params['password'] ??= $model->password;
        $params['password_confirmation'] ??= $model->password;
        $model->validator($params, $model->rules('setting'), [], $model->labels())->validate();
        if ($request->ajax()) {
            return;
        }
        AutoFill::fill($model, params: $params);
        $model->saveOrFail();
        return redirect()->back()->with('success', 'Simpan Berhasil');
    }

    private function findModel(array $params)
    {
        return User::where($params)->firstOrFail();
    }

    /**
     * Upload profile avatar
     */
    public function avatar(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Handle file upload using Fileable trait
        if ($request->hasFile('foto_profil')) {
            $user->foto_profil = $request->file('foto_profil');
            $user->save();
        }

        Alert::success('Foto profil berhasil diupdate!', 'Berhasil');
        return redirect()->route('profile');
    }
}
