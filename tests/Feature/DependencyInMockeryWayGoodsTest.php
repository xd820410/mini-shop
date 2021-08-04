<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use App\Http\Controllers\GoodsController;

class DependencyInMockeryWayGoodsTest extends TestCase
{
    // 放待測物件
    protected $mock;

    // 放 mock 物件
    protected $target;

    public function setUp(): void
    {
        parent::setUp();

        $this->mock = $this->initMock(\App\Services\GoodsService::class);
        $this->target = $this->app->make(GoodsController::class);
    }

    /**
     * 可用於模擬第三方系統回傳 ex. 簡訊、發票、問庫存  等等等
     */
    public function test_mockerySample()
    {
        $this->mock->shouldReceive('updateById')
                    ->once()
                    ->andReturn(true);

        /**
         * 以此為例，我傳空的變數、不存在的id過去更新，如果service沒被我置換掉，一定會報錯
         * 但卻成功了，因為我置換掉了service，且updateById固定回傳true
         */
        $requestInput = [];
        $response = $this->patchJson('/api/goods/948787', $requestInput);
        //dd($response);
        $response->assertNoContent();
    }
}
