<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/6/18
 * Time: 1:57 PM
 */

namespace App\Models;


class Banner extends Model{
    public function getBannerById($id){
        return self::table('banner')->where(['banner.id'=>$id])
            ->leftJoin('banner_item', 'banner.id', '=', 'banner_item.banner_id')
            ->leftJoin('image', 'banner_item.img_id','=','image.id')
            ->get();
    }
    public function getThemes(){
        return self::table('theme')
            ->leftJoin('image','theme.topic_img_id','=','image.id')
            ->get();
    }
}