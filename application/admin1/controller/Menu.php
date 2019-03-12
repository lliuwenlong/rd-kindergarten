<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Menu extends Controller {
    public function inv(){

        $data = Db::query("select * from tp_user_node");
        $data = $this->getTreeBySon($data);
        // foreach($data as $key => $value){
        //     $list[$key]['name'] = str_repeat('--', $value['level']).$value['name'];
        //     $list[$key]['id'] = $value['id'];
        //     $list[$key]['p_id'] = $value['p_id'];
        // }
        // dump($data);die;
        $this->assign('list',$data);
        return $this->fetch();
    }
            //调整菜单数据，  无限极分类 调整son类型
    function getTreeBySon($arr,$parent_id=0){
       $tree = [];
       foreach ($arr as $k=>$v){
           if ($v['p_id'] == $parent_id){
               $v['son'] = $this->getTreeBySon($arr,$v['id']);
               if ($v['son'] == null){
                   unset($v['son']);
               }
               $tree[] = $v;
           }
       }
       return $tree;
   }
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

    //权限系统获取用户人员根据机构动态读取
    public function getuser(){
        $id = rtrim(I("get.id"),",");
        $idarr = explode(",",$id);
        if(end($idarr)==0){
            unset($idarr[count($idarr)-1]);
        }
        $id = implode(",",$idarr);
//        dump($idarr);die;
//        dump($id);die;
        $res = DB::name("user_node_relation")->where("organization_id",$id)->column("user_id");
        $data = DB::name("user")->where("admin_id","in",implode(",",$res))
        ->field("admin_id,user_name")
        ->select();
        // dump($data);die;
        if($data){
            return json_encode(['status'=>1,'msg'=>"查询成功",'data'=>$data]);
        }else{
             return json_encode(['status'=>0,'msg'=>"此机构下没有人哦亲"]);
        }
    }
    public function node(){
//        echo md5("admin45c7af1485d3ef45db160e6fa119a886yey");die;
        //获取机构数据
        $type2=DB::name("organization")->where("p_id",0)->select();     
        $this->assign("list",$type2);
        //   获取权限数据
        $node = DB::name("user_node")->where("p_id",'gt',0)->select();

        $node = $this->getTreeBySon($node,1);
        $this->assign('node',$node);
//         dump($node);die;
        return view();
    }
    // public function node_list(){
    //     $data = Db::name("node")->alias('n')->join("menu m","m.category_id=n.menu_id")->join("category c","c.category_id=n.category_id")->field("node_id,node_name,c.category_name,m.category_name as menu_name,addtime")->select();
    //     foreach ($data as $key => $value) {
    //        $data[$key]['addtime']=date("Y-m-d H:i:s",$value['addtime']);
    //     }
      
       
    //     $this->assign("list",$data);
    //     return view();
    // }


    //权限添加复选框点击子框父级选中
    public function nodecheck(){
        $menu_id = I("get.menu_id");
       $p_id =  DB::name("user_node")->where("id",$menu_id)->column("p_id");
      // dump($p_id);die;
       if($p_id[0]==1){
            return json_encode(['status'=>1,'msg'=>'主菜单','data'=>'']);
       }else{
            $p_id2=DB::name("user_node")->where("id",$p_id['0'])->column("p_id");
            if($p_id2[0]==1){
                return json_encode(['status'=>1,'msg'=>'二级菜单','data'=>['0'=>$p_id[0]]]); 
            }else{
                $p_id3=DB::name("user_node")->where("id",$p_id2['0'])->column("p_id");
                if($p_id3[0]==1){
                    return json_encode(['status'=>1,'msg'=>'三级菜单', 'data'=>['0'=>$p_id[0],'1'=>$p_id2[0]]]);
                }else{
                    return json_encode(['status'=>1,'msg'=>'四级菜单', 'data'=>['0'=>$p_id[0],'1'=>$p_id2[0],'2'=>$p_id3[0]]]);
                }

            }
            // dump($p_id2);die;
       }
       
    }

    public function nodeadd(){
        $data = I('post.');
//         dump($data);die;
        if(end($data['ji'])==0){
           unset($data['ji'][count($data['ji'])-1]);
        }

        $list['organization_id']=implode(",",$data['ji']);
        $list['node_id']=implode(",",$data['node']);
        $user_id=$data['ren'];
        $list['node_type']=$data['node_type'];
        $res= Db::name('user_node_relation')->where("user_id",$user_id)->update($list);
        if($res){
            return json_encode(['status'=>1,'msg'=>'添加权限成功','url'=>U('Admin/Menu/node')]);
        }else{
            return json_encode(['status'=>0,'msg'=>'添加权限失败','url'=>U('Admin/Menu/node')]);
        }
    }
    public function admin_info(){
        $id = I('get.id/d',0);
        $act = I('get.act',0);
        if($id){
            if($act == 'update'){
                $info = Db::name('user_node')->where("id", $id)->find();
                $this->assign('info',$info);
                $act = 'update';
            }else{
                $info['id'] = $id;
                $this->assign('info',$info);
                $act = 'insert';
            }
        }
        if(empty($id)){
            $act = 'add';
        }
        $this->assign('act',$act);
        return $this->fetch("admin_info");
    }
    public function menuHandle(){
        $data = I('post.');
        // dump($data);die;
        if($data['act'] == 'add'){
            $list['name']=$data['name'];
            $list['p_id']=1;
           
            DB::name("user_node")->insert($list);
           
        }elseif ($data['act'] == 'insert'){
            $list['name']=$data['name'];
            $list['p_id']=$data['p_id'];
            
            DB::name("user_node")->insert($list);
           
           
        }else{
            $list['name']=$data['name'];
            DB::name("user_node")->where("id",$data['p_id'])->update($list);
           
        }
        return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Menu/inv')]);
    }
    public function del(){
        $data = I('post.');
      
        $list = DB::name("user_node")->where("p_id",$data['id'])->select();
   
        if(!empty($list[0]['id'])){
            return json_encode(['status'=>0,'msg'=>'操作失败','url'=>U('Admin/Menu/inv')]);
        }else{
            DB::name("user_node")->delete($data['id']);
           
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Menu/inv')]);
        }
    }
}