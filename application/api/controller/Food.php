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

class Food {   
	//添加菜品
	public function menu(){
		// $garden_id = $this->garden_id;
		// $organization_id = $this->organization_id;
		// rData(1,"成功",$garden_id);
		$data['food_menu_type']=DB::name("food_menu_type")->select();
		$food_cai = DB::name("food_cai")->select();
    	$food_cai = $this->getTree($food_cai);

    	 foreach($food_cai as $key => $value){
            $list[$key]['name'] = str_repeat('--', $value['level']).$value['name'];
            $list[$key]['id'] = $value['id'];
            $list[$key]['p_id'] = $value['p_id'];
        }
		$data['food_cai']=$list;
		rData(1,"成功",$data);
	}

	//添加周菜谱首页页面数据展示
	public function week_menu(){
		$menu = DB::name("food_menu")
	    			->alias("f")
	    			->join("food_menu_type m","m.id=f.menu_type")
	    			->where("f.garden_id",$this->garden_id)
	    			->field("f.*,m.name as type_name")
	    			->select();
	    $list =[];
	    foreach ($menu as $key => $value) {
	    	$list[$value['menu_type']][]=$value;
	    }
	    $res=[];	    
	    foreach ($list as $key => $value) {
	    	foreach ($value as $k => $v) {
	    		if($k<=2){
	    			$res[$key][]=$v;
	    		}else{
	    			break;
	    		}
	    	}
	    }
		rData(1,"成功",$res);
	}

	//制作周菜谱   页面数据提交入库添加
	public function week_menu_add(){
		$data = I("post.");
		rData(1,"传输成功",$data);
		$res = DB::name("food_week_menu")->insert($data);
		if($res){
			rData(1,'添加成功');
		}else{
			rData(0,'添加失败');
		}
	}

	//制作周菜谱   轮播替换菜谱
	public function week_menu_huan(){
		$menu_type=I("post.menu_type");
		// $menu_type=1;
		$res = DB::name("food_menu")
				->where("menu_type",$menu_type)
				->field("id")
				->select();
		$res = array_column($res,"id");
	    shuffle($res);//利用shuffle()函数将产生的$num数组随机打乱顺序
	    for ($i=0; $i < 3; $i++) {//选取数组前3个，即随机 
	        $id .= $res[$i].",";
	    }
	    $id = rtrim($id,",");
		$menu = DB::name("food_menu")
	    			->alias("f")
	    			->join("food_menu_type m","m.id=f.menu_type")
	    			->where("f.id in (".$id.")")
	    			->field("f.*,m.name as type_name")
	    			->limit(3)
	    			->select();
	    rData(1,'成功',$menu);
	}

	//月菜谱展示   
	public function month_menu(){
		$data = DB::name("food_week_menu")
				// ->where("garden_id",$this->garden_id)
				->where("garden_id",8)
				->column("eat_time");
		foreach ($data as $key => $value) {
			$data[$key]=substr($value,0,7);
		}
		$data = array_unique($data);
		rData(1,"成功",$data);
	}



	//营养标准页面数据
	public function food_ying_biao(){
		$data = DB::name("food_ying_biao")->select();
		if($data){
			rData(1,'查询成功',$data);
		}else{
			rData(0,'查询失败');
		}
	}

	//食品材料采购列表   展示采购清单
	public function buy_list(){
		//当前日期
		$nowDate = date("Y-m-d");
		//$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		$first=1;
		//获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		$w=date('w',strtotime($nowDate));
		//获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$week_start=date('Y-m-d',strtotime("$nowDate -".($w ? $w - $first : 6).' days'));
		//本周结束日期
		$week_end=date('Y-m-d',strtotime("$week_start +6 days"));
		//日清单
		$data['day_list']=DB::name("cai_list")
							->alias("c")
							->join("food_cai f","f.id=c.cai_id")
							->where("addtime",'=',$nowDate)
							->where("garden_id",$this->garden_id)
							->order("c.id desc")
							->select();
		//周清单
		$data['week_list']=DB::name("cai_list")
							->alias("c")
							->join("food_cai f","f.id=c.cai_id")
							->where("addtime",">=",$week_start)
							->where("addtime","<=",$week_end)
							->where("garden_id",$this->garden_id)
							->order("addtime desc")
							->select();
		rData(1,"成功",$data);
	}

	//采购清单  前去采购  展示采购页面
	public function go_buy(){
		$food_cai = DB::name("food_cai")->select();
    	$food_cai = $this->getTree($food_cai);

    	 foreach($food_cai as $key => $value){
            $list[$key]['name'] = str_repeat('--', $value['level']).$value['name'];
            $list[$key]['id'] = $value['id'];
            $list[$key]['p_id'] = $value['p_id'];
        }
        rData(1,"成功",$food_cai);
	}
	//采购清单   前去采购  添加采购物品
	public function  buy_list_add(){
		$data = I("post.");
		$data['garden_id']=$this->garden_id;
		$res = DB::name("cai_list")->insert($data);
		if($res){
			rData(1,"添加成功");
		}else{
			rData(0,"添加失败");
		}

	}

	//营养标准页面    修改营养标准数据
	public function good_ying_biao_update(){
		//获取要修改的数据ID
		$id = I("post.id");
		//获取要修改的列名 字段名
		$key =I("post.key");
		//获取要修改的值
		$val =I("post.val");
		$data[$key]=$val;
		$res = DB::name("food_ying_biao")->where("id",$id)->update($data);
		if($res){
			rData(1,'修改成功',$data);
		}else{
			rData(0,'修改失败');
		}

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
}