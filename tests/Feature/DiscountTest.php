<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}