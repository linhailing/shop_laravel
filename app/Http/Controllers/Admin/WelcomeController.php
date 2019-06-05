<?php


namespace App\Http\Controllers\Admin;


use App\Libs\Util;
use App\Models\Model;

class WelcomeController extends Controller{
    public function index(){
//        $data = Util::getLateOfDay();
//        $days = Model::Report()->getLateOfDay($data);
//        $newDays = [];
//        $reportDay = [];
//        if (!empty($data)){
//            foreach ($days as $day) {
//                $newDays[$day->day] = $day->reg_count;
//            }
//        }
//        if (!empty($newDays)){
//            foreach ($data as $item) {
//                if (isset($newDays[$item])) $reportDay[$item] = $newDays[$item];
//                else $reportDay[$item] = 0;
//            }
//        }
        $reportDay = [];
        $yTotal = 0;
        $pTotal = 0;
        $member = 0;
        $lateDay = 0;
        return view('admin.welcome.index',compact('reportDay','yTotal','pTotal','member','lateDay'));
    }
}
