<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

trait CrudTrait
{
    public function show($id)
    {
        return App::call([$this, 'view'], get_defined_vars());
    }

    public function create()
    {
        return App::call([$this, 'form'], get_defined_vars());
    }

    public function edit($id)
    {
        return App::call([$this, 'form'], get_defined_vars());
    }

    public function store()
    {
        if (method_exists($this, 'save')) {
            return App::call([$this, 'save'], get_defined_vars());
        } else {
            return App::call([$this, 'form'], get_defined_vars());
        }
    }

    public function update($id)
    {
        if (method_exists($this, 'save')) {
            return App::call([$this, 'save'], get_defined_vars());
        } else {
            return App::call([$this, 'form'], get_defined_vars());
        }
    }

    public function destroy($id)
    {
        return App::call([$this, 'delete'], get_defined_vars());
    }

    public function delete($id, Request $request)
    {
        $model = $this->findModel(['id' => $id]);

        if (method_exists($this, 'canAccess')) {
            abort_unless($this->canAccess('delete', $model), 403);
        }

        try {
            $model->deleteOrFail();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Proses Hapus Gagal, Data Masih Digunakan');
        }

        if ($request->ajax()) {
            return;
        }

        return redirect()->back()->with('success', 'Proses Hapus Berhasil');
    }
}
