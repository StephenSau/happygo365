<?php

defined('haipinlegou') or exit('Access Invalid!');

class navigationModel {
	
	public function getNavigationList($condition,$page){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'navigation';
		$param['where'] = $condition_str;
		$param['order']	= $condition['order'] ? $condition['order'] : 'nav_id';
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	private function _condition($condition){
		$condition_str = '';
		
		if ($condition['like_nav_title'] != ''){
			$condition_str .= " and nav_title like '%". $condition['like_nav_title'] ."%'";
		}
		if ($condition['nav_location'] != ''){
			$condition_str .= " and nav_location = '". $condition['nav_location'] ."'";
		}		
		
		return $condition_str;
	}
	
	public function getOneNavigation($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'navigation';
			$param['field'] = 'nav_id';
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
			$result = Db::insert('navigation',$tmp);
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
			$where = " nav_id = '". $param['nav_id'] ."'";
			$result = Db::update('navigation',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function del($id){
		if (intval($id) > 0){
			$where = " nav_id = '". intval($id) ."'";
			$result = Db::delete('navigation',$where);
			return $result;
		}else {
			return false;
		}
	}	
}