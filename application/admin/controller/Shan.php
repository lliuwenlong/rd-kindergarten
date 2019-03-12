<?php
/**
 * 菜谱管理
 */
namespace app\admin\controller; 
use think\AjaxPage;
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Db;
use think\Paginator;
class Shan extends Base {
	//食谱列表展示
	public function food_menu(){
       
      $page = isset($_GET['page'])?$_GET['page']:1;
      $pagesize = 10;
      $count = DB::name("food_menu")->count();
      $pagecount = ceil($count/$pagesize);
      $pagelimit = ($page-1)*$pagesize;
      $uppage = ($page-1)<1?1:($page-1);
      $nextpage = ($page+1)>$pagecount?$pagecount:($page+1);
        $list=DB::name("food_menu")->order('id desc')->limit($pagelimit,$pagesize)->paginate($pagesize,true);
        // dump($list);die;
        $this->assign("uppage",$uppage);
        $this->assign("nextpage",$nextpage);
        $this->assign("list",$list);
		
		return view();
	}
    //食谱详情展示
    public function getXQ(){
        $id = I("get.id");
        $data = DB::name("food_menu")->where("id",$id)->find();
        return json_encode($data);
    }
    //食谱图片修改
    public function img_save(){
        $data = I("post.");
        // dump($data);
        if(empty($_FILES['file']['type'])){
            $this->error("没有上传图片");die;
        }
        $file = request()->file('file');
        // 移动到框架应用根目录/public/img/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'img');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 2060820/42a79759f284b767dfcb2a09790428jpg
            $cai['img'] = $info->getSaveName();
            $id = $data['id'];

            $res = DB::name("food_menu")->where("id",$id)->update($cai);
            if($res){
                $this->success("添加成功","Shan/food_menu");
            }else{
                $this->error("添加失败");
            }

        }else{
            // 上传失败获取错误信息
            $this->error($file->getError());

        }
    }

    //食谱删除
	public function food_menu_del(){
		$id = I("get.id");
    	$res = DB::name("food_menu")->where("id",$id)->delete();
    	if($res){
    		$this->success("删除成功",U("Admin/Shan/food_menu"));
    	}else{
    		$this->error("删除失败",U("Admin/Shan/food_menu"));
    	}
	}
    //营养标准
    ////即点即改修改营养标准
    public function ying_biao(){
        if(IS_AJAX){
            $val = I("get.val");
            $id = I("get.id");
            $name = I("get.name");
          $data[$name]=$val;
          dump($data);

           $res= DB::name("food_ying_biao")->where('id',$id)->update($data);
            if($res){
                echo 1;
            }else{
                echo 0;
            }
        }else{
             $list = DB::name("food_ying_biao")->select();
            $this->assign('list',$list);
            return view();
        }
       
    }

    public function index(){
        echo "这是index方法";
    }
    //这是后台管理中的上传excel装换数据方法
    public function two(){
    	vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        //获取表单上传文件
        $file = request()->file('excel');
        $info = $file->validate(['size'=>200000000,'ext'=>'xlsx,xls,csv'])->move(ROOT_PATH . 'public' . DS . 'excel');
        if($info){
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'excel' . DS . $exclePath;   //上传文件的地址
            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');

            $PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            echo "<pre>";
            $currentSheet=$PHPExcel->getSheet(0);
            // $allColumn=$currentSheet->getHighestColumn();
            $allRow=$currentSheet->getHighestRow();
            $allColumn="E";
            $data=[];
            for($currentRow=4;$currentRow<=$allRow;$currentRow++){
                for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                    $address=$currentColumn.$currentRow;
                    $data[$address]=$currentSheet->getCell($address)->getValue();
                }
            }
            $res=[];
            foreach ($data as $key => $value) {              
                $res[substr($key,0,1)][]=$value; 
            }
            $list=[];          
                   $i=0;
                    foreach ($res['A'] as $k => $v) {   
                        if(!empty($v)){
                           if($i==0){
                              $list[$v]['id']=$v;
                               $list[$v]['name']=$res['B'][$k];
                               $i=1; 
                               continue; 
                           }
                            $list[$v]['id']=$v;
                            $list[$v]['name']=$res['B'][$k];
                            for($j=$i-1;$j<$k;$j++){
                                 $list[$v-1]['cai'][]=$res['C'][$j];
                                 $list[$v-1]['ke'][]=$res['D'][$j];
                                 $list[$v-1]['hl'][]=$res['E'][$j];
                            }
                             $i=$k+1;
                             $id=$v;
                           if($k==count($res['A'])-1){
                             for($j=$i-1;$j<=$k;$j++){
                                 $list[$v]['cai'][]=$res['C'][$j];
                                 $list[$v]['ke'][]=$res['D'][$j];
                                 $list[$v]['hl'][]=$res['E'][$j];
                            }
                        }
                           
                        }else{
                        if($k==count($res['A'])-1){
                            for($j=$i-1;$j<=$k;$j++){
                                 $list[$id]['cai'][]=$res['C'][$j];
                                 $list[$id]['ke'][]=$res['D'][$j];
                                 $list[$id]['hl'][]=$res['E'][$j];
                            }
                        }
                        }
                    }

            $data=[];
           foreach ($list as $key => $value) {
            $food_cai='';
            $cai_id=[];
                foreach ($value['cai'] as $k => $v) {
                   $cai_id[]=DB::name("food_cai")->where("name",$v)->field("id")->find();
                }
                foreach ($cai_id as $ke => $va) {
                    $food_cai.=$va['id'].",";
                }
                $food_cai = rtrim($food_cai,",");

            
             $data[$key]['name']=$value['name'];
             $data[$key]['cai']=implode(",",$value['cai']);
             $data[$key]['ke']=implode(",",$value['ke']);
             $data[$key]['hl']=implode(",",$value['hl']);
             $data[$key]['food_cai']=$food_cai;
             $data[$key]['food_menu_type']=I("post.food_menu_type");
           }  
            Db::name('food_menu')->insertAll($data);
            $this->success("添加成功",U("Admin/Shan/index"));
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }
    //菜谱分类展示
    public function menu_type_list(){
    	$data = DB::name("food_menu_type")->select();
    	$this->assign("list",$data);
    	return view();
    }

    //菜谱分类删除
    public function hou_menu_type_del(){
    	$id = I("get.id");
    	$res = DB::name("food_menu_type")->where("id",$id)->delete();
    	if($res){
    		$this->success("删除成功",U("Admin/Shan/menu_type_list"));
    	}else{
    		$this->error("删除失败",U("Admin/Shan/menu_type_list"));
    	}
    }
      //菜谱分类编辑
    public function hou_menu_type_edit(){
    	if(IS_POST){
    		$data = I("post.");
    		$list['name']=$data['name'];
    		$list['addtime']=time();
    		$res = DB::name("food_menu_type")->where("id",$data['id'])->update($list);
    		if($res){
	    		$this->success("编辑成功",U("Admin/Shan/menu_type_list"));
	    	}else{
	    		$this->error("编辑失败",U("Admin/Shan/menu_type_list"));
    		}
    	}else{
    		$id = I("get.id");
    	$res = DB::name("food_menu_type")->where("id",$id)->find();
    	$this->assign("res",$res);
    	return view();
    	}
    	
    	
    }


    //后台添加菜谱分类
    public function hou_menu_type(){
        if(IS_POST){
            $data = I("post.");
            $data['addtime']=time();
            DB::name("food_menu_type")->insert($data);
            $this->success("添加成功",U("Admin/Shan/menu_type_list"));
        }else{
          return view();  
        }
        
    }

    //前台菜谱添加
    public function qian_food_add(){
    	if(IS_POST){
    		$data = I("post.");
    		// dump($data);
    		if(empty($_FILES['file']['type'])){
    			$this->error("没有上传图片");die;
    		}
    		$file = request()->file('file'); 		
		    // 移动到框架应用根目录/public/img/ 目录下
		    $info = $file->move(ROOT_PATH . 'public' . DS . 'img');
		    if($info){
		        // 成功上传后 获取上传信息
		        // 输出 2060820/42a79759f284b767dfcb2a09790428jpg
		       $cai['img'] = $info->getSaveName();	
		       foreach ($data['ke'] as $key => $value) {
		       		$ke[]=empty($value)?"——":$value;
		       }
		        foreach ($data['hl'] as $key => $value) {
		       		$hl[]=empty($value)?"——":$value;
		       }

		       $cai['ke']=implode(",",$ke);
		       $cai['hl']=implode(",",$hl);
		       $cai['garden_id']=$data['garden_id'][1];
		       $cai['name']=$data['name'];
		       $cai['addtime']=time();
		       $cai['food_cai']=implode(",",$data['food_cai']);
		       $cailiao=DB::name("food_cai")->where("id","in",$cai['food_cai'])->column("name");
		       $cai['cai']=implode(",",$cailiao);
		   
		       $cai['menu_type']=$data['menu_type'];
		       // dump($cai);die;
		       $res = DB::name("food_menu")->insert($cai);
		       if($res){
		       	$this->success("添加成功","Shan/food_menu");
		       }else{
		       	$this->error("添加失败");
		       }
		       
		    }else{
		        // 上传失败获取错误信息
		        $this->error($file->getError());
		       
		    }	
    	}
    	$garden = DB::name("organization")->where("p_id",0)->select();
    	$menu = DB::name("food_menu_type")->select();
    	$data = DB::name("food_cai")->select();
    	$data = $this->getTree($data);
    	 foreach($data as $key => $value){
            $list[$key]['name'] = str_repeat('--', $value['level']).$value['name'];
            $list[$key]['id'] = $value['id'];
            $list[$key]['p_id'] = $value['p_id'];
        }
    	$this->assign("cai",$list);
    	$this->assign("menu",$menu);
    	$this->assign("garden",$garden);
    	return view();
    }

    //菜谱添加   机构园所动态
    public function getji(){
		$id = I("get.id");
		$type=DB::name("organization")->where("p_id",$id)->select();
		if($type){
			return json_encode(['state'=>1,'data'=>$type]);
		}else{
			return json_encode(['state'=>0]);
		}
		
	}

  //后台菜谱添加展示
    public function hou_menu_add(){
        $type = DB::name("food_menu_type")->select();
        $this->assign('type',$type);
    	return view();
    }

    public function week_menu(){
    	if(IS_POST){
    		$data = I("post.");
    		$data['menu_id']=implode(",",$data['menu_id']);
    		$res = DB::name("week_menu")->insert($data);
    		if($res){
    			$this->success("添加成功");
    		}else{
    			$this->error('添加失败');
    		}
    	}
    	$menu = DB::name("food_menu")
    			->alias("f")
    			->join("food_menu_type m","m.id=f.menu_type")
    			->field("f.*,m.name as type_name")
    			->select();
    	foreach ($menu as $key => $value) {
    		$data[]=DB::name("food_cai")->where("id","in",$value['food_cai'])->field("name")->select();
    	}
    	foreach ($data as $key => $value) {
    		foreach ($value as $k => $v) {
    			$str[$key][]=implode(",",$v);
    		}
    		
    	}
    	foreach ($str as $key => $value) {
    		$menu[$key]['food_cai']=implode(",",$value);
    	}
    	// dump($str);
    	// dump($menu);
    	// dump($data);die;
    	$list=[];
    	foreach ($menu as $key => $value) {
    		$list[$value['eat_time_type']][]=$value;
    	}
    	// dump($list);
    	// dump($menu);die;
    	$this->assign("menu",$list);
    	return view();
    }
    public function go_buy(){
    	if(IS_POST){
    		$data = I("post.");
    		$data['addtime']=date("Y-m-d",time());
    		
    		 $res = DB::name("cai_list")->insert($data);
		       if($res){
		       		$this->success("添加成功","Shan/buy_list");
		       }else{
		       		$this->error("添加失败","Shan/buy_list");
		       }
    	}
    	$data = DB::name("food_cai")->select();
    	$data = $this->getTree($data);
    	 foreach($data as $key => $value){
            $list[$key]['name'] = str_repeat('--', $value['level']).$value['name'];
            $list[$key]['id'] = $value['id'];
            $list[$key]['p_id'] = $value['p_id'];
        }
    	$this->assign("cai",$list);
    	return view();
    }
    public function buy_list(){
    	$start = I("get.start",'');
    	$end = I("get.end",'');
    	// $now = date("Y-m-d",time());
    	
    	$where="";
    	if(!empty($start)){
    		$where =" addtime >= '$start'";	
    	}
    	if(!empty($end)){
    		if(!empty($start)){
    			$where.=" and addtime <= '$end'";
    		}else{
    			$where=" addtime <= '$end'";
    		}
    	}
    	$list = DB::name("cai_list")
    			->alias("c")
    			->join("food_cai f","c.cai_id=f.id")
    			->where($where)
    			->select();
    	$this->assign('start',$start);
    	$this->assign('end',$end);
    	// $this->assign('start',empty($start) ?$now:$start);
    	// $this->assign("end",empty($end) ?$now:$end);
    	$this->assign('list',$list);
    	return view();
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