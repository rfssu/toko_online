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

    /*public function save(Request $request, $id = null)
    {
        $model = $id ? $this->findModel(['id' => $id]) : new Barang;
        $params = $request->all();
        $model->validator($params, $model->rules(), [], $model->labels())->validate();
        if ($request->ajax() && $request->wantsJson()) {
            return;
        }
        AutoFill::fill($model, params: $params);
        $model->saveOrFail();
        return redirect()->back()->with('success', 'Simpan Berhasil');
    }*/

    // --- PENGGANTI FUNGSI SAVE (UNTUK DATA BARU) ---
    public function store(Request $request)
    {
        // 1. Validasi Manual (Lebih aman daripada di Model)
        $request->validate([
            'nama_barang' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $model = new Barang;
        $params = $request->all();

        // 2. Logika Upload Gambar
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('barangs', 'public');
            $params['gambar'] = $path;
        }

        // 3. Simpan
        AutoFill::fill($model, $params);
        $model->saveOrFail();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil disimpan');
    }

    // --- PENGGANTI FUNGSI SAVE (UNTUK UPDATE DATA) ---
    public function update(Request $request, $id)
    {
        $model = $this->findModel(['id' => $id]);

        $request->validate([
            'nama_barang' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $params = $request->all();

        // 2. Logika Update Gambar
        if ($request->hasFile('gambar')) {
            if ($model->gambar && Storage::disk('public')->exists($model->gambar)) {
                Storage::disk('public')->delete($model->gambar);
            }
            
            $path = $request->file('gambar')->store('barangs', 'public');
            $params['gambar'] = $path;
        } else {
            unset($params['gambar']);
        }

        // 3. Simpan
        AutoFill::fill($model, $params);
        $model->saveOrFail();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diupdate');
    }

    // --- HAPUS DATA ---
    public function destroy($id)
    {
        $model = $this->findModel(['id' => $id]);
        
        if ($model->gambar && Storage::disk('public')->exists($model->gambar)) {
            Storage::disk('public')->delete($model->gambar);
        }
        
        $model->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus');
    }


    private function findModel(array $params)
    {
        return Barang::where($params)->firstOrFail();
    }
}
