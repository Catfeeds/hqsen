<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:app 接口用户类api user
 */


namespace api\app;

class user extends base{
    // 用户登陆
    public function login(){
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $data = array(
            'access_token' => 2,
            'nike_name' => 'monkey',
            'user_type' => 2
        );
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    //支付宝绑定
    public function alipayBind(){
        $this->loginInit();
        $alipay = $_POST['alipay'];
        if($alipay){
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success']);
        } else {
            $this->appDie($this->back_code['user']['bind_empty'], $this->back_msg['user']['bind_empty']);
        }

    }


}