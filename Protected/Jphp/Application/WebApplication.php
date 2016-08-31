<?php
namespace Jphp\Application;

use Jphp\Config\Config;
use Jphp\Database\Connection;
use Jphp\Http\Kernel;
use Jphp\Http\Request;
use Jphp\Http\Response;
use Jphp\Http\Router;
use Jphp\Middleware\Auth;
use Jphp\Middleware\CSRF;
use Jphp\Service\Container;
use Jphp\Test\UserFinder;
use Jphp\Test\UserFinderInterface;
use Jphp\Test\UserList;

/**
 * Class WebApplication
 * @package Jphp\Application
 */
class WebApplication extends Container implements ApplicationInterface {
    
    private static $instance = null;
    private static $app;
    
    private $app_ns;
    private $basePath;
    private $protectedPath;
    private $configPath;
    private $controllerPath;
    private $databasePath;
    private $domainPath;
    private $modelPath;
    private $runtimePath;
    private $viewPath;
    private $middleWareStack;
    
    /**
     * 构造函数
     * @param String $base_path 应用程序路径
     * @param $app_ns
     */
    private function __construct($base_path, $app_ns)
    {
        $this->setAppNs($app_ns);
        $this->setPath($base_path);
        $this->registerAutoLoad();
        $this->registerBindings();
        $this->registerMiddleWares();
        $this->registerProviders();
        $this->registerDeferredProviders();
    }
    
    /**
     * @return mixed
     */
    public function getAppNs()
    {
        return $this->app_ns;
    }
    
    /**
     * @param mixed $app_ns
     */
    public function setAppNs($app_ns)
    {
        $this->app_ns = $app_ns;
    }
    
    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * @param mixed $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }
    
    /**
     * @return mixed
     */
    public function getProtectedPath()
    {
        return $this->protectedPath;
    }
    
    /**
     * @param mixed $protectedPath
     */
    public function setProtectedPath($protectedPath)
    {
        $this->protectedPath = $protectedPath;
    }
    
    /**
     * @return mixed
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }
    
    /**
     * @param mixed $configPath
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = $configPath;
    }
    
    /**
     * @return mixed
     */
    public function getControllerPath()
    {
        return $this->controllerPath;
    }
    
    /**
     * @param mixed $controllerPath
     */
    public function setControllerPath($controllerPath)
    {
        $this->controllerPath = $controllerPath;
    }
    
    /**
     * @return mixed
     */
    public function getDatabasePath()
    {
        return $this->databasePath;
    }
    
    /**
     * @param mixed $databasePath
     */
    public function setDatabasePath($databasePath)
    {
        $this->databasePath = $databasePath;
    }
    
    /**
     * @return mixed
     */
    public function getDomainPath()
    {
        return $this->domainPath;
    }
    
    /**
     * @param mixed $domainPath
     */
    public function setDomainPath($domainPath)
    {
        $this->domainPath = $domainPath;
    }
    
    /**
     * @return mixed
     */
    public function getModelPath()
    {
        return $this->modelPath;
    }
    
    /**
     * @param mixed $modelPath
     */
    public function setModelPath($modelPath)
    {
        $this->modelPath = $modelPath;
    }
    
    /**
     * @return mixed
     */
    public function getRuntimePath()
    {
        return $this->runtimePath;
    }
    
    /**
     * @param mixed $runtimePath
     */
    public function setRuntimePath($runtimePath)
    {
        $this->runtimePath = $runtimePath;
    }
    
    /**
     * @return mixed
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }
    
    /**
     * 防止克隆
     */
    private function __clone()
    {
    }
    
    /**
     * 获取应用对象
     * @param $base_path
     * @param $app_ns
     * @return null|static
     */
    public static function getInstance($base_path, $app_ns)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($base_path, $app_ns);
        }
        
        self::setApp(self::$instance);
        return self::$instance;
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
    public function registerProviders()
    {
        // TODO: Implement registerProvider() method.
    }
    
    /**
     * 注册延迟加载的服务
     * @return mixed
     */
    public function registerDeferredProviders()
    {
        // TODO: Implement registerDeferredProvider() method.
    }
    
    /**
     * 设置应用程序的相关路径
     * @param $base_path
     */
    private function setPath($base_path)
    {
        $this->basePath = $base_path . DIRECTORY_SEPARATOR;
        $this->protectedPath = $this->basePath . "Protected" . DIRECTORY_SEPARATOR;
        $this->configPath = $this->protectedPath . "Config";
        $this->controllerPath = $this->protectedPath . "Controller";
        $this->databasePath = $this->protectedPath . "Database";
        $this->domainPath = $this->protectedPath . "Domain";
        $this->modelPath = $this->protectedPath . "Model";
        $this->runtimePath = $this->protectedPath . "Runtime";
        $this->viewPath = $this->protectedPath . "View";
    }
    
    /**
     * 注册自动加载
     */
    private function registerAutoLoad()
    {
        spl_autoload_register(array($this, "autoLoad"));
    }
    
    /**
     * 注册中间件
     */
    private function registerMiddleWares()
    {
        if (is_null($this->middleWareStack)) {
            $this->middleWareStack = new \SplStack();
        }
        $this->middleWareStack->push(CSRF::class);
        $this->middleWareStack->push(Auth::class);
    }
    
    /**
     *绑定默认服务
     */
    private function registerBindings()
    {
        //        $db=Connection::class;
        //        $this->bindObj($db,Connection::class,array(
        //            "host"=>"localhost",
        //            "dbName"=>"shop",
        //            "username"=>"root",
        //            "password"=>"root",
        //        ));
        //
        //        $db=$this->$db;
        //
        //        $ufi=UserFinderInterface::class;
        //        $this->bindObj($ufi,UserFinder::class);
        //        $user_finder_intl=$this->$ufi;
        //        $ul=UserList::class;
        //        $this->bindObj($ul,UserList::class);
        //
        //        $user_list=$this->$ul;
        //        $user=$user_list->getInfo();
        //        dump($user,1);
        //        dump($user_list,1);
        
        $kernel_cls = Kernel::class;
        $kernel = new $kernel_cls($this);
        $this->bindInstance(Kernel::class, $kernel);
        $config = new Config($this->configPath);
        $this->bindInstance("config", $config);
        $this->bindInstance("request", function () {
            $request = Request::createFromGlobals();
            return $request;
        });
        $router_cls = Router::class;
        $router = new $router_cls($this);
        $this->bindInstance("router", $router);
    }
    
    /**
     * 处理请求
     */
    public function handle()
    {
        $csrf = new CSRF();
        $request = $this->request;
        $response = new Response();
        
        $data = $csrf($request, $response, function () {
        });
        $kernel = Kernel::class;
        $this->$kernel->handle($this->request);
        // TODO: Implement handle() method.
    }
    
    /**
     * 自动加载
     * @param $class_name
     */
    private function autoLoad($class_name)
    {
        if (strpos($class_name, $this->app_ns) !== false) {
            $file = str_replace($this->app_ns, $this->basePath . "Protected", $class_name);
            $file = str_replace("\\", DIRECTORY_SEPARATOR, $file) . ".php";
            if (is_file($file)) {
                require_once $file;
                return;
            }
            throw new \LogicException(" class '{$class_name}' not found");
        }
    }
    
    /**
     * 全局对象
     * @param ApplicationInterface $app
     * @return mixed
     */
    public static function setApp(ApplicationInterface $app)
    {
        self::$app = $app;
    }
    
    /**
     * 全局对象
     * @return mixed
     */
    public static function getApp()
    {
        return self::$app;
    }
}