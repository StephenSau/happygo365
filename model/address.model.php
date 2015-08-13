<?php

defined('haipinlegou') or exit('Access Invalid!');
class addressModel{	
	
	public function getAddressList($condition){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'address';
		$param['where'] = $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'address.address_id';
		$result = Db::select($param);
		return $result;
	}
	
	
	private function _condition($condition){
		$condition_str = '';
		
		if ($condition['member_id'] != ''){
			$condition_str .= " member_id = '". intval($condition['member_id']) ."'";
		}
		
		return $condition_str;
	}
	
	
	public function addAddress($param){
		if (!empty($param) && is_array($param)){
			$tmp = array(
				'member_id'=>$_SESSION['member_id'],
				'true_name'=>$param['true_name'],
				'area_id'=>$param['area_id'],
				'city_id'=>$param['city_id'],
				'area_info'=>$param['area_info'],
				'address'=>$param['address'],
				'zip_code'=>$param['zip_code'],
				'tel_phone'=>$param['tel_phone'],
				'mob_phone'=>$param['mob_phone'],
				'card'=>$param['card'],
				'distinguish'=>$param['distinguish'],
				'idcard'=>$param['idcard'],
				'idcard2'=>$param['idcard2']
			);
			$result = Db::insert('address',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function getOneAddress($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'address';
			$param['field'] = 'address_id';
			$param['value'] = intval($id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function updateAddress($param){
		if (is_array($param) && !empty($param) && self::checkAddress($_SESSION['member_id'],$param['id'])){
			$tmp = array(
				'member_id'=>$_SESSION['member_id'],
				'true_name'=>$param['true_name'],
				'area_id'=>$param['area_id'],
				'city_id'=>$param['city_id'],
				'area_info'=>$param['area_info'],
				'address'=>$param['address'],
				'zip_code'=>$param['zip_code'],
				'tel_phone'=>$param['tel_phone'],
				'mob_phone'=>$param['mob_phone'],
				'card'=>$param['card'],
				'idcard'=>$param['idcard'],
				'idcard2'=>$param['idcard2']
			);
			$where = " address_id = '". intval($param['id'])."'";
			$result = Db::update('address',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}	
	
	//更新收货人
	public function upAddress($param){
		if (is_array($param) && !empty($param) && self::checkAddress($_SESSION['member_id'],$param['id'])){
			
			$tmp = array(
				'member_id'=>$_SESSION['member_id'],
				//'true_name'=>$param['true_name'],
				'card'=>$param['card'],
				'idcard'=>$param['idcard'],
				'idcard2'=>$param['idcard2']
			);
			
			$where = " address_id = '". intval($param['id'])."'";
			$result = Db::update('address',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function checkAddress($member_id,$address_id) {
		
		$check_array = self::getOneAddress($address_id);
		if ($check_array['member_id'] == $member_id){
			unset($check_array);
			return true;
		}
		unset($check_array);
		return false;
	}
	
	public function delAddress($id){
		if (intval($id) > 0 && self::checkAddress($_SESSION['member_id'],$id)){
			$where = " address_id = '". intval($id) ."'";
			$result = Db::delete('address',$where);
			return $result;
		}else {
			return false;
		}
	}
}