<?php
namespace app\admin\controller;
use think\Controller;
use think\AjaxPage;
use think\Db;
class Dynamic extends Controller {
    /**
     * @return 展示添加园所动态
     */
    public function index(){
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign("jituan",$jituan);
        return $this->fetch();
    }

    public function AjaxJ(){
        $ji = I("post.j_id");
        $data = Db::name("organization")
            ->where('p_id = '. $ji)
            ->select();
        return json_encode($data);
    }
    /**
     * @return 添加园所动态
     */
    public function DynamicHandle(){
        $files = $_FILES;
//        return json_encode($files);die;
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $file_name = './public/uploads/'.md5(rand(00000,1000000000).$file['name']).substr($file['name'],strpos($file['name'],'.'));
            move_uploaded_file("{$file['tmp_name']}",$file_name);
        }
        $data = I('post.');
        $data['d_photo'] = $file_name;
        $data['addtime'] = time();
        Db::name("dynamic")->add($data);

        return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Dynamic/index')]);
    }
    public function show(){
        $data = Db::name("dynamic")
            ->join("organization a","tp_dynamic.ji_id = a.id",'left')
            ->join("organization b",'tp_dynamic.garden_id = b.id','left')
            ->join("user",'tp_dynamic.admin_id = user.admin_id','left')
            ->join("staff",'staff.staff_id = user.staff_id','left')
            ->field("d_id,d_title,d_desc,d_status,tp_dynamic.addtime,staff.staff_name as user_name,d_photo,a.name as jituan_name,b.name as garden_name")
            ->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function update(){
        $data = I('post.');
        $res = Db::query("update tp_dynamic set d_status = {$data['status_id']} where d_id = {$data['id']}");
        return json_encode(['status'=>1,'msg'=>'修改成功']);
    }

}