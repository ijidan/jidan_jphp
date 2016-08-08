<?php

namespace Jphp\Service;

/**
 * Interface ContainerInterface
 * @package Jphp\Service
 */
interface ContainerInterface {
    /**
     * @param $name
     * @param $content
     * @return mixed
     */
    public function bindParam($name,$content);

    /**
     * 绑定服务
     * @param string $name 服务名称
     * @param   $definitions
     * @param array $params
     * @return mixed
     * @internal param $content
     */
    public function bindObj($name,$definitions=null,array $params=array());

    /**
     * 绑定服务
     * @param String $name 服务名称
     * @param        $definitions
     * @param array $params
     * @return mixed
     * @internal param $content
     */
    public function bindInstance($name,$definitions=null,array $params=array());
    /**
     * 判断服务是否已经绑定
     * @param String $name 服务名字
     * @return bool
     */
    public function isBound($name);

}