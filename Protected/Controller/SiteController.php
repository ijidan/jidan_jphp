<?php

namespace App\Controller;

use App\Model\Category;
use Jphp\Application\WebApplication;
use Jphp\Controller\HttpController;
use Jphp\DesignPattern\Observer\MailObserver;
use Jphp\DesignPattern\Observer\MessageSubject;
use Jphp\DesignPattern\Observer\WeiXinObserver;
use Jphp\Http\Request;
use Jphp\Http\Response;
use Jphp\Template\Template;
use Kyoz\ZookeeperClient;
use Lex\Parser;
use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\Mongo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Jphp\Database\Connection;
use Sonata\Cache\Adapter\Cache\MemcachedCache;

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
        $data=Category::findOne();
        dump($da,1);
        $client = new \Memcached();
        $client->addServer('127.0.0.1', 11211);
        
        $key     = $_GET["key"];
        $data    = $key;
        $client->add($key,$data,86400);
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
    
    public function mem(Request $request)
    {
    
    }
    
}
