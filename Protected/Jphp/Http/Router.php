<?php

namespace Jphp\Http;

use Jphp\Application\WebApplication;

class Router {

    const DEFAULT_CONTROLLER="Site";
    const DEFAULT_ACTION="index";

    private $app;
    private $controller;
    private $action;

    /**
     * 构造函数
     * @param WebApplication $app
     */
    public function __construct(WebApplication $app){
        $this->app=$app;
    }

    /**
     * 处理请求
     * @param Request $request
     * @return mixed
     */
    public function dispatch(Request $request){
        $query=$request->query;
        if(isset($query["r"])){
            $r=$query["r"];
            $r_arr=explode("/",$r);
            if(count($r_arr)!=2){
                throw new \LengthException("route format error");
            }
            $this->controller=$r_arr[0];
            $this->action=$r_arr[1];
        }else{
            $this->controller=self::DEFAULT_CONTROLLER;
            $this->action=self::DEFAULT_ACTION;
        }
        $ns=$this->app->getAppNs();
        $ctrl_class=$ns."\\Controller\\".ucfirst($this->controller)."Controller";
        $act_func=strtolower($this->action)."Action";
        if(!class_exists($ctrl_class)){
            throw new \RuntimeException("controller {$this->controller} not found");
        }
        if(!method_exists($ctrl_class,$act_func)){
            throw new \BadMethodCallException(" {$act_func} not found in class {$ctrl_class}");
        }
        $response=call_user_func_array(array(new $ctrl_class(),$act_func),array($request));
        return $response;
    }
} 