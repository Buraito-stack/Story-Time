<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

trait HasPicture
{
    /**
     * Upload a picture and associate it with the model.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @return void
     */
    public function uploadPicture($file, $directory = 'uploads')
    {
        if ($this->picture) {
            Storage::disk('public')->delete($this->picture->file_path);
            $this->picture()->delete();
        }

        $filePath = $file->store($directory, 'public');

        $this->picture()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
        ]);
    }

    /**
     * Delete the current picture associated with the model.
     *
     * @return void
     */
    public function deletePicture()
    {
        if ($this->picture) {
            Storage::disk('public')->delete($this->picture->file_path);
            $this->picture()->delete();
        }
    }

    /**
     * Define a polymorphic relationship for the picture.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function picture()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
