<?php

namespace Jphp\Event;

/**
 * Interface EventInterface
 * @package Jphp\Event
 */
interface EventInterface {
    /**
     * @param EventInterface|null $event
     * @return mixed
     */
    public function handle(EventInterface $event = null);
    
}