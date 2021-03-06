<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:kezi 后台接口客资数据
 */


namespace api\web;

class kezi extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();// 登录态操作
    }

    // 客资列表
    public function keziList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $begin_time = strtotime($this->postString('begin_time'));
        $end_time = strtotime($this->postString('end_time'));
        $order_by = ' order by id desc ';
        $sql_limit = " limit $offset , $limit";
        $where = '';
        if($begin_time){
            $where .= ' and create_time > ' . $begin_time;
        }
        if($end_time){
            $where .= ' and create_time < ' . $end_time;
        }
        $search_input = $this->postInt('search_text');
        if($search_input){
            $where .= " and order_phone like '%$search_input%' ";
        }
        $order = $this->db->getRows("select * from hqsen_kezi_order  where del_flag = 1 $where $order_by " . $sql_limit);
        $data = [];
        foreach ($order as $one_order){
            if($one_order){
                $create_user =  $this->db->getRow("select * from hqsen_user  where id =  " . $one_order['user_id']);
                $create_user_name = $create_user['user_name'];
                if($create_user['user_type'] == 4){
                    $create_user_data =  $this->db->getRow("select * from hqsen_user_data  where user_id =  " . $one_order['user_id']);
                    $create_user_name = $create_user_data['hotel_name'] . '(' . $create_user_name . ')';
                }
                $kezi_item = array(
                    'order_id' => $one_order['id'],
                    'customer_name' => $one_order['customer_name'],
                    'create_user_name' => $create_user_name,
                    'order_phone' => $one_order['order_phone'],
                    'order_type' => $one_order['order_type'],
                    'order_area_hotel_type' => $one_order['order_area_hotel_type'],
                    'create_time' => date('Y-m-d H:i:s', $one_order['create_time']),
                );
                // 如果是1 表示是区域  如果是2 表示是酒店
                if($one_order['order_area_hotel_type'] == 1){
//                    $area = $this->db->getRow("select * from hqsen_area  where id =  " . $one_order['order_area_hotel_id']);
                    $kezi_item['area_hotel_name'] = (string)$this->get_sh_area($one_order['order_area_hotel_id']);
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
                    $kezi_item['area_hotel_name'] = '';
                    foreach ($hotel as $hotel_name){
                        $kezi_item['area_hotel_name'] .= $hotel_name['hotel_name'] . '  ';
                    }
                }
                $data['list'][] = $kezi_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_kezi_order', 'del_flag = 1 ' . $where);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 客资详情
    public function keziDetail(){
        $order_id = $this->postString('id');
        if($order_id){
            $order = $this->db->getRow("select * from hqsen_kezi_order  where del_flag = 1 and id = " . $order_id);
            $order_item = array(
                'id' => $order['id'],
                'customer_name' => $order['customer_name'],
                'order_type' => $order['order_type'],
                'order_phone' => $order['order_phone'],
                'desk_count' => $order['desk_count'],
                'order_money' => $order['order_money'],
                'use_date' => date('Y-m-d', $order['use_date']),
                'watch_user' => $order['watch_user'],
                'order_desc' => $order['order_desc'],
            );
            // 如果是1 表示是区域  如果是2 表示是酒店
            if($order['order_area_hotel_type'] == 1){
//                $area = $this->db->getRow("select * from hqsen_area  where id =  " . $order['order_area_hotel_id']);
                $order_item['area_hotel_name'] = (string)$this->get_sh_area($order['order_area_hotel_id']);
            } else {
                // 多个酒店
                $id_arr = explode(',', $order['order_area_hotel_id']);
                $in_id = [];
                foreach ($id_arr as $one_id){
                    if($one_id){
                        $in_id[] = intval($one_id);
                    }
                }
                $in_id = implode(',', $in_id);
                $hotel = $this->db->getRows("select * from hqsen_hotel  where id in( " . $in_id . ')');
                $order_item['area_hotel_name'] = '';
                foreach ($hotel as $hotel_name){
                    $order_item['area_hotel_name'] .= $hotel_name['hotel_name'] . '  ';
                }
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function getSyncDetail(){
        $kezi_id = $this->postString('id');
        $user_kezi_order = $this->db->getRows("select * from hqsen_user_kezi_order  where kezi_order_id = $kezi_id and order_from != 1 ");
        $sync_info['hotel_names'] = '';
        foreach ($user_kezi_order as $one){
            $sync_info['hotel_names'] .= $one['watch_user_hotel_name'] . ' ,';
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sync_info);
    }
}