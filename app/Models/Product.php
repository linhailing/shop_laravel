<?php


namespace App\Models;


class Product extends Model{
    public function getProductParams($q, $limit){
        $query = self::table('products');
        if (!empty($q)){
            $query->where('product_name', 'like' , '%'.$q.'%');
            $query->orWhere('product_core', 'like' , '%'.$q.'%');
        }
        return $query->orderBy('product_id', 'desc')->paginate($limit)->toArray();
    }
    public function insertProduct($data){
        return self::table('products')->insertGetId($data);
    }
    public function updateProduct($id,$data){
        return self::table('products')->where(['product_id'=>$id])->update($data);
    }
    public function getProductById($id){
        return self::table('products')->where(['product_id'=>$id])->first();
    }
    public function delProduct($id){
        return self::table('products')->where(['product_id'=>$id])->delete();
    }
    public function getProducts(){
        return self::table('product')->orderBy('id', 'desc')->get()->toArray();
    }
}
