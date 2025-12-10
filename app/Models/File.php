<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'file';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if file exists in storage
     */
    public function exist()
    {
        if (empty($this->id) || empty($this->path)) {
            return false;
        }

        return Storage::disk('public')->exists($this->path);
    }

    /**
     * Check if has file data
     */
    public function hasFile()
    {
        return !empty($this->id) && !empty($this->path);
    }

    /**
     * Get download URL
     */
    public function url()
    {
        if (empty($this->id)) {
            return null;
        }
        return route('file.download', ['file' => $this->id]);
    }

    /**
     * Get preview URL
     */
    public function preview()
    {
        if (empty($this->id)) {
            return null;
        }
        return route('file.preview', ['file' => $this->id]);
    }

    /**
     * Get storage path
     */
    public function storagePath()
    {
        if (empty($this->path)) {
            return null;
        }
        return Storage::disk('public')->path($this->path);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile()
    {
        if ($this->exist()) {
            return Storage::disk('public')->delete($this->path);
        }
        return false;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto delete file when record deleted
        static::deleting(function ($file) {
            $file->deleteFile();
        });
    }
}
