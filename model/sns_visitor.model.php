<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_visitorModel{
	
	public function visitorAdd($param){
		if (empty($param)){
			return false;
		}
		$result = Db::insert('sns_visitor',$param);
		return $result;
	}
	
	public function getVisitorList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'sns_visitor';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sns_visitor.v_addtime desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getVisitorRow($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_visitor';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function visitorEdit($param,$condition){
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_visitor',$param,$condition_str);
		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['v_id'] != '') {
			$condition_sql .= " and sns_visitor.v_id = '{$condition_array['v_id']}' ";
		}
		if($condition_array['v_mid'] != '') {
			$condition_sql .= " and sns_visitor.v_mid = '{$condition_array['v_mid']}' ";
		}
		if($condition_array['v_ownermid'] != '') {
			$condition_sql .= " and sns_visitor.v_ownermid = '{$condition_array['v_ownermid']}' ";
		}
		return $condition_sql;
	}
}