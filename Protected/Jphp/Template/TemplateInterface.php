<?php

namespace Jphp\Template;

/**
 * Interface TemplateInterface
 * @package Jphp\Template
 */
interface TemplateInterface {
    /**
     * @return mixed
     */
    public function parseVariable();
    
    /**
     * @return mixed
     */
    public function parseComment();
    
    /**
     * @return mixed
     */
    public function parseTags();
}