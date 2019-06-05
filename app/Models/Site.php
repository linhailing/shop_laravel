<?php


namespace App\Models;


class Site extends Model{
    public function getSite(){
        return self::table('sites')->pluck( 'value', 'key');
    }
    public function updateSite($data){
        $sql = "replace into sites(`key`,`value`)values(:key,:value)";
        return self::select($sql, [":key"=>$data['key']??'',":value"=>$data['value'] ?? '']);
    }
}
