<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Entity;

final class Cart
{
    public function __construct(
        readonly private string $uuid,
        private array $items = [],
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }
}
