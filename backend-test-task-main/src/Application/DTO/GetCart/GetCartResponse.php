<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\DTO\GetCart;

use Raketa\BackendTestTask\Domain\Entity\Cart;

class GetCartResponse
{

    public function __construct(
        private Cart $cart,
    ) {
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

}
