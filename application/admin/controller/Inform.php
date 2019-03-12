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

        $files = $_FILES;
        if($files['d_photo']['error']!=0){
            return json_encode(['status'=>0,'msg'=>'请上传图片']);
        }
//        dump($files);
        $data = I('post.');
//        dump($data);die;
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $file_name = './public/uploads/'.md5(rand(00000,1000000000).$file['name']).substr($file['name'],strpos($file['name'],'.'));
            move_uploaded_file("{$file['tmp_name']}",$file_name);
        }

        $data['inform_photo'] = $file_name;
        $data['addtime'] = time();
       $res = Db::name("inform")->add($data);
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Inform/show')]);
        }else{
            return json_encode(['status'=>0,'msg'=>'操作失败']);
        }

    }
    public function show(){
        $data  = Db::name("inform")
            ->alias("i")
            ->join('organization b','i.garden_id = b.id','left')
            ->join("organization c",'i.ji_id = c.id','left')
            ->field("i.*,b.name as g_name,c.name as j_name")
            ->select();
//        dump($data);die;
        $this->assign('data',$data);
        $ji=Db::name("organization")->where("p_id",0)->select();
        $this->assign("ji",$ji);
        return $this->fetch();
    }
    //ajax获取动态条件筛选
    public function getData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and i.".$value['type']."=".$value['id'];
            }
        }

        $data  = Db::name("inform")
            ->alias("i")
            ->join('organization b','i.garden_id = b.id','left')
            ->join("organization c",'i.ji_id = c.id','left')
            ->where($where)
            ->field("i.*,b.name as g_name,c.name as j_name")
            ->select();
        return json_encode($data);
    }
    public function update(){
        $data = I('post.');
        $res = Db::query("update tp_inform set inform_status = {$data['status_id']} where inform_id = {$data['id']}");
        return json_encode(['status'=>1,'msg'=>'修改成功']);
    }
}