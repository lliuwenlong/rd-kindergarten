<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
class Spare extends Controller{
    public function index(){
        $list  = DB::name("money")
            ->join('organization a','tp_money.class_id = a.id','left')
            ->join('organization b','tp_money.garden_id = b.id','left')
            ->join("organization c",'tp_money.ji_id = c.id','left')
            ->join("cause",'tp_money.cause_id = cause.id','left')
            ->join("student",'tp_money.student_id = student.id','left')
            ->join("garden_spare s","tp_money.id=s.ming_id",'left')
            ->field('money,tp_money.status,ie_status,tp_money.addtime,cause.name as cause_name,c.name as jituan_name,b.name as garden_name,a.name as class_name,principal,student.name as student_name,channel,s.chu_money,s.yu_money')
            ->select();
//        dump($list);die;
        $ji=DB::name("organization")->where("p_id",0)->select();
        $where['ji']=$ji;
        $this->assign("where",$where);
        $this->assign("list",$list);
        return view();
    }
    //动态获取数据
    public function getData(){
        $arr = $_POST['arr'];
//    	dump($arr);
        $where = "1=1";
        foreach ($arr as $key => $value) {

            if($value['id']!=0){
                $where.=" and tp_money.".$value['type']."=".$value['id'];
            }

        }
        $list  = DB::name("money")
            ->join('organization a','tp_money.class_id = a.id','left')
            ->join('organization b','tp_money.garden_id = b.id','left')
            ->join("organization c",'tp_money.ji_id = c.id','left')
            ->join("cause",'tp_money.cause_id = cause.id','left')
            ->join("student",'tp_money.student_id = student.id','left')
            ->join("garden_spare s","tp_money.id=s.ming_id",'left')
            ->where($where)
            ->field('money,tp_money.status,ie_status,tp_money.addtime,cause.name as cause_name,c.name as jituan_name,b.name as garden_name,a.name as class_name,principal,student.name as student_name,channel,s.chu_money,s.yu_money')
            ->select();
        return json_encode($list);

    }
}



?>