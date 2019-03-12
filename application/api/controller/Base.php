<?php
namespace app\api\controller;

use think\Controller;
use think\Db;
use think\response\Json;
use think\Cookie;
class Base extends Controller {
    public $node_list;//权限列表
    public $organization_id;//机构ID整合
    public $garden_id;//园所ID
    public $ji_id;//集团id
    public function __construct(){
    	$admin_id =I("post.admin_id");
       	 // $admin_id = Cookie::get("admin_id");
         // rData(0,"失败",$admin_id);
        if(empty($admin_id)){
            rData(0,"非法登陆");
            // $this->error("非法登录",U("Admin/admin/index"));
        }  
      	$this->role_node($admin_id);
    }
    //判断用户是否拥有权限
    public function node(){
      $admin_id = I("post.admin_id");
      //$admin_id = Cookie::get("admin_id");
      $menu_id = I("post.menu_id");
      if(empty($menu_id)){
      	rData(0,"没有传递参数menu_id");
      }
      $this->node_list = $this->role_node($admin_id);
      
      if(!in_array($menu_id,explode(",",$this->node_list))){
        rData(0,"请求失败，没有此权限",$admin_id);
      }else{
        rData(1,"请求成功，拥有此权限",$this->node_list);
      }
    }


    //获取用户登录之后    所在集团  展示集团之下园所  班级
    public function getJT(){
    	$admin_id = I("post.admin_id");
    	$res=DB::name("user_relation")->where("admin_id",$admin_id)->find();
    	//集团id
    	$ji_id = explode(",",$res['organization_id'])[0];
    	$ji = DB::name("organization")->where("id",$ji_id)->find();
    	//园所
    	$garden = DB::name("organization")->where("p_id",$ji_id)->where("status",1)->select();
    	foreach ($garden as $key => $value) {
    		$garden_id .= $value['id'].",";
    	}
    	$garden_id = rtrim($garden_id,",");
    	$class = DB::name("organization")->where("status",2)->where("p_id","in",$garden_id)->select();
    	$data['ji']=$ji;
    	$data['garden']=$garden;
    	$data['class']=$class;
    	// dump($data);die;
    	rData(1,"成功",$data);
    	
    }

    //点击园所  展示园所之下的所有班级 
    public function getClass(){
    	$garden_id = I("post.garden_id");
    	$class = DB::name("organization")->where("status",2)->where("p_id",$garden_id)->select();
    	rData(1,"ok",$class);
    }

    /**
     * [role_node description]参入用户id    查询权限列表返回
     * @param  [type] $id [description]用户id
     * @return [type]     [description]
       ["node_id"]   权限id  ','分割  多权限
       ["organization_id"]   用户所属机构  
       ["node_type"]   权限类型
 
     */
    public function role_node($id){
        $node = DB::name("user_node_relation")->where("user_id",$id)->find();
        $this->organization_id = $node['organization_id'];
        $this->ji_id = explode(",",$node['organization_id'])[0];
        $this->garden_id = explode(",",$node['organization_id'])[1];
        return $node['node_id'];
    }

    //调整菜单数据，  无限极分类
    public function getTree($array, $pid =0, $level = 0){

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


    //调整菜单数据，  无限极分类 调整son类型
    public  function getTreeBySon($arr,$parent_id=0){
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
}