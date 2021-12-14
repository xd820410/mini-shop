<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Mockery\MockInterface;
use App\Services\CartService;
use Carbon\Carbon;
use App\Repositories\GoodsRepository;

class DiscountTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.mysql.database' => env('DB_TEST_DATABASE', 'test_dbname')
        ]);

        Artisan::call('db:seed --class=DiscountTestSeeder');
    }

    /**
     * 檢查優惠區間是否生效
     * @dataProvider cartForTimeIntervalOfDiscount
     * @return void
     */
    public function test_timeInterval($rawCart, $goodsData, $now, $expectedTotal)
    {
        $this->partialMock(CartService::class, function (MockInterface $mock) use ($rawCart) {
            $mock->shouldReceive('getSessionCart')
                ->once()
                ->andReturn($rawCart);
        });

        $this->partialMock(GoodsRepository::class, function (MockInterface $mock) use ($goodsData) {
            $mock->shouldReceive('getByIds')
                ->once()
                ->with([9527])
                ->andReturn(collect($goodsData));
        });

        Carbon::setTestNow($now);
        //↓It works.
        //dump('now: ', Carbon::now());
        $response = $this->get('/cart');
        $response->assertJson([
            'total' => $expectedTotal
        ], true);
    }

    /**
     * @return array[]
     */
    public function cartForTimeIntervalOfDiscount()
    {
        return [
            [
                [
                    'goods_9527' => [
                        'goods_id' => "9527",
                        'quantity' => 3,
                    ]
                ],
                [
                    [
                        'id' => 9527,
                        'title' => 'Goods for test',
                        'description' => null,
                        'price' => 100,
                        'image_path' => null
                    ]
                ],
                Carbon::now(),
                260
            ],
            [
                [
                    'goods_9527' => [
                        'goods_id' => "9527",
                        'quantity' => 3,
                    ]
                ],
                [
                    [
                        'id' => 9527,
                        'title' => 'Goods for test',
                        'description' => null,
                        'price' => 100,
                        'image_path' => null
                    ]
                ],
                new Carbon('2030-01-23 11:53:20'),
                300
            ]
        ];
    }
}