<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Infrastructure\Persistence\Exception\ConnectorException;

interface CartRepositoryInterface
{

    /**
     * Gets the Cart object from repository.
     *
     * @param string $customerId
     *   The customer id key.
     *
     * @return Cart|null
     *   The Cart object or null if not found.
     */
    public function get(string $customerId): ?Cart;

    /**
     * Saves the Cart to the repository.
     *
     * @throws ConnectorException
     */
    public function save(string $customerId, Cart $value): void;

    /**
     * Checks if Cart for specific customer exists in the repository.
     *
     * @param string $customerId
     *   The session id key.
     *
     * @return bool
     *   True if the Cart exists.
     */
    public function has(string $customerId): bool;
}
