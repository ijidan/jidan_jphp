<?php

namespace Jphp\DesignPattern\Observer;

use SplObserver;
use SplSubject;

/**
 * Class MailObserver
 * @package Jphp\DesignPattern\Observer
 */
class MailObserver implements SplObserver {
    /**
     * Receive update from subject
     * @link http://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {
        echo "message sent to " . __CLASS__ . "---{$subject->getMessage()}</br>";
        // TODO: Implement update() method.
    }
}