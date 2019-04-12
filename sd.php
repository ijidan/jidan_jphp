<?php

use Lib\Util\SDUtil;
use Symfony\Component\Console\Output\OutputInterface;

set_time_limit(0);

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

defined("DS") or define("DS", DIRECTORY_SEPARATOR);

require_once __DIR__ . DS . "Protected" . DS . "Jphp" . DS . "Function.php";
//require_once __DIR__ . DS . "Protected" . DS . "Jphp" . DS . "Bootstrap.php";
require_once __DIR__ . DS . "Protected" . DS . "vendor" . DS . "autoload.php";




$app = new Silly\Application();

$app->command('run [type]', function ($type, OutputInterface $output) {
	switch ($type){
		case "us":
			SDUtil::uploadUS($output);
	}
	$output->writeln("执行完毕");
});
$app->run();