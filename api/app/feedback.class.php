<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:order 接口订单类api user
 */


namespace api\app;

class feedback extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit();
    }

    //
    public function create(){
        $feedback_main = $this->postString('feedback_main');
        $feedback_phone = $this->postString('feedback_phone');
        if($feedback_main){
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }



}