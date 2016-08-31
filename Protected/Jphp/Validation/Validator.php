<?php

namespace Jphp\Validation;

/**
 * Class Validator
 * @package Jphp\Validation
 */
class Validator {
    
    /**
     * 必填项
     * @param null $field
     * @return bool
     */
    public static function validateRequired($field = null)
    {
        if (is_null($field)) {
            return false;
        } elseif (is_string($field) && trim($field) === "") {
            return false;
        }
        return true;
    }
    
    /**
     * 邮箱
     * @param $field
     * @return bool
     */
    protected function validateEmail($field)
    {
        return filter_var($field, \FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * 是否相等
     * @param $field
     * @param $value
     * @param bool $check_type
     * @return bool
     */
    protected function validateEquals($field, $value, $check_type = false)
    {
        return $check_type ? $field === $value : $field == $value;
    }
    
    /**
     * 是否数组
     * @param $field
     * @return mixed
     */
    protected function validateArray($field)
    {
        return is_array($field);
    }
    
    /**
     * 是否数字
     * @param $field
     * @return mixed
     */
    protected function validateNumeric($field)
    {
        return is_numeric($field);
    }
    
    /**
     * 是否整数
     * @param $field
     * @return bool
     */
    protected function validateInteger($field)
    {
        return filter_var($field, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * 最短长度
     * @param $field
     * @param $min
     * @return bool
     */
    protected function validateMinLength($field, $min)
    {
        $length = $this->computeStringLength($field);
        return $length >= $min;
    }
    
    /**
     * 最大长度
     * @param $field
     * @param $max
     * @return mixed
     */
    protected function validateMaxLength($field, $max)
    {
        $length = $this->computeStringLength($field);
        return $length <= $max;
    }
    
    /**
     * 长度范围
     * @param $field
     * @param $min
     * @param $max
     * @return bool
     */
    protected function validateBetweenLength($field, $min, $max)
    {
        if ($min > $max) {
            throw new \InvalidArgumentException("min > max");
        }
        return $this->validateMinLength($field, $max) && $this->validateMaxLength($field, $max);
    }
    
    /**
     * 验证IP
     * @param $field
     * @return bool
     */
    protected function validateIp($field)
    {
        return filter_var($field, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * 布尔值
     * @param $field
     * @return bool
     */
    protected function validateBoolean($field)
    {
        return filter_var($field, FILTER_VALIDATE_BOOLEAN) !== false;
    }
    
    /**
     * 计算字符串长度
     * @param $field
     * @return mixed
     */
    protected function computeStringLength($field)
    {
        if (!is_string($field)) {
            throw new \InvalidArgumentException("String required");
        }
        if (function_exists("mb_strlen")) {
            return mb_strlen($field);
        }
        return strlen($field);
    }
}