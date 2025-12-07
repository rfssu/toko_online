<?php

namespace App\Http\Controllers;

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
        $users = User::all();
        return view('seller/pages/users/index', get_defined_vars());
    }

    public function form($id = null)
    {
        $models = $id ? $this->findModel(['id' => $id]) : new User;
        $user = $this->user;

        return view('user.form', get_defined_vars());
    }

    private function findModel(array $params)
    {
        return User::where($params)->firstOrFail();
    }
}
