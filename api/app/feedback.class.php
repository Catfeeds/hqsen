<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 接口订单类api user
 */


namespace api\app;

class feedback extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    //
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


    public function accountEdit(){
        $old_password = $this->postString('old_password');
        $password = $this->postString('password');
        $re_password = $this->postString('re_password');
        $user = $this->db->getRow("select * from hqsen_user where id = " . $this->user['id']);
        if($old_password and md5($old_password) == $user['password'] and $password and $password == $re_password){
            $sql_user['password'] = md5($password);
//            $sql_user['session_id'] =  md5($user['id'] . $user['last_login_time']);
            $this->db->update('hqsen_user', $sql_user, ' id = ' . $this->user['id']);
            $data['access_token'] = $sql_user['session_id'];
//            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    public function wallet(){
        $data['my_account']['alipay'] = $this->user['alipay_account'];
        $data['my_account']['bank_name'] = $this->user['bank_name'];
        $data['my_account']['bank_user'] = $this->user['bank_user'];
        $data['my_account']['bank_account'] = $this->user['bank_account'];
        // 获取客资提供者身份 账号信息
        $unpay = $this->db->getRow('select sum(hukos.order_money) as s from hqsen_user_kezi_order as huko 
left join hqsen_user_kezi_order_sign as hukos on hukos.user_kezi_order_id=huko.id 
where huko.user_order_status = 2 and huko.user_id = ' . $this->user['id']);

        $pay = $this->db->getRow('select sum(hukos.order_money) as s from hqsen_user_kezi_order as huko 
left join hqsen_user_kezi_order_sign as hukos on hukos.user_kezi_order_id=huko.id 
where huko.user_order_status = 3 and huko.user_id = ' . $this->user['id']);

        $data['my_money']['unpay'] = intval($unpay['s']);
        $data['my_money']['pay'] = intval($pay['s']);

        // 获取客资跟踪者 和 搭建提供者账号信息 如果是酒店账号
        if($this->user['user_type'] == 4){
            $watch_unpay = $this->db->getRow('select sum(hukos.order_money) as s from hqsen_user_kezi_order as huko 
left join hqsen_user_kezi_order_sign as hukos on hukos.user_kezi_order_id=huko.id 
where huko.user_order_status = 2 and huko.watch_user_id = ' . $this->user['id']);

            $watch_pay = $this->db->getRow('select sum(hukos.order_money) as s from hqsen_user_kezi_order as huko 
left join hqsen_user_kezi_order_sign as hukos on hukos.user_kezi_order_id=huko.id 
where huko.user_order_status = 3 and huko.watch_user_id = ' . $this->user['id']);

            $data['my_money']['unpay'] = $data['my_money']['unpay'] + intval($watch_unpay['s']);
            $data['my_money']['pay'] = $data['my_money']['pay'] + intval($watch_pay['s']);

            $dajian_unpay = $this->db->getRow('select sum(hukos.order_money) as s from hqsen_user_dajian_order as huko 
left join hqsen_user_dajian_order_sign as hukos on hukos.user_dajian_order_id=huko.id 
where huko.user_order_status = 2 and huko.user_id = ' . $this->user['id']);

            $dajian_pay = $this->db->getRow('select sum(hukos.order_money) as s from hqsen_user_dajian_order as huko 
left join hqsen_user_dajian_order_sign as hukos on hukos.user_dajian_order_id=huko.id 
where huko.user_order_status = 3 and huko.user_id = ' . $this->user['id']);

            $data['my_money']['unpay'] = $data['my_money']['unpay'] + intval($dajian_unpay['s']);
            $data['my_money']['pay'] = $data['my_money']['pay'] + intval($dajian_pay['s']);
        }
        $data['my_money']['all'] = $data['my_money']['unpay'] + $data['my_money']['pay'];
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }



}