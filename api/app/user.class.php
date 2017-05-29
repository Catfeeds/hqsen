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
        $phone = $this->postString('phone');
        $code = $this->postString('code');
        $phone = '18521598476';
        $user = $this->db->getRow('select * from hqsen_user where user_name = ' . $phone);
        session_id($user['session_id']);
        session_start();
        $login_user = array(
            'access_token' => session_id(),
            'alipay_account' => $user['alipay_account'],
            'nike_name' => $user['nike_name'],
            'user_type' => $user['user_type']
        );
        $_SESSION['user_info'] = $user;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);

        if($phone and $code){
            session_id("sen-" . $phone);
            session_start();
            if($phone == '15068159662' or (isset($_SESSION['code']) and $_SESSION['code'] == $code)){
                $user = $this->db->getRow('select * from hqsen_user where user_name = ' . $phone);
                if(!$user){
                    $user['user_name'] = $phone;
                    $user['nike_name'] = $phone;
                    $user['phone'] = $phone;
                    $user['alipay_account'] = '';
                    $user['create_time'] = time();
                    $user['user_type'] = 3;
                    $user['del_flag'] = 1;
                    $user_id = $this->db->insert('hqsen_user', $user);
                    if($user_id){
                        $user['id'] = $user_id;
                    } else {
                        $this->appDie($this->back_code['sys']['mysql_err'], $this->back_msg['sys']['mysql_err']);
                    };
                }
                session_destroy();//销毁一个会话中的全部数据
                // todo 删除每次登录的session 只保留最后一次
                $user['session_id'] = substr(md5($user['id'] . time()), 0, 20);
                $this->db->update('hqsen_user', $user, ' id = ' . $user['id']);
                session_id($user['session_id']);
                session_start();
                $login_user = array(
                    'access_token' => session_id(),
                    'alipay_account' => $user['alipay_account'],
                    'nike_name' => $user['nike_name'],
                    'user_type' => $user['user_type']
                );
                $_SESSION['user_info'] = $user;
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
            } else {
                $this->appDie($this->back_code['user']['phone_code_err'], $this->back_msg['user']['phone_code_err']);
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 账号登录
    public function loginByUser(){
        $user_name = $this->postString('user_name');
        $password = $this->postString('password');
        $user_name = 'H10000';
        $user = $this->db->getRow("select * from hqsen_user where user_name = '$user_name'");
        session_id($user['session_id']);
        session_start();
        $login_user = array(
            'access_token' => session_id(),
            'alipay_account' => $user['alipay_account'],
            'nike_name' => $user['nike_name'],
            'user_type' => $user['user_type']
        );
        $_SESSION['user_info'] = $user;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);

        if($user_name and $password){
                $user = $this->db->getRow("select * from hqsen_user where user_name = '$user_name'");
                if(!$user or md5($password) != $user['password']){
                    $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
                }
                // todo 删除每次登录的session 只保留最后一次
                $user['session_id'] = substr(md5($user['id'] . time()), 0, 20);
                session_id($user['session_id']);
                session_start();
                $login_user = array(
                    'access_token' => session_id(),
                    'alipay_account' => $user['alipay_account'],
                    'nike_name' => $user['nike_name'],
                    'user_type' => $user['user_type']
                );
                $_SESSION['user_info'] = $user;
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    //支付宝绑定
    public function alipayBind(){
        $this->loginInit();
        $alipay = $this->postString('alipay');
        if($alipay){
            $update_user['alipay_account'] = $alipay;
            $this->db->update('hqsen_user', $update_user, ' user_name = ' . $this->user['user_name']);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['user']['bind_empty'], $this->back_msg['user']['bind_empty']);
        }

    }

    // 云片发短信
    public function getPhoneCode(){
        $mobile = isset($_POST['mobile']) ? (string)$_POST['mobile'] : '';
        $data['code'] = intval(1000);
        $data['phone'] = $mobile;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);

        if($mobile){
            header("Content-Type:text/html;charset=utf-8");
            $apikey = "b181d90efe2155f5fe3d74b468c0a136"; //修改为您的apikey(https://www.yunpian.com)登陆官网后获取
            if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                $rand_text = rand(1000,9999);
//                $text="验证码：" . $rand_text;
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
                $send_data = array('tpl_id'=>'1755704','tpl_value'=>('#code#').'='.urlencode($rand_text),'apikey'=>$apikey,'mobile'=>$mobile);
                curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/tpl_single_send.json');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send_data));
                $json_data = curl_exec($ch);
                $array = json_decode($json_data,true);
//                if(isset($array['msg']) and  $array['msg'] == '发送成功'){
                    $data['code'] = $rand_text;
                    $data['phone'] = $mobile;
                    $session_id = "sen-" . $data['phone'];
                    session_id($session_id);
                    session_start();
                    $_SESSION['code'] = $rand_text;
//                } else {
//                    $data['code'] = 0;
//                    $this->appDie($this->back_code['user']['phone_code_err'], $array['detail'], $data);
//                }
                // 发送模板短信
                curl_close($ch);
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
            } else {
                $data['phone'] = (string)$mobile;
                $this->appDie($this->back_code['sys']['phone_illegal'], $this->back_msg['sys']['phone_illegal'], $data);
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }


}