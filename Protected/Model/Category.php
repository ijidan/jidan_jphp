<?php
namespace App\Model;

use Lib\BaseModel;


/**
 * Class Category
 * @package App\Model
 */
class Category extends BaseModel {
	/**
	 * 表前缀
	 * @return string
	 */
	public function getTablePrefix() {
		return "";
	}

	/**
	 * 表名
	 * @return mixed
	 */
	public function getTableName() {
		return "category";
	}

	/**
	 * 主键
	 * @return string
	 */
	public function getPrimaryKey() {
		return "category_id";
	}
}