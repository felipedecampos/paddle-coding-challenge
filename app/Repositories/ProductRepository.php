<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository extends BaseRespository
{
    /**
     * @var string
     */
    protected string $modelClass = Product::class;

    /**
     * @param int $limit
     * @param bool $paginate
     *
     * @return Paginator
     */
    public function viewAllProducts(int $limit = 15, bool $paginate = true): Paginator
    {
        return $this->getAll($limit, $paginate);
    }

    /**
     * @param array $fields
     *
     * @return Product
     */
    public function createProduct(array $fields): Product
    {
        return $this->create($fields);
    }

    /**
     * @param int $productId
     *
     * @return Product
     */
    public function viewProduct(int $productId): Product
    {
        return $this->findById($productId);
    }

    /**
     * @param int $productId
     * @param array $fields
     *
     * @return Product
     */
    public function updateProduct(int $productId, array $fields): Product
    {
        return $this->update($productId, $fields);
    }

    /**
     * @param int $productId
     *
     * @return bool
     */
    public function deleteProduct(int $productId): bool
    {
        return $this->delete($productId);
    }
}
