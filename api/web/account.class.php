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
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";

        $user = $this->db->getRows("select * from hqsen_user where  del_flag = 1 and user_type = 3 " . $sql_limit);
        $data = [];
        foreach ($user as $one_user){
            if($one_user){
                $user_item = array(
                    'user_id' => $one_user['id'],
                    'user_name' => $one_user['user_name'],
                    'alipay_account' => $one_user['alipay_account'],
                );
                $data['list'][] = $user_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_user', ' del_flag = 1 and user_type = 3 ');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);

    }

    // 酒店账号列表
    public function hotelAccountList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";

        $user = $this->db->getRows("select *,hu.id as hu_id from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where  hu.del_flag = 1 and hu.user_type = 4 order by hu.id desc" . $sql_limit);
        $data = [];
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
        $data['count'] = $this->db->getCount('hqsen_user', ' del_flag = 1 and user_type = 4 ');
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

    public function hotelAccountAdd(){
        $user_name = $this->postString('user_name');
        $hotel_id = $this->postString('hotel_id');
        $password = $this->postString('password');
        if($user_name and $hotel_id and $password){
            $sql_user['user_name'] = $user_name;
            $sql_user['password'] = md5($password);
            $sql_user['create_time'] = time();
            $sql_user['user_type'] = 4;
            $sql_user['del_flag'] = 1;
            $sql_user['id'] = $this->db->insert('hqsen_user', $sql_user);

            $hotel = $this->db->getRow("select * from hqsen_hotel  where id =" . $hotel_id);
            if($hotel and $sql_user['id']){
                $sql_user_date['hotel_id'] = $hotel['id'];
                $sql_user_date['hotel_name'] = $hotel['hotel_name'];
                $sql_user_date['area_id'] = $hotel['area_id'];
                $sql_user_date['hotel_area'] = $this-> get_sh_area($hotel['area_sh_id']);
                $sql_user_date['user_id'] = $sql_user['id'];
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

        $user = $this->db->getRows("select *,hu.id as hu_id from hqsen_user as hu left join hqsen_user_data as hud on hu.id=hud.user_id where  hu.del_flag = 1 and hu.user_type > 10 order by hu.id desc " . $sql_limit);
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
                $user_item = array(
                    'user_id' => $one_user['hu_id'],
                    'user_name' => $one_user['user_name'],
                    'user_type' => $user_type,
                    'user_status' => $one_user['user_status'],
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
        if($user_name and $user_type and $password){
            $sql_user['user_name'] = $user_name;
            $sql_user['user_type'] = $user_type;
            $sql_user['password'] = md5($password);
            $sql_user['create_time'] = time();
            $sql_user['del_flag'] = 1;
            $sql_user['id'] = $this->db->insert('hqsen_user', $sql_user);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function innerAccountDetail(){
        $user_id = $this->postInt('id');
        $user = $this->db->getRow("select * from hqsen_user where id = " . $user_id);
        $data = [];
        if($user){
            $user_item = array(
                'user_name' => $user['user_name'],
                'user_type' => $user['user_type'],
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
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }
}