<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\UseCases\GetCart;

use Raketa\BackendTestTask\Application\DTO\GetCart\GetCartRequest;
use Raketa\BackendTestTask\Application\DTO\GetCart\GetCartResponse;
use Raketa\BackendTestTask\Application\Service\CartService;

class GetCartHandler
{
    public function __construct(
        private CartService $cartService,
    ) {
    }

    public function handle(GetCartRequest $getCartRequest): GetCartResponse
    {
        try {
            $cart = $this->cartService->getCart($getCartRequest->getCustomerId());
        } catch (\Exception $e) {
            throw $e;
        }

        return new GetCartResponse($cart);
    }
}
