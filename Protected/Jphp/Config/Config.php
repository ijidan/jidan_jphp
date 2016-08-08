<?php

namespace Jphp\Config;

/**
 * Class Config
 * @package Jphp\Config
 */
class Config implements ConfigInterface {

    /**
     * @var
     */
    private $config_path;

    /**
     * 配置路径
     * @param $config_path
     */
    public function __construct($config_path){
        $this->config_path=$config_path;
    }
    /**
     * 获取配置
     * @param String $key 关键字
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null){
        $key_list=explode(".",$key);
        $config_filename=ucfirst(array_shift($key_list));
        $config_file=$this->config_path.DIRECTORY_SEPARATOR.$config_filename.".php";
        if(!is_file($config_file)){
            throw new \LogicException("config file not found");
        }
        $config_content= include $config_file;
        if(!$key_list){
            return $config_content;
        }
        $value=$config_content;
        foreach($key_list as $k){
            if(!isset($value[$k])){
                throw new \OutOfRangeException("{$key} configure not found");
            }
            dump($value);
            $value=$value[$k];
        }
        return $value;
    }
}