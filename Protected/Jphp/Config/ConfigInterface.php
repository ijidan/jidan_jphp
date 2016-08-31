<?php

namespace Jphp\Config;

/**
 * Interface ConfigInterface
 * @package Jphp\Config
 */
interface ConfigInterface {
    /**
     * 获取配置
     * @param String $key 关键字
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);
}