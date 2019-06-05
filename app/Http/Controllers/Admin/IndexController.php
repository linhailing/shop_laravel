<?php


namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;

class IndexController extends Controller {
    public function getData(Request $request){
        $model = $request->input('model', '');
        $result = [];
        switch (strtolower($model)){
            case 'search_log':
                $result = $this->returnSearchLogData(intval($request->get('limit', $this->limit)));
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
    public function index(){
        $ids = $this->routeIds;
        $info = Model::User()->getPermissionsByIdArray($ids);
        $menus = $this->menusTree($this->object_array($info));
        $user = Model::User()->getUserById($this->uid);
        return view('admin.index.index', compact('menus', 'user'));
    }
    private function returnSearchLogData($limit){
        $result = Model::Log()->getSearchLog (intval($limit));
        $list = ['data'=>[], 'total'=>0];
        if (!$result) return $list;
        $data = [];
        foreach ($result['data'] as $key=>$item){
            $data[$key]['create_time'] = ($item->create_time && $item->create_time) ? date('Y-m-d H:i:s', $item->create_time)  : '';
            $data[$key]['id'] = $item->id;
            $data[$key]['uid'] = $item->uid;
            $data[$key]['search_count'] = $item->search_count;
            $data[$key]['search_name'] = $item->search_name;
        }
        $list['data'] = $data;
        $list['total'] = $result['total'];
        return $list;
    }
}
