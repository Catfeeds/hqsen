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
                    'area_list' => $this-> get_sh_area($one_hotel['area_id']),
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
        $area_id = $this->postString('area_id');
        $hotel_level = $this->postString('hotel_level');
        if($hotel_name and $hotel_address and $area_id){
            $sql_order['hotel_name'] = $hotel_name;
            $sql_order['hotel_address'] = $hotel_address;
            $sql_order['area_id'] = $area_id;
            $sql_order['hotel_level'] = $hotel_level;
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
        $area_id = $this->postString('area_id');
        $hotel_level = $this->postString('hotel_level');
        if($hotel_id and ($hotel_name or $hotel_address or $area_id)){
            $sql_order = [];
            if($hotel_name){
                $sql_order['hotel_name'] = $hotel_name;
            }
            if($hotel_address){
                $sql_order['hotel_address'] = $hotel_address;
            }
            if($area_id){
                $sql_order['area_id'] = $area_id;
            }
            if($area_id){
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
                'area_id' => (string)$hotel['area_id'],
                'hotel_address' => $hotel['hotel_address'],
                'hotel_level' => $hotel['hotel_level'],
            );
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $hotel_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


}