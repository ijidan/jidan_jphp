<?php

namespace App\Controller;

use App\Model\Category;
use Jphp\Controller\HttpController;
use Jphp\Http\Request;
use Jphp\Http\Response;
use League\Csv\Writer;
use Lib\Util\Get68Util;
use QL\QueryList;


/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends HttpController {
	/**
	 * 首页
	 * @param Request $request
	 * @return Response
	 * @throws \League\Csv\CannotInsertRecord
	 */
	public function indexAction(Request $request) {
		set_time_limit(0);
		$get68 = new Get68Util();
		$output = null;
		$companyList = $get68->getIndustryCompanyList(Get68Util::TYPE_GIFT_CODE, $output);
		$firstCompany = $companyList[0];
		$header = array_keys($firstCompany);
		$csv = Writer::createFromString('');
		$csv->insertOne($header);
		$csv->insertAll($companyList);
		$content = $csv->getContent(); //returns the CSV document as a string
		pr($content, 1);
		return new Response("成功");
	}


	/**
	 * 获取公司信息
	 * @param $companyId
	 * @return array
	 */
	private function getCompanyInfo($companyId) {
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

			return [
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
			return [
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
	}

	/**
	 * 获取总页数
	 * @param $type
	 * @return mixed
	 */
	private function getCompanyTotalPage($type) {
		$pattern = "/_(\d+).shtml/";
		$url = "http://www.get56.com/get56n/info/custom_${type}_1.shtml";
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
	 * 获取公司ID
	 * @param $type
	 * @return array
	 */
	private function getCompanyIdList($type) {
		$lastPage = $this->getCompanyTotalPage($type);
		$allCompanyIdList = [];
		for ($i = 1; $i <= $lastPage; $i++) {
			$url = "http://www.get56.com/get56n/info/custom_${type}_${i}.shtml";
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
			file_put_contents("gift_company_id.log", \json_encode($data) . "\r\n", FILE_APPEND);

			file_put_contents("gift_company_log.log", "第${i}页处理完毕，公司ID分别是:" . \json_encode($currentPageCompanyIdList) . " \r\n", FILE_APPEND);
			$rand = rand(1, 1000);
			usleep($rand);
		}
		return $allCompanyIdList;
	}

	/**
	 * 获取一页的数据
	 * @param $type
	 * @param $pageIndex
	 */
	private function getOnePageCompanyIdList($type, $pageIndex) {
		$totalPage = $this->getCompanyTotalPage($type);
		$pageSize = 100;
		$pageStart = ($totalPage - $pageSize * $pageIndex);
		$pageEnd = $pageStart + $pageSize;
		if ($pageEnd >= $totalPage) {
			$pageEnd = $totalPage;
		}
		$this->getRangeCompanyIdList($type, $pageStart, $pageEnd);

	}

	/**
	 * 获取公司ID
	 * @param $type
	 * @param $pageStart
	 * @param $pageEnd
	 * @return array
	 */
	private function getRangeCompanyIdList($type, $pageStart, $pageEnd) {
		$allCompanyIdList = [];
		for ($i = $pageStart; $i <= $pageEnd; $i++) {
			$url = "http://www.get56.com/get56n/info/custom_${type}_${i}.shtml";
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
			file_put_contents("gift_company_id.log", \json_encode($data) . "\r\n", FILE_APPEND);
			file_put_contents("gift_company_log.log", "第${i}页处理完毕，公司ID分别是:" . \json_encode($currentPageCompanyIdList) . " \r\n", FILE_APPEND);
			$rand = rand(1, 1000);
			usleep($rand);
		}
		return $allCompanyIdList;
	}


	/**
	 * @methods=["GET","POST"];
	 * @param Request $request
	 * @return Response
	 */
	public function testAction(Request $request) {
		$data = Category::findOne();
		$client = new \Memcached();
		$client->addServer('127.0.0.1', 11211);

		$key = $_GET["key"];
		$data = $key;
		$client->add($key, $data, 86400);
		return new Response($data);


		//
		//
		//        dump("1",1);
		//          $client=new ZookeeperClient("127.0.0.1:2181");
		//          $data=$client->makeNode("/test1","jidan1");
		//          dump($data,1);
		//          $serverList=$client->get("/server_list");
		//          dump($serverList,1);
		////        $weixin = new WeiXinObserver();
		////        $mail = new MailObserver();
		////
		////        $subject = new MessageSubject();
		////        $subject->attach($weixin);
		////        $subject->attach($mail);
		////        $subject->setMessage("haha");
		////        $subject->notify();
		//
		//
		//        $app = WebApplication::getApp();
		//        $view_path = $app->getViewPath();
		//        $test_tpl = $view_path . DIRECTORY_SEPARATOR . "test.html";
		////        $content = file_get_contents($test_tpl);
		//        $data = array("a" => 1, "b" => 2);
		////        $parser = new Parser();
		////        $content = $parser->parse($content, $data);
		//        $template = new Template();
		//        $content = $template->parse($test_tpl, $data);
		////        $container = new Container();
		////        $container["db"] = function ($c) {
		////            $connection = new Connection("localhost", "shop", "root", "root");
		////            return $connection->getConnect();
		////        };
		////        $db = $container["db"];

		//return new Response("succ");
	}

	public function mem(Request $request) {

	}

}
