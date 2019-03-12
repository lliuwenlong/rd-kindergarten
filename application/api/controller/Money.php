<?php
namespace app\api\controller;
use think\Controller;
use app\api\controller\Base;
use think\Db;
//园所经营   财务金钱管理
class Money extends Base{
	//资产明细表
	public function zichan(){

		$data = DB::name("zichan")->select();
		if($data){
			rData(1,"成功",$data);
		}else{
			rData(0,"失败");
		}
	}

	//收入数据  收入管理 页面数据渲染
	public function ShouRu(){
		//花费类型  用于表单头部展示
		$data['cost_type'] = DB::name("cause")->select();
		//花费详情  数据展示
		$res= DB::name("money")->select();
		$list=[];
		foreach ($res as $key => $value) {
			$list[$value['student_id']][]=$value;
		}
		$data['data']=$list;
		rData(1,"成功",$data);
	}

}