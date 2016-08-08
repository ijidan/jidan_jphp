<?php

namespace App;

use Jphp\Application\WebApplication;

defined("DS") or define("DS",DIRECTORY_SEPARATOR);

$jphp=__DIR__.DS."Protected".DS."Jphp".DS."Bootstrap.php";
require_once $jphp;
$autoload=__DIR__.DS."Protected".DS."vendor".DS."autoload.php";
require_once $autoload;

$app=WebApplication::getInstance(__DIR__,__NAMESPACE__);
$app->handle();