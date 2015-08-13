<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_friendModel{
	
	public function addFriend($param) {
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('sns_friend',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function listFriend($condition,$field='*',$obj_page='',$type='simple') {
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		switch ($type){
			case 'simple':
				$param['table']		= 'sns_friend';
				break;
			case 'detail':
				$param['table']		= 'sns_friend,member';
				$param['join_type']	= 'INNER JOIN';
				$param['join_on']	= array('sns_friend.friend_tomid=member.member_id');
				break;
			case 'fromdetail':
				$param['table']		= 'sns_friend,member';
				$param['join_type']	= 'INNER JOIN';
				$param['join_on']	= array('sns_friend.friend_frommid=member.member_id');
				break;
		}
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sns_friend.friend_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		$friend_list	= Db::select($param,$obj_page);
		return $friend_list;
	}
	
	public function getFriendRow($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_friend';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function countFriend($condition){
		$condition_str	= $this->getCondition($condition);
		$count = Db::getCount('sns_friend',$condition_str);
		return $count;
	}
	
	public function editFriend($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_friend',$param,$condition_str);
		return $result;
	}
	
	public function delFriend($condition){
		if (empty($condition)){
			return false;
		}
		if ($condition['friend_frommid'] != ''){	
			$condition_str .= " and friend_frommid='{$condition['friend_frommid']}' ";
		}
		$condition_str = '';
		if ($condition['friend_tomid'] != ''){
			$condition_str .= " and friend_tomid='{$condition['friend_tomid']}' ";
		}
		return Db::delete('sns_friend',$condition_str);
	}
	
	
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['friend_id'] != '') {
			$condition_sql	.= " and sns_friend.friend_id= '{$conditon_array['friend_id']}'";
		}
		if($conditon_array['friend_frommid'] != '') {
			$condition_sql	.= " and sns_friend.friend_frommid= '{$conditon_array['friend_frommid']}'";
		}
		if($conditon_array['friend_tomid'] != '') {
			$condition_sql	.= " and sns_friend.friend_tomid = '{$conditon_array['friend_tomid']}'";
		}
		if($conditon_array['friend_followstate'] != '') {
			$condition_sql	.= " and sns_friend.friend_followstate = '{$conditon_array['friend_followstate']}'";
		}
		return $condition_sql;
	}
}