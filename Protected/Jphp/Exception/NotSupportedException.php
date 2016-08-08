<?php
namespace Jphp\Exception;

/**
 * Class NotSupportedException
 * @package Jphp\Exception
 */
class NotSupportedException extends \ErrorException{
    /**
     * 获取错误信息
     * @return string
     */
    public function getName(){
        return 'Not Supported';
    }

}