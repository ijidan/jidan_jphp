<?php

namespace Jphp\Http;

interface KernelInterface {
    /**
     * 处理请求返回响应
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request);
}