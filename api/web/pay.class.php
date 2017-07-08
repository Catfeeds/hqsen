<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:pay 打款
 */


namespace api\web;

class pay extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    // 客资订单列表
    public function payRatio(){
        $kezi_user = $this->postString('kezi_user');
        $kezi_hotel = $this->postString('kezi_hotel');
        $dajian_user = $this->postString('dajian_user');

        if($kezi_user and $kezi_hotel and $dajian_user){
            $pay_ratio = $this->db->getRow("select *  from hqsen_pay_ratio limit 1");
            $pay_ratio['kezi_user'] = $kezi_user;
            $pay_ratio['kezi_hotel'] = $kezi_hotel;
            $pay_ratio['dajian_user'] = $dajian_user;

            if(isset($pay_ratio['id']) and $pay_ratio['id']){
                $this->db->update('hqsen_pay_ratio', $pay_ratio, ' id = ' . $pay_ratio['id']);
            } else {
                $this->db->insert('hqsen_pay_ratio', $pay_ratio);
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success']);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    public function payRatioDetail(){
        $pay_ratio = $this->db->getRow("select *  from hqsen_pay_ratio limit 1");
        $item = [];
        if(!$pay_ratio){
            $item['kezi_user'] = 0.01;
            $item['kezi_hotel'] = 0.01;
            $item['dajian_user'] = 0.01;
        } else {
            $item['kezi_user'] = $pay_ratio['kezi_user'];
            $item['kezi_hotel'] = $pay_ratio['kezi_hotel'];
            $item['dajian_user'] = $pay_ratio['dajian_user'];
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'],$item);
    }

    // 客资财务 打款列表
    public function keziOrderList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        // 总经理要在财务审批通过基础上
        $sign = $this->db->getRows("select *  from hqsen_user_kezi_order_sign  where boss_sign_status = 2 order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $user_order = $this->db->getRow("select *  from hqsen_user_kezi_order where id=" . $one_sign['user_kezi_order_id']);
            $create_user = $this->db->getRow("select *  from hqsen_user where id=" . $user_order['user_id']);
            $watch_user = $this->db->getRow("select *  from hqsen_user where id=" . $user_order['watch_user_id']);
            $pay_item['id'] = $one_sign['id'];
            $pay_item['user_kezi_order_id'] = $one_sign['user_kezi_order_id'];
            $pay_item['order_money'] = $one_sign['order_money'];
            $pay_item['order_other_money'] = $one_sign['order_other_money'];
            $pay_item['create_user_name'] = $create_user['user_name'];
            $pay_item['create_account'] = $create_user['alipay_account'];
            $pay_item['create_user_money'] = '100';
            $pay_item['watch_user_name'] = $watch_user['user_name'];
            $pay_item['watch_account'] = $watch_user['alipay_account'];
            $pay_item['watch_user_money'] = '100';
            $pay_item['create_time'] = date('Y-m-d H:i:s' , $one_sign['create_time']);
            $pay_item['pay_status'] = $user_order['order_status'];// 1未打款 2 已打款
            $data['list'][] = $pay_item;
        }
        $data['count'] = $this->db->getCount('hqsen_user_kezi_order_sign', 'boss_sign_status = 2');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 财务打款操作
    public function keziPayOrder(){
        $order_id = $this->postInt('order_id');
        $user_order = $this->db->getRow("select *  from hqsen_user_kezi_order where id=" . $order_id);
        if($user_order){
            $user_order['order_status'] = 4;
            $user_order['user_order_status'] = 3;
            $this->db->update('hqsen_user_kezi_order', $user_order, ' id = ' . $user_order['id']);
        }
        $this->appDie();
    }

    // 客资财务打款详情页
    public function keziOrderDetail(){
        $id = $this->postInt('id');
        $one_sign = $this->db->getRow("select *  from hqsen_user_kezi_order_sign where id=" . $id);
        $user_order = $this->db->getRow("select *  from hqsen_user_kezi_order where id=" . $one_sign['user_kezi_order_id']);
        //todo 获取用户支付宝
        $pay_item['id'] = $one_sign['id'];
        $pay_item['user_kezi_order_id'] = $one_sign['user_kezi_order_id'];
        $pay_item['order_money'] = $one_sign['order_money'];
        $pay_item['order_other_money'] = $one_sign['order_other_money'];
        $pay_item['create_user_name'] = $user_order['user_id'];// 改成用户名字
        $pay_item['create_user_money'] = '100';
        $pay_item['create_user_alipay'] = 'zhifubao.cc';
        $pay_item['watch_user_name'] = $user_order['watch_user_name'];
        $pay_item['watch_user_money'] = '100';
        $pay_item['watch_user_alipay'] = 'zhifubao.cc';
        $pay_item['pay_status'] = $user_order['order_status'];// 1未打款 2 已打款
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $pay_item);
    }

    // 搭建财务 打款列表
    public function dajianOrderList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        // 总经理要在财务审批通过基础上
        $sign = $this->db->getRows("select *  from hqsen_user_dajian_order_sign  where boss_sign_status = 2 order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $user_order = $this->db->getRow("select *  from hqsen_user_dajian_order where id=" . $one_sign['user_dajian_order_id']);
            $user_info = $this->db->getRow("select *  from hqsen_user where id=" . $user_order['user_id']);
            $pay_item['id'] = $one_sign['id'];
            $pay_item['user_dajian_order_id'] = $one_sign['user_dajian_order_id'];
            $pay_item['order_money'] = $one_sign['order_money'];
            $pay_item['first_order_money'] = $one_sign['first_order_money'];
            $pay_item['sign_user_id'] = $user_order['watch_user_id']; //  首销ID
            $pay_item['create_user_name'] = $user_info['user_name'];
            $pay_item['create_user_money'] = '100';
            $pay_item['pay_status'] = $user_order['order_status'];// 1未打款 2 已打款
            $pay_item['create_time'] = date('Y-m-d H:i:s' , $one_sign['create_time']);
            $data['list'][] = $pay_item;
        }
        $data['count'] = $this->db->getCount('hqsen_user_dajian_order_sign', ' boss_sign_status = 2 ');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 搭建财务打款操作
    public function dajianPayOrder(){
        $order_id = $this->postInt('order_id');
        $user_order = $this->db->getRow("select *  from hqsen_user_dajian_order where id=" . $order_id);
        if($user_order){
            $user_order['order_status'] = 4;
            $user_order['user_order_status'] = 3;
            $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $user_order['id']);
        }
        $this->appDie();
    }

    // 搭建财务打款详情页
    public function dajianOrderDetail(){
        $id = $this->postInt('id');
        $one_sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $id);
        $user_order = $this->db->getRow("select *  from hqsen_user_dajian_order where id=" . $one_sign['user_dajian_order_id']);
        //todo 获取用户支付宝
        $pay_item['id'] = $one_sign['id'];
        $pay_item['user_dajian_order_id'] = $one_sign['user_dajian_order_id'];
        $pay_item['order_money'] = $one_sign['order_money'];
        $pay_item['create_user_name'] = $user_order['user_id'];// 改成用户名字
        $pay_item['create_user_money'] = '100';
        $pay_item['create_user_alipay'] = 'zhifubao.cc';
        $pay_item['first_order_money'] = $one_sign['first_order_money'];
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $pay_item);
    }


}