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
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";

        $user = $this->db->getRows("select * from hqsen_user where user_type = 3 " . $sql_limit);
        $data = [];
        foreach ($user as $one_user){
            if($one_user){
                $user_item = array(
                    'user_id' => $one_user['id'],
                    'user_name' => $one_user['user_name'],
                    'alipay_account' => $one_user['alipay_account'],
                );
                $data['list'][] = $user_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_user', ' del_flag = 1 and user_type = 3 ');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);

    }
}