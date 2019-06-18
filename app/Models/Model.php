<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/4/12
 * Time: 10:07 AM
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class Model extends DB{
    public function insertFields($data) {
        return '(`'.implode('`,`', array_keys($data)).'`) values(:'.implode(',:', array_keys($data)).')';
    }
    public function insertAndUpdateFields(&$data, $data2 = null) {
        $sql = $this->insertFields($data);
        if (empty($data2)) $data2 = $data;
        $sql .= ' on duplicate key update '.$this->set2($data, $data2);
        return $sql;
    }
    public function set($data) {
        $sql = array();
        foreach ($data as $key=>$value) {
            $sql[] = sprintf('`%s`=:%s', $key, $key);
        }
        return implode(',', $sql);
    }
    public function fields($data) {
        return '`'.implode('`,`', array_keys($data)).'`';
    }
    public function values($data) {
        $sql = array();
        foreach ($data as $key=>$value) {
            $sql[] = ':'.$key;
        }
        return implode(',', $sql);
    }
    private function _toString($value) {
        if ($value === null) {
            return 'NULL';
        } elseif ($value === true) {
            return '1';
        } elseif ($value === false) {
            return '0';
        } else {
            return addslashes($value);
        }
    }
    public function clearTable($tableName){
        return self::table($tableName)->truncate();
    }
    public function combinateSql($data) {
        $insertValue = '';
        foreach ($data as $value) {
            if ($insertValue)	$insertValue .= ",(".$this->values2($value).")";
            else				$insertValue .= "values(".$this->values2($value).")";
        }
        $result['insertKey'] 	= $data[0];
        $result['insertValue'] 	= $insertValue;
        return $result;
    }
    public function getCount($table, $field='id'){
        $sql = "select count({$field}) as total from {$table} ";
        $res = self::selectOne($sql);
        return $res ? $res->total : 0;
    }

    //网站设置
    private static $site;
    public static function Site(){
        if (!self::$site) self::$site = new Site();
        return self::$site;
    }
    //后台管理员
    private static $user;
    public static function User(){
        if (!self::$user) self::$user = new User();
        return self::$user;
    }
    //管理员操作日志
    private static $log;
    public static function Log(){
        if (!self::$log) self::$log = new Log();
        return self::$log;
    }
    //商品信息
    private static $banner;
    public static function Banner(){
        if (!self::$banner) self::$banner = new Banner();
        return self::$banner;
    }
    //商品信息
    private static $product;
    public static function Product(){
        if (!self::$product) self::$product = new Product();
        return self::$product;
    }
}
