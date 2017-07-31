<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2017/3/20 0020
 * Time: 10:16
 * File Using:area  后台接口 区域相关类
 */


namespace api\web;

class area extends base {

    public function __construct()
    {
        parent::__construct();
        $this-> loginInit(); //  所有接口 登录态
    }

    // 区域列表
    public function areaList(){
        $page = $this->postInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql_limit = " limit $offset , $limit";
        $area = $this->db->getRows("select *   from hqsen_area  where del_flag = 1 order by id desc " . $sql_limit);
        $data = [];
        foreach ($area as $one_area){
            if($one_area){
                $area_sh_arr = explode(',', $one_area['area_list']);
                $area_list = '';
                foreach ($area_sh_arr as $one_area_sh){
                    if($one_area_sh){
                        $area_list .= ',' . $this->get_sh_area($one_area_sh);
                    }

                }
                $area_item = array(
                    'area_id' => $one_area['id'],
                    'area_name' => $one_area['area_name'],
                    'area_list' => trim($area_list,','),
                );
                $data['list'][] = $area_item;
            }
        }
        $data['count'] = $this->db->getCount('hqsen_area', 'del_flag = 1');
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    // 创建区域
    public function areaCreate(){
        $area_name = $this->postString('area_name');
        $area_list = $this->postString('area_list');
        if($area_name and $area_list ){
            $sql_order['area_name'] = $area_name;
            $sql_order['area_list'] = $area_list;
            $sql_order['create_time'] = time();
            $sql_order['del_flag'] = 1;
            $sql_order['id'] = $this->db->insert('hqsen_area', $sql_order);

            // 更新区域信息
            $area_sh_arr = explode(',', $sql_order['area_list']);
            foreach ($area_sh_arr as $one_area_sh){
                $sql_area_sh['link_area_id'] = $sql_order['id'];
                $this->db->update('hqsen_area_sh', $sql_area_sh, ' id = ' . $one_area_sh);
                $this-> userHotelAreaLink($one_area_sh, $sql_order['id']);
            }
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }

    }

    // 编辑区域
    public function areaEdit(){
        $area_id = $this->postString('id');
        $area_name = $this->postString('area_name');
        $area_list = $this->postString('area_list');
        if($area_id and ($area_name or $area_list)){
            $sql_order = [];
            if($area_name){
                $sql_order['area_name'] = $area_name;
            }
            if($area_list){
                $sql_order['area_list'] = $area_list;
                // 更新区域信息
                $area_sh_arr = explode(',', $sql_order['area_list']);
                $sql_clean_area['link_area_id'] = 0;
                $this->db->update('hqsen_area_sh', $sql_clean_area, ' link_area_id = ' . $area_id);
                foreach ($area_sh_arr as $one_area_sh){
                    $sql_area_sh['link_area_id'] = $area_id;
                    $this->db->update('hqsen_area_sh', $sql_area_sh, ' id = ' . $one_area_sh);
                    $this-> userHotelAreaLink($one_area_sh, $sql_order['id']);
                }
            }

            $this->db->update('hqsen_area', $sql_order, ' id = ' . $area_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 　删除区域
    public function areaDelete(){
        $area_id = $this->postString('id');
        if($area_id){
            $sql_order['del_flag'] = 2;
            $this->db->update('hqsen_area', $sql_order, ' id = ' . $area_id);
            $this->appDie();
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 区域详情
    public function areaDetail(){
        $area_id = $this->postString('id');
        if($area_id){
            $area = $this->db->getRow("select * from hqsen_area  where id =  " . $area_id);
            $area_item = array(
                'area_id' => $area['id'],
                'area_name' => $area['area_name'],
                'area_list' => $area['area_list'],
            );
            $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $area_item);
        } else {
            $this->appDie($this->back_code['sys']['value_empty'], $this->back_msg['sys']['value_empty']);
        }
    }

    // 上海所有区
    public function areaSH(){
        $area_id = $this->postInt('id');
        $area = $this->db->getRows("select has.* , ha.`del_flag` as is_used from hqsen_area_sh as has left join hqsen_area as ha on has.`link_area_id`=ha.id");
        $data = [];
        foreach ($area as $one_area){
            if($one_area){
                $area_item = array(
                    'label' => $one_area['area_label'],
                    'value' => $one_area['id'],
                );
                if(intval($one_area['is_used']) != 1 or ($area_id and $area_id == $one_area['link_area_id'])){
                    $area_item['disabled'] = false;
                } else {
                    $area_item['disabled'] = true;
                }
                if($area_item['label'] == '南汇区'){
                    continue;
                }
                $data['area_sh'][] = $area_item;
            }
        }
        $this->appDie($this->back_code['sys']['success'], $this->back_msg['sys']['success'], $data);
    }

    public function userHotelAreaLink($area_sh_id, $area_id){
        $sql_area_sh['area_id'] = $area_id;
        $this->db->update('hqsen_hotel', $sql_area_sh, ' area_sh_id = ' . $area_sh_id);
        $this->db->update('hqsen_user_data', $sql_area_sh, ' area_sh_id = ' . $area_sh_id);
    }
}