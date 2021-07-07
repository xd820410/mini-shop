<?php

namespace App\Services;

use App\Repositories\GoodsRepository;
use Exception;

class GoodsService
{
    protected $goodsRepository;

    public function __construct(GoodsRepository $goodsRepository)
    {
        $this->goodsRepository = $goodsRepository;
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
}
