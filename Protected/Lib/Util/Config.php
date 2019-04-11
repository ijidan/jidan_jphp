<?php

namespace Lib\Util;

/**
 * 获取配置工具类
 * Class Config
 * @package Lib\Util
 */
class Config {
	private static $configList = [];
	private static $dataList = [];
	private static $i18nList = [];

	/**
	 * 加载文件
	 * @param $fileName
	 * @return mixed
	 * @throws \ErrorException
	 */
	public static function loadConfig($fileName) {
		$path = BASE_DIR . "/config/{$fileName}.php";
		if (!isset(self::$configList[$path])) {
			self::$configList[$path] = self::load($path);
		}
		return self::$configList[$path];
	}

	/**
	 * 加载数据
	 * @param $fileName
	 * @return mixed
	 * @throws \ErrorException
	 */
	public static function loadData($fileName) {
		//$list = explode('.', $fileName);
		//$fileName = implode('/', $list);
		$defaultPath = BASE_DIR . "/data/default/{$fileName}.php";
		$path = BASE_DIR . "/data/{$fileName}.php";
		if (!isset(self::$dataList[$path])) {
			if (file_exists($defaultPath)) {
				self::$dataList[$path] = array_replace_recursive(self::load($defaultPath), self::load($path));
			}
			self::$dataList[$path] = self::load($path);
		}
		return self::$dataList[$path];
	}

	/**
	 * 加入语言文件
	 * @param $lang
	 * @param $fileName
	 * @return mixed
	 * @throws \ErrorException
	 */
	public static function loadI18n($lang, $fileName) {
		$path = BASE_DIR . "/i18n/{$lang}/{$fileName}.php";
		if (!isset(self::$i18nList[$path])) {
			self::$i18nList[$path] = self::load($path);
		}
		return self::$i18nList[$path];
	}

	/**
	 * 记载PHP文件
	 * @param $path
	 * @return mixed
	 * @throws \ErrorException
	 */
	private static function load($path) {
		if (!file_exists($path)) {
			throw new \ErrorException("Configuration file: [$path] cannot be found");
		}

		$data = require $path;

		if (!is_array($data)) {
			throw new \ErrorException('PHP file does not return an array');
		}

		return $data;
	}
}