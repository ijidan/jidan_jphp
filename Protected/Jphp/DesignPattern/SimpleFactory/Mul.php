<?php

namespace Jphp\DesignPattern\SimpleFactory;

/**
 * Class Mul
 * @package Jphp\DesignPattern\SimpleFactory
 */
class Mul extends Operation{

	/**
	 * 获取值
	 * @param $num1
	 * @param $num2
	 * @return mixed
	 */
	public function getValue($num1, $num2) {
		return $num1 * $num2;
	}
}