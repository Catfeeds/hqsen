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

    }


}