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
    public function dels(){
        $id = I("post.id");
        $res = Db::name("property")
            ->where("id",$id)
            ->delete();
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }
    }

    public function getData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and a.".$value['type']."=".$value['id'];
            }
        }
        $data = Db::name("property")
            ->alias("a")
            ->join("organization b",'a.ji_id = b.id')
            ->join("organization c",'a.garden_id = c.id')
            ->join("user d",'a.admin_id = d.admin_id')
            ->where($where)
            ->field("a.id,a.name,a.addtime,b.name as ji_name,c.name as garden_name,a.number,d.user_name,a.price")
            ->select();
        return json_encode($data);
    }




}


?>