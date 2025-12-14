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
        // Filter: Seller hanya lihat pesanan yang SUDAH DIBAYAR
        $query = Pesanan::query()
            ->select('pesanans.*')
            ->join('users', 'pesanans.user_id', '=', 'users.id')
            ->whereIn('pesanans.status', ['checkout', 'siap_pickup', 'co', 'pickup']);  // Exclude pending_payment

        $models = QuerySearch::apply(
            query: $query,
            request: $request,
            searchableColumns: ['pesanans.kode', 'users.name', 'users.no_hp', 'pesanans.status'],
            filterableColumns: ['status' => 'pesanans.status'],
            perPage: 10,
            defaultSort: [
                'pesanans.status' => 'asc',
                'pesanans.created_at' => 'desc'
            ]
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
        if ($request->ajax()) {
            return;
        }
        $model->status = 'pickup';
        $model->tanggal_pickup = now();
        $model->saveOrFail();
        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    private function findModel(array $params)
    {
        return Pesanan::where($params)->firstOrFail();
    }
}
