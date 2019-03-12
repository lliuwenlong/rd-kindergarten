<?php
namespace app\api\controller;

use think\Controller;
use app\api\controller\Base;
use think\Db;

class Message extends Base{
    /**
     * 通知公告添加
     */
    public function notice(){
        $data = I('post.');
            $data['admin_id'] = $this->garden_id;
        $res = Db::name("inform")
            ->add($data);
        if($res){
            rData('1','添加成功');
        }else{
            rData('-1','添加失败');
        }
    }

    /***
     * 通知公告展示
     */
    public function notice_list(){
        $data = Db::name("inform")
            ->select();
        if($data){
            rData('1','获取成功',$data);
        }else{
            rData('-1','获取失败');
        }
    }

    /***
     * 通知公告修改状态
     */
    public function notice_save(){
        $inform_id = I('post.inform_id');
        $status = 1;
        $res = Db::name("inform")
            ->where("inform_id = ". $inform_id)
            ->save("inform_status = ". $status);
            if($res){
            rData('1','修改成功');
        }else{
            rData('-1','修改失败');
        }
    }

    /***工作任务展示
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function work(){
        $data = Db::name("work")->select();
        if($data){
            rData('1','获取成功',$data);
        }else{
            rData('-1','获取失败');
        }
    }
    /**
     * 工作任务添加
     */
    public function work_add(){
        $add = I("post.");
        $add['admin_id'] = $this->garden_id;
        $res = Db::name("work")
            ->add($add);
        if($res){
            rData('1','添加成功');
        }else{
            rData('-1','添加失败');
        }
    }

    /***
     * 工作任务修改
     */
    public function work_save(){
        $work_id = I("post.work_id");
        $work_status =  I("post.work_status");
        $res = Db::name("work")
            ->where("work_id = ". $work_id)
            ->save("work_status =" . $work_status);
        if($res){
            rData('1','修改成功');
        }else{
            rData('0','修改失败');
        }
    }

    /***园所动态
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function garden(){
        $data = Db::name("dynamic")
            ->select();
        if($data){
            rData('1','获取成功',$data);
        }else{
            rData('-1','获取失败');
        }
    }

    /***
     * 园所动态添加
     */
    public function garden_add(){
        $data = I("post.");
        $data['d_photo'] = $_FILES;
        $data['admin_id'] = $this->garden_id;
        $res = Db::name("dynamic")
            ->add($data);
        if($res){
            rData('1','添加成功');
        }else{
            rData('-1','添加失败');
        }
    }

    /***
     * 园所动态修改
     */
    public function garden_save(){
        $d_id = I("post.d_id");
        $d_status = I("post.d_status");
        $res = Db::name("dynamic")
            ->where("d_id = ". $d_id)
            ->save('d_status ='.$d_status);
        if($res){
            rData('1','修改成功');
        }else{
            rData('-1','修改失败');
        }
    }

}