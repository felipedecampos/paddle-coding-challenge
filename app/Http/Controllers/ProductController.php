<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResourceCollection;
use App\Repositories\ProductRepository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $repository;

    /**
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ProductResourceCollection
     */
    public function index(Request $request): ProductResourceCollection
    {
        // TODO: Could implement a sorting feature
        $products = $this->repository->viewAllProducts(10, true);

        return new ProductResourceCollection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProductRequest  $request
     *
     * @return ProductCollection
     */
    public function store(StoreProductRequest $request): ProductCollection
    {
        // TODO: If there is relationship to be store, could implement transaction
        $product = $this->repository->createProduct($request->toArray());

        return new ProductCollection($product);
    }

    /**
     * Display the specified resource.
     *
     * @param int $productId
     *
     * @return ProductCollection
     */
    public function show(int $productId): ProductCollection
    {
        $product = $this->repository->viewProduct($productId);

        return new ProductCollection($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     *
     * @param int $productId
     *
     * @return ProductCollection
     */
    public function update(UpdateProductRequest $request, int $productId): ProductCollection
    {
        // TODO: If there is relationship to be updated, could implement transaction
        $product = $this->repository->updateProduct($productId, $request->toArray());

        return new ProductCollection($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $productId
     *
     * @return Response
     */
    public function destroy(int $productId): Response
    {
        // TODO: If there is relationship to be deleted, could implement transaction
        $deletedProduct = $this->repository->deleteProduct($productId);

        /**
         * TODO: Could implement more types of HTTP response for each case, such as:
         * - Resource does not exist: 404 Not Found
         * - Resource already deleted: 410 Gone
         * - Users does not have permission: 403 Forbidden
         * - Method Not Allowed: 405 Method Not Allowed
         * - Conflict (User can resolve the conflict and delete): 409 Conflict
         */
        return response(
            null,
            true === $deletedProduct ? ResponseStatus::HTTP_NO_CONTENT : ResponseStatus::HTTP_EXPECTATION_FAILED
        );
    }
}
