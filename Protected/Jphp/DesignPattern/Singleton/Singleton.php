<?php

namespace Jphp\DesignPattern\Singleton;

/**
 * Class Singleton
 * @package Jphp\DesignPattern\Singleton
 */
class Singleton {
    
    /**
     * 实例
     * @var null
     */
    private static $_instance = null;
    
    /**
     * 构造函数
     * Singleton constructor.
     */
    private function __construct()
    {
    }
    
    /**
     * 克隆对象
     */
    private function __clone()
    {
        trigger_error("cant clone this object", E_USER_ERROR);
    }
    
    /**
     * 获取实例
     * @return null|static
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }
    
    /**
     * 测试
     */
    public function test()
    {
        echo "this is test";
    }
}