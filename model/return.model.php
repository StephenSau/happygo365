<?php

defined('haipinlegou') or exit('Access Invalid!');
class returnModel{
	
	public function getRow($return_id){
		$param	= array();
		$param['table']	= 'return';
		$param['field']	= 'return_id';
		$param['value']	= $return_id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	
	public function getList($condition = '',$page = ''){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['table'] = 'return';
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'return_id desc';
		$param['limit'] = $condition['limit'];
		$result = Db::select($param,$page);
		return $result;
	}
	
	public function getOrderGoodsList($order_id){
		$condition_str = " and order_id = '".$order_id."'";
		$param = array();
		$param['table'] = 'order_goods';
		$param['where']	= $condition_str;
		$result = Db::select($param);
		return $result;
	}
	
	public function getOrderGoodsCount($order_id){
		$condition_str = " and order_id = '".$order_id."'";
		$param = array();
		$param['table'] = 'order_goods';
		$array['field']		= 'sum(goods_num) as goods_num';
		$param['where']	= $condition_str;
		$result = Db::select($param);
		return $result[0]['goods_num'];
	}
	
	public function getReturnGoodsList($condition){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['field'] = $condition['field'] ? $condition['field'] : 'return_goods.*,return.add_time,return.order_sn,return.return_sn';
		$param['table'] = 'return,return_goods';
		$param['where']	= $condition_str;
		$param['join_type']	= 'left join';
		$param['join_on']	= array('return.return_id=return_goods.return_id');
		$param['order'] = $condition['order'] ? $condition['order'] : 'return.return_id desc';
		$result = Db::select($param);
		return $result;
	}
	
	public function getSn() {
		$sn = '9'.date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
		return $sn;
	}
	
	
	public function add($param){
           
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$param['return_sn'] = $this->getSn();
			$result = Db::insert('return',$param);
			return $result;
		}else {
			return false;
		}
                   print_r($param);
            die;
	}
	
	public function addGoods($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('return_goods',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function update($condition,$param){
          
		$return_id = $condition['return_id'];
		if (intval($return_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($param)){
			$result = Db::update('return',$param,$where);
                        
			return result;
		}else {
			return false;
		}
	}
	
	public function del($condition){
		$return_id = $condition['return_id'];
		if (intval($return_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($condition)){
			$result = Db::delete('return',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getCount($condition) {
		$condition_str	= $this->getCondition($condition);
		$count	= Db::getCount('return',$condition_str);
		return $count;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['return_id'] !== null) {
			$condition_sql	.= " and return.return_id = '".$condition_array['return_id']."'";
		}
		if($condition_array['order_id'] !== null) {
			$condition_sql	.= " and return.order_id = '".$condition_array['order_id']."'";
		}
		if($condition_array['seller_id'] !== null) {
			$condition_sql	.= " and seller_id = '".$condition_array['seller_id']."'";
		}
		if($condition_array['store_id'] !== null) {
			$condition_sql	.= " and store_id = '".$condition_array['store_id']."'";
		}
		if($condition_array['order_sn'] !== null) {
			$condition_sql	.= " and order_sn = '".$condition_array['order_sn']."'";
		}
		if($condition_array['return_sn'] !== null) {
			$condition_sql	.= " and return_sn = '".$condition_array['return_sn']."'";
		}
		if($condition_array['return_type'] !== null) {
			$condition_sql	.= " and return_type = '".$condition_array['return_type']."'";
		}
		if($condition_array['return_state'] !== null) {
			$condition_sql	.= " and return_state = '".$condition_array['return_state']."'";
		}
		if($condition_array['seller_return_state'] !== null) {
			$condition_sql	.= " and return_state > 1";
		}
		if($condition_array['add_time_from'] !== null){
			$condition_sql	.= " and add_time >= '".$condition_array['add_time_from']."'";
		}
		if($condition_array['add_time_to'] !== null){
			$condition_sql	.= " and add_time <= '".$condition_array['add_time_to']."'";
		}
		if ($condition_array['keyword'] !== null){
			$condition_sql .= " and return.". $condition_array['type'] ." like '%". $condition_array['keyword'] ."%'";
		}
		if($condition_array['order_ids'] !== null) {
			$condition_sql	.= " and return.order_id in(".$condition_array['order_ids'].")";
		}		
		if($condition_array['buyer_id'] !== null) {
			$condition_sql	.= " and buyer_id = '".$condition_array['buyer_id']."'";
		}
		return $condition_sql;
	}
	
}