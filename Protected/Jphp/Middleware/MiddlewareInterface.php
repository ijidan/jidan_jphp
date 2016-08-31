<?php

namespace Jphp\Middleware;

use Jphp\Http\Request;
use Jphp\Http\Response;

/**
 * Interface MiddlewareInterface
 * @package Jphp\Middleware
 */
interface MiddlewareInterface {
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $next);
}