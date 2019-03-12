<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Property extends Controller{
    public function index(){
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
        $data = Db::name("property")
            ->alias("a")
            ->join("organization b",'a.ji_id = b.id')
            ->join("organization c",'a.garden_id = c.id')
            ->join("user d",'a.admin_id = d.admin_id')
            ->field("a.id,a.name,a.addtime,b.name as ji_name,c.name as garden_name,a.number,d.user_name,a.price")
            ->select();
        $this->assign('data',$data);
        return view();
    }
}


?>