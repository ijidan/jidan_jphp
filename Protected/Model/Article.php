<?php
namespace App\Model;

use Lib\BaseModel;


/**
 * Class Article
 * @package Tools\Models
 */
class Article extends BaseModel {
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
		return "article";
	}

	/**
	 * 主键
	 * @return string
	 */
	public function getPrimaryKey() {
		return "article_id";
	}
}