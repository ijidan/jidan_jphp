<?php

namespace Jphp\Database;

/**
 * Interface ActiveRecordInterface
 * @package Jphp\Database
 */
interface ActiveRecordInterface {

    public function getPrimaryKey();
    public function isPrimaryKey($key);
    public function getTableSchema();
    public function getAttributes();
    public function getAttribute($name);
    public function setAttribute($name,$value);
    public function hasAttribute($name);

    public function insert();
    public function update();
    public function save();
    public function delete();

    public static function getDbConnection();
    public static function find($pk);
    public static function findAllByPks($pks);
    public static function findAllByAttributes(array $attributes);
    public static function updateAllByAttributes(array $attributes,$condition);
    public static function updateAllByPks(array $attributes,array $pks);
    public static function deleteAllByPks(array $pks);
    public static function deleteAllByAttributes(array $attributes);

}