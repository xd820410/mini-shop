<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Services\GoodsService;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class MockeryWayGoodsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.mysql.database' => env('DB_TEST_DATABASE', 'test_dbname')
        ]);
    }

    public function test_mockerySample()
    {
        $this->partialMock(GoodsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateById')
                ->once()
                ->andReturn(true);
        });

        Sanctum::actingAs(
            User::factory()->create(),
            ['edit']
        );
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