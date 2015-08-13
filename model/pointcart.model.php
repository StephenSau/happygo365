<?php

defined('haipinlegou') or exit('Access Invalid!');

class pointcartModel {
	
	public function addPointCart($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('points_cart',$param);
		if($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	public function getPointCartList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'points_cart';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_cart.pgoods_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getPointCartInfo($condition,$field='*'){
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'points_cart';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$info		= Db::select($array);
		return $info[0];
	}
	
	public function countPointCart($condition) {
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'points_cart';
		$array['where']	= $condition_str;
		$array['field']	= "count(pcart_id)";
		$cart_goods_num = Db::select($array);
		return $cart_goods_num[0][0];
	}
	
	
	public function dropPointCartById($pc_id){
		if(empty($pc_id)) {
			return false;
		}
		$condition_str = ' 1=1 ';
		if (is_array($pc_id) && count($pc_id)>0){
			$pc_idStr = implode(',',$pc_id);
			$condition_str .= " and	pcart_id in({$pc_idStr}) ";
		}else {
			$condition_str .= " and pcart_id = '{$pc_id}' ";
		}
		$result = Db::delete('points_cart',$condition_str);		
		return $result;
	}
	
	public function dropPointCart($condition){
		$condition_str	= $this->getCondition($condition);
		$result = Db::delete('points_cart',$condition_str);		
		return $result;
	}
	
	public function updatePointCart($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('points_cart',$param,$condition_str);
		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['pcart_id']) {
			$condition_sql	.= " and `points_cart`.pcart_id = '{$condition_array['pcart_id']}'";
		}
		
		if ($condition_array['pmember_id']) {
			$condition_sql	.= " and pmember_id = '{$condition_array['pmember_id']}'";
		}
		
		if ($condition_array['pgoods_id']) {
			$condition_sql	.= " and `points_cart`.pgoods_id = '{$condition_array['pgoods_id']}'";
		}
		return $condition_sql;
	}
}