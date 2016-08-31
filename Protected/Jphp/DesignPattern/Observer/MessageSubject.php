<?php

namespace Jphp\DesignPattern\Observer;

use SplObjectStorage;
use SplObserver;
use SplSubject;

/**
 * Class MessageSubject
 * @package Jphp\DesignPattern\Observer
 */
class MessageSubject implements SplSubject {
    
    private $message;
    private $servers;
    
    /**
     * 构造函数
     * MessageSubject constructor.
     */
    public function __construct()
    {
        $this->servers = new SplObjectStorage();
    }
    
    /**
     * Attach an SplObserver
     * @link http://php.net/manual/en/splsubject.attach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to attach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function attach(SplObserver $observer)
    {
        // TODO: Implement attach() method.
        $this->servers->attach($observer);
    }
    
    /**
     * Detach an observer
     * @link http://php.net/manual/en/splsubject.detach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to detach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function detach(SplObserver $observer)
    {
        // TODO: Implement detach() method.
        $this->servers->detach($observer);
    }
    
    /**
     * Notify an observer
     * @link http://php.net/manual/en/splsubject.notify.php
     * @return void
     * @since 5.1.0
     */
    public function notify()
    {
        foreach ($this->servers as $server) {
            $server->update($this);
        }
        // TODO: Implement notify() method.
    }
    
    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}