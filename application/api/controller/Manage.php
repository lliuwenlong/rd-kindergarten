<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\api\controller\Base;
class Manage extends Base {
    public function expenditure(){
        $data = Db::name("money")
//            ->join("organization",'')
//            ->where("p_id = ". $this->garden_id)
            ->where("ie_status = 0")
            ->field("money,cause_id,status,addtime,student_id,class_id,principal,channel")
            ->select();
        dump($data);die;
    }
}