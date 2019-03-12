<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Techang extends Controller{

    public function index(){
        $ji=DB::name("organization")->where("p_id",0)->select();
//        $garden = DB::name("organization")->where("status",1)->select();
//        $class= DB::name("organization")->where("status",2)->select();
//        $post =DB::name("organization")->where("status",0)->select();
//        // dump($data);die;
//        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
//        $where['garden']=$garden;
//        $where['class']=$class;
//        $where['post']=$post;
//        $where['type']=$type;

        $this->assign("where",$where);
        $data = Db::name("techang")
            ->alias("a")
            ->join("organization b",'a.ji_id = b.id')
            ->join("organization c",'a.garden_id = c.id')
            ->field("a.*,b.name as ji_name,c.name as garden_name")
            ->select();
        $this->assign("data",$data);
        return view();
    }
    public function getTechangData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and a.".$value['type']."=".$value['id'];
            }
        }
        $data = Db::name("techang")
            ->alias("a")
            ->join("organization b",'a.ji_id = b.id')
            ->join("organization c",'a.garden_id = c.id')
            ->where($where)
            ->field("a.*,b.name as ji_name,c.name as garden_name")
            ->select();
        return json_encode($data);
    }
    public function add(){
        if(IS_POST){
            $data = I("post.");
            foreach($data as $key=>$val){
                if(empty($val)){
                    return json_encode(['status'=>0,'msg'=>$key."参数不存在,请填完!"]);
                }

            }
            foreach($data['desc'] as $key=>$val){
                if(empty($val)){
                    return json_encode(['status'=>0,'msg'=>"请好好填完日期时间!"]);
                }
            }
            $str='';
            foreach($data['desc'] as $key=>$value){
                if($key%2==0){
                    $str.=$value.",";
                }else{
                    $str.=$value."@";
                }

            }
//            dump($data);
//            dump($str);
            $str = rtrim($str,"@");
//            dump($str);die;
            $data['desc']=$str;
            $res = DB::name("techang")->insert($data);
            if($res){
                return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U("Techang/index")]);
            }else{
                return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }
//            dump($data);die;
        }else{
            $jituan = Db::name("organization")
                ->where("p_id = 0")
                ->select();
            $this->assign("jituan",$jituan);
            return view();
        }
    }

    public function XQ(){
        $id = I("get.id");
        $data = DB::name("techang")->where("id",$id)->find();
//        dump($data);
        $res = explode("@",$data['desc']);
//        dump($res);
        $re=[];
        foreach($res as $key=>$value){
            $re[$key]['name']="课时".($key+1);
            $re[$key]['time']=str_replace(",","——",$value);
        }
        $data['ke']=$re;
        dump($data);
        $this->assign("data",$data);
        return view();
    }
    public function del(){
        $id = I("post.id");
        $res = Db::name("techang")
            ->where("id",$id)
            ->delete();
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }
    }
}



?>