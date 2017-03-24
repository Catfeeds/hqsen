<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:app 接口用户类api user
 */


namespace api\app;
//include('base.class.php');

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
            $this->appDie();
        } else {
            $this->appDie($this->back_code['user']['bind_empty'], $this->back_msg['user']['bind_empty']);
        }

    }

    // 云片发短信
    public function getPhoneCode(){
        $mobile = isset($_POST['mobile']) ? (string)$_POST['mobile'] : '';
        header("Content-Type:text/html;charset=utf-8");
        $apikey = "6974b9344296ea1410a285905c766960"; //修改为您的apikey(https://www.yunpian.com)登陆官网后获取
        $mobile = "15068159661"; //请用自己的手机号代替
        $data['mobile'] = (string)$mobile;
        $data['code'] = (string)2312;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
        if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
            $rand_text = rand(1000,9999);
            $text="验证码：" . $rand_text;
            $ch = curl_init();

            /* 设置验证方式 */

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

            /* 设置返回结果为流 */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /* 设置超时时间*/
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            /* 设置通信方式 */
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // 发送短信
            $send_data = array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
//            echo '<pre>';print_r($send_data);
            curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send_data));
            $json_data = curl_exec($ch);
            $array = json_decode($json_data,true);
//            echo '<pre>';print_r($array);
            if(isset($array['msg']) and  $array['msg'] == '发送成功'){
                $data['data']['code'] = $rand_text;
            } else {
                $data['data']['code'] = 0;
                $data['alert']['msg'] = $array['detail'];
            }
            // 发送模板短信
            curl_close($ch);
        } else {
            $data['phone_code'] = (string)'';
            $data['phone'] = (string)$mobile;
        }


    }


}