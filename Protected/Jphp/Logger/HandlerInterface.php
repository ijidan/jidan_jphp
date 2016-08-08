<?php

namespace Jphp\Logger;

/**
 * 处理器接口
 * Interface HandlerInterface
 * @package Jphp\Logger
 */
interface HandlerInterface {
	/**
	 * 处理日志
	 * @param array $record
	 * @return mixed
	 */
	public function handle(array $record);
	/**
	 * 格式化日志
	 * @param array $record
	 * @return mixed
	 */
	public function format(array $record);
}