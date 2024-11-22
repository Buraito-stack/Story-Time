<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

trait HasPicture
{
    public function uploadPicture($file, $directory = 'uploads')
    {
        $filePath = $file->store($directory, 'public');
        $this->coverImage()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
        ]);
    }

    public function deletePicture()
    {
        $this->coverImage->each(function ($image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        });
    }

    public function coverImage()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
