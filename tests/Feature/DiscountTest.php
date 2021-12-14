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
use App\Repositories\CartRepository;

class DiscountTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.mysql.database' => env('DB_TEST_DATABASE', 'test_dbname')
        ]);

        //seed裡面是 9527每兩個8折
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
     * 檢查優惠區間是否生效(ForUserCart)
     * @dataProvider cartForTimeIntervalOfDiscount
     * @return void
     */
    public function test_timeIntervalForUserCart($rawCart, $goodsData, $now, $expectedTotal)
    {
        $this->partialMock(CartRepository::class, function (MockInterface $mock) use ($rawCart) {
            $userCart = [];
            $userCart['payload'] = $rawCart;
            $mock->shouldReceive('getByUserId')
                ->once()
                ->andReturn($userCart);
        });

        $this->partialMock(GoodsRepository::class, function (MockInterface $mock) use ($goodsData) {
            $mock->shouldReceive('getByIds')
                ->once()
                ->andReturn(collect($goodsData));
        });

        Carbon::setTestNow($now);
        //↓It works.
        //dump('now: ', Carbon::now());
        $this->login();
        $response = $this->get('/cart');
        $response->assertJson([
            'total' => $expectedTotal
        ], true);
    }

    /**
     * 檢查折扣數量門檻
     * @dataProvider cartForQuantityThreshold
     * @return void
     */
    public function test_quantityThreshold($rawCart, $goodsData, $expectedTotal)
    {
        $this->partialMock(CartService::class, function (MockInterface $mock) use ($rawCart) {
            $mock->shouldReceive('getSessionCart')
                ->once()
                ->andReturn($rawCart);
        });

        $this->partialMock(GoodsRepository::class, function (MockInterface $mock) use ($goodsData) {
            $mock->shouldReceive('getByIds')
                ->once()
                ->andReturn(collect($goodsData));
        });

        $response = $this->get('/cart');
        $response->assertJson([
            'total' => $expectedTotal
        ], true);
    }

    /**
     * 檢查折扣數量門檻(ForUserCart)
     * @dataProvider cartForQuantityThreshold
     * @return void
     */
    public function test_quantityThresholdForUserCart($rawCart, $goodsData, $expectedTotal)
    {
        $this->partialMock(CartRepository::class, function (MockInterface $mock) use ($rawCart) {
            $userCart = [];
            $userCart['payload'] = $rawCart;
            $mock->shouldReceive('getByUserId')
                ->once()
                ->andReturn($userCart);
        });

        $this->partialMock(GoodsRepository::class, function (MockInterface $mock) use ($goodsData) {
            $mock->shouldReceive('getByIds')
                ->once()
                ->andReturn(collect($goodsData));
        });

        $this->login();
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

    public function login()
    {
        $this->postJson('/login', [
            'email' => config('app.admin_account', 'enter admin account'),
            'password' => config('app.admin_password', 'enter admin password'),
            '_token' => csrf_token()
        ]);
    }

    /**
     * @return array[]
     */
    public function cartForQuantityThreshold()
    {
        return [
            [
                [
                    'goods_9527' => [
                        'goods_id' => "9527",
                        'quantity' => 3,
                    ],
                    'goods_9528' => [
                        'goods_id' => "9528",
                        'quantity' => 1,
                    ],
                ],
                [
                    [
                        'id' => 9527,
                        'title' => 'Goods for test',
                        'description' => null,
                        'price' => 100,
                        'image_path' => null
                    ],
                    [
                        'id' => 9528,
                        'title' => 'Goods for test2',
                        'description' => null,
                        'price' => 1000,
                        'image_path' => null
                    ]
                ],
                1260
            ],
            [
                [
                    'goods_9527' => [
                        'goods_id' => "9527",
                        'quantity' => 6,
                    ],
                    'goods_9528' => [
                        'goods_id' => "9528",
                        'quantity' => 1,
                    ],
                ],
                [
                    [
                        'id' => 9527,
                        'title' => 'Goods for test',
                        'description' => null,
                        'price' => 100,
                        'image_path' => null
                    ],
                    [
                        'id' => 9528,
                        'title' => 'Goods for test2',
                        'description' => null,
                        'price' => 1000,
                        'image_path' => null
                    ]
                ],
                1480
            ]
            ,
            [
                [
                    'goods_9527' => [
                        'goods_id' => "9527",
                        'quantity' => 7,
                    ],
                    'goods_9528' => [
                        'goods_id' => "9528",
                        'quantity' => 1,
                    ],
                ],
                [
                    [
                        'id' => 9527,
                        'title' => 'Goods for test',
                        'description' => null,
                        'price' => 100,
                        'image_path' => null
                    ],
                    [
                        'id' => 9528,
                        'title' => 'Goods for test2',
                        'description' => null,
                        'price' => 1000,
                        'image_path' => null
                    ]
                ],
                1580
            ]
        ];
    }
}