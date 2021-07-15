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

    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function updateById($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function deleteById($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
