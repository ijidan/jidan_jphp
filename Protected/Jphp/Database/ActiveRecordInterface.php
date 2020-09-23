<?php

namespace Jphp\Database;

/**
 * Interface ActiveRecordInterface
 * @package Jphp\Database
 */
interface ActiveRecordInterface {

	/**
	 * 获取主键
	 * @return mixed
	 */
	public function getPrimaryKey();

	/**
	 * 是否主键
	 * @param $key
	 * @return mixed
	 */
	public function isPrimaryKey($key);

	/**
	 * 获取表结构
	 * @return mixed
	 */
	public function getTableSchema();

	/**
	 * 获取属性
	 * @return mixed
	 */
	public function getAttributes();

	/**
	 * 获取属性
	 * @param $name
	 * @return mixed
	 */
	public function getAttribute($name);

	/**
	 * 设置属性
	 * @param $name
	 * @param $value
	 * @return mixed
	 */
	public function setAttribute($name, $value);

	/**
	 * 是否有属性
	 * @param $name
	 * @return mixed
	 */
	public function hasAttribute($name);

	/**
	 * 插入
	 * @return mixed
	 */
	public function insert();

	/**
	 * 更新
	 * @return mixed
	 */
	public function update();

	/**
	 * 保存
	 * @return mixed
	 */
	public function save();

	/**
	 * 删除
	 * @return mixed
	 */
	public function delete();

	/**
	 * 获取数据库连接
	 * @return mixed
	 */
	public static function getDbConnection();

	/**
	 * 通过主键查询
	 * @param $pk
	 * @return mixed
	 */
	public static function find($pk);

	/**
	 * 通过批量主键查询
	 * @param $pks
	 * @return mixed
	 */
	public static function findAllByPks($pks);

	/**
	 * 通过批量属性查询
	 * @param array $attributes
	 * @return mixed
	 */
	public static function findAllByAttributes(array $attributes);

	/**
	 * 通过批量属性更新
	 * @param array $attributes
	 * @param $condition
	 * @return mixed
	 */
	public static function updateAllByAttributes(array $attributes, $condition);

	/**
	 * 通过批量主键更新
	 * @param array $attributes
	 * @param array $pks
	 * @return mixed
	 */
	public static function updateAllByPks(array $attributes, array $pks);

	/**
	 * 通过批量主键删除
	 * @param array $pks
	 * @return mixed
	 */
	public static function deleteAllByPks(array $pks);

	/**
	 * 通过批量属性删除
	 * @param array $attributes
	 * @return mixed
	 */
	public static function deleteAllByAttributes(array $attributes);

}