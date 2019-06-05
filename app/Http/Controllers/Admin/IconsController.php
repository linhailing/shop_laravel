<?php


namespace App\Http\Controllers\Admin;


use App\Models\Model;

class IconsController extends Controller {
    public function index(){
        $icons = Model::User()->getIcons();
        return response()->json(['code' => 0, 'msg' => '请求成功', 'data' => $icons]);
    }
}
