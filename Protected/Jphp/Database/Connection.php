<?php

namespace Jphp\Database;

/**
 * Class Connection
 * @package Jphp\Database
 */
class Connection {

    private $host;
    private $dbName;
    private $username;
    private $password;
    private $connect;

    /**
     * 构造函数
     * @param $host
     * @param $dbName
     * @param $username
     * @param $password
     */
    public function __construct($host,$dbName,$username,$password){
        $this->setHost($host);
        $this->setDbName($dbName);
        $this->setUsername($username);
        $this->setPassword($password);
    }
    /**
     * 返回数据库连接
     * @return \PDO
     */
    public function getConnect(){
        $dsn="mysql:host={$this->host};dbname={$this->dbName};charset=UTF8;";
        try{
            $pdo=new \PDO($dsn,$this->username,$this->password);
            $this->connect=$pdo;
        }catch (\PDOException $e){
            dump($e->getMessage(),1);
        }

        return $this->connect;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @param mixed $dbName
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param \PDO $connect
     */
    public function setConnect($connect)
    {
        $this->connect = $connect;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
}