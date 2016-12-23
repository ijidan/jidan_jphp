<?php


namespace Jphp\Http;

use Jphp\Application\WebApplication;

class Kernel implements KernelInterface {
    
    private $app;
    
    /**
     * 构造函数
     * @param WebApplication $app
     * @internal param Container $container
     */
    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }
    
    /**
     * 处理请求返回响应
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        /** @var Router $router */
        $router = $this->app->router;
        /** @var Response $response */
        $response = $router->dispatch($request);
        $response->send();
        // TODO: Implement handle() method.
    }
}