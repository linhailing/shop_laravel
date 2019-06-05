<?php


namespace App\Http\Controllers\Admin;


use App\Libs\Util;
use App\Models\Model;
use Illuminate\Http\Request;

class LoginController extends Controller {
    public function index(){
        return view('admin.login.login');
    }
    public function store(Request $request){
        $data = $request->except(['_token']);
        if (empty($data) || empty($data['username'])  || empty($data['password'])) return back()->withErrors(['status'=>'用户名或密码不能为空'])->withInput();
        $user = Model::User()->getUserByName($data['username']);
        if (empty($user)) return back()->withErrors(['status'=>'用户名或密码不能为空'])->withInput();
        if (Util::password($data['password'], $user->uuid) !== $user->password) return back()->withErrors(['status'=>'用户名或密码不能为空'])->withInput();
        $token = Util::getLoginToken($request->getClientIp(), $user);
        //session()->put('uid', $user->id);
        //session()->put('userInfo', $user);
        //dd(session()->all());
        return response()->redirectTo('/admin')->withCookie(LOGINTOKEN, $token);
    }
    public function logout(){
        return response()->redirectTo('/admin/login')->withCookie(LOGINTOKEN, '');
    }
}
