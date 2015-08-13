<?php

defined('haipinlegou') or exit('Access Invalid!');

class store_gradeModel{
	
	public function getGradeList($condition = array()){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'store_grade';
		$param['where'] = $condition_str;
		$param['order'] = $condition['order']?$condition['order']:'sg_id';
		$result = Db::select($param);
		return $result;
	}
	
	private function _condition($condition){
		$condition_str = '';
		
		if ($condition['like_sg_name'] != ''){
			$condition_str .= " and sg_name like '%". $condition['like_sg_name'] ."%'";
		}
		if ($condition['no_sg_id'] != ''){
			$condition_str .= " and sg_id != '". intval($condition['no_sg_id']) ."'";
		}
		if ($condition['sg_name'] != ''){
			$condition_str .= " and sg_name = '". $condition['sg_name'] ."'";
		}
		if ($condition['sg_id'] != ''){
			$condition_str .= " and store_grade.sg_id = '". $condition['sg_id'] ."'";
		}
		
		if(isset($condition['store_id'])) {
			$condition_str .= " and store.store_id = '{$condition['store_id']}' ";
		}
		if (isset($condition['sg_confirm'])){
			$condition_str .= " and sg_confirm = '{$condition['sg_confirm']}'";	
		}
		if (isset($condition['sg_sort'])){
			if ($condition['sg_sort'] == ''){
				$condition_str .= " and sg_sort = '' ";
			}else {
				$condition_str .= " and sg_sort = '{$condition['sg_sort']}'";
			}
		}
		return $condition_str;
	}
	
	
	public function getOneGrade($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'store_grade';
			$param['field'] = 'sg_id';
			$param['value'] = intval($id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('store_grade',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function update($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " sg_id = '{$param['sg_id']}'";
			$result = Db::update('store_grade',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function del($id){
		if (intval($id) > 0){
			$where = " sg_id = '". intval($id) ."'";
			$result = Db::delete('store_grade',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	
	public function getGradeShopList($condition,$page=''){
		$condition_str = $this->_condition($condition);
		$param = array(
					'table'=>'store_grade,store',
					'field'=>'store_grade.*,store.*',
					'where'=>$condition_str,
					'join_type'=>'left join',
					'join_on'=>array(
						'store_grade.sg_id = store.grade_id',
					)
				);		
		$result = Db::select($param,$page);
		return $result;
	}
}