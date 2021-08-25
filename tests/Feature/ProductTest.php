<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test view all products.
     *
     * @return void
     */
    public function testViewingAllProducts()
    {
        $quantity = 10;

        $products = Product::factory()
            ->count($quantity)
            ->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertJsonCount($quantity, 'data');
        $this->assertJsonStringEqualsJsonString($products->toJson(), json_encode($response->json('data')));
    }


    /**
     * Test creating a new product.
     *
     * @return void
     */
    public function testCreatingANewProduct()
    {
        $product = Product::factory()->make(['id' => 101]);

        $response = $this->post(route('products.store'), $product->toArray());

        $response->assertCreated();
        $this->assertJsonStringEqualsJsonString($product->toJson(), json_encode($response->json('data')));
    }

    /**
     * Test viewing a product.
     *
     * @return void
     */
    public function testViewingAProduct()
    {
        $productId = 101;
        $product = Product::factory()->create(['id' => $productId]);

        $response = $this->get(route('products.show', $productId));

        $response->assertOk();
        $this->assertJsonStringEqualsJsonString($product->toJson(), json_encode($response->json('data')));
    }

    /**
     * Test updating a product.
     *
     * @return void
     */
    public function testUpdatingAProduct()
    {
        $product = Product::factory()->create(['name' => 'T-shirt color Blue']);
        $product->name = 'T-shirt color White';

        $response = $this->patch(route('products.update', $product->id), $product->toArray());
        $jsonResponse = $response->json('data');

        $product->updated_at = $jsonResponse['updated_at'];

        $response->assertOk();
        $this->assertEquals($product->name, $jsonResponse['name']);
        $this->assertJsonStringEqualsJsonString($product->toJson(), json_encode($jsonResponse));
    }

    /**
     * Test deleting a product.
     *
     * @return void
     */
    public function testDeletingAProduct()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product->id));

        $response->assertNoContent();
    }
}
