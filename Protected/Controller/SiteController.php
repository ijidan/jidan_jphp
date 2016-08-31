<?php

namespace App\Controller;

use Jphp\Application\WebApplication;
use Jphp\Controller\HttpController;
use Jphp\DesignPattern\Observer\MailObserver;
use Jphp\DesignPattern\Observer\MessageSubject;
use Jphp\DesignPattern\Observer\WeiXinObserver;
use Jphp\Http\Request;
use Jphp\Http\Response;
use Jphp\Template\Template;
use Lex\Parser;
use Monolog\Handler\Mongo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Jphp\Database\Connection;

/**
 * Class SiteController
 * @package App\Controller
 */
class SiteController extends HttpController {
    /**
     * @methods=["GET","POST"];
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        
        $weixin = new WeiXinObserver();
        $mail = new MailObserver();
        
        $subject = new MessageSubject();
        $subject->attach($weixin);
        $subject->attach($mail);
        $subject->setMessage("haha");
        $subject->notify();
        
        dump("1", 1);
        $app = WebApplication::getApp();
        $view_path = $app->getViewPath();
        $test_tpl = $view_path . DIRECTORY_SEPARATOR . "test.jidan";
        $content = file_get_contents($test_tpl);
        $data = array("a" => 1, "b" => 2);
        $parser = new Parser();
        $content = $parser->parse($content, $data);
        $template = new Template();
        $content = $template->parse($test_tpl, $data);
        dump($content, 1);
        $container = new Container();
        $container["db"] = function ($c) {
            $connection = new Connection("localhost", "shop", "root", "root");
            return $connection->getConnect();
        };
        $db = $container["db"];
        dump($db, 1);
        
        return new Response("===============");
    }
}
