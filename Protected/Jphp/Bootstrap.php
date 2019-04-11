<?php

include "Function.php";

defined("DS") or define("DS", DIRECTORY_SEPARATOR);
defined("JPHP_PATH") or define("JPHP_PATH", __DIR__ . DS);
defined("BASE_PATH") or define("BASE_PATH", dirname(dirname(JPHP_PATH)) . DS);
defined("BASE_DIR") or define("BASE_DIR",dirname(__DIR__).DS);

/**
 * 自动加载
 * @param $class
 */
function jAutoload($class)
{
    $class_map = array(
        'Jphp\Application\WebApplication' => JPHP_PATH . "Application" . DS . "WebApplication.php"
    );
    if (strpos($class, "Jphp") !== false) {
        $path = str_replace("Jphp", rtrim(JPHP_PATH,"/"), $class);
        $path=str_replace("\\",DS,$path);
        $path=str_replace("/",DS,$path);
        $file = $path . ".php";
        if (is_file($file)) {
            require_once $file;
            return;
        }
        throw new \LogicException(" class '{$class}' not found");
    }
}

/**
 * 错误
 * @param $err_no
 * @param $err_message
 * @param $err_file
 * @param $err_line
 * @param array $err_context
 * @return bool
 */
function jErrorHandler($err_no, $err_message, $err_file, $err_line, array $err_context = array())
{
    $content = <<<EOF
        Error      No:{$err_no}</br>
        Error Message:{$err_message}</br>
        Error    File:{$err_file}</br>
        Error    Line:{$err_line}</br>
EOF;
    return false;
}

/**
 * 异常
 * @param Exception $exception
 */
function jExceptionHandler($exception)
{
    dump($exception->getMessage(), 1);
}

/**
 * Shut Down
 */
function jShutdownFunction()
{
    restore_error_handler();
    restore_exception_handler();
}

spl_autoload_register("jAutoload");
set_error_handler("jErrorHandler");
set_exception_handler("jExceptionHandler");
register_shutdown_function("jShutdownFunction");