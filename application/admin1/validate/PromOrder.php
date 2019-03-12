<?php
namespace app\admin\validate;
use think\Validate;
use think\Db;
class PromOrder extends Validate
{
    // 验证规则
    protected $rule = [
        'id'=>'checkId',
        'name'=>'require|max:50|unique:prom_order',
        'type'=> 'require',
        'money'=> 'require',
        'expression'=> 'require',
        'start_time'=> 'require|checkEndTime',
    ];
    //错误信息
    protected $message  = [
        'name.require'             => '促销标题必填',
        'name.unique'              => '促销标题重复',
        'name.max'                 => '促销标题应小于50字符',
        'type.require'             => '活动类型必填',
        'money.require'            => '需要满足的金额必填',
        'expression.require'       => '请填写优惠',
        'start_time.require'       => '请填开始时间',
        'start_time.checkEndTime'  => '结束时间不得小于开始时间',
    ];
    protected $sence=[
        'add'  => ['name','type','money','expression','start_time'],
        'edit' => ['id','name','type','money','expression','start_time'],
    ];
    /**
     * 检查结束时间
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    protected function checkEndTime($value, $rule ,$data)
    {
        return ($value > $data['end_time']) ? false : true;
    }

    /**
     * 该活动是否可以编辑
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    protected function checkId($value, $rule ,$data)
    {
        $res = Db::name('order')->where(['order_prom_id' => $value])->count();
        if($res){
            return '该活动有订单参与不能编辑，删除!';
        }else{
            return true;
        }
    }
}