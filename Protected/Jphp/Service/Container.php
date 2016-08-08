<?php

namespace Jphp\Service;

/**
 * Class Container
 * @package Jphp\Service
 */
class Container implements ContainerInterface,\ArrayAccess{

    private $resolved=array();
    private $resolved_instance=array();
    private $resolved_param=array();

    private $definitions=array();
    private $instance_definitions=array();
    private $param_definitions=array();

    /**
     * 构造函数的参数
     * @var array
     */
    private $params=array();
    private $instance_params=array();


    /**
     *获取类
     * @param $value
     * @return string
     */
    private function getClass($value){
        if(is_string($value)){
            return $value;
        }elseif($value instanceof \Closure){
            return "";
        }elseif(is_array($value)){
            return $value["class"];
        }
        return "";
    }

    /**
     * @param $class
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    private function getParams($class,$param){
        foreach($this->registered as $value){
            $curr_class=$this->getClass($value);
            if($curr_class==$class){
                $param_name=$param->getName();
                if(isset($value[$param_name])){
                    return $value[$param_name];
                }
            }
        }
        throw new \Exception("param err");
    }
    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->isBound($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset){
        return $this->$offset;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.174
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset) {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name){
        $is_bound=$this->isBound($name);
        if($is_bound===false){
            return null;
        }
        if(array_key_exists($name,$this->param_definitions)){
            return $this->makeParam($name);
        }
        if(array_key_exists($name,$this->instance_definitions)){
            return $this->makeInstance($name);
        }
        if(array_key_exists($name,$this->definitions)){
            return $this->makeObj($name);
        }
        return null;
    }

    /**
     * 绑定参数
     * @param $name
     * @param $content
     * @return mixed
     */
    public function bindParam($name,$content){
        if($this->isBound($name)){
            throw new \OverflowException("{$name} have exist");
        }
        $this->param_definitions[$name]=$content;
    }
    /**
     * 绑定服务
     * @param string $name 服务名称
     * @param mixed $definitions
     * @param array $params
     * @return mixed
     * @internal param $content
     */
    public function bindObj($name,$definitions=null, array $params = array()) {
        if($this->isBound($name)){
            throw new \OverflowException("{$name} have exist");
        }
        $normalized_definitions=$this->normalizeDefinitions($name,$definitions);
        $this->definitions[$name]=$normalized_definitions;
        $this->params[$name]=$params;
    }
    /**
     * 绑定单例
     * @param string $name 服务名称
     * @param       $definitions
     * @param array $params
     * @return mixed
     * @internal param $content
     */
    public function bindInstance($name, $definitions=null, array $params = array()) {
        if($this->isBound($name)){
            throw new \OverflowException("{$name} have exist");
        }
        $this->instance_definitions[$name]=$definitions;
        $this->instance_params[$name]=$params;
    }
    /**
     * 服务是否注册
     * @param String $name
     * @return bool
     */
    public function isBound($name){
        $this->checkNameFormat($name);
        return  array_key_exists($name,$this->definitions)
                || array_key_exists($name,$this->instance_definitions)
                || array_key_exists($name,$this->param_definitions);
    }

    /**
     * 标准化依赖
     * @param $name
     * @param null $definitions
     * @return array|null
     */
    private function normalizeDefinitions($name,$definitions=null){
        if(is_null($definitions)){
            if(!class_exists($name)){
                throw new \InvalidArgumentException("class {$definitions} not found");
            }
             return array("class"=> $name);
        }elseif (is_string($definitions)){
            if(!class_exists($definitions)){
                throw new \InvalidArgumentException("class {$definitions} not found");
            }
            return array("class"=>$definitions);
        }elseif(is_callable($definitions,true) || is_object($definitions)){
            return $definitions;
        }elseif (is_array($definitions)){
            if(!array_key_exists("class",$definitions) || !class_exists($definitions["class"])){
                throw new \InvalidArgumentException("class {$definitions['class']} not found");
            }
            return $definitions;
        }else{
            throw  new \InvalidArgumentException(" class wrong");
        }
    }
    /**
     * 检查格式
     * @param $name
     * @return bool
     */
    private function checkNameFormat($name){
        if(!is_string($name)){
            throw new \InvalidArgumentException("{$name} must be string ");
        }
        return true;
    }

    /**
     * 获取参数
     * @param $name
     * @return mixed
     */
    private function makeParam($name){
        if(!isset($this->resolved_param[$name])){
            $this->resolved_param[$name];
        }
        return $this->resolved_param[$name];
    }
    /**
     * 获取实例
     * @param $name
     * @return mixed
     */
    public function makeObj($name){
        $definition=$this->definitions[$name];
        $param=$this->params[$name];
        if(is_callable($definition)){
            $ins=call_user_func_array($definition,$param);
        }elseif(is_object($definition)){
            $ins=$definition;
        }else{
            $class=$definition["class"];
            $ins=$this->makeByClass($class,$param);
        }
        $this->resolved[$name]=$ins;
        return $this->resolved[$name];
    }

    /**
     * 构造单例
     * @param $name
     * @return mixed
     */
    private function makeInstance($name){
        if(array_key_exists($name,$this->resolved_instance)){
            return $this->resolved_instance[$name];
        }
        $definition=$this->instance_definitions[$name];
        $param=$this->instance_params[$name];
        if(is_callable($definition)){
            $ins=call_user_func_array($definition,$param);
        }elseif(is_object($definition)){
            $ins=$definition;
        }else{
            $class=$definition["class"];
            $ins=$this->makeByClass($class,$param);
        }
        $this->resolved_instance[$name]=$ins;
        return $this->resolved_instance[$name];
    }
    /**
     * @param $class
     * @param array $params
     * @return object
     */
    private function makeByClass($class,array $params){
        if($class instanceof \Closure){
            return $class();
        }
        $reflection=new \ReflectionClass($class);
        if(!$reflection->isInstantiable()){
            if($reflection->isAbstract() || $reflection->isInterface()){
                $name=$reflection->getName();
                return $this->$name;
            }

            throw new \LogicException("service cant to be instanced");
        }
        //无构造函数
        $constructor=$reflection->getConstructor();
        if(is_null($constructor)){
            return new $class;
        }
        //有构造函数
        $resolved_params=array();
        $con_params=$constructor->getParameters();;
        foreach($con_params as $curr_param){
            $param_name=$curr_param->name;
            $curr_param_class=$curr_param->getClass();
            $value=null;
            if(is_null($curr_param_class)){
                $default_value_ava=$curr_param->isDefaultValueAvailable();
                if(array_key_exists($param_name,$params)){
                    $value=$params[$param_name];
                }elseif($default_value_ava){
                    $value=$curr_param->getDefaultValue();
                }
            }else{
                $value=$this->makeByClass($curr_param_class->name,$this->params[$curr_param_class->name]);
            }
            $resolved_params[$param_name]=$value;
        }
        return $reflection->newInstanceArgs($resolved_params);
    }
    /**
     * 获取实例
     * @param $class
     * @return object
     * @throws \Exception
     */
    public function makeByClass1($class){
        if($class instanceof \Closure){
            return $class();
        }
        $reflection=new \ReflectionClass($class);
        if(!$reflection->isInstantiable()){
            throw new \LogicException("service cant to be instanced");
        }
        //无构造函数
        $constructor=$reflection->getConstructor();
        if(is_null($constructor)){
            return new $class;
        }
        //有构造函数
        $resolve_params=array();
        $con_params=$constructor->getParameters();
        foreach($con_params as $curr_param){
            $curr_param_class=$curr_param->getClass();
            if(!is_null($curr_param_class)){
                $resolve_params[]=$this->makeByClass($curr_param_class->getName());
            }else{
                $default_value_ava=$curr_param->isDefaultValueAvailable();
                if($default_value_ava===false){
                    $resolve_params[]=$this->getParams($class,$curr_param);
                }else{
                    $resolve_params[]=$curr_param->getDefaultValue();
                }
            }
        }
        return $reflection->newInstanceArgs($resolve_params);
    }
}