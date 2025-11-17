<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\Controller;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\DTO\GetCart\GetCartRequest;
use Raketa\BackendTestTask\Application\UseCases\GetCart\GetCartHandler;
use Raketa\BackendTestTask\Domain\Service\CartServiceInterface;
use Raketa\BackendTestTask\Presentation\View\CartView;

readonly class GetCartController
{
    public function __construct(
        private CartView $cartView,
        private GetCartHandler $handler,
        private LoggerInterface $logger,
    ) {
    }

    public function get(): ResponseInterface
    {
        try {
            $customerId = session_id();
            $this->logger->debug('Processing Get Cart request', [
                'controller' => static::class,
            ]);
            $cart = $this->handler->handle(new GetCartRequest($customerId))->getCart();
        } catch (Exception $e) {
            $this->logger->error('Unexpected error', [
                'controller' => static::class,
                'exception' => $e,
            ]);

            return $this->cartView->errorResponse('Internal Server Error', 500);
        }

        try {
            $this->logger->info('Get Cart request completed successfully', [
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
