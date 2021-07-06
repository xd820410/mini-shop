<?php

namespace App\Repositories;

use App\Models\Goods;

class GoodsRepository
{
    protected $model;

    public function __construct(Goods $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function getAll()
    {
        return $this->model->get();
    }
}
