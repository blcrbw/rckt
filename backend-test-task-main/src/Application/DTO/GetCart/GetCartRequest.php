<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\DTO\GetCart;

readonly class GetCartRequest
{

    public function __construct(
        public string $customerId,
    ) {
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

}
