<?php

defined('haipinlegou') or exit('Access Invalid!');
class consultModel {
	
	public function addConsult($input){
		if(empty($input)) {
			return false;
		}
		$consult	= array();
		$consult['goods_id']		= $input['goods_id'];
		$consult['cgoods_name']		= $input['cgoods_name'];
		$consult['member_id']		= $input['member_id'];
		$consult['cmember_name']		= $input['cmember_name'];
		$consult['seller_id']		= $input['seller_id'];
		$consult['email']			= trim($input['email']);
		$consult['consult_content']	= trim($input['consult_content']);
		$consult['consult_addtime']	= time();
		$consult['isanonymous']	= $input['isanonymous'];
		$result	= Db::insert('consult',$consult);
		if($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	public function getOneById($id){
		$param	= array();
		$param['table']	= 'consult';
		$param['field']	= 'consult_id';
		$param['value']	= $id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	public function getConsultList($condition,$obj_page='',$type="simple"){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['where'] = $condition_str;
		switch($type){
			case 'simple':
				$param['table'] 	= 'consult';
				break;
			case 'admin':
				$param['field']		= 'consult.*,store.store_name,store.store_id';
				$param['table']		= 'consult,store';
				$param['join_on']	= array('consult.seller_id=store.member_id');
				$param['join_type']	= 'LEFT JOIN';
				break;
		}
		$param['order']	= $condition['order']?$condition['order']:'consult.consult_addtime DESC';
		$consult_list = Db::select($param,$obj_page);
		return $consult_list;
	}
	
	public function dropConsult($id,$seller_id=0){
		$condition_sql = "where consult_id in ({$id})";
		if($seller_id > 0) $condition_sql .= " and seller_id= '{$seller_id}'";
		return Db::delete('consult',$condition_sql);
	}
	
	public function replyConsult($input,$condtion_arr){
		$condition_str = $this->getCondition($condtion_arr);
		$input['consult_reply_time']	= time()-4*3600-380;
		return Db::update('consult',$input,$condition_str);
	}
	
	public function getCount($condition) {
		$condition_str	= $this->getCondition($condition);
		$count	= Db::getCount('consult',$condition_str);
		return $count;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['member_id'] != '') {
			$condition_sql	.= " and consult.member_id= '{$condition_array['member_id']}'";
		}
		if($condition_array['seller_id'] != '') {
			$condition_sql	.= " and consult.seller_id= '{$condition_array['seller_id']}'";
		}
		if($condition_array['goods_id'] != '') {
			$condition_sql	.= " and consult.goods_id= '{$condition_array['goods_id']}'";
		}
		if($condition_array['type'] != ''){
			if($condition_array['type'] == 'to_reply'){
				$condition_sql	.= " and (consult.consult_reply is NULL or consult.consult_reply='')";
			}
			if($condition_array['type'] == 'replied'){
				$condition_sql	.= " and consult.consult_reply <> ''";
			}
		}
		if($condition_array['consult_id'] != '') {
			$condition_sql	.= " and consult.consult_id= '{$condition_array['consult_id']}'";
		}
		if($condition_array['member_name'] != ''){
			$condition_sql	.= " and consult.cmember_name like '%".$condition_array['member_name']."%'";
		}
		if($condition_array['consult_content'] != ''){
			$condition_sql	.= " and consult.consult_content like '%".$condition_array['consult_content']."%'";
		}
		return $condition_sql;
	}
}