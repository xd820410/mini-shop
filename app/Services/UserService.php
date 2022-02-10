<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function truncate()
    {
        $result = $this->userRepository->truncate();

        return $result;
    }

    public function create($data)
    {
        $result = $this->userRepository->create($data);

        if (empty($result)) {
            throw new Exception('Fail to create.');
        }

        return $result;
    }
}
