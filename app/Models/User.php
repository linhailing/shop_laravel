<?php


namespace App\Models;


class User extends Model{
    public function getUser($page = 10){
        return self::table('users')->paginate($page)->toArray();
    }
    public function getUserHasRoleByUIdAndRId($uid, $rId){
        $count = self::table('user_has_role')->where(['user_id'=>$uid,'role_id'=>$rId])->count();
        return $count ?? false;
    }
    public function delUserHasRoleByUId($uid){
        return self::table('user_has_role')->where(['user_id'=>$uid])->delete();
    }
    public function insertUserHasRole($data){
        return self::table('user_has_role')->insert($data);
    }
    public function getIcons(){
        return self::table('icons')->orderBy('sort', 'desc')->get()->toArray();
    }
    public function getUserById($id){
        return self::table('users')->where(['id'=> $id])->first();
    }
    public function delUserById($id){
        return self::table('users')->where(['id'=> $id])->delete();
    }
    public function getUserByName($name){
        return self::table('users')->where(['name'=>$name])->first();
    }
    public function insertUser($data){
        return self::table('users')->insert($data);
    }
    public function updateUserById($id, $data){
        return self::table('users')->where(['id'=>$id])->update($data);
    }
    public function getRoles($page = 10){
        return self::table('roles')->paginate($page)->toArray();
    }
    public function getRolesNotPage(){
        return self::table('roles')->get()->toArray();
    }
    public function getRoleById($id){
        return self::table('roles')->where(['id'=> $id])->first();
    }
    public function delRoleById($id){
        return self::table('roles')->where(['id'=> $id])->delete();
    }
    public function insertRole($data){
        return self::table('roles')->insert($data);
    }
    public function updateRoleById($id, $data){
        return self::table('roles')->where(['id'=>$id])->update($data);
    }
    public function getPermissions(){
        return self::table('permissions')->orderBy('sort', 'desc')->get();
    }
    public function updatePermissionsById($id, $data){
        return self::table('permissions')->where(['id'=> $id])->update($data);
    }
    public function insertPermissions($data){
        return self::table('permissions')->insert($data);
    }
    public function getPermissionsByPid($pid =0){
        return self::table('permissions')->leftJoin('icons', 'permissions.icon_id', '=', 'icons.id')->select('permissions.*','icons.class','icons.name as cname')->where(['parent_id'=>$pid])->orderBy('sort', 'desc')->paginate(1000)->toArray();
    }
    public function getPermissionsByRoute($routeName){
        $info = self::table('permissions')->where(['route'=>$routeName])->first();
        return $info->id ?? 0;
    }
    public function delPermissionsById($id){
        return self::table('permissions')->where(['id'=>$id])->delete();
    }
    public function getPermissionsByIdArray($ids=[]){
        return self::table('permissions')->leftJoin('icons', 'permissions.icon_id', '=', 'icons.id')->select('permissions.*','icons.class','icons.name as cname')->whereIn('permissions.id',$ids)->orderBy('permissions.sort', 'desc')->get()->toArray();
    }
    public function getPermissionByUid($uid){
        return self::table('user_has_role')->leftJoin('role_has_permissions', 'role_has_permissions.role_id', '=', 'user_has_role.role_id')->select('role_has_permissions.*')->where(['user_has_role.user_id'=>$uid])->pluck('role_has_permissions.permission_id')->toArray();
    }
    public function getRoleHasPermissionsByRoleIdAndPermissionId($roleId, $permissionId){
        $count = self::table('role_has_permissions')->where(['role_id'=>$roleId,'permission_id'=>$permissionId])->count();
        return $count ?? false;
    }
    public function getPermissionsById($id){
        return self::table('permissions')->leftJoin('icons', 'permissions.icon_id', '=', 'icons.id')->select('permissions.*','icons.class','icons.name as cname')->where(['permissions.id'=>$id])->first();
    }
    public function getRolePermissionByRoleIdFilter($roleId){
        return self::table('role_has_permissions')->where(['role_id'=>$roleId])->pluck('permission_id')->toArray();
    }
    public function insertRoleHasPermission($data){
        return self::table('role_has_permissions')->insert($data);
    }
    public function delRoleHasPermission($roleId){
        return self::table('role_has_permissions')->where(['role_id'=>$roleId])->delete();
    }
    public function getImagesCount(){
        return self::table('images')->count();
    }
    public function insertImages($data){
        return self::table('images')->insert($data);
    }
    public function getImagesAll($start, $limit=20){
        return self::table('images')->orderBy('id', 'desc')->offset($start)->limit($limit)->get();
    }
}
