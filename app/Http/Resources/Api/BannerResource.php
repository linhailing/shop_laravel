<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/6/18
 * Time: 2:13 PM
 */

namespace App\Http\Resources\Api;


class BannerResource{
    public static function getBanners($data){
        $ret = [];
        if (!$data) return $ret;
        foreach ($data as $key=>$item) {
            $ret[$key]['id'] = $item->id;
            $ret[$key]['name'] = $item->name;
            $ret[$key]['key_word'] = $item->key_word;
            $ret[$key]['url'] = (strripos($item->url,'http') === false) ? BASEURI.'images'.$item->url : $item->url;
            $ret[$key]['from'] = $item->from;
        }
        return $ret;
    }
    public static function getThemes($data){
        $ret = [];
        if (!$data) return $ret;
        foreach ($data as $key=>$item) {
            $ret[$key]['id'] = $item->id;
            $ret[$key]['name'] = $item->name;
            $ret[$key]['url'] = (strripos($item->url,'http') === false) ? BASEURI.'images'.$item->url : $item->url;
            $ret[$key]['from'] = $item->from;
        }
        return $ret;
    }
    public static function getProducts($data){
        $ret = [];
        if (!$data) return $ret;
        foreach ($data as $key=>$item) {
            $ret[$key]['id'] = $item->id;
            $ret[$key]['name'] = $item->name;
            $ret[$key]['main_img_url'] = (strripos($item->main_img_url,'http') === false) ? BASEURI.'images'.$item->main_img_url : $item->main_img_url;
            $ret[$key]['from'] = $item->from;
            $ret[$key]['price'] = floatval($item->price);
            $ret[$key]['stock'] = $item->stock;
        }
        return $ret;
    }
}