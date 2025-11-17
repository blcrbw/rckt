<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\View;

use Raketa\BackendTestTask\Domain\Entity\Order;
use Raketa\BackendTestTask\Domain\Entity\OrderItem;

readonly class OrderView
{
    public function __construct(
        private ProductView $productView,
    ) {
    }

    public function toArray(Order $order): array
    {
        $data = [
            'uuid' => $order->getUuid(),
            'customer' => [
                'id' => $order->getCustomer()->getId(),
                'name' => implode(' ', [
                    $order->getCustomer()->getLastName(),
                    $order->getCustomer()->getFirstName(),
                    $order->getCustomer()->getMiddleName(),
                ]),
                'email' => $order->getCustomer()->getEmail(),
            ],
            'payment_method' => $order->getPaymentMethod(),
        ];

        $total = 0;
        $data['items'] = [];
        foreach ($order->getItems() as $item) {
            /** @var OrderItem $item */
            $product = $item->getProduct();
            $total += $item->getPrice() * $item->getQuantity();

            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'total' => $item->getPrice() * $item->getQuantity(),
                'quantity' => $item->getQuantity(),
                'product' => $this->productView->toArray($product),
            ];
        }

        $data['total'] = $total;

        return $data;
    }
}
