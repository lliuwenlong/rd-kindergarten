<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Teach extends Controller{
    //幼儿考勤管理
    public function clocking_in(){
        $data = Db::name("clocking_in")
            ->alias("a")
            ->join("student b","a.student_id = b.id")
            ->join("organization c","b.ji_id = c.id")
            ->join("organization d","b.garden_id = d.id")
            ->join("organization f","b.class_id = f.id")
            ->field("b.*,a.time,a.status,c.name as ji_name,d.name as garden_name,f.name as class_name")
            ->select();
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
        $this->assign("list",$data);
        $this->assign("where",$where);
        return view();
    }
    public function getclocking_inData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and b.".$value['type']."=".$value['id'];
            }
        }

        $data = Db::name("clocking_in")
            ->alias("a")
            ->join("student b","a.student_id = b.id")
            ->join("organization c","b.ji_id = c.id")
            ->join("organization d","b.garden_id = d.id")
            ->join("organization f","b.class_id = f.id")
            ->where($where)
            ->field("b.*,a.time,a.status,c.name as ji_name,d.name as garden_name,f.name as class_name")
            ->select();
        return json_encode($data);
    }

    //成长档案
    public function record(){
        $where = $this->SX();
        $this->assign("where",$where);
        $list = DB::name("student")->select();
        $this->assign('list',$list);
        return view();
    }
    public function getrecordData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and ".$value['type']."=".$value['id'];
            }
        }

        $data = DB::name("student")->where($where)->select();
        return json_encode($data);
    }
    //成长档案 学生档案详情
    public function recordXQ(){
        $id = I("get.id");
        $data = DB::name("student_dangan")->where("student_id",$id)->select();
        // dump($data);
        foreach ($data as $key => $value) {
            $data[$key]['img']=explode(",",$value['img']);
        }
        // dump($data);die;
        $this->assign('list',$data);
        return view();
    }
    //成长档案   学生档案详情  模板添加
    public function mu_add(){
        if(IS_POST){
                // 获取表单上传文件 例如上传了001.jpg
                $file = request()->file('files');
                // 移动到框架应用根目录/public/uploads/ 目录下
                if($file){
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'img');
                    if($info){
                       
                        $data['img'] = $info->getSaveName();
                        $data['name'] = I("post.name");
                        // dump($data);die;
                        $res = DB::name("muban")->insert($data);
                        if($res){
                            $this->success("添加成功",U("Teach/record"));
                        }else{
                            $this->error("添加失败",U("Teach/record"));
                        }
                    }else{
                        // 上传失败获取错误信息
                        echo $file->getError();
                    }
                }
        }else{
            return view();
        }
       
    }
    //教育教学
    public function teaching(){
        $where = $this->SX();
        $this->assign("where",$where);
         $list = DB::name("teaching")->select();
        $this->assign('list',$list);
        return view();
    }
    public function getteachingData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and ".$value['type']."=".$value['id'];
            }
        }

        $data = DB::name("teaching")->where($where)->select();
        return json_encode($data);
    }
    //巡园日志
    public function patrol(){
        $where = $this->SX();
        $this->assign("where",$where);
        $list = DB::name("garden_xun_log")->select();
        $this->assign('list',$list);
        return view();
    }
    public function getpatrolData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and ".$value['type']."=".$value['id'];
            }
        }

        $data = DB::name("garden_xun_log")->where($where)->select();
        return json_encode($data);
    }

    //园长日报
    public function garden_day(){
        // $data = DB::name("organization")->where("branch","in",1)->select();
        // dump($data);die;
        // echo "园长日报";
        return view();
    }

    //园长日报添加
    public function garden_add(){
        if(IS_POST){
            $data = I("post.");
            dump($data);
        }else{
            return view();
        }
    }


    //园所考核评分
    public function statement(){
        //筛选条件   展示
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
        $data = DB::name("garden_fen_biao")->select();
        $data = $this->getTreeBySon($data);
        // dump($data);die;
        $this->assign("list",$data);
        return view();
    }
    public function getstatementData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and ".$value['type']."=".$value['id'];
            }
        }
//        echo $where;
        $data = DB::name("garden_fen_biao")->where($where)->select();
//        dump($data);
        $data = $this->getTreeBySon($data);
//        dump($data);
        return json_encode($data);
    }

    public function statement_add(){
        if(IS_POST){
            $data = I("post.");
//            dump($data);die;
            $res = DB::name("garden_fen_biao")->insert($data);
            if($res){
                $this->success("添加成功",U("Teach/statement"));
            }else{
                $this->error("添加失败",U("Teach/statement"));
            }
        }else{
          $type = DB::name("garden_fen_biao")->where("p_id",0)->select();
          $ji = DB::name("organization")->where("p_id",0)->select();
          $this->assign("ji",$ji);
            $this->assign("type",$type);
            return view();  
        }
        
    }

//递归处理数组 
    function getTreeBySon($arr,$parent_id=0){
       $tree = [];
       foreach ($arr as $k=>$v){
           if ($v['p_id'] == $parent_id){
               $v['son'] = $this->getTreeBySon($arr,$v['id']);
               // if ($v['son'] == null){
               //     unset($v['son']);
               // }
               $tree[] = $v;
           }
       }
       return $tree;
   }
   //递归处理数组
    function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['p_id'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['id'], $level+1);

            }
        }
        return $list;
    }

//筛选条件   返回
    public function SX(){
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
        return $where;
    }
   
}



?>