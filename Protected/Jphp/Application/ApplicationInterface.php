<?php

namespace Jphp\Application;

/**
 * Interface ApplicationInterface
 * @package Jphp\Application
 */
interface ApplicationInterface {
    /**
     * 获取应用名称
     * @return mixed
     */
    public function getName();

    /**
     * 获取应用版本号
     * @return mixed
     */
    public function getVersion();
    /**
     * 用户应用环境
     * @return mixed
     */
    public function getEnvironment();

    /**
     * 启动应用
     * @return mixed
     */
    public function boot();

    /**
     * 应用关闭
     * @return mixed
     */
    public function shutdown();

    /**
     * 获取配置的服务
     * @return mixed
     */
    public function getRegisteredConfiguredProviders();

    /**
     * 注册服务
     * @return mixed
     */
    public function registerProviders();

    /**
     *处理请求
     * @return Response
     */
    public function handle();

    /**
     * 全局对象
     * @param ApplicationInterface $app
     * @return mixed
     */
    public static function setApp(ApplicationInterface $app);

    /**
     * 全局对象
     * @return mixed
     */
    public static function getApp();
}