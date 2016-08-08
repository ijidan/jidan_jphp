<?php

namespace Jphp\DesignPattern\SimpleFactory;

/**
 * 操作类
 * Class Operation
 * @package Jphp\DesignPattern\SimpleFactory
 */
abstract class Operation {
	/**
	 * 获取值
	 * @param $num1
	 * @param $num2
	 * @return mixed
	 */
	abstract public function getValue($num1,$num2);
}