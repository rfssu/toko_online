<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use App\Helpers\QuerySearch;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Traits\CrudTrait;

class PesananController extends Controller
{
    use CrudTrait;
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        // Filter: Seller hanya lihat pesanan yang SUDAH DIBAYAR
        $query = Pesanan::query()
            ->select('pesanans.*')
            ->leftJoin('users', 'pesanans.user_id', '=', 'users.id')
            ->leftJoin('users as pic', 'pesanans.pic', '=', 'pic.id')
            ->whereIn('pesanans.status', ['co', 'pickup']);  // Exclude pending_payment

        $models = QuerySearch::apply(
            query: $query,
            request: $request,
            searchableColumns: ['pesanans.kode', 'users.name', 'users.no_hp', 'pesanans.status', 'pic.name'],
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

        $oldStatus = $model->status;
        $model->status = 'pickup';
        $model->tanggal_pickup = now();
        $model->pic = $this->user->id;
        $model->saveOrFail();

        // Send pickup ready email if status changed from 'co' to 'pickup'
        if ($oldStatus === 'co' && $model->status === 'pickup') {
            try {
                $pesanan = $model->load('pesanan_detail.barang', 'user');

                Mail::send('emails.order-ready-pickup', [
                    'pesanan' => $pesanan
                ], function ($message) use ($pesanan) {
                    $message->to($pesanan->user->email);
                    $message->subject('Pesanan #' . $pesanan->kode . ' Siap Diambil - Toko Online Khas Jogja');
                });
            } catch (\Exception $e) {
                // Log error but don't fail the order confirmation
                \Log::error('Pickup ready email failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    private function findModel(array $params)
    {
        return Pesanan::where($params)->firstOrFail();
    }
}
