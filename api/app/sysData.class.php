<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 接口订单类api user
 */


namespace api\app;

class sysData extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    // 更新数据
    public function updateData(){

        $data['order_type'][] = array('type'=>'1', 'type_name'=>'婚宴');
        $data['order_type'][] = array('type'=>'2', 'type_name'=>'满月');
        $data['order_type'][] = array('type'=>'3', 'type_name'=>'订婚');

        $data['order_status'][] = array('type'=>'1', 'type_name'=>'待处理');
        $data['order_status'][] = array('type'=>'2', 'type_name'=>'跟踪中');
        $data['order_status'][] = array('type'=>'3', 'type_name'=>'待结算');
        $data['order_status'][] = array('type'=>'4', 'type_name'=>'已结算');
        $data['order_status'][] = array('type'=>'5', 'type_name'=>'已取消');

        $area_sql = 'select * from hqsen_area as ha left join hqsen_hotel as hh on ha.id=hh.area_id where ha.del_flag = 1 and hh.del_flag =1 ';
        $areas = $this->db->getRows($area_sql);
        $area_tree = [];
        foreach ($areas as $one_area){
            if($one_area){
                $area_tree[$one_area['area_id']][] = $one_area;
            }
        }

        foreach ($area_tree as $area_id=>$one_area_hotels){
            $one_area_item = [];
            foreach ($one_area_hotels as $one_hotel){
                $hotel_item = array(
                    'hotel_id'=> $one_hotel['id'],
                    'hotel_name'=> $one_hotel['hotel_name'],
                );
                $one_area_item['area'] = $one_hotel['area_id'];
                $one_area_item['area_name'] = $one_hotel['area_name'];
                $one_area_item['area_hotel'][] = $hotel_item;
            }
            $data['order_area'][] = $one_area_item;
        }
//        $data['order_area'][] = array('area'=>'1', 'area_name'=>'普陀区' , 'area_hotel'=> [
//            array(
//                'hotel_id'=> 1,
//                'hotel_name'=> '希尔顿酒店',
//            ),
//            array(
//                'hotel_id'=> 2,
//                'hotel_name'=> '喜来登酒店',
//            )
//        ]);
//        $data['order_area'][] = array('area'=>'2', 'area_name'=>'浦东区');
//        $data['order_area'][] = array('type'=>'3', 'type_name'=>'指定酒店');
        $data['update_status'] = array('version'=>'1.0', 'update_now'=>'1');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }




}