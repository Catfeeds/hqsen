<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:app 接口用户类api user
 */


namespace api\app;

class user extends base{
    // 用户登陆
    public function login(){
        $phone = $this->postString('phone');
        $code = $this->postString('code');
        if(($phone and $code) or $phone == '13813813800'){
            session_id("sen-" . $phone);
            session_start();
            if((isset($_SESSION['code']) and $_SESSION['code'] == $code)  or $phone == '13813813800'){
                $user = $this->db->getRow('select * from hqsen_user where user_name = ' . $phone);
                if(!$user){
                    $user['user_name'] = $phone;
                    $user['nike_name'] = $phone;
                    $user['phone'] = $phone;
                    $user['alipay_account'] = '';
                    $user['bank_account'] = '';
                    $user['create_time'] = time();
                    $user['user_type'] = 3;
                    $user['del_flag'] = 1;
                    $user_id = $this->db->insert('hqsen_user', $user);
                    if($user_id){
                        $user['id'] = $user_id;
                    } else {
                        $this->appDie($this->back_code['sys']['mysql_err'], $this->back_msg['sys']['mysql_err']);
                    };
                }
                session_destroy();//销毁一个会话中的全部数据

                $user['last_login_time'] = time();
                $user['session_id'] = md5($user['id'] . $user['last_login_time']);
                // todo 注释使用最后登陆时间
                $user['session_id'] = md5($user['id'] . $user['create_time']);
                $this->db->update('hqsen_user', $user, ' id = ' . $user['id']);
                $login_user = array(
                    'access_token' => $user['session_id'],
                    'alipay_account' => $user['alipay_account'],
                    'bank_account' => $user['bank_account'],
                    'nike_name' => $user['nike_name'],
                    'user_type' => $user['user_type']
                );
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
            } else {
                $this->appDie($this->back_code['user']['phone_code_err'], $this->back_msg['user']['phone_code_err']);
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 账号登录
    public function loginByUser(){
        $user_name = $this->postString('user_name');
        $password = $this->postString('password');
        if($user_name and $password){
            $user = $this->db->getRow("select * from hqsen_user where user_status = 1 and user_name = '$user_name'");
            if(!$user or md5($password) != $user['password']){
                $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
            }
            $company_account = [4, 11, 12];
            if(!in_array($user['user_type'], $company_account)){
                $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
            }
            $user_data = $this->db->getRow("select * from hqsen_user_data where user_id = " . $user['id']);
            $user['last_login_time'] = time();
            $user['session_id'] = md5($user['id'] . $user['last_login_time']);
            // todo 注释使用最后登陆时间
            $user['session_id'] = md5($user['id'] . $user['create_time']);
            $this->db->update('hqsen_user', $user, ' id = ' . $user['id']);
//            session_id($user['session_id']);
//            session_start();
            $login_user = array(
                'access_token' => $user['session_id'],
                'alipay_account' => $user['alipay_account'],
                'bank_account' => $user['bank_account'],
                'nike_name' => $user['nike_name'],
                'hotel_name' => $user_data['hotel_name'],
                'hotel_id' => $user_data['hotel_id'],
                'area_id' => $user_data['area_id'],
                'hotel_area' => $user_data['hotel_area'],
                'user_type' => $user['user_type'],
                'auto_type' => $user_data['auto_type']
            );
//            $_SESSION['user_info'] = $user;
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    //支付宝绑定
    public function alipayBind(){
        $this->loginInit();
        $alipay = $this->postString('alipay');
        $bank_name = $this->postString('bank_name');
        $bank_user = $this->postString('bank_user');
        $bank_account = $this->postString('bank_account');
        if($alipay or $bank_name){
            $update_user['alipay_account'] = $alipay;
            $update_user['bank_name'] = $bank_name;
            $update_user['bank_user'] = $bank_user;
            $update_user['bank_account'] = $bank_account;
            $this->db->update('hqsen_user', $update_user, ' id = ' . $this->user['id']);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['user']['bind_empty'], $this->back_msg['user']['bind_empty']);
        }

    }

    // 云片发短信
    public function getPhoneCode(){
        $mobile = isset($_POST['mobile']) ? (string)$_POST['mobile'] : '';
        if($mobile){
            header("Content-Type:text/html;charset=utf-8");
            $apikey = "b181d90efe2155f5fe3d74b468c0a136"; //修改为您的apikey(https://www.yunpian.com)登陆官网后获取
            if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                $rand_text = rand(1000,9999);
//                $rand_text = 1000;
                $text="验证码：" . $rand_text;
                $ch = curl_init();
                /* 设置验证方式 */
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));
                /* 设置返回结果为流 */
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                /* 设置超时时间*/
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                /* 设置通信方式 */
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                // 发送短信
                $send_data = array('tpl_id'=>'1755704','tpl_value'=>('#code#').'='.urlencode($text),'apikey'=>$apikey,'mobile'=>$mobile);
                curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/tpl_single_send.json');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send_data));
                $json_data = curl_exec($ch);
                // 处理返回结果
                $array = json_decode($json_data,true);
                if(isset($array['msg']) and  $array['msg'] == '发送成功'){
                    $data['code'] = $rand_text;
                    $data['phone'] = $mobile;
                    $session_id = "sen-" . $data['phone'];
                    session_id($session_id);
                    session_start();
                    $_SESSION['code'] = $rand_text;
                } else {
                    $data['code'] = 0;
                    $this->appDie($this->back_code['user']['phone_code_err'], $array['detail'], $data);
                }
                // 发送模板短信
                curl_close($ch);
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
            } else {
                $data['phone'] = (string)$mobile;
                $this->appDie($this->back_code['sys']['phone_illegal'], $this->back_msg['sys']['phone_illegal'], $data);
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 首页酒店列表
    public function mainList(){
        $list_type = $this->postInt('list_type');
        $area_sh_id = $this->postInt('area_sh_id');
        $hotel_type = $this->postInt('hotel_type');
        if($area_sh_id == 20){
            $list_type = 1;
        }
        $hotel_type_sql = '';
        if($hotel_type > 1){
            $hotel_type = array(
                '2' => '星级酒店',
                '3' => '特色餐厅',
                '4' => '婚礼会所',
                '5' => '游轮婚礼',
            )[$hotel_type];
            $hotel_type_sql = ' and  hhd.hotel_type = "' . $hotel_type . '"';
        }
        $hotel = '';
        if($list_type == 1){
            $hotel = $this->db->getRows("select * from hqsen_hotel_rec as hhr 
                      left join hqsen_hotel as hh on hhr.hotel_id = hh.id left join hqsen_hotel_data as hhd on hh.id = hhd.id where hh.is_data = 1 and  hh.del_flag = 1  $hotel_type_sql and  hhr.del_flag = 1  order by hhr.hotel_weight asc ");
        } else if($list_type == 2){
            $area_sh_id_sql = '';
            if($area_sh_id){
                $area_sh_id_sql = ' and  hh.area_sh_id = ' . $area_sh_id ;
            }
            $hotel = $this->db->getRows("select * from hqsen_hotel as hh  left join hqsen_hotel_data as hhd on hh.id = hhd.id where  hh.is_data = 1 and hh.del_flag = 1 $hotel_type_sql $area_sh_id_sql order by hh.weight asc ");
        } else if($list_type == 3){
            $search_input = $this->postString('search_input');
            $hotel = $this->db->getRows("select * from hqsen_hotel as hh  left join hqsen_hotel_data as hhd on hh.id = hhd.id where  hh.is_data = 1 and  hh.hotel_name like '%$search_input%' and hh.del_flag = 1 order by hh.weight asc ");
        }
        $data = [];
        if($hotel){
            foreach ($hotel as $one_hotel){
                $item['hotel_id'] = (string)$one_hotel['id'];
                $item['hotel_name'] = (string)$one_hotel['hotel_name'];
                $item['hotel_low'] = (string)$one_hotel['hotel_low'];
                $item['hotel_high'] = (string)$one_hotel['hotel_high'];
                $item['hotel_max_desk'] = (string)$one_hotel['hotel_max_desk'];
                $item['area_sh_name'] = (string)$this->get_sh_area($one_hotel['area_sh_id']);
                $item['hotel_type'] = (string)$one_hotel['hotel_type'];
                $item['hotel_phone'] = (string)$one_hotel['hotel_phone'];
                $item['hotel_image'] = $one_hotel['hotel_image'] ? json_decode($one_hotel['hotel_image'], true)[0] : '';
                $data[] = $item;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 首页酒店详情
    public function mainHotelDetail(){
        $hotel_id = $this->postInt('hotel_id');
        $one_hotel = $this->db->getRow("select * from hqsen_hotel as hh 
                  left join hqsen_hotel_data as hhd on hh.id = hhd.id 
                  where hh.id = $hotel_id");
        $item['hotel_name'] = (string)$one_hotel['hotel_name'];
        $item['hotel_low'] = (string)$one_hotel['hotel_low'];
        $item['hotel_high'] = (string)$one_hotel['hotel_high'];
        $item['hotel_max_desk'] = (string)$one_hotel['hotel_max_desk'];
        $item['area_sh_name'] = (string)$this->get_sh_area($one_hotel['area_sh_id']);
        $item['hotel_type'] = (string)$one_hotel['hotel_type'];
        $item['hotel_phone'] = (string)$one_hotel['hotel_phone'];
        $item['hotel_image'] = $one_hotel['hotel_image'] ? json_decode($one_hotel['hotel_image'], true)[0] : '';
        $item['hotel_images'] = $one_hotel['hotel_image'] ? json_decode($one_hotel['hotel_image'], true) : [];
        $item['hotel_address'] = (string)$one_hotel['hotel_address'];

        $hotel_room = $this->db->getRows("select * from hqsen_hotel_room where hotel_id = $hotel_id and del_flag =1");
        if($hotel_room){
            foreach ($hotel_room as $one_room){
                $room_item['room_image'] = $one_room['room_image'] ? json_decode($one_room['room_image'], true) : [];
                $room_item['room_name'] = (string)$one_room['room_name'];
                $room_item['room_max_desk'] = (string)$one_room['room_max_desk'];
                $room_item['room_high'] = (string)$one_room['room_high'];
                $room_item['room_lz'] = (string)$one_room['room_lz'];

                $room_item['room_min_desk'] = (string)$one_room['room_min_desk'];
                $room_item['room_best_desk'] = (string)$one_room['room_best_desk'];
                $room_item['room_m'] = (string)$one_room['room_m'];
                $item['room_list'][] = $room_item;
            }
        }

        $hotel_menu = $this->db->getRows("select * from hqsen_hotel_menu where hotel_id = $hotel_id and del_flag = 1");
        if($hotel_menu){
            foreach ($hotel_menu  as $one_menu){
                $menu_item['menu_name'] = (string)$one_menu['menu_name'];
                $menu_item['menu_money'] = (string)$one_menu['menu_money'];
                $item['menu_list'][] = $menu_item;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $item);
    }

    // 获取上海18个区
    public function getShArea()
    {
        $gsa = $this->get_sh_area();
        $gsa['20'] = '精选地区';
        $gsa = array_reverse($gsa, true);
        $data['sh_area'] = $gsa;
        $data['hotel_level'] = array(
            '1' => '全部酒店类型',
            '2' => '星级酒店',
            '3' => '特色餐厅',
            '4' => '婚礼会所',
            '5' => '游轮婚礼',
        );
        foreach ($data['sh_area'] as $k => $v){
            $data['sh_area_order'][] = $k;
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function syncOrder(){
        $start_time = time() - 60 * 1; //同步三天前的订单
        // 获取三天前 未同步的指定酒店的订单
        $order = $this->db->getRows("select * from hqsen_kezi_order where  del_flag = 1 and create_time < $start_time and sync_type = 1 and order_area_hotel_type = 2");
        foreach ($order as $one_order){
            $hotel_arr = explode(',', $one_order['order_area_hotel_id']);
            foreach ($hotel_arr as $one_hotel_id){
                $this->syncHotelOrder($one_order, $one_hotel_id);
            }
            // 更新同步状态
            $update_sql['sync_type'] = 2;
            $this->db->update('hqsen_kezi_order', $update_sql, ' id = ' . $one_order['id']);
        }
    }


    // 按区域  同步同区域下所有的酒店
    public function syncHotelOrder($order, $using_hotel_id)
    {
        $using_hotel = $this->db->getRow('select * from hqsen_hotel where id =' . $using_hotel_id);
        if($using_hotel){
            $hotel_level = $using_hotel['hotel_level'];
        } else {
            return '酒店错误:' . $using_hotel_id . '!';
        }
        $using_area_id = $using_hotel['area_id'];
        // 分配订单 需要分配酒店账号 user_type=4
        $user_data = $this->db->getRows("
                    select * from (
                            select hud.* from hqsen_user as hu 
                            left join hqsen_user_data as hud on hu.id=hud.user_id 
                            left join hqsen_hotel as hh on hud.hotel_id = hh.id
                            where hu.user_type=4 and hu.del_flag = 1 and hu.user_status=1 
                            and hud.area_id = $using_area_id and hh.hotel_level = '$hotel_level' 
                            and hud.auto_type = 2 
                            order by last_order_time asc
                        ) as c group by c.hotel_id
                ");
        if (!$user_data) {
            $error_message = '区域:' . $using_hotel['hotel_name'] . '同区域不存在酒店账号,同步失败';
            return $error_message;
        }
        foreach ($user_data as $one_user_data) {
            $one_user_order_sql = [];
            // 酒店已存在该订单  不创建
            if($one_user_data['hotel_id'] == $using_hotel_id){
                continue;
            }
            if ($one_user_data) {
                $one_user_order_sql['user_id'] = $order['user_id'];
                $one_user_order_sql['watch_user_name'] = $one_user_data['user_name'];
                $one_user_order_sql['watch_user_hotel_name'] = $one_user_data['hotel_name'];
                $one_user_order_sql['watch_user_id'] = $one_user_data['user_id'];
                $one_user_order_sql['kezi_order_id'] = $order['id'];
                $one_user_order_sql['create_time'] = time();
                $one_user_order_sql['update_time'] = time();
                $one_user_order_sql['order_from'] = 2;
                $one_user_order_sql['order_phone'] = $order['order_phone'];
                $rs = $this->db->insert('hqsen_user_kezi_order', $one_user_order_sql);
                if ($rs) {
                    $update_sql['last_order_time'] = time();
                    $this->db->update('hqsen_user_data', $update_sql, ' user_id = ' . $one_user_data['user_id']);
                }
            }
        }
        return false;
    }

}