<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use App\Helpers\QuerySearch;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CrudTrait;

class PesananController extends Controller
{
    use CrudTrait;
    public $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index(Request $request)
    {
        $query = Pesanan::query()
            ->select('pesanans.*')->join('users', 'pesanans.user_id', '=', 'users.id');
        $models = QuerySearch::apply(
            query: $query,
            request: $request,
            searchableColumns: ['pesanans.kode', 'users.name', 'users.no_hp', 'pesanans.status'],
            filterableColumns: ['status' => 'pesanans.status'],
            perPage: 10
        );

        return view('seller/pages/pesanans/index', get_defined_vars());
    }

    public function form($id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new Pesanan;
        $user = $this->user;

        return view('seller/pages/pesanans/form', data: get_defined_vars());
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new Pesanan;
        $params = $request->all();
        $params['password'] ??= $model->password;
        $model->validator($params, $model->rules(), [], $model->labels())->validate();
        if ($request->ajax()) {
            return;
        }
        AutoFill::fill($model, params: $params);
        $model->saveOrFail();
        return redirect()->back()->with('success', 'Simpan Berhasil');
    }

    private function findModel(array $params)
    {
        return Pesanan::where($params)->firstOrFail();
    }
}
