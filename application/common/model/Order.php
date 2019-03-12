<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: dyr
 * Date: 2016-08-23
 */

namespace app\common\model;

use think\Model;
use think\Db;

/**
 * @package Home\Model
 */
class Order extends Model
{

    public function shop()
    {
        return $this->hasOne('shop','shop_id','shop_id');
    }
    public function shopOrder()
    {
        return $this->hasOne('ShopOrder','order_id','order_id');
    }
    //获取所有订单商品
    public function OrderGoods()
    {
        return $this->hasMany('OrderGoods','order_id','order_id')->field('*,(goods_num * member_goods_price) AS goods_total');
    }
    //订单商品总数
    public function countGoodsNum()
    {
        return $this->hasMany('OrderGoods','order_id','order_id')->sum('goods_num');
    }
    /**
     * 获取订单操作记录
     * @return \think\model\relation\HasMany
     */
    public function OrderAction()
    {
        return $this->hasMany('OrderAction','order_id','order_id')->order('action_id desc');
    }
    /**
     * 获取订单发货单
     * @return \think\model\relation\HasMany
     */
    public function DeliveryDoc()
    {
        return $this->hasMany('DeliveryDoc','order_id','order_id');
    }
    public function users(){
        return $this->hasone('users','user_id','user_id')->field('user_id,nickname');
    }
    /**
     * 获取虚拟订单的兑换码
     * @return \think\model\relation\HasMany
     */
    public function VrOrderCode(){
        return $this->hasMany('vr_order_code','order_id','order_id');
    }
    /**
     * 获取订单状态对应的中文
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getOrderStatusDetailAttr($value, $data)
    {
        $data_status_arr = C('ORDER_STATUS_DESC');
        // 货到付款
        if ($data['pay_code'] == 'cod') {
            if (in_array($data['order_status'], array(0, 1)) && $data['shipping_status'] == 0) {
                return $data_status_arr['WAITSEND']; //'待发货',
            }
        } else {
            // 非货到付款
            if ($data['pay_status'] == 0 && $data['order_status'] == 0){
                return $data_status_arr['WAITPAY']; //'待支付',
            }
            if ($data['pay_status'] == 1 && in_array($data['order_status'], array(0, 1)) && $data['shipping_status'] != 1){
                if ($data['prom_type'] == 5) { //虚拟商品
                    return $data_status_arr['WAITRECEIVE']; //'待收货',
                } elseif($data['prom_type'] == 6){
                    if($data['order_status'] == 0){
                        return '待处理';
                    }else{
                        if($data['shop_id'] > 0){
                            return '待自提';
                        }
                        return $data_status_arr['WAITSEND']; //'待发货',
                    }
                }
                else {
                    if($data['shop_id'] > 0){
                        return '待自提';
                    }
                    return $data_status_arr['WAITSEND']; //'待发货',
                }
            }
        }
        if (($data['shipping_status'] == 1) && ($data['order_status'] == 1)){
            return $data_status_arr['WAITRECEIVE']; //'待收货',
        }
        if ($data['order_status'] == 2 && $data['is_comment'] == 1) {
            return $data_status_arr['FINISH']; //'待评价',
        }
        if ($data['order_status'] == 2) {
            return $data_status_arr['WAITCCOMMENT']; //'待评价',
        }
        if ($data['order_status'] == 3){
            return $data_status_arr['CANCEL']; //'已取消',
        }
        if ($data['order_status'] == 4){
            return $data_status_arr['FINISH']; //'已完成',
        }
        return $data_status_arr['OTHER'];
    }

    /**
     *  只有在订单为拼团才有用:prom_type = 6
     */
    public function teamActivity()
    {
        return $this->hasOne('TeamActivity', 'team_id', 'prom_id');
    }

    public function teamFollow(){
        return $this->hasOne('TeamFollow','order_id','order_id');
    }


    /**
     * 订单详细收货地址
     * @param $value
     * @param $data
     * @return string
     */
    public function getFullAddressAttr($value, $data)
    {
        $province = Db::name('region')->where(['id'=>$data['province']])->value('name');
        $city = Db::name('region')->where(['id'=>$data['city']])->value('name');
        $district = Db::name('region')->where(['id'=>$data['district']])->value('name');
        $address = $province.'，'.$city.'，'.$district.'，'.$data['address'];
        return $address;
    }

    /**
     * 订单是否可拆单
     * @param $value
     * @param $data
     * @return bool
     */
    public function getIsSplitAttr($value, $data){
        if($data['shop_id'] > 0){
            return false;
        }
        if($data['prom_type'] == 4){
            return false;
        }
        if($data['order_status'] < 2){
            $goods_num = Db::name('order_goods')->where('order_id',$data['order_id'])->sum('goods_num');
            if($goods_num > 1){
                return true;
            }
        }
        return false;
    }

    public function teamFound(){
        return $this->hasOne('TeamFound','order_id','order_id');
    }

    /**
     * 获取当前可操作的按钮(后台)
     * @param $data
     * @return array
     */
    public function getAdminOrderButtonAttr($value, $data){
        /*
         *  操作按钮汇总 ：付款、设为未付款、确认、取消确认、无效、去发货、确认收货、申请退货
         *
         */
        $os = $data['order_status'];//订单状态
        $ss = $data['shipping_status'];//发货状态
        $ps = $data['pay_status'];//支付状态
        $pt = $data['prom_type'];//订单类型：0默认1抢购2团购3优惠4预售5虚拟6拼团
        $btn = array();
        if($data['pay_code'] == 'cod') {
            if($os == 0 && $ss == 0){
                if($pt != 6){
                    $btn['confirm'] = '确认';
                }
            }elseif($os == 1 && ($ss == 0 || $ss == 2)){
                $btn['delivery'] = '去发货';
                if($pt != 6){
                    $btn['cancel'] = '取消确认';
                }
            }elseif($ss == 1 && $os == 1 && $ps == 0){
                $btn['pay'] = '付款';
            }elseif($ps == 1 && $ss == 1 && $os == 1){
                if($pt != 6){
                    $btn['pay_cancel'] = '设为未付款';
                }
            }
        }else{
            if($ps == 0 && $os == 0 || $ps == 2){
                $btn['pay'] = '付款';
            }elseif($os == 0 && $ps == 1){
                if($pt != 6){
                    $btn['pay_cancel'] = '设为未付款';
                    $btn['confirm'] = '确认';
                }
            }elseif($os == 1 && $ps == 1 && ($ss == 0 || $ss == 2)){
                if($pt != 6){
                    $btn['cancel'] = '取消确认';
                }
                $btn['delivery'] = '去发货';
            }
        }

        if($ss == 1 && $os == 1 && $ps == 1){
//        	$btn['delivery_confirm'] = '确认收货';
            $btn['refund'] = '申请退货';
        }elseif($os == 2 || $os == 4){
            $btn['refund'] = '申请退货';
        }elseif($os == 3 || $os == 5){
            $btn['remove'] = '移除';
        }
        if($os != 5){
            $btn['invalid'] = '无效';
        }
        return $btn;
    }

    /**
     * 获取订单状态的显示按钮（用户）
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getOrderButtonAttr($value, $data)
    {
        /**
         *  订单用户端显示按钮
         * 去支付     AND pay_status=0 AND order_status=0 AND pay_code ! ="cod"
         * 取消按钮  AND pay_status=0 AND shipping_status=0 AND order_status=0
         * 确认收货  AND shipping_status=1 AND order_status=0
         * 评价      AND order_status=1
         * 查看物流  if(!empty(物流单号))
         */
        $btn_arr = array(
            'pay_btn' => 0, // 去支付按钮
            'cancel_btn' => 0, // 取消按钮
            'receive_btn' => 0, // 确认收货
            'comment_btn' => 0, // 评价按钮
            'shipping_btn' => 0, // 查看物流
            'return_btn' => 0, // 退货按钮 (联系客服)
            'return_overdue' => 0, // 是否过了售后时间
        );
        $auto_service_date = tpCache('shopping.auto_service_date'); //申请售后时间段
        $confirm_time = strtotime ( "-$auto_service_date day" );
        // 三个月(90天)内的订单才可以进行有操作按钮. 三个月(90天)以外的过了退货 换货期, 即便是保修也让他联系厂家, 不走线上
        if (time() - $data['add_time'] > (86400 * 90)) {
            return $btn_arr;
        }
        if($data['confirm_time']<$confirm_time){
            $btn_arr['return_overdue']=1;
        }
        // 货到付款
        if ($data['pay_code'] == 'cod') {
            if (($data['order_status'] == 0 || $data['order_status'] == 1) && $data['shipping_status'] == 0)
            {
                $btn_arr['cancel_btn'] = 1; // 取消按钮 (联系客服)
            }
            if ($data['shipping_status'] == 1 && $data['order_status'] == 1) //待收货
            {
                $btn_arr['receive_btn'] = 1;  // 确认收货
                $btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
            }
        } // 非货到付款
        else {
            if ($data['pay_status'] == 0 && $data['order_status'] == 0) // 待支付
            {
                $btn_arr['pay_btn'] = 1; // 去支付按钮
                $btn_arr['cancel_btn'] = 1; // 取消按钮
            }
            if ($data['pay_status'] == 1 && $data['order_status'] < 2 && $data['shipping_status'] == 0) {
                if ($data['prom_type'] == 6) {
                    $btn_arr['cancel_btn'] = 0; // 取消按钮
                } else {
                    $btn_arr['cancel_btn'] = 1; // 取消按钮
                }
            }
            if ($data['pay_status'] == 1 && $data['order_status'] == 1 && $data['shipping_status'] == 1) {
                $btn_arr['receive_btn'] = 1;  // 确认收货
            }
        }

        if ($data['order_status'] == 4) {
            $btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
        }
        if ($data['order_status'] == 2) {
            if ($data['is_comment'] == 0) $btn_arr['comment_btn'] = 1;  // 评价按钮
            $btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
        }
        if ($data['shipping_status'] > 0 && $data['order_status'] == 1) {
            $btn_arr['shipping_btn'] = 1; // 查看物流
        }
        if ($data['shipping_status'] == 2 && $data['order_status'] == 1) {
            $btn_arr['return_btn'] = 1; // 退货按钮 (联系客服)
        }
//        if($data['order_status'] == 3 && ($data['pay_status'] == 1 || $data['pay_status'] == 4)){
//        	$btn_arr['cancel_info'] = 1; // 取消订单详情
//        }
        if ($data['order_status'] == 3 && ($data['pay_status'] == 1 || $data['pay_status'] == 4 || $data['pay_status'] == 3)) {
            $btn_arr['cancel_info'] = 1; // 取消订单详情
        }
        return $btn_arr;
    }

    /**
     * 虚拟订单操作按钮
     * @param $value
     * @param $data
     * @return array
     */
    public function getVirtualOrderButtonAttr($value, $data){
        $vr_order_code = Db::name('vr_order_code')->where(['order_id'=>$data['order_id']])->find();
        $Virtual_btn_arr = array(
            'pay_btn' => 0, // 去支付按钮
            'cancel_btn' => 0, // 取消按钮
            'receive_btn' => 0, // 确认收货
            'comment_btn' => 0, // 评价按钮
        );
        if ($data['pay_status'] == 0 && $data['order_status'] == 0) {   // 待支付
            $Virtual_btn_arr['pay_btn'] = 1; // 去支付按钮
            $Virtual_btn_arr['cancel_btn'] = 1; //未支付取消按钮
        }
        if(!empty($vr_order_code)){
            if ($data['pay_status']==1 && $data['order_status']<2 && $vr_order_code['vr_state']!=1 && $vr_order_code['refund_lock']<1)
            {
                if ($vr_order_code['vr_indate'] > time() ) {
                    $Virtual_btn_arr['cancel_btn'] = 2; // 已支付取消按钮
                }
                if ($vr_order_code['vr_indate'] < time() && $vr_order_code['vr_invalid_refund'] == 1)
                {
                    $Virtual_btn_arr['cancel_btn'] = 2; // 已支付取消按钮
                    M('vr_order_code')->where(array('order_id'=>$data['order_id']))->update(['vr_state'=>2]);
                }
                $Virtual_btn_arr['receive_btn'] = 1;
            }
            if ($data['order_status'] == 2) {
                if ($data['is_comment'] == 0) $Virtual_btn_arr['comment_btn'] = 1;  // 评价按钮
            }
        }
        return $Virtual_btn_arr;
    }

    public function getAddressRegionAttr($value, $data){
        $regions = Db::name('region')->where('id', 'IN', [$data['province'], $data['city'], $data['district'],$data['twon']])->order('level desc')->select();
        $address = '';
        if($regions){
            foreach($regions as $regionKey=>$regionVal){
                $address = $regionVal['name'] . $address;
            }
        }
        return $address;
    }

    public function getPayStatusDetailAttr($value, $data)
    {
        $pay_status = config('PAY_STATUS');
        return $pay_status[$data['pay_status']];
    }

    public function getShippingStatusDetailAttr($value, $data)
    {
        $shipping_status = config('SHIPPING_STATUS');
        return $shipping_status[$data['shipping_status']];
    }

    public function getPromTypeDescAttr($value, $data){
        if($data['prom_type'] == 4){
            return '预售订单';
        }elseif($data['prom_type'] == 5){
            return '虚拟订单';
        }elseif($data['prom_type'] == 6){
            return '拼团订单';
        }else{
            return '普通订单';
        }
    }
    /**
     * 是否显示支付到期时间FinallyPayTime
     * @param $value
     * @param $data
     * @return bool
     */
    public function getPayBeforeTimeShowAttr($value, $data)
    {
        if($data['prom_type'] == 4){
            return false;
        }
        return true;
    }
    /**
     * 订单支付期限
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getFinallyPayTimeAttr($value, $data){
        return $data['add_time'] + config('finally_pay_time');
    }
    /**
     * 付款URL
     * @param $value
     * @param $data
     * @return string
     */
    public function getPayUrlAttr($value, $data)
    {
        if($data['prom_type'] == 4){
            return U('Cart/cart4',['master_order_sn'=>$data['master_order_sn']]);
        }else{
            return U('Cart/cart4',['order_id'=>$data['order_id']]);
        }
    }
    /**
     * 订单发票
     * @return string
     */
    public function invoice()
    {
        return $this->hasOne('invoice','order_id','order_id');
    }

    /**
     * 配送方式
     */
    public function getDeliveryMethodAttr($value, $data)
    {
        if($data['shop_id'] > 0){
            return "上门自提";
        }else{
            return "快递配送";
        }
    }

    public function getConsigneeDescAttr($value, $data)
    {
        if($data['shop_id'] > 0){
            return "提货人";
        }else{
            return "收货人";
        }
    }
    /**
     * 获取预售信息
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getPreSellInfoAttr($value, $data)
    {
        if($data['prom_type'] == 4){
            $pre_sell_item =  Db::name('goods_activity')->where(array('act_id'=>$data['prom_id']))->find();
            $pre_sell_item = array_merge($pre_sell_item, unserialize($pre_sell_item['ext_info']));
            $data['pre_sell_is_finished'] = $pre_sell_item['is_finished'];
            $data['pre_sell_retainage_start'] = $pre_sell_item['retainage_start'];
            $data['pre_sell_retainage_end'] = $pre_sell_item['retainage_end'];
            $data['pre_sell_deliver_goods'] = $pre_sell_item['deliver_goods'];
        }else{
            $data['pre_sell_is_finished'] = -1;//没有参与预售的订单
        }
        return $data;
    }

    public function getShippingStatusDescAttr($value, $data)
    {
        $config = config('SHIPPING_STATUS');
        return $config[$data['shipping_status']];
    }
    public function getCountGoodsNumAttr($value, $data)
    {
        return Db::name('order_goods')->where('order_id',$data['order_id'])->sum('goods_num');
    }
}