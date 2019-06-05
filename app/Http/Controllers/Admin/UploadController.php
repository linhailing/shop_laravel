<?php


namespace App\Http\Controllers\Admin;


use App\Libs\Util;
use App\Models\Model;
use Illuminate\Http\Request;

class UploadController extends Controller {
    public function uploadImage(){
        $file = isset($_FILES['image']) ? @$_FILES['image'] : @$_FILES['file'];
        if (!empty($file) && $file['name'] && $file['error'] == 0 && (isset($GLOBALS['upload_mime'][$file['type']]))) {
            $path = '/images/'.date('Ymd')."/";
            $url = $path. Util::getUploadImageName() .'.'.pathinfo($file['name'])['extension'];
            if (!is_dir(RESOURCEPATH.$path)){
                if(!mkdir(RESOURCEPATH.$path, 0777)){
                    return response()->json(['msg'=>'目录创建失败','code'=>1]);
                }
            };
            if (!move_uploaded_file($file['tmp_name'], RESOURCEPATH.$url))  return 0;
            return  response()->json(['url'=> UPLOADS."/Uploads".$url,'code'=>0]);
        }
        return response()->json(['msg'=>'图片上传失败','code'=>1]);
    }
    public function uploadVideos(){
        $file = isset($_FILES['video']) ? @$_FILES['video'] : @$_FILES['file'];
        if (empty($file)) return response()->json(['msg'=>'请选择视频','code'=>1]);
        if(!in_array($file['type'], $GLOBALS['upload_video'])) return response()->json(['msg'=>'请选择正确的视频','code'=>1]);
        if (!empty($file) && $file['name'] && $file['error'] == 0) {
            $path = '/videos/'.date('Ymd')."/";
            $url = $path.Util::getUploadImageName().'.'.pathinfo($file['name'])['extension'];
            if (!is_dir(RESOURCEPATH.$path)){
                if(!mkdir(RESOURCEPATH.$path, 0777)){
                    return response()->json(['msg'=>'目录创建失败','code'=>1]);
                }
            };
            if (!move_uploaded_file($file['tmp_name'], RESOURCEPATH.$url))  return 0;
            return  response()->json(['url'=> UPLOADS."/Uploads".$url,'code'=>0]);
        }
        return response()->json(['msg'=>'视频上传失败','code'=>1]);
    }
    public function ueditor(Request $request){
        $action = $request->input('action');
        $root_path = BASEURI.'static/plugin/ueditor/';
        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($root_path."upload_config.json")), true);
        switch ($action){
            case 'config':
                return json_encode($config);
                break;
            case 'uploadImage':
                return $this->editUpload();
                break;
            case 'listImage':
                return $this->getListImage($request);
                break;
        }
    }
    private function editUpload(){
        $resp = ['state'=>'SUCCESS', 'url'=>'', 'title'=>'', 'original'=>'','code'=>200];
        $file = isset($_FILES['image']) ? @$_FILES['image'] : @$_FILES['upfile'];
        if (!empty($file) && $file['name'] && $file['error'] == 0 && (isset($GLOBALS['upload_mime'][$file['type']]))) {
            $path = '/images/'.date('Ymd')."/";
            $url = $path. Util::getUploadImageName() .'.'.pathinfo($file['name'])['extension'];
            if (!is_dir(RESOURCEPATH.$path)){
                if(!mkdir(RESOURCEPATH.$path, 0777)){
                    $resp['state'] = '目录创建失败';
                    $resp['code'] = 404;
                    return response()->json($resp);
                }
            };
            if (!move_uploaded_file($file['tmp_name'], RESOURCEPATH.$url))  return 0;
            $resp['url'] = UPLOADS."/Uploads".$url;
            $resp['code'] = 200;
            Model::User()->insertImages(['file_key'=>$resp['url'],'created_time'=>DATETIME]);
            return  response()->json($resp);
        }
        $resp['state'] = '图片上传失败';
        $resp['code'] = 404;
        return response()->json($resp);
    }
    private function getListImage($request){
        $resp = ['state'=>'SUCCESS', 'list'=> [], 'start'=> 0, 'total'=> 0 ];
        $start = intval($request->input('start', 0));
        $size = intval($request->input('size', 20));
        $total = Model::User()->getImagesCount();
        $resp['total'] = $total;
        $res = Model::User()->getImagesAll($start, $size);
        $images = [];
        if (empty($res)) {
            $resp['list'] = $images;
            return response()->json($resp);
        }
        foreach ($res as $item) {
            $temp = [];
            $temp['url'] = $item->file_key;
            array_push($images, $temp);
        }
        $resp['start'] = $start;
        $resp['list'] = $images;
        return response()->json($resp);
    }
}
