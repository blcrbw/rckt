<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Persistence\Mysql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Factory\ProductFactoryInterface;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Raketa\BackendTestTask\Domain\Entity\Product;

class MysqlProductRepository implements ProductRepositoryInterface
{

    public function __construct(
        private Connection $connection,
        private ProductFactoryInterface $productFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getByUuid(string $uuid): ?Product
    {
        try {
            $row = $this->connection->fetchOne(
                "SELECT * FROM products WHERE uuid = ?",
                [$uuid],
            );
        } catch (DBALException $e) {
            $this->logger->error('Cannot fetch the product', [
                'exception' => $e,
                'uuid' => $uuid,
                'repository' => static::class,
            ]);
            throw new Exception('Cannot fetch the product', 0, $e);
        }

        try {
            return !empty($row) ? $this->productFactory->createFromAssoc($row) : null;
        } catch (InvalidArgumentException $e) {
            $this->logger->warning('Cannot create product from MySQL row', ['exception' => $e, 'row' => $row]);

            return null;
        }
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function getByCategory(string $category): array
    {
        $result = [];
        try {
            $iterator = $this->connection->iterateAssociative(
                "SELECT * FROM products WHERE is_active = 1 AND category = ?",
                [$category],
            );
        } catch (DBALException $e) {
            $this->logger->error('Cannot fetch the products by category', [
                'exception' => $e,
                'category' => $category,
                'repository' => static::class,
            ]);
            throw new Exception('Cannot fetch the products', $e->getCode(), $e);
        }
        foreach ($iterator as $product) {
            try {
                $result[] = $this->productFactory->createFromAssoc($product);
            } catch (InvalidArgumentException $e) {
                $this->logger->warning('Cannot create product from array', [
                    'exception' => $e,
                    'row' => $product,
                    'repository' => static::class,
                ]);
                continue;
            }
        }

        return $result;
    }

}
