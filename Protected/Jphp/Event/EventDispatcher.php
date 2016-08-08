<?php

namespace Jphp\Event;

use OutOfRangeException;


/**
 * 事件调度器
 * Class EventDispatcher
 */
class EventDispatcher implements EventDispatcherInterface{

	/**
	 * 监听者
	 * @var array
	 */
	private $listeners=array();
	

	/**
	 * 添加监听者
	 * @param $event_name
	 * @param callable $listener
	 * @return mixed
	 * @internal param $name
	 */
	public function addListener($event_name, callable $listener) {
		if(!isset($this->listeners[$event_name])){
			$this->listeners[$event_name]=array();
		}
		array_push($this->listeners[$event_name],$listener);
	}

	/**
	 * @param $event_name
	 * @param callable $listener
	 * @return bool
	 */
	public function remove($event_name, callable $listener) {
		if(!isset($event_name)){
			throw  new OutOfRangeException("event {$event_name} not found");
		}
		if($key=array_search($listener,$this->listeners[$event_name])!==false){
			unset($this->listeners[$event_name][$key]);
		}
		return true;
	}

	/**
	 * 获取所有监听者
	 * @param $event_name
	 * @return mixed
	 */
	public function getListeners($event_name) {
		return isset($this->listeners[$event_name]) ? $this->listeners[$event_name]:array();
	}

	/**
	 * 是否有监听者
	 * @param $event_name
	 * @return mixed
	 */
	public function hasListener($event_name) {
		$count=$this->count($event_name);
		return (bool)$count;
	}

	/**
	 * 移除所有监听者
	 * @param $event_name
	 * @return mixed
	 */
	public function removeAllListener($event_name) {
		if(isset($this->listeners[$event_name])){
			$this->listeners[$event_name]=array();
		}
	}

	/**
	 * 事件调度
	 * @param $event_name
	 * @param EventInterface $event
	 * @return mixed
	 */
	public function dispatch($event_name, EventInterface $event = null) {
		if($this->hasListener($event_name)){
			foreach ($this->listeners[$event_name] as $listener){
				call_user_func($listener,$event);
			}
		}
	}
	/**
	 *事件监听者数目
	 * @param $event_name
	 * @return mixed
	 */
	public function count($event_name) {
		if(isset($this->listeners[$event_name])){
			return count($this->listeners[$event_name]);
		}
		return 0;
	}
}