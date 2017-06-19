<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:hotel 后台接口 酒店类
 */


namespace api\web;

class hotel extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    // 酒店列表
    public function hotelList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $hotel = $this->db->getRows("select *  from hqsen_hotel  where del_flag = 1 order by id desc " . $sql_limit);
        $data = [];
        foreach ($hotel as $one_hotel){
            if($one_hotel){
                $hotel_item = array(
                    'hotel_id' => $one_hotel['id'],
                    'hotel_name' => $one_hotel['hotel_name'],
                    'area_list' => $this-> get_sh_area($one_hotel['area_sh_id']),
                    'hotel_address' => $one_hotel['hotel_address'],
                );
                $data['list'][] = $hotel_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_hotel', 'del_flag = 1');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 创建酒店
    public function hotelCreate(){
        $hotel_name = $this->postString('hotel_name');
        $hotel_address = $this->postString('hotel_address');
        $area_sh_id = $this->postString('area_id');
        $hotel_level = $this->postString('hotel_level');
        $weight = $this->postString('weight');
        if($hotel_name and $hotel_address and $area_sh_id){
            $area_sh = $this->db->getRow("select * from hqsen_area_sh  where id =  " . $area_sh_id);
            $sql_order['hotel_name'] = $hotel_name;
            $sql_order['hotel_address'] = $hotel_address;
            $sql_order['area_sh_id'] = $area_sh_id;
            $sql_order['area_id'] = isset($area_sh['link_area_id']) ? $area_sh['link_area_id'] : 0;
            $sql_order['hotel_level'] = $hotel_level;
            $sql_order['weight'] = $weight;
            $sql_order['create_time'] = time();
            $sql_order['del_flag'] = 1;
            $this->db->insert('hqsen_hotel', $sql_order);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 编辑酒店
    public function hotelEdit(){
        $hotel_id = $this->postString('id');
        $hotel_name = $this->postString('hotel_name');
        $hotel_address = $this->postString('hotel_address');
        $area_sh_id = $this->postString('area_id');
        $hotel_level = $this->postString('hotel_level');
        $weight = $this->postString('weight');
        if($hotel_id and ($hotel_name or $hotel_address or $area_sh_id)){
            $sql_order = [];
            if($hotel_name){
                $sql_order['hotel_name'] = $hotel_name;
            }
            if($hotel_address){
                $sql_order['hotel_address'] = $hotel_address;
            }
            if($weight){
                $sql_order['weight'] = $weight;
            }
            if($area_sh_id){
                $area_sh = $this->db->getRow("select * from hqsen_area_sh  where id =  " . $area_sh_id);
                $sql_order['area_id'] = isset($area_sh['link_area_id']) ? $area_sh['link_area_id'] : 0;
                $sql_order['area_sh_id'] = $area_sh_id;
            }
            if($hotel_level){
                $sql_order['hotel_level'] = $hotel_level;
            }
            $this->db->update('hqsen_hotel', $sql_order, ' id = ' . $hotel_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 删除酒店 修改酒店状态 不真正删除数据
    public function hotelDelete(){
        $hotel_id = $this->postString('id');
        if($hotel_id){
            $sql_order['del_flag'] = 2;
            $this->db->update('hqsen_hotel', $sql_order, ' id = ' . $hotel_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 酒店详情
    public function hotelDetail(){
        $hotel_id = $this->postString('id');
        if($hotel_id){
            $hotel = $this->db->getRow("select * from hqsen_hotel  where id =  " . $hotel_id);
            $hotel_item = array(
                'hotel_id' => $hotel['id'],
                'hotel_name' => $hotel['hotel_name'],
                'area_id' => (string)$hotel['area_sh_id'],
                'hotel_address' => $hotel['hotel_address'],
                'hotel_level' => $hotel['hotel_level'],
            );
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $hotel_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    public function hotelDataCreate(){
        $hotel_id = $this->postString('id');
        $hotel_low = $this->postString('hotel_low');
        $hotel_high = $this->postString('hotel_high');
        $hotel_max_desk = $this->postString('hotel_max_desk');
        $hotel_type = $this->postString('hotel_type');
        $hotel_phone = $this->postString('hotel_phone');
        $hotel_image = $this->postString('hotel_image');
        if($hotel_id){
            $hotel_data = $this->db->getRow("select * from hqsen_hotel_data  where id =  " . $hotel_id);
            $sql_order['id'] = $hotel_id;
            $sql_order['hotel_low'] = $hotel_low;
            $sql_order['hotel_high'] = $hotel_high;
            $sql_order['hotel_max_desk'] = $hotel_max_desk;
            $sql_order['hotel_type'] = $hotel_type;
            $sql_order['hotel_phone'] = $hotel_phone;
            $sql_order['hotel_image'] = $hotel_image;
            if($hotel_data){
                $this->db->update('hqsen_hotel_data', $sql_order, ' id = ' . $hotel_id);
            } else {
                $this->db->insert('hqsen_hotel_data', $sql_order);
            }
            $update_hotel_data['is_data'] = 1;
            $this->db->update('hqsen_hotel', $update_hotel_data, ' id = ' . $hotel_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 酒店描述详情
    public function hotelDataDetail(){
        $hotel_id = $this->postString('id');
        if($hotel_id){
            $hotel = $this->db->getRow("select * from hqsen_hotel_data  where id =  " . $hotel_id);
            if($hotel){
                $hotel_item = array(
                    'hotel_id' => $hotel['id'],
                    'hotel_low' => $hotel['hotel_low'],
                    'hotel_high' => (string)$hotel['hotel_high'],
                    'hotel_max_desk' => $hotel['hotel_max_desk'],
                    'hotel_type' => $hotel['hotel_type'],
                    'hotel_phone' => $hotel['hotel_phone'],
                    'hotel_image' => $hotel['hotel_image'] ? $hotel['hotel_image'] : json_encode([]),
                );
            } else {
                $hotel_item = array(
                    'hotel_id' => $hotel_id,
                );
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $hotel_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    // 编辑酒店
    public function hotelDataEdit(){
        $hotel_id = $this->postString('id');
        $hotel_low = $this->postString('hotel_low');
        $hotel_high = $this->postString('hotel_high');
        $hotel_max_desk = $this->postString('hotel_max_desk');
        $hotel_type = $this->postString('hotel_type');
        $hotel_phone = $this->postString('hotel_phone');
        $hotel_image = $this->postString('hotel_image');
        if($hotel_id and ($hotel_low or $hotel_high or $hotel_max_desk or $hotel_phone or $hotel_type or $hotel_image)){
            $sql_order = [];
            if($hotel_low){
                $sql_order['hotel_low'] = $hotel_low;
            }
            if($hotel_high){
                $sql_order['hotel_high'] = $hotel_high;
            }
            if($hotel_max_desk){
                $sql_order['hotel_max_desk'] = $hotel_max_desk;
            }
            if($hotel_type){
                $sql_order['hotel_type'] = $hotel_type;
            }
            if($hotel_type){
                $sql_order['hotel_phone'] = $hotel_phone;
            }
            if($hotel_type){
                $sql_order['hotel_image'] = $hotel_image;
            }
            $this->db->update('hqsen_hotel_data', $sql_order, ' id = ' . $hotel_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function hotelMenuList(){
        $hotel_id = $this->postInt('id');
        $hotel = $this->db->getRows("select *  from hqsen_hotel_menu where del_flag=1 and hotel_id = " . $hotel_id);
        $data['list'] = [];
        foreach ($hotel as $one_hotel){
            if($one_hotel){
                $hotel_menu = array(
                    'id' => $one_hotel['id'],
                    'hotel_id' => $one_hotel['hotel_id'],
                    'menu_name' => $one_hotel['menu_name'],
                    'menu_money' => $one_hotel['menu_money'],
                );
                $data['list'][] = $hotel_menu;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function hotelMenuCreate(){
        $menu_name = $this->postString('menu_name');
        $menu_money = $this->postString('menu_money');
        $hotel_id = $this->postString('hotel_id');
        if($hotel_id and $menu_money and $menu_name){
            $sql_order['hotel_id'] = $hotel_id;
            $sql_order['menu_name'] = $menu_name;
            $sql_order['menu_money'] = $menu_money;
            $sql_order['id'] = $this->db->insert('hqsen_hotel_menu', $sql_order);
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sql_order);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function hotelMenuDelete(){
        $id = $this->postString('id');
        if($id){
            $sql_order['del_flag'] = 2;
            $this->db->update('hqsen_hotel_menu', $sql_order, ' id = ' . $id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function hotelRoomList(){
        $hotel_id = $this->postInt('id');
        $hotel = $this->db->getRows("select *  from hqsen_hotel_room where del_flag=1 and hotel_id = $hotel_id");
        $data['list'] = [];
        foreach ($hotel as $one_hotel_room){
            if($one_hotel_room){
                $hotel_menu = array(
                    'id' => $one_hotel_room['id'],
                    'hotel_id' => $one_hotel_room['hotel_id'],
                    'room_name' => $one_hotel_room['room_name'],
                    'room_max_desk' => $one_hotel_room['room_max_desk'],
                    'room_min_desk' => $one_hotel_room['room_min_desk'],
                    'room_best_desk' => $one_hotel_room['room_best_desk'],
                    'room_m' => $one_hotel_room['room_m'],
                    'room_lz' => $one_hotel_room['room_lz'],
                    'room_image' => $one_hotel_room['room_image'],
                    'room_high' => $one_hotel_room['room_high'],
                );
                $data['list'][] = $hotel_menu;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function hotelRoomCreate(){
        $room_name = $this->postString('room_name');
        $room_max_desk = $this->postString('room_max_desk');
        $room_min_desk = $this->postString('room_min_desk');
        $room_best_desk = $this->postString('room_best_desk');
        $room_m = $this->postString('room_m');
        $room_lz = $this->postString('room_lz');
        $room_image = $this->postString('room_image');
        $room_high = $this->postString('room_high');
        $hotel_id = $this->postString('hotel_id');
        if($hotel_id){
            $sql_order['hotel_id'] = $hotel_id;
            $sql_order['room_name'] = $room_name;
            $sql_order['room_max_desk'] = $room_max_desk;
            $sql_order['room_min_desk'] = $room_min_desk;
            $sql_order['room_best_desk'] = $room_best_desk;
            $sql_order['room_m'] = $room_m;
            $sql_order['room_lz'] = $room_lz;
            $sql_order['room_image'] = $room_image ? $room_image : '';
            $sql_order['room_high'] = $room_high;
            $this->db->insert('hqsen_hotel_room', $sql_order);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function hotelRoomDelete(){
        $id = $this->postString('id');
        if($id){
            $sql_order['del_flag'] = 2;
            $this->db->update('hqsen_hotel_room', $sql_order, ' id = ' . $id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 酒店描述详情
    public function hotelRoomDetail(){
        $hotel_id = $this->postString('id');
        if($hotel_id){
            $one_hotel_room = $this->db->getRow("select * from hqsen_hotel_room  where id =  " . $hotel_id);
            $hotel_item = array(
                'id' => $one_hotel_room['id'],
                'hotel_id' => $one_hotel_room['hotel_id'],
                'room_name' => $one_hotel_room['room_name'],
                'room_max_desk' => $one_hotel_room['room_max_desk'],
                'room_min_desk' => $one_hotel_room['room_min_desk'],
                'room_best_desk' => $one_hotel_room['room_best_desk'],
                'room_m' => $one_hotel_room['room_m'],
                'room_lz' => $one_hotel_room['room_lz'],
                'room_image' => $one_hotel_room['room_image'],
                'room_high' => $one_hotel_room['room_high'],
            );
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $hotel_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    // 编辑酒店宴会厅
    public function hotelRoomEdit(){
        $id = $this->postString('id');
        $room_name = $this->postString('room_name');
        $room_max_desk = $this->postString('room_max_desk');
        $room_min_desk = $this->postString('room_min_desk');
        $room_best_desk = $this->postString('room_best_desk');
        $room_m = $this->postString('room_m');
        $room_lz = $this->postString('room_lz');
        $room_image = $this->postString('room_image');
        $room_high = $this->postString('room_high');
        $hotel_id = $this->postString('hotel_id');
        $sql_order = $this->db->getRow("select * from hqsen_hotel_room  where id =  " . $id);
        if($sql_order){
            if($room_name){
                $sql_order['room_name'] = $room_name;
            }
            if($room_max_desk){
                $sql_order['room_max_desk'] = $room_max_desk;
            }
            if($room_min_desk){
                $sql_order['room_min_desk'] = $room_min_desk;
            }
            if($room_best_desk){
                $sql_order['room_best_desk'] = $room_best_desk;
            }
            if($room_m){
                $sql_order['room_m'] = $room_m;
            }
            if($room_lz){
                $sql_order['room_lz'] = $room_lz;
            }
            if($room_image){
                $sql_order['room_image'] = $room_image;
            }
            if($room_high){
                $sql_order['room_high'] = $room_high;
            }
            $this->db->update('hqsen_hotel_room', $sql_order, ' id = ' . $id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    public function hotelRecList(){
        $hotel = $this->db->getRows("select *  from hqsen_hotel_rec where del_flag=1 order by id desc ");
        $data = [];
        foreach ($hotel as $one_hotel){
            if($one_hotel){
                $hotel_detail = $this->db->getRow("select *  from hqsen_hotel where id=" . $one_hotel['hotel_id']);
                $hotel_menu = array(
                    'id' => $one_hotel['id'],
                    'hotel_id' => $one_hotel['hotel_id'],
                    'hotel_name' => $hotel_detail['hotel_name'],
                    'area_list' => $this-> get_sh_area($hotel_detail['area_sh_id']),
                    'hotel_weight' => $one_hotel['hotel_weight'],
                );
                $data['list'][] = $hotel_menu;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function getHotelListByAreaId(){
        $id = $this->postInt('id');
        $hotel = $this->db->getRows("select *  from hqsen_hotel  where del_flag = 1 and area_sh_id = $id" );
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

    public function hotelRecCreate(){
        $hotel_weight = $this->postString('hotel_weight');
        $hotel_id = $this->postString('hotel_id');
        if($hotel_id and $hotel_weight){
            $sql_order['hotel_id'] = $hotel_id;
            $sql_order['hotel_weight'] = $hotel_weight;
            $this->db->insert('hqsen_hotel_rec', $sql_order);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    public function hotelRecDelete(){
        $id = $this->postString('id');
        if($id){
            $sql_order['del_flag'] = 2;
            $this->db->update('hqsen_hotel_rec', $sql_order, ' id = ' . $id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }



}