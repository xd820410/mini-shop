<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    protected $model;

    public function __construct(Cart $model)
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

    public function getByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->first();
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
