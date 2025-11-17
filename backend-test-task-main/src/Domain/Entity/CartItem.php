<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Entity;

final readonly class CartItem
{
    public function __construct(
        public string $uuid,
        public string $productUuid,
        public int $quantity,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
