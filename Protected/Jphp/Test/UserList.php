<?php

namespace Jphp\Test;

/**
 * Class UserList
 * @package Jphp\Test
 */
class UserList {
    /**
     * @var UserFinderInterface
     */
    public $finder;
    
    /**
     * UserList constructor.
     * @param UserFinderInterface $finder
     */
    public function __construct(UserFinderInterface $finder)
    {
        $this->finder = $finder;
    }
    
    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->finder->findUser();
    }
}