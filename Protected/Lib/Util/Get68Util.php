<?php

namespace Lib\Util;


use QL\QueryList;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 爬取数据
 * Class Get68
 * @package App\Model
 */
class Get68Util {

	const TYPE_GIFT_NAME = "工艺礼品";
	const TYPE_ELECTRONIC_NAME = "消费电子";

	const TYPE_GIFT_CODE = "_gift";
	const TYPE_ELECTRONIC_CODE = "_electronic";

	/**
	 * 映射
	 * @var array
	 */
	public static $TYPE_CODE_NAME_MAP = [
		self::TYPE_GIFT_CODE       => self::TYPE_GIFT_NAME,
		self::TYPE_ELECTRONIC_CODE => self::TYPE_ELECTRONIC_NAME,
	];

	/**
	 * 映射
	 * @var array
	 */
	public static $TYPE_CODE_FILE_MAP = [
		self::TYPE_GIFT_CODE       => [
			"_id"   => "gift_company_id.log",
			"_list" => "gift_company_list.json",
			"_log"  => "gift_company_log.log"
		],
		self::TYPE_ELECTRONIC_CODE => [
			"_id"   => "elec_company_id.log",
			"_list" => "elec_company_list.json",
			"_log"  => "elec_company_log.log"
		],
	];

	/**
	 * 从文件中获取数据
	 * @param $code
	 * @return array
	 */
	public static function getCompanyIdListFromFile($code) {
		$filePath = self::$TYPE_CODE_FILE_MAP[$code]["_id"];
		$file = fopen($filePath, "r");
		$allCompanyIdList = [];
		while (!feof($file)) {
			$content = trim(fgets($file));
			if ($content) {
				$contentArr = \json_decode($content, true);
				$companyIdList = $contentArr["companyIdList"];
				$allCompanyIdList = array_merge($allCompanyIdList, $companyIdList);
			}
		}
		fclose($file);
		$uniqueAllCompanyIdList = array_unique($allCompanyIdList);
		return $uniqueAllCompanyIdList;
	}

	/**
	 * 获取公司信息
	 * @param $code
	 * @return mixed
	 */
	public static function getCompanyInfoListFromFile($code) {
		$file = self::$TYPE_CODE_FILE_MAP[$code]["_list"];
		$contents = file_get_contents($file);
		$data = \json_decode($contents, true);
		return $data;
	}


	/**
	 * 获取公司列表
	 * @param $code
	 * @param OutputInterface $output
	 * @return array
	 */
	public static function getIndustryCompanyList($code, $output) {
		$companyInfoList = self::getCompanyInfoListFromFile($code);
		if ($companyInfoList) {
			return $companyInfoList;
		}
		$companyIdList = self::getCompanyIdListFromFile($code);

		if (count($companyIdList) == 0) {
			$companyIdList = self::getCompanyIdList($code, $output);
		}
		$companyInfoList = [];
		foreach ($companyIdList as $idx => $companyId) {
			$companyInfo = self::getCompanyInfo($companyId, $output, $idx);
			if ($companyInfo["companyMail"]) {
				array_push($companyInfoList, $companyInfo);
			}
		}

		$file = self::$TYPE_CODE_FILE_MAP[$code]["_list"];
		file_put_contents($file, \json_encode($companyInfoList), FILE_APPEND);
		$companyInfoList = self::getCompanyInfoListFromFile($code);
		return $companyInfoList;
	}

	/**
	 * 获取公司ID
	 * @param $type
	 * @param OutputInterface $output
	 * @return array
	 */
	public static function getCompanyIdList($code, $output) {
		$lastPage = self::getCompanyTotalPage($code);
		$name = self::$TYPE_CODE_NAME_MAP[$code];
		$allCompanyIdList = [];
		for ($i = 1; $i <= $lastPage; $i++) {
			$url = "http://www.get56.com/get56n/info/custom_${name}_${i}.shtml";
			$queryObj = QueryList::get($url);
			$detailHtml = "div[class=seller_unit_more] a";
			$detailUrlCollection = $queryObj->find($detailHtml)->attrs("href");
			$detailUrlList = $detailUrlCollection->toArray();

			$currentPageCompanyIdList = [];
			if ($detailUrlList) {
				$pattern = "/_(\d+).shtml/";
				foreach ($detailUrlList as $detailUrl) {
					$detailMatchResult = preg_match($pattern, $detailUrl, $matches);
					if ($detailMatchResult !== false) {
						$currentId = $matches[1];
						array_push($currentPageCompanyIdList, $currentId);
						array_push($allCompanyIdList, $currentId);
					}
				}
			}
			$data = [
				"page"          => $i,
				"companyIdList" => $currentPageCompanyIdList
			];
			file_put_contents(self::$TYPE_CODE_FILE_MAP[$code]["_id"], \json_encode($data) . "\r\n", FILE_APPEND);
			$message = "第${i}页处理完毕，公司ID分别是:" . \json_encode($currentPageCompanyIdList);
			file_put_contents(self::$TYPE_CODE_FILE_MAP[$code]["_log"], $message . " \r\n", FILE_APPEND);
			if ($output) {
				$output->writeln($message);
			}

			$rand = rand(1, 1000);
			usleep($rand);
		}
		return uniqid($allCompanyIdList);
	}

	/**
	 * 获取总页数
	 * @param $code
	 * @return mixed
	 */
	public static function getCompanyTotalPage($code) {
		$name = self::$TYPE_CODE_NAME_MAP[$code];
		$pattern = "/_(\d+).shtml/";
		$url = "http://www.get56.com/get56n/info/custom_${name}_1.shtml";
		$lastPageHtml = "a[title=尾页]";
		$queryObj = QueryList::get($url);
		/** @var TYPE_NAME $queryObj */
		$lastUrl = $queryObj->find($lastPageHtml)->attr("href");
		$matchResult = preg_match($pattern, $lastUrl, $matches);
		if ($matchResult === false) {
			dump("尾页解析错误", 1);
		}
		$lastPage = $matches[1];
		return $lastPage;
	}

	/**
	 * 获取公司信息
	 * @param $companyId
	 * @param OutputInterface $output
	 * @param $idx
	 * @return array
	 */
	public static function getCompanyInfo($companyId, $output, $idx) {
		$url = "http://www.get56.com/get56n/info/customdetail_${companyId}.shtml";
		$queryObj = QueryList::get($url);
		$divHtml = "div[class=seller_detail_info_unit_detail]";
		$InfoList = $queryObj->find($divHtml)->texts();
		$infoArr = $InfoList->toArray();

		$companyName = $infoArr[0];
		$companyAddress = $infoArr[1];
		$companyIndustry = $infoArr[2];
		$companyCountry = $infoArr[3];
		$companyContacts = $infoArr[4];
		$companyTel = $infoArr[5];
		$companyPhone = $infoArr[6];
		$companyFax = $infoArr[7];
		$companyMail = $infoArr[8];
		if ($companyMail) {
			$companyMailList = explode("@", $companyMail);
			$userName = $companyMailList[0];

			$companyInfo = [
				"companyName"     => $companyName,
				"companyAddress"  => $companyAddress,
				"companyIndustry" => $companyIndustry,
				"companyCountry"  => $companyCountry,
				"companyContacts" => $companyContacts,
				"companyTel"      => $companyTel,
				"companyPhone"    => $companyPhone,
				"companyFax"      => $companyFax,
				"companyMail"     => $companyMail,
				"userName"        => $userName,
			];
		} else {
			$companyInfo = [
				"companyName"     => "",
				"companyAddress"  => "",
				"companyIndustry" => "",
				"companyCountry"  => "",
				"companyContacts" => "",
				"companyTel"      => "",
				"companyPhone"    => "",
				"companyFax"      => "",
				"companyMail"     => "",
				"userName"        => "",
			];
		}
		$idx += 1;
		if ($output) {
			$output->writeln("第${idx}个公司【${companyId}】数据获取完毕.");
		}
		return $companyInfo;
	}

}