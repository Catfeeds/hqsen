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
            $user_order = $this->db->getRow("select *  from hqsen_pay_ratio limit 1");
        }
        $data['count'] = $this->db->getCount('hqsen_user_kezi_order_sign', 'del_flag != 0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sign);

    }



}