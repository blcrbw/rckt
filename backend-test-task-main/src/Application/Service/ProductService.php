<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Service;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Raketa\BackendTestTask\Domain\Service\ProductServiceInterface;
use Raketa\BackendTestTask\Domain\Entity\Product;

class ProductService implements ProductServiceInterface
{

    public function __construct(
        public ProductRepositoryInterface $productRepository,
        public LoggerInterface $logger,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getByUuid(string $uuid): ?Product
    {
        return $this->productRepository->getByUuid($uuid);
    }

    /**
     * @inheritdoc
     */
    public function getByCategory(string $category): array
    {
        return $this->productRepository->getByCategory($category);
    }
}
