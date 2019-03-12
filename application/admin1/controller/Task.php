<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Task extends Controller{
    public function index(){
        $data = Db::name("task_or")
            ->join("task",'tp_task_or.task_id = task.id','left')
            ->join("organization a","tp_task_or.garden_id = a.id",'left')
            ->join("organization b","a.p_id = b.id",'left')
            ->field('a.id as garden_id,b.name as jituan_name,a.name as garden_name,task.name as lv,tp_task_or.id,shi,yu')
            ->select();
        $res =[];
        foreach ($data as  $k=>$v){
            $res[$v['garden_id']]['garden_name']=$v['garden_name'];
            $res[$v['garden_id']]['jituan_name']=$v['jituan_name'];
            $res[$v['garden_id']]['lv'][$k]['lv_id']=$v['id'];
            $res[$v['garden_id']]['lv'][$k]['lv']=$v['lv'];
            $res[$v['garden_id']]['lv'][$k]['shi']=$v['shi'];
            $res[$v['garden_id']]['lv'][$k]['yu']=$v['yu'];
        }
//        dump($res);die;
        $this->assign("data",$res);
        return $this->fetch();
    }
}


?>