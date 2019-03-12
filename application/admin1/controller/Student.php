<?php
/**
 * 学生管理控制器
 */
namespace app\admin\controller; 
use think\AjaxPage;
use think\Controller;
use think\Url;
use think\Config;
use think\Page;
use think\Verify;
use think\Db;
class Student extends Base {
    /**
     * @return 学生档案
     */
    public function index(){
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
        $list = Db::name("student")
            ->join("organization a","tp_student.ji_id = a.id",'left')
            ->join("organization b","tp_student.garden_id = b.id",'left')
            ->join("organization c","tp_student.class_id = c.id",'left')
            ->join("message_source m","m.id=tp_student.source_id",'left')
            ->join("student_family d","tp_student.family_id = d.id",'left')
            ->field('tp_student.name as student_name,tp_student.sex,d.tel,a.name as ji_name,b.name as garden_name,c.name as class_name,zhaoteacher,tuo,can,xiao,qi,tp_student.id,tp_student.status')
            ->select();
    	$this->assign("list",$list);
    	return view();

    }
    public function getstudent(){
        $id = $_GET['id'];
        $list = Db::name("student")
            ->alias("s")
            ->join("organization a","s.ji_id = a.id",'left')
            ->join("organization b","s.garden_id = b.id",'left')
            ->join("organization c","s.class_id = c.id",'left')
            ->join("message_source m","m.id=s.source_id",'left')
            ->join("student_family d","s.family_id = d.id",'left')
            ->where("s.id",$id)
            ->field("s.*,s.id as s_id,a.id as ji_id,s.place as jiguan,b.id as garden_id,c.id as class_id,a.name as ji_name,b.name as garden_name,c.name as class_name,m.id as source_id,m.name as source_name,d.id as f_id,d.name as f_name,d.*")
            ->find();
//        dump($list);die;
            $jituan = Db::name("organization")
                ->where("p_id = 0")
                ->select();
            $message_source=DB::name("message_source")->select();
            $this->assign("message_source",$message_source);
            $this->assign("jituan",$jituan);
            $this->assign("info",$list);
            return view("student/student_add");
    }
    public function getstudentData(){
        $arr = $_POST['arr'];
//        dump($arr);die;
        $where ='1=1';
        foreach($arr as $key=>$val){
            if($val['id']!=0){
                $where .=" and tp_student.".$val['type']."=".$val['id'];
            }

        }
        $list = Db::name("student")
            ->join("organization a","tp_student.ji_id = a.id",'left')
            ->join("organization b","tp_student.garden_id = b.id",'left')
            ->join("organization c","tp_student.class_id = c.id",'left')
            ->join("message_source m","m.id=tp_student.source_id",'left')
            ->join("student_family d","tp_student.family_id = d.id",'left')
            ->where($where)
            ->field('tp_student.name as student_name,tp_student.sex,d.tel,a.name as ji_name,b.name as garden_name,c.name as class_name,zhaoteacher,tuo,can,xiao,qi,tp_student.id,tp_student.status')
            ->select();
        return json_encode($list);

    }
    /**
     * @return 添加学生
     */
    public function student_add(){
            $id = $_GET['id'];
            $list = Db::name("student_source")
                ->join("organization a","tp_student_source.ji_id = a.id")
                ->join("organization b","tp_student_source.garden_id = b.id")
                ->join("organization c","tp_student_source.class_id = c.id")
                ->join("message_source m","m.id=tp_student_source.source_id")
                ->join("student_family d","tp_student_source.family_id = d.id")
                ->field("a.name as ji_name,a.id as ji_id,b.name as garden_name,b.id as garden_id,c.name as class_name,c.id as class_id,m.name as source_name,tp_student_source.id,tp_student_source.name,tp_student_source.sex,tp_student_source.birthday,tp_student_source.status,tp_student_source.addtime,d.id as f_id,idea,d.name as f_name,d.relation,unit,xueli,tel,wechat,tp_student_source.source_id,tuo,qi,can,xiao,tuo_zhou,can_zhou,qi_zhou,xiao_zhou,jiguan,tp_student_source.home,m.id as m_id")
                ->where("tp_student_source.id",$id)
                ->find();
            $jituan = Db::name("organization")
                ->where("p_id = 0")
                ->select();
            $message_source=DB::name("message_source")->select();
            $this->assign("message_source",$message_source);
            $this->assign("jituan",$jituan);
            $this->assign("info",$list);
            return view();

    }
    public function add_student(){
        $data =$_POST;
        if(!empty($data['id'])){
            if(empty($data['backgarden'])){
                $this->error("请填写退园原因");die;
            }
            $data['status'] = 2;
            $res = Db::name("student")
                ->where("id",$data['id'])
                ->save($data);
            if($res){
                $this->success("修改成功",'Student/index');die;
            }else{
                $this->error("修改失败");die;
            }
        }
        $data['addtime'] = date("Y-m-d",time());
        $res = Db::name("student")
            ->add($data);
        $this->redirect("Student/index");

    }

    public function student_source(){
    	$keywords = I('keywords/s');
		$list = Db::name("student_source")
            ->join("organization a","tp_student_source.ji_id = a.id","left")
            ->join("organization b","tp_student_source.garden_id = b.id","left")
            ->join("organization c","tp_student_source.class_id = c.id","left")
            ->join("message_source m","m.id=tp_student_source.source_id","left")
            ->join("user f","f.admin_id = tp_student_source.admin_id",'left')
            ->join("student_family d","tp_student_source.family_id = d.id","left")
            ->field("f.user_name,a.name as ji_name,b.name as garden_name,c.name as class_name,m.name as source_name,tp_student_source.id,tp_student_source.name,tp_student_source.sex,tp_student_source.birthday,tp_student_source.status,tp_student_source.addtime,idea,d.name as f_name,d.relation,unit,xueli,d.tel,wechat")
            ->select();
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
//    	$garden = DB::name("garden")->select();
//    	$class = Db::name("class")->select();
//    	$list = DB::name("student_source")
//    			->alias("s")
//    			->join("student_family f","f.id=s.family_id")
//    			->join("message_source m","m.id=s.source_id")
//    			->where('s.name','like','%'.$keywords.'%')
//    			->order('id desc')
//    			->field("s.name,sex,birthday,f.name as family_name,tel,status,s.id,m.name as source_name,idea")
//    			->select();
//    	$this->assign("garden",$garden);
//    	$this->assign("class",$class);
    	$this->assign("list",$list);
    	return view();
    }

    /**
     * @return 添加生源
     */
    public function dataHandle(){

    	$garden_id = I("get.garden_id");
    	if($garden_id=='0'){
    		$list = DB::name("student_source")
    			->alias("s")
    			->join("student_family f","f.id=s.family_id")
    			->join("message_source m","m.id=s.source_id")
    			->order('id desc')
    			->field("s.name,sex,birthday,f.name as family_name,tel,status,s.id,m.name as source_name,idea")
    			->select();
    	}else{
    		$list = DB::name("student_source")
    			->alias("s")
    			->join("student_family f","f.id=s.family_id")
    			->join("message_source m","m.id=s.source_id")
    			->where('s.garden_id',$garden_id)
    			->order('id desc')
    			->field("s.name,sex,birthday,f.name as family_name,tel,status,s.id,m.name as source_name,idea")
    			->select();
    	}
    	
    	return json_encode($list);
    }
    public function getsourceData(){
        $arr = $_POST['arr'];
//        dump($arr);die;
        $where ='1=1';
        foreach($arr as $key=>$val){
            if($val['id']!=0){
                $where .=" and tp_student_source.".$val['type']."=".$val['id'];
            }

        }
        $list = Db::name("student_source")
            ->join("organization a","tp_student_source.ji_id = a.id","left")
            ->join("organization b","tp_student_source.garden_id = b.id","left")
            ->join("organization c","tp_student_source.class_id = c.id","left")
            ->join("message_source m","m.id=tp_student_source.source_id","left")
            ->join("student_family d","tp_student_source.family_id = d.id","left")
            ->where($where)
            ->field("a.name as ji_name,b.name as garden_name,c.name as class_name,m.name as source_name,tp_student_source.id,tp_student_source.name,tp_student_source.sex,tp_student_source.birthday,tp_student_source.status,tp_student_source.addtime,idea,d.name as f_name,d.relation,unit,xueli,tel,wechat")
            ->select();
        return json_encode($list);
    }
     public function dataHandleClass(){
    	$class_id = I("get.class_id");
    	if($class_id=='0'){
    		$class = DB::name("class")->select();
    			$list = DB::name("student_source")
    			->alias("s")
    			->join("student_family f","f.id=s.family_id")
    			->join("message_source m","m.id=s.source_id")
    			->order('id desc')
    			->field("s.name,sex,birthday,f.name as family_name,tel,status,s.id,m.name as source_name,idea")
    			->select();
    		return json_encode(['type'=>'1','data'=>$class,'list'=>$list]);
    	}
    	if(strpos($class_id,"_")){
    		
    		$data = DB::name("garden_class")
    			->where('id',substr($class_id,strpos($class_id,"_")+1))
    			->find();
    		$list = DB::name("student_source")
    			->alias("s")
    			->join("student_family f","f.id=s.family_id")
    			->join("message_source m","m.id=s.source_id")
    			->where('s.class_id',$data['class_id'])
    			->where("s.garden_id",$data['garden_id'])
    			->order('id desc')
    			->field("s.name,sex,birthday,f.name as family_name,tel,status,s.id,m.name as source_name,idea")
    			->select();
    	}else{
    		
    		$list = DB::name("student_source")
    			->alias("s")
    			->join("student_family f","f.id=s.family_id")
    			->join("message_source m","m.id=s.source_id")
    			->where('s.class_id',$class_id)
    			->order('id desc')
    			->field("s.name,sex,birthday,f.name as family_name,tel,status,s.id,m.name as source_name,idea")
    			->select();
    	}
    	
    	return json_encode($list);
    }
    public function txsy(){
        $id = $_GET['id'];
        
    }
    public function ClassHandletwo(){
    	$garden_id = I("get.garden_id");
    	if($garden_id=='0'){
    		$class = DB::name("class")->select();
    	}else{

    		$class = DB::name("garden_class")->alias("gc")->join("class c","c.class_id=gc.class_id")->where("garden_id",$garden_id)->select();
    	}
    	return json_encode($class);
    }
    /**
     * [ClassHandle description]
     * 处理园所 班级  之间的联动
     */
    public function ClassHandle(){
    	$garden_id = I("get.garden_id");
    	
    	$class = DB::name("garden_class")->alias("gc")->join("class c","c.class_id=gc.class_id")->where("garden_id",$garden_id)->select();



    	return json_encode($class);

    }


    public function source_del(){
    	$id = I("get.id");
    	DB::name("student_source")->delete($id);
    	$this->redirect("Student/student_source");
    }
    public function AjaxJ(){
        $ji = I("post.j_id");
        $data = Db::name("organization")
            ->where('p_id = '. $ji)
            ->select();
        return json_encode($data);
    }
    public function AjaxGarden(){
        $garden_id = I("post.garden_id");
        $data = Db::name("organization")
            ->where("p_id",$garden_id)
            ->where("status = 2")
            ->select();
        return json_encode($data);
    }
    public function source_add(){
    	if(IS_POST){
    	    $data = I("post.");
    	    $data['addtime'] = date("Y-m-d",time());
            $student_family['name']=$data['family_name'];
            $student_family['relation']=$data['relation'];
            $student_family['unit']=$data['unit'];
            $student_family['xueli']=$data['xueli'];
            $student_family['wechat']=$data['wechat'];
            $student_family['tel']=$data['tel'];
            $family_id=DB::name("student_family")->insertGetId($student_family);
            $data['family_id'] =$family_id;
            $data['status'] = $data['go'];
            $res = DB::name("student_source")->insert($data);
            $this->redirect("Student/student_source");
    	}else{
            $jituan = Db::name("organization")
                ->where("p_id = 0")
                ->select();
            $this->assign("jituan",$jituan);
    		$message_source=DB::name("message_source")->select();
	    	$this->assign("message_source",$message_source);
	    	return view();
    	}
    	
    }

}