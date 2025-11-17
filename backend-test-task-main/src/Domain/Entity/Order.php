<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Entity;

final class Order
{
    public function __construct(
        readonly private string $uuid,
        readonly private Customer $customer,
        private string $paymentMethod,
        private array $items,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): Order
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }
}
