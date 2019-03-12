<?php

namespace  app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\controller\Base;
class Account extends Controller{
    public function page($table,$limitSize,$where,$get){
        $count = Db::name($table)->where($where)->count("*");
        $pageSize = $limitSize;
        $pageCount = ceil($count/$pageSize);
        $page = $get;
        $pageLimit = ($page-1)*$pageSize;
        $data["pageCount"] = $pageCount;
        $data['pageLimit'] = $pageLimit;
        $data['pageSize'] = $pageSize;
        $data['pageUp'] = $page-1<1?1:$page-1;
        $data['pageNe'] = $page+1>$pageCount?$pageCount:$page+1;
        return $data;
    }
    public function index(){
        $where ="1=1";
        $get =isset($_GET['page'])?$_GET['page']:1;
        if(request()->isAjax()){
            $data = $_POST;
            if(!empty($data["garden"])){
                $where .= " and tp_money.garden_id = {$data['garden']}";
            }
            if(!empty($data["ie_status"])){
                if($data["ie_status"] == 2){
                    $where .= " and tp_money.ie_status = 0";
                }else{
                    $where .= " and tp_money.ie_status = {$data['ie_status']}";
                }
            }
            if(!empty($data["time"])){
                $where .= " and tp_money.addtime = '{$data['time']}'";
            }
        }
        $page = $this->page("money","7",$where,$get);
        $this->assign("page",$page);
        $list  = Db::name("money")
            ->join('organization a','tp_money.class_id = a.id','left')
            ->join('organization b','tp_money.garden_id = b.id','left')
            ->join("organization c",'tp_money.ji_id = c.id','left')
            ->join("cause",'tp_money.cause_id = cause.id','left')
            ->join("student",'tp_money.student_id = student.id','left')
            ->limit("{$page['pageLimit']}","{$page['pageSize']}")
            ->where($where)
            ->field('money,tp_money.status,ie_status,tp_money.addtime,cause.name as cause_name,c.name as jituan_name,b.name as garden_name,a.name as class_name,principal,student.name as student_name,channel')
            ->select();
        if(request()->isAjax()){
            $li["page"] = $page;
            $li["data"] = $list;
            return json_encode($li);die;
        }
        $data = Db::name("money")
            ->join("organization c",'tp_money.ji_id = c.id','left')
            ->field('c.name as jituan_name,sum(money) as money,c.id,ie_status')
            ->group('c.name,c.id,ie_status')
            ->select();
        $res=[];
        foreach ($data as $key=>$v){
            $res[$v['id']]['jituan_name']=$v['jituan_name'];
            if($v['ie_status']==0){
                $res[$v['id']]['zhichu']=$v['money'];
            }else{
                $res[$v['id']]['shouru']=$v['money'];
            }
        }
        $this->assign('data',$res);
        $garden = Db::name("organization")->where("status","1")->select();
        $this->assign("garden",$garden);
        $this->assign('list',$list);
        $cause = Db::table("tp_cause")->select();
//        dump($list);die;
        $this->assign('cause',$cause);
        return $this->fetch();
    }
}




?>