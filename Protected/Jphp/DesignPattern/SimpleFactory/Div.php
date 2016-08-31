<?php

namespace Jphp\DesignPattern\SimpleFactory;

/**
 * Class Div
 * @package Jphp\DesignPattern\SimpleFactory
 */
class Div extends Operation {
    
    /**
     * 获取值
     * @param $num1
     * @param $num2
     * @return mixed
     */
    public function getValue($num1, $num2)
    {
        // TODO: Implement getValue() method.
        if ($num2 == 0) {
            throw new \InvalidArgumentException("除数不能为0");
        }
        return $num1 / $num2;
    }
}