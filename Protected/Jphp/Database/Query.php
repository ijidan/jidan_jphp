<?php

namespace Jphp\Database;

/**
 * Class Query
 * @package Jphp\Database
 */
class Query {
    const SELECT="select";
    const UPDATE="update";
    const DELETE="delete";
    const INSERT="insert";

    private $operation;
    private $field;
    private $table_prefix="";
    private $table;
    private $where;
    private $limit=array();
    private $order=array();
    private $group=array();
    private $data=array();

    /**
     * @param string $str
     * @return $this
     */
    public function select($str="*"){
        $this->operation=self::SELECT;
        $this->field=$str;
        return $this;
    }

    /**
     * @return $this
     */
    public function insert(){
        $this->operation=self::INSERT;
        return $this;
    }

    /**
     * @return $this
     */
    public function update(){
        $this->operation=self::UPDATE;
        return $this;
    }

    /**
     * @return $this
     */
    public function delete(){
        $this->operation=self::DELETE;
        return $this;
    }

    /**
     * @param $table_prefix
     * @return $this
     */
    public function setPrefix($table_prefix){
        $this->table_prefix=$table_prefix;
        return $this;
    }
    /**
     * @param $table
     * @return $this
     */
    public function from ($table){
        $this->table=$table;
        return $this;
    }
    /**
     * @param string $str
     * @return $this
     */
    public function where($str=""){
        if($str){
            $this->where=$str;
        }
        return $this;
    }

    /**
     * @param $start
     * @param $len
     * @return $this
     */
    public function limit($start,$len){
        $this->limit=array($start,$len);
        return $this;
    }

    /**
     * @param array $order_list
     * @return $this
     * @internal param $str
     */
    public function order(array $order_list=array()){
        $this->order=$order_list;
        return $this;
    }

    /**
     * @param array $group_list
     * @return $this
     * @internal param $str
     */
    public function group(array $group_list=array()){
        $this->group=$group_list;
        return $this;
    }

    /**
     * 插入、更新的时候使用的值
     * @param array $data 键值对数据
     * @return $this
     */
    public function setData(array $data=array()){
        $this->data=$data;
        return $this;
    }

    /**
     * 添加一个值
     * @param $name
     * @param $value
     */
    public function addData($name,$value){
        $this->data[$name]=$value;
    }

    /**
     * 生成SQL语句
     * @return string
     */
    public function genSQL(){
        $sql="";
        switch($this->operation){
            case self::SELECT:
                // select from table where a=1 and b=2 limit 1,2 order by xxx limit xxx
                $sql="select {$this->field} from ";
                if($this->table_prefix){
                    $sql.=$this->table_prefix;
                }
                $sql.="{$this->table}";
                if($this->where){
                    $sql.=" where {$this->where} ";
                }
                if($this->limit){
                    $sql.=" limit {$this->limit[0]},{$this->limit[1]}";
                }
                if($this->order){
                    $sql.=" order by ";
                    $order_list="";
                    foreach($this->order as $field=>$sort){
                        $order_list=" {$field} {$sort},";
                    }
                    $sql.=rtrim($order_list,",");
                }
                if($this->group){
                    $sql=" group by ";
                    $group_list="";
                    foreach($this->group as $field){
                        $group_list.=" {$field},";
                    }
                    $group_list=rtrim($group_list,",");
                    $sql.=$group_list;
                }
                break;
            case self::INSERT:
                $sql="insert into ";
                if($this->table_prefix){
                    $sql.=$this->table_prefix;
                }
                $sql.="{$this->table}";
                $field_list=$value_list=array();
                foreach($this->data as $field=>$value){
                    array_push($field_list,$field);
                    array_push($value_list,$value);
                }
                $insert_str="( ".join(",",$field_list).") values ('".join("','",$value_list)."')";
                $sql.=$insert_str;
                break;
            case self::UPDATE:
                $sql="update ";
                if($this->table_prefix){
                    $sql.=$this->table_prefix;
                }
                $sql.="{$this->table} set ";

                $set_list="";
                foreach($this->data as $set_key=>$set_value){
                    $set_list.=" {$set_key}='{$set_value}',";
                }
                $set_list=rtrim($set_list,",");
                $sql.=$set_list;
                if($this->where){
                    $sql.=" where {$this->where}";
                }
                break;
            case self::DELETE:
                $sql="delete from  ";
                if($this->table_prefix){
                    $sql.=$this->table_prefix;
                }
                $sql.="{$this->table}";
                if($this->where){
                    $sql.=" where {$this->where }";
                }
                break;
        }
        return $sql;
    }

    /**
     * 生成SQL语句
     * @return string
     */
    public function __toString(){
        return $this->genSQL();
    }
}