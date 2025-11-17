<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\UseCases\GetProductByCategory;

use Raketa\BackendTestTask\Application\DTO\GetProductByCategory\GetProductByCategoryRequest;
use Raketa\BackendTestTask\Application\DTO\GetProductByCategory\GetProductByCategoryResponse;
use Raketa\BackendTestTask\Domain\Service\ProductServiceInterface;

class GetProductByCategoryHandler
{
    public function __construct(
        private ProductServiceInterface $productService,
    ) {
    }

    public function handle(GetProductByCategoryRequest $request): GetProductByCategoryResponse
    {
        try {
            $products = $this->productService->getByCategory($request->getCategoryId());
        } catch (\Exception $e) {
            throw $e;
        }

        return new GetProductByCategoryResponse($products);
    }
}
