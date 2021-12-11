<?php

namespace Database\Seeders;

use App\Services\DiscountService;

use Illuminate\Database\Seeder;

class DiscountTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(DiscountService $discountService)
    {
        $discountService->truncate();
        $data = [
            'type' => 1,
            'title' => '測試兩件八折',
            'payload' => '{"affected": [9527], "threshold": 2, "discount_type": "percent", "discount_value": 20}',
            'start_at' => '2021-12-05 11:17:36',
            'end_at' => '2024-12-05 11:17:36',
        ];
        $discountService->create($data);
    }
}