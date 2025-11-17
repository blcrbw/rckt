<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\Controller;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\DTO\AddToCart\AddToCartRequest;
use Raketa\BackendTestTask\Application\UseCases\AddToCart\AddToCartHandler;
use Raketa\BackendTestTask\Presentation\View\CartView;

readonly class AddToCartController
{
    public function __construct(
        private CartView $cartView,
        private AddToCartHandler $handler,
        private LoggerInterface $logger,
    ) {
    }

    public function post(RequestInterface $request): ResponseInterface
    {
        // Dummy customer info.
        $customerId = session_id();
        $this->logger->debug('Processing Add To Cart request', ['controller' => static::class]);
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->warning('Invalid JSON format', ['controller' => static::class]);

            return $this->cartView->errorResponse('Invalid JSON format', 400);
        }

        $productUuid = $rawRequest['productUuid'] ?? '';
        $quantity = $rawRequest['quantity'] ?? '';

        try {
            $addToCartRequest = new AddToCartRequest($productUuid, $quantity, $customerId);
            $cart = $this->handler->handle($addToCartRequest)->getCart();
        } catch (InvalidArgumentException $e) {
            $this->logger->warning('Client input validation failed', [
                'productUuid' => $productUuid,
                'quantity' => $quantity,
                'controller' => static::class,
                'exception' => $e,
            ]);

            return $this->cartView->errorResponse('Invalid user input', 400);
        } catch (Exception $e) {
            $this->logger->error('Unexpected error', [
                'productUuid' => $productUuid,
                'quantity' => $quantity,
                'controller' => static::class,
                'exception' => $e,
            ]);

            return $this->cartView->errorResponse('Internal server error', 500);
        }

        try {
            $this->logger->info('Add To Cart request completed successfully', [
                'controller' => static::class,
            ]);

            return $this->cartView->jsonResponse($cart);
        } catch (Exception $e) {
            $this->logger->error('Failed to make json response', [
                'cart_uuid' => $cart->getUuid(),
                'controller' => static::class,
                'exception' => $e,
            ]);

            return $this->cartView->errorResponse('Internal server error', 500);
        }
    }
}
