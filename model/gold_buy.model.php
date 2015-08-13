<?php

defined('haipinlegou') or exit('Access Invalid!');
class gold_buyModel{
	
	public function getRow($gbuy_id){
		$param	= array();
		$param['table']	= 'gold_buy';
		$param['field']	= 'gbuy_id';
		$param['value']	= $gbuy_id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	
	public function getList($condition = '',$page = ''){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['table'] = 'gold_buy';
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'gbuy_id desc';
		$param['limit'] = $condition['limit'];
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('gold_buy',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function update($condition,$param){
		$gbuy_id = $condition['gbuy_id'];
		if (intval($gbuy_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($param)){
			$result = Db::update('gold_buy',$param,$where);
			return result;
		}else {
			return false;
		}
	}
	
	public function del($condition){
		$gbuy_id = $condition['gbuy_id'];
		if (intval($gbuy_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($condition)){
			$result = Db::delete('gold_buy',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['gbuy_id'] != '') {
			$condition_sql	.= " and gbuy_id = '".$condition_array['gbuy_id']."'";
		}
		if($condition_array['gbuy_mid'] != '') {
			$condition_sql	.= " and gbuy_mid = '".$condition_array['gbuy_mid']."'";
		}
		if($condition_array['membername_like'] != '') {
			$condition_sql	.= " and gbuy_membername like '%{$condition_array['membername_like']}%'";
		}
		if($condition_array['storename_like'] != '') {
			$condition_sql	.= " and gbuy_storename like '%{$condition_array['storename_like']}%'";
		}
		if($condition_array['gbuy_check_type'] != '') {
			$condition_sql	.= " and gbuy_check_type = '{$condition_array['gbuy_check_type']}'";
		}		
		if($condition_array['gbuy_ispay'] != '') {
			$condition_sql	.= " and gbuy_ispay = '".$condition_array['gbuy_ispay']."'";
		}
		if($condition_array['add_time_from'] != ''){
			$condition_sql	.= " and gbuy_addtime >= '".$condition_array['add_time_from']."'";
		}
		if($condition_array['add_time_to'] != ''){
			$condition_sql	.= " and gbuy_addtime <= '".$condition_array['add_time_to']."'";
		}
		
		return $condition_sql;
	}
	
}