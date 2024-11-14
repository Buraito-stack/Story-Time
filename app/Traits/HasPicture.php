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
        if ($directory == 'avatars') {
            if ($this->profilePicture) {
                Storage::disk('public')->delete($this->profilePicture->file_path);
                $this->profilePicture()->delete();
            }

            $filePath = $file->store($directory, 'public');
            $this->profilePicture()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
            ]);
        } else {
            if ($this->coverImage()->count() >= 5) {
                $this->coverImage()->oldest()->first()->delete();
            }

            $filePath = $file->store($directory, 'public');

            $this->coverImage()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
            ]);
        }
    }

    /**
     * Delete the current picture associated with the model.
     *
     * @return void
     */
    public function deletePicture()
    {
        if ($this->profilePicture) {
            Storage::disk('public')->delete($this->profilePicture->file_path);
            $this->profilePicture()->delete();
        }
    }

    /**
     * Define a polymorphic relationship for the profile picture.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function profilePicture()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    /**
     * Define a polymorphic relationship for cover images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function coverImage()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
