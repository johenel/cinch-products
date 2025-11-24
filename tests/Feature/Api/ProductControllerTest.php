<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test products
        Product::query()->insert([
            [
                'name' => 'Washing Machine #1',
                'description' => 'test description washing machine',
                'price' => 450.50,
                'stock' => 23
            ],
            [
                'name' => 'Kettle #1',
                'description' => 'test description kettle',
                'price' => 25.00,
                'stock' => 10
            ],
            [
                'name' => 'Hammer #3',
                'description' => 'Hammer description',
                'price' => 75.25,
                'stock' => 5
            ]
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_index_api_with_search_query_and_min_max_price_filters(): void
    {
        $response = $this->getJson('/api/products');
        $response->assertOk();
        // Make sure that data key is available from the response
        $response->assertJsonStructure(['data']);
        // Get the actual data from data key
        $data = json_decode($response->getContent())->data;
        // Expect 3 products
        $this->assertEquals(3, count($data));

        // Test search query
        $response = $this->getJson('/api/products?' . http_build_query([
            'search' => '#1'
        ]));
        $response->assertOk();
        // Make sure that data key is available from the response
        $response->assertJsonStructure(['data']);
        // Get the actual data from data key
        $data = json_decode($response->getContent())->data;
        // Expect 2 products that will have a '#1' in their name
        $this->assertEquals(2, count($data));

        // Test min price query
        $response = $this->getJson('/api/products?' . http_build_query([
                'min_price' => 400
            ]));
        $response->assertOk();
        // Make sure that data key is available from the response
        $response->assertJsonStructure(['data']);
        // Get the actual data from data key
        $data = json_decode($response->getContent())->data;
        // Expect 1 product that will have a price of 400+
        $this->assertEquals(1, count($data));

        // Test max price query
        $response = $this->getJson('/api/products?' . http_build_query([
                'max_price' => 80
            ]));
        $response->assertOk();
        // Make sure that data key is available from the response
        $response->assertJsonStructure(['data']);
        // Get the actual data from data key
        $data = json_decode($response->getContent())->data;
        // Expect 2 products that will have a price of 80 and below
        $this->assertEquals(2, count($data));

        // Test all filters - search, min and max price
        $response = $this->getJson('/api/products?' . http_build_query([
                'min_price' => 20,
                'max_price' => 80,
                'search' => '#3'
            ]));
        $response->assertOk();
        // Make sure that data key is available from the response
        $response->assertJsonStructure(['data']);
        // Get the actual data from data key
        $data = json_decode($response->getContent())->data;
        // Expect 1 product that will have a price between 20 and 80 and have a '#3' in its name
        $this->assertEquals(1, count($data));
    }
}
