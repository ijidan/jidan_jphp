<?php

namespace Jphp\Template;


/**
 * Class Template
 * @package Jphp\Template
 */
class Template implements TemplateInterface {
    
    private $content;
    private $data = array();
    private $variable_reg;
    private $comment_reg;
    
    /**
     * Template constructor.
     */
    public function __construct()
    {
    }
    
    /**
     * 解析
     * @param $file
     * @param array $data
     */
    public function parse($file, array $data)
    {
        $this->content = file_get_contents($file);
        $this->data = $data;
        $this->setReg();
        $this->parseVariable();
        return $this->content;
    }
    
    
    /**
     * 正则
     */
    private function setReg()
    {
        $this->variable_reg = '/\{\{\s*([a-zA-Z]+[a-zA-Z0-9]*)\s*\}\}/ms';
        $this->comment_reg = '/\{\#.*\#\}/ms';
    }
    
    
    /**
     * @return mixed
     */
    public function parseVariable()
    {
        if (preg_match_all($this->variable_reg, $this->content, $matches) !== false) {
            foreach ($matches[1] as $idx => $match) {
                $this->content = str_replace($matches[0][$idx], $this->data[$match], $this->content);
            }
        }
    }
    
    /**
     * @return mixed
     */
    public function parseComment()
    {
        // TODO: Implement parseComment() method.
    }
    
    /**
     * @return mixed
     */
    public function parseTags()
    {
        // TODO: Implement parseTags() method.
    }
}