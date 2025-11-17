<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\DTO\GetProductByCategory;

use Raketa\BackendTestTask\Domain\Entity\Product;

readonly class GetProductByCategoryResponse
{

    /**
     * @param Product[] $products
     */
    public function __construct(
        private array $products,
    ) {
    }

    public function getProducts(): array
    {
        return $this->products;
    }

}
