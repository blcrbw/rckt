<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Entity\Product;

interface ProductRepositoryInterface
{

    /**
     * Gets Product object by UUID.
     *
     * @param string $uuid
     *   The UUID string.
     *
     * @return Product|null
     *   The Product object if found.
     */
    public function getByUuid(string $uuid): ?Product;

    /**
     * Gets the list of Products by category.
     *
     * @param string $category
     *   The category UUID string.
     * @return Product[]
     *   The list of Products.
     */
    public function getByCategory(string $category): array;

}
