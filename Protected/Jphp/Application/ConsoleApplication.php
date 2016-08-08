<?php
namespace Jphp\Application;

/**
 * Class ConsoleApplication
 * @package Jphp\Application
 */
class ConsoleApplication implements ApplicationInterface {
    /**
     * 构造函数
     */
    public function __construct(){
    }
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
    public function registerProvider()
    {
        // TODO: Implement registerProvider() method.
    }

    /**
     * 注册延迟加载的服务
     * @return mixed
     */
    public function registerDeferredProvider()
    {
        // TODO: Implement registerDeferredProvider() method.
    }

    /**
     * 注册服务
     * @return mixed
     */
    public function registerProviders() {
        // TODO: Implement registerProviders() method.
    }

    /**
     *处理请求
     * @return Response
     */
    public function handle() {
        // TODO: Implement handle() method.
    }

    /**
     * 全局对象
     * @param ApplicationInterface $app
     * @return mixed
     */
    public static function setApp(ApplicationInterface $app) {
        // TODO: Implement setApp() method.
    }

    /**
     * 全局对象
     * @return mixed
     */
    public static function getApp() {
        // TODO: Implement getApp() method.
    }
}