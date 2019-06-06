<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\Util;

class ApiController extends Controller {
    protected $token = '';
    protected $uid = 0;
    protected $income = null;
    protected $pageSize = 10;
    use ApiResponse;
    public function __construct(){
        @define('TIMESTAMP', time());
        @define('DATETIME', date('Y-m-d H:i:s', TIMESTAMP));
        //token
        $this->token = $this->req('token');
        if (!empty($this->token)) $this->income = Util::up_decode($this->token);
        if (!empty($this->income)) $this->uid = isset($this->income['u']) ? $this->income['u'] : 0;
    }
    // 检查是否登录
    public function checkLogin(){
        if (!$this->income || $this->uid < 1){
            return $this->error('非法的请求或登录已经失效，请重新登录',422);
        }
    }
    protected function req($key, $val = null){
        if (isset($_REQUEST[$key])) return Util::saddslashes(@$_REQUEST[$key]);
        return $val;
    }
    public function paramsErr($msg='参数错误',$code = 404){
        $result = [
            'status' => 'error',
            'code' => $code,
            'msg' =>  $msg
        ];
        return $this->status($msg, $result, $code);
    }
    public function json($data, $msg = 'success', $code = 200){
        $result = [
            'status' => $msg,
            'code' => $code,
            'results' => $data
        ];
        return $this->status($msg, $result, $code);
    }
    public function error($msg, $code = 404){
        $result = [
            'status' => 'error',
            'code' => $code,
            'msg' =>  $msg
        ];
        return $this->status($msg, $result, $code);
    }
    public function pagination($data){
        $total = intval(count($data));
        $page = intval($this->req('page', 1));
        if (empty($page)) $page = 1;
        $start= ($page - 1 ) * $this->pageSize; #计算每次分页的开始位置
        $size = intval(ceil(count($data) / $this->pageSize));
        $temp = array_slice($data , $start, $this->pageSize);
        return ['size' => $size, 'currentPage' => $page -1, 'total'=> $total, 'list' => $temp];
    }
}
