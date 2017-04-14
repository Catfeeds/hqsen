<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 接口订单类api user
 */


namespace api\web;

class kezi extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    public function keziList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $order = $this->db->getRows("select * from hqsen_kezi_order  where del_flag = 1 " . $sql_limit);
        $data = [];
        foreach ($order as $one_order){
            if($one_order){
                $kezi_item = array(
                    'order_id' => $one_order['id'],
                    'customer_name' => $one_order['customer_name'],
                    'order_phone' => $one_order['order_phone'],
                    'order_type' => $one_order['order_type'],
                );
                if($one_order['order_area_hotel_type'] == 1){
                    $area = $this->db->getRow("select * from hqsen_area  where id =  " . $one_order['order_area_hotel_id']);
                    $kezi_item['area_hotel_name'] = (string)$area['area_name'];
                } else {
                    $hotel = $this->db->getRow("select * from hqsen_hotel  where id =  " . $one_order['order_area_hotel_id']);
                    $kezi_item['area_hotel_name'] = (string)$hotel['hotel_name'];
                }
                $data['list'][] = $kezi_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_kezi_order', 'del_flag = 1');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function keziDetail(){
        $order_id = $this->postString('id');
        if($order_id){
            $order = $this->db->getRow("select * from hqsen_kezi_order  where del_flag = 1 and id = " . $order_id);
            $order_item = array(
                'id' => $order['id'],
                'customer_name' => $order['customer_name'],
                'order_type' => $order['order_type'],
                'order_phone' => $order['order_phone'],
//                'order_area' => [array('value'=>$order['order_area'] ,'label'=>$order['order_area_name'])],
                'order_area' => '',
//                'order_hotel' => [array('value'=>$order['order_hotel'], 'label'=>$order['order_hotel_name'])],
                'order_hotel' => '',
                'desk_count' => $order['desk_count'],
                'order_money' => $order['order_money'],
                'use_date' => $order['use_date'],
                'watch_user' => $order['watch_user'],
                'order_desc' => $order['order_desc'],
            );

            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }
}