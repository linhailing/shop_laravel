<?php


namespace App\Models;


class Log extends Model{
    public function insertAdminLog($data){
        return self::table('admin_log')->insert($data);
    }
    public function insertSearchLog($data){
        return self::table('admin_log')->insert($data);
    }
    public function getAdminLogs($q, $limit){
        $query = self::table('admin_log');
        $query->leftJoin('users', 'users.id', '=', 'admin_log.uid');
        $query->select('admin_log.*', 'users.username');
        if (!empty($q)){
            $query->where('admin_log.uid', 'like' , '%'.$q.'%');
            $query->orWhere('admin_log.path', 'like' , '%'.$q.'%');
            $query->orWhere('admin_log.method', 'like' , '%'.$q.'%');
            $query->orWhere('admin_log.ip', 'like' , '%'.$q.'%');
        }
        return $query->orderBy('admin_log.id','desc')->paginate($limit)->toArray();
    }
    public function clearAdminLog(){
        return self::table('admin_log')->truncate();
    }
    public function getSearchLog($limit){
        return self::table('search_log')
            ->select(self::raw('count(id) as search_count,id,search_name,uid,create_time'))
            ->orderBy('id', 'desc')
            ->groupBy('search_name', 'id')
            ->paginate($limit)->toArray();
    }
}
