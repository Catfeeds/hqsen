<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 意见反馈接口
 */


namespace api\app;

class feedback extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();// 需要登录态
    }

    // 创建意见反馈
    public function create(){
        $feedback_main = $this->postString('content');
        $feedback_phone = $this->postString('phone');
        if($feedback_main){
            $sql_feedback['content'] = $feedback_main;
            $sql_feedback['phone'] = $feedback_phone;
            $sql_feedback['user_id'] = $this->user['id'];
            $sql_feedback['user_name'] = $this->user['user_name'];
            $sql_feedback['create_time'] = time();
            $sql_feedback['del_flag'] = 1;
            $sql_feedback['id'] = $this->db->insert('hqsen_feedback', $sql_feedback);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    // 账号密码修改
    public function accountEdit(){
        $old_password = $this->postString('old_password');
        $password = $this->postString('password');
        $re_password = $this->postString('re_password');
        $user = $this->db->getRow("select * from hqsen_user where id = " . $this->user['id']);
        if($old_password and md5($old_password) == $user['password'] and $password and $password == $re_password){
            $sql_user['password'] = md5($password);
            $this->db->update('hqsen_user', $sql_user, ' id = ' . $this->user['id']);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    // 个人佣金
    public function wallet(){
        $data['my_account']['alipay'] = $this->user['alipay_account'];
        $data['my_account']['bank_name'] = $this->user['bank_name'];
        $data['my_account']['bank_user'] = $this->user['bank_user'];
        $data['my_account']['bank_account'] = $this->user['bank_account'];
        // 获取客资提供者身份 账号信息
        $unpay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_kezi_order where user_order_status = 2 and user_id = ' . $this->user['id']);
        $pay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_kezi_order where user_order_status = 3 and user_id = ' . $this->user['id']);

        $data['my_money']['unpay'] = round($unpay['s'], 2);
        $data['my_money']['pay'] = round($pay['s'], 2);

        // 获取客资跟踪者 和 搭建提供者账号信息 如果是酒店账号
        if($this->user['user_type'] == 4){
            $watch_unpay = $this->db->getRow('select sum(watch_user_money) as s from hqsen_user_kezi_order where user_order_status = 2 and watch_user_id = ' . $this->user['id']);
            $watch_pay = $this->db->getRow('select sum(watch_user_money) as s from hqsen_user_kezi_order where user_order_status = 3 and watch_user_id = ' . $this->user['id']);

            $data['my_money']['unpay'] = $data['my_money']['unpay'] + round($watch_unpay['s'], 2);
            $data['my_money']['pay'] = $data['my_money']['pay'] + round($watch_pay['s'], 2);

            $dajian_unpay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_dajian_order where user_order_status = 2 and user_id = ' . $this->user['id']);
            $dajian_pay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_dajian_order where user_order_status = 3 and user_id = ' . $this->user['id']);

            $data['my_money']['unpay'] = $data['my_money']['unpay'] + round($dajian_unpay['s'], 2);
            $data['my_money']['pay'] = $data['my_money']['pay'] + round($dajian_pay['s'], 2);
        }
        $data['my_money']['all'] = $data['my_money']['unpay'] + $data['my_money']['pay'];
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function autoType(){
        $sql_user['auto_type'] = 2;
        $this->db->update('hqsen_user_data', $sql_user, ' id = ' . $this->user['id']);
    }



}