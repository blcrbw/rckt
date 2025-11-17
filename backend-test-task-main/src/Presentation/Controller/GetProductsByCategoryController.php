<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Presentation\Controller;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\DTO\GetProductByCategory\GetProductByCategoryRequest;
use Raketa\BackendTestTask\Application\UseCases\GetProductByCategory\GetProductByCategoryHandler;
use Raketa\BackendTestTask\Presentation\View\ProductsView;

readonly class GetProductsByCategoryController
{
    public function __construct(
        private ProductsView $view,
        private GetProductByCategoryHandler $handler,
        private LoggerInterface $logger,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $this->logger->debug('Processing Get Product By Category request', [
            'controller' => static::class,
        ]);
        $rawRequest = $request->getQueryParams();
        $category = $rawRequest['category'] ?? '';

        try {
            $getProductsByCategoryRequest = new GetProductByCategoryRequest($category);
            $products = $this->handler
                ->handle($getProductsByCategoryRequest)
                ->getProducts();
        } catch (InvalidArgumentException $e) {
            $this->logger->info('Client input validation failed', [
                'category' => $category,
                'controller' => static::class,
                'exception' => $e,
            ]);

            return $this->view->errorResponse('Invalid product category', 400);
        } catch (Exception $e) {
            $this->logger->info('Unexpected error', [
                'category' => $category,
                'controller' => static::class,
                'exception' => $e,
            ]);

            return $this->view->errorResponse('Internal server error', 500);
        }

        return $this->view->jsonResponse($products);
    }
}
