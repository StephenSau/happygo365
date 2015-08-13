<?php

defined('haipinlegou') or exit('Access Invalid!');
class p_mansongModel{

    const TABLE_NAME = 'p_mansong';
    const PK = 'mansong_id';

	
	private function getCondition($condition){
		$condition_str = '';
        if (!empty($condition['mansong_id'])){
            $condition_str .= " and mansong_id = '".$condition['mansong_id'] ."'";
        }
        if(!empty($condition['mansong_name'])) {
            $condition_str .= " and mansong_name like '%".$condition['mansong_name'] ."%'";
        }
        if(!empty($condition['store_name'])) {
            $condition_str .= " and store_name like '%".$condition['store_name'] ."%'";
        }
        if (!empty($condition['store_id'])){
        	if(is_array($condition['store_id'])){
	       		$condition_str .= " and store_id in (".implode(',',$condition['store_id']).")";
	        }else{
	            $condition_str .= " and store_id = '".$condition['store_id'] ."'";
	        }
		}
        if (!empty($condition['state'])){
            $condition_str .= " and state = '".$condition['state'] ."'";
        }
        if(!empty($condition['greater_than_start_time'])) {
            $condition_str .= " and start_time < '".$condition['greater_than_start_time']."'";
        }
        if(!empty($condition['less_than_end_time'])) {
            $condition_str .= " and end_time > '".$condition['less_than_end_time']."'";
        }
        if(!empty($condition['greater_than_end_time'])) {
            $condition_str .= " and end_time < '".$condition['greater_than_end_time']."'";
        }
		return $condition_str;
	}

	
	public function getList($condition,$page='',$field='*'){

        $param = array();
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' state asc,'.self::PK.' desc';
        $param['limit'] = $condition['limit'];
        $param['field'] = $field;
        return Db::select($param,$page);
	}

    
    public function getLast($condition,$state) {

        $param = array();
        $param += $condition;  
        $param['order'] = 'end_time desc';
        $param['limit'] = 1;
        $param['state'] = $state;
        $list = $this->getList($param);
        return $list[0];
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
