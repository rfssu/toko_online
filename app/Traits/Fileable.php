<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait Fileable
{
    protected $files = [];
    protected $disableAutoSaveFiles = false;

    /**
     * Get single file by field
     */
    public function file($field, $reset = false)
    {
        if ($reset || !isset($this->files[$field])) {
            $file = File::where([
                'parent_id'     => $this->id,
                'parent_table'  => $this->getTable(),
                'parent_field'  => $field,
            ])
                ->orderBy('id', 'desc')
                ->first();

            if (empty($file)) {
                $file = new File();
                $file->fill([
                    'parent_id'     => $this->id,
                    'parent_table'  => $this->getTable(),
                    'parent_field'  => $field,
                ]);
            }

            $this->files[$field] = $file;
        }

        return $this->files[$field];
    }

    /**
     * Get multiple files by field
     */
    public function files($field, $reset = false)
    {
        $key = $field . '_collection'; // Tambah suffix untuk membedakan dengan single file

        if ($reset || !isset($this->files[$key])) {
            $this->files[$key] = File::where([
                'parent_id'     => $this->id,
                'parent_table'  => $this->getTable(),
                'parent_field'  => $field,
            ])
                ->orderBy('id', 'desc')
                ->get();
        }

        return $this->files[$key];
    }

    /**
     * Load all files
     */
    public function loadAllFiles()
    {
        $allFiles = File::where([
            'parent_id' => $this->id,
            'parent_table' => $this->getTable()
        ])
            ->orderBy('id', 'desc')
            ->get();

        foreach ($allFiles as $file) {
            $field = $file->parent_field;

            if (!isset($this->files[$field])) {
                $this->files[$field] = $file;
            }
        }

        return $this->files;
    }

    /**
     * Save all uploaded files from request
     */
    public function saveAllFiles($request = null, $replaceFiles = false)
    {
        $request = $request ?: request();
        $files = $request->allFiles();

        foreach ($files as $field => $uploadedFiles) {
            // Process upload_* fields
            if (strpos($field, 'upload_') === 0) {
                $parentField = str_replace("upload_", "", $field);

                // Delete existing files if replace mode
                if ($replaceFiles) {
                    $this->deleteFilesByField($parentField);
                }

                // Handle multiple files
                if (is_array($uploadedFiles)) {
                    foreach ($uploadedFiles as $uploadedFile) {
                        if ($uploadedFile instanceof \Illuminate\Http\UploadedFile) {
                            $this->saveFile($uploadedFile, $parentField);
                        }
                    }
                }
                // Handle single file
                elseif ($uploadedFiles instanceof \Illuminate\Http\UploadedFile) {
                    $this->saveFile($uploadedFiles, $parentField);
                }
            }
        }
    }

    /**
     * Save single file
     */
    protected function saveFile($uploadedFile, $field)
    {
        if (!$uploadedFile instanceof \Illuminate\Http\UploadedFile) {
            return null;
        }

        try {
            if (!$uploadedFile->isValid()) {
                Log::warning('Upload file is not valid');
                return null;
            }

            $path = date('Y/m/d');
            $name = uniqid();
            $extension = $uploadedFile->getClientOriginalExtension();
            $filePath = $path . "/" . $name . "." . $extension;

            // Save to database
            $modelFile = File::create([
                'parent_id'     => $this->id,
                'parent_table'  => $this->getTable(),
                'parent_field'  => $field,
                'name'          => $uploadedFile->getClientOriginalName(),
                'mime'          => $uploadedFile->getMimeType(),
                'path'          => $filePath,
            ]);

            // Save to storage
            Storage::disk('public')->put(
                $filePath,
                file_get_contents($uploadedFile->getRealPath())
            );

            // Update cache
            $this->files[$field] = $modelFile;

            return $modelFile;
        } catch (\Exception $e) {
            Log::error('File save failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete files by field
     */
    protected function deleteFilesByField($field)
    {
        $existingFiles = File::where([
            'parent_id'     => $this->id,
            'parent_table'  => $this->getTable(),
            'parent_field'  => $field,
        ])->get();

        foreach ($existingFiles as $file) {
            $file->delete(); // Will trigger auto delete in File model boot
        }

        // Clear cache
        unset($this->files[$field]);
    }

    /**
     * Disable auto save files
     */
    public function disableAutoSaveFiles()
    {
        $this->disableAutoSaveFiles = true;
        return $this;
    }

    /**
     * Enable auto save files
     */
    public function enableAutoSaveFiles()
    {
        $this->disableAutoSaveFiles = false;
        return $this;
    }

    /**
     * Boot trait
     */
    public static function bootFileable()
    {
        static::retrieved(function ($model) {
            if (!empty($model->id)) {
                $model->loadAllFiles();
            }
        });

        static::saved(function ($model) {
            if (!$model->disableAutoSaveFiles) {
                $model->saveAllFiles();
            }
        });

        static::deleting(function ($model) {
            // Delete all files when model deleted
            File::where([
                'parent_id' => $model->id,
                'parent_table' => $model->getTable()
            ])->each(function ($file) {
                $file->delete();
            });
        });
    }
}
