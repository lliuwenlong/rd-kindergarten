<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\controller\Base;
class Assess extends Base{
	//管理指标方法
	public function index(){
	
        $data = Db::name("task_or")
            ->join("task",'tp_task_or.task_id = task.id','left')
            ->join("organization a","tp_task_or.garden_id = a.id",'left')
            ->join("organization b","a.p_id = b.id",'left')
            ->field('a.id as garden_id,b.name as jituan_name,a.name as garden_name,task.name as lv,tp_task_or.id,shi,yu')
            ->select();
        $res =[];
        foreach ($data as  $k=>$v){
            $res[$v['garden_id']]['garden_name']=$v['garden_name'];
            $res[$v['garden_id']]['jituan_name']=$v['jituan_name'];
            $res[$v['garden_id']]['lv'][$k]['lv_id']=$v['id'];
            $res[$v['garden_id']]['lv'][$k]['lv']=$v['lv'];
            $res[$v['garden_id']]['lv'][$k]['shi']=$v['shi'];
            $res[$v['garden_id']]['lv'][$k]['yu']=$v['yu'];
        }
        $this->assign("data",$res);
        return $this->fetch();
    }


    //园务考核评分方法
    public function garden(){
    	$fen = DB::name("assess_fen")->column("id");
	    	$fen_id = implode(",",$fen);
    		$res = DB::name("staff")
    		->alias("s")
    		->join("assess_fen_staff a","s.staff_id = a.staff_id","left")
    		->join("organization j","j.id=s.ji_id",'left')
    		->join("organization r","r.id=s.o_id",'left')
    		->join("organization g","g.id=s.garden_id",'left')
    		->join("organization c","c.id=s.class_id",'left')
//    		->where("fen_id","in",$fen_id)
    		->field("s.*,j.name as j_name,r.name as r_name,g.name as g_name,c.name as c_name,sum(fen) as fen")
    		->group("staff_id")
    		->select();
    	// $data = DB::name("staff")
    	// 	->alias("s")
    	// 	->join("organization j","j.id=s.ji_id",'left')
    	// 	->join("organization r","r.id=s.o_id",'left')
    	// 	->join("organization g","g.id=s.garden_id",'left')
    	// 	->join("organization c","c.id=s.class_id",'left')
    	// 	->field("s.*,j.name as j_name,r.name as r_name,g.name as g_name,c.name as c_name")
    	// 	->select();
    	$ji=DB::name("organization")->where("p_id",0)->select();
    	$garden = DB::name("organization")->where("status",'in','1')->select();
    	$class= DB::name("organization")->where("status",2)->select();
    	$post =DB::name("organization")->where("status",0)->select();
    		// dump($data);die;
    	$type = DB::name("assess_type")->where("status",0)->select();
    	$where['ji']=$ji;
//    	$where['garden']=$garden;
//    	$where['class']=$class;
//    	$where['post']=$post;
//    	$where['type']=$type;

    	$this->assign("where",$where);
    	$this->assign("list",$res);
    	return view();
    }

    //ajax 动态获取评分分类标准
    public function getFenType(){
    	
    	$tid = I("get.tid");
    	if($tid=='园长'){
    		$data = DB::name("assess_type")->where("status",0)->select();
    	}else{
    		$data = DB::name("assess_type")->where("status",1)->select();
    	}
    
    	return json_encode($data);
    }

    //ajax动态获取园所分类
    public function getGarden(){
    	$id = I("get.id");
    	$cat = I("get.cat");

    	$garden = DB::name("organization")->where("p_id",$id)->where("status",'in',"1,3")->select();
    	return json_encode($garden);
    }

    //ajax动态获取班级分类
    public function getClass(){
    	$id = I("get.id");
    	$cat = I("get.cat");

    	$garden = DB::name("organization")->where("p_id",$id)->where('status',2)->select();
    	return json_encode($garden);
    }

    //园务考核评分 动态获取数据
    public function getData(){
    	$arr = $_POST['arr'];
//    	dump($arr);
    	$where = "1=1";
    	foreach ($arr as $key => $value) {
    		if($value['type']!='fen_id'){
    			if($value['id']!=0){
	    			$where.=" and s.".$value['type']."=".$value['id'];
	    		}
    		}else{
    			$type = $value['id'];
    		}
    		
    	}
//    	 echo $type;echo $where;die;
	    if($type!=0){
	    	$fen = DB::name("assess_fen")
	    	->where("type_id",$type)
	    	->column("id");
	    	// dump($fen);die;
	    	$fen_id = implode(",",$fen);
	    }else{
	    	$fen = DB::name("assess_fen")->column("id");
	    	$fen_id = implode(",",$fen);
	    }
    	
    	// $res = DB::name("assess_fen_staff")
    	// 		->alias("a")
    	// 		->join("staff f","f.staff_id=a.staff_id","right")
    	// 		->where("fen_id","in",$fen_id)
    	// 		->field("sum(fen) as fen,f.*")
    	// 		->group("staff_id")
    	// 		// ->join("staff s","s.staff_id=a.staff_id")
    	// 		->select();
    	// 		dump($res);die;
    	$res = DB::name("staff")
    		->alias("s")
    		->join("assess_fen_staff a","s.staff_id = a.staff_id","left")
    		->join("organization j","j.id=s.ji_id",'left')
    		->join("organization r","r.id=s.o_id",'left')
    		->join("organization g","g.id=s.garden_id",'left')
    		->join("organization c","c.id=s.class_id",'left')
    		->where($where)
    		->where("fen_id","in",$fen_id)
    		
    		->field("s.*,j.name as j_name,r.name as r_name,g.name as g_name,c.name as c_name,sum(fen) as fen")
    		->group("staff_id")
    		->select();
    	
    	return json_encode($res);
    }

    //考核评分标准方法
    public function fen(){
    	// $role = DB::name("yey_role")->select();
    	// $this->assign("role",$role);
    	$data = DB::name("assess_fen")->select();
//    	$type = DB::name("assess_type")->where("status",0)->select();
    	//获取职位
//        $ren = $this
//        $ren =DB::name("organization")->where("status",0)->select();
//        $ren = array_unique($ren);
//        dump($ren);die;
        $ji=DB::name("organization")->where("p_id",0)->select();
//        $garden = DB::name("organization")->where("status",'in','1')->select();
        $this->assign("garden",$garden);
        $this->assign('ji',$ji);
    	$this->assign("ren",$ren);
    	$this->assign("type",$type);
    	$this->assign("list",$data);
    	return view();
    }

    //考核评分添加
    public function fen_add(){
    	if(IS_POST){
    		$data = I("POST.");
//    		dump($data);die;
    		$res = DB::name("assess_fen")->insert($data);
    		if($res){
    			$this->success("成功",U("Assess/fen"));
    		}else{
    			$this->error("失败",U("Assess/fen"));
    		}
    		
    	}else{
    		$id = I("get.id");
//    		$data = DB::name("assess_type")->where("status",$id)->select();
//    		$role = DB::name("yey_role")->select();
    		$ji = DB::name("organization")->where("p_id",0)->select();
    		$this->assign("ji",$ji);
    		$this->assign("status",$id);
//    		$this->assign("role",$role);
//    		$this->assign("type",$data);
    		return view();
    	}
    }

    //根据集团id获取集团之下的所有评分分类
    public function getJiType(){
	    $id = I("get.id");
	    $status = I("get.status");
	    $data = DB::name("assess_type")->where("ji_id",$id)->where("status",$status)->select();
	    return json_encode($data);
    }

    //考核评分集团之下的职位
    public function getRole(){
        $data = I("get.");
	    $ji_id = $data['id'];
	    $data= DB::name("organization")->select();
        $data = $this->getTreeBySon($data);
//        dump($data);die;
        foreach ($data as $key=>$value){
            if($value['id']!=$ji_id){
                unset($data[$key]);
            }
        }
        $role=[];
        foreach ($data as $key=>$value){
            if($value['status']==0){
                $role[$value['id']]['name']=$value['name'];
                $role[$value['id']]['id']=$value['id'];
            }
            foreach($value['son'] as $ke=>$val){
                if($val['status']==0){

                    $role[$val['id']]['name']=$val['name'];
                    $role[$val['id']]['id']=$val['id'];
                }
                foreach($val['son'] as $k=>$v){
                    if($v['status']==0){

                        $role[$v['id']]['name']=$v['name'];
                        $role[$v['id']]['id']=$v['id'];
                    }
                    foreach($v['son'] as $ko=>$vo){
                        if($vo['status']==0){

                            $role[$vo['id']]['name']=$vo['name'];
                            $role[$vo['id']]['id']=$vo['id'];
                        }
                        foreach($vo['son'] as $kol=>$vol){
                            if($vol['status']==0){

                                $role[$vol['id']]['name']=$vol['name'];
                                $role[$vol['id']]['id']=$vol['id'];
                            }

                        }

                    }

                }

            }
        }
       return json_encode($role);



    }


    //考核评分获取评分标准动态和获取
    public function getFenData(){
    	$arr = $_POST['arr'];
//    	dump($arr);die;
    	$where = "1=1";
    	foreach ($arr as $key => $value) {
    		if($value['type']=='ji_id'){
                $where.=" and t.".$value['type']."=".$value['id'];
            }else{
                if($value['id']!=0){
                    $where.=" and f.".$value['type']."=".$value['id'];
                }
            }


    		
    		
    	}
//    	 dump($where);die;
    	$data = DB::name("assess_fen")
            ->alias("f")
        ->join("assess_type t","f.type_id=t.id")
    	->where($where)
    	->select();
    	return json_encode($data);
    }

    //考核评分方法 ajax获取选中角色的评分标准
    public function getFen(){
    	$role = I("get.role");
    	//没用
    	if($role==13){
    		$res =DB::name("assess_type")->where("status",0)->select();
    	}else{
    		$res =DB::name("assess_type")->where("status",1)->select();
    	}
    	$data = DB::name("assess_fen")
    		->alias("f")
    		->where("role_id",$role)
    		->join("assess_type t","f.type_id=t.id")
    		->column("type_id,t.name");
    	$data =array_unique($data);
    	foreach ($data as $key => $value) {
    		$list[$key]["type_id"]=$key;
    		$list[$key]['name']=$value;
    	}
    	return json_encode(['state'=>1,'msg'=>'ok','data'=>$list,'type'=>$res]);
    }

    //考核评分方法 ajax获取选中角色的评分标准 返回详情
    public function getFenXQ(){
    	$role_id = I("get.role_id");
    	$type_id = I("get.type_id");
    	$data = DB::name("assess_fen")
    		->alias("f")
    		
    		->join("assess_type t","f.type_id=t.id")
	    	->where("type_id",$type_id)
	    	->where("role_id",$role_id)
	    	->select();
	    return json_encode($data);
    }

    //考核评分标准分类
    public function type_add(){
	    if(IS_POST){
	        $data = I("post.");
//	        dump($data);die;
	        $res = DB::name("assess_type")->insert($data);
	        if($res){
	            $this->success("添加成功",U("assess/fen"));
            }else{
                $this->error("添加失败",U("assess/fen"));
            }
        }else{
            $ji = DB::name("organization")->where("p_id",0)->select();
            $this->assign("ji",$ji);
	        return view();
        }
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

}