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
//        $this-> loginInit();
    }

    // 更新数据
    public function updateData(){
        $c_appver = $this->postString('appver');
        $appver = 0;
//        $data['order_type'][] = array('type'=>'1', 'type_name'=>'婚宴');
//        $data['order_type'][] = array('type'=>'2', 'type_name'=>'会务');
//        $data['order_type'][] = array('type'=>'3', 'type_name'=>'生日宴 团宴 宝宝宴');
//
//        $data['order_status'][] = array('type'=>'1', 'type_name'=>'待处理');
//        $data['order_status'][] = array('type'=>'2', 'type_name'=>'跟踪中');
//        $data['order_status'][] = array('type'=>'3', 'type_name'=>'待结算');
//        $data['order_status'][] = array('type'=>'4', 'type_name'=>'已结算');
//        $data['order_status'][] = array('type'=>'5', 'type_name'=>'已取消');

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
        if(isset($c_appver)) {
            $subs = explode('.', $c_appver);
            foreach ($subs as $sub) {
                if ($sub < 10) {
                    $appver .= (0 . $sub);
                } else {
                    $appver .= $sub;
                }
            }
            $appver = intval($appver);
        }
        if($appver < 10000){
            $data['update_status'] = array('version'=>'1.0.0', 'update_now'=>'3');// 正常 2强制更新 3普通更新
        } else {
            $data['update_status'] = array('version'=>'1.0.0', 'update_now'=>'1');// 正常 2强制更新 3普通更新
        }

        $data['url'] = 'http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/package/sen-release.apk';
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function log(){
        $content = $this->postString('content');
        $uuid = $this->postString('uuid');
        $sql['log_content'] = $content;
        $sql['create_time'] = time();
        $sql['uuid'] = $uuid;
        $sql['id'] = $this->db->insert('hqsen_log', $sql);
        if($sql['id']){
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['mysql_err'], $this->back_msg['sys']['mysql_err'], $sql);
        }

    }




}