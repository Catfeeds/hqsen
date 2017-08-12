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
            $user = $this->db->getRow("select * from hqsen_user where  user_status = 1 and user_name = '$user_name'");

            if($user and $user['password'] == md5($password)){
                if(!in_array($user['user_type'], [2, 13, 14, 15, 16])){
                    $this->appDie($this->back_code['user']['login_err'], $this->back_msg['user']['login_err']);
                }
                $user['last_login_time'] = time();
                $user['session_id'] = md5($user['id'] . $user['last_login_time']);
                // todo 注释使用最后登陆时间
                $user['session_id'] = md5($user['id'] . $user['create_time']);
                $this->db->update('hqsen_user', $user, ' id = ' . $user['id']);
//                session_start();
                $login_user = array(
                    'access_token' => $user['session_id'],
                    'user_type' => $user['user_type'],
                );
//                $_SESSION['user_info'] = $user;
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
        if($this->user['user_name'] == 'monkey' or  $this->user['user_name'] == 'admin'){
            $config_data['user_security'] = $this->user_security('monkey');
        } else if($this->user['user_type'] == 15){
            $config_data['user_security'] = $this->user_security('admin');
        } else if($this->user['user_type'] == 13){
            $config_data['user_security'] = $this->user_security('finance');
        } else if($this->user['user_type'] == 14){
            $config_data['user_security'] = $this->user_security('service');
        } else if($this->user['user_type'] == 16){
            $config_data['user_security'] = $this->user_security('editor');
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
        $sql_limit = " order by id desc limit $offset , $limit";
        $feedback = $this->db->getRows("select *   from hqsen_feedback  where del_flag = 1 " . $sql_limit);
        $data = [];
        foreach ($feedback as $one_feedback){
            if($one_feedback){
                $one_feedback_item = array(
                    'user_name' => $one_feedback['user_name'],
                    'content' => $one_feedback['content'],
                    'create_time' => date('Y-m-d H:i:s' , $one_feedback['create_time']),
                    'phone' => $one_feedback['phone'],
                    'id' => $one_feedback['id'],
                );
                $data['list'][] = $one_feedback_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_feedback', 'del_flag = 1');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function uploadPic(){
        $url = '';
        if(isset($_FILES['file']["name"])){
            move_uploaded_file($_FILES['file']['tmp_name'], API_PATH . "/upload/" . time() . $_FILES["file"]["name"]);
//            $url = 'http://dev.51isen.com' . "/api/upload/" . time() . $_FILES["file"]["name"];
        }


        // Create a cURL handle
        $ch = curl_init('http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com');
//        $policy = '{"expiration": "2120-01-01T12:00:00.000Z","conditions":[{"bucket": "sendevimg" },["content-length-range", 0, 104857600]]}';
//        $policy = base64_encode($policy);
        $postData = array(
            'OSSAccessKeyId'=> 'LTAIoOF3QnYG9bZm',
            'policy'=> 'eyJleHBpcmF0aW9uIjogIjIxMjAtMDEtMDFUMTI6MDA6MDAuMDAwWiIsImNvbmRpdGlvbnMiOlt7ImJ1Y2tldCI6ICJzZW5kZXZpbWciIH0sWyJjb250ZW50LWxlbmd0aC1yYW5nZSIsIDAsIDEwNDg1NzYwMF1dfQ==',
            'signature'=> 'xg7inAutAlCNgAbWEDJ1HUgBoys=',
            'key'=> 'upload/user_web/${filename}',
            'file'=> curl_file_create(API_PATH . "/upload/" . time() . $_FILES["file"]["name"],$_FILES["file"]["type"],time() . $_FILES["file"]["name"]),
        );
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute the handle
        $r = curl_exec($ch);
        $url = 'http://sendevimg.oss-cn-zhangjiakou.aliyuncs.com/upload/user_web/' . time() . $_FILES["file"]["name"];
        $data['url'] = $url;
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }



}