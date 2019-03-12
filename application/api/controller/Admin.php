<?php
namespace app\api\controller;
use think\Controller;
use app\common\logic\AdminLogic;
use app\common\logic\ModuleLogic;
use think\Page;
use think\Verify;
use think\Loader;
use think\Db;
use think\Session;
use think\Cookie;

class Admin extends Controller{
    // 用户登录
    public function login()
    {
        $username = I('post.username/s');
        $password = I('post.password/s');
        $key = I("post.key");
        if($key!=md5($username.$password.'yey')){
        	rData(0,'非法登录，数据被篡改');
        }
        $res = DB::name("user")->where("user_name='$username'")->find();
        if($res){
            // return json_encode($res);
            if($res['password'] == $password){
//                Cookie::set('admin_id',$res['admin_id']);
                rData(1,"登录成功",['admin_id'=>$res['admin_id'],'user_name'=>$res['user_name']]);
            }else{
                  rData(0,"密码错误");
            }
        }else{
        	 $data['username']=$username;
        	 $data['pwd']=$password;
        	 $data['key']=$key;
              rData(0,"账号不存在",$data);
        }
    }

    //根据admin——id获取用户权限菜单  动态展示
    public function getMenu(){
         // $admin_id = Cookie::get("admin_id");
         $data=$this->role_node();
         rData('1','请求成功',$data);
    }

    public function adminHandle(){
    	$data = I('post.');
		$adminValidate = Loader::validate('Admin');
		if(!$adminValidate->scene($data['act'])->batch()->check($data)){
			$this->ajaxReturn(['status'=>-1,'msg'=>'操作失败','result'=>$adminValidate->getError()]);
		}
		if(empty($data['password'])){
			unset($data['password']);
		}else{
			$data['password'] =encrypt($data['password']);
		}
    	if($data['act'] == 'add'){
    		unset($data['admin_id']);    		
    		$data['add_time'] = time();
			$r = D('admin')->add($data);
    	}
    	
    	if($data['act'] == 'edit'){
    		$r = D('admin')->where('admin_id', $data['admin_id'])->save($data);
    	}
        if($data['act'] == 'del' && $data['admin_id']>1){
    		$r = D('admin')->where('admin_id', $data['admin_id'])->delete();
    	}
    	
    	if($r){
			$this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Admin/index')]);

		}else{
			$this->ajaxReturn(['status'=>-1,'msg'=>'操作失败']);
    	}
    }
    
    public function index(){
         if(!empty(Cookie::get('admin_id'))){
            $this->error("您已登录",U("Admin/Index/index"));
        }

            return view("admin/login");
    }

    
    /**
     * 退出登陆
     */
    public function logout()
    {
       Cookie::delete('admin_id');
        $this->success("退出成功",U('Admin/Admin/index'));
    }
    
    /**
     * 验证码获取
     */
    public function vertify()
    {
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => false,
            'useNoise' => false,
        	'reset' => false
        );    
        $Verify = new Verify($config);
        $Verify->entry("admin_login");
        exit();
    }
    
    /**
     * [role_node description]参入用户id    查询菜单列表返回
     * @param  [type] $id [description]用户id
     * @return [type]     [description]
     */
    private function role_node(){
       $node_list=DB::name("user_node")->where("p_id>0")->select();
        $data = $this->getTreeBySon($node_list,1);
        return $data;
    }

    
       //调整菜单数据，  无限极分类
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


    //调整菜单数据，  无限极分类 调整son类型
   public function getTreeBySon($arr,$parent_id=0){
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