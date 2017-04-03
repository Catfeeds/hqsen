<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 接口订单类api user
 */


namespace api\web;

class account extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    public function registerAccountList(){
        $user = $this->db->getRow("select * from hqsen_user where user_type = 3");
        $data = [];
        foreach ($user as $one_user){
            if($one_user){
                $user_item = array(
                    'user_id' => $one_user['id'],
                    'user_name' => $one_user['user_name'],
                    'alipay_account' => $one_user['alipay_account'],
                );
                $data[] = $user_item;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);

    }
}