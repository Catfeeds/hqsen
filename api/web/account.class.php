<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:web   后台接口 account 账号
 */


namespace api\web;

class account extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit(); // 所有接口需要登录态
    }

    // 注册账号列表
    public function registerAccountList(){
        $page = $this->postInt('page', 1);
        $search_input = $this->postString('search_input');
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " order by id desc limit $offset , $limit";
        $search_sql = '';
        if($search_input){
            $search_sql = " and user_name like '%$search_input%'" ;
        }
        $user = $this->db->getRows("select * from hqsen_user where  del_flag = 1 and user_type = 3 $search_sql " . $sql_limit);
        $data['list'] = [];
        foreach ($user as $one_user){
            if($one_user){
                if($one_user['alipay_account']){
                    $accout = '支付宝:' . $one_user['alipay_account'];
                } else if($one_user['bank_account']){
                    $accout = $one_user['bank_name'] . ':' . $one_user['bank_account'] . '(' . $one_user['bank_user'] . ')' ;
                } else {
                    $accout = '未设置账号';
                }

                $unpay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_kezi_order  where user_order_status = 2 and user_id = ' . $one_user['id']);
                $pay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_kezi_order where user_order_status = 3 and user_id = ' . $one_user['id']);

                $user_item = array(
                    'user_id' => $one_user['id'],
                    'user_name' => $one_user['user_name'],
                    'create_time' => date('Y-m-d H:i:s' , $one_user['create_time']),
                    'alipay_account' => $accout,
                    'payed' => round($pay['s'], 2),
                    'unpay' => round($unpay['s'], 2),
                );
                $data['list'][] = $user_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_user', ' del_flag = 1 and user_type = 3 ' . $search_sql);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);

    }

    // 酒店账号列表
    public function hotelAccountList(){
        $page = $this->postInt('page', 1);
        $search_input = $this->postString('search_input');
//        if($search_input){
//            $this->accountSearch();
//        }
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";

        $search_sql = '';
        if($search_input){
            $search_sql = " and hu.user_name like '%$search_input%'" ;
        }
        $user = $this->db->getRows("select *,hu.id as hu_id from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where  hu.del_flag = 1 and hu.user_type = 4 $search_sql order by hu.id desc" . $sql_limit);
        $data = [];
        foreach ($user as $one_user){
            if($one_user){

                $unpay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_kezi_order  where user_order_status = 2 and user_id = ' . $one_user['hu_id']);
                $pay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_kezi_order where user_order_status = 3 and user_id = ' . $one_user['hu_id']);
                $watch_unpay = $this->db->getRow('select sum(watch_user_money) as s from hqsen_user_kezi_order where user_order_status = 2 and watch_user_id = ' . $one_user['hu_id']);
                $watch_pay = $this->db->getRow('select sum(watch_user_money) as s from hqsen_user_kezi_order where user_order_status = 3 and watch_user_id = ' . $one_user['hu_id']);
                $dajian_unpay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_dajian_order where user_order_status = 2 and user_id = ' . $one_user['hu_id']);
                $dajian_pay = $this->db->getRow('select sum(create_user_money) as s from hqsen_user_dajian_order where user_order_status = 3 and user_id = ' . $one_user['hu_id']);
                $user_item = array(
                    'user_id' => $one_user['hu_id'],
                    'user_name' => $one_user['user_name'],
                    'hotel_name' => (string)$one_user['hotel_name'],
                    'hotel_area' => (string)$one_user['hotel_area'],
                    'user_status' => $one_user['user_status'],
                    'create_time' => date('Y-m-d H:i:s' , $one_user['create_time']),
                    'alipay_account' => $one_user['alipay_account'] ? $one_user['alipay_account'] : '未设置账号',
                    'payed' => round($unpay['s'], 2) + round($watch_unpay['s'], 2) + round($dajian_unpay['s'], 2),
                    'unpay' => round($pay['s'], 2) + round($watch_pay['s'], 2) + round($dajian_pay['s'], 2),
                );
                $data['list'][] = $user_item;
            }
        }
        $count = $this->db->getRow("select count(1) as c from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where  hu.del_flag = 1 and hu.user_type = 4 $search_sql ");
        $data['count'] = isset($count['c']) ? $count['c'] : 0;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 酒店列表
    public function hotelList(){
        $hotel = $this->db->getRows("select *  from hqsen_hotel  where del_flag = 1 order by id desc " );
        $data = [];
        foreach ($hotel as $one_hotel){
            if($one_hotel){
                $hotel_item = array(
                    'value' => $one_hotel['id'],
                    'label' => $one_hotel['hotel_name'],
                );
                $data['list'][] = $hotel_item;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 区域列表
    public function areaList(){
        $hotel = $this->db->getRows("select *  from hqsen_area  where del_flag = 1 order by id desc " );
        $data = [];
        foreach ($hotel as $one_hotel){
            if($one_hotel){
                $hotel_item = array(
                    'value' => $one_hotel['id'],
                    'label' => $one_hotel['area_name'],
                );
                $data['list'][] = $hotel_item;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function hotelAccountAdd(){
        $user_name = $this->postString('user_name');
        $hotel_id = $this->postString('hotel_id');
        $password = $this->postString('password');
        if($user_name and $hotel_id and $password){
            $sql_user['user_name'] = $user_name;
            $sql_user['password'] = md5($password);
            $sql_user['del_flag'] = 1;
            $old_user = $this->db->getRow("select * from hqsen_user where user_name = '$user_name' and user_type = 4");
            if($old_user){
                $this->db->update('hqsen_user', $sql_user, ' id = ' . $old_user['id']);
            } else {
                $sql_user['user_type'] = 4;
                $sql_user['create_time'] = time();
                $sql_user['id'] = $this->db->insert('hqsen_user', $sql_user);
            }

            $hotel = $this->db->getRow("select * from hqsen_hotel  where id =" . $hotel_id);
            if($hotel and isset($sql_user['id']) and $sql_user['id']){
                $sql_user_date['hotel_id'] = $hotel['id'];
                $sql_user_date['hotel_name'] = $hotel['hotel_name'];
                $sql_user_date['area_id'] = $hotel['area_id'];
                $sql_user_date['hotel_area'] = $this-> get_sh_area($hotel['area_sh_id']);
                $sql_user_date['user_id'] = $sql_user['id'];
                $sql_user_date['area_sh_id'] = $hotel['area_sh_id'];
                $sql_user_date['user_name'] = $sql_user['user_name'];
                $this->db->insert('hqsen_user_data', $sql_user_date);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }
    public function accountEdit(){
        $user_id = $this->postString('id');
        $password = $this->postString('password');
        $re_password = $this->postString('re_password');
        if($user_id  and $password and $password == $re_password){
            $sql_user['password'] = md5($password);
            $this->db->update('hqsen_user', $sql_user, ' id = ' . $user_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }
    public function hotelAccountDetail(){
        $user_id = $this->postInt('id');
        $user = $this->db->getRow("select * from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where hu.id = " . $user_id);
        $data = [];
        if($user){
            $user_item = array(
                'user_name' => $user['user_name'],
                'hotel_id' => $user['hotel_id'],
            );
            $data = $user_item;
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }
    public function accountChangeStatus(){
        $user_id = $this->postString('id');
        $user_status = $this->postString('user_status');
        if($user_id  and $user_status){
            $sql_user['user_status'] = $user_status;
            $this->db->update('hqsen_user', $sql_user, ' id = ' . $user_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 内部账号列表
    public function innerAccountList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";

        $user = $this->db->getRows("select *,hu.id as hu_id,hu.user_name as hu_user_name from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where  hu.del_flag = 1 and hu.user_type > 10 order by hu.id desc " . $sql_limit);
        $data = [];
        foreach ($user as $one_user){
            if($one_user){
                $user_type = '';
                foreach ($this->inner_type() as $one_type){
                    if($one_type['value'] == $one_user['user_type']){
                        $user_type = $one_type['label'];
                        break;
                    }
                }
                $user_area = $one_user['hotel_area'] ? $one_user['hotel_area'] : '/';
//                if(in_array($one_user['user_type'], [11,12])){
//                    $sh_area = $this->db->getRows('select * from hqsen_area_sh where link_area_id=' . $one_user['area_id']);
//                    foreach ($sh_area as $one_sh_area){
//                        if($one_sh_area){
//                            $one_tip = mb_substr($one_sh_area['area_label'], 0, 1, 'utf-8');
//                            $user_area = $user_area . '|' . $one_tip;
//                        }
//                    }
//                }
                $user_item = array(
                    'user_id' => $one_user['hu_id'],
                    'user_name' => $one_user['hu_user_name'],
                    'user_type' => $user_type,
                    'user_area' => $user_area,
                    'user_status' => $one_user['user_status'],
                    'create_time' => date('Y-m-d H:i:s' , $one_user['create_time']),
                );
                $data['list'][] = $user_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_user', ' del_flag = 1 and user_type > 10 ');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function innerAccountAdd(){
        $user_name = $this->postString('user_name');
        $user_type = $this->postString('user_type');
        $password = $this->postString('password');
        $area_id = $this->postInt('area_id');
        if($user_name and $user_type and $password){
            $sql_user['user_name'] = $user_name;
            $sql_user['user_type'] = $user_type;
            $sql_user['password'] = md5($password);
            $sql_user['create_time'] = time();
            $sql_user['del_flag'] = 1;
            $sql_user['id'] = $this->db->insert('hqsen_user', $sql_user);
            if(!$sql_user['id']){
                $this->appDie($this->back_code['user']['create_user_exist'], $this->back_msg['user']['create_user_exist']);
            }
            $area = $this->db->getRow("select * from hqsen_area  where id =" . $area_id);
            if($area){
                $sql_user_data['hotel_area'] = $area['area_name'];
            }
            $sql_user_data['area_id'] = $area['id'];
            $sql_user_data['user_id'] = $sql_user['id'];
            $sql_user_data['user_name'] = $sql_user['user_name'];
            $this->db->insert('hqsen_user_data', $sql_user_data);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function innerAccountDetail(){
        $user_id = $this->postInt('id');
        $user = $this->db->getRow("select * from hqsen_user as hu 
left join hqsen_user_data as hud on hu.id=hud.user_id where hu.id = " . $user_id);
        $data = [];
        if($user){
            $user_item = array(
                'user_name' => $user['user_name'],
                'user_type' => $user['user_type'],
                'area_id' => $user['area_id'],
            );
            $data = $user_item;
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function adminAccountEdit(){
        $old_password = $this->postString('old_password');
        $password = $this->postString('password');
        $re_password = $this->postString('re_password');
        $user = $this->db->getRow("select * from hqsen_user where user_name = 'sen' ");
        if($old_password and md5($old_password) == $user['password'] and $password and $password == $re_password){
            $sql_user['password'] = md5($password);
            $this->db->update('hqsen_user', $sql_user, ' user_name = "sen"  ');
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 　删除区域
    public function accountDelete(){
        $user_id = $this->postString('id');
        if($user_id){
            $sql_user['del_flag'] = 2;
            $this->db->update('hqsen_user', $sql_user, ' id = ' . $user_id);
            $this->db->update('hqsen_user_data', $sql_user, ' id = ' . $user_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 酒店或者账号搜索
    public function accountSearch(){
        $page = $this->postInt('page', 1);
        $search_input = $this->postString('search_input');
        $limit = 10;
        $offset = ($page - 1) * $limit;

//        $search_hotel_sql = "select *,hu.id as hu_id from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where hud.hotel_name like '%$search_input%' and hu.del_flag = 1 and hu.user_type = 4 order by hu.id desc" ;
//        $user = $this->db->getRows($search_hotel_sql);
//        $data = [];
//        $data['list'] = [];
//
//        foreach ($user as $one_user){
//            if($one_user){
//                $user_item = array(
//                    'user_id' => $one_user['hu_id'],
//                    'user_name' => $one_user['user_name'],
//                    'hotel_name' => (string)$one_user['hotel_name'],
//                    'hotel_area' => (string)$one_user['hotel_area'],
//                    'user_status' => $one_user['user_status'],
//                );
//                $data['list'][] = $user_item;
//            }
//        }
        $search_name_sql = "select *,hu.id as hu_id from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where hud.user_name like '%$search_input%' and hu.del_flag = 1 and hu.user_type = 4 order by hu.id desc" ;
        $user = $this->db->getRows($search_name_sql);
        foreach ($user as $one_user){
            if($one_user){
                $user_item = array(
                    'user_id' => $one_user['hu_id'],
                    'user_name' => $one_user['user_name'],
                    'hotel_name' => (string)$one_user['hotel_name'],
                    'hotel_area' => (string)$one_user['hotel_area'],
                    'user_status' => $one_user['user_status'],
                );
                $data['list'][] = $user_item;
            }
        }
        $data['count'] = count($data['list']);
        $data['list'] = array_slice($data['list'], $offset, $limit);
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }


    // 用户客资列表
    public function userKeziList(){
        $order_page = $this->postInt('order_page', 1);
        $user_id = $this->postInt('user_id', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " order by create_time desc limit $offset , $limit";
        $sql_status = '  user_order_status != 0 ';
        $sql_status .= ' and user_id = '. $user_id;// 以注册用户纬度获取  客资列表信息
        $order = $this->db->getRows('select * from hqsen_user_kezi_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        $status_detail = array(
            '1'=>'跟进中',
            '2'=>'待结算',
            '3'=>'已结算',
            '4'=>'已取消'
        );
        if($order){
            foreach ($order as $one_order){
                $order_from = $one_order['order_from'] == 2 ? '(同步)' : '';
                $order_monkey = '';
                // 订单成功才有金额
                if(in_array($one_order['user_order_status'],[2,3])){
                    $order_monkey = $one_order['create_user_money'] ? '￥(' . $one_order['create_user_money'] . ')' : '';
                }
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'kezi_order_id' => (int)$one_order['kezi_order_id'],
                    'create_time' => (string)date('Y-m-d H:i:s', $one_order['create_time']),
                    'order_status' => $status_detail[$one_order['user_order_status']] . $order_monkey,// 需要返回提供者状态   不搞给错了
                    'order_phone' => (string)$one_order['order_phone'].$order_from,
                    'watch_user' => (string)$one_order['watch_user_name'] . '  (' . $one_order['watch_user_hotel_name'] . ')' ,
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_kezi_order', $sql_status); // 总的订单数
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }


    // 酒店账号搭建列表
    public function userDajianList(){
        $order_page = $this->postInt('order_page', 1);
        $user_id = $this->postInt('user_id', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " order by create_time desc limit $offset , $limit";
        $sql_status = '  user_order_status != 0 ';
        $sql_status .= ' and user_id = '. $user_id;// 以注册用户纬度获取  客资列表信息
        $order = $this->db->getRows('select * from hqsen_user_dajian_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        $status_detail = array(
            '1'=>'跟进中',
            '2'=>'待结算',
            '3'=>'已结算',
            '4'=>'已取消'
        );
        if($order){
            foreach ($order as $one_order){
                $order_monkey = '';
                if(in_array($one_order['user_order_status'],[2,3])){
                    $order_monkey = $one_order['create_user_money'] ? '￥(' . $one_order['create_user_money'] . ')' : '';
                }
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'dajian_order_id' => (int)$one_order['dajian_order_id'],
                    'create_time' => (string)date('Y-m-d H:i:s', $one_order['create_time']),
                    'order_status' => $status_detail[$one_order['user_order_status']] . $order_monkey,// 需要返回提供者状态   不搞给错了
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user_name'] . '  (' . $one_order['watch_user_hotel_name'] . ')' ,
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_dajian_order', $sql_status); // 总的订单数
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    // 酒店账号跟踪客资列表
    public function hotelFollowList(){
        $order_page = $this->postInt('order_page', 1);
        $user_id = $this->postInt('user_id', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " order by create_time desc limit $offset , $limit";
        $sql_status = '  user_order_status != 0 ';
        $sql_status .= ' and watch_user_id = '. $user_id;// 以注册用户纬度获取  客资列表信息
        $order = $this->db->getRows('select * from hqsen_user_kezi_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        $status_detail = array(
            '1'=>'跟进中',
            '2'=>'待结算',
            '3'=>'已结算',
            '4'=>'已取消'
        );
        if($order){
            foreach ($order as $one_order){
                $create_user = $this->db->getRow('select * from hqsen_user where id = ' . $one_order['user_id']);
                $order_monkey = '';
                // 订单成功才有金额
                if(in_array($one_order['user_order_status'],[2,3])){
                    $order_monkey = $one_order['create_user_money'] ? '￥(' . $one_order['create_user_money'] . ')' : '';
                }
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'kezi_order_id' => (int)$one_order['kezi_order_id'],
                    'create_time' => (string)date('Y-m-d H:i:s', $one_order['create_time']),
                    'order_status' => $status_detail[$one_order['user_order_status']] . $order_monkey,// 需要返回提供者状态   不搞给错了
                    'order_phone' => (string)$one_order['order_phone'],
                    'create_user' => (string)$create_user['user_name'],
                    'watch_user' => (string)$one_order['watch_user_name'] . '  (' . $one_order['watch_user_hotel_name'] . ')' ,
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = intval($this->db->getCount('hqsen_user_kezi_order', $sql_status)); // 总的订单数
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    // 首销账号跟进搭建列表
    public function sxFollowList(){
        $order_page = $this->postInt('order_page', 1);
        $user_id = $this->postInt('user_id', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " order by create_time desc limit $offset , $limit";
        $sql_status = '  order_status != 0 ';
        $sql_status .= ' and watch_user_id = '. $user_id;// 以注册用户纬度获取  客资列表信息
        $order = $this->db->getRows('select * from hqsen_user_dajian_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        $status_detail = array(
            1=>'待处理',
            2=>'待审核',
            3=>'待结算',
            4=>'已结算',
            5=>'已驳回',
            6=>'已取消'
        );
        if($order){
            foreach ($order as $one_order){
                $order_monkey = '';
                $create_user = $this->db->getRow('select * from hqsen_user_data where user_id = '. $one_order['user_id']);
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'dajian_order_id' => (int)$one_order['dajian_order_id'],
                    'create_time' => (string)date('Y-m-d H:i:s', $one_order['create_time']),
                    'order_status' => $status_detail[$one_order['order_status']] . $order_monkey,// 需要返回提供者状态   不搞给错了
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$one_order['watch_user_name'] . '  (' . $one_order['watch_user_hotel_name'] . ')' ,
                    'create_user' => (string)$create_user['user_name'] . '  (' . $create_user['hotel_name'] . ')' ,
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_dajian_order', $sql_status); // 总的订单数
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    // 二销账号跟进搭建列表
    public function exFollowList(){
        $order_page = $this->postInt('order_page', 1);
        $user_id = $this->postInt('user_id', 1);
        $limit = 10;
        $offset = ($order_page - 1) * $limit;
        $sql_limit = " order by create_time desc limit $offset , $limit";
        $sql_status = '  erxiao_order_status != 0 ';
        $sql_status .= ' and erxiao_user_id = '. $user_id;// 以注册用户纬度获取  客资列表信息
        $order = $this->db->getRows('select * from hqsen_user_dajian_order where ' . $sql_status . $sql_limit);
        $order_list['order_list'] = [];
        $status_detail = array(
            1=>'待处理',
            2=>'待审核',
            3=>'已完结',
        );
        if($order){
            $watch_user = $this->db->getRow('select * from hqsen_user_data where user_id = '. $user_id);
            foreach ($order as $one_order){
                $order_monkey = '';
                $create_user = $this->db->getRow('select * from hqsen_user_data where user_id = '. $one_order['user_id']);
                $order_item = array(
                    'id' => (int)$one_order['id'],
                    'dajian_order_id' => (int)$one_order['dajian_order_id'],
                    'create_time' => (string)date('Y-m-d H:i:s', $one_order['create_time']),
                    'order_status' => $status_detail[$one_order['erxiao_order_status']] . $order_monkey,// 需要返回提供者状态   不搞给错了
                    'order_phone' => (string)$one_order['order_phone'],
                    'watch_user' => (string)$watch_user['user_name'] . '  (' . $watch_user['hotel_area'] . ')' ,
                    'create_user' => (string)$create_user['user_name'] . '  (' . $create_user['hotel_name'] . ')' ,
                );
                $order_list['order_list'][] = $order_item;
            }

        }
        $order_list['count'] = $this->db->getCount('hqsen_user_dajian_order', $sql_status); // 总的订单数
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    //客资 订单跟进记录列表
    public function keziOrderFollowList(){
        $user_kezi_order_id = $this->postInt('id'); // 订单ID
        if($user_kezi_order_id){
            $user_kezi_order_follow_list = $this->db->getRows("select * from hqsen_user_kezi_order_follow where user_kezi_order_id = $user_kezi_order_id ");
            $back_follows = [];
            if($user_kezi_order_follow_list){
                foreach ($user_kezi_order_follow_list as $one_item){
                    $follow_item['order_follow_time'] = $one_item['user_order_status'] == 2 ? '已取消' : date('Y-m-d H:i:s', $one_item['order_follow_time']);
                    $follow_item['order_follow_desc'] = $one_item['order_follow_desc'];
                    $follow_item['id'] = $one_item['id'];
                    $follow_item['order_follow_create_time'] = date('Y-m-d H:i:s', $one_item['order_follow_create_time']);
                    $follow_item['user_order_status'] = $one_item['user_order_status'];
                    $back_follows[] = $follow_item;
                }
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $back_follows);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 搭建订单日志列表
    public function dajianOrderFollowList(){
        $user_dajian_order_id = $this->postInt('id'); // 订单ID
        if($user_dajian_order_id){
            $user_dajian_order_follow_list = $this->db->getRows("select * from hqsen_user_dajian_order_follow where user_dajian_order_id = $user_dajian_order_id order by id desc");
            $back_follows = [];
            if($user_dajian_order_follow_list){
                foreach ($user_dajian_order_follow_list as $one_item){
                    $follow_item['order_follow_time'] = $one_item['user_order_status'] == 2 ? '已取消' : date('Y-m-d H:i:s', $one_item['order_follow_time']);
                    $follow_item['order_follow_desc'] = $one_item['order_follow_desc'];
                    $follow_item['id'] = $one_item['id'];
                    $follow_item['order_follow_create_time'] = date('Y-m-d H:i:s', $one_item['order_follow_create_time']);
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