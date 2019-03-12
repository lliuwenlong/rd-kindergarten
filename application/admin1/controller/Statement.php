<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
class Statement extends Controller{
    public function single(){
        //获取每个集团下的园所
        $data = DB::name("organization")->where("p_id",0)->select();
        foreach ($data as $key => $value){
                $res =  DB::name("organization")->where("p_id",$value['id'])->where("status",1)->select();
               $data[$key]['garden']=$res;
        }

       foreach($data as $key=>&$value){
           foreach($value['garden'] as $k=>&$v){
               $g = DB::name("money")->where("garden_id",$v['id'])->column("addtime");
               $t = array_unique($g);
               if(!empty($t)){
                   $id = $v['id'];
                   foreach($t as $ke=>$val){
                       $v['son'][$val]['shou']=DB::name("money")->where("garden_id",$id)->where("addtime",$val)->where("ie_status",1)->column("sum(money)");
                       $v['son'][$val]['chu']=DB::name("money")->where("garden_id",$id)->where("addtime",$val)->where("ie_status",0)->column("sum(money)");
                   }
               }else{
                   $v['son']=[];
               }
           }
       }
        $ji=DB::name("organization")->where("p_id",0)->select();
        $where['ji']=$ji;
        $this->assign("where",$where);
        $this->assign("list",$data);
        return view();
    }
    public function Finance(){
        return view();
    }
}



?>