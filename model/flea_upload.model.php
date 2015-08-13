<?php

defined('haipinlegou') or exit('Access Invalid!');

class flea_uploadModel{
	
	public function getUploadList($condition){
		
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'flea_upload';
		$param['where'] = $condition_str;
		$result = Db::select($param);
		return $result;
	}

	
	private function _condition($condition){
		$condition_str = '';

		if ($condition['upload_type'] != ''){
			$condition_str .= " and upload_type = '". $condition['upload_type'] ."'";
		}
		if ($condition['item_id'] != ''){
			$condition_str .= " and item_id = '". $condition['item_id'] ."'";
		}
		if ($condition['file_name'] != '') {
			$condition_str	.= " and file_name = '".$condition['file_name']."'";
		}
		if (isset($condition['upload_type_in'])){
			if ($condition['upload_type_in'] == ''){
				$condition_str .= " and upload_type in('')";
			}else{
				$condition_str .= " and upload_type in({$condition['upload_type_in']})";
			}
		}
		if (isset($condition['item_id_in'])){
			if ($condition['item_id_in'] == ''){
				$condition_str .= " and item_id in('')";
			}else{
				$condition_str .= " and item_id in({$condition['item_id_in']})";
			}
		}
		if (isset($condition['upload_id_in'])){
			if ($condition['upload_id_in'] == ''){
				$condition_str .= " and upload_id in('')";
			}else{
				$condition_str .= " and upload_id in({$condition['upload_id_in']})";
			}
		}
		if ($condition['store_id'] != ''){
			$condition_str .= " and store_id = '". $condition['store_id'] ."'";
		}
		if ($condition['upload_time_lt'] != ''){
			$condition_str .= " and upload_time < '". $condition['upload_time_lt'] ."'";
		}
		return $condition_str;
	}

	
	public function getOneUpload($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'flea_upload';
			$param['field'] = 'upload_id';
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
			$result = Db::insert('flea_upload',$param);
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
			$where = " upload_id = '". $param['upload_id'] ."'";
			$result = Db::update('flea_upload',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function updatebywhere($param,$conditionarr){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$condition_str = $this->_condition($conditionarr);
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::update('flea_upload',$tmp,$condition_str);
			return $result;
		}else {
			return false;
		}
	}
	
	public function del($id){
		if (intval($id) > 0){
			$where = " upload_id = '". intval($id) ."'";
			$result = Db::delete('flea_upload',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function dropUploadById($id){
		if(empty($id)) {
			return false;
		}
		$condition_str = ' 1=1 ';
		if (is_array($id) && count($id)>0){
			$idStr = implode(',',$id);
			$condition_str .= " and upload_id in({$idStr}) ";
		}else {
			$condition_str .= " and upload_id = {$id} ";
		}
		$result = Db::delete('flea_upload',$condition_str);
		return $result;
	}
	
	public function delByWhere($conditionarr){
		if(is_array($conditionarr)){
			$condition_str = $this->_condition($conditionarr);
			$result = Db::delete('flea_upload',$condition_str);
			return $result;
		}else{
			return false;
		}
	}
}
