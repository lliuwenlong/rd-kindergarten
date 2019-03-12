<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Classify extends Controller {

    public function one(){
        return view("index");
    }
    public function inv(){

        $data = Db::query("select * from tp_category");
        $data = $this->getTreeBySon($data);
        // foreach($data as $key => $value){
        //     $list[$key]['category_name'] = str_repeat('--', $value['level']).$value['category_name'];
        //     $list[$key]['category_id'] = $value['category_id'];
        //     $list[$key]['category_pid'] = $value['category_pid'];
        // }
        // $this->assign('list',$list);
        //dump($data);die;
         $this->assign('list',$data);
        return $this->fetch();
    }

        //调整菜单数据，  无限极分类 调整son类型
    function getTreeBySon($arr,$parent_id=0){
       $tree = [];
       foreach ($arr as $k=>$v){
           if ($v['category_pid'] == $parent_id){
               $v['son'] = $this->getTreeBySon($arr,$v['category_id']);
               // if ($v['son'] == null){
               //     unset($v['son']);
               // }
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
            if ($value['category_pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['category_id'], $level+1);

            }
        }
        return $list;
    }
//    function getTree($arr,$parent_id=0){
//        $tree = [];
//        foreach ($arr as $k=>$v){
//            if ($v['category_pid'] == $parent_id){
//                $v['son'] = $this->getTree($arr,$v['category_id']);
//                if ($v['son'] == null){
//                    unset($v['son']);
//                }
//                $tree[] = $v;
//            }
//        }
//        return $tree;
//    }
    public function admin_info(){
        $category_id = I('get.category_id/d',0);
        $act = I('get.act',0);
        if($category_id){
            if($act == 'update'){
                $info = Db::name('category')->where("category_id", $category_id)->find();
                $this->assign('info',$info);
                $act = 'update';
            }else{
                $info['category_id'] = $category_id;
                $this->assign('info',$info);
                $act = 'insert';
            }
        }
        if(empty($category_id)){
            $act = 'add';
        }
        $this->assign('act',$act);
        return $this->fetch();
    }
    public function adminHandle(){
        $data = I('post.');
        if($data['act'] == 'add'){
            Db::query("insert into tp_category set category_name = '{$data['category_name']}',category_pid = 0");
        }elseif ($data['act'] == 'insert'){
            Db::query("insert into tp_category values(null,'{$data['category_name']}',{$data['category_id']})");
        }else{
            Db::query("update tp_category set category_name = '{$data['category_name']}' where category_id = {$data['category_id']}");
        }
        return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Classify/inv')]);
    }
    public function del(){
        $data = I('post.');
        $list = Db::query("select category_id from tp_category where category_pid = {$data['category_id']}");
        if(!empty($list[0]['category_id'])){
            return json_encode(['status'=>0,'msg'=>'操作失败','url'=>U('Admin/Classify/inv')]);
        }else{
            Db::query("delete from tp_category where category_id = {$data['category_id']}");
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Classify/inv')]);
        }
    }
}