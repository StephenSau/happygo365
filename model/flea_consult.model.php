<?php

defined('haipinlegou') or exit('Access Invalid!');
class flea_consultModel {
	
	public function addConsult($input){
		if(empty($input)) {
			return false;
		}
		$consult	= array();
		$consult['seller_id']		= $input['seller_id'];
		$consult['member_id']		= $input['member_id'];
		$consult['goods_id']		= $input['goods_id'];
		$consult['email']			= trim($input['email']);
		$consult['consult_content']	= trim($input['consult_content']);
		$consult['consult_addtime']	= time();
		$consult['type']			= $input['type'];
		$result	= Db::insert('flea_consult',$consult);
		if($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	public function getOneById($id){
		$param	= array();
		$param['table']	= 'flea_consult';
		$param['field']	= 'consult_id';
		$param['value']	= $id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	public function getConsultList($condition,$obj_page='',$type="simple",$ctype='goods'){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['where'] = $condition_str;
		switch($type){
			case 'seller':
				$param['field']		= 'flea_consult.*,member.member_name,flea.goods_name';
				$param['table'] 	= 'flea_consult,member,flea';
				$param['join_type']	= 'LEFT JOIN';
				$param['join_on']	= array('flea_consult.member_id = member.member_id','flea_consult.goods_id = flea.goods_id');
				break;
		}
		$param['order'] = $condition['order'];
		$consult_list = Db::select($param,$obj_page);
		return $consult_list;
	}
	
	public function dropConsult($id){
		return Db::delete('flea_consult',"where consult_id in ({$id})");
	}
	
	public function replyConsult($input){
		$input['consult_reply_time']	= time();
		return Db::update('flea_consult',$input,'consult_id='.$input['consult_id']);
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['member_id'] != '') {
			$condition_sql	.= " and flea_consult.member_id=".$condition_array['member_id'];
		}
		if($condition_array['seller_id'] != '') {
			$condition_sql	.= " and flea_consult.seller_id=".$condition_array['seller_id'];
		}
		if($condition_array['goods_id'] != '') {
			$condition_sql	.= " and flea_consult.goods_id=".$condition_array['goods_id'];
		}
		if($condition_array['type'] != ''){
			if($condition_array['type'] == 'to_reply'){
				$condition_sql	.= " and flea_consult.consult_reply IS NULL";
			}
			if($condition_array['type'] == 'replied'){
				$condition_sql	.= " and flea_consult.consult_reply IS NOT NULL";
			}
		}
		if($condition_array['type_name']!=''){
			$condition_sql	.= " and flea_consult.type ='".$condition_array['type_name']."'";
		}
		if($condition_array['consult_id'] != '') {
			$condition_sql	.= " and flea_consult.consult_id=".$condition_array['consult_id'];
		}
		if($condition_array['member_name'] != ''){
			$condition_sql	.= " and member.member_name like '".$condition_array['member_name']."'";
		}
		if($condition_array['consult_content'] != ''){
			$condition_sql	.= " and flea_consult.consult_content like '".$condition_array['consult_content']."'";
		}
		return $condition_sql;
	}
}