<?php

defined('haipinlegou') or exit('Access Invalid!');

class upload_albumModel{
	
	public function getUploadList($condition){
		
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'album_pic';
		$param['where'] = $condition_str;
		$result = Db::select($param);
		return $result;
	}

	
	private function _condition($condition){
		$condition_str = '';

		if($condition['apic_name'] != '') {
			$condition_str .= " and apic_name='{$condition['pic_name']}'";
		}
		if($condition['apic_tag'] != '') {
			$condition_str .= " and apic_tag='{$condition['apic_tag']}'";
		}
		if($condition['aclass_id'] != '') {
			$condition_str .= " and aclass_id='{$condition['aclass_id']}'";
		}
		if($condition['apic_cover'] != '') {
			$condition_str .= " and apic_cover='{$condition['apic_cover']}'";
		}
		if($condition['apic_size'] != '') {
			$condition_str .= " and apic_size='{$condition['apic_size']}'";
		}
		if($condition['store_id'] != '') {
			$condition_str .= " and store_id='{$condition['store_id']}'";
		}
		if($condition['upload_time'] != '') {
			$condition_str .= " and upload_time='{$condition['upload_time']}'";
		}
		return $condition_str;
	}

	
	public function getOneUpload($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'album_pic';
			$param['field'] = 'apic_id';
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
			$result = Db::insert('album_pic',$param);
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
			$where = " apic_id = '{$param['apic_id']}'";
			$result = Db::update('album_pic',$tmp,$where);
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
			$result = Db::update('album_pic',$tmp,$condition_str);
			return $result;
		}else {
			return false;
		}
	}
	
	public function del($id){
		if (intval($id) > 0){
			$where = " apic_id = '". intval($id) ."'";
			$result = Db::delete('album_pic',$where);
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
			$condition_str .= " and apic_id in({$idStr}) ";
		}else {
			$condition_str .= " and apic_id = {$id} ";
		}
		$result = Db::delete('album_pic',$condition_str);
		return $result;
	}
	



}