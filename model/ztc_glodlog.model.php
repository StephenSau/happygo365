<?php

defined('haipinlegou') or exit('Access Invalid!');

class ztc_glodlogModel {
	
	public function addlog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('ztc_glodlog',$param);
		return $result;
	}
	
	public function getLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'ztc_glodlog';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'ztc_glodlog.glog_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['glog_goodsname']) {
			$condition_sql	.= " and `ztc_glodlog`.glog_goodsname like '%{$condition_array['glog_goodsname']}%'";
		}
		if ($condition_array['glog_type']){
			$condition_sql	.= " and `ztc_glodlog`.glog_type = '{$condition_array['glog_type']}'";
		}
		if ($condition_array['glog_storeid']) {
			$condition_sql	.= " and `ztc_glodlog`.glog_storeid = '{$condition_array['glog_storeid']}'";
		}
		if ($condition_array['glog_storename']) {
			$condition_sql	.= " and `ztc_glodlog`.glog_storename like '%{$condition_array['glog_storename']}%'";
		}
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `ztc_glodlog`.glog_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `ztc_glodlog`.glog_addtime <= '{$condition_array['eaddtime']}'";
		}
		return $condition_sql;
	}
}