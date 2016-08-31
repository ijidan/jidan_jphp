<?php

namespace Jphp\Logger;

/**
 * Class FileHandler
 * @package Jphp\Logger
 */
class FileHandler implements HandlerInterface {
    
    private $file;
    
    /**
     * 构造函数
     * FileHandler constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }
    
    /**
     * 处理日志
     * @param array $record
     * @return mixed
     */
    public function handle(array $record)
    {
        if (!file_exists($this->file)) {
            touch($this->file);
        }
        $handle = fopen($this->file, "a");
        fwrite($handle, $this->format($record) . "\r\n");
        fclose($handle);
        return true;
    }
    
    /**
     * 格式化日志
     * @param array $record
     * @return mixed
     */
    public function format(array $record)
    {
        return (new StringFormatter())->format($record);
    }
}