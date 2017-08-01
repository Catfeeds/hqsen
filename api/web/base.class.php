<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2016/6/16 0020
 * Time: 10:16
 * File Using:市场部报表接口
 */

namespace api\web;
use avf\lib\mysql;

class base{
    public $message;
    public $user;
    public function __construct(){
        $this-> db = mysql::getInstance();
        ini_set('session.gc_maxlifetime', 3600 * 12); //设置时间
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
                'login_err' => '账号或密码错误',
                'create_user_exist' => '创建账号已存在',
            ),
            'order' => array(
            ),
        );
        $this-> back_code = array(
            'sys' => array(
                'success' => '200',
                'fail' => '999',
                'token_empty' => '998',
                'token_fail' => '997',
                'value_empty' => '996',
                'mysql_err' => '995',
            ),
            'user' => array(
                'login_err' => '994',
                'create_user_exist' => '994',
            ),
            'order' => array(
            ),
        );

    }


    // 登录初始化 判断是否登录
    public function loginInit(){
        $session_id = isset($_REQUEST['access_token']) ? $_REQUEST['access_token'] : '';
        if($session_id){
            $user = $this->db->getRow("select * from hqsen_user where session_id = '$session_id'");
            if($user){
                $this->user = $user;
            } else {
                $this->appDie($this->back_code['sys']['token_fail'], $this->back_msg['sys']['token_fail']);
            };
        } else {
            $this->appDie($this->back_code['sys']['token_empty'], $this->back_msg['sys']['token_empty']);
        }

    }

    public function appDie($back_code = 200, $back_msg = 'success', $back_data = []){
        if (!API_DEBUG) ob_clean();
        header('Access-Control-Allow-Origin:*');
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


    public function user_security($user_type){
        $security = array(
            'monkey' => array(
                array(
                    'key' => 'order_info',
                    'label'=>'客资/搭建信息',
                    'child' => array(
                        array('key'=>'order_info_kezi_list', 'label'=>'客资列表', 'parent_key'=>'order_info'),
                        array('key'=>'order_info_dajian_list', 'label'=>'搭建列表', 'parent_key'=>'order_info'),
                    ),
                ),
                array(
                    'key' => 'hotel_info',
                    'label'=>'基础信息设定',
                    'child' => array(
                        array('key'=>'hotel_info_hotel_list', 'label'=>'酒店信息', 'parent_key'=>'hotel_info'),
                        array('key'=>'hotel_info_area_list', 'label'=>'区域信息', 'parent_key'=>'hotel_info'),
                    ),
                ),
                array(
                    'key' => 'account_info',
                    'label'=>'帐号管理',
                    'child' => array(
                        array('key'=>'account_info_register_list', 'label'=>'注册账号', 'parent_key'=>'account_info'),
                        array('key'=>'account_info_hotel_list', 'label'=>'酒店账号', 'parent_key'=>'account_info'),
                        array('key'=>'account_info_inner_list', 'label'=>'内部账号', 'parent_key'=>'account_info'),
                        array('key'=>'account_info_password_back', 'label'=>'超管重置密码', 'parent_key'=>'account_info'),
                    ),
                ),
                array(
                    'key' => 'finance_info',
                    'label'=>'财务审批',
                    'child' => array(
                        array('key'=>'finance_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'finance_info'),
                        array('key'=>'finance_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'finance_info'),
                    ),
                ),
                array(
                    'key' => 'manager_info',
                    'label'=>'总经理审批',
                    'child' => array(
                        array('key'=>'manager_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'manager_info'),
                        array('key'=>'manager_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'manager_info'),
                    ),
                ),
                array(
                    'key' => 'remittance_info',
                    'label'=>'财务打款',
                    'child' => array(
                        array('key'=>'remittance_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'remittance_info'),
                        array('key'=>'remittance_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'remittance_info'),
                        array('key'=>'remittance_info_remittance_ratio', 'label'=>'打款系数', 'parent_key'=>'remittance_info'),
                    ),
                ),
                array(
                    'key' => 'feedback_info',
                    'label'=>'意见反馈'
                ),
            ),
            'admin' => array(
                array(
                    'key' => 'order_info',
                    'label'=>'客资/搭建信息',
                    'child' => array(
                        array('key'=>'order_info_kezi_list', 'label'=>'客资列表', 'parent_key'=>'order_info'),
                        array('key'=>'order_info_dajian_list', 'label'=>'搭建列表', 'parent_key'=>'order_info'),
                    ),
                ),
                array(
                    'key' => 'hotel_info',
                    'label'=>'基础信息设定',
                    'child' => array(
                        array('key'=>'hotel_info_hotel_list', 'label'=>'酒店信息', 'parent_key'=>'hotel_info'),
                        array('key'=>'hotel_info_area_list', 'label'=>'区域信息', 'parent_key'=>'hotel_info'),
                    ),
                ),
                array(
                    'key' => 'account_info',
                    'label'=>'帐号管理',
                    'child' => array(
                        array('key'=>'account_info_register_list', 'label'=>'注册账号', 'parent_key'=>'account_info'),
                        array('key'=>'account_info_hotel_list', 'label'=>'酒店账号', 'parent_key'=>'account_info'),
                        array('key'=>'account_info_inner_list', 'label'=>'内部账号', 'parent_key'=>'account_info'),
                    ),
                ),
                array(
                    'key' => 'finance_info',
                    'label'=>'财务审批',
                    'child' => array(
                        array('key'=>'finance_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'finance_info'),
                        array('key'=>'finance_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'finance_info'),
                    ),
                ),
                array(
                    'key' => 'remittance_info',
                    'label'=>'财务打款',
                    'child' => array(
                        array('key'=>'remittance_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'remittance_info'),
                        array('key'=>'remittance_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'remittance_info'),
                        array('key'=>'remittance_info_remittance_ratio', 'label'=>'打款系数', 'parent_key'=>'remittance_info'),
                    ),
                ),
                array(
                    'key' => 'feedback_info',
                    'label'=>'意见反馈'
                ),
            ),
            'finance' => array(
                array(
                    'key' => 'order_info',
                    'label'=>'客资/搭建信息',
                    'child' => array(
                        array('key'=>'order_info_kezi_list', 'label'=>'客资列表', 'parent_key'=>'order_info'),
                        array('key'=>'order_info_dajian_list', 'label'=>'搭建列表', 'parent_key'=>'order_info'),
                    ),
                ),
                array(
                    'key' => 'finance_info',
                    'label'=>'财务审批',
                    'child' => array(
                        array('key'=>'finance_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'finance_info'),
                        array('key'=>'finance_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'finance_info'),
                    ),
                ),
                array(
                    'key' => 'remittance_info',
                    'label'=>'财务打款',
                    'child' => array(
                        array('key'=>'remittance_info_kezi_contract', 'label'=>'客资合同', 'parent_key'=>'remittance_info'),
                        array('key'=>'remittance_info_dajian_contract', 'label'=>'搭建合同', 'parent_key'=>'remittance_info'),
                        array('key'=>'remittance_info_remittance_ratio', 'label'=>'打款系数', 'parent_key'=>'remittance_info'),
                    ),
                ),
            ),
            'service' => array(
//                array(
//                    'key' => 'order_info',
//                    'label'=>'客资/搭建信息',
//                    'child' => array(
//                        array('key'=>'order_info_kezi_list', 'label'=>'客资列表', 'parent_key'=>'order_info'),
//                        array('key'=>'order_info_dajian_list', 'label'=>'搭建列表', 'parent_key'=>'order_info'),
//                    ),
//                ),
                array(
                    'key' => 'feedback_info',
                    'label'=>'意见反馈'
                ),
            ),
            'editor' => array(
                array(
                    'key' => 'hotel_info',
                    'label'=>'基础信息设定',
                    'child' => array(
                        array('key'=>'hotel_info_hotel_list', 'label'=>'酒店信息', 'parent_key'=>'hotel_info'),
                        array('key'=>'hotel_info_area_list', 'label'=>'区域信息', 'parent_key'=>'hotel_info'),
                    ),
                ),
            ),



        );
        return $security[$user_type];
    }

    public function user_right(){
        $right = array(
            'user' => ['login'=>'用户登录'],
            'kezi' => ['keziList' => '客资列表', 'keziDetail'=>'客资详情'],
            'dajian' => ['dajianList' => '搭建列表', 'dajianDetail' => '搭建详情'],
            'hotel' => ['hotelList'=>'酒店列表', 'hotelCreate'=>'新增', 'hotelEdit'=>'编辑', 'hotelDelete'=>'删除', ],
            'area' => ['areaList'=>'地域列表', 'arealCreate'=>'新增', 'arealEdit'=>'编辑', 'arealDelete'=>'删除', ],
            'account' => ['registerAccountList' => '注册账号列表'],

        );
    }

    public function order_type(){
        return array(
            array('value'=>'1', 'label'=>'婚宴'),
            array('value'=>'2', 'label'=>'会务'),
            array('value'=>'3', 'label'=>'生日宴 团宴 宝宝宴'),
        );
    }

    public function hotel_level(){
        return array(
            array('value'=>'A', 'label'=>'A'),
            array('value'=>'B', 'label'=>'B'),
            array('value'=>'C', 'label'=>'C'),
        );
    }

    public function inner_type(){
        return array(
            array('value'=>'11', 'label'=>'首销'),
            array('value'=>'12', 'label'=>'二销'),
            array('value'=>'13', 'label'=>'财务'),
            array('value'=>'14', 'label'=>'客服'),
            array('value'=>'15', 'label'=>'管理员'),
            array('value'=>'16', 'label'=>'编辑'),
        );
    }

    // 默认返回所有区域  有ID 返回对应的所有区域名字 映射表和mysql hqsen_area_sh 对应
    public function get_sh_area($sh_area_id = 0){
        $sh_area = array(
            '1'=>'浦东新区',
            '2'=>'卢湾区',
            '3'=>'黄浦区',
            '4'=>'虹口区',
            '5'=>'杨浦区',
            '6'=>'闸北区',
            '7'=>'普陀区',
            '8'=>'长宁区',
            '9'=>'静安区',
            '10'=>'徐汇区',
//            '11'=>'南汇区',
            '12'=>'闵行区',
            '13'=>'奉贤区',
            '14'=>'金山区',
            '15'=>'松江区',
            '16'=>'青浦区',
            '17'=>'嘉定区',
            '18'=>'宝山区',
            '19'=>'崇明县',
        );
        $return = isset($sh_area[$sh_area_id]) ?  $sh_area[$sh_area_id] : $sh_area;
        return $return;
    }


}