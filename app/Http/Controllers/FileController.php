<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Get files by parent
    */
    public function index(Request $request)
    {
        $query = File::query();

        // Filter by parent
        if ($request->parent_table && $request->parent_id) {
            $query->where('parent_table', $request->parent_table)
                ->where('parent_id', $request->parent_id);

            if ($request->parent_field) {
                $query->where('parent_field', $request->parent_field);
            }
        }

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $files = $query->latest()->get();

        return response()->json([
            'success' => true,
            'file' => $files
        ]);
    }

    /**
     * Get single file
     */
    public function show(File $file)
    {
        return response()->json([
            'success' => true,
            'file' => $file,
            'exists' => $file->exist()
        ]);
    }

    /**
     * Download file
     */
    public function download(File $file)
    {
        if (!$file->exist()) {
            abort(404, 'File not found');
        }

        return response()->download(
            $file->storagePath(),
            $file->name
        );
    }

    /**
     * Preview file
     */
    public function preview(File $file)
    {
        if (!$file->exist()) {
            abort(404, 'File not found');
        }

        $filePath = $file->storagePath();
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType
        ]);
    }

    /**
     * Upload file
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'parent_id' => 'required|integer',
            'parent_table' => 'required|string',
            'parent_field' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $uploadedFile = $request->file('file');

        try {
            $path = date('Y/m/d');
            $name = uniqid();
            $extension = $uploadedFile->getClientOriginalExtension();
            $filePath = $path . "/" . $name . "." . $extension;

            // Save to database
            $file = File::create([
                'parent_id'     => $request->parent_id,
                'parent_table'  => $request->parent_table,
                'parent_field'  => $request->parent_field,
                'name'          => $uploadedFile->getClientOriginalName(),
                'mime'          => $uploadedFile->getMimeType(),
                'path'          => $filePath,
                'keterangan'    => $request->keterangan,
            ]);

            // Save to storage
            Storage::disk('public')->put(
                $filePath,
                file_get_contents($uploadedFile->getRealPath())
            );

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload',
                'file' => $file
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete file
     */
    public function destroy(File $file)
    {
        try {
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hapus file gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}
