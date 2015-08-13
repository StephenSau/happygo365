<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_goodsModel{
	
	public function goodsAdd($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('sns_goods',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getGoodsInfo($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_goods';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function editGoods($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_goods',$param,$condition_str);
		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['snsgoods_goodsid'] != '') {
			$condition_sql	.= " and `sns_goods`.snsgoods_goodsid = '{$condition_array['snsgoods_goodsid']}'";
		}
		return $condition_sql;
	}
}