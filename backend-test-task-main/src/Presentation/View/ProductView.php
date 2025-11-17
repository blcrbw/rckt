<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\View;

use Raketa\BackendTestTask\Domain\Entity\Product;

readonly class ProductView
{

    public function toArray(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'uuid' => $product->getUuid(),
            'name' => $product->getName(),
            'category' => $product->getCategory(),
            'description' => $product->getDescription(),
            'thumbnail' => $product->getThumbnail(),
            'price' => $product->getPrice(),
        ];
    }
}
