<?php

namespace Jphp\Database;

use ArrayAccess;
use Jphp\Exception\NotSupportedException;
use PDO;
use PDOStatement;
use Serializable;

/**
 * Class ActiveRecord
 * @package Jphp\Database
 */
abstract class ActiveRecord implements ActiveRecordInterface, Serializable, ArrayAccess{

    /**
     * 属性
     * @var array
     */
    private $attributes=array();
    /**
     * 未变化属性
     * @var array
     */
    private $oldAttributes=array();
    /**
     * 列
     * @var array
     */
    private $columns=array();

    /**
     * 表前缀
     * @return string
     */
    public  function getTablePrefix(){
        return "";
    }
    /**
     * 表名
     * @return mixed
     */
    public abstract function getTableName();

    /**
     * 主键
     * @return string
     */
    public function getPrimaryKey(){
        return "id";
    }

    /**
     * 是否主键
     * @param $key
     * @return bool
     */
    public function isPrimaryKey($key){
        return $this->getPrimaryKey()==$key;
    }

    /**
     * 表结构
     * @return array
     */
    public function getTableSchema(){
        $connection=self::getDbConnection();
        $sql="SHOW FULL COLUMNS FROM `{$this->getFullTableName()}` ";
        $state=$connection->query($sql);
        $schema=$state->fetchAll(PDO::FETCH_ASSOC);
        return $schema;
    }

    /**
     * @return array
     */
    public function getAttributes(){
        return $this->attributes;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getAttribute($name){
        return $this->attributes[$name];
    }

    /**
     * 设置属性
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value){
        $this->attributes[$name]=$value;
    }

    /**
     * 是否拥有属性
     * @param $name
     * @return mixed
     */
    public function hasAttribute($name) {
        return (boolval(isset($this->attributes[$name])));
    }

    /**
     * 批量设置属性
     * @param array $attributes
     */
    public function setAttributes(array $attributes){
        foreach ($attributes as $attr_name=>$attr_value){
            $this->setAttribute($attr_name,$attr_value);
        }
    }
    /**
     * 批量设置属性
     * @param array $attributes
     */
    public function setOldAttributes(array $attributes){
        foreach ($attributes as $attr_name=>$attr_value){
            $this->setOldAttribute($attr_name,$attr_value);
        }
    }

    /**
     * 设置列
     */
    public function setColumns(){
        $this->columns=array_keys($this->attributes);
    }

    /**
     * 获取列
     * @return array
     */
    public function getColumns(){
        return $this->columns;
    }
    /**
     * 设置未变化的属性
     * @param $name
     * @param $value
     */
    public function setOldAttribute($name,$value){
        $this->oldAttributes[$name]=$value;
    }

    /**
     * 插入之前调用
     * @return bool
     */
    public function onBeforeInsert(){
        return true;
    }

    /**
     * 更新之前调用
     * @return bool
     */
    public function onBeforeUpdate(){
        return true;
    }

    /**
     * 删除之前调用
     * @return bool
     */
    public function onBeforeDelete(){
        return true;
    }

    /**
     * 添加数据
     * @return bool|int
     */
    public function insert(){
        if($this->onBeforeInsert()===false){
            return false;
        }
        $query=new Query();
        $query->setPrefix($this->getTablePrefix())->from($this->getTableName());
        $attributes=$this->getChangedAttributes();
        $query->setData($attributes)->insert();
        $connection=self::getDbConnection();
        $count=$connection->exec($query);
        return $count;
    }
    /**
     * 更新
     * @return bool|int
     */
    public function update() {
        if($this->onBeforeUpdate()===false){
            return false;
        }
        $query=new Query();
        $query->setPrefix($this->getTablePrefix())->from($this->getTableName());
        $primary_key=$this->getPrimaryKey();
        $condition="{$primary_key}={$this->$primary_key}";
        $attributes=$this->getChangedAttributes();
        $query->setData($attributes)->update()->where($condition);
        $connection=self::getDbConnection();
        $count=$connection->exec($query);
        return $count;
    }

    /**
     * 保存数据
     * @return mixed
     */
    public function save(){
        $primary_key=$this->getPrimaryKey();
        if($this->$primary_key){
            return $this->update();
        }else{
            return $this->insert();
        }
    }

    /**
     * 删除
     * @return int
     */
    public function delete(){
        if($this->onBeforeDelete()===false){
            return false;
        }

        $query=new Query();
        $primary_key=$this->getPrimaryKey();
        $primary=$this->$primary_key;
        $where="{$primary_key}={$primary}";
        $query->setPrefix($this->getTablePrefix())->delete()->from($this->getTableName())->where($where);
        /** @var PDO $connection */
        $connection=self::getDbConnection();
        /** @var PDOStatement $stat */
        $affected_count=$connection->exec($query);
        return $affected_count;
    }
    /**
     * 根据主键查询
     * @param $pk
     * @return mixed|null
     */
    public static function find($pk){
        $model=self::getCaller();
        $table_prefix=$model->getTablePrefix();
        $table_name=$model->getTableName();
        $primary_key=$model->getPrimaryKey();
        $query=new Query();
        $where="{$primary_key}={$pk}";
        $query->setPrefix($table_prefix)->select()->from($table_name)->where($where);
        /** @var PDO $connection */
        $connection=self::getDbConnection();
        /** @var PDOStatement $stat */
        $stat=$connection->query($query->genSQL());
        $record=$stat->fetch(PDO::FETCH_ASSOC);
        if($record===false){
            return null;
        }
        $obj=new static();
        $obj->setAttributes($record);
        $obj->setOldAttributes($record);
        $obj->setColumns();
        return $obj;
    }

    /**
     * @return \PDO
     */
    public static function getDbConnection(){
        $connection=new Connection("localhost","shop","root","root");
        return $connection->getConnect();
    }

    public static function findAllByPks($pks)
    {
        // TODO: Implement findAllByPks() method.
    }

    public static function findAllByAttributes(array $attributes)
    {
        // TODO: Implement findAllByAttributes() method.
    }

    public static function updateAllByAttributes(array $attributes, $condition)
    {
        // TODO: Implement updateAllByAttributes() method.
    }

    public static function updateAllByPks(array $attributes, array $pks)
    {
        // TODO: Implement updateAllByPks() method.
    }

    public static function deleteAllByPks(array $pks)
    {
        // TODO: Implement deleteAllByPks() method.
    }

    public static function deleteAllByAttributes(array $attributes)
    {
        // TODO: Implement deleteAllByAttributes() method.
    }

    /**
     * get caller
     * @return mixed
     */
    public static function getCaller(){
        $class=get_called_class();
        $obj=new $class();
        return $obj;
    }

    /**
     * 完整表名
     * @return string
     */
    private function getFullTableName(){
        $prefix=$this->getTablePrefix();
        $table_name=$this->getTableName();
        return $prefix.$table_name;
    }

    /**
     * 获取改变了的属性
     * @return mixed
     */
    private function getChangedAttributes(){
        return array_diff_assoc($this->attributes,$this->oldAttributes);
    }

    /**
     * 设置属性
     * @param $name
     * @param $value
     */
    public function __set($name,$value){
        $this->setAttribute($name,$value);
    }

    /**
     * 获取属性
     * @param $name
     * @return mixed|null
     */
    public function __get($name){
        if($this->hasAttribute($name)){
            return $this->getAttribute($name);
        }
        return null;
    }
    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() {
        return serialize($this->attributes);
        // TODO: Implement serialize() method.
    }
    
	/**
     * @param string $serialized
     * @throws NotSupportedException
     */
    public function unserialize($serialized) {
        throw new NotSupportedException();
    }
    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset) {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset) {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value) {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {
        // TODO: Implement offsetUnset() method.
    }
}