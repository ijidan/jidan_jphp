<?php
namespace Lib\Util;

class CommonUtil {
    public static function getShardUids($ids)
    {
        
    }
    
    public static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
    
    public static function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/', '-_');
    }
    
    /*
     * 玩家奖励只记录小数点后两位
     */
    public static function numberFormat($num)
    {
        return round($num, 2);
    }
    
    /*
     * 用于多语言里的字符串替换
     */
    public static function replace($str, array $replace)
    {
        $keys = []; //需要替换的key
        $vals = []; //替换的值
        foreach ($replace as $key => $val) {
            $keys[] = '{{' . $key . '}}';
            $vals[] = $val;
        }
        return str_replace($keys, $vals, $str);
    }
    
    /*
     * 读取php文件，返回php54格式的（短格式）的字符串
     */
    public static function convertArraysToSquareBrackets($file)
    {
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
    
    /*
     * 数组格式化成php54的array
     */
    public static function var_export54($var, $indent = "")
    {
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
}