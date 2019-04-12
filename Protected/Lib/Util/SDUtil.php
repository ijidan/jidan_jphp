<?php

namespace Lib\Util;

use App\Model\SdProduct;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * 网站工具类
 * Class SDUtil
 * @package Lib\Util
 */
class SDUtil {

	/***
	 * 上传USB Sticker
	 * @param OutputInterface|null $output
	 */
	public static function uploadUS(OutputInterface $output = null) {
		$dir = "/vagrant/usb_stick";
		$catId = 52;
		$catName = "usb stick";
		$dirName = "usb_stick";

		$di = new \DirectoryIterator($dir);
		foreach ($di as $idx => $item) {
			$pathName = $item->getPathname();
			$fileName = $item->getFilename();
			$fileInfo = $item->getFileInfo();
			$docName = str_replace(strrchr($fileName, "."), "", $fileName);
			$docNameInt = CommonUtil::extractNumber($docName);
			$sort = $docNameInt > 0 ? $docNameInt : $idx + 1;

			$contents = '<img src="/Uploads/' . $dirName . '/' . $fileName . '" alt="" />';
			$data = [
				"name"         => "$docName",
				"title"        => "$catName:$docName",
				"url"          => "$docName",
				"keywords"     => "$catName:$docName",
				"description"  => "$catName:$docName",
				"contents"     => $contents,
				"ename"        => "$docName",
				"etitle"       => "$catName:$docName",
				"ekeywords"    => "$catName:$docName",
				"edescription" => "$catName:$docName",
				"econtents"    => $contents,
				"pid"          => "$catId",
				"bid"          => $sort,
				"photo"        => "$dirName/$fileName",
				"thumb"        => "$dirName/$fileName",
				"property1"    => "",
				"property2"    => "",
				"property3"    => "",
				"property4"    => "",
				"eproperty1"   => "",
				"eproperty2"   => "",
				"eproperty3"   => "",
				"eproperty4"   => "",
				"sort"         => "$sort",
				"featured"     => "0"
			];
			$where = "url='" . $docName . "'";
			$checkRe = SdProduct::find($where);
			if ($checkRe) {
				SdProduct::update($data, $where);
				if($output){
					$output->writeln("更新：$docName");
				}
			} else {
				SdProduct::insert($data);
				if($output){
					$output->writeln("新增：$docName");
				}
			}
		}
	}
}