<?php

defined('haipinlegou') or exit('Access Invalid!');
class gold_paymentModel{
	
	public function getRow($payment_id){
		$param	= array();
		$param['table']	= 'gold_payment';
		$param['field']	= 'payment_id';
		$param['value']	= $payment_id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	public function getRowByCode($payment_code){
		$param	= array();
		$param['table']	= 'gold_payment';
		$param['field']	= 'payment_code';
		$param['value']	= $payment_code;
		$result	= Db::getRow($param);
		return $result;
	}
	
	public function getRowByCondition($conditionfield,$conditionvalue){
		$param	= array();
		$param['table']	= 'gold_payment';
		$param['field']	= $conditionfield;
		$param['value']	= $conditionvalue;
		$result	= Db::getRow($param);
		return $result;
	}
	
	public function getList($condition = array()){
		$condition_str = $this->getCondition($condition);
		
		$param = array();
		$param['table'] = 'gold_payment';
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'payment_state';
		$param['limit'] = '9';
		$result = Db::select($param);
		return $result;
	}
	
	
	public function update($payment_id,$param){
		if (intval($payment_id) < 1){
			return false;
		}
		$where = " payment_id = '". $payment_id ."'";
		if (is_array($param)){
			$result = Db::update('gold_payment',$param,$where);
			return result;
		}else {
			return false;
		}
	}
	
	public function checkPayment($payment_code) {
		$payment_info = $this->getRowByCode($payment_code);
		if (empty($payment_info)){
			return false;
		}
		if ($payment_info['payment_state'] == '1'){
			return true;
		}else {
			return false;
		}
	}	
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['payment_id'] != '') {
			$condition_sql	.= " and `gold_payment`.payment_id = '".$condition_array['payment_id']."'";
		}
		if($condition_array['payment_state'] != '') {
			$condition_sql	.= " and `gold_payment`.payment_state = '".$condition_array['payment_state']."'";
		}
		if($condition_array['payment_code'] != '') {
			$condition_sql	.= " and `gold_payment`.payment_code = '".$condition_array['payment_code']."'";
		}
		
		return $condition_sql;
	}
	
}