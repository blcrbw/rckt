<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\DTO\AddToCart;

use Raketa\BackendTestTask\Domain\Entity\Cart;

readonly class AddToCartResponse
{
    public function __construct(
        private Cart $cart,
    ) {
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

}
