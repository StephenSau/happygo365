<?php

defined('haipinlegou') or exit('Access Invalid!');
class goods_groupModel{

    const TABLE_NAME = 'goods_group';
    const PK = 'group_id';

	
	private function getCondition($condition){
		$condition_str = '';
        if (!empty($condition['store_id'])){
            $condition_str .= " and store_id = '".$condition['store_id'] ."'";
        }
        if (!empty($condition['group_name'])){
            $condition_str .= " and group_name like '%".$condition['group_name'] ."%'";
        }
        if (!empty($condition['group_id'])){
            $condition_str .= " and group_id = '".$condition['group_id'] ."'";
        }
        if (!empty($condition['state'])){
            $condition_str .= " and state = '".$condition['state'] ."'";
        }
        if (!empty($condition['state_progress_and_close'])){
            $condition_str .= " and state in(3,5) ";
        }
        if (!empty($condition['recommended'])){
            $condition_str .= " and recommended = '".$condition['recommended'] ."'";
        }
        if (!empty($condition['goods_id'])){
            $condition_str .= " and goods_id = '".$condition['goods_id'] ."'";
        }
        if (!empty($condition['class_id'])){
            $condition_str .= " and class_id = '".$condition['class_id'] ."'";
        }
        if (!empty($condition['area_id'])){
            $condition_str .= " and area_id = '".$condition['area_id'] ."'";
        }
        if (!empty($condition['greater_than_groupbuy_price'])) {
            $condition_str .= " and groupbuy_price < '".$condition['greater_than_groupbuy_price']."'";
        }
        if (!empty($condition['less_than_groupbuy_price'])) {
            $condition_str .= " and groupbuy_price >= '".$condition['less_than_groupbuy_price']."'";
        }
        if (!empty($condition['greater_than_start_time'])) {
            $condition_str .= " and start_time <= '".$condition['greater_than_start_time']."'";
        }
        if (!empty($condition['less_than_end_time'])) {
            $condition_str .= " and end_time >= '".$condition['less_than_end_time']."'";
        }
        if (!empty($condition['in_progress'])) {
            $condition_str .= " and start_time <= '".$condition['in_progress']."' and end_time >= '".$condition['in_progress']."'";
        }
        if (!empty($condition['expire'])) {
            $condition_str .= " and end_time < '".$condition['expire']."'";
        }
        if (!empty($condition['soon'])) {
            $condition_str .= " and start_time > '".$condition['soon']."'";
        }
        if (!empty($condition['template_id'])){
            $condition_str .= " and template_id = '".$condition['template_id'] ."'";
        }
        if (!empty($condition['in_template_id'])){
            $condition_str .= " and template_id IN (". $condition['in_template_id'] .")";
        }
        if (!empty($condition['in_group_id'])){
            $condition_str .= " and group_id IN (". $condition['in_group_id'] .")";
        }
		return $condition_str;
	}

	
	public function getList($condition = array(),$page='',$field='*'){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' state asc,'.self::PK.' desc';
        $param['limit'] = $condition['limit'];
        $param['field'] = $field;
        return Db::select($param,$page);
	}

    
	public function getOne($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = self::TABLE_NAME;
			$param['field'] = self::PK;
			$param['value'] = intval($id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}

	
	public function isExist($condition='') {

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $list = Db::select($param);
        if(empty($list)) {
            return false;
        }
        else {
            return true;
        }
	}

	
	public function save($param){
	
		return Db::insert(self::TABLE_NAME,$param) ;
	
	}
	
	
	public function update($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update(self::TABLE_NAME,$update_array,$where) ;
    
    }
	
	
	public function drop($param){

		$where = $this->getCondition($param) ;
		return Db::delete(self::TABLE_NAME, $where) ;
	}
	
}
