<?php

defined('haipinlegou') or exit('Access Invalid!');
class mail_templatesModel{
	
	public function getTemplatesList($condition){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'mail_msg_temlates';
		$param['where'] = $condition_str;
		$result = Db::select($param);
		return $result;
	}
	
	
	private function _condition($condition){
		$condition_str = '';
		
		if ($condition['type'] != ''){
			$condition_str .= " and type = '". $condition['type'] ."'";
		}
		if ($condition['code'] != ''){
			$condition_str .= " and code = '". $condition['code'] ."'";
		}
		return $condition_str;
	}
	
	
	public function getOneTemplates($code){
		if (!empty($code)){
			$param = array();
			$param['table'] = 'mail_msg_temlates';
			$param['field'] = 'code';
			$param['value'] = $code;
			$result = Db::getRow($param);
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
			$where = " code = '". $param['code'] ."'";
			$result = Db::update('mail_msg_temlates',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
}