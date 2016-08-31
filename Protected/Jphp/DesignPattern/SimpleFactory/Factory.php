<?php

namespace Jphp\DesignPattern\SimpleFactory;

/**
 * 工厂类
 * Class Factory
 * @package Jphp\DesignPattern\SimpleFactory
 */
class Factory {
    
    /**
     * 获取操作符对象
     * @param $type
     * @return Add|Div|Mul|Sub
     */
    public static function getOperation($type)
    {
        switch ($type) {
            case "+":
                return new Add();
                break;
            case "-":
                return new Sub();
                break;
            case "*":
                return new Mul();
                break;
            case "/":
                return new Div();
                break;
        }
    }
}