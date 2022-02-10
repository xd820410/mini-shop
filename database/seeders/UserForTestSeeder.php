<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserForTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(UserService $userService)
    {
        $userService->truncate();
        $data = [
            'name' => 'test_man',
            'email' => config('app.admin_account', 'test@huhu543.click'),
            'password' => Hash::make(config('app.admin_password', 'test1234')),
        ];
        $userService->create($data);
    }
}
