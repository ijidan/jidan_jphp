<?php

namespace Jphp\Event;

/**
 * Interface EventDispatcherInterface
 */
interface EventDispatcherInterface {

	/**
	 * 添加监听者
	 * @param $event_name
	 * @param callable $listener
	 * @return mixed
	 * @internal param $name
	 */
	public function addListener($event_name,callable $listener);

	/**
	 * 移除某个监听者
	 * @param $event_name
	 * @param callable $listener
	 * @return mixed
	 */
	public function remove($event_name,callable $listener);

	/**
	 * 获取所有监听者
	 * @param $event_name
	 * @return mixed
	 */
	public function getListeners($event_name);

	/**
	 * 是否有监听者
	 * @param $event_name
	 * @return mixed
	 */
	public function hasListener($event_name);

	/**
	 * 移除所有监听者
	 * @param $event_name
	 * @return mixed
	 */
	public function removeAllListener($event_name);

	/**
	 * 调度事件
	 * @param $event_name
	 * @param EventInterface $event
	 * @return mixed
	 */
	public function dispatch($event_name,EventInterface $event=null);

	/**
	 *事件监听者数目
	 * @param $event_name
	 * @return mixed
	 */
	public function count($event_name);
}