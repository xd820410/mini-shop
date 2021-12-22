<?php

namespace App\Services;

use App\Repositories\GoodsRepository;
use Exception;
use Illuminate\Support\Facades\Storage;

class GoodsService
{
    protected $goodsRepository;

    public function __construct(GoodsRepository $goodsRepository)
    {
        $this->goodsRepository = $goodsRepository;
    }

    public function exportTxt($data)
    {
        //sleep(5);
        $fileName = 'goods_' . time() . '.txt';
        $content = '';
        foreach ($data as $eachRow) {
            $content .= json_encode($eachRow) . "\r\n";
        }

        Storage::put("/$fileName", $content);
    }

    public function create($data)
    {
        $result = $this->goodsRepository->create($data);

        if (empty($result)) {
            throw new Exception('Fail to create.');
        }

        return $result;
    }

    public function getAll()
    {
        return $this->goodsRepository->getAll();
    }

    public function getById($id)
    {
        $result = $this->goodsRepository->getById($id);
        if (empty($result)) {
            throw new Exception('Resource not found.');
        }

        return $result;
    }

    public function updateById($id, $data)
    {
        $result = $this->goodsRepository->getById($id);
        if (empty($result)) {
            throw new Exception('Resource not found.');
        }
        $this->goodsRepository->updateById($id, $data);
    }

    public function deleteById($id)
    {
        $result = $this->goodsRepository->getById($id);
        if (empty($result)) {
            throw new Exception('Resource not found.');
        }
        $this->goodsRepository->deleteById($id);
    }
}
