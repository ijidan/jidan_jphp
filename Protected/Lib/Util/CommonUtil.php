<?php

namespace Lib\Util;

/**
 * 公共工具类
 * Class CommonUtil
 * @package Lib\Util
 */
class CommonUtil {

	/**
	 * 获取分享
	 * @param $ids
	 */
	public static function getShardUids($ids) {

	}

	/**
	 * BASE64加密
	 * @param $input
	 * @return bool|string
	 */
	public static function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}

	/**
	 * BASE64解密
	 * @param $input
	 * @return string
	 */
	public static function base64_url_encode($input) {
		return strtr(base64_encode($input), '+/', '-_');
	}

	/**
	 * 小数点后两位
	 * @param $num
	 * @return float
	 */
	public static function numberFormat($num) {
		return round($num, 2);
	}

	/**
	 * 用于多语言的字符串替换
	 * @param $str
	 * @param array $replace
	 * @return mixed
	 */
	public static function replace($str, array $replace) {
		$keys = []; //需要替换的key
		$vals = []; //替换的值
		foreach ($replace as $key => $val) {
			$keys[] = '{{' . $key . '}}';
			$vals[] = $val;
		}
		return str_replace($keys, $vals, $str);
	}

	/**
	 * 读取php文件，返回php54格式的（短格式）的字符串
	 * @param $file
	 * @return string
	 */
	public static function convertArraysToSquareBrackets($file) {
		$code = file_get_contents($file);
		$out = '';
		$brackets = [];
		$tokens = token_get_all($code);
		for ($i = 0; $i < count($tokens); $i++) {
			$token = $tokens[$i];
			if ($token === '(') {
				$brackets[] = false;
			} elseif ($token === ')') {
				$token = array_pop($brackets) ? ']' : ')';
			} elseif (is_array($token) && $token[0] === T_ARRAY) {
				$a = $i + 1;
				if (isset($tokens[$a]) && $tokens[$a][0] === T_WHITESPACE) {
					$a++;
				}
				if (isset($tokens[$a]) && $tokens[$a] === '(') {
					$i = $a;
					$brackets[] = true;
					$token = '[';
				}
			}
			$out .= is_array($token) ? $token[1] : $token;
		}
		return $out;
	}

	/**
	 * 数组格式化成PHP54的ARRAY
	 * @param $var
	 * @param string $indent
	 * @return mixed|string
	 */
	public static function var_export54($var, $indent = "") {
		switch (gettype($var)) {
			case "string":
				return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
			case "array":
				$indexed = array_keys($var) === range(0, count($var) - 1);
				$r = [];
				foreach ($var as $key => $value) {
					$r[] = "$indent    " . ($indexed ? "" : self::var_export54($key) . " => ") . self::var_export54($value, "$indent    ");
				}
				return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
			case "boolean":
				return $var ? "TRUE" : "FALSE";
			default:
				return var_export($var, true);
		}
	}

	/**
	 * 数字自动三位三位加逗号
	 * @param $num
	 * @return array|string
	 */
	public static function tran($num) {
		$v = explode('.', $num);//把整数和小数分开
		$rl = $v[1];//小数部分的值
		$j = strlen($v[0]) % 3;//整数有多少位
		$sl = substr($v[0], 0, $j);//前面不满三位的数取出来
		$sr = substr($v[0], $j);//后面的满三位的数取出来
		$i = 0;
		$rvalue = '';
		while ($i <= strlen($sr)) {
			$rvalue = $rvalue . ',' . substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开
			$i = $i + 3;
		}
		$rvalue = $sl . $rvalue;
		$rvalue = substr($rvalue, 0, strlen($rvalue) - 1);//去掉最后一个逗号
		$rvalue = explode(',', $rvalue);//分解成数组
		if ($rvalue[0] == 0) {
			array_shift($rvalue);//如果第一个元素为0，删除第一个元素
		}
		$rv = $rvalue[0];//前面不满三位的数
		for ($i = 1; $i < count($rvalue); $i++) {
			$rv = $rv . ',' . $rvalue[$i];
		}
		if (!empty ($rl)) {
			$rvalue = $rv . '.' . $rl;//小数不为空，整数和小数合并
		} else {
			$rvalue = $rv;//小数为空，只有整数
		}
		return $rvalue;
	}

	/**
	 * 提取字符串
	 * @param string $str
	 * @return string
	 */
	public static function extractNumber($str = '') {
		$str = trim($str);
		if (empty($str)) {
			return '';
		}
		$temp = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
		$result = '';
		for ($i = 0; $i < strlen($str); $i++) {
			if (in_array($str[$i], $temp)) {
				$result .= $str[$i];
			}
		}
		return intval($result);
	}
}