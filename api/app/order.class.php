<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 接口订单类api user
 */


namespace api\app;

class order extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    //
    public function validatePhoneOrderType(){
        $order_type = $this->postInt('order_type');
        $order_phone = $this->postInt('order_phone');
        if($order_type and $order_phone){
            $order = $this->db->getRow('select * from hqsen_kezi_order where order_type = ' . $order_type . ' and order_phone = '. $order_phone);
            if($order){
                $this->appDie($this->back_code['order']['phone_type_exist'], $this->back_msg['order']['phone_type_exist']);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function createKeZi(){
        $customer_name = $this->postString('customer_name');
        $order_type = $this->postInt('order_type');
        $order_phone = $this->postInt('order_phone');
        $order_area_hotel_type = $this->postString('order_area_hotel_type');
        $order_area_hotel_id = $this->postString('order_area_hotel_id');
        $desk_count = $this->postInt('desk_count');
        $order_money = $this->postInt('order_money');
        $use_date = $this->postString('use_date');
        $watch_user = $this->postString('watch_user');
        $order_desc = $this->postString('order_desc');
        if($order_type and $order_phone and $order_area_hotel_type and $order_area_hotel_id){
            $order = $this->db->getRow('select * from hqsen_kezi_order where order_type = ' . $order_type . ' and order_phone = '. $order_phone);
            if($order){
                $this->appDie($this->back_code['order']['phone_type_exist'], $this->back_msg['order']['phone_type_exist']);
            } else {
                $sql_order['customer_name'] = $customer_name;
                $sql_order['order_type'] = $order_type;
                $sql_order['order_phone'] = $order_phone;
                $sql_order['order_area_hotel_type'] = $order_area_hotel_type;
                $sql_order['order_area_hotel_id'] = $order_area_hotel_id;
                $sql_order['desk_count'] = $desk_count;
                $sql_order['use_date'] = $use_date;
                $sql_order['order_money'] = $order_money;
                $sql_order['watch_user'] = $watch_user;
                $sql_order['order_desc'] = $order_desc;
                $sql_order['order_status'] = 1;
                $sql_order['create_time'] = time();
                $sql_order['user_id'] = $this->user['id'];
                $sql = $this->db->insert('hqsen_kezi_order', $sql_order);
                $this->appDie();
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    public function orderKeZiList(){
        $order_status = $this->getInt('order_status');
        $order_status = $order_status ? $order_status : $this->postInt('order_status');
        $order_page = $this->getInt('order_page', 1);
        $order_page = $order_page ? $order_page : $this->postInt('order_page', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sql_status = ' where order_status != 0 ';
        if($order_status){
            $sql_status = ' where order_status = ' . $order_status;
        }
        $sql_status .= ' and user_id = '. $this->user['id'];
        $order = $this->db->getRows('select * from hqsen_kezi_order ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        if($order){
            foreach ($order as $one_order){
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'create_time' => (string)$one_order['create_time'],
                    'order_status' => (int)$one_order['order_status'],
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user'],
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    public function orderKeZiDetail(){
        $order_id = $this->getInt('order_id');
        $order_id = $order_id ? $order_id : $this->postInt('order_id');
        if($order_id){
            $order = $this->db->getRow('select * from hqsen_kezi_order where id = ' . $order_id );
            $order_list['order_item'] = [];
            if($order){
                $order_item = array(
                    'id' => (int)$order['id'],
                    'create_time' => (string)$order['create_time'],
                    'order_status' => (int)$order['order_status'],
                    'order_phone' => (string)$order['order_phone'],
                    'watch_user' => (string)$order['watch_user'],
                    'customer_name' => (string)$order['customer_name'],
                    'order_type' => (int)$order['order_type'],
                    'order_area_hotel_type' => (int)$order['order_area_hotel_type'],
                    'order_area_hotel_id' => (int)$order['order_area_hotel_id'],
                    'desk_count' => (string)$order['desk_count'],
                    'order_money' => (string)$order['order_money'],
                    'use_date' => (string)$order['use_date'],
                    'order_desc' => (string)$order['order_desc'],
                );
                if($order['order_area_hotel_type'] == 1){
                    $area = $this->db->getRow("select * from hqsen_area  where id =  " . $order['order_area_hotel_id']);
                    $order_item['order_area_hotel_name'] = (string)$area['area_name'];
                } else {
                    $hotel = $this->db->getRow("select * from hqsen_hotel  where id =  " . $order['order_area_hotel_id']);
                    $order_item['order_area_hotel_name'] = (string)$hotel['hotel_name'];
                }
                $order_list['order_item'] = $order_item;
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
        }
    }

    public function orderHotelArea(){
        $hotel_area_type = $this->postInt('hotel_area_type');// 1区域列表 2酒店列表
        $list['area_list'] = [];
        $list['hotel_list'] = [];
        if($hotel_area_type == 1){
            $area = $this->db->getRows("select *  from hqsen_area  where del_flag = 1 order by id desc ");
            foreach ($area as $one_area){
                if($one_area){
                    $area_item = array(
                        'area_id' => (int)$one_area['id'],
                        'area_name' => (string)$one_area['area_name'],
                    );
                    $list['area_list'][] = $area_item;
                }
            }
        } else {
            $hotel = $this->db->getRows("select *  from hqsen_hotel  where del_flag = 1 order by id desc ");
            foreach ($hotel as $one_hotel){
                if($one_hotel){
                    $hotel_item = array(
                        'hotel_id' => $one_hotel['id'],
                        'hotel_name' => $one_hotel['hotel_name'],
                    );
                    $list['hotel_list'][] = $hotel_item;
                }
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $list);
    }


}