<?php


namespace App;

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

defined("DS") or define("DS", DIRECTORY_SEPARATOR);

$jphp = __DIR__ . DS . "Protected" . DS . "Jphp" . DS . "Bootstrap.php";
require_once $jphp;
$autoload = __DIR__ . DS . "Protected" . DS . "vendor" . DS . "autoload.php";

require_once $autoload;

use Jphp\Application\WebApplication;
$app = WebApplication::getInstance(__DIR__, __NAMESPACE__);
$app->handle();
