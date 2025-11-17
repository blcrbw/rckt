<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\View;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Infrastructure\Http\JsonResponse;
use Raketa\BackendTestTask\Domain\Entity\Product;

readonly class ProductsView
{

    public function __construct(
        private ProductView $productView,
    ) {
    }

    /**
     * @param Product[] $products
     *
     * @return ResponseInterface
     */
    public function jsonResponse(array $products): ResponseInterface
    {
        $data = array_map(
            fn($product) => $this->productView->toArray($product),
            $products
        );

        return (new JsonResponse())
            ->withData($data)
            ->withStatus(200);
    }

    public function errorResponse(string $message, int $code): ResponseInterface
    {
        return (new JsonResponse())
            ->withData(['message' => $message])
            ->withStatus($code);
    }
}
