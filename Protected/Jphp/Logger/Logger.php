<?php

namespace Jphp\Logger;

/**
 * Class Logger
 * @package Jphp\Logger
 */
class Logger implements LoggerInterface {
    
    const DEBUG = 1;
    const INFO = 2;
    const NOTICE = 3;
    const WARNING = 4;
    const ERROR = 5;
    
    /**
     * 日志记录器名称
     * @var
     */
    private $name;
    private $handlers = array();
    /**
     * @var array
     */
    protected static $levelName = array(
        self::DEBUG   => 'DEBUG',
        self::INFO    => 'INFO',
        self::NOTICE  => 'NOTICE',
        self::WARNING => 'WARNING',
        self::ERROR   => 'ERROR'
    );
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    /**
     * 添加处理器
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler)
    {
        array_push($this->handlers, $handler);
    }
    
    /**
     * 获取日志级别名称
     * @param $level
     * @return mixed
     */
    private function getLevelName($level)
    {
        return self::$levelName[$level];
    }
    
    /**
     * DEBUG 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function debug($message, array $content)
    {
        return $this->addRecord(self::DEBUG, $message, $content);
    }
    
    /**
     * INFO 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function info($message, array  $content)
    {
        return $this->addRecord(self::INFO, $message, $content);
    }
    
    /**
     * NOTICE 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function notice($message, array $content)
    {
        return $this->addRecord(self::NOTICE, $message, $content);
    }
    
    /**
     * WARNING 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function warning($message, array $content)
    {
        return $this->addRecord(self::WARNING, $message, $content);
    }
    
    /**
     * ERROR 日志
     * @param $message
     * @param array $content
     * @return mixed
     */
    public function error($message, array $content)
    {
        return $this->addRecord(self::ERROR, $message, $content);
    }
    
    /**
     * 添加日志
     * @param $level
     * @param $message
     * @param array $content
     * @return bool
     */
    private function addRecord($level, $message, array $content)
    {
        $record = array(
            "time"       => date("Y-m-d H:i:s"),
            "name"       => $this->name,
            "level_name" => $this->getLevelName($level),
            "level"      => $level,
            "message"    => $message,
            "content"    => $content
        );
        if ($this->handlers) {
            /** @var HandlerInterface $handler */
            foreach ($this->handlers as $handler) {
                $handler->handle($record);
            }
        }
        return true;
    }
}