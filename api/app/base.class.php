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
            ),
            'user' => array(
                'bind_empty' => '绑定不能为空'
            ),
        );
        $this-> back_code = array(
            'sys' => array(
                'success' => '200',
                'fail' => '999',
                'token_empty' => '998',
                'token_fail' => '997',
            ),
            'user' => array(
                'bind_empty' => '996'
            ),
        );
    }


    // 登录初始化 判断是否登录
    public function loginInit(){
        $session_id = isset($_REQUEST['access_token']) ? $_REQUEST['access_token'] : '';
        if($session_id){
            session_id($session_id);
            session_start();
            if(isset($_SESSION['user_info'])){
                $this->user = $_SESSION['user_info'];
            } else {
                $this->appDie($this->back_code['sys']['token_fail'], $this->back_msg['sys']['token_fail']);
            };
        } else {
            $this->appDie($this->back_code['sys']['token_empty'], $this->back_msg['sys']['token_empty']);
        }

    }

    public function appDie($back_code = 200, $back_msg = 'success', $back_data = []){
        $data['status'] = (int)$back_code;
        $data['data'] = (array)$back_data;
        $data['message'] = (string)$back_msg;
        die(json_encode($data));
    }


}