<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Factory;

use Raketa\BackendTestTask\Domain\Entity\Product;

interface ProductFactoryInterface
{
    /**
     * Create the Product object from associative array.
     *
     * @param array $assoc
     *   Product data array.
     * @return Product
     *   Product object.
     */
    public function createFromAssoc(array $assoc): Product;
}
