<?php

namespace Jphp\View;

/**
 * Class ViewBase
 * @package Jphp\View
 */
class ViewBase {
    public function __construct($file_path, $data)
    {
        $file_path = str_replace("\\", DS, $file_path);
        $file_path = str_replace("\/", DS, $file_path);
        $file = VIEW_PATH . $file_path . ".php";
        if (is_file($file) && file_exists($file)) {
            extract($data);
            include $file;
        } else {
            throw new \Exception("view not exist");
        }
    }
} 