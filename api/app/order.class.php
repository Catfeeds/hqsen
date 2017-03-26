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
        $phone = $this->postInt('order_phone');
        if($order_type and $phone){
            $this->appDie();
        } else {
            $this->appDie($this->back_code['order']['phone_type_exist'], $this->back_msg['order']['phone_type_exist']);
        }
    }

    public function createKeZi(){
        $customer_name = $this->postString('customer_name');
        $order_type = $this->postInt('order_type');
        $order_phone = $this->postInt('order_phone');
        $order_area = $this->postString('order_area');
        $order_hotel = $this->postString('order_hotel');
        $desk_count = $this->postInt('desk_count');
        $order_money = $this->postInt('order_money');
        $use_date = $this->postString('use_date');
        $watch_user = $this->postString('watch_user');
        $order_desc = $this->postString('order_desc');
        if($order_type and $order_phone and $order_area and $order_hotel){
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    public function orderKeZiList(){
        $order_status = $this->getInt('order_status');
        $order_page = $this->getInt('order_page', 1);
        $order_item = array(
            'id' => (int)116,
            'create_time' => (string)time(),
            'order_status' => (int)1,
            'order_phone' => (string)'186 2736 1728',
            'watch_user' => (string)'上海国际饭店',
        );
        $order_list['order_list'] = [$order_item, $order_item, $order_item];
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
    }

    public function orderKeZiDetail(){
        $order_id = $this->getInt('order_id');
        if($order_id){
            $order_item = array(
                'id' => (int)116,
                'create_time' => (string)time(),
                'order_status' => (int)1,
                'order_phone' => (string)'186 2736 1728',
                'watch_user' => (string)'上海国际饭店',
                'customer_name' => (string)'monkey',
                'order_type' => (int)1,
                'order_type_name' => (string)'婚宴',
                'order_area' => (int)1,
                'order_area_name' => (string)'指定酒店',
                'desk_count' => (string)'18',
                'order_money' => (string)'120000',
                'use_date' => (string)'17-10-01',
                'order_desc' => (string)'备注信息',
            );
            $order_list['order_item'] = $order_item;
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $order_list);
        }
    }


}