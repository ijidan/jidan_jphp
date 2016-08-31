<?php

namespace Jphp\Logger;

/**
 * Class JSONFormatter
 * @package Jphp\Logger
 */
class JSONFormatter implements FormatterInterface {
    
    /**
     * 格式化日志
     * @param array $data
     * @return mixed
     */
    public function format(array $data)
    {
        return json_encode($data);
    }
}