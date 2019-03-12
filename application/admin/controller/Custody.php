<?php
namespace app\admin\controller;
use think\Db;

class Custody extends \think\Controller{
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
        $data = Db::name("custody_detail")
            ->alias("a")
            ->join("custody b",'a.wuzi_id = b.id','left')
            ->join("organization c",'b.ji_id = c.id',"left")
            ->join("organization d",'b.garden_id = d.id','left')
            ->join("user e",'a.admin_id = e.admin_id','left')
            ->field("a.id,b.id,b.name,a.status,c.name as ji_name,e.user_name,d.name as garden_name,desc,a.number,a.sum,a.addtime")
            ->select();
        $this->assign("data",$data);
        return view();
    }
    public function del(){
            $id = I("post.id");
            $res = Db::name("custody_detail")
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
                $where.=" and b.".$value['type']."=".$value['id'];
            }
        }

        $data = Db::name("custody_detail")
            ->alias("a")
            ->join("custody b",'a.wuzi_id = b.id','left')
            ->join("organization c",'b.ji_id = c.id',"left")
            ->join("organization d",'b.garden_id = d.id','left')
            ->join("user e",'a.admin_id = e.admin_id','left')
            ->where($where)
            ->field("a.id,b.id,b.name,a.status,c.name as ji_name,e.user_name,d.name as garden_name,desc,a.number,a.sum,a.addtime")
            ->select();
        return json_encode($data);
    }
}


?>