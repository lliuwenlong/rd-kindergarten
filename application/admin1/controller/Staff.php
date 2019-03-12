<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
// use think\db;

class  Staff extends Controller{
    /**
     * @return 添加员工
     */
    public function index(){
//        echo base64_encode(md5(123456789));die;
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $garden = Db::name("organization")
            ->where("status = 1")
            ->select();
        $class = Db::name("organization")
            ->where("status = 2")
            ->select();
        $post = Db::name("organization")
            ->where("status = 0")
            ->select();
        $data = Db::query("select role_id,pay_id from tp_pay_type");
        foreach($data as $key => $val){
            $list[] = Db::query("select role_name from tp_admin_role where role_id in({$val['role_id']})");
            $li[] = array_column($list[$key],'role_name');
        }
        foreach ($li as $k => $v){
            $data[$k]['role_name'] = implode("、",$v);
        }

        $rank = Db::table("tp_pay_rank")->where('type_id',$data[0]['pay_id'])->field('pay_id,pay_name')->select();
//        dump($data);die;
        $standard = Db::table("tp_pay_standard")->field('pay_id,pay_name,pay_price')->where('rank_id',$rank[0]['pay_id'])->select();
//        $role = Db::query("select role_name,role_id from tp_admin_role where role_name != '投资人' and role_name != '超级管理员' and role_name != '家长'");
//        $this->assign("role",$role);
        $this->assign("jituan",$jituan);
        $this->assign('rank',$rank);
        $this->assign("standard",$standard);
        $this->assign("data",$data);
        $this->assign('info',$garden);
        $this->assign('class',$class);
        $this->assign('post',$post);
        return $this->fetch();
    }
    //删除
    public function staff_del(){
        $data = I("post.");
//        dump($data);die;
        $res = Db::name($data['table'])->where("staff_id = {$data['id']}")->delete();
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功']);
        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }
    }
    //详情
    public function desc(){
        $id = I("get.id");
        $data = Db::name("staff")
            ->join('organization a','tp_staff.o_id = a.id','left')
            ->join('organization b','tp_staff.class_id = b.id','left')
            ->join("organization c",'tp_staff.garden_id = c.id','left')
            ->join('organization d','tp_staff.ji_id = d.id','left')
            ->join('pay_rank e','tp_staff.pay_rank_id = e.pay_id','left')
            ->join('pay_standard f','tp_staff.pay_standard_id = f.pay_id','left')
            ->join("pay_type g",'tp_staff.pay_id = g.pay_id','left')
            ->field("tp_staff.*,a.name as post_name,b.name as class_name,g.role_id,c.name as garden_name,d.name as ji_name,e.pay_name as r_name,f.pay_name as standard_name")
            ->where("staff_id",$id)
            ->find();
//        foreach ($data as &$val){
//        dump($data['role_id']);
            $data['role_id']= Db::name("organization")->where("id","in","{$data['role_id']}")->field("name")->select();
//        }
//        array(34) {
//            ["staff_id"] => int(1)
//            ["staff_name"] => string(6) "张三"
//            ["staff_sex"] => int(1)
//            ["staff_date"] => string(10) "2019-01-01"
//            ["staff_tel"] => string(11) "45545445544"
//            ["staff_address"] => string(6) "海淀"
//            ["education_status"] => int(1)
//            ["staff_education"] => string(6) "本科"
//            ["teaching_status"] => int(1)
//            ["entry_date"] => string(10) "2019-01-09"
//            ["garden_id"] => int(8)
//            ["class_id"] => int(11)
//            ["position_id"] => int(2)
//            ["pay_id"] => int(2)
//            ["pay_rank_id"] => int(2)
//            ["pay_standard_id"] => int(4)
//            ["positive_date"] => string(10) "2019-01-16"
//            ["transfer_date"] => NULL
//            ["dimission_date"] => string(10) "2019-01-18"
//            ["positive_post_id"] => int(1)
//            ["staff_status"] => int(1)
//            ["time"] => NULL
//            ["promote_time"] => NULL
//            ["promote_number"] => int(0)
//            ["backbone_status"] => int(1)
//            ["o_id"] => int(12)
//            ["ji_id"] => int(1)
//            ["post_name"] => string(12) "主班老师"
//            ["class_name"] => string(9) "小一班"
//            ["role_id"] => array(2) {
//                [0] => array(1) {
//                    ["name"] => string(15) "光明幼儿园"
//    }
//    [1] => array(1) {
//                    ["name"] => string(16) "辽宁幼儿园1"
//    }
//  }
//  ["garden_name"] => string(21) "明日之星幼儿园"
//            ["ji_name"] => string(12) "明日之星"
//            ["r_name"] => string(4) "2级"
//            ["standard_name"] => string(4) "4挡"
//}
        $this->assign("data",$data);
        return view();
    }
    public function AjaxJG(){
        $ji = I("post.j_id");
        $data = Db::name("organization")
            ->where("p_id = $ji")
            ->select();
        return json_encode($data);
    }
    public function AjaxJ(){
        $ji = I("post.j_id");
        $data = Db::name("organization")
            ->where('p_id = '. $ji)
            ->select();
        return json_encode($data);
    }
    /***
     * @return ajax动态读取级别和标准
     */
    public function rank(){
        $data = I('post.');
        $data['rank'] = Db::table("tp_pay_rank")->where('type_id',$data['pay_id'])->field('pay_id,pay_name')->select();
        $data['standard'] = Db::table("tp_pay_standard")->field('pay_id,pay_name,pay_price')->where('rank_id',$data['rank'][0]['pay_id'])->select();
        return json_encode($data);
    }
    /***
     * @return 展示班级
     */
    public function garden(){
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
        $data = Db::name("organization")
            ->alias("a")
            ->join("organization b",'a.p_id = b.id','left')
            ->field('a.name as garden_name,b.name as ji_name,a.id')
            ->where("a.status = 1")
            ->select();
        $class = Db::name("organization")
            ->alias("a")
            ->join("organization b",'a.p_id = b.id','left')
            ->join("organization c",'b.p_id = c.id','left')
            ->field('a.id,a.name as class_name,b.name as garden_name,c.name as ji_name')
            ->where("a.status",2)
            ->select();
        $this->assign('garden',$data);
        $this->assign('class',$class);
        return $this->fetch();
    }

    /**
     * @return 展示职位
     */
    public function position(){
        $data = Db::name("organization")
            ->alias('a')
            ->join('organization b','a.p_id = b.id','left')
            ->join('organization c','b.p_id = c.id','left')
            ->join('organization d','c.p_id = d.id','left')
            ->where("a.status = 0")
            ->field("a.id,a.name as post_name,b.name as class_name,c.name as garden_name,d.name as ji_name")
            ->select();
        foreach ($data as $key => &$val){
            if(empty($val['ji_name'])){
                $val['ji_name'] = $val['garden_name'];
                if($val['ji_name'] == $val['garden_name']){
                    $val['garden_name'] = $val['class_name'];
                    unset($val['class_name']);
                }
            }
            if(empty($val['ji_name'])){
                $val['ji_name'] = $val['garden_name'];
                unset($val['garden_name']);
            }
        }
        $this->assign('post',$data);
        return $this->fetch();
    }

    /***
     * @return 员工信息
     */
    public function show(){
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
        $data = Db::name("staff")
            ->join('organization a','tp_staff.o_id = a.id','left')
            ->join('organization b','tp_staff.class_id = b.id','left')
            ->join("organization c",'tp_staff.garden_id = c.id','left')
            ->join('organization d','tp_staff.ji_id = d.id','left')
            ->join('pay_rank e','tp_staff.pay_rank_id = e.pay_id','left')
            ->join('pay_standard f','tp_staff.pay_standard_id = f.pay_id','left')
            ->field("staff_id,staff_name,staff_sex,staff_date,staff_tel,staff_address,education_status,staff_education,teaching_status,entry_date,c.name as garden_name,b.name as class_name ,a.name as post_name,d.name as j_name,e.pay_name as rank_name,f.pay_name as standard_name,staff_status")
            ->select();
//        dump($data);die;
        $this->assign('list',$data);
        return $this->fetch();
//            ->join("tp_pay_type","tp_staff.pay_id = tp_pay_type.pay_id")
//            ->join("tp_pay_rank","tp_staff.pay_rank_id = tp_pay_rank.pay_id")
//            ->join("tp_pay_standard","tp_staff.pay_standard_id = tp_pay_standard.pay_id")
//            ->join("tp_post b","tp_staff.positive_post_id = b.post_id")
//            ->field("staff_name,tp_staff.staff_id,time,staff_status,class_name,garden_name,tp_pay_rank.pay_name as rank_name,tp_pay_standard.pay_name as standard_name,a.post_name,b.post_name as positive_post,staff_sex,staff_date,staff_tel,staff_address,education_status,staff_education,teaching_status,entry_date,positive_date,transfer_date,dimission_date")
//            ->select();
//        $this->assign("list",$data);
//        return $this->fetch();
    }

    //ajax帅选条件获取员工信息数据
    public function getstaffData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
                if($value['id']!=0){
                    $where.=" and tp_staff.".$value['type']."=".$value['id'];
                }
        }
//        echo $where;
//        dump($arr);die;
        $data = Db::name("staff")
            ->join('organization a','tp_staff.o_id = a.id','left')
            ->join('organization b','tp_staff.class_id = b.id','left')
            ->join("organization c",'tp_staff.garden_id = c.id','left')
            ->join('organization d','tp_staff.ji_id = d.id','left')
            ->join('pay_rank e','tp_staff.pay_rank_id = e.pay_id','left')
            ->join('pay_standard f','tp_staff.pay_standard_id = f.pay_id','left')
            ->where($where)
            ->field("staff_id,staff_name,staff_sex,staff_date,staff_tel,staff_address,education_status,staff_education,teaching_status,entry_date,c.name as garden_name,b.name as class_name ,a.name as post_name,d.name as j_name,e.pay_name as rank_name,f.pay_name as standard_name,staff_status")
            ->select();
        return json_encode($data);

    }

    /**
     * @return 晋升统计
     */
    public function promote(){
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->join("organization d","tp_staff.ji_id = d.id",'left')
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,d.name as ji_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number")
            ->where("staff_status",1)
            ->select();
//            dump($data);die;
        $this->assign("list",$data);
        return $this->fetch();
    }
    public function getpromoteData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and tp_staff.".$value['type']."=".$value['id'];
            }
        }

        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->join("organization d","tp_staff.ji_id = d.id",'left')
            ->where($where)
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,d.name as ji_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number")
            ->where("staff_status",1)
            ->select();
        return json_encode($data);
    }

    /***
     * @return 离职统计
     */
    public function dimission(){
        if(request()->isAjax()){
            $data = I("post.");
            $staff_id = $data['staff_id'];
            $staff_status = $data['staff_status'];
            $dimission_date = date("Y-m-d",time());
            $res = Db::query("update tp_staff set staff_status = $staff_status,dimission_date = '$dimission_date' where staff_id = $staff_id ");
            if(!empty($res)){
                return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }else{
                return json_encode(['status'=>1,'msg'=>'操作成功','data'=>$dimission_date]);
            }
        }
        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->join("organization d","tp_staff.ji_id = d.id",'left')
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number,d.name as ji_name")
            ->select();
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;

        $this->assign("where",$where);
//        dump($data);die;
        $this->assign(  "list",$data);
        return $this->fetch();
    }

    public function getdimissionData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and tp_staff.".$value['type']."=".$value['id'];
            }
        }

        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->join("organization d","tp_staff.ji_id = d.id",'left')
            ->where($where)
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number,d.name as ji_name")
            ->select();
        return json_encode($data);
    }

    /***
     * @return 考勤统计
     */
    public function attendance(){
        $data = Db::name("staff_clocking_in")
            ->alias("s")
            ->join("staff a","a.staff_id = s.staff_id",'left')
            ->join("organization b","a.ji_id = b.id",'left')
            ->join("organization c","a.garden_id = c.id",'left')
            ->join("organization d","a.class_id = d.id",'left')
            ->field("a.*,a.staff_name as name,a.staff_id as id,s.status,s.time,b.name as ji_name,c.name as garden_name,d.name as class_name")
            ->select();
//        dump($data);die;
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;
        $this->assign("where",$where);
        $this->assign("list",$data);
        return $this->fetch();
    }
    //考勤统计 ajax获取数据
    public function getaddendanceData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and a.".$value['type']."=".$value['id'];
            }
        }
//        echo $where;
        $data = Db::name("staff_clocking_in")
            ->alias("s")
            ->join("staff a","a.staff_id = s.staff_id",'left')
            ->join("organization b","a.ji_id = b.id",'left')
            ->join("organization c","a.garden_id = c.id",'left')
            ->join("organization d","a.class_id = d.id",'left')
            ->where($where)
            ->field("a.*,a.staff_name as name,a.staff_id as id,s.status,s.time,b.name as ji_name,c.name as garden_name,d.name as class_name")
            ->select();
        return json_encode($data);
    }

    /***
     * @return 在职统计
     */
    public function job(){
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;
        $this->assign("where",$where);
        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->join("organization d","tp_staff.ji_id = d.id",'left')
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,d.name as ji_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number")
            ->where("staff_status",1)
            ->select();
        $this->assign("list",$data);
        return $this->fetch();
    }
    //ajax获取在职统计数据
    public function getjobData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and tp_staff.".$value['type']."=".$value['id'];
            }
        }

        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->join("organization d","tp_staff.ji_id = d.id",'left')
            ->where($where)
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,d.name as ji_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number")
            ->where("staff_status",1)
            ->select();
        return json_encode($data);
    }

    /***
     * @return 骨干生成
     */
    public function backbone(){
        if(request()->isAjax()){
            $data = I("get.");
            $res = Db::query("update tp_staff set backbone_status = {$data['backbone_status']} where staff_id = {$data['staff_id']}");
            if($res){
                return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }else{
                return json_encode(['status'=>1,'msg'=>'操作成功']);
            }
        }
        $ji=DB::name("organization")->where("p_id",0)->select();
        $garden = DB::name("organization")->where("status",1)->select();
        $class= DB::name("organization")->where("status",2)->select();
        $post =DB::name("organization")->where("status",0)->select();
        // dump($data);die;
        $type = DB::name("assess_type")->where("status",0)->select();
        $where['ji']=$ji;
        $where['garden']=$garden;
        $where['class']=$class;
        $where['post']=$post;
        $where['type']=$type;
        $this->assign("where",$where);
        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->field("staff_id,staff_name,entry_date,c.name as post_name,a.name as garden_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number")
            ->where("staff_status",1)
            ->where("backbone_status",0)
            ->select();
        $this->assign("list",$data);
        return $this->fetch();
    }
    //ajax获取骨干内容
    public function getbackboneData(){
        $arr = $_POST['arr'];
        $where = "1=1";
        foreach ($arr as $key => $value) {
            if($value['id']!=0){
                $where.=" and tp_staff.".$value['type']."=".$value['id'];
            }
        }

        $data = Db::name("staff")
            ->join("organization a","tp_staff.garden_id = a.id",'left')
            ->join("organization b","tp_staff.class_id = b.id",'left')
            ->join("organization c","tp_staff.o_id = c.id",'left')
            ->field("staff_id,staff_name,c.name as post_name,a.name as garden_name,b.name as class_name,dimission_date,staff_status,promote_time,promote_number")
            ->where($where)
            ->where("staff_status",1)
            ->where("backbone_status",0)
            ->select();
        return json_encode($data);
    }

    /**
     * @return AJAX动态获取班级
     */
    public function AjaxGarden(){
        $garden_id = I("post.garden_id");
        $data = Db::name("organization")
            ->where("p_id",$garden_id)
            ->where("status = 2")
            ->select();
        return json_encode($data);
    }
    public function AjaxP(){
        $id = I("post.id");
        $data = Db::name("organization")
            ->where("p_id",$id)
            ->where("status = 0")
            ->select();
        return json_encode($data);
    }
    /**
     * @return 添加员工
     */
    public function IndexHandle(){
        $data = I("post.");
        $res = Db::name('staff')->add($data);
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/index')]);
        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }

    }
    /**
     * @return 展示部门
     */
    public function branch(){
        $list = Db::name("organization")
            ->alias("a")
            ->join("organization b",'a.p_id = b.id','left')
            ->field("a.name,b.name as p_name,b.id,a.id")
            ->where("a.status = 3")
            ->select();
//        dump($list);die;
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * @return 添加部门展示
     */
    public function branch_info(){
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign("jituan",$jituan);
        return $this->fetch();
    }

    /***
     * @return 添加职位展示
     */
    public function position_info(){
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign('jituan',$jituan);
        return $this->fetch();
    }

    /**
     * @return 添加班级展示
     */
    public function class_info(){
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign('jituan',$jituan);
        return $this->fetch();
    }

    /***
     * @return 添加部门
     */
    public function branchHandle(){
        $data = I('post.');
        if(!empty($data['ji_id'])){
            $res = Db::name("organization")
                ->add(['name'=>$data['branch_name'],'p_id'=>$data['ji_id'],'addtime'=>date("Y-m-d",time()),'status'=>1,'branch'=>1]);
        }else{
            return json_encode(['status'=>0,'msg'=>'请选择集团']);
        }
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/branch')]);
        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }

    }
    public function garden_info(){
        if(request()->isAjax()){
            $data = $_POST;
            if(empty($data['ji_id'])){
                return json_encode(['status'=>0,'msg'=>'请认真填写']);
            }
            $data['p_id'] = $data['ji_id'];
            $data['status'] = 1;
            $res = Db::name("organization")
                ->add($data);
            if($res){
                return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/garden')]);
            }else{
                return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }
        }
        $jituan = Db::name("organization")
            ->where("p_id = 0")
            ->select();
        $this->assign('jituan',$jituan);
        return view();
    }
    /**
     * @return 添加班级
     */
    public function classHandle(){
        $data = I('post.');
        if(empty($data['garden_id']) || empty($data['ji_id'])){
            return json_encode(['status'=>-1,'msg'=>'请认真填写']);
        }
        $data['p_id'] = $data['garden_id'];
        $data['status'] = 2;
        $res = Db::name("organization")->add($data);
        if($res){
//            $r = Db::query("insert into tp_garden_class set garden_id = {$data['garden_id']},class_id = $class_id,principal='{$data['principal']}'");
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/garden')]);
        }
        return json_encode(['status'=>-1,'msg'=>'操作失败']);
    }

    /**
     * @return 添加职位
     */
    public function postHandle(){
        $data = I('post.');
        if(empty($data['ji_id']) || empty($data['name'])){
            return json_encode(["status"=>-1,'msg'=>"请认真填写",'']);
        }
        if(empty($data['garden_id'])){
            $data['p_id'] = $data['ji_id'];
        }elseif(empty($data['class_id'])){
            $data['p_id'] = $data['garden_id'];
        }else{
            $data['p_id'] = $data['class_id'];
        }
        $data['status'] = 0;
        $res = Db::name("organization")
            ->add($data);
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/position')]);
        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }
//        return json_encode($data);die;
//        $post = Db::table("tp_post")->field('post_id')->where('post_name',$data['post_name'])->find();
//        $post_id = $post['post_id'];
//        if(empty($post_id)){
//            $role = Db::query("select role_name from tp_admin_role where  role_name = '{$data['post_name']}'");
//            if(empty($role['role_name'])){
//                Db::query("insert into tp_admin_role set role_name = '{$data['post_name']}'");
//            }
//            Db::query("insert into tp_post set post_name = '{$data['post_name']}'");
//            $post_id = Db::name("tp_post")->getLastInsID();
//        }
//        $garden_class = Db::query("select * from tp_post_garden where garden_id = {$data['garden_id']} and post_id = $post_id");
//        if(empty($garden_class)){
//            $r = Db::query("insert into tp_post_garden set garden_id = {$data['garden_id']},post_id = $post_id,principal='{$data['principal']}'");
//            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/position')]);
//        }
//        return json_encode(['status'=>-1,'msg'=>'操作失败']);
    }

    /***
     * @return 删除班级
     */
    public function class_del(){
        $data = I('post.');
        $res = Db::name("organization")
            ->where("id",$data['id'])
            ->delete();
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/garden')]);

        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }
    }

    /**
     * @return 删除职位
     */
    public function post_del(){
        $id = I('post.id');
        $res = Db::name("organization")
            ->where("id",$id)
            ->delete();
        if($res){
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/position')]);

        }else{
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }
    }

    /***
     * @return 删除部门
     */
    public function branch_id(){
        $data = I('get.');
        $res = Db::query("delete from tp_organization where id = {$data['id']}");
//        return json_encode($res);die;
        if($res){
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }else{
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/garden')]);
        }
    }

    /***
     * @return 级别设置
     */
    public function levels(){
        $post = Db::name("organization")
            ->where('status','0')
            ->select();
//        dump($post);die;
        $this->assign("post",$post);
        return $this->fetch();
    }

    /***
     *
     */
    public function levelsHandle(){
        $data = I('post.');
        $role_id = implode(',',$data['role_id']);
        $res = Db::query("insert into tp_pay_type set role_id = '$role_id'");
        if($res){
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }else{
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/levels')]);
        }
    }

    /***
     * @return 薪酬级别
     */
    public function grade(){
        $data = Db::query("select role_id from tp_pay_type");
        foreach($data as $key => $val){
            $list[] = Db::query("select name as role_name,id from tp_organization where id in({$val['role_id']})");
            $li[] = array_column($list[$key],'role_name');
        }
        foreach ($li as $k => $v){
            $data[$k]['role_name'] = implode("、",$v);
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * @retrun 工作日志
     */
    public function journal(){
        
        $data = DB::name("work_log")
        ->alias("w")
        ->join("staff s","w.staff_id=s.staff_id")
        ->select();
        
        $this->assign("list",$data);
        return view("journal_list");
    }
     public function journal_del(){
        $id = I("get.id");
        $res = DB::name("work_log")->where("id",$id)->delete();
         if(!$res){
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
        }else{
            return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/journal')]);
        }
       
    }
    public function journal_edit(){
        if(IS_POST){
            $data = I("POST.");
            // dump($data);die;
            $list['today_desc']=$data['today_desc'];
            $list['ming_desc']=$data['ming_desc'];
            $res = DB::name("work_log")->where("id",$data['id'])->update($list);
             if(!$res){
                    return json_encode(['status'=>-1,'msg'=>"修改失败"]);
                }else{
                    return json_encode(['status'=>1,'msg'=>"修改成功",'url'=>U('Admin/Staff/journal')]);
                }
        }else{
             $id = I("get.id");
            $data = DB::name("work_log")->where("id",$id)->find();
            $res = DB::name("staff")->where("staff_id",$data['staff_id'])->find();
            $name = $res['staff_name'];
            $this->assign('name',$name);
            $this->assign("data",$data);
            return view();
        }
       
    }



        /**
     * @retrun 工作日志添加
     */
    public function journal_add(){
        if(IS_POST){
           $data = I("post.");
           $list['staff_id']=$data['staff_id'];
           $list['today_desc']=$data['today_desc'];
           $list['ming_desc']=$data['ming_desc'];
           $list['addtime']=time();
           $res =DB::name("work_log")->insert($list);
           if(!$res){
            return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }else{
                return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/journal')]);
            }
        }else{
            $data = DB::name("organization")->where("p_id",0)->select();
            $this->assign("jituan",$data);
            return view("journal");
        }
    
        
    }

    public function journal_getstaff(){
        $data['ji_id'] = I("get.ji_id");
        $data['garden_id'] = I("get.garden_id");
        $data['class_id'] = I("get.class_id");
        $data['o_id'] = I("get.post_id");
        $where="";
        foreach ($data as $key => $value) {
           if(!empty($value)){
                $where .="".$key."=".$value." and ";
           }
        }
        $where = rtrim($where," and ");
        $res = DB::name("staff")->where($where)->select();
        // echo $res;
        // dump(DB::name("staff")->getLastSql());
        // echo $where;
         // dump($res);die;
        // dump($res->getLastSql());die;
        if(!$res){
            return json_encode(['status'=>0,'msg'=>"没有人员"]);
        }else{
            return json_encode(['status'=>1,'msg'=>"",'data'=>$res]);
        }
    }
/**
 * @return 招聘信息展示及ajax动态修改
 */
    public function recruit(){
        if(request()->isAjax()){
            $data = I("post.");
            $recruit_status = $data['recruit_status'];
            $recruit_id = $data['recruit_id'];
            $res = Db::query("update tp_recruit set recruit_status=$recruit_status where recruit_id = $recruit_id ");
            if($res){
                return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }else{
                return json_encode(['status'=>1,'msg'=>'操作成功']);
            }
        }
        $data = Db::table("tp_recruit")
            ->join("tp_admin_role","tp_recruit.role_id = tp_admin_role.role_id")
            ->field("recruit_id,recruit_name,recruit_time,role_name,interviewer,recruit_status,desc")
            ->select();
//        dump($data);die;
        $this->assign("recruit",$data);
        return $this->fetch();
    }
    /**
     * @return 招聘信息添加
     */
    public function recruit_info(){
        if(request()->isAjax()){
            $data = I("post.");
            $data['addtime'] = date("Y-m-d",time());
            $res = Db::name("recruit")->add($data);
            if($res){
                return json_encode(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Staff/recruit')]);
            }else{
               return json_encode(['status'=>-1,'msg'=>'操作失败']);
            }
            die;
        }
        $role = Db::query("select role_id,role_name from tp_admin_role where role_name != '投资人' and role_name != '超级管理员' and role_name != '家长'");
        $this->assign('role',$role);
        return $this->fetch();
    }
}