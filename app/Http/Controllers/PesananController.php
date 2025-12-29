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
            ->whereIn('pesanans.status', ['preparing', 'ready', 'co', 'pickup']);  // All paid statuses

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
        return Pesanan::with(['user', 'pesanan_detail.barang'])
            ->where($params)
            ->firstOrFail();
    }

    /**
     * Show QR code scanner page
     */
    public function showScanner()
    {
        return view('seller.pages.pesanans.scanner');
    }

    /**
     * Verify QR code and return order details
     */
    public function verifyQr(Request $request)
    {
        try {
            // Decrypt QR data
            $data = decrypt($request->qr_data);

            // Find order
            $pesanan = Pesanan::with('pesanan_detail.barang', 'user')
                ->where('kode', $data['kode'])
                ->where('id', $data['id'])
                ->firstOrFail();

            // Verify status (must be paid)
            if ($pesanan->status === 'pending_payment') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan belum dibayar'
                ], 400);
            }

            // Check if already picked up
            if ($pesanan->status === 'pickup') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan sudah diambil pada ' . $pesanan->tanggal_pickup->format('d M Y H:i'),
                    'pesanan' => $pesanan
                ], 400);
            }

            return response()->json([
                'success' => true,
                'pesanan' => $pesanan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid'
            ], 400);
        }
    }

    /**
     * Confirm pickup from QR scanner
     */
    public function confirmPickupFromQr(Request $request)
    {
        try {
            $pesanan = Pesanan::findOrFail($request->pesanan_id);

            // Update status
            $oldStatus = $pesanan->status;
            $pesanan->status = 'pickup';
            $pesanan->tanggal_pickup = now();
            $pesanan->pic = $this->user->id;
            $pesanan->save();

            // Send pickup ready email if status changed from 'co' to 'pickup'
            if ($oldStatus === 'co' && $pesanan->status === 'pickup') {
                try {
                    $pesananFull = $pesanan->load('pesanan_detail.barang', 'user');

                    Mail::send('emails.order-ready-pickup', [
                        'pesanan' => $pesananFull
                    ], function ($message) use ($pesananFull) {
                        $message->to($pesananFull->user->email);
                        $message->subject('Pesanan #' . $pesananFull->kode . ' Selesai - Toko Online Khas Jogja');
                    });
                } catch (\Exception $e) {
                    \Log::error('Pickup completion email failed: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pickup berhasil dikonfirmasi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark order as ready for pickup (admin action)
     */
    public function markReady($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            // Only allow if currently preparing
            if ($pesanan->status !== Pesanan::STATUS_PREPARING) {
                return back()->with('error', 'Order harus dalam status "Sedang Disiapkan"');
            }

            // Mark as ready (will send email automatically)
            $pesanan->markAsReady();

            return back()->with('success', 'Order ditandai siap pickup. Email notifikasi telah dikirim ke customer.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
