<?php

namespace Jphp\Middleware;

use Jphp\Http\Request;
use Jphp\Http\Response;

/**
 * Class CSRF
 * @package Jphp\Middleware
 */
class CSRF  implements MiddlewareInterface{
	/**
	 * @param Request $request
	 * @param Response $response
	 * @param callable $next
	 * @return mixed
	 */
	public function __invoke(Request $request, Response $response, callable $next) {
		return array("a","b","c");
	}
}