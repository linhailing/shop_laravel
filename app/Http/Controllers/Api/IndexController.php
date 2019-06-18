<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/6/18
 * Time: 1:49 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\Api\BannerResource;
use App\Models\Model;

class IndexController extends ApiController{
    public function index(){
        $banner = $this->getBannerById(1);
        $theme = $this->getThemes();
        $products = BannerResource::getProducts(Model::Product()->getProducts());
        return $this->json(['banner'=>$banner,'theme'=>$theme,'products'=>$products]);
    }
    private function getBannerById($id){
        return BannerResource::getBanners(Model::Banner()->getBannerById($id));
    }
    private function getThemes(){
        return BannerResource::getThemes(Model::Banner()->getThemes());
    }
}