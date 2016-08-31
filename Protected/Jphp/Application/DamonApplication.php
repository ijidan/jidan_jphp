<?php
namespace Jphp\Application;

use Jphp\Http\Response;
use Jphp\Service\Container;

/**
 * Class DamonApplication
 * @package Jphp\Application
 */
class DamonApplication extends Container implements ApplicationInterface {
    
    /**
     * 获取应用名称
     * @return mixed
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }
    
    /**
     * 获取应用版本号
     * @return mixed
     */
    public function getVersion()
    {
        // TODO: Implement getVersion() method.
    }
    
    /**
     * 用户应用环境
     * @return mixed
     */
    public function getEnvironment()
    {
        // TODO: Implement getEnvironment() method.
    }
    
    /**
     * 启动应用
     * @return mixed
     */
    public function boot()
    {
        // TODO: Implement boot() method.
    }
    
    /**
     * 应用关闭
     * @return mixed
     */
    public function shutdown()
    {
        // TODO: Implement shutdown() method.
    }
    
    /**
     * 获取配置的服务
     * @return mixed
     */
    public function getRegisteredConfiguredProviders()
    {
        // TODO: Implement getRegisteredConfiguredProviders() method.
    }
    
    /**
     * 注册服务
     * @return mixed
     */
    public function registerProviders()
    {
        // TODO: Implement registerProviders() method.
    }
    
    /**
     *处理请求
     * @return Response
     */
    public function handle()
    {
        // TODO: Implement handle() method.
    }
    
    /**
     * 全局对象
     * @param ApplicationInterface $app
     * @return mixed
     */
    public static function setApp(ApplicationInterface $app)
    {
        // TODO: Implement setApp() method.
    }
    
    /**
     * 全局对象
     * @return mixed
     */
    public static function getApp()
    {
        // TODO: Implement getApp() method.
    }
}