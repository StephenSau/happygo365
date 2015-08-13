<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_commentModel{
	
	public function commentAdd($param){
		if (empty($param)){
			return false;
		}
		
		if ($param['comment_content']){
			preg_match_all("/@(.+?)([\s|:]|$)/is", $param['comment_content'], $matches);
			if (!empty($matches[1])){
				$membername_str = "'".implode("','",$matches[1])."'";
				$member_model = Model('member');
				$member_list = $member_model->getMemberList(array('in_member_name'=>"$membername_str"));
				foreach ($member_list as $k=>$v){
					$param['comment_content'] = preg_replace("/@(".$v['member_name'].")([\s|:]|$)/is",'<a href=\"index.php?act=member_snsindex\">@${1}</a>${2}',$param['comment_content']);
				}
			}
			unset($matches);
		}
		if (is_array($param)){
			$result = Db::insert('sns_comment',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getCommentList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'sns_comment';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sns_comment.comment_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getCommentCount($condition){
		$condition_str	= $this->getCondition($condition);
		return Db::getCount("sns_comment",$condition_str);
	}
	
	public function getCommentRow($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_comment';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function delComment($condition){
		if (empty($condition)){
			return false;
		}
		$condition_str = '';
		if ($condition['comment_id'] != ''){
			$condition_str .= " and comment_id='{$condition['comment_id']}' ";
		}
		if($condition['comment_id_in'] != '') {
			$condition_str .= " and comment_id in('{$condition['comment_id_in']}')";
		}
		if ($condition['comment_originalid_in'] !=''){
			$condition_str .= " and comment_originalid in('{$condition['comment_originalid_in']}') ";
		}
		if ($condition['comment_originalid'] != ''){
			$condition_str .= " and comment_originalid='{$condition['comment_originalid']}' ";
		}
		if ($condition['comment_originaltype'] != ''){
			$condition_str .= " and comment_originaltype = '{$condition['comment_originaltype']}' ";
		}
		return Db::delete('sns_comment',$condition_str);
	}
	
	public function commentEdit($param,$condition){
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_comment',$param,$condition_str);
		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['comment_id_in'] != '') {
			$condition_sql .= " and sns_comment.comment_id in('{$condition_array['comment_id_in']}')";
		}
		if($condition_array['comment_originalid'] != '') {
			$condition_sql .= " and sns_comment.comment_originalid = '{$condition_array['comment_originalid']}'";
		}
		if($condition_array['comment_originaltype'] != '') {
			$condition_sql .= " and sns_comment.comment_originaltype = '{$condition_array['comment_originaltype']}'";
		}
		if($condition_array['comment_membername_like'] != '') {
			$condition_sql .= " and sns_comment.comment_membername like '%{$condition_array['comment_membername_like']}%'";
		}
		if($condition_array['comment_state'] != '') {
			$condition_sql .= " and sns_comment.comment_state = '{$condition_array['comment_state']}'";
		}
		if($condition_array['comment_content_like'] != '') {
			$condition_sql .= " and sns_comment.comment_content like '%{$condition_array['comment_content_like']}%'";
		}
		if ($condition_array['stime'] !=''){
			$condition_sql	.= " and `sns_comment`.comment_addtime >= {$condition_array['stime']}";
		}
		if ($condition_array['etime'] !=''){
			$condition_sql	.= " and `sns_comment`.comment_addtime <= {$condition_array['etime']}";
		}
		return $condition_sql;
	}
}