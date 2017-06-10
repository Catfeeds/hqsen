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



}