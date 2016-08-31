<?php

namespace Jphp\Middleware;

use Jphp\Http\Request;
use Jphp\Http\Response;

/**
 * Class Auth
 * @package Jphp\Middleware
 */
class Auth implements MiddlewareInterface {
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // TODO: Implement __invoke() method.
    }
}