<?php


namespace App\Http\Controllers\Api;


use App\Libs\Util;

class TestController extends ApiController{
    public function index(){
        return $this->json(['ddd','dd']);
    }
    public function token(){
        $token = Util::makePkey('211', time(), '18721186620');
        return $this->json($token);
    }
    public function login(){
        if ($this->checkLogin()) return $this->checkLogin();
        return $this->json(['ddd','dd']);
    }
}
