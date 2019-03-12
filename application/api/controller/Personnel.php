<?php
namespace app\api\controller;

use app\api\controller\Base;
use think\Db;

class Personnel extends Base{
    /**离职统计
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function dimission(){
        $data = Db::name("staff")
            ->join('organization a','tp_staff.garden_id = a.id')
            ->join('organization b','tp_staff.class_id = b.id')
            ->join('organization c','tp_staff.positive_post_id = c.id')
            ->field('a.id as garden_id,a.name as garden_name,b.id as class_id,b.name as class_name,c.id as post_id,c.name as post_name,staff_name,staff_status,dimission_date')
//            ->where('staff_status = '. 0)
            ->select();
        if($data){
            rData(1,"请求成功",$data);
        }else{
            rData(0,"请求失败");
        }
    }

    /***
     *离职
     */
    public function dimission_status (){
        $staff_id = I("post.");
        $staff_status = 0;
        $dimission_date = date("Y-m-d",time());
        $res = Db::name("staff")
            ->where('staff_id = '.$staff_id)
            ->save(['staff_status' => $staff_status,'$dimssion_date' => $dimission_date]);
        if($res){
            rData(1,"修改成功");
        }else{
            rData(0,"修改失败");
        }
    }

    /***在职统计
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function  in_service(){
        $data = Db::name("staff")
            ->join('organization a','tp_staff.garden_id = a.id')
            ->join('organization b','tp_staff.class_id = b.id')
            ->join('organization c','tp_staff.positive_post_id = c.id')
            ->field('a.id as garden_id,a.name as garden_name,b.id as class_id,b.name as class_name,c.id as post_id,c.name as post_name,staff_name,staff_status,dimission_date')
            ->where('staff_status = '. 1)
            ->select();
        if($data){
            rData(1,"请求成功",$data);
        }else{
            rData(0,"请求失败");
        }
    }

    /***晋升统计
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function promote(){
        $data = Db::name("staff")
            ->join('organization a','tp_staff.garden_id = a.id')
            ->join('organization b','tp_staff.class_id = b.id')
            ->join('organization c','tp_staff.positive_post_id = c.id')
            ->field('a.id as garden_id,a.name as garden_name,b.id as class_id,b.name as class_name,c.id as post_id,c.name as post_name,staff_name,staff_status,dimission_date,promote_time,promote_number')
            ->where('staff_status = '. 1)
            ->select();
        if($data){
            rData(1,"请求成功",$data);
        }else{
            rData(0,"请求失败");
        }
    }

    /***骨干生成统计
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function backbone(){
        $data = Db::name("staff")
            ->join('organization a','tp_staff.garden_id = a.id')
            ->join('organization b','tp_staff.class_id = b.id')
            ->join('organization c','tp_staff.positive_post_id = c.id')
            ->field('a.id as garden_id,a.name as garden_name,b.id as class_id,b.name as class_name,c.id as post_id,c.name as post_name,staff_name,staff_status,dimission_date,backbone_status')
            ->where('backbone_status = '. 0)
            ->select();
        if($data){
            rData(1,"请求成功",$data);
        }else{
            rData(0,"请求失败");
        }
    }

    /***
     *骨干生成
     */
    public function generate(){
        $staff_id = I("post.staff_id");
        $backbone_status = I("post.backbone_status");
        $res = Db::name("staff")
            ->where('staff_id = '. $staff_id)
            ->save('$backbone_status = '.$backbone_status);
        if($res){
            rData(1,"生成骨干成功");
        }else{
            rData(0,"生成骨干失败");
        }
    }

    /***招聘统计
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function recruit(){
        $data = Db::name("recruit")
            ->join("tp_admin_role","tp_recruit.role_id = tp_admin_role.role_id")
            ->field("recruit_id,recruit_name,recruit_time,role_name,interviewer,recruit_status")
            ->select();
        if($data){
            rData(1,"请求成功",$data);
        }else{
            rData(0,"请求失败");
        }
    }

    /***
     *招聘统计状态修改
     */
    public function recruit_save(){
        $recruit_id = I("post.recruit_id");
        $recruit_status = I("post.recruit_status");
        $res = Db::name("recruit")
            ->where('recruit_id = '. $recruit_id)
            ->save('recruit_status = '.$recruit_status);
        if($res){
            rData(1,"修改成功");
        }else{
            rData(0,"修改失败");
        }
    }
    public function back_garden(){
        $recruit_id = I("post.recruit_id");
        $data = Db::name("staff")
            ->select();
        dump($data);


    }
}