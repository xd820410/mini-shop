<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Repositories\GoodsRepository;

class ImageProccessingService
{
    protected $goodsRepository;

    public function __construct(GoodsRepository $goodsRepository)
    {
        $this->goodsRepository = $goodsRepository;
    }

    public function deleteGoodsImageByGoodsId($goodsId)
    {
        $goodsData = $this->goodsRepository->getById($goodsId);
        if (empty($goodsData)) {
            throw new Exception('Resource not found.');
        }

        if (!empty($goodsData['image_path'])) {
            //path sampe: /storage/images/goods/snow_sanpaiBC1636566542.jpg
            $imagePathSplit = explode('/storage', $goodsData['image_path'], 2);
            Storage::delete('public' . $imagePathSplit[1]);
        }
    }

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
