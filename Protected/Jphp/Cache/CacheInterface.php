<?php

namespace Jphp\Cache;

/**
 * Interface CacheInterface
 * @package Jphp\Cache
 */
interface CacheInterface {
    /**
     * 添加缓存
     * @param $key
     * @param $value
     * @param int $expire
     * @return mixed
     * @internal param int $ttf
     */
    public function set($key,$value,$expire=0);

    /**
     * 获取缓存
     * @param $key
     * @param null $default 默认值
     * @return mixed
     */
    public function get($key,$default=null);

    /**
     * 删除缓存
     * @param $key
     * @return mixed
     */
    public function delete($key);
}