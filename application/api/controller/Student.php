<?php
namespace app\api\controller;
use think\Controller;
use app\api\controller\Base;
use think\Db;

class Student extends Base{
    /***
     *学生添加
     */
    public function student_add(){
        $data = I("post.");
        foreach($data as $key=>$val){
            if(empty($val)){
                rData(0,$key."参数不存在",$data);
                die;
            }
        }
        $add = Db::name("student")
            ->add($data);
        if($add){
            rData(1,"添加成功");
        }else{
            rData(0,"添加失败");
        }
    }

    /***添加学生展示
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function student_get(){
        $garden = Db::name('organization')
            ->where('p_id = '.substr($this->organization_id,0,1))
            ->where("status = " . 1)
            ->select();
        if($garden){
            rData(1,"获取成功",$garden);
        }else{
            rData(0,"获取失败");
        }
    }

    /***添加学生动态读取
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function student_class(){
        $role_id = I("post.role_id");
        $class_id = I("post.class_id");
        if(!empty($role_id)){
            $data =  Db::name("organization")
                ->where("id = ".$role_id)
                ->where("status = ". 2)
                ->select();
        }else if(!empty($class_id)){
            $data =  Db::name("organization")
                ->where("p_id = ".$class_id)
                ->where("status = ". 0)
                ->select();
        }
        if($data){
            rData(1,"获取成功",$data);
        }else{
            rData(0,"获取失败");
        }
    }

    /***学生档案
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function record(){
        $data = Db::name('student')
            ->join('organization a','tp_student.garden_id = a.id',"left")
            ->join('organization b','tp_student.class_id = b.id',"left")
            ->join("student_family c","tp_student.family_id = c.id")
            ->join("techang d","tp_student.te_id = d.id")
            ->field("a.name as garden_name,tp_student.name,sex,c.tel,b.name as class_name,tp_student.status,d.name as techang_name,d.money")
            ->select();
        if($data){
            rData(1,"获取成功",$data);
        }else{
            rData(0,"获取失败");
        }
    }

    /***
     *学生档案毕业
     */
    public function student_status(){
        $student_id = I('post.student_id');
        $status =   0;
        $res = Db::name("student")
            ->where('student_id = '. $student_id)
            ->save('status = '.$status);
        if($res){
            rData(1,"修改成功");
        }else{
            rData(0,"修改失败");
        }
    }


    //学生目标生源页面展示
    public function student_source(){
    	$data = DB::name("student_source")
    					->alias("s")
    					->join("student_family f","f.id=s.family_id")
                        ->join("message_source a","s.source_id = a.id")
                        ->field("s.*,f.name as family_name,a.name as s_name")
    					->select();
    	rData(1,"成功",$data);
    }
}