<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:kezi 后台接口客资数据
 */


namespace api\web;

class dajian extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();// 登录态操作
    }

    // 客资列表
    public function dajianList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";

        $begin_time = strtotime($this->postString('begin_time'));
        $end_time = strtotime($this->postString('end_time'));
        $where = '';
        if($begin_time){
            $where .= ' and create_time > ' . $begin_time;
        }
        if($end_time){
            $where .= ' and create_time < ' . $end_time;
        }

        $order = $this->db->getRows("select * from hqsen_dajian_order  where del_flag = 1 $where order by id desc " . $sql_limit);
        $data = [];
        foreach ($order as $one_order){
            if($one_order){
                $item_come_from = array(
                    '1' => '用户创建',
                    '2' => '同步',
                );
                $create_user =  $this->db->getRow("select * from hqsen_user  where id =  " . $one_order['user_id']);
                $create_user_name = $create_user['user_name'];
                if($create_user['user_type'] == 4){
                    $create_user_data =  $this->db->getRow("select * from hqsen_user_data  where user_id =  " . $one_order['user_id']);
                    $create_user_name = $create_user_data['hotel_name'] . '(' . $create_user_name . ')';
                }
                $dajian_item = array(
                    'order_id' => $one_order['id'],
                    'customer_name' => $one_order['customer_name'],
                    'create_user_name' => $create_user_name,
                    'order_phone' => $one_order['order_phone'],
                    'create_time' => date('Y-m-d H:i:s', $one_order['create_time']),
                    'order_type' => $one_order['order_type'],
                    'order_area_hotel_type' => '指定区域',
                    'order_from' => $item_come_from[$one_order['order_from']],
                );
                // 如果是1 表示是区域  如果是2 表示是酒店
                if($one_order['order_area_hotel_type'] == 1){
                    $area = $this->db->getRow("select * from hqsen_area  where id =  " . $one_order['order_area_hotel_id']);
                    $dajian_item['area_hotel_name'] = (string)$area['area_name'];
                } else {
                    // 多个酒店
                    $id_arr = explode(',', $one_order['order_area_hotel_id']);
                    $in_id = [];
                    foreach ($id_arr as $one_id){
                        if($one_id){
                            $in_id[] = intval($one_id);
                        }
                    }
                    $in_id = implode(',', $in_id);
                    $hotel = $this->db->getRows("select * from hqsen_hotel  where id in( " . $in_id . ')');
                    $dajian_item['area_hotel_name'] = '';
                    foreach ($hotel as $hotel_name){
                        $dajian_item['area_hotel_name'] .= $hotel_name['hotel_name'] . '  ';
                    }
                }
                $data['list'][] = $dajian_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_dajian_order', 'del_flag = 1 ' . $where);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 客资详情
    public function dajianDetail(){
        $order_id = $this->postString('id');
        if($order_id){
            $order = $this->db->getRow("select * from hqsen_dajian_order  where del_flag = 1 and id = " . $order_id);
            $area = $this->db->getRow("select * from hqsen_area  where id =  " . $order['order_area_hotel_id']);
            $order_item = array(
                'id' => $order['id'],
                'customer_name' => $order['customer_name'],
                'order_type' => $order['order_type'],
                'order_phone' => $order['order_phone'],
//                'order_area' => [array('value'=>$order['order_area_hotel_id'],'label'=>$area['area_name'])],
                'order_area' => $area['area_name'],
                'order_money' => $order['order_money'],
                'use_date' => date('Y-m-d',$order['use_date']),
                'order_desc' => $order['order_desc'],
            );

            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }
}