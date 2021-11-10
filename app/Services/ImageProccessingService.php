<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageProccessingService
{
    public function squareAndSave($tempImage)
    {
        $image = Image::make($tempImage);
        $resizeFlag = false;

        $trashWordArray = ['A', 'B', 'C'];
        //prevent from the same file name
        $imageName = pathinfo($tempImage->getClientOriginalName(), PATHINFO_FILENAME) . Arr::random($trashWordArray) . Arr::random($trashWordArray) . time() . '.' . $tempImage->getClientOriginalExtension();
        //return $imageName;

        if ($image->width() > $image->height()) { 
            $width = $image->width();
            $height = $image->width();
            $resizeFlag = true;
        } else if ($image->height() > $image->width()) { 
            $width = $image->height();
            $height = $image->height();
            $resizeFlag = true;
        }

        if ($resizeFlag === true) {
            $image->resizeCanvas($width, $height, 'center', false, '#ffffff');
            $image->save(storage_path('app/public/images/goods/' . $imageName));
        } else {
            Storage::putFileAs(
                'public/images/goods', $tempImage, $imageName
            );
        }
        $imagePath = '/storage/images/goods/' . $imageName;

        return $imagePath;
    }
}
