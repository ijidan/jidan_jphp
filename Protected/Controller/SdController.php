<?php

namespace App\Controller;

use Jphp\Controller\HttpController;
use Jphp\Http\Request;
use Jphp\Http\Response;
use Lib\Util\SDUtil;


/**
 * Class SdController
 * @package App\Controller
 */
class SdController extends HttpController {

	/**
	 * 上传USD stick
	 * @param Request $request
	 * @return Response
	 */
	public function uploadUSAction(Request $request) {
		set_time_limit(0);

		SDUtil::uploadUS();
		dump("done",1);
		return new Response("成功");
	}
}
