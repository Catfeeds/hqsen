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
                $sql_order['id'] = $this->db->insert('hqsen_kezi_order', $sql_order);
                $area_hotel_id_array = explode(',', $order_area_hotel_id);
                $area_hotel_id_array = array_filter($area_hotel_id_array);
                $error_msg = '';
                $error_count = 0;
                foreach ($area_hotel_id_array as $one_area_hotel_id){
                    $order_msg = $this-> insertUserKeZiOrder($sql_order['id'], $order_area_hotel_type, $one_area_hotel_id, $order_phone);
                    if($order_msg){
                        $error_msg .= $order_msg;
                        $error_count++;
                    }
                }
                if($error_count >= count($area_hotel_id_array)) {
                    $del_order['del_flag'] = 2;
                    $this->db->update('hqsen_kezi_order', $del_order, ' id = ' . $sql_order['id']);
                }
                if($error_msg){
                    $this->appDie($this->back_code['order']['kezi_order_fail'], $error_msg);
                } else {
                    $this->appDie();
                }
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 提供方 客资信息列表
    public function orderKeZiList(){
        $order_status = $this->getInt('order_status');
        $order_status = $order_status ? $order_status : $this->postInt('order_status');
        $order_page = $this->getInt('order_page');
        $order_page = $order_page ? $order_page : $this->postInt('order_page', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sql_status = '  user_order_status != 0 ';
        if($order_status){
            $sql_status = '  user_order_status = ' . $order_status;
        }
        $sql_status .= ' and user_id = '. $this->user['id'];
        $order = $this->db->getRows('select * from hqsen_user_kezi_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        if($order){
            foreach ($order as $one_order){
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'create_time' => (string)$one_order['create_time'],
                    'order_status' => (int)$one_order['order_status'],
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user_name'],
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_kezi_order', $sql_status);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    // 跟踪方  客资信息列表
    public function orderHandleKeZiList(){
        $order_status = $this->getInt('order_status');
        $order_status = $order_status ? $order_status : $this->postInt('order_status');
        $order_page = $this->getInt('order_page');
        $order_page = $order_page ? $order_page : $this->postInt('order_page', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sql_status = '  order_status != 0 ';
        if($order_status){
            $sql_status = '  order_status = ' . $order_status;
        }
        $sql_status .= ' and watch_user_id = '. $this->user['id'];
        $order = $this->db->getRows('select * from hqsen_user_kezi_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        if($order){
            foreach ($order as $one_order){
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'create_time' => (string)$one_order['create_time'],
                    'order_status' => (int)$one_order['order_status'],
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user_name'],
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_kezi_order', $sql_status);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    public function orderKeZiDetail(){
        $order_id = $this->getInt('order_id');
        $order_id = $order_id ? $order_id : $this->postInt('order_id');
        if($order_id){
            $user_order = $this->db->getRow('select * from hqsen_user_kezi_order where id = ' . $order_id );
            $order = $this->db->getRow('select * from hqsen_kezi_order where id = ' . $user_order['kezi_order_id'] );
            $order_list['order_item']['id'] = (int)0;
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
//                    $hotel = $this->db->getRow("select * from hqsen_hotel  where id =  " . $order['order_area_hotel_id']);
                    $order_item['order_area_hotel_name'] = (string)$user_order['watch_user_hotel_name'];
                }
                $order_list['order_item'] = $order_item;
                $one_item = $this->db->getRow("select * from hqsen_user_kezi_order_follow where user_kezi_order_id = $order_id order by id desc");
                if($one_item){
                    $follow_item['order_follow_time'] = $one_item['order_follow_time'];
                    $follow_item['order_follow_desc'] = $one_item['order_follow_desc'];
                    $follow_item['order_follow_create_time'] = $one_item['order_follow_create_time'];
                    $follow_item['user_order_status'] = $one_item['user_order_status'];
                    $order_list['order_follow'] = $follow_item;
                }
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

    public function insertUserKeZiOrder($order_id, $area_hotel_type, $area_hotel_id, $order_phone)
    {
        if ($area_hotel_id) {
            if ($area_hotel_type == 1) {
                // 分配订单 需要分配酒店账号 user_type=4
                $user_data = $this->db->getRows("
                    select * from (
                            select hud.* from hqsen_user as hu 
                            left join hqsen_user_data as hud on hu.id=hud.user_id 
                            where hu.user_type=4 and hu.del_flag = 1 and hud.area_id = $area_hotel_id
                            order by last_order_time asc
                        ) as c group by c.hotel_id
                ");
                if(!$user_data){
                    $area = $this->db->getRow('select * from hqsen_area where id =' . $area_hotel_id);
                    $error_message = '区域:' . $area['area_name'] . '客资创建失败';
                    return $error_message;
                }
                foreach ($user_data as $one_user_data) {
                    $one_user_order_sql = [];
                    if ($one_user_data) {
                        $one_user_order_sql['user_id'] = $this->user['id'];
                        $one_user_order_sql['watch_user_name'] = $one_user_data['user_name'];
                        $one_user_order_sql['watch_user_hotel_name'] = $one_user_data['hotel_name'];
                        $one_user_order_sql['watch_user_id'] = $one_user_data['user_id'];
                        $one_user_order_sql['kezi_order_id'] = $order_id;
                        $one_user_order_sql['create_time'] = time();
                        $one_user_order_sql['order_phone'] = $order_phone;
                        $rs = $this->db->insert('hqsen_user_kezi_order', $one_user_order_sql);
                        if($rs){
                            $update_sql['last_order_time'] = time();
                            $this->db->update('hqsen_user_data', $update_sql, ' user_id = ' . $one_user_data['user_id']);
                        }
                    }
                }
            } else {
                $one_user_data = $this->db->getRow("
                    select hud.* from hqsen_user as hu 
                    left join hqsen_user_data as hud on hu.id=hud.user_id 
                    where hu.user_type=4 and hu.del_flag = 1 and hud.hotel_id = $area_hotel_id
                    order by last_order_time asc
                    limit 1
                ");
                $one_user_order_sql = [];
                if ($one_user_data) {
                    $one_user_order_sql['user_id'] = $this->user['id'];
                    $one_user_order_sql['watch_user_name'] = $one_user_data['user_name'];
                    $one_user_order_sql['watch_user_hotel_name'] = $one_user_data['hotel_name'];
                    $one_user_order_sql['watch_user_id'] = $one_user_data['user_id'];
                    $one_user_order_sql['kezi_order_id'] = $order_id;
                    $one_user_order_sql['create_time'] = time();
                    $one_user_order_sql['order_phone'] = $order_phone;
                    $rs = $this->db->insert('hqsen_user_kezi_order', $one_user_order_sql);
                    if($rs){
                        $update_sql['last_order_time'] = time();
                        $this->db->update('hqsen_user_data', $update_sql, ' user_id = ' . $one_user_data['user_id']);
                    }
                } else {
                    $hotel = $this->db->getRow('select * from hqsen_hotel where id =' . $area_hotel_id);
                    $error_message = '酒店:' . $hotel['hotel_name'] . '客资创建失败 ';
                    return $error_message;
                }
            }
            return false;
        }
    }

    // 创建客资订单跟进记录
    public function keziOrderFollow(){
        $user_kezi_order_id = $this->postInt('user_kezi_order_id'); // 订单ID
        $user_order_status = $this->postInt('user_order_status'); // 1有效  2无效  3签单
        $order_follow_time = $this->postString('follow_time');
        $order_follow_desc = $this->postString('follow_desc');

        if($user_order_status and $user_kezi_order_id){
            // 新增跟踪状态
            $order_follow = [];
            $order_follow['user_kezi_order_id'] = $user_kezi_order_id;
            $order_follow['order_follow_time'] = $order_follow_time;
            $order_follow['order_follow_desc'] = $order_follow_desc;
            $order_follow['order_follow_create_time'] = time();
            $order_follow['user_order_status'] = $user_order_status;
            $order_follow['id'] = $this->db->insert('hqsen_user_kezi_order_follow', $order_follow);
            if($user_order_status == 2){ // 无效驳回
                // 更新用户订单  已驳回
                $sql_order['order_status'] = 5;
                $this->db->update('hqsen_user_kezi_order', $sql_order, ' id = ' . $user_kezi_order_id);
            } else if($user_order_status == 3){
                // 更新用户订单  待审核
                $sql_order['order_status'] = 3;
                $this->db->update('hqsen_user_kezi_order', $sql_order, ' id = ' . $user_kezi_order_id);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 创建客资签单详情
    public function keziOrderSign(){
        $user_kezi_order_id = $this->postInt('user_kezi_order_id'); // 订单ID
        $order_money = $this->postString('order_money');
        $order_other_money = $this->postString('order_other_money');
        $sign_using_time = $this->postInt('sign_using_time');
        $sign_pic = $this->postString('sign_pic'); //签单凭证  json
        $sign_pic_arr = explode(',', $sign_pic);
        $sign_pic_json = json_encode($sign_pic_arr);
        $user_kezi_order_sign = $this->db->getRow('select * from hqsen_user_kezi_order_sign where user_kezi_order_id = ' . $user_kezi_order_id );
        $user_kezi_order_sign['order_money'] = $order_money;
        $user_kezi_order_sign['order_other_money'] = $order_other_money;
        $user_kezi_order_sign['sign_using_time'] = $sign_using_time;
        $user_kezi_order_sign['sign_pic'] = $sign_pic_json;
        $user_kezi_order_sign['user_kezi_order_id'] = $user_kezi_order_id;
        if($user_kezi_order_id){
            if(isset($user_kezi_order_sign['id']) and $user_kezi_order_sign['id']){
                $this->db->update('hqsen_user_kezi_order_sign', $user_kezi_order_sign, ' id = ' . $user_kezi_order_sign['id']);
            } else {
                $this->db->insert('hqsen_user_kezi_order_sign', $user_kezi_order_sign);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 客资签单详情
    public function keziOrderSignDetail(){
        $user_kezi_order_id = $this->postInt('user_kezi_order_id'); // 订单ID
        $user_kezi_order_sign = $this->db->getRow('select * from hqsen_user_kezi_order_sign where user_kezi_order_id = ' . $user_kezi_order_id );
        $sign_item = [];
        if ($user_kezi_order_sign){
            $sign_item['order_money'] = $user_kezi_order_sign['order_money'];
            $sign_item['order_other_money'] = $user_kezi_order_sign['order_other_money'];
            $sign_item['sign_using_time'] = $user_kezi_order_sign['sign_using_time'];
            $sign_item['sign_pic'] = implode(',' ,json_decode($user_kezi_order_sign['sign_pic']));
            $sign_item['user_kezi_order_id'] = $user_kezi_order_sign['user_kezi_order_id'];
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sign_item);
    }

    //客资 订单跟进记录列表
    public function keziOrderFollowList(){
        $user_kezi_order_id = $this->postInt('user_kezi_order_id'); // 订单ID
        if($user_kezi_order_id){
            $user_kezi_order_follow_list = $this->db->getRows("select * from hqsen_user_kezi_order_follow where user_kezi_order_id = $user_kezi_order_id ");
            $back_follows = [];
            if($user_kezi_order_follow_list){
                foreach ($user_kezi_order_follow_list as $one_item){
                    $follow_item['order_follow_time'] = $one_item['order_follow_time'];
                    $follow_item['order_follow_desc'] = $one_item['order_follow_desc'];
                    $follow_item['order_follow_create_time'] = $one_item['order_follow_create_time'];
                    $follow_item['user_order_status'] = $one_item['user_order_status'];
                    $back_follows[] = $follow_item;
                }
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $back_follows);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 酒店账号才可以创建搭建信息
    public function createDaJian(){
        if($this->user['user_type'] != 4){
            $this->appDie($this->back_code['order']['dajian_order_right'], $this->back_msg['order']['dajian_order_right']);
        }
        $customer_name = $this->postString('customer_name');
        $order_type = $this->postInt('order_type');
        $order_phone = $this->postInt('order_phone');
        $order_area_hotel_type = $this->postString('order_area_hotel_type');
        $order_area_hotel_id = $this->postString('order_area_hotel_id');
        $order_money = $this->postInt('order_money');
        $use_date = $this->postString('use_date');
        $order_desc = $this->postString('order_desc');
        if($order_type and $order_phone and $order_area_hotel_type and $order_area_hotel_id){
            $order = $this->db->getRow('select * from hqsen_dajian_order where order_type = ' . $order_type . ' and order_phone = '. $order_phone);
            if($order){
                $this->appDie($this->back_code['order']['phone_type_exist'], $this->back_msg['order']['phone_type_exist']);
            } else {
                $sql_order['customer_name'] = $customer_name;
                $sql_order['order_type'] = $order_type;
                $sql_order['order_phone'] = $order_phone;
                $sql_order['order_area_hotel_type'] = $order_area_hotel_type;
                $sql_order['order_area_hotel_id'] = $order_area_hotel_id;
                $sql_order['use_date'] = $use_date;
                $sql_order['order_money'] = $order_money;
                $sql_order['order_desc'] = $order_desc;
                $sql_order['order_status'] = 1;
                $sql_order['create_time'] = time();
                $sql_order['user_id'] = $this->user['id'];
                $sql_order['id'] = $this->db->insert('hqsen_dajian_order', $sql_order);
                $area_hotel_id_array = explode(',', $order_area_hotel_id);
                $area_hotel_id_array = array_filter($area_hotel_id_array);
                $error_msg = '';
                $error_count = 0;
                // 目前区域单选  兼容多选
                foreach ($area_hotel_id_array as $one_area_hotel_id){
                    $order_msg = $this-> insertUserDaJianOrder($sql_order['id'], $order_area_hotel_type, $one_area_hotel_id, $order_phone);
                    if($order_msg){
                        $error_msg .= $order_msg;
                        $error_count++;
                    }
                }
                // 如果创建失败   那么删除订单
                if($error_count >= count($area_hotel_id_array)) {
                    $del_order['del_flag'] = 2;
                    $this->db->update('hqsen_dajian_order', $del_order, ' id = ' . $sql_order['id']);
                }

                if($error_msg){
                    $this->appDie($this->back_code['order']['dajian_order_fail'], $error_msg);
                } else {
                    $this->appDie();
                }
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    public function orderDaJianList(){
        $order_status = $this->getInt('order_status');
        $order_status = $order_status ? $order_status : $this->postInt('order_status');
        $order_page = $this->getInt('order_page');
        $order_page = $order_page ? $order_page : $this->postInt('order_page', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sql_status = '  order_status != 0 ';
        if($order_status){
            $sql_status = '  order_status = ' . $order_status;
        }
        // todo 首销 二销 分别处理
        if($this->user['user_type'] == 11){
            $sql_status .= ' and watch_user_id = '. $this->user['id'];
        } else {
            $sql_status .= ' and user_id = '. $this->user['id'];
        }
        $order = $this->db->getRows('select * from hqsen_user_dajian_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        if($order){
            foreach ($order as $one_order){
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'create_time' => (string)$one_order['create_time'],
                    'order_status' => (int)$one_order['order_status'],
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user_name'],
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_dajian_order', $sql_status);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    // 搭建订单详情
    public function orderDaJianDetail(){
        $order_id = $this->getInt('order_id');
        $order_id = $order_id ? $order_id : $this->postInt('order_id');
        if($order_id){
            $order = $this->db->getRow('select * from hqsen_dajian_order where id = ' . $order_id );
            $order_list['order_item']['id'] = (int)0;
            if($order){
                $order_item = array(
                    'id' => (int)$order['id'],
                    'create_time' => (string)$order['create_time'],
                    'order_phone' => (string)$order['order_phone'],
                    'customer_name' => (string)$order['customer_name'],
                    'order_type' => (int)$order['order_type'],
                    'order_area_hotel_type' => (int)$order['order_area_hotel_type'],
                    'order_area_hotel_id' => (int)$order['order_area_hotel_id'],
                    'order_money' => (string)$order['order_money'],
                    'use_date' => (string)$order['use_date'],
                    'order_desc' => (string)$order['order_desc'],
                );
                $area = $this->db->getRow("select * from hqsen_area  where id =  " . $order['order_area_hotel_id']);
                $order_item['order_area_hotel_name'] = (string)$area['area_name'];
                $order_list['order_item'] = $order_item;
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
        }
    }

    public function validatePhoneDaJianOrderType(){
        $order_type = $this->postInt('order_type');
        $order_phone = $this->postInt('order_phone');
        if($order_type and $order_phone){
            $order = $this->db->getRow('select * from hqsen_dajian_order where order_type = ' . $order_type . ' and order_phone = '. $order_phone);
            if($order){
                $this->appDie($this->back_code['order']['phone_type_exist'], $this->back_msg['order']['phone_type_exist']);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function insertUserDaJianOrder($order_id, $area_hotel_type, $area_hotel_id, $order_phone)
    {
        if ($area_hotel_id) {
            if ($area_hotel_type == 1) {
                // 区域信息  自动分配首销账号 user_id=11
                $user_data = $this->db->getRows("
                    select hud.* from hqsen_user as hu 
                    left join hqsen_user_data as hud on hu.id=hud.user_id 
                    where hu.user_type=11
                    and hu.del_flag = 1 
                    and hud.area_id = $area_hotel_id 
                    order by last_order_time asc
                ");
                if(!$user_data){
                    $area = $this->db->getRow('select * from hqsen_area where id =' . $area_hotel_id);
                    $error_message = '区域:' . $area['area_name'] . '搭建信息创建失败';
                    return $error_message;
                }
                foreach ($user_data as $one_user_data) {
                    $one_user_order_sql = [];
                    if ($one_user_data) {
                        $one_user_order_sql['user_id'] = $this->user['id'];
                        $one_user_order_sql['watch_user_name'] = $one_user_data['user_name'];
                        $one_user_order_sql['watch_user_hotel_name'] = $one_user_data['hotel_name'];
                        $one_user_order_sql['watch_user_id'] = $one_user_data['user_id'];
                        $one_user_order_sql['dajian_order_id'] = $order_id;
                        $one_user_order_sql['create_time'] = time();
                        $one_user_order_sql['order_phone'] = $order_phone;
                        $rs = $this->db->insert('hqsen_user_dajian_order', $one_user_order_sql);
                        if($rs){
                            $update_sql['last_order_time'] = time();
                            $this->db->update('hqsen_user_data', $update_sql, ' user_id = ' . $one_user_data['user_id']);
                        }
                    }
                }
            }
            return false;
        }
    }

    // 首销账号才可以录入跟进创建搭建订单跟进记录
    public function dajianOrderFollow(){
        $user_kezi_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        $user_order_status = $this->postInt('user_order_status'); // 1有效  2无效  3签单
        $order_follow_time = $this->postString('follow_time');
        $order_follow_desc = $this->postString('follow_desc');

        if($user_order_status and $user_kezi_order_id){
            // 新增跟踪状态
            $order_follow = [];
            $order_follow['user_dajian_order_id'] = $user_kezi_order_id;
            $order_follow['order_follow_time'] = $order_follow_time;
            $order_follow['order_follow_desc'] = $order_follow_desc;
            $order_follow['order_follow_create_time'] = time();
            $order_follow['user_order_status'] = $user_order_status;
            $order_follow['id'] = $this->db->insert('hqsen_user_dajian_order_follow', $order_follow);
            if($user_order_status == 2){ // 无效驳回
                // 更新用户订单  已驳回
                $sql_order['order_status'] = 5;
                $this->db->update('hqsen_user_dajian_order', $sql_order, ' id = ' . $user_kezi_order_id);
            } else if($user_order_status == 3){
                // 更新用户订单  待审核
                $sql_order['order_status'] = 3;
                $this->db->update('hqsen_user_dajian_order', $sql_order, ' id = ' . $user_kezi_order_id);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 首销账号才可以创建客资签单详情
    public function dajianOrderSign(){
        $user_dajian_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        $order_money = $this->postString('order_money');
        $sign_using_time = $this->postInt('sign_using_time');
        $first_order_money = $this->postString('first_order_money');
        $first_order_using_time = $this->postString('first_order_using_time');
        $next_pay_time = $this->postString('next_pay_time');
        $sign_pic = $this->postString('sign_pic'); //签单凭证  json
        $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_sign where user_dajian_order_id = ' . $user_dajian_order_id );
        $user_dajian_order_sign['user_dajian_order_id'] = $user_dajian_order_id;
        $user_dajian_order_sign['order_money'] = $order_money;
        $user_dajian_order_sign['sign_using_time'] = $sign_using_time;
        $user_dajian_order_sign['sign_pic'] = $sign_pic;
        $user_dajian_order_sign['first_order_money'] = $first_order_money;
        $user_dajian_order_sign['first_order_using_time'] = $first_order_using_time;
        $user_dajian_order_sign['next_pay_time'] = $next_pay_time;
        $user_dajian_order_sign['order_time'] = time();// 订单创建时间
        $user_dajian_order_sign['sign_user_id'] = $this->user['id'];// 订单创建时间
        if($user_dajian_order_id){
            if(isset($user_dajian_order_sign['id']) and $user_dajian_order_sign['id']){
                $this->db->update('hqsen_user_dajian_order_sign', $user_dajian_order_sign, ' id = ' . $user_dajian_order_sign['id']);
            } else {
                $this->db->insert('hqsen_user_dajian_order_sign', $user_dajian_order_sign);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 二销账号才可以创建客资签单其他详情
    public function dajianOrderSignOther(){
        // todo 修改可以创建多条
        $user_dajian_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        $sign_type = $this->postInt('sign_type');// 1 中款 2尾款 3附加款
        $order_other_money = $this->postString('order_money');
        $order_other_time = $this->postInt('order_time');
        $order_other_sign_pic = $this->postString('order_sign_pic'); //签单凭证  json
        $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_other_sign where user_dajian_order_id = ' . $user_dajian_order_id );
        $user_dajian_order_sign['sign_type'] = $sign_type;
        $user_dajian_order_sign['user_dajian_order_id'] = $user_dajian_order_id;
        $user_dajian_order_sign['order_money'] = $order_other_money;
        $user_dajian_order_sign['order_time'] = $order_other_time;
        $user_dajian_order_sign['order_sign_pic'] = $order_other_sign_pic;
        if($user_dajian_order_id){
            if(isset($user_dajian_order_sign['id']) and $user_dajian_order_sign['id']){
                $this->db->update('hqsen_user_dajian_order_other_sign', $user_dajian_order_sign, ' id = ' . $user_dajian_order_sign['id']);
            } else {
                $this->db->insert('hqsen_user_dajian_order_other_sign', $user_dajian_order_sign);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 搭建订单日志列表
    public function dajianOrderFollowList(){
        $user_dajian_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        if($user_dajian_order_id){
            $user_dajian_order_follow_list = $this->db->getRows("select * from hqsen_user_dajian_order_follow where user_dajian_order_id = $user_dajian_order_id ");
            $back_follows = [];
            if($user_dajian_order_follow_list){
                foreach ($user_dajian_order_follow_list as $one_item){
                    $follow_item['order_follow_time'] = $one_item['order_follow_time'];
                    $follow_item['order_follow_desc'] = $one_item['order_follow_desc'];
                    $follow_item['order_follow_create_time'] = $one_item['order_follow_create_time'];
                    $follow_item['user_order_status'] = $one_item['user_order_status'];
                    $back_follows[] = $follow_item;
                }
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $back_follows);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


}