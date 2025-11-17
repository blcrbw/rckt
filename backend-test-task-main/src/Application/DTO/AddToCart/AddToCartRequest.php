<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\DTO\AddToCart;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

readonly class AddToCartRequest
{
    public function __construct(
        private string $productUuid,
        private string $quantity,
        private string $customerId,
    ) {
        if (empty($productUuid)) {
            throw new InvalidArgumentException('Product uuid cannot be empty.');
        } elseif (!Uuid::isValid($productUuid)) {
            throw new InvalidArgumentException('Invalid product uuid.');
        }

        if (empty($quantity) || !is_numeric($quantity) || (float)$quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be empty.');
        }
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

}
