<?php


namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;

class SiteController extends Controller{
    public function index(){
        $config = Model::Site()->getSite();
        return view('admin.site.index',compact('config'));
    }
    public function store(Request $request){
        $data = $request->except(['_token','_method']);
        if (empty($data)) return back()->withCookie(['status' => '无数据更新']);
        Model::Site()->clearTable('sites');
        foreach ($data as $key => $item) {
            Model::Site()->updateSite(['key'=>$key,'value'=>$item]);
        }
        return back()->with(['status' => '更新成功']);
    }
}
