<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:finance 后台接口 财务类
 */


namespace api\web;

class boss extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    // 客资订单列表
    public function keziOrderSignList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        // 总经理要在财务审批通过基础上
        $sign = $this->db->getRows("select *  from hqsen_user_kezi_order_sign  where sign_status = 2 order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $item['id'] = $one_sign['id'];
            $item['order_money'] = $one_sign['order_money'];
            $item['order_other_money'] = $one_sign['order_other_money'];
            $item['sign_pic_count'] = count(json_decode($one_sign['sign_pic']));
            $item['boss_sign_status'] = $one_sign['boss_sign_status'];//1未处理 2通过 3驳回
            $list['list'][] = $item;
        }
        $list['count'] = $this->db->getCount('hqsen_user_kezi_order_sign', 'sign_status = 2');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $list);
    }

    // 客资签单 创建总经理审批
    public function keziSignFollowCreate()
    {
        $user_sign_id = $this->postInt('user_sign_id');
        $status_desc = $this->postString('status_desc');
        $sign_status = $this->postString('boss_sign_status', 1);
        if($status_desc == 'undefined'){
            $status_desc = '';
        }
        // 审批流程数据
        if ($user_sign_id and $sign_status) {
            $sign_follow['boss_sign_status'] = $sign_status;
            $sign_follow['status_desc'] = $status_desc;
            $sign_follow['user_sign_id'] = $user_sign_id;
            $sign_follow['create_time'] = time();
            $sign_follow['id'] = $this->db->insert('hqsen_user_kezi_sign_follow', $sign_follow);
            // 审批成功  更新签单数据  不更新跟踪者订单数据 还是待审核状态
            if (isset($sign_follow['id']) and $sign_follow['id']) {
                $order_sign['boss_sign_status'] = $sign_status;
                $this->db->update('hqsen_user_kezi_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);
                // 总经理通过  处理跟踪者和提供者订单状态
                if($sign_status == 2){
                    $sign = $this->db->getRow("select *  from hqsen_user_kezi_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 3;
                        $user_order['user_order_status'] = 2;
                        $this->db->update('hqsen_user_kezi_order', $user_order, ' id = ' . $sign['user_kezi_order_id']);
                    }
                }
                if($sign_status == 3){
                    $order_sign['sign_status'] = 4; // 签单信息更改为总经理驳回
                    $this->db->update('hqsen_user_kezi_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);
                }
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    //客资签单 详情页
    public function keziSignDetail(){
        $sign_id = $this->postInt('id');
        $sign = $this->db->getRow("select *  from hqsen_user_kezi_order_sign where id=" . $sign_id);
        $item['id'] = $sign['id'];
        $item['order_money'] = $sign['order_money'];
        $item['order_other_money'] = $sign['order_other_money'];
        $item['sign_pic'] = json_decode($sign['sign_pic']);
        $item['sign_using_time'] = date('Y-m-d',$sign['sign_using_time']);
        $item['boss_sign_status'] = $sign['boss_sign_status'];//1未处理 2通过 3驳回

        $sign_follow_list = $this->db->getRows("select *  from hqsen_user_kezi_sign_follow where user_sign_id = $sign_id order by id desc ");
        $follow_list = [];
        foreach ($sign_follow_list as $one_follow){
            if($one_follow['boss_sign_status'] > 1){
                $one_item['status_type'] = (string)1;// 1总经理审核 2 财务审核
            } else {
                $one_item['status_type'] = (string)2;
            }
            $one_item['create_time'] = date('Y-m-d',$one_follow['create_time']);
            $one_item['status'] = $one_follow['boss_sign_status'] > 1 ? $one_follow['boss_sign_status'] : $one_follow['sign_status'];
            $one_item['status_desc'] = $one_follow['status_desc'];
            $follow_list[] = $one_item;
        }
        $item['follow_list'] = $follow_list;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $item);
    }

    // 搭建订单列表
    public function dajianOrderSignList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        // 总经理要在财务审批通过基础上
        $sign = $this->db->getRows("select *  from hqsen_user_dajian_order_sign  where sign_status in (2,4) and sign_type = 0 order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $item['id'] = $one_sign['id'];
            $item['order_money'] = $one_sign['order_money'];
            $item['sign_type'] = $one_sign['sign_type'];
            $item['sign_pic_count'] = count(json_decode($one_sign['sign_pic']));
            $item['del_flag'] = $one_sign['del_flag'];//0未知 1初次录入 2再次录入
            $item['boss_sign_status'] = $one_sign['boss_sign_status'];//1未处理 2通过 3驳回
            $list['list'][] = $item;
        }
        $list['count'] = $this->db->getCount('hqsen_user_dajian_order_sign', ' sign_status = 2 and sign_type = 1 ');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $list);
    }

    //搭建详情页数据
    public function dajianSignDetail(){
        $sign_id = $this->postInt('id');
        $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $sign_id);
        $item['id'] = $sign['id'];
        $item['order_money'] = $sign['order_money'];
        $item['sign_using_time'] = date('Y-m-d',$sign['sign_using_time']);
        $item['first_order_money'] = $sign['first_order_money'];
        $item['first_order_using_time'] = date('Y-m-d',$sign['first_order_using_time']);
        $item['sign_pic'] = json_decode($sign['sign_pic']);
        $item['boss_sign_status'] = $sign['boss_sign_status'];//1未处理 2通过 3驳回

        $sign_follow_list = $this->db->getRows("select *  from hqsen_user_dajian_sign_follow where user_sign_id = $sign_id order by id desc ");
        $follow_list = [];
        foreach ($sign_follow_list as $one_follow){
            if($one_follow['boss_sign_status'] > 1){
                $one_item['status_type'] = (string)1;// 1总经理审核 2 财务审核
            } else {
                $one_item['status_type'] = (string)2;
            }
            $one_item['create_time'] = date('Y-m-d',$one_follow['create_time']);
            $one_item['status'] = $one_follow['boss_sign_status'] > 1 ? $one_follow['boss_sign_status'] : $one_follow['sign_status'];
            $one_item['status_desc'] = $one_follow['status_desc'];
            $follow_list[] = $one_item;
        }
        $item['follow_list'] = $follow_list;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $item);
    }

    // 搭建首销签单 创建总经理审批
    public function dajianSignFollowCreate()
    {
        $user_sign_id = $this->postInt('user_sign_id');
        $status_desc = $this->postString('status_desc');
        $sign_status = $this->postString('boss_sign_status', 1);
        // 审批流程数据
        if ($user_sign_id and $sign_status) {
            $sign_follow['boss_sign_status'] = $sign_status;
            $sign_follow['status_desc'] = $status_desc;
            $sign_follow['user_sign_id'] = $user_sign_id;
            $sign_follow['create_time'] = time();
            $sign_follow['id'] = $this->db->insert('hqsen_user_dajian_sign_follow', $sign_follow);
            // 审批成功  更新签单数据  不更新跟踪者订单数据 还是待审核状态
            if (isset($sign_follow['id']) and $sign_follow['id']) {
                $order_sign['boss_sign_status'] = $sign_status;
                $this->db->update('hqsen_user_dajian_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);
            }
            // 总经理通过  创建二销
            if($sign_status == 2){
                $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $user_sign_id);
                if($sign){
                    //
                    $user_order['order_status'] = 3;
                    $user_order['user_order_status'] = 2;
                    // 二销信息
                    $user_order['erxiao_order_status'] = 1;//  0首销还未通过 1待处理 2待审核 3已完结
                    $user_order['erxiao_user_id'] = 62;// 写死二销 ex001
                    $user_order['erxiao_sign_type'] = 1;// 二销签单状态1 中款 2尾款 3附加款 4尾款时间
                    $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $sign['user_dajian_order_id']);
                }
            }
            if($sign_status == 3){
                $order_sign['sign_status'] = 4; // 签单信息更改为总经理驳回
                $this->db->update('hqsen_user_dajian_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

}