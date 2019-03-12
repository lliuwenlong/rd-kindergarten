<?php

namespace app\api\controller;

use think\Cookie;
use think\Db;
use think\Controller;
use app\api\controller\Base;
class Administration extends Base{
    /***添加员工所需数据
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
	public function get(){
		$data['pay_rank'] = Db::name('pay_rank')->field('pay_name,pay_id')->select();
		$data['pay_standard'] = Db::name('pay_standard')->field('pay_name,pay_id,pay_price')->select();
        $data['garden'] = Db::name("organization")
            ->where('p_id ='.substr($this->organization_id,0,1))
            ->where('status = '. 1)
            ->field('id,name,p_id,category_id,addr_id')
            ->select();
        $data['role'] = Db::name('organization')
            ->where('p_id ='.substr($this->organization_id,0,1))
            ->where('status ='. 0)
            ->select();
        if($data){
            rData(1,"获取成功",$data);
        }else{
            rData(0,"获取失败");
        }
	}

    /***
     *添加员工
     */
	public function staff_add(){
		$data = I("post.");
		$res = Db::name("staff")->add($data);
		if($res){
            rData(1,"获取成功");
		}else{
            rData(0,"获取失败");
		}
	}

    /***动态获取园所下班级
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function class_get(){
	    $garden_id = I("post.garden_id");
        $class = Db::name("organization")
            ->where('p_id ='.$garden_id)
            ->where('status = '. 2)
            ->select();
        if($class){
            rData(1,"获取成功",$class);
        }else{
            rData(0,"获取失败");
        }
    }

    /***动态获取班级下员工或园所下员工
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function role_get(){
	    $class_id = I('post.class_id');
	    $garden_id = I('post.garden_id');
	    if(!empty($class_id)){
            $role = Db::name('organization')
                ->where('p_id ='.$class_id)
                ->where('status ='. 0)
                ->select();
        }else if(!empty($garden_id)){
            $role = Db::name('organization')
                ->where('p_id ='.$garden_id)
                ->where('status ='. 0)
                ->select();
        }
        if($role){
            rData(1,"获取成功",$role);
        }else{
            rData(0,"获取失败");
        }
    }
    /***员工离职
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
	public function staff_del(){
		$id = I("post.id");
		$res = Db::name("staff")->where("post_id = $id")->delete();
        if($res){
            rData(1,"请求成功");
        }else{
            rData(0,"请求失败");
        }
	}

    /***
     *
     */
	public function staff_update(){
		$id = I("post.id");
		$test = I("post");
		$res = Db::name("staff")->where("post_id = $id")->save($test);
        if($res){
            rData(1,"请求成功");
        }else{
            rData(0,"请求失败");
        }
	}

    /***员工信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
	public function staff_get(){
        $data = Db::name("staff")
            ->join('tp_pay_rank','tp_pay_rank.pay_id = tp_staff.pay_rank_id')
            ->join('tp_pay_standard','tp_pay_standard.pay_id = tp_staff.pay_standard_id')
            ->field('tp_pay_rank.pay_name as rank_name,tp_pay_standard.pay_name as standard_name,staff_id,staff_name,staff_status,time,entry_date')
            ->select();
       if($data){
           rData(1,"请求成功",$data);
       }else{
           rData(0,"请求失败");
       }
    }
}
?>