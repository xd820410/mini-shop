<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoodsTest extends TestCase
{
    use RefreshDatabase;

    public function test_getGoodsListHttpStatus()
    {
        $response = $this->get('/api/goods');
        $response->assertStatus(200);
    }

    public function test_getGoodsListResponseFormat()
    {
        $response = $this->get('/api/goods');
        $response->assertJsonStructure(['result',
                                        'message' => [
                                            '*' => [
                                                'title'
                                            ]
                                        ]]);
    }

    // /**
    //  * @dataProvider getGoodsTestData
    //  * @return void
    //  */
    // public function test_creatGoodsDefaultValue($testResource, $exceptedDescription)
    // {
    //     $response = $this->postJson('/api/goods', $testResource);

    //     //$response->decodeResponseJson()['message']['description']

    //     $response->dump();

    //     $response->assertJson([
    //                             'message' => [
    //                                 'description' => $exceptedDescription
    //                             ]
    //                         ], true);
    // }

    /**
     * @dataProvider getGoodsTestData
     * @return void
     */
    public function test_creatGoodsResponseFormat($testResource, $exceptedDescription)
    {
        $response = $this->postJson('/api/goods', $testResource);
        $response
            ->assertJsonStructure(['result',
                                    'message' => [
                                        'title'
                                    ]]);
    }

    /**
     * @return array[]
     */
    public function getGoodsTestData()
    {
        return [
            [
                ['title' => '漢堡王早餐券', 'price' => 200],
                null
            ],
            [
                ['title' => '高級肥皂一組', 'price' => 200, 'description' => '不要撿'],
                '不要撿'
            ]
        ];
    }
}
