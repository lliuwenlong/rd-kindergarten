<?php 
namespace app\admin\controller;

use app\common\logic\AdminLogic;
use app\common\logic\ModuleLogic;
use think\Page;
use think\Verify;
use think\Loader;
use think\Db;
use think\Session;

class People extends Base {


	public function index(){
        $list=DB::name("user")
            ->alias("u")
            ->join("user_node_relation r","r.user_id=u.admin_id")
            ->select();
            foreach ($list as $key => $value) {
               $list[$key]['organization']=DB::name("organization")->where("id","in",$value['organization_id'])->column("name");
               // ->getLastSql();
            }
         
            foreach ($list as $key => $value) {
             $list[$key]['organization'] = implode("——>",$value['organization']);
            }
       
          // dump($ji);
          // dump($jia);
         //dump($list);die;
        $this->assign("list",$list);
        return view();
    }


    //人员编辑修改数据
    public function update(){
      if(IS_POST){
            $data = I("post.");
            // dump($data);die;
            $list['user_name']=$data['user_name'];
            $list['tel']=$data['tel'];
            $list['password']=md5($data['password']);
            $admin_id = $data['admin_id'];
            $res['organization_id']=implode(",",$data['ji']);
            DB::name("user")->where("admin_id",$admin_id)->update($list);
            DB::name("user_relation")->where("admin_id",$admin_id)->update($res);
            return json_encode(['status'=>1,'msg'=>"修改成功",'url'=>U('Admin/People/index')]);

      }else{
            $admin_id = I("get.admin_id");
            $list = DB::name("user")->where("admin_id",$admin_id)->find();
            // dump($list);die;
            $this->assign("info",$list);
            $type2=DB::name("organization")->where("p_id",0)->select();
            $this->assign("type2",$type2);
            return view();
      }
     
    }

    public function add(){
        if(IS_POST){
            //只能添加投资人
            $data = I("post.");
//            dump($data);die;
            $user['user_name']=$data['user_name'];
            $user['tel']=$data['tel'];
            $user['password']=md5($data['password']);
            $user['addtime']=time();
            $user['role_id']='all';
            $admin_id = DB::name("user")->insertGetId($user);
            if(!$admin_id){
              
               return json_encode(["status"=>0,"msg"=>"添加失败"]);
            }
            $res['user_id']=$admin_id;
//            $res['organization_id']=implode(',',$data['ji']);
            $res['organization_id']=$data['ji'][0];
            $r=DB::name("user_node_relation")->insert($res);
            if($r){
               return json_encode(["status"=>1,"msg"=>"添加成功",'url'=>U('Admin/People/index')]);
            }else{
               return json_encode(["status"=>0,"msg"=>"添加失败"]);
            }
           

        }else{
            $type2=DB::name("organization")->where("p_id",0)->select();
            $this->assign("type2",$type2);
            return view();
        }
    }
    
      public function del(){
        $id = I("get.admin_id");
        // dump($id);die;
         $res = DB::name("user")->where("admin_id",$id)->delete();
         if($res){
            return json_encode(['status'=>1,'msg'=>'删除成功']);
         }else{
          return json_encode(['status'=>0,'msg'=>'删除失败']);
         }
      }
        public function getji(){
        $id = I("get.id");
        $type=DB::name("organization")->where("p_id",$id)->select();
        if($type){
            return json_encode(['state'=>1,'data'=>$type]);
        }else{
            return json_encode(['state'=>0]);
        }
        
    }


}


?>