<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:finance 后台接口 财务类
 */


namespace api\web;

class finance extends base {

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
        $sign = $this->db->getRows("select *  from hqsen_user_kezi_order_sign order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $item['id'] = $one_sign['id'];
            $item['order_money'] = $one_sign['order_money'];
            $item['kezi_order_id'] = $one_sign['kezi_order_id'];
            $item['create_time'] = date('Y-m-d H:i:s' , $one_sign['create_time']);
            $item['update_time'] = date('Y-m-d H:i:s' , $one_sign['update_time']);
            $item['order_other_money'] = $one_sign['order_other_money'];// 客资附加款  去掉不用 0607
            $item['sign_pic_count'] = count(json_decode($one_sign['sign_pic']));
            $item['sign_status'] = $one_sign['sign_status'];//1未处理 2通过 3驳回 4总经理驳回 5待修改
            $list['list'][] = $item;
        }
        $list['count'] = $this->db->getCount('hqsen_user_kezi_order_sign', 'del_flag != 0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $list);
    }

    // 客资签单 创建财务审批
    public function keziSignFollowCreate(){
        $user_sign_id = $this->postInt('user_sign_id');
        $status_desc = $this->postString('status_desc');
        $sign_status = $this->postString('sign_status', 1);

        if($status_desc == 'undefined'){
            $status_desc = '';
        }
        if($user_sign_id and $sign_status){
            // 审批流程数据
            $sign_follow['sign_status'] = $sign_status;
            $sign_follow['status_desc'] = $status_desc;
            $sign_follow['user_sign_id'] = $user_sign_id;
            $sign_follow['create_time'] = time();
            $sign_follow['id'] = $this->db->insert('hqsen_user_kezi_sign_follow', $sign_follow);
            // 审批成功  更新签单数据  不更新跟踪者订单数据 还是待审核状态
            if(isset($sign_follow['id']) and $sign_follow['id']){
                $order_sign['sign_status'] = $sign_status; // 用户签单  财务审核状态
                $order_sign['update_time'] = time(); // 用户签单  财务审核状态
                // 处理跟踪者和提供者订单状态
                if($sign_status == 3){
                    $sign = $this->db->getRow("select *  from hqsen_user_kezi_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 6;
                        $user_order['user_order_status'] = 4;
                        $this->db->update('hqsen_user_kezi_order', $user_order, ' id = ' . $sign['user_kezi_order_id']);
                    }
                }
                // 处理跟踪者和提供者订单状态
                if($sign_status == 2){
                    $order_sign['boss_sign_status'] = 1; // 用户签单  总经理审核状态
                    $sign = $this->db->getRow("select *  from hqsen_user_kezi_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 2;
                        $user_order['user_order_status'] = 1;
                        $this->db->update('hqsen_user_kezi_order', $user_order, ' id = ' . $sign['user_kezi_order_id']);
                    }
                }
                if($sign_status == 5){
                    $sign = $this->db->getRow("select *  from hqsen_user_kezi_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 5;
                        $user_order['user_order_status'] = 1;
                        $this->db->update('hqsen_user_kezi_order', $user_order, ' id = ' . $sign['user_kezi_order_id']);
                    }
                }
                $this->db->update('hqsen_user_kezi_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);
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
        $item['sign_status'] = $sign['sign_status'];//1未处理 2通过 3驳回

        $sign_follow_list = $this->db->getRows("select *  from hqsen_user_kezi_sign_follow where user_sign_id = $sign_id order by id desc ");
        $follow_list = [];
        foreach ($sign_follow_list as $one_follow){
            if($one_follow['boss_sign_status'] > 1){
                $one_item['status_type'] = (string)1;// 1总经理审核 2 财务审核
            } else {
                $one_item['status_type'] = (string)2;
            }
            $one_item['create_time'] = date('Y-m-d H:i:s',$one_follow['create_time']);
            $one_item['status'] = $one_follow['boss_sign_status'] > 1 ? $one_follow['boss_sign_status'] : $one_follow['sign_status'];
            $one_item['status_desc'] = $one_follow['status_desc'];
            $follow_list[] = $one_item;
        }
        $item['follow_list'] = $follow_list;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $item);
    }


    // 搭建 订单列表
    public function dajianOrderSignList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $sign = $this->db->getRows("select *  from hqsen_user_dajian_order_sign order by id desc " . $sql_limit);
        foreach ($sign as $one_sign){
            $item['id'] = $one_sign['id'];
            $item['sign_other_sign_id'] = $one_sign['sign_other_sign_id'];
            $item['dajian_order_id'] = $one_sign['dajian_order_id'];
            $item['create_time'] = date('Y-m-d H:i:s' , $one_sign['create_time']);
            $item['update_time'] = date('Y-m-d H:i:s' , $one_sign['update_time']);
            $item['order_money'] = $one_sign['order_money'];
            $item['sign_type'] = $one_sign['sign_type'];// 搭建二销签单状态 0首款   1 中款  2尾款  3附加款 4尾款时间
            $item['sign_pic_count'] = count(json_decode($one_sign['sign_pic']));
            $item['del_flag'] = $one_sign['del_flag'];//0未知 1初次录入 2再次录入
            $item['sign_status'] = $one_sign['sign_status'];//财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改
            $item['sign_other_sign_status'] = $one_sign['sign_other_sign_status'];//财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改
            if($item['sign_type'] > 0){
                $item['sign_status'] = $item['sign_other_sign_status'];
            }
            $list['list'][] = $item;
        }
        $list['count'] = $this->db->getCount('hqsen_user_dajian_order_sign', 'del_flag != 0');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $list);
    }

    //搭建首销 详情页
    public function dajianSignDetail(){
        $sign_id = $this->postInt('id');
        $sign_other_sign_id = $this->postInt('sign_other_sign_id');
        $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $sign_id);
        $item['id'] = $sign['id'];
        $item['sign_other_sign_id'] = $sign['sign_other_sign_id'];
        $item['order_money'] = $sign['order_money'];
        $item['sign_using_time'] = date('Y-m-d',$sign['sign_using_time']);
        $item['first_order_money'] = $sign['first_order_money'];
        $item['first_order_using_time'] = date('Y-m-d',$sign['first_order_using_time']);
        $item['sign_pic'] = json_decode($sign['sign_pic']);
        $item['sign_status'] = $sign['sign_status'];//1未处理 2通过 3驳回
        $sign_follow_list = $this->db->getRows("select *  from hqsen_user_dajian_sign_follow where user_sign_id = $sign_id order by id desc ");
        $follow_list = [];
        foreach ($sign_follow_list as $one_follow){
            if($one_follow['boss_sign_status'] > 1){
                $one_item['status_type'] = (string)1;// 1总经理审核 2 财务审核
            } else {
                $one_item['status_type'] = (string)2;
            }
            $one_item['create_time'] = date('Y-m-d H:i:s',$one_follow['create_time']);
            $one_item['status'] = $one_follow['boss_sign_status'] > 1 ? $one_follow['boss_sign_status'] : $one_follow['sign_status'];
            $one_item['status_desc'] = $one_follow['status_desc'];
            $follow_list[] = $one_item;
        }
        $item['follow_list'] = $follow_list;

        if($sign_other_sign_id){
            $other_sign = $this->db->getRow("select *  from hqsen_user_dajian_order_other_sign where id=" . $sign_other_sign_id);
            $item['other_item_id'] = $other_sign['id'];
            $item['other_item_sign_type'] = $other_sign['sign_type'];
            $item['other_item_order_money'] = $other_sign['order_money'];
            $item['other_item_order_time'] = date('Y-m-d',$other_sign['order_time']);
            $item['other_item_weikuan_old_time'] = date('Y-m-d',$sign['sign_using_time']);
            $item['other_item_weikuan_new_time'] = date('Y-m-d',$other_sign['order_time']);
            $item['other_item_order_sign_pic'] = json_decode($other_sign['order_sign_pic']);
            $item['other_item_sign_status'] = $other_sign['sign_status'];//财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改
//            $item['other_item'] = $other_item;
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $item);
    }

    // 搭建首销签单 创建财务审批
    public function dajianSignFollowCreate(){
        $user_sign_id = $this->postInt('user_sign_id');
        $status_desc = $this->postString('status_desc');
        $sign_status = $this->postString('sign_status', 1);

        if($status_desc == 'undefined'){
            $status_desc = '';
        }
        if($user_sign_id and $sign_status){
            // 审批流程数据
            $sign_follow['sign_status'] = $sign_status;
            $sign_follow['status_desc'] = $status_desc;
            $sign_follow['user_sign_id'] = $user_sign_id;
            $sign_follow['create_time'] = time();
            $sign_follow['id'] = $this->db->insert('hqsen_user_dajian_sign_follow', $sign_follow);
            // 审批成功  更新签单数据  不更新跟踪者订单数据 还是待审核状态
            if(isset($sign_follow['id']) and $sign_follow['id']){
                $order_sign['sign_status'] = $sign_status;// 用户签单  财务审核状态
                $order_sign['update_time'] = time();// 用户签单  财务审核状态
                // 不通过
                if($sign_status == 3){
                    $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 6;
                        $user_order['user_order_status'] = 4;
                        $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $sign['user_dajian_order_id']);
                    }
                }
                // 通过
                if($sign_status == 2){
                    $order_sign['boss_sign_status'] = 1; // 用户签单  总经理审核状态
                    $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 2;
                        $user_order['user_order_status'] = 1;
                        $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $sign['user_dajian_order_id']);
                    }
                }
                // 待处理
                if($sign_status == 5){
                    $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $user_sign_id);
                    if($sign){
                        $user_order['order_status'] = 5;
                        $user_order['user_order_status'] = 1;
                        $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $sign['user_dajian_order_id']);
                    }
                }
                $this->db->update('hqsen_user_dajian_order_sign', $order_sign, ' id = ' . $sign_follow['user_sign_id']);

            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


    // 搭建二销签单 创建财务审批
    public function dajianErXiaoFollowCreate(){
        $user_sign_id = $this->postInt('user_sign_id');
        $status_desc = $this->postString('status_desc');
        $sign_status = $this->postString('sign_status', 1);

        if($status_desc == 'undefined'){
            $status_desc = '';
        }
        if($user_sign_id and $sign_status){
            // 审批流程数据
            $sign_follow['sign_status'] = $sign_status;
            $sign_follow['status_desc'] = $status_desc;
            $sign_follow['user_sign_id'] = $user_sign_id;
            $sign_follow['create_time'] = time();
            $sign_follow['id'] = $this->db->insert('hqsen_user_dajian_sign_follow', $sign_follow);
            // 审批成功  更新签单数据  不更新跟踪者订单数据 还是待审核状态
            if(isset($sign_follow['id']) and $sign_follow['id']){
                $sign = $this->db->getRow("select *  from hqsen_user_dajian_order_sign where id=" . $user_sign_id);
                $user_order_sign['sign_other_sign_status'] = $sign_status;// 二销财务审核 0未知 1未处理 2通过 3驳回 4 总经理驳回 5待修改
                $this->db->update('hqsen_user_dajian_order_sign', $user_order_sign, ' id = ' . $user_sign_id);// 签单搭建 财务状态

                // 通过
                if($sign_status == 2 and $sign){
                    // 如果尾款  进入已完成 非尾款 进入待处理
                    if($sign['sign_type'] == 2){
                        $user_order['erxiao_order_status'] = 3;// 搭建二销修改成 已完成
                    } else {
                        $user_order['erxiao_order_status'] = 1;// 搭建二销修改成 待处理
                    }
                    // 通过修改尾款时间  订单修改尾款时间
                    if($sign['sign_type'] == 4){
                        $other_sign = $this->db->getRow("select *  from hqsen_user_dajian_order_other_sign where sign_type = 4 and id=" . $sign['sign_other_sign_id']);
                        if($other_sign){
                            $dajian_order['use_date'] = $other_sign['order_time'];
                            $this->db->update('hqsen_dajian_order', $dajian_order, ' id = ' . $sign['dajian_order_id']);// 签单搭建 财务状态
                        }
                    }
                    $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $sign['user_dajian_order_id']);
                }
                // 待修改
                if($sign_status == 5 and $sign){
                    $user_order['erxiao_order_status'] = 5;// 搭建二销修改成 待处理
                    $this->db->update('hqsen_user_dajian_order', $user_order, ' id = ' . $sign['user_dajian_order_id']);
                }
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


}