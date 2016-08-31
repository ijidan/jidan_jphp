<?php

namespace App\Model;

use Jphp\Database\ActiveRecord;

/**
 * Class GoodsModel
 * @package App\Model
 */
class GoodsModel extends ActiveRecord {
    /**
     * 主键
     * @return string
     */
    public function getTableName()
    {
        return "goods";
    }
    
    /**
     * 表前缀
     * @return string
     */
    public function getTablePrefix()
    {
        return "iwebshop_";
    }
    
    /**
     * 主键
     * @return string
     */
    public function getPrimaryKey()
    {
        return "id";
    }
}