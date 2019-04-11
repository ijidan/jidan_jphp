<?php

use Lib\Util\Get56Util;
use Symfony\Component\Console\Output\OutputInterface;

set_time_limit(0);

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

defined("DS") or define("DS", DIRECTORY_SEPARATOR);
require_once __DIR__ . DS . "Protected" . DS . "Jphp" . DS . "Bootstrap.php";
require_once __DIR__ . DS . "Protected" . DS . "Jphp" . DS . "Function.php";
require_once __DIR__ . DS . "Protected" . DS . "vendor" . DS . "autoload.php";


$app = new Silly\Application();

$app->command('run [type]', function ($type, OutputInterface $output) {
	$get68 = new Get56Util();
	$codeList=Get56Util::getTypeCodeList();
	$codeList=[Get56Util::TYPE_HANDCRAFTED_GIFT_CODE];
	foreach($codeList as $code){
		$companyInfoList=$get68->getIndustryCompanyList($code, $output);
		$get68->genCSVDoc($code,$companyInfoList);
		$output->writeln(Get56Util::$TYPE_CODE_NAME_MAP[$code]."执行完毕");
		$sec=mt_rand(5,30);
		sleep($sec);
	}
	$output->writeln("执行完毕");
});

$app->run();