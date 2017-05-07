<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:finance 后台接口 财务类
 */


namespace api\web;

class finance extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    // 酒店列表
    public function keziOrderSignList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sign = $this->db->getRows("select *  from hqsen_user_kezi_order_sign order by id desc " . $sql_limit);
        $data['count'] = $this->db->getCount('hqsen_user_kezi_order_sign', 'del_flag != 0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sign);
    }

    // 酒店列表
    public function dajianOrderSignList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sign = $this->db->getRows("select *  from hqsen_user_dajian_order_sign order by id desc " . $sql_limit);
        $data['count'] = $this->db->getCount('hqsen_user_dajian_order_sign', 'del_flag != 0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sign);
    }



}