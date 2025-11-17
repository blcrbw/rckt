<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Service;

use Raketa\BackendTestTask\Domain\Entity\Cart;

interface CartServiceInterface
{

    /**
     * Saves the Cart.
     *
     * @param Cart $cart
     *   The Cart object.
     * @param string $customerId
     *   The Customer id string.
     */
    public function saveCart(Cart $cart, string $customerId): void;

    /**
     * Loads the cart.
     *
     * @param string $customerId
     *   The Customer ID string.
     *
     * @return ?Cart
     */
    public function getCart(string $customerId): ?Cart;

    /**
     * Adds product to the cart.
     *
     * @param string $productUuid
     *   The product uuid.
     * @param string|float|int $quantity
     *   The product quantity to add to cart.
     * @param string $customerId
     *   The customer id string.
     *
     * @return Cart
     *   The full cart object.
     */
    public function addToCart(string $productUuid, string|float|int $quantity, string $customerId): Cart;

}
