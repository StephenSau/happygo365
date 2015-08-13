<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_tracelogModel{
	
	public function tracelogAdd($param){
		if (empty($param)){
			return false;
		}
		if ($param['trace_title']){
			preg_match_all("/@(.+?)([\s|:]|$)/is", $param['trace_title'], $matches);
			if (!empty($matches[1])){
				$membername_str = "'".implode("','",$matches[1])."'";
				$member_model = Model('member');
				$member_list = $member_model->getMemberList(array('in_member_name'=>"$membername_str"));
				foreach ($member_list as $k=>$v){
					$param['trace_title'] = preg_replace("/@(".$v['member_name'].")([\s|:]|$)/is",'<a href=\"%siteurl%index.php?act=member_snshome&mid='.$v['member_id'].'\" target="_blank">@${1}</a>${2}',$param['trace_title']);
				}
			}
			unset($matches);
		}
		$result = Db::insert('sns_tracelog',$param);
		return $result;
	}
	
	public function getTracelogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'sns_tracelog';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sns_tracelog.trace_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getTracelogRow($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_tracelog';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function tracelogEdit($param,$condition){
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_tracelog',$param,$condition_str);
		return $result;
	}
	
	public function delTracelog($condition){
		if (empty($condition)){
			return false;
		}
		$condition_str = '';	
		if ($condition['trace_id'] != ''){
			$condition_str .= " and trace_id='{$condition['trace_id']}' ";
		}
		if ($condition['trace_id_in'] !=''){
			$condition_str .= " and trace_id in('{$condition['trace_id_in']}') ";
		}
		if ($condition['trace_memberid'] != ''){
			$condition_str .= " and trace_memberid='{$condition['trace_memberid']}' ";
		}
		return Db::delete('sns_tracelog',$condition_str);
	}
	
	public function countTrace($condition){
		$condition_str	= $this->getCondition($condition);
		$count = Db::getCount('sns_tracelog',$condition_str);
		return $count;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['trace_id'] != '') {
			$condition_sql .= " and sns_tracelog.trace_id = '{$condition_array['trace_id']}' ";
		}
		if($condition_array['traceid_in'] != '') {
			$condition_sql .= " and sns_tracelog.trace_id in('{$condition_array['traceid_in']}') ";
		}
		if($condition_array['trace_originalid'] != '') {
			$condition_sql .= " and sns_tracelog.trace_originalid = '{$condition_array['trace_originalid']}' ";
		}
		if($condition_array['trace_originalid_in'] != '') {
			$condition_sql .= " and sns_tracelog.trace_originalid in('{$condition_array['trace_originalid_in']}')";
		}
		if($condition_array['trace_memberid'] != '') {
			$condition_sql .= " and sns_tracelog.trace_memberid = '{$condition_array['trace_memberid']}' ";
		}
		if($condition_array['trace_membernamelike'] != '') {
			$condition_sql .= " and sns_tracelog.trace_membername like '%{$condition_array['trace_membernamelike']}%' ";
		}
		if ($condition_array['trace_state'] != ''){
			$condition_sql .= " and sns_tracelog.trace_state = '{$condition_array['trace_state']}' ";
		}
		if($condition_array['allowshow'] != '') {
			$allowshowsql_arr = array();
			if ($condition_array['allowshow_memberid'] !=''){
				$allowshowsql_arr[0] = " (sns_tracelog.trace_memberid = '{$condition_array['allowshow_memberid']}')";
			}
			if ($condition_array['allowshow_followerin'] !=''){
				$allowshowsql_arr[1] .= " (sns_tracelog.trace_privacy=0 and sns_tracelog.trace_memberid in('{$condition_array['allowshow_followerin']}'))";
			}
			if ($condition_array['allowshow_friendin'] !=''){
				$allowshowsql_arr[2] .= " (sns_tracelog.trace_privacy=1 and sns_tracelog.trace_memberid in('{$condition_array['allowshow_friendin']}'))";
			}
			$condition_sql .=" and (".implode(' or ',$allowshowsql_arr).")";
		}
		if ($condition_array['trace_privacyin'] !=''){
			$condition_sql	.= " and `sns_tracelog`.trace_privacy in('{$condition_array['trace_privacyin']}')";
		}
		if ($condition_array['stime'] !=''){
			$condition_sql	.= " and `sns_tracelog`.trace_addtime >= {$condition_array['stime']}";
		}
		if ($condition_array['etime'] !=''){
			$condition_sql	.= " and `sns_tracelog`.trace_addtime <= {$condition_array['etime']}";
		}
		if ($condition_array['trace_contentortitle'] !=''){
			$condition_sql	.= " and (`sns_tracelog`.trace_title like '%{$condition_array['trace_contentortitle']}%' or `sns_tracelog`.trace_content like '%{$condition_array['trace_contentortitle']}%') ";
		}
		return $condition_sql;
	}
}