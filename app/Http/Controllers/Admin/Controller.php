<?php


namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Controller extends \App\Http\Controllers\Controller{
    public $uid;
    public $routeIds = [];
    public $limit = 30;
    public function __construct(Request $request){
        @define('TIMESTAMP', time());
        @define('DATETIME', date('Y-m-d H:i:s', TIMESTAMP));
        try{
            $login_data  = Crypt::decryptString($request->cookie(LOGINTOKEN));
            $login_list = explode('#', $login_data);
            $this->uid = end($login_list);
            $GLOBALS['uid'] = $this->uid;
        }catch(\Exception $e) {
            $this->uid = 0;
            $GLOBALS['uid'] = 0;
            return redirect('/admin/login');
        }
        $this->getRoleIds();
    }
    public function getRoleIds(){
        $this->routeIds = Model::User()->getPermissionByUid($this->uid) ?? [];
        return $this->routeIds;
    }
    public function isPermissions($routeName){
        if (empty($routeName)) return false;
        $id = Model::User()->getPermissionsByRoute($routeName);
        if (empty($id)) return false;
        return in_array($id, $this->routeIds);
    }
    public function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    public function menusTree($list=[], $pk='id', $pid = 'parent_id', $child = '_child', $root = 0)
    {
        if (empty($list)){
            return [];
        }
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
    /**
     * 处理权限分类
     */
    public function tree($list=[], $pk='id', $pid = 'parent_id', $child = '_child', $root = 0)
    {
        if (empty($list)){
            $list = Model::User()->getPermissions()->toArray();
            $list = $this->object_array($list);
        }
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
