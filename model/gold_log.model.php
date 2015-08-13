<?php

defined('haipinlegou') or exit('Access Invalid!');
class gold_logModel{
	
	public function getRow($glog_id){
		$param	= array();
		$param['table']	= 'gold_log';
		$param['field']	= 'glog_id';
		$param['value']	= $glog_id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	
	public function getList($condition = '',$page = ''){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['table'] = 'gold_log';
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'glog_id desc';
		$param['limit'] = $condition['limit'];
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('gold_log',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['glog_id'] != '') {
			$condition_sql	.= " and glog_id = '".$condition_array['glog_id']."'";
		}
		if($condition_array['glog_memberid'] != '') {
			$condition_sql	.= " and glog_memberid = '".$condition_array['glog_memberid']."'";
		}
		if($condition_array['membername_like'] != '') {
			$condition_sql	.= " and glog_membername like '%{$condition_array['membername_like']}%'";
		}
		if($condition_array['storename_like'] != '') {
			$condition_sql	.= " and glog_storename like '%{$condition_array['storename_like']}%'";
		}
		if($condition_array['glog_method'] != '') {
			$condition_sql	.= " and glog_method = '".$condition_array['glog_method']."'";
		}
		if($condition_array['glog_stage'] != '') {
			$condition_sql	.= " and glog_stage = '".$condition_array['glog_stage']."'";
		}
		if($condition_array['add_time_from'] != ''){
			$condition_sql	.= " and glog_addtime >= '".$condition_array['add_time_from']."'";
		}
		if($condition_array['add_time_to'] != ''){
			$condition_sql	.= " and glog_addtime <= '".$condition_array['add_time_to']."'";
		}
		return $condition_sql;
	}
	
}