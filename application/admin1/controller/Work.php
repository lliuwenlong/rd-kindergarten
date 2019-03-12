<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Work extends Controller {
    /**
     * @return 展示添加工作任务
     */
    public function index(){
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign("jituan",$jituan);
        return view();
    }
    public function AjaxJ(){
        $ji = I("post.j_id");
        $data = Db::name("organization")
            ->where('p_id = '. $ji)
            ->select();
        return json_encode($data);
    }
    /**
     * @return 添加工作任务
     */
    public function workHandle(){
        $data = I('post.');
        $data['addtime'] = time();
         Db::name("work")->add($data);

        return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Work/index')]);
    }
    public function show(){
        $data = Db::name("work")
            ->join("organization a","tp_work.ji_id = a.id",'left')
            ->join("organization b",'tp_work.garden_id = b.id','left')
            ->join("organization c",'tp_work.class_id =c.id','left')
            ->join("user",'tp_work.admin_id = user.admin_id','left')
            ->join("staff",'staff.staff_id = user.staff_id','left')
            ->field("work_id,work_title,work_desc,c.name as class_name,work_status,tp_work.addtime,staff.staff_name as user_name,a.name as jituan_name,b.name as garden_name")
            ->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function update(){
        $data = I('post.');
//        dump($data);die;
        $res = Db::query("update tp_work set work_status = {$data['status_id']} where work_id = {$data['id']}");
        return json_encode(['status'=>1,'msg'=>'修改成功']);
    }
}