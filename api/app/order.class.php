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
        $this-> loginInit();// 需要登录态
    }

    // 验证手机是否可以创建客资订单
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

    // 创建客资订单
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
            $order = $this->db->getRow('select * from hqsen_kezi_order where  del_flag = 1 and order_type = ' . $order_type . ' and order_phone = '. $order_phone);
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
                // 创建客资订单成功之后   分配给选择的酒店 或者选择区域下的酒店 的酒店账号
                foreach ($area_hotel_id_array as $one_area_hotel_id){
                    // 针对酒店账号   生成一个针对酒店账号的客资订单
                    $order_msg = $this-> insertUserKeZiOrder($sql_order['id'], $order_area_hotel_type, $one_area_hotel_id, $order_phone);
                    if($order_msg){
                        $error_msg .= $order_msg;
                        $error_count++;
                    }
                }
                // 如果没有一个酒店账号创建成功订单  那么删除订单
                if($error_count >= count($area_hotel_id_array)) {
                    $del_order['del_flag'] = 2;
                    $this->db->update('hqsen_kezi_order', $del_order, ' id = ' . $sql_order['id']);
                }
                // 出现一个酒店错误  就报错
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
        $order_status = $this->getInt('order_status');// 客资提供者状态 1跟进中 2待结算 3已结算 4已取消
        $order_status = $order_status ? $order_status : $this->postInt('order_status');
        $order_page = $this->getInt('order_page');
        $order_page = $order_page ? $order_page : $this->postInt('order_page', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " order by update_time asc limit $offset , $limit";
        $sql_status = '  user_order_status != 0 ';
        if($order_status){
            $sql_status = '  user_order_status = ' . $order_status;
        }
        $sql_status .= ' and user_id = '. $this->user['id'];// 以注册用户纬度获取  客资列表信息
        $order = $this->db->getRows('select * from hqsen_user_kezi_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        if($order){
            foreach ($order as $one_order){
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'create_time' => (string)$one_order['create_time'],
                    'order_status' => (int)$one_order['user_order_status'],// 需要返回提供者状态   不搞给错了
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user_name'],
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_kezi_order', $sql_status); // 总的订单数
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    // 跟踪方  客资信息列表
    public function orderHandleKeZiList(){
        $order_status = $this->getInt('order_status'); // 客资跟踪者订单状态1待处理 2待审核 3待结算 4已结算 5已驳回 6已取消
        $order_status = $order_status ? $order_status : $this->postInt('order_status');
        $order_page = $this->getInt('order_page');
        $order_page = $order_page ? $order_page : $this->postInt('order_page', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = "  order by update_time asc limit $offset , $limit";
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

    // 客资订单详情
    public function orderKeZiDetail(){
        $order_id = $this->getInt('order_id');
        $order_id = $order_id ? $order_id : $this->postInt('order_id');
        $detail_type = $this->postInt('detail_type'); // 1客资提供者 2客资跟踪者
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
                // 提供者 1
                if($detail_type == 1){
                    switch ($order['order_status']){
                        case 1:
                            $order_list['handle_note'] = '跟进中(跟踪者处理中)';
                            break;
                        case 2:
                            $order_list['handle_note'] = '待结算(等待财务打款)';
                            break;
                        case 3:
                            $order_list['handle_note'] = '已结算(财务已打款)';
                            break;
                        case 4:
                            if('$财务 通过的审批 案$'){
                                $order_list['handle_note'] = '已取消($财务 通过的审批 案$)';
                            } else {
                                $order_list['handle_note'] = '该客资信息已被取消,后续无法继续跟进';
                            }
                            break;
                    }
                    // 跟踪者 2
                } else if($detail_type == 2){
                    switch ($order['order_status']){
                        case 1:
                            $order_list['handle_note'] = '无';
                            break;
                        case 2:
                            $order_list['handle_note'] = '该搭建合同正在被审核,请耐 等待^_^';
                            break;
                        case 3:
                            $order_list['handle_note'] = '相关的奖励即将发放给提供搭建信息者';
                            break;
                        case 4:
                            $order_list['handle_note'] = '关奖励已经发放';
                            break;
                        case 5:
                            $order_list['handle_note'] = '客资合同待重新提交:$财务待修改的审批 案$';
                            break;
                        case 6:
                            if('$财务 通过的审批 案$'){
                                $order_list['handle_note'] = '已取消($财务 通过的审批 案$)';
                            } else {
                                $order_list['handle_note'] = '该客资信息已被取消,后续无法继续跟进';
                            }
                            break;
                    }
                } else {
                    $order_list['handle_note'] = $order['order_status'];
                }
                $order_list['handle_time'] = $order['create_time'];
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
                        $one_user_order_sql['update_time'] = time();
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
                    $one_user_order_sql['update_time'] = time();
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
                // 更新用户订单  已取消
                $sql_order['order_status'] = 6;
                $sql_order['user_order_status'] = 4;
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
            // 处理跟踪者和提供者订单状态
            $user_order['order_status'] = 2;
            $this->db->update('hqsen_user_kezi_order', $user_order, ' id = ' . $user_kezi_order_sign['user_kezi_order_id']);
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
            $order = $this->db->getRow('select * from hqsen_dajian_order where del_flag = 1 and order_type = ' . $order_type . ' and order_phone = '. $order_phone);
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
        $sql_limit = "  order by create_time desc  limit $offset , $limit";
        $sql_status = '  order_status != 0 ';
        // 11 首销 12 二销 其他 type 默认提供者
        if($this->user['user_type'] == 11){
            if($order_status){
                $sql_status = '  order_status = ' . $order_status;
            }
            $sql_status .= ' and watch_user_id = '. $this->user['id'];

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
        } elseif($this->user['user_type'] == 12){
            if($order_status){
                $sql_status = ' erxiao_order_status = ' . $order_status;//搭建二销状态 0首销还未通过 1待处理 2待审核 3已完结 5已驳回
            }
            $sql_status .= ' and erxiao_user_id = '. $this->user['id'];

            $order = $this->db->getRows('select * from hqsen_user_dajian_order where ' . $sql_status . $sql_limit);
            $order_list['order_list'] = [];
            if($order){
                foreach ($order as $one_order){
                    $order_item = array(
                        'id' => (int)$one_order['id'],
                        'create_time' => (string)$one_order['create_time'],
                        'order_status' => (int)$one_order['erxiao_order_status'],
                        'erxiao_sign_type' => (int)$one_order['erxiao_sign_type'],
                        'order_phone' => (string)$one_order['order_phone'],
                        'watch_user' => (string)$one_order['watch_user_name'],
                    );
                    $order_list['order_list'][] = $order_item;
                }

            }
        } else {
            if($order_status){
                $sql_status = '  user_order_status = ' . $order_status;
            }
            $sql_status .= ' and user_id = '. $this->user['id'];

            $order = $this->db->getRows('select * from hqsen_user_dajian_order where ' . $sql_status . $sql_limit);
            $order_list['order_list'] = [];
            if($order){
                foreach ($order as $one_order){
                    $order_item = array(
                        'id' => (int)$one_order['id'],
                        'create_time' => (string)$one_order['create_time'],
                        'order_status' => (int)$one_order['user_order_status'],
                        'order_phone' => (string)$one_order['order_phone'],
                        'watch_user' => (string)$one_order['watch_user_name'],
                    );
                    $order_list['order_list'][] = $order_item;
                }

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
            $user_order = $this->db->getRow('select * from hqsen_user_dajian_order where id = ' . $order_id );
            $order = $this->db->getRow('select * from hqsen_dajian_order where id = ' . $user_order['dajian_order_id'] );
            $order_list['order_item']['id'] = (int)0;
            if($order){
                $order_item = array(
                    'id' => (int)$order_id,
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
                if($this->user['user_type'] == 11){
                    switch ($user_order['order_status']){
                        case 1:
                            $order_list['handle_note'] = '无';
                            break;
                        case 2:
                            $order_list['handle_note'] = '该搭建合同正在被审核,请耐 等待^_^';
                            break;
                        case 3:
                            $order_list['handle_note'] = '相关的奖励即将发放给提供搭建信息者';
                            break;
                        case 4:
                            $order_list['handle_note'] = '关奖励已经发放给提供搭建信息者';
                            break;
                        case 5:
                            $order_list['handle_note'] = '搭建合同待重新提交:$财务待修改的审批 案$';
                            break;
                        case 6:
                            if('$财务 通过的审批 案$'){
                                $order_list['handle_note'] = '已取消($财务 通过的审批 案$)';
                            } else {
                                $order_list['handle_note'] = '该搭建信息已被你取消,后续无法继续跟进';
                            }
                            break;
                    }
                } else if($this->user['user_type'] == 12){
                    switch ($user_order['order_status']){
                        case 1:
                            $order_list['handle_note'] = '无';
                            break;
                        case 2:
                            $order_list['handle_note'] = '正在被审核,请耐 等待^_^';
                            break;
                        case 3:
                            $order_list['handle_note'] = '搭建订单已完结';
                            break;
                        case 4:
                            $order_list['handle_note'] = '待重新提交:$财务待修改的审批 案$';
                            break;
                    }
                } else if($this->user['user_type'] == 4){
                    switch ($user_order['order_status']){
                        case 1:
                            $order_list['handle_note'] = '跟进中(专员处中)';
                            break;
                        case 2:
                            $order_list['handle_note'] = '待结算(等待财务打款)';
                            break;
                        case 3:
                            $order_list['handle_note'] = '已结算(财务已打款)';
                            break;
                        case 4:
                            if('$财务 通过的审批 案$'){
                                $order_list['handle_note'] = '已取消($财务 通过的审批 案$)';
                            } else {
                                $order_list['handle_note'] = '已取消(专员已终 搭建跟进)';
                            }
                            break;
                    }
                } else {
                    $order_list['handle_note'] = $user_order['user_order_status'];// 默认提供者状态
                }
                $order_list['handle_time'] = $order['create_time'];
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
                // 区域信息  自动分配首销账号 user_type=11
                $user_data = $this->db->getRow("
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
                $one_user_order_sql = [];
                if ($user_data) {
                    $one_user_order_sql['user_id'] = $this->user['id'];
                    $one_user_order_sql['watch_user_name'] = $user_data['user_name'];
                    $one_user_order_sql['watch_user_hotel_name'] = $user_data['hotel_name'];
                    $one_user_order_sql['watch_user_id'] = $user_data['user_id'];
                    $one_user_order_sql['dajian_order_id'] = $order_id;
                    $one_user_order_sql['create_time'] = time();
                    $one_user_order_sql['update_time'] = time();
                    $one_user_order_sql['order_phone'] = $order_phone;
                    $rs = $this->db->insert('hqsen_user_dajian_order', $one_user_order_sql);
                    if($rs){
                        $update_sql['last_order_time'] = time();
                        $this->db->update('hqsen_user_data', $update_sql, ' user_id = ' . $user_data['user_id']);
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
            $sql_order['update_time'] = intval($order_follow_time);
            if($user_order_status == 2){ // 已取消
                // 更新用户订单  已取消
                $sql_order['order_status'] = 6;
                $sql_order['user_order_status'] = 4;
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
        $sign_pic_arr = explode(',', $sign_pic);
        $sign_pic_json = json_encode($sign_pic_arr);
        $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_sign where user_dajian_order_id = ' . $user_dajian_order_id );
        $user_dajian_order_sign['user_dajian_order_id'] = $user_dajian_order_id;
        $user_dajian_order_sign['order_money'] = $order_money;
        $user_dajian_order_sign['sign_using_time'] = $sign_using_time;
        $user_dajian_order_sign['sign_pic'] = $sign_pic_json;
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
            // 更新首销订单 关于二销信息
            $order_type_sql['order_status'] = 2;
            $this->db->update('hqsen_user_dajian_order', $order_type_sql, ' id = ' . $user_dajian_order_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 首销 合同审核详情
    public function dajianOrderSignDetail(){
        $user_dajian_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        if($user_dajian_order_id){
            $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_sign where user_dajian_order_id = ' . $user_dajian_order_id );
            if($user_dajian_order_sign){
                $sign_item['order_money'] = $user_dajian_order_sign['order_money'];
                $sign_item['sign_using_time'] = $user_dajian_order_sign['sign_using_time'];
                $sign_item['first_order_money'] = $user_dajian_order_sign['first_order_money'];
                $sign_item['first_order_using_time'] = $user_dajian_order_sign['first_order_using_time'];
                $sign_item['sign_pic'] = $user_dajian_order_sign['sign_pic'] ? json_decode($user_dajian_order_sign['sign_pic'], true) : [];
                $sign_item['next_pay_time'] = $user_dajian_order_sign['next_pay_time'] ;
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sign_item);
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
        $sign_type = $this->postInt('sign_type');// 1 中款 2尾款 3附加款 4尾款时间
        $order_other_money = $this->postString('order_money');
        $order_other_time = $this->postInt('order_time');
        $order_other_sign_pic = $this->postString('order_sign_pic'); //签单凭证  json
        $sign_pic_arr = explode(',', $order_other_sign_pic);
        $sign_pic_json = json_encode($sign_pic_arr);
        if($sign_type != 3){
            $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_other_sign where user_dajian_order_id = ' . $user_dajian_order_id . ' and  sign_type = ' . $sign_type );
        }
        $user_dajian_order_sign['sign_type'] = $sign_type;
        $user_dajian_order_sign['user_dajian_order_id'] = $user_dajian_order_id;
        $user_dajian_order_sign['order_money'] = $order_other_money;
        $user_dajian_order_sign['order_time'] = $order_other_time;
        $user_dajian_order_sign['order_sign_pic'] = $sign_pic_json;
        if($user_dajian_order_id){
            if(isset($user_dajian_order_sign['id']) and $user_dajian_order_sign['id']){
                $this->db->update('hqsen_user_dajian_order_other_sign', $user_dajian_order_sign, ' sign_type = ' . $sign_type . ' and id = ' . $user_dajian_order_sign['id']);
            } else {
                $user_dajian_order_sign['create_time'] = time();
                $user_dajian_order_sign['id'] = $this->db->insert('hqsen_user_dajian_order_other_sign', $user_dajian_order_sign);
            }
            // 更新首销订单 关于二销信息
            $order_type_sql['erxiao_sign_type'] = $sign_type;
            $order_type_sql['erxiao_order_status'] = 2; //搭建二销状态 0首销还未通过 1待处理 2待审核 3已完结 5已驳回
            $this->db->update('hqsen_user_dajian_order', $order_type_sql, ' id = ' . $user_dajian_order_id);
            // 更新首销签单  关于二销信息
            $erxiao_sign_sql['sign_type'] = $sign_type;
            $erxiao_sign_sql['sign_other_sign_id'] = $user_dajian_order_sign['id'];
            $erxiao_sign_sql['sign_user_id'] = $this->user['id'];
            $erxiao_sign_sql['sign_other_sign_status'] = 1; // 二销财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改
            $this->db->update('hqsen_user_dajian_order_sign', $erxiao_sign_sql, ' user_dajian_order_id = ' . $user_dajian_order_id);

            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 付款记录
    public function dajianOrderSignOtherList(){
        // todo 修改可以创建多条
        $user_dajian_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_sign where user_dajian_order_id = ' . $user_dajian_order_id);
        $other_signs = $this->db->getRows('select * from hqsen_user_dajian_order_other_sign where user_dajian_order_id = ' . $user_dajian_order_id . ' order by create_time asc');

        $sign_item['sign_type'] = (string)5;
        $sign_item['order_money'] = $user_dajian_order_sign['order_money'];
        $sign_item['order_time'] = $user_dajian_order_sign['order_time'];
        $sign_item['first_order_money'] = $user_dajian_order_sign['first_order_money'];
        $sign_item['first_order_using_time'] = $user_dajian_order_sign['first_order_using_time'];
        $sign_item['other_item_weikuan_old_time'] = $user_dajian_order_sign['sign_using_time']; // 尾款时间修改 sign_type =4 的时候是用
        $sign_item['other_item_weikuan_new_time'] = (string)0; // 尾款时间修改 sign_type =4 的时候是用
        $sign_item['order_sign_pic'] = $user_dajian_order_sign['sign_pic'] ? json_decode($user_dajian_order_sign['sign_pic'], true) : [];
        $list['sign_list'][] = $sign_item;

        foreach ($other_signs as $one_other_sign){
            $other_sign_item['sign_type'] = (string)$one_other_sign['sign_type'];
            $other_sign_item['other_item_weikuan_old_time'] = $user_dajian_order_sign['sign_using_time']; // 尾款时间修改 sign_type =4 的时候是用
            $other_sign_item['other_item_weikuan_new_time'] = $one_other_sign['order_time']; // 尾款时间修改 sign_type =4 的时候是用
            $other_sign_item['first_order_money'] = $user_dajian_order_sign['first_order_money']; // 首销数据
            $other_sign_item['first_order_using_time'] = $user_dajian_order_sign['first_order_using_time']; // 首销数据
            $other_sign_item['order_money'] = $one_other_sign['order_money'];
            $other_sign_item['order_time'] = $one_other_sign['order_time'];
            $other_sign_item['order_sign_pic'] = $one_other_sign['order_sign_pic'] ? json_decode($one_other_sign['order_sign_pic'], true) : [];
            $list['sign_list'][] = $other_sign_item;
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $list);
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

    // 二销 合同审核详情
    public function dajianOrderOtherSignDetail(){
        $user_dajian_order_id = $this->postInt('user_dajian_order_id'); // 订单ID
        if($user_dajian_order_id){
            $user_dajian_order_sign = $this->db->getRow('select * from hqsen_user_dajian_order_sign where user_dajian_order_id = ' . $user_dajian_order_id );
            if($user_dajian_order_sign){
                $user_dajian_order_other_sign = $this->db->getRow('select * from hqsen_user_dajian_order_other_sign where id = ' . $user_dajian_order_sign['sign_other_sign_id'] );
                if($user_dajian_order_other_sign){
                    $item = [];
                    if($user_dajian_order_other_sign['sign_type'] == 1){
                        $item['title'] = '中款明细';
                        $item['first_input_note'] = '中款金额';
                        $item['first_input_content'] = $user_dajian_order_other_sign['order_money'];
                        $item['second_input_note'] = '支付时间';
                        $item['second_input_content'] = $user_dajian_order_other_sign['order_time'];
                        $item['third_input_note'] = '中款凭证';
                        $item['third_input_content'] = $user_dajian_order_other_sign['order_sign_pic'] ? json_decode($user_dajian_order_other_sign['order_sign_pic'], true) : [];

                    }
                    if($user_dajian_order_other_sign['sign_type'] == 2){
                        $item['title'] = '中款明细';
                        $item['first_input_note'] = '尾款金额';
                        $item['first_input_content'] = $user_dajian_order_other_sign['order_money'];
                        $item['second_input_note'] = '支付时间';
                        $item['second_input_content'] = $user_dajian_order_other_sign['order_time'];
                        $item['third_input_note'] = '尾款凭证';
                        $item['third_input_content'] = $user_dajian_order_other_sign['order_sign_pic'] ? json_decode($user_dajian_order_other_sign['order_sign_pic'], true) : [];

                    }
                    if($user_dajian_order_other_sign['sign_type'] == 3){
                        $item['title'] = '中款明细';
                        $item['first_input_note'] = '附加款金额';
                        $item['first_input_content'] = $user_dajian_order_other_sign['order_money'];
                        $item['second_input_note'] = '支付时间';
                        $item['second_input_content'] = $user_dajian_order_other_sign['order_time'];
                        $item['third_input_note'] = '附加款凭证';
                        $item['third_input_content'] = $user_dajian_order_other_sign['order_sign_pic'] ? json_decode($user_dajian_order_other_sign['order_sign_pic'], true) : [];

                    }
                    if($user_dajian_order_other_sign['sign_type'] == 4){
                        $item['title'] = '申请时间';
                        $item['first_input_note'] = '原时间';
                        $item['first_input_content'] = $user_dajian_order_sign['sign_using_time'];
                        $item['second_input_note'] = '申请时间';
                        $item['second_input_content'] = $user_dajian_order_other_sign['order_time'];
                        $item['third_input_note'] = (string)'';
                        $item['third_input_content'] = (array)[];
                    }
                    if($item){
                        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $item);
                    }

                }
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


}