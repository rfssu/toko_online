<?php

namespace App\Http\Controllers;

use App\Helpers\AutoFill;
use App\Helpers\QuerySearch;
use App\Models\Barang;
use App\Traits\CrudTrait;
use App\Imports\BarangImport;
use App\Exports\BarangTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    use CrudTrait;
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index(Request $request)
    {
        $models = QuerySearch::apply(
            query: Barang::query(),
            request: $request,
            searchableColumns: ['nama_barang'],
            perPage: 10
        );

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
        $params = $request->all();

        if ($id) {
            $model = $this->findModel(['id' => $id]);
            $model->stok = $params['stok_fisik'] + ($model->stok - $model->stok_fisik);
        } else {
            $model = new Barang;
            $model->stok = $params['stok_fisik'];
        }
        $model->validator($params, $model->rules(), [], $model->labels())->validate();
        if ($request->ajax()) {
            return;
        }
        AutoFill::fill($model, params: $params);
        $model->saveOrFail();
        return redirect()->back()->with('success', $id ? 'Update Berhasil' : 'Simpan Berhasil');
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new BarangTemplateExport,
            'Template_Import_Barang_' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Import barang from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV (.csv)',
            'file.max' => 'Ukuran file maksimal 2MB'
        ]);

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));

            if (!empty($import->errors)) {
                $errorMessage = 'Import selesai dengan ' . count($import->errors) . ' error:<br>';
                foreach ($import->errors as $error) {
                    $errorMessage .= '- ' . $error . '<br>';
                }
                return redirect()->back()->with('warning', $errorMessage);
            }

            $message = "Import berhasil! ";
            if ($import->imported > 0) {
                $message .= "{$import->imported} produk baru ditambahkan. ";
            }
            if ($import->updated > 0) {
                $message .= "{$import->updated} produk diupdate (stok ditambahkan).";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with(
                'error',
                'Import gagal: ' . $e->getMessage()
            );
        }
    }

    private function findModel(array $params)
    {
        return Barang::where($params)->firstOrFail();
    }
}
