<?php

namespace Jphp\Logger;

/**
 * 日志接口
 * Interface LoggerInterface
 * @package Jphp\Logger
 */
interface LoggerInterface {
    /**
     * DEBUG 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function debug($message, array $content);
    
    /**
     * INFO 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function info($message, array  $content);
    
    /**
     * NOTICE 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function notice($message, array $content);
    
    /**
     * WARNING 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function warning($message, array $content);
    
    /**
     * ERROR 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function error($message, array $content);
}