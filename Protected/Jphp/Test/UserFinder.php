<?php

namespace Jphp\Test;

use Jphp\Database\Connection;

/**
 * Class UserFinder
 * @package Jphp\Test
 */
class UserFinder implements UserFinderInterface {
    public $db;
    
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    
    public function findUser()
    {
        $connection = $this->db->getConnect();
        $sql = "SHOW FULL COLUMNS FROM `iwebshop_goods` ";
        $state = $connection->query($sql);
        $schema = $state->fetchAll(\PDO::FETCH_ASSOC);
        return $schema;
    }
}