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
//        dump($data);
        return view();
    }
    public function getsingleData(){
        $arr = $_POST['arr'];
//        dump($arr);

        foreach ($arr as $key => $value) {
          $res[$value['type']]=$value['id'];
        }


        $data = DB::name("organization")->where("p_id",0)->select();
        foreach ($data as $key => $value){
            $re =  DB::name("organization")->where("p_id",$value['id'])->where("status",1)->select();
            $data[$key]['garden']=$re;
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

        if($res['garden_id']==0){
           foreach ($data as $k=>$v){
               if($v['id']!=$res['ji_id']){
                   unset($data[$k]);
               }
           }
//           dump($data);die;
        }else{
            $ji_id = DB::name("organization")->where("id",$res['garden_id'])->column("p_id");
           $ji_id=$ji_id[0];

            foreach($data as $key=>&$value){
                if($value['id']!=$ji_id){
                   unset($data[$key]);
                }
                foreach ($value['garden'] as $k=>&$v){
                    if($v['id']!=$res['garden_id']){
                        unset($data[$key]['garden'][$k]);
                    }
                }
            }
//            echo 222;
//            dump($data);die;
        }

//    dump($data);die;
        return json_encode($data);
    }
    public function Finance(){
        return view();
    }
}



?>