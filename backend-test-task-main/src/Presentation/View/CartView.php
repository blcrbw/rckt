<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\View;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Domain\Service\ProductServiceInterface;
use Raketa\BackendTestTask\Infrastructure\Http\JsonResponse;

readonly class CartView
{
    public function __construct(
        private ProductServiceInterface $productService,
        private ProductView $productView,
    ) {
    }

    public function jsonResponse(Cart $cart): ResponseInterface
    {
        return (new JsonResponse())
            ->withData($this->toArray($cart))
            ->withStatus(200);
    }

    public function errorResponse(string $message, int $code): ResponseInterface
    {
        return (new JsonResponse())
            ->withData(['message' => $message])
            ->withStatus($code);
    }


    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
        ];

        $total = 0;
        $data['items'] = [];
        foreach ($cart->getItems() as $item) {
            try {
                $product = $this->productService->getByUuid($item->getProductUuid());
            } catch (\Exception $e) {
                continue;
            }
            if (empty($product)) {
                // Skip broken products in case of cart. Don't do that in case of Order.
                continue;
            }
            $total += $product->getPrice() * $item->getQuantity();

            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $product->getPrice(),
                'total' => $product->getPrice() * $item->getQuantity(),
                'quantity' => $item->getQuantity(),
                'product' => $this->productView->toArray($product),
            ];
        }

        $data['total'] = $total;

        return $data;
    }
}
