<?php


namespace App\Http\Controllers\Admin;


use App\Libs\RedisManager;
use App\Models\Model;
use Illuminate\Http\Request;

class CacheController extends Controller{
    public function getData(Request $request){
        $model = $request->get('model');
        $result = [];
        switch (strtolower($model)){
            case 'logs':
                $result = $this->handleData($request->get('q', ''), $request->get('limit', $this->limit));
                break;
            case 'company':
                $result = Model::Company()->getCompanyBasicByParams($request->get('q', ''),$request->get('limit', $this->limit));
                break;
        }
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $result['total']??0,
            'data' => $result['data']??[]
        ];
        return response()->json($data);
    }
    public function index(Request $request){
        if (!$this->isPermissions('admin.cache')) return back()->withErrors(['status'=>'您无权限删除，请联系管理员']);
        if ($request->isMethod('GET')) return view('admin.cache.index');
        RedisManager::clearAll();
        return back()->with(['status'=>'缓存清除成功']);
    }
    public function logs(){
        return view('admin.cache.logs');
    }
    public function logDel(){
        if (!$this->isPermissions('admin.cache.logs.del')) return back()->withErrors(['status'=>'您无权限删除，请联系管理员']);
        Model::Log()->clearAdminLog();
        return response()->json(['code'=>0,'msg'=>'清空成功']);
    }
    private function handleData($q, $limt){
        $result = Model::Log()->getAdminLogs($q, $limt);
        if (empty($result) && empty($result['data'])) return ['total'=>0,'data'=>[]];
        foreach ($result['data'] as &$item) {
            if (!empty($item) && $item->create_time) $item->create_time = date('Y-m-d H:i:s', $item->create_time);
        }
        return $result;
    }
}
