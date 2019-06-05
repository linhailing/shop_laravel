<?php


namespace App\Http\Controllers\Admin;


use App\Libs\Util;
use App\Models\Model;
use Illuminate\Http\Request;

class SystemController extends Controller{
    public function userData(Request $request){
        $model = $request->get('model');
        $result = [];
        switch (strtolower($model)){
            case 'user':
                $result = Model::User()->getUser($request->get('limit', $this->limit));
                break;
            case 'role':
                $result = Model::User()->getRoles($request->get('limit', $this->limit));
                break;
            case 'permission':
                $result = Model::User()->getPermissionsByPid(intval($request->input('parent_id', 0)));
                break;
            default:
                $result = Model::User()->getUser();
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
    public function roleList(){
        return view('admin.role.index');
    }
    public function userInfo(Request $request){
        $info =  Model::User()->getUserById($this->uid);
        return view('admin.user.info',compact('info'));
    }
    public function userPassword(Request $request){
        $uid = $this->uid;
        if ($request->isMethod('GET')){
            $info =  Model::User()->getUserById($uid);
            return view('admin.user.password',compact('info', 'uid'));
        }
        $data = $request->except(['_token', 'uid']);
        //判断密码是否为空
        if (empty($data['password']) || empty($data['password_confirmation'])) return back()->withErrors(['status'=>'请输入密码或确认密码'])->withInput();
        else if (!empty($data['password']) && !empty($data['password_confirmation'])){
            if ($data['password'] !== $data['password_confirmation']) return back()->withErrors(['status'=>'两次输入密码不相同'])->withInput();
            $data['uuid'] = Util::getUUID();
            $data['password'] = Util::password($data['password'], $data['uuid']);
            unset($data['password_confirmation']);
            Model::User()->updateUserById($uid,$data);
            return back()->with(['status'=> '修改成功']);
        }
        return back()->with(['status'=> '服务器错误']);
    }
    public function userRole(Request $request){
        $id = intval($request->input('id', 0));
        if (empty($id)) return back()->withErrors(['status'=> '用户信息错误']);
        $user = Model::User()->getUserById($id);
        $roles = Model::User()->getRolesNotPage();
        foreach ($roles as $role){
            $role->own = Model::User()->getUserHasRoleByUIdAndRId($id, $role->id) ? true : false;
        }
        return view('admin.user.role', compact('user','roles', 'id'));
    }
    public function userRolesStore(Request $request){
        $uid = intval($request->input('uid', 0));
        if (empty($uid)) return back()->withErrors(['status'=> '参数错误']);
        $rolesId = $request->input('roles', []);
        $insert = [];
        Model::User()->delUserHasRoleByUId($uid);
        foreach ($rolesId as $key=>$item){
            $insert[$key]['user_id'] = $uid;
            $insert[$key]['role_id'] = $item;
        }
        Model::User()->insertUserHasRole($insert);
        return back()->with(['status'=> '操作成功']);
    }
    public function roleStore(Request $request){
        $id = intval($request->input('id', 0));
        $role = [];
        if ($request->isMethod('GET')){
            $role = Model::User()->getRoleById($id);
            return view('admin.role.store',compact('id', 'role'));
        }
        $data = $request->except(['_token','_method', 'id']);
        if (!empty($id)) {
            //修改
            Model::User()->updateRoleById($id,$data);
            return back()->with(['status'=> '修改成功']);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        Model::User()->insertRole($data);
        return back()->with(['status'=> '新增成功']);
    }
    public function rolePermission(Request $request){
        $id = intval($request->input('id', 0));
        $role = Model::User()->getRoleById($id);
        $permissions = $this->tree();
        foreach ($permissions as $key1 => $item1){
            $permissions[$key1]['own'] = Model::User()->getRoleHasPermissionsByRoleIdAndPermissionId($id,($item1['id'])) ? 'checked' : false ;
            if (isset($item1['_child'])){
                foreach ($item1['_child'] as $key2 => $item2){
                    $permissions[$key1]['_child'][$key2]['own'] = Model::User()->getRoleHasPermissionsByRoleIdAndPermissionId($id,$item2['id']) ? 'checked' : false ;
                    if (isset($item2['_child'])){
                        foreach ($item2['_child'] as $key3 => $item3){
                            $permissions[$key1]['_child'][$key2]['_child'][$key3]['own'] = Model::User()->getRoleHasPermissionsByRoleIdAndPermissionId($id,$item3['id']) ? 'checked' : false ;
                        }
                    }
                }
            }

        }
        return view('admin.role.permission',compact('role', 'id', 'permissions'));
    }
    public function rolePermissionStore(Request $request){
        $roleId = intval($request->input('role_id', 0));
        if (empty($roleId)) return back()->withErrors(['status'=> '参数错误']);
        $permissionsId = $request->input('permissions', []);
        //if (empty($permissionsId)) return back()->withErrors(['status'=> '请选择权限']);
        $insert = [];
        Model::User()->delRoleHasPermission($roleId);
        foreach ($permissionsId as $key=>$item){
            $insert[$key]['role_id'] = $roleId;
            $insert[$key]['permission_id'] = $item;
        }
        Model::User()->insertRoleHasPermission($insert);
        return back()->with(['status'=> '操作成功']);
    }
    public function userList(){
        $is_del = $this->isPermissions('admin.user.del');
        $is_edit = $this->isPermissions('admin.user.store');
        return view('admin.user.index',compact('is_del', 'is_edit'));
    }
    public function userDel(Request $request){
        $id = intval($request->input('id', 0));
        if (empty($id)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Model::User()->delUserById($id)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
    public function roleDel(Request $request){
        $id = intval($request->input('id', 0));
        if (empty($id)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Model::User()->delRoleById($id)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
    public function userStore(Request $request){
        $id = intval($request->input('id', 0));
        $title = $id ? '更新用户' : '新增用户';
        $user = [];
        if ($request->isMethod('GET')){
            $user = Model::User()->getUserById($id);
            return view('admin.user.store', ['id'=>$id, 'user'=> $user,'title'=>$title]);
        }
        $data = $request->except(['_token','_method', 'id']);
        if (!empty($id)) {
            //修改
            //判断密码是否为空
            if (!empty($data['password'])){
                if ($data['password'] !== $data['password_confirmation']) return back()->withErrors(['status'=>'两次输入密码不相同'])->withInput();
                $data['uuid'] = Util::getUUID();
                $data['password'] = Util::password($data['password'], $data['uuid']);
                unset($data['password_confirmation']);
            }else{
                unset($data['password_confirmation']);
                unset($data['password']);
            }
            Model::User()->updateUserById($id,$data);
            return back()->with(['status'=> '修改成功']);
        }
        if (empty($data['password']) || empty($data['password_confirmation'])) return back()->withErrors(['status'=>'请输入密码'])->withInput();
        if ($data['password'] !== $data['password_confirmation']) return back()->withErrors(['status'=>'两次输入密码不相同'])->withInput();
        $data['uuid'] = Util::getUUID();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['password'] = Util::password($data['password'], $data['uuid']);
        unset($data['password_confirmation']);
        Model::User()->insertUser($data);
        return back()->with(['status'=> '新增成功']);
    }
    public function permissions(){
        return view('admin.permission.index');
    }
    public function permissionDel(Request $request){
        $id = intval($request->input('id', 0));
        if (empty($id)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Model::User()->delPermissionsById($id)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
    public function permissionStore(Request $request){
        $id = intval($request->input('id', 0));
        $title = $id ? '更新权限' : '新增权限';
        $info = [];
        $permissions = $this->tree();
        if ($request->isMethod('GET')){
            $info = Model::User()->getPermissionsById($id);
            return view('admin.permission.store', compact('id', 'title', 'info','permissions'));
        }
        $data = $request->except(['_token','_method', 'id']);
        if (!empty($id)) {
            //修改
            Model::User()->updatePermissionsById($id,$data);
            return back()->with(['status'=> '修改成功']);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        Model::User()->insertPermissions($data);
        return back()->with(['status'=> '新增成功']);
    }
}
