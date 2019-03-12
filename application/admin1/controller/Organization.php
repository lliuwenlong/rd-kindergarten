<?php 
namespace app\admin\controller; 
use think\AjaxPage;
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Db;
//机构控制器：负责添加机构班级等展示
class Organization extends Base {

	public function index(){
		$data = Db::name("organization")->select();
        $data = $this->getTreeBySon($data);
        // foreach($data as $key => $value){
        //     $list[$key]['name'] = str_repeat('--', $value['level']).$value['name'];
        //     $list[$key]['id'] = $value['id'];
        //     $list[$key]['p_id'] = $value['p_id'];
        // }
        // $this->assign("list",$list);
        // foreach ($data as $key => $value) {
        // 	$cat_id[$key] = explode(",",$value['category_id']);
        // }
        // foreach ($cat_id as $key => $value) {
        // 	if(end($value)=='0'){
        // 		$data[$key]['category_id']=$value[count($value)-2];
        // 	}
        // }
      // dump($data);die;
        $this->assign("list",$data);
		return view("inv");
	}

	public function getOrganization(){
		$cat_id = I("get.cat_id");
		$data = DB::name("category")->where("category_pid",$cat_id)->select();
		return json_encode($data);
	}
	public function getOrganization2(){
		$id = I("get.id");
		$cat_id = I("get.cat_id");
		$data = DB::name("organization")
				->where("category_id",'like',"%$cat_id%")
				->where("p_id",$id)
				->select();
		return json_encode($data);
	}

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

	// public function getgarden(){
	// 	$id = I("get.id");
	// 	if($id=="0"){
	// 		$res = DB::name("garden")->select();
	// 	}else{
	// 		$list = DB::name("admin")->where("admin_id",$id)->field("garden_id")->find();
			
	// 	}
	// 	dump($res);die;
	// 	echo json_encode($res);
		
	// }
	public function update(){
		if(IS_POST){
			$data = I("post.");
			
			if(end($data['addr'])=="zhi"){
				unset($data['addr'][count($data['addr'])-1]);
			}
			// dump($data);die;
			$list['name']=$data['name'];
			$list['addr_id']=implode(",",$data['addr']);
			
			DB::name("organization")->where("id",$data['id'])->update($list);
			$this->success("修改成功",U("Admin/Organization/index"));
		}
		$id = I("get.id");
		$addr = DB::name("city")->select();
		$name = DB::name("organization")->where("id",$id)->find();
		// dump($name);die;
		$addr_id=explode(",",$name['addr_id']);
		//dump($addr_id);die;
		$this->assign("addr_id",$addr_id);
		$this->assign('name',$name);
		$this->assign('addr',$addr);
		return view();
	}

	public function del(){
		$id = I("get.id");
		// dump($id);die;
		$data = DB::name("organization")->where("p_id",$id)->select();
		if(!empty($data)){
			return json_encode(['status'=>0,'msg'=>'操作失败,有子分类存在','url'=>U('Admin/Classify/inv')]);
		}
		
		$res = DB::name('organization')->delete($id);
		if($res){
			return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Classify/inv')]);
		}else{
			return json_encode(['status'=>0,'msg'=>'操作失败','url'=>U('Admin/Classify/inv')]);
		}
		
		// $this->success("删除成功",U("Admin/Organization/index"));
	}

	// public function add(){
	// 	$data=I("get.");

	// 	$category_id=$data['category_id'];
	// 	$id=$data['id'];
		
	// 	if(IS_POST){
	// 		$data=[];
	// 		$data = I("post.");
			
	// 		$list=[];
	// 		if($data['id']==0){
	// 			$list['p_id']=0;
	// 			$list['name']=$data['name'];
	// 			$list['addtime']=time();
	// 			$list['category_id']=implode(',',$data['cat']);
	// 			$list['addr_id']=implode(',',$data['addr']);
	// 		}else{
	// 			$list['p_id']=$data['id'];
	// 			$list['name']=$data['name'];
	// 			$list['addtime']=time();
	// 			$list['category_id']=$data['category_id'];
	// 			$list['addr_id']=implode(',',$data['addr']);
	// 		}
			 
	// 		DB::name("organization")->insert($list);
	// 		$this->success("添加成功",U("Admin/Organization/index"));
	// 	}
		
	// 	if($id==0){
	// 		$type=DB::name("category")->where("category_pid",1)->select();
	// 	}else{
	// 		$data = DB::name("organization")->where("id",$id)->find();
	// 		$cat_id = $data['category_id'];
	// 		$type=DB::name("category")->where("category_id",$cat_id)->select();
	// 	}
	// 	$addr = DB::name("city")->where("REGION_TYPE",1)->select();
	// 	$type1=DB::name("category")->where("category_id",$category_id)->find();
	// 	// dump($addr);die;
	// 	$type2=DB::name("organization")->where("p_id",0)->select();
	// 	$this->assign("type",$type);
	// 	$this->assign("addr",$addr);
	// 	$this->assign("type2",$type2);
	// 	$this->assign("category_id",$category_id);
	// 	$this->assign("type1",$type1);
	// 	$this->assign("id",$id);
	// 	return view();
	// }
	public function add(){
		if(IS_POST){
			$data = I("post.");
//			 dump($data);die;
			$list['name']=$data['name'];
			if(end($data['cat'])==0){
			    $list['category_id']=$data['cat'][count($data['cat'])-2];
            }else{
                $list['category_id']=$data['cat'][count($data['cat'])-1];
            }
//			$list['category_id']=implode(",",$data['cat']);
			if(count($data['ji'])==1){
				$list['p_id']=0;
			}else{
				if(end($data['ji'])==0){
					$list['p_id']=$data['ji'][count($data['ji'])-2];
				}else{
					$list['p_id']=$data['ji'][count($data['ji'])-1];
				}
				
			}
			
			$list['addr_id']=implode(",",$data['addr']);
			$list['status']=$data['type'];
			$list['addtime']=date("Y-m-d",time());
//			dump($list);die;
			$res = DB::name("organization")->insert($list);
			if($res){
                $this->success("添加成功",U("Admin/Organization/index"));
            }else{
                $this->error("添加失败",U("Admin/Organization/index"));
            }


		}else{
			
	 		$addr = DB::name("city")->where("REGION_TYPE",1)->select();
	 		$type=DB::name("category")->where("category_pid",1)->select();
			$type2=DB::name("organization")->where("p_id",0)->select();
			$this->assign("type",$type);
			$this->assign("addr",$addr);
			$this->assign("type2",$type2);
			return view();
		}
	}
	public function getmoaddr(){
		$id = I("get.id");
		$data = DB::name("organization")->where("id",$id)->find();
		// $addr_id = explode(",",$data['addr_id']);
		$addr= DB::name("city")->where("ID in (".$data['addr_id'].")")->select();
		// dump($addr);die;
		return json_encode($addr);

	}

	public function getaddr(){
		$id = I("get.id");
		if($id =="zhi"){
			$type=DB::name("city")->where("REGION_TYPE",1)->select();
		}else{
			$type=DB::name("city")->where("PARENT_ID",$id)->select();
		}
		
		if($type){
			return json_encode(['state'=>1,'data'=>$type]);
		}else{
			return json_encode(['state'=>0]);
		}
	}

	public function getcat(){
		$id = I("get.id");
		$type=DB::name("category")->where("category_pid",$id)->select();
		if($type){
			return json_encode(['state'=>1,'data'=>$type]);
		}else{
			return json_encode(['state'=>0]);
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