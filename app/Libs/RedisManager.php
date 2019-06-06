<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/4/12
 * Time: 2:55 PM
 */

namespace App\Libs;

use App\Models\Model;
use Illuminate\Support\Facades\Redis;

class RedisManager{
    private static $year = 86400*7; //七天
    private static $day = 86400; //一天
    public static function cache($func, $params = [], $seconds = 3600) {
        $key = self::cache_param($func, $params);
        $value = self::CacheInstanceGet($key);
        if (!$value) {
            $value = call_user_func_array($func, $params);
            self::CacheInstanceSet($key, $value, $seconds);
        }
        return $value;
    }
    private static function cache_param($func, $params) {
        $key = '';
        if (is_string($func)) {
            $key .= $func;
        } else if (is_array($func)) {
            foreach ($func as $info) {
                if (is_object($info)) $key .= get_class($info);
                if (is_string($info)) $key .= '_'.$info;
            }
        }
        $key = $key.'_'.implode('_', array_values($params));
        $key = last(explode('\\', $key));
        return $key;
    }
    //锁
    public static function cacheLockStart($key, $seconds = 10) {
        $value = self::CacheInstanceGet($key, null);
        if ($value) return false;
        self::CacheInstanceSet($key, 1, $seconds);
        return true;
    }
    public static function cacheLockEnd($key) {
        self::CacheInstanceDelete($key);
    }
    //CACHE
    private static function CacheInstanceGet($key, $def = null) {
        if (!Redis::get($key)) return $def;
        return json_decode(Redis::get($key));
    }
    private static function CacheInstanceSet($key, $value, $lifetime = null) {
        //if (is_array($value)) $value = json_encode($value, true);
        $value = json_encode($value, true);
        return Redis::setex($key, $lifetime, $value);
    }
    private static function CacheInstanceDelete($key) {
        return Redis::del($key);
    }
    public static function clearAll(){
        return Redis::flushAll();
    }
    public static function cache_delete($func, $params = []) {
        return self::CacheInstanceDelete(self::cache_param($func, $params));
    }
    //缓存用户信息
    public static function getMemberInfo($uid = null){
        $data = self::cache([Model::Member(), 'getMemberInfo'],[], self::$year);
        if (!empty($uid)){
            foreach ($data as $item) {
                if ($uid == $item->id){
                    return $item;
                }
            }
        }
        return $data;
    }
    public static function delMemberInfo(){
        return self::cache_delete([Model::Member(), 'getMemberInfo'],[]);
    }
    //缓存加盟点评信息
    public static function getReviews($id = 0, $is_hot = false, $cid= null, $good = false, $other=null, $q =''){
        //dd($id);
        $list = self::cache([Model::Review(), 'getReviews'], [$is_hot,$cid,$good,$other], self::$year);
        $result_item = [];
        if (!empty($id)){
            foreach ($list as $item) {
                if ($item->id == $id){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($id)) return $result_item ? $result_item : [];
        if (!empty($q)){
            foreach ($list as $item) {
                if (strpos($item->name, $q) !== false){
                    array_push($result_item, $item);
                }
            }
        }
        if (!empty($q)) return $result_item ? $result_item : [];
        return $list;
    }
    public static function delReviews(){
        return self::cache_delete([Model::Review(), 'getReviews'], []);
    }
    //缓存导师信息
    public static function getTutors($id = 0,$isHot=null, $cid = null, $year= null){
        $list = self::cache([Model::Tutor(), 'getTutorList'], [$isHot, $cid, $year], self::$year);
        $result = [];
        foreach ($list as $item) {
            $result[$item->id] = $item;
        }
        if (!empty($id)){
            if (isset($result[$id])) return $result[$id];
            else return [];
        }
        return $result;
    }
    public static function delTutors($isHot=null, $cid = null, $year= null){
        return self::cache_delete([Model::Tutor(), 'getTutorList'], [$isHot, $cid, $year]);
    }
    //缓存帖子信息
    public static function getPosts($id = null, $tid = null){
        $list = self::cache([Model::Posts(), 'getPosts'], [$tid], self::$year);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        if (!empty($id)){
            if (isset($result[$id])) return $result[$id];
            else return [];
        }
        return $result;
    }
    public static function  delPosts($tid = null){
        return self::cache_delete([Model::Posts(), 'getPosts'], [$tid]);
    }
    //缓存帖子评论信息
    public static function getPostComments($id){
        $list = self::cache([Model::Comment(), 'getPostComment'], [$id], self::$year);
        return $list;
    }
    public static function delPostComments($id){
        return self::cache_delete([Model::Comment(), 'getPostComment'], [$id]);
    }
    //缓存首页数据
    public static function getHomeIndex($cid=0, $kind=null){
        $list = self::cache([Model::Branch(), 'getBrandByCid'],[$cid, $kind], self::$year);
        return $list;
    }
    public static function delHomeIndex($cid=0, $kind=null){
        return self::cache_delete([Model::Branch(), 'getBrandByCid'],[$cid, $kind]);
    }
    //严选缓存
    public static function getBrands($id =0, $cid=0, $is_hot=0,$good=0, $other=null,$q=''){
        $list = self::cache([Model::Branch(), 'getBrandByParams'], [$cid, $is_hot,$good, $other],self::$year);
        $result_item = [];
        if (!empty($id)){
            foreach ($list as $item) {
                if ($item->id == $id){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($id)) return $result_item ? $result_item : [];
        if (!empty($q)){
            foreach ($list as $item) {
                if (strpos($item->name, $q) !== false){
                    array_push($result_item, $item);
                }
            }
        }
        if (!empty($q)) return $result_item ? $result_item : [];
        return $list;
    }


    //缓存个人帖子信息
    public static function getUserPosts($uid = null){
        $list = self::cache([Model::Member(), 'getUserPost'], [$uid], self::$day);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        return $result;
    }
    public static function delUserPosts($uid){
        return self::cache_delete([Model::Member(), 'getUserPost'], [$uid]);
    }
    //缓存个人收藏
    public static function getCollection($uid,$kind=null){
        $list = self::cache([Model::Member(), 'getCollection'], [$uid], self::$day);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        if (!empty($kind)){
            $res=[];
            foreach ($list as $item){
                if($item->kind==$kind)
                $res[$item->id] = $item;
            }
            if (isset($res)) return $res;
            else return [];
        }
        return $result;
    }
    //缓存个人点赞
    public static function getPraise($uid,$kind=null){
        $list = self::cache([Model::Member(), 'getMyPraise'], [$uid], self::$day);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        if (!empty($kind)){
            $res=[];
            foreach ($list as $item){
                if($item->kind==$kind)
                $res[$item->id] = $item;
            }
            if (isset($res)) return $res;
            else return [];
        }
        return $result;
    }
    //获取点评数据
    public static function getBrandCommentsById($id, $uId=0,$is_hot=0){
        $list = self::cache([Model::Branch(), 'getBrandComments'],[$id,$is_hot], self::$year);
        $result_item = [];
        if (!empty($uId)){
            foreach ($list as $item) {
                if ($item->id == $uId){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($uId)) return $result_item ? $result_item : [];
        return $list;
    }
    public static function delBrandCommentsById($id){
        return self::cache_delete([Model::Branch(), 'getBrandComments'],[$id]);
    }
    //缓存严选评论
    public static function getBrandDiscussById($id){
        $list = self::cache([Model::Comment(), 'getBrandDiscuss'],[$id], self::$year);
        return $list;
    }
    //缓存微信access_token
    public static function getAccessToken($key){
        return self::CacheInstanceGet($key);
    }
    public static function setAccessToken($key, $value, $seconds){
        return self::CacheInstanceSet($key, $value, $seconds);
    }

    //缓存个人足迹
    public static function getFootprint($uid){
        $list = self::cache([Model::Member(), 'getFootprint'], [$uid], 3600);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        return $result;
    }
    public static function delFootprint($uid){
        return self::cache_delete([Model::Member(), 'getFootprint'], [$uid]);
    }
    //缓存个人积分
    public static function getScore($uid){
        $list = self::cache([Model::Member(), 'getScore'], [$uid], self::$day);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        return $result;
    }
    public static function delScore($uid){
        return self::cache_delete([Model::Member(), 'getScore'], [$uid]);
    }
    //缓存投诉
    public static function getComplaints($id=0, $is_hot = 0, $status=0,$q=''){
        $list = self::cache([Model::Complaint(), 'getComplaints'],[$is_hot,$status], self::$year);
        $result_item = [];
        if (!empty($id)){
            foreach ($list as $item) {
                if ($item->id == $id){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($id)) return $result_item ? $result_item : [];
        if (!empty($q)){
            foreach ($list as $item) {
                if ((strpos($item->title, $q) !== false) || (strpos($item->name, $q) !== false) || (strpos($item->cause, $q) !== false)){
                    array_push($result_item, $item);
                }
            }
        }
        if (!empty($q)) return $result_item ? $result_item : [];
        return $list;
    }
    //投诉评论
    public static function getComplaintsDiscuss($id){
        $list = self::cache([Model::Comment(), 'getComplaintDiscuss'], [$id], self::$year);
        return $list;
    }
    //缓存我的点评
    public static function getComment($uid){
        $list = self::cache([Model::Member(), 'getComment'], [$uid], self::$day);
        $result = [];
        foreach ($list as $item){
            $result[$item->id] = $item;
        }
        return $result;
    }
    //缓存公司信息
    public static function getCompanyBase($id=0){
        $list = self::cache([Model::Member(), 'getCompanyBase'],[], self::$year);
        $result_item = [];
        if (!empty($id)){
            foreach ($list as $item) {
                if ($item->pid == $id){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($id)) return $result_item ? $result_item : [];
        return $list;
    }  
    //缓存商标信息
    public static function getcompanyMark($id=0){
        $list = self::cache([Model::Member(), 'getcompanyMark'],[], self::$year);
        $result_item = [];
        if (!empty($id)){
            foreach ($list as $item) {
                if ($item->pid == $id){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($id)) return $result_item ? $result_item : [];
        return $list;
    }   
    //缓存特许经营
    public static function getFranchiseQuery($id=0){
        $list = self::cache([Model::Member(), 'getFranchiseQuery'],[], self::$year);
        $result_item = [];
        if (!empty($id)){
            foreach ($list as $item) {
                if ($item->pid == $id){
                    $result_item = $item;
                    break;
                }
            }
        }
        if (!empty($id)) return $result_item ? $result_item : [];
        return $list;
    }
    //缓存banner图
    public static function getAllImg(){
        $list = self::cache([Model::Member(), 'getAllImg'], [], self::$year);
        return $list;
    }
    public static function delAllImg(){
        return self::cache_delete([Model::Comment(), 'getAllImg'], []);
    }
}
