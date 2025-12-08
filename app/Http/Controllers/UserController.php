<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use App\Models\User;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use CrudTrait;
    public $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        $models = User::all();

        return view('seller/pages/users/index', get_defined_vars());
    }

    public function form($id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new User;
        $user = $this->user;
        
        return view('seller/pages/users/form', data: get_defined_vars());
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new User;
        $params = $request->all();
        $params['password'] ??= $model->password;
        $model->validator($params, $model->rules(), [], $model->labels())->validate();
        if ($request->ajax() && $request->wantsJson()) {
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
}
