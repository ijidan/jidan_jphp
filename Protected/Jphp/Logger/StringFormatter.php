<?php

namespace Jphp\Logger;

/**
 * Class StringFormatter
 * @package Jphp\Logger
 */
class StringFormatter implements FormatterInterface {
    
    /**
     * 格式化日志
     * @param array $data
     * @return mixed
     */
    public function format(array $data)
    {
        $log = sprintf("[%s] %s %s %s", $data["time"], $data["name"], $data["level_name"], json_encode($data["content"]));
        return $log;
    }
}