<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class Upload
{
    /**
     * @param UploadedFile $uploadedFile
     * @return string
     */
    public static function process(UploadedFile $uploadedFile): string
    {
        $imageName = 'uploads/images/' . time(). '.' . $uploadedFile->clientExtension();
        $uploadedFile->move(public_path('uploads/images'), $imageName);
        return $imageName;
    }
}
