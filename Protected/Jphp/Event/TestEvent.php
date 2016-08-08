<?php

namespace Jphp\Event;

/**
 * Class TestEvent
 * @package Jphp\Event
 */
class TestEvent implements EventInterface{
	/**
	 * @param EventInterface|null $event
	 * @return mixed
	 */
	public function handle(EventInterface $event = null) {
		echo "====jidan==handle===";
		// TODO: Implement handle() method.
	}
}