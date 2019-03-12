<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Inform extends Controller {
    /**
     * @return 展示添加通知公告
     */
    public function index(){
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign("jituan",$jituan);
        return $this->fetch();
    }

    /**
     * @return 添加通知公告
     */
    public function InformHandle(){
        return json_encode($_POST);die;
        $files = $_FILES;
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $file_name = './public/uploads/'.md5(rand(00000,1000000000).$file['name']).substr($file['name'],strpos($file['name'],'.'));
            move_uploaded_file("{$file['tmp_name']}",$file_name);
        }
        $data = I('post.');
        $data['inform_photo'] = $file_name;
        $data['addtime'] = time();
        Db::name("inform")->add($data);

        return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Inform/index')]);
    }
    public function show(){
        $data = Db::query("select * from tp_inform");
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function update(){
        $data = I('post.');
        $res = Db::query("update tp_inform set inform_status = {$data['status_id']} where inform_id = {$data['id']}");
        return json_encode(['status'=>1,'msg'=>'修改成功']);
    }
}