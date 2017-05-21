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
        $sign = $this->db->getRows("select *  from hqsen_user_kezi_order_sign  where sign_status = 1 order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $item['id'] = $one_sign['id'];
            $item['order_money'] = $one_sign['order_money'];
            $item['order_other_money'] = $one_sign['order_other_money'];
            $item['sign_pic_count'] = count(json_decode($one_sign['sign_pic']));
            $item['del_flag'] = $one_sign['del_flag'];//1初次录入 2再次录入 3审批失败
        }
        $data['count'] = $this->db->getCount('hqsen_user_kezi_order_sign', 'del_flag != 0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $sign);
    }

    // 客资签单 创建总经理审批
    public function keziSignFollowCreate(){
        $user_sign_id = $this->postInt('user_sign_id');
        $status_desc = $this->postString('status_desc');
        $sign_status = $this->postString('boss_sign_status', 1);
        // 审批流程数据
        $sign_follow['boss_sign_status'] = $sign_status;
        $sign_follow['status_desc'] = $status_desc;
        $sign_follow['user_sign_id'] = $user_sign_id;
        $sign_follow['create_time'] = time();
        $this->db->insert('hqsen_user_kezi_sign_follow', $sign_follow);
        // 审批成功  更新签单数据  不更新跟踪者订单数据 还是待审核状态
        if(isset($sign_follow['id']) and $sign_follow['id']){
            $order_sign['boss_sign_status'] = $sign_status;
            $this->db->update('hqsen_user_kezi_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);
        }
        $this->appDie();
    }




}