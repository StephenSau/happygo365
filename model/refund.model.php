<?php

defined('haipinlegou') or exit('Access Invalid!');
class refundModel{
	
	public function getRow($log_id){
		$param	= array();
		$param['table']	= 'refund_log';
		$param['field']	= 'log_id';
		$param['value']	= $log_id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	
	public function getList($condition = '',$page = ''){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['table'] = 'refund_log';
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'log_id desc';
		$param['limit'] = $condition['limit'];
		$result = Db::select($param,$page);
		return $result;
	}
	
	public function getSn() {
		$sn = '8'.date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
		return $sn;
	}
	
	
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$param['refund_sn'] = $this->getSn();
			$result = Db::insert('refund_log',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function update($condition,$param){
		$log_id = $condition['log_id'];
		if (intval($log_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($param)){
			$result = Db::update('refund_log',$param,$where);
			return result;
		}else {
			return false;
		}
	}
	
	public function del($condition){
		$log_id = $condition['log_id'];
		if (intval($log_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($condition)){
			$result = Db::delete('refund_log',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getCount($condition) {
		$condition_str	= $this->getCondition($condition);
		$count	= Db::getCount('refund_log',$condition_str);
		return $count;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['log_id'] !== null) {
			$condition_sql	.= " and log_id = '".$condition_array['log_id']."'";
		}
		if($condition_array['order_id'] !== null) {
			$condition_sql	.= " and order_id = '".$condition_array['order_id']."'";
		}
		if($condition_array['seller_id'] !== null) {
			$condition_sql	.= " and seller_id = '".$condition_array['seller_id']."'";
		}
		if($condition_array['buyer_id'] !== null) {
			$condition_sql	.= " and buyer_id = '".$condition_array['buyer_id']."'";
		}
		if($condition_array['store_id'] !== null) {
			$condition_sql	.= " and store_id = '".$condition_array['store_id']."'";
		}
		if($condition_array['order_sn'] !== null) {
			$condition_sql	.= " and order_sn = '".$condition_array['order_sn']."'";
		}
		if($condition_array['refund_type'] !== null) {
			$condition_sql	.= " and refund_type = '".$condition_array['refund_type']."'";
		}
		if($condition_array['refund_state'] !== null) {
			$condition_sql	.= " and refund_state = '".$condition_array['refund_state']."'";
		}
		if($condition_array['seller_refund_state'] !== null) {
			$condition_sql	.= " and refund_state > 1";
		}
		if($condition_array['refund_sn'] !== null) {
			$condition_sql	.= " and refund_sn = '".$condition_array['refund_sn']."'";
		}
		if($condition_array['add_time_from'] !== null){
			$condition_sql	.= " and add_time >= '".$condition_array['add_time_from']."'";
		}
		if($condition_array['add_time_to'] !== null){
			$condition_sql	.= " and add_time <= '".$condition_array['add_time_to']."'";
		}
		if ($condition_array['keyword'] !== null){
			$condition_sql .= " and ". $condition_array['type'] ." like '%". $condition_array['keyword'] ."%'";
		}
		if($condition_array['order_ids'] !== null) {
			$condition_sql	.= " and order_id in(".$condition_array['order_ids'].")";
		}
		return $condition_sql;
	}
	
}