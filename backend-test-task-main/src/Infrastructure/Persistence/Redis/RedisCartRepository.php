<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Persistence\Redis;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Domain\Repository\CartRepositoryInterface;
use Raketa\BackendTestTask\Infrastructure\Persistence\Exception\ConnectorException;

class RedisCartRepository implements CartRepositoryInterface
{

    private const TTL = 24 * 60 * 60;
    private const KEY_PREFIX = 'cart:';

    public function __construct(
        private RedisConnector $redis,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $customerId): ?Cart
    {
        try {
            $this->logger->debug("Trying to fetch the cart from Redis", ['customerId' => $customerId]);
            $cart = $this->redis->get(static::KEY_PREFIX.$customerId, static::TTL);
            $this->logger->debug("The cart fetched successfully", ['customerId' => $customerId]);

            return !empty($cart) ? unserialize($cart) : null;
        } catch (ConnectorException $e) {
            $this->logger->error('Failed to fetch the cart from Redis', [
                'customerId' => $customerId,
                'exception' => $e,
            ]);
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function save(string $customerId, Cart $value): void
    {
        try {
            $this->redis->set(static::KEY_PREFIX.$customerId, serialize($value), static::TTL);
        } catch (ConnectorException $e) {
            throw new Exception('Redis connector error', $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function has($customerId): bool
    {
        try {
            return $this->redis->has($customerId);
        } catch (ConnectorException $e) {
            throw new Exception('Redis connector error', $e->getCode(), $e);
        }
    }
}
