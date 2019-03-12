<?php
namespace app\api\controller;
use think\Page;
use think\Verify;
use think\Loader;
use think\Db;
use think\Session;
use think\Controller;
use think\Cookie;
class Task extends Base{
    //获取园所管理-考核管理-管理指标页面内容数据接口
    public function index(){
        $garden_id = $this->garden_id;
        $data = Db::name("task_or")
            ->join("task",'tp_task_or.task_id = task.id','left')
            ->join("organization a","tp_task_or.garden_id = a.id",'left')
            ->join("organization b","a.p_id = b.id",'left')
            ->where("garden_id",$garden_id)
            ->field('a.id as garden_id,b.name as jituan_name,a.name as garden_name,task.name as lv,tp_task_or.id,shi,yu')
            ->select();
        if($data){
            rData(1,'ok',$data);
        }else{
            rData(0,'数据为空',$data);
        }

//        $res =[];
//        foreach ($data as  $k=>$v){
//            $res[$v['garden_id']]['garden_name']=$v['garden_name'];
//            $res[$v['garden_id']]['jituan_name']=$v['jituan_name'];
//            $res[$v['garden_id']]['lv'][$k]['lv_id']=$v['id'];
//            $res[$v['garden_id']]['lv'][$k]['lv']=$v['lv'];
//            $res[$v['garden_id']]['lv'][$k]['shi']=$v['shi'];
//            $res[$v['garden_id']]['lv'][$k]['yu']=$v['yu'];
//        }
    }

    //园所管理-考核管理-园长考核页面数据
    public function garden_list(){
        $ji_id = $this->ji_id;
        $fen = DB::name("assess_fen")->column("id");
        $fen_id = implode(",",$fen);
        $res = DB::name("staff")
            ->alias("s")
            ->join("assess_fen_staff a","s.staff_id = a.staff_id","left")
            ->join("organization j","j.id=s.ji_id",'left')
            ->join("organization r","r.id=s.o_id",'left')
            ->join("organization g","g.id=s.garden_id",'left')
            ->join("organization c","c.id=s.class_id",'left')
//            ->where("fen_id","in",$fen_id)
            ->where("s.ji_id",$ji_id)
            ->where("s.o_id",13)
            ->field("s.*,j.name as j_name,r.name as r_name,g.name as g_name,c.name as c_name,sum(fen) as fen")
            ->group("staff_id")
            ->select();
        if($res){
            rData(1,'ok',$res);
        }else{
            rData(0,'数据为空',$res);
        }
    }

    //园所管理——考核管理——园长考核——评分
    public function garden_fen_list(){
        //获取接取的员工id
        $staff_id = I("post.staff_id");
        $role_data=DB::name("staff")->where("staff_id",$staff_id)->find();
        $role_id = $role_data['o_id'];
       $role_name = DB::name("organization")->where("id",$role_id)->column("name");
//        rData(1,'ok',$role_name);
        $data = DB::name("assess_fen")
            ->alias("f")
            ->join("assess_type t","t.id=f.type_id")
            ->where("role_id",$role_name)
            ->select();
        dump($data);

    }

}