<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Persistence\Redis;

use Raketa\BackendTestTask\Infrastructure\Persistence\Exception\ConnectorException;
use Redis;
use RedisException;

class RedisConnector
{
    private Redis $redis;

    /**
     * @throws ConnectorException
     */
    public function __construct(string $host, int $port = 6379, ?string $password = null, ?int $dbIndex = null)
    {
        $this->redis = new Redis();

        try {
            $isConnected = $this->redis->connect($host, $port);
            if (!$isConnected) {
                throw new ConnectorException('Failed to connect to Redis');
            }

            if ($password && !$this->redis->auth($password)) {
                throw new ConnectorException('Redis authentication failed');
            }

            if ($dbIndex && !$this->redis->select($dbIndex)) {
                throw new ConnectorException('Failed to select Redis database');
            }

            if (!$this->redis->ping('Pong')) {
                throw new ConnectorException('Redis connection test failed');
            }
        } catch (RedisException $e) {
            throw new ConnectorException(
                'Redis connection error: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key, ?int $expire = null): string
    {
        try {
            $options = [];
            if (isset($expire)) {
                $options['EX'] = $expire;
            }

            return $this->redis->getex($key, $options);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, string $value, ?int $expire = null): void
    {
        try {
            $options = [];
            if (isset($expire)) {
                $options['EX'] = $expire;
            }
            $this->redis->set($key, $value, $options);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @param $key
     * @return bool
     * @throws ConnectorException
     */
    public function has($key): bool
    {
        try {
            return $this->redis->exists($key);
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

}
