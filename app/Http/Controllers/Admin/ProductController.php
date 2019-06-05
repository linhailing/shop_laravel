<?php


namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;

class ProductController extends Controller{
    public function getData(Request $request){
        $model = $request->get('model');
        switch (strtolower($model)){
            case 'product':
                $result = $this->getHandleData($request->get('q', ''),$request->get('limit', $this->limit));
                break;
            default:
                $result = $this->getHandleData($request->get('q', ''),$request->get('limit', $this->limit));
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
        $page = 0;
        return view('admin.product.product', compact('page'));
    }
    public function productStore(Request $request){
        $page = $request->get('page', 1);
        $id = $request->get('id', 0);
        if (!$this->isPermissions('admin.product.store')) return back()->withErrors(['status'=>'您无权限删除，请联系管理员']);
        if ($request->isMethod('GET')){
            $info = Model::Product()->getProductById($id);
            if ($info && !empty($info->pics_url)) $info->pics_url = explode(";", $info->pics_url);
            return view('admin.product.product_store', compact('info', 'id', 'page'));
        }
        $data = $request->except(['_token','file']);
        if (isset($data['pics_url'])) $data['pics_url'] = implode(';', $data['pics_url']);
        if (isset($data['id'])) unset($data['id']);
        if (!empty($id)){
            Model::Product()->updateProduct($id, $data);
            return back()->with(['status'=>'修改成功']);
        }
        Model::Product()->insertProduct($data);
        return back()->with(['status'=>'新增成功']);
    }
    public function productDel(Request $request){
        $id = intval($request->input('id', 0));
        if( !$this->isPermissions('admin.product.del')) return response()->json(['code'=>1,'msg'=>'您无权限删除，请联系管理员']);
        if (empty($id)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Model::Product()->delProduct($id)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
    public function category(){
        return [];
    }
    public function categoryStore(){
        return [];
    }
    public function categoryDel(){
        return [];
    }
    private function getHandleData($q, $limit){
        $result = Model::Product()->getProductParams($q, $limit);
        $list = ['data'=>[], 'total'=>0];
        if (!$result) return $list;
        $data = [];
        foreach ($result['data'] as $key=>$item){
            $data[$key]['id'] = $item->product_id;
            $data[$key]['product_name'] = $item->product_name;
            $data[$key]['price'] = $item->price;
            $data[$key]['icon'] = ($item && $item->pic_icon) ? '<img style="width:50px;height:50px" src="'.$item->pic_icon.'">' : '';
            $data[$key]['stock'] = $item->stock;
        }
        $list['data'] = $data;
        $list['total'] = $result['total'];
        return $list;
    }
}
