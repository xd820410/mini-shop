<?php

namespace App\Services;

use App\Repositories\DiscountRepository;
use Exception;

class DiscountService
{
    protected $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function create($data)
    {
        $result = $this->discountRepository->create($data);

        if (empty($result)) {
            throw new Exception('Fail to create.');
        }

        return $result;
    }

    public function getAll()
    {
        return $this->discountRepository->getAll();
    }

    public function getById($id)
    {
        $result = $this->discountRepository->getById($id);
        if (empty($result)) {
            throw new Exception('Resource not found.');
        }

        return $result;
    }

    public function updateById($id, $data)
    {
        $result = $this->discountRepository->getById($id);
        if (empty($result)) {
            throw new Exception('Resource not found.');
        }
        $this->discountRepository->updateById($id, $data);
    }

    public function deleteById($id)
    {
        $result = $this->discountRepository->getById($id);
        if (empty($result)) {
            throw new Exception('Resource not found.');
        }
        $this->discountRepository->deleteById($id);
    }
}
