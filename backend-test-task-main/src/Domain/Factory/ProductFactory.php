<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Factory;

use InvalidArgumentException;
use Raketa\BackendTestTask\Domain\Entity\Product;
use Ramsey\Uuid\Uuid;

class ProductFactory implements ProductFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createFromAssoc(array $assoc): Product
    {
        $this->validateData($assoc);

        return new Product(
            (int)$assoc['id'],
            $assoc['uuid'],
            $assoc['is_active'] ?: false,
            $assoc['category'],
            $assoc['name'],
            (float)$assoc['price'],
            $assoc['description'] ?: '',
            $assoc['thumbnail'] ?: '',
        );
    }

    public function validateData(array $assoc): void
    {
        $errors = [];
        if (empty($assoc['id']) || !is_numeric($assoc['id'])) {
            $errors[] = 'Invalid id';
        }
        if (empty($assoc['uuid']) || !is_string($assoc['uuid']) || !Uuid::isValid($assoc['uuid'])) {
            $errors[] = 'Invalid uuid';
        }
        if (empty($assoc['category']) || !is_string($assoc['category']) || !Uuid::isValid($assoc['category'])) {
            $errors[] = 'Invalid category';
        }
        if (!isset($assoc['name']) || !is_string($assoc['name'])) {
            $errors[] = 'Invalid name';
        }
        if (!isset($assoc['price']) || !is_numeric($assoc['price'])) {
            $errors[] = 'Invalid price';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException('Cannot create product: '.implode(', ', $errors));
        }
    }
}
