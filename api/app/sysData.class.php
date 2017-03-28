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

        $data['order_area'][] = array('area'=>'1', 'area_name'=>'普陀区' , 'area_hotel'=> [
            array(
                'hotel_id'=> 1,
                'hotel_name'=> '希尔顿酒店',
            ),
            array(
                'hotel_id'=> 2,
                'hotel_name'=> '喜来登酒店',
            )
        ]);
        $data['order_area'][] = array('area'=>'2', 'area_name'=>'浦东区');
        $data['order_area'][] = array('type'=>'3', 'type_name'=>'指定酒店');
        $data['update_status'] = array('version'=>'1.0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }




}