<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\UseCases\AddToCart;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\DTO\AddToCart\AddToCartRequest;
use Raketa\BackendTestTask\Application\DTO\AddToCart\AddToCartResponse;
use Raketa\BackendTestTask\Application\Service\CartService;

class AddToCartHandler
{

    public function __construct(
        private CartService $cartService,
        private LoggerInterface $logger,
    ) {
    }

    public function handle(AddToCartRequest $request): AddToCartResponse
    {
        $this->logger->info('Adding product to cart', [
            'product_uuid' => $request->getProductUuid(),
            'quantity' => $request->getQuantity(),
            'customer_id' => $request->getCustomerId(),
        ]);

        try {
            $cart = $this->cartService->addToCart(
                $request->getProductUuid(),
                $request->getQuantity(),
                $request->getCustomerId()
            );
            $this->logger->info('Added product to cart', [
                'product_uuid' => $request->getProductUuid(),
                'quantity' => $request->getQuantity(),
                'customer_id' => $request->getCustomerId(),
                'cart_uuid' => $cart->getUuid(),
            ]);
        } catch (\Exception $e) {
            $this->logger->warning('Failed to add product to cart', [
                'product_uuid' => $request->getProductUuid(),
                'exception' => $e,
            ]);
            throw $e;
        }

        return new AddToCartResponse($cart);
    }
}
