<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2016/6/16 0020
 * Time: 10:16
 * File Using:市场部报表接口
 */

namespace api\app;
use avf\lib\mysql;

class base{
    public $message;
    public $user;
    public function __construct(){
        $this-> db = mysql::getInstance();
        $this-> back_msg = array(
            'sys' => array(
                'success' => '请求成功',
                'fail' => '请求失败',
                'token_empty' => 'token不能为空',
                'token_fail' => '登录失效请重新登录',
                'value_empty' => '数据缺失',
                'mysql_err' => '数据操作失败',
            ),
            'user' => array(
                'bind_empty' => '绑定不能为空',
                'phone_illegal' => '手机号非法',
                'phone_code_err' => '手机验证码错误',
            ),
            'order' => array(
                'phone_type_exist' => '婚宴中已存在该手机信息，无法成功录入',
                'kezi_order_fail' => '生成客资订单失败',
                'dajian_order_fail' => '生成搭建信息失败',
                'dajian_order_right' => '没有权限生成搭建信息',
            ),
        );
        $this-> back_code = array(
            'sys' => array(
                'success' => '1000',
                'fail' => '999',
                'token_empty' => '998',
                'token_fail' => '997',
                'value_empty' => '994',
                'mysql_err' => '991',
            ),
            'user' => array(
                'bind_empty' => '996',
                'phone_illegal' => '993',
                'phone_code_err' => '992',
            ),
            'order' => array(
                'phone_type_exist' => '995',
                'kezi_order_fail' => '990',
                'dajian_order_fail' => '989',
                'dajian_order_right' => '988',
            ),
        );

    }


    // 登录初始化 判断是否登录
    public function loginInit(){
        $session_id = isset($_REQUEST['access_token']) ? $_REQUEST['access_token'] : '';
        if($session_id){
            session_id($session_id);
            session_start();
            $user = $this->db->getRow("select * from hqsen_user where session_id = '$session_id'");
            if(isset($_SESSION['user_info'])){
                $this->user = $_SESSION['user_info'];
            } else if($user){
                $this->user = $user;
            } else {
                $this->appDie($this->back_code['sys']['token_fail'], $this->back_msg['sys']['token_fail']);
            };
        } else {
            $this->appDie($this->back_code['sys']['token_empty'], $this->back_msg['sys']['token_empty']);
        }

    }

    public function appDie($back_code = 1000, $back_msg = '请求成功', $back_data = []){
        if (!API_DEBUG) ob_clean();
        $data['status'] = (int)$back_code;
        $data['data'] = (array)$back_data;
        $data['message'] = (string)$back_msg;
        die(json_encode($data));
    }

    public function getInt($get_key, $default = 0){
        return isset($_GET[$get_key]) ? (int)$_GET[$get_key] : $default;
    }
    public function getString($get_key, $default = ''){
        return isset($_GET[$get_key]) ? (string)$_GET[$get_key] : (string)$default;
    }
    public function postInt($post_key, $default = 0){
        return isset($_POST[$post_key]) ? (int)$_POST[$post_key] : $default;
    }
    public function postString($post_key, $default = ''){
        return isset($_POST[$post_key]) ? (string)$_POST[$post_key] : (string)$default;
    }


}