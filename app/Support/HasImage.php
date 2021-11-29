<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateImage(UploadedFile $photo)
    {
        tap($this->{$this->imageColumn()}, function ($previous) use ($photo) {
            $this->forceFill([
                $this->imageColumn() => $photo->storePublicly(
                    $this->imageFolder(),
                    ['disk' => $this->imageDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->imageDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        $imagePath = $this->{$this->imageColumn()};

        if (! $imagePath) {
            return $this->defaultImageUrl();
        }

        return filter_var($imagePath, FILTER_VALIDATE_URL)
                    ? $imagePath
                    : Storage::disk($this->imageDisk())->url($this->{$this->imageColumn()});
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function imageDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultImageUrl()
    {
        return 'https://picsum.photos/id/1/1280/720';
    }

    /**
     * Get the column that will hold the profile photo url.
     *
     * @return string
     */
    protected function imageColumn()
    {
        return 'image_path';
    }

    /**
     * Get the folder that profile photos should be stored in.
     *
     * @return string
     */
    protected function imageFolder()
    {
        return 'images';
    }
}
