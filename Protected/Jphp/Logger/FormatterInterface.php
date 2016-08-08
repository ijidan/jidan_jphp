<?php

namespace Jphp\Logger;

/**
 * 格式化工具
 * Interface FormatterInterface
 * @package Jphp\Logger
 */
interface FormatterInterface {
	
	/**
	 * 格式化日志
	 * @param array $data
	 * @return mixed
	 */
	public function format(array $data);
}