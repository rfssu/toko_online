<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use App\Models\Barang;
use App\Traits\CrudTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    use CrudTrait;
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        $models = Barang::all();

        return view('seller/pages/barangs/index', get_defined_vars());
    }

    public function form($id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new Barang;
        $user = $this->user;

        return view('seller/pages/barangs/form', data: get_defined_vars());
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new Barang;
        $params = $request->all();
        $model->validator($params, $model->rules(), [], $model->labels())->validate();
        if ($request->ajax()) {
            return;
        }
        AutoFill::fill($model, params: $params);
        $model->saveOrFail();
        return redirect()->back()->with('success', $id ? 'Update Berhasil' : 'Simpan Berhasil');
    }



    private function findModel(array $params)
    {
        return Barang::where($params)->firstOrFail();
    }
}
