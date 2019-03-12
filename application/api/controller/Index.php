<?php
namespace app\admin\controller;
use think\Page;
use think\Verify;
use think\Loader;
use think\Db;
use think\Session;
use think\Controller;
use think\Cookie;
class Index extends Base{   
 	public function index(){
 		$node_list=$this->node_list;
 		dump($node_list);die;
 		return view();
 	}


 	  /**
     * 退出登陆
     */
    public function logout()
    {
       Cookie::delete('admin_id');
        $this->success("退出成功",U('Admin/Admin/index'));
    }

 }