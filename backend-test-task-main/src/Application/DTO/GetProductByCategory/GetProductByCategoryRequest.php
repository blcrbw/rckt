<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\DTO\GetProductByCategory;

use Ramsey\Uuid\Uuid;

readonly class GetProductByCategoryRequest
{

    public function __construct(
        private string $categoryId,
    ) {
        if (empty($categoryId)) {
            throw new \InvalidArgumentException('Category cannot be empty');
        } elseif (!Uuid::isValid($categoryId)) {
            throw new \InvalidArgumentException('Category is not valid UUID.');
        }
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

}
