<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Service;

use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Entity\Cart;
use Raketa\BackendTestTask\Domain\Entity\CartItem;
use Raketa\BackendTestTask\Domain\Repository\CartRepositoryInterface;
use Raketa\BackendTestTask\Domain\Service\CartServiceInterface;
use Raketa\BackendTestTask\Domain\Service\ProductServiceInterface;
use Raketa\BackendTestTask\Infrastructure\Persistence\Exception\ConnectorException;
use Ramsey\Uuid\Uuid;


class CartService implements CartServiceInterface
{

    public function __construct(
        public CartRepositoryInterface $cartRepository,
        public ProductServiceInterface $productService,
        public LoggerInterface $logger,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart, string $customerId): void
    {
        try {
            $this->cartRepository->save($customerId, $cart);
        } catch (Exception|ConnectorException $e) {
            $this->logger->error('Cannot save the cart', [
                'exception' => $e,
                'customer_id' => $customerId,
            ]);
        }
    }

    /**
     * Creates the Cart.
     *
     * @param string $customerId
     *   The Customer id string.
     *
     * @return Cart
     *   The Cart object.
     */
    public function createCart(string $customerId): Cart
    {
        $cart = new Cart(Uuid::uuid4()->toString(), []);
        $this->saveCart($cart, $customerId);

        return $cart;
    }

    /**
     * @inheritdoc
     */
    public function getCart(string $customerId): ?Cart
    {
        try {
            $cart = $this->cartRepository->get($customerId);
        } catch (Exception $e) {
            $this->logger->warning('Cannot get the cart', [
                'exception' => $e,
                'customer_id' => $customerId,
            ]);
            throw $e;
        }

        if (!isset($cart)) {
            $cart = $this->createCart($customerId);
        }

        return $cart;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function addToCart(string $productUuid, string|int|float $quantity, string $customerId): Cart
    {
        $cart = $this->getCart($customerId) ?? $this->createCart($customerId);

        try {
            $product = $this->productService->getByUuid($productUuid);
        } catch (Exception $e) {
            $this->logger->warning('Cannot add to cart. Product not found', ['product_uuid' => $productUuid]);
            throw new Exception('Cannot add to cart', 0, $e);
        }

        if (empty($product) || !$product->isActive()) {
            $this->logger->warning(
                'Cannot add to cart. Product not found or not active',
                ['product_uuid' => $productUuid]
            );
            throw new InvalidArgumentException('Cannot add to cart. Product not found or not active');
        }

        $cart->addItem(
            new CartItem(
                Uuid::uuid4()->toString(),
                $product->getUuid(),
                $quantity,
            )
        );
        $this->saveCart($cart, $customerId);

        return $cart;
    }
}
