<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $repository;

    /**
     * @var Paginator
     */
    protected Paginator $paginator;

    /**
     * @var Product
     */
    protected Product $dummyProduct;

    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * Setup tests
     */
    public function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->paginator = $this->createMock(Paginator::class);
        $this->validator = $this->createMock(Validator::class);
        $this->dummyProduct = new Product(
            [
                'id' => 104,
                'name' => 'Shirt',
                'description' => 'Color Purple',
                'qrcode' => '4973156489526',
                'price' => 299.99
            ]
        );

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testViewAllProductsSucceeds(): void
    {
        $this->repository->expects(self::once())
            ->method('viewAllProducts')
            ->with(10, true)
            ->willReturn($this->paginator);

        $products = $this->repository->viewAllProducts(10, true);

        $this->assertInstanceOf(Paginator::class, $products);
    }

    /**
     * @return void
     */
    public function testCreateAProductSucceeds(): void
    {
        $dummyProduct = Arr::only($this->dummyProduct->toArray(), ['name', 'description', 'qrcode', 'price']);

        $this->repository->expects(self::once())
            ->method('createProduct')
            ->with($dummyProduct)
            ->willReturn($this->dummyProduct);

        $product = $this->repository->createProduct($dummyProduct);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->dummyProduct, $product);
    }

    /**
     * @return void
     */
    public function testCreateAProductFails(): void
    {
        $dummyProduct = [
            'name' => 'T-shirt',
            'description' => 'Color White',
            'price' => 99.99
        ];

        $this->repository->expects(self::once())
            ->method('createProduct')
            ->with($dummyProduct)
            ->willThrowException(new ValidationException($this->validator));

        $this->expectException(ValidationException::class);

        $this->repository->createProduct($dummyProduct);
    }

    /**
     * @return void
     */
    public function testViewAProductSucceeds(): void
    {
        $dummyProductId = $this->dummyProduct->getAttribute('id');

        $this->repository->expects(self::once())
            ->method('viewProduct')
            ->with($dummyProductId)
            ->willReturn($this->dummyProduct);

        $product = $this->repository->viewProduct($dummyProductId);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->dummyProduct, $product);
    }

    /**
     * @return void
     * @throws ModelNotFoundException
     */
    public function testViewAProductFails(): void
    {
        $dummyProductId = $this->dummyProduct->getAttribute('id');

        $errorMessage = sprintf('No query results for models [%s] %d', Product::class, $dummyProductId);

        $this->repository->expects(self::once())
            ->method('viewProduct')
            ->with($dummyProductId)
            ->willThrowException(new ModelNotFoundException($errorMessage, ResponseStatus::HTTP_NOT_FOUND));

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionCode(ResponseStatus::HTTP_NOT_FOUND);
        $this->expectExceptionMessage($errorMessage);

        $this->repository->viewProduct($dummyProductId);
    }

    /**
     * @return void
     */
    public function testUpdateAProductSucceeds(): void
    {
        $dummyProductId = $this->dummyProduct->getAttribute('id');

        $this->dummyProduct->setUpdatedAt(now());

        $updateProduct = clone $this->dummyProduct;
        $updateProduct->setAttribute('qrcode', '6465456461315');
        $updateProduct->setAttribute('price', '100.99');

        $this->repository->expects(self::once())
            ->method('updateProduct')
            ->with($dummyProductId, $updateProduct->toArray())
            ->willReturn($updateProduct);

        $product = $this->repository->updateProduct($dummyProductId, $updateProduct->toArray());

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($updateProduct, $product);
        $this->assertNotNull($product['updated_at']);
    }

    /**
     * @return void
     */
    public function testUpdateAProductFails(): void
    {
        $dummyProductId = $this->dummyProduct->getAttribute('id');

        $updateProduct = clone $this->dummyProduct;
        $updateProduct->setAttribute('qrcode', '6465456461315');
        $updateProduct->setAttribute('price', '100.99');

        $this->repository->expects(self::once())
            ->method('updateProduct')
            ->with($dummyProductId, $updateProduct->toArray())
            ->willReturn($this->dummyProduct);

        $product = $this->repository->updateProduct($dummyProductId, $updateProduct->toArray());

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotEquals($updateProduct, $product);
        $this->assertNull($product['updated_at']);
    }

    /**
     * @return void
     */
    public function testDeleteAProductSucceeds(): void
    {
        $this->repository->expects(self::once())
            ->method('deleteProduct')
            ->with(104)
            ->willReturn(true);

        $product = $this->repository->deleteProduct(104);

        $this->assertTrue($product);
    }

    /**
     * @return void
     */
    public function testDeleteAProductFails(): void
    {
        $dummyProductId = 104;

        $this->repository->expects(self::once())
            ->method('deleteProduct')
            ->with($dummyProductId)
            ->willReturn(false);

        $product = $this->repository->deleteProduct($dummyProductId);

        $this->assertFalse($product);
    }
}
