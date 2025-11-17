<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Factory;

use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Domain\Entity\CartItem;
use Ramsey\Uuid\Uuid;

class CartFactory implements CartFactoryInterface
{
    /**
     * Creates the Cart object.
     *
     * @param string $uuid
     *   Optional UUID for the cart.
     * @param CartItem[] $cartItems
     *   Optional CartItem objects array.
     *
     * @return Cart
     *   Product object.
     */
    public function create(string $uuid = '', array $cartItems = []): Cart
    {
        if ($uuid === '') {
            $uuid = Uuid::uuid4()->toString();
        }

        return new Cart($uuid, $cartItems);
    }
}
