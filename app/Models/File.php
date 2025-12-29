<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'file';
    protected $fillable = [
        'parent_id',
        'parent_table',
        'parent_field',
        'name',
        'mime',
        'path',
        'keterangan',
        'full_url', // ← For Spaces CDN URL
    ];

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

        // Check if Spaces URL (CDN URL or direct)
        if (!empty($this->full_url) && str_contains($this->full_url, 'digitaloceanspaces.com')) {
            return true; // Spaces file assumed to exist
        }

        // Fallback: check on Spaces disk
        try {
            return Storage::disk('spaces')->exists($this->path);
        } catch (\Exception $e) {
            // Fallback to local storage for old files
            return Storage::disk('public')->exists($this->path);
        }
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

        // Return CDN URL directly if exists
        if (!empty($this->full_url)) {
            return $this->full_url;
        }

        // Fallback: generate Spaces URL
        if (!empty($this->path)) {
            try {
                return Storage::disk('spaces')->url($this->path);
            } catch (\Exception $e) {
                // Fallback to local preview route
                return route('file.preview', ['file' => $this->id]);
            }
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
            if (!empty($file->path)) {
                try {
                    // Try delete from Spaces first
                    Storage::disk('spaces')->delete($file->path);
                } catch (\Exception $e) {
                    // Fallback: delete from local storage
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                }
            }
        });
    }
}
