<?php

defined('haipinlegou') or exit('Access Invalid!');
class messageModel {
	
	public function listMessage($condition,$page='') {
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']		= 'message';
		$param['where']		= $condition_str;
		$param['order']		= 'message.message_id DESC';
		$message_list		= Db::select($param,$page);
		return $message_list;
	}
	
	public function listAndStoreMessage($condition,$page='') {		
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'message,store';
		$param['field'] = 'message.*,store.store_name,store.store_id';
		$param['where']	= $condition_str;
		$param['join_type'] = 'left join';
		$param['join_on'] = array('message.from_member_id = store.member_id');
		$param['order']	= 'message.message_id DESC';
		$message_list	= Db::select($param,$page);
		return $message_list;
	}
	
	public function countMessage($condition) {
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']		= 'message';
		$param['where']		= $condition_str;
		$param['field']		= ' count(message_id) as countnum ';
		$message_list		= Db::select($param,$page);
		return $message_list[0]['countnum'];
	}
	
	public function countNewMessage($member_id){
		$condition_arr = array();
		$condition_arr['to_member_id'] = "$member_id";
		$condition_arr['no_message_state'] = '2';
		$condition_arr['message_open_common'] = '0';
		$condition_arr['no_del_member_id'] = "$member_id";
		$condition_arr['no_read_member_id'] = "$member_id";
		$countnum = $this->countMessage($condition_arr);
		return $countnum;
	}
	
	public function getRowMessage($condition) {
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']		= 'message';
		$param['where']		= $condition_str;
		$message_list		= Db::select($param);
		return $message_list[0];
	}
	
	public function saveMessage($param) {
		if($param['member_id'] == '') {
			return false;
		}
		$array	= array();
		$array['message_parent_id'] = $param['message_parent_id']?$param['message_parent_id']:'0';
		$array['from_member_id']	= $param['from_member_id'] ? $param['from_member_id'] : '0' ;
		$array['from_member_name']	= $param['from_member_name'] ? $param['from_member_name'] : '' ;
		$array['to_member_id']	    = $param['member_id'];
		$array['to_member_name']	= $param['to_member_name']?$param['to_member_name']:'';
		$array['message_body']		= trim($param['msg_content']);
		$array['message_time']		= time();
		$array['message_update_time']= time();
		$array['message_type']		= $param['message_type']?$param['message_type']:'0';
		$array['message_ismore']	= $param['message_ismore']?$param['message_ismore']:'0';
		$array['read_member_id']	= $param['read_member_id']?$param['read_member_id']:'';
		$array['del_member_id']	= $param['del_member_id']?$param['del_member_id']:'';
		return Db::insert('message',$array);
	}
	
	public function updateCommonMessage($param,$condition){
		if(empty($param)) {
			return false;
		}
		$condition_str = $this->getCondition($condition);
		Db::update('message',$param,$condition_str);
	}
	
	public function dropCommonMessage($condition,$drop_type){
		$condition_str = $this->getCondition($condition);
		$message_list	= array();
		$message_list = Db::select(array('table'=>'message','where'=>$condition_str,'field'=>'message_id,from_member_id,to_member_id,message_state,message_open'));
		unset($condition_str);
		if (empty($message_list)){
			return true;
		}
		$delmessage_id = array();
		$updatemessage_id = array();
		foreach ($message_list as $k=>$v){
			if ($drop_type == 'msg_private') {
				if($v['message_state'] == 2) {
					$delmessage_id[] = $v['message_id'];
				} elseif ($v['message_state'] == 0) {
					$updatemessage_id[] = $v['message_id'];
				}
			} elseif ($drop_type == 'msg_list') {
				if($v['message_state'] == 1) {
					$delmessage_id[] = $v['message_id'];
				} elseif ($v['message_state'] == 0) {
					$updatemessage_id[] = $v['message_id'];
				}
			} elseif ($drop_type == 'sns_msg'){
				$delmessage_id[] = $v['message_id'];
			}
		}
		if (!empty($delmessage_id)){
			$delmessage_id_str = "'".implode("','",$delmessage_id)."'";
			$condition_str = $this->getCondition(array('message_id_in'=>$delmessage_id_str));
			Db::delete('message',$condition_str);
			unset($condition_str);
		}
		if (!empty($updatemessage_id)){
			$updatemessage_id_str = "'".implode("','",$updatemessage_id)."'";
			$condition_str = $this->getCondition(array('message_id_in'=>$updatemessage_id_str));
			if ($drop_type == 'msg_private') {
				Db::update('message',array('message_state'=>1),$condition_str);
			}elseif ($drop_type == 'msg_list') {
				Db::update('message',array('message_state'=>2),$condition_str);
			}
		}
		return true;
	}
	
	public function dropBatchMessage($condition,$to_member_id){
		$condition_str = $this->getCondition($condition);
		$message_list	= array();
		$message_list = Db::select(array('table'=>'message','where'=>$condition_str));
		unset($condition_str);
		if (empty($message_list)){
			return true;
		}
		foreach ($message_list as $k=>$v){
			$tmp_delid_str = '';
			if (!empty($v['del_member_id'])){
				$tmp_delid_arr = explode(',',$v['del_member_id']);
				if (!in_array($to_member_id,$tmp_delid_arr)){
					$tmp_delid_arr[] = $to_member_id;
				}
				foreach ($tmp_delid_arr as $delid_k=>$delid_v){
					if ($delid_v == ''){
						unset($tmp_delid_arr[$delid_k]);
					}
				}
				$tmp_delid_arr = array_unique ($tmp_delid_arr);
				sort($tmp_delid_arr);
				$tmp_delid_str = ",".implode(',',$tmp_delid_arr).",";
			}else {
				$tmp_delid_str = ",{$to_member_id},";
			}
			if ($tmp_delid_str == $v['to_member_id']){
				Db::delete('message'," message_id = '{$v['message_id']}' ");
			}else {
				Db::update('message',array('del_member_id'=>$tmp_delid_str)," message_id = '{$v['message_id']}' ");
			}
		}
		return true;
	}
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['message_id'] != ''){
			$condition_sql	.= " and message.message_id = '{$condition_array['message_id']}'";
		}
		if($condition_array['message_parent_id'] != ''){
			$condition_sql	.= " and message.message_parent_id = '{$condition_array['message_parent_id']}'";
		}
		if($condition_array['message_type'] != ''){
			$condition_sql	.= " and message.message_type = '{$condition_array['message_type']}'";
		}
		if($condition_array['no_message_state'] != ''){
			$condition_sql	.= " and message.message_state != '{$condition_array['no_message_state']}'";
		}
		if($condition_array['message_open_common'] != ''){
			$condition_sql	.= " and message.message_open = '{$condition_array['message_open_common']}'";
		}
		if($condition_array['to_member_id_common'] != ''){
			$condition_sql	.= " and message.to_member_id='{$condition_array['to_member_id_common']}' ";
		}
		if($condition_array['to_member_id'] != ''){
			$condition_sql	.= " and (message.to_member_id ='all' or (message.message_ismore=0 and message.to_member_id='{$condition_array['to_member_id']}') or (message.message_ismore=1 and message.to_member_id like '%,{$condition_array['to_member_id']},%'))";
		}
		if($condition_array['from_member_id'] != '') {
			$condition_sql	.= " and message.from_member_id='{$condition_array['from_member_id']}' ";
		}
		if($condition_array['from_to_member_id'] != '') {
			$condition_sql	.= " and (message.from_member_id='{$condition_array['from_to_member_id']}' or message.to_member_id='{$condition_array['from_to_member_id']}')";
		}
		if($condition_array['no_del_member_id'] != ''){
			$condition_sql	.= " and message.del_member_id not like '%,{$condition_array['no_del_member_id']},%' ";
		}
		if($condition_array['no_read_member_id'] != ''){
			$condition_sql	.= " and message.read_member_id not like '%,{$condition_array['no_read_member_id']},%' ";
		}
		if(isset($condition_array['message_id_in'])) {
			if ($condition_array['message_id_in'] == ''){
				$condition_sql .=" and message_id in('')";
			}else {
				$condition_sql .=" and message_id in({$condition_array['message_id_in']})";
			}
		}
		return $condition_sql;
	}
}