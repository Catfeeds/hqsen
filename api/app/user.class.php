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
        if($phone and $code){
            session_id("sen-" . $phone);
            session_start();
            if(isset($_SESSION['code']) and $_SESSION['code'] == $code){
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

                $user['last_login_time'] = time();
                $user['session_id'] = md5($user['id'] . $user['last_login_time']);
                // todo 注释使用最后登陆时间
                $user['session_id'] = md5($user['id'] . $user['create_time']);
                $this->db->update('hqsen_user', $user, ' id = ' . $user['id']);
//                session_id($user['session_id']);
//                session_start();
                $login_user = array(
                    'access_token' => session_id(),
                    'alipay_account' => $user['alipay_account'],
                    'bank_account' => $user['bank_account'],
                    'nike_name' => $user['nike_name'],
                    'user_type' => $user['user_type']
                );
//                $_SESSION['user_info'] = $user;
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
        if($user_name and $password){
            $user = $this->db->getRow("select * from hqsen_user where user_name = '$user_name'");
            if(!$user or md5($password) != $user['password']){
                    $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
            }
            $user_data = $this->db->getRow("select * from hqsen_user_data where user_id = " . $user['id']);
            $user['last_login_time'] = time();
            $user['session_id'] = md5($user['id'] . $user['last_login_time']);
            // todo 注释使用最后登陆时间
            $user['session_id'] = md5($user['id'] . $user['create_time']);
            $this->db->update('hqsen_user', $user, ' id = ' . $user['id']);
//            session_id($user['session_id']);
//            session_start();
            $login_user = array(
                'access_token' => $user['session_id'],
                'alipay_account' => $user['alipay_account'],
                'bank_account' => $user['bank_account'],
                'nike_name' => $user['nike_name'],
                'hotel_name' => $user_data['hotel_name'],
                'hotel_id' => $user_data['hotel_id'],
                'user_type' => $user['user_type']
            );
//            $_SESSION['user_info'] = $user;
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    //支付宝绑定
    public function alipayBind(){
        $this->loginInit();
        $alipay = $this->postString('alipay');
        $bank_name = $this->postString('bank_name');
        $bank_user = $this->postString('bank_user');
        $bank_account = $this->postString('bank_account');
        if($alipay or $bank_name){
            $update_user['alipay_account'] = $alipay;
            $update_user['bank_name'] = $bank_name;
            $update_user['bank_user'] = $bank_user;
            $update_user['bank_account'] = $bank_account;
            $this->db->update('hqsen_user', $update_user, ' id = ' . $this->user['id']);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['user']['bind_empty'], $this->back_msg['user']['bind_empty']);
        }

    }

    // 云片发短信
    public function getPhoneCode(){
        $mobile = isset($_POST['mobile']) ? (string)$_POST['mobile'] : '';
        if($mobile){
            header("Content-Type:text/html;charset=utf-8");
            $apikey = "b181d90efe2155f5fe3d74b468c0a136"; //修改为您的apikey(https://www.yunpian.com)登陆官网后获取
            if(preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
                $rand_text = rand(1000,9999);
                $rand_text = 1000;
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
//                $json_data = curl_exec($ch);
//                $array = json_decode($json_data,true);
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

    public function mainList(){
        $list_type = $this->postInt('list_type');
        if($list_type == 1){

        } else {
            $area_sh_id = $this->postInt('area_sh_id');
            $hotel = $this->db->getRows("select * from hqsen_hotel as hh left join hqsen_hotel_data as hhd on hh.id = hhd.id where hh.area_sh_id = $area_sh_id and hh.is_data = 1");
            $data = [];
            foreach ($hotel as $one_hotel){
                $item['hotel_name'] = (string)$one_hotel['hotel_name'];
                $item['hotel_low'] = (string)$one_hotel['hotel_low'];
                $item['hotel_high'] = (string)$one_hotel['hotel_high'];
                $item['hotel_max_desk'] = (string)$one_hotel['hotel_max_desk'];
                $item['area_sh_name'] = (string)$this->get_sh_area($one_hotel['area_sh_id']);
                $item['hotel_type'] = (string)$one_hotel['hotel_type'];
                $item['hotel_phone'] = (string)$one_hotel['hotel_phone'];
                $item['hotel_image'] = (string)$one_hotel['hotel_image'];
                $data[] = $item;
            }
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
        }

    }

    public function getShArea()
    {
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $this->get_sh_area());
    }

}