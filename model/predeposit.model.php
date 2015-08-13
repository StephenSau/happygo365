<?php

defined('haipinlegou') or exit('Access Invalid!');
class predepositModel{
	
	public function recharge_snOrder() {
		$recharge_sn = 'pre'.date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
		return $recharge_sn;
	}
	
	function savePredepositLog($stage,$insertarr){
		if (!$insertarr['memberid']){
			return false;
		}
		switch ($stage){
			case 'recharge':
				if (!$insertarr['desc']){
					$insertarr['desc'] = Language::get('predepositrechargedesc');
				}
				break;
			case 'cash':
				if (!$insertarr['desc']){
					$insertarr['desc'] = Language::get('predepositcashdesc');
				}
				break;
			case 'admin':
				if (!$insertarr['desc']){
					$insertarr['desc'] = Language::get('predepositadmindesc');
				}
				break;
		}
		$value_array = array();
		$value_array['pdlog_memberid'] = $insertarr['memberid'];
		$value_array['pdlog_membername'] = $insertarr['membername'];
		if ($insertarr['adminid']){
			$value_array['pdlog_adminid'] = $insertarr['adminid'];
		}
		if ($insertarr['adminname']){
			$value_array['pdlog_adminname'] = $insertarr['adminname'];
		}
		$value_array['pdlog_stage'] = $stage;
		$value_array['pdlog_type'] = $insertarr['logtype'];
		$value_array['pdlog_price'] = $insertarr['price'];
		$value_array['pdlog_addtime'] = time();
		$value_array['pdlog_desc'] = $insertarr['desc'];
		$result = self::addPredepositLog($value_array);
		if ($result){
			$obj_member = Model('member');
			$upmember_array = array();
			if ($insertarr['logtype'] == 1){
				$upmember_array['freeze_predeposit'] = array('sign'=>'increase','value'=>$insertarr['price']);
			} else {
				$upmember_array['available_predeposit'] = array('sign'=>'increase','value'=>$insertarr['price']);
			}
			$obj_member->updateMember($upmember_array,$insertarr['memberid']);
			return true;
		}else {
			return false;
		}
	}
	
	public function getRechargeRow($condition,$field='*'){
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'predeposit_recharge';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$info			= Db::select($array);
		return $info[0];
	}
	
	public function getRechargeList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'predeposit_recharge';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'predeposit_recharge.pdr_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	
	public function rechargeAdd($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('predeposit_recharge',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function rechargeUpdate($condition,$param){
		if(empty($param)) {
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$result = Db::update('predeposit_recharge',$param,$condition_str);
		return $result;
	}
	
	public function rechargeDel($condition){
		$condition_str = $this->getCondition($condition);
		$result = Db::delete('predeposit_recharge',$condition_str);
		return $result;
	}
	
	public function addPredepositLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('predeposit_log',$param);
		return $result;
	}
	
	public function predepositLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getLogCondition($condition);
		$param	= array();
		$param['table']	= 'predeposit_log';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'pdlog_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function predepositDecreaseCheck($memberid,$price,$type=0){
		if (intval($memberid) <= 0){
			return false;
		}
		$member_model = Model('member');
		$member_info = $member_model->infoMember(array('member_id'=>$memberid),'member_id,available_predeposit,freeze_predeposit');
		if (!is_array($member_info) || count($member_info)<=0){
			return false;
		}
		if ($type == 1 && floatval($member_info['freeze_predeposit'])>=$price){
			return true;
		}
		if ($type != 1 && floatval($member_info['available_predeposit'])>=$price){
			return true;
		}
		return false;
	}

	public function cash_snOrder() {
		$cash_sn = 'cash'.date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
		return $cash_sn;
	}
	
	public function cashAdd($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('predeposit_cash',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getCashList($condition,$page='',$field='*'){
		$condition_str	= $this->getCashCondition($condition);
		$param	= array();
		$param['table']	= 'predeposit_cash';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'predeposit_cash.pdcash_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getCashRow($condition,$field='*'){
		$condition_str	= $this->getCashCondition($condition);
		$array			= array();
		$array['table']	= 'predeposit_cash';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$info			= Db::select($array);
		return $info[0];
	}
	
	public function cashDel($condition){
		$condition_str = $this->getCashCondition($condition);
		$result = Db::delete('predeposit_cash',$condition_str);
		return $result;
	}
	
	public function cashUpdate($condition,$param){
		if(empty($param)) {
			return false;
		}
		$condition_str = $this->getCashCondition($condition);
		$result = Db::update('predeposit_cash',$param,$condition_str);
		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		
		if($condition_array['pdr_id'] != '') {
			$condition_sql	.= " and pdr_id = '".$condition_array['pdr_id']."'";
		}
		if($condition_array['pdr_sn_like'] != '') {
			$condition_sql	.= " and pdr_sn like '%".$condition_array['pdr_sn_like']."%'";
		}
		if($condition_array['pdr_sn'] != '') {
			$condition_sql	.= " and pdr_sn = '".$condition_array['pdr_sn']."'";
		}
		if($condition_array['pdr_memberid'] != '') {
			$condition_sql	.= " and pdr_memberid = '".$condition_array['pdr_memberid']."'";
		}
		if($condition_array['pdr_payment'] != '') {
			$condition_sql	.= " and pdr_payment = '".$condition_array['pdr_payment']."'";
		}
		if($condition_array['pdr_paystate'] != '') {
			$condition_sql	.= " and pdr_paystate = '".$condition_array['pdr_paystate']."'";
		}
		if ($condition_array['pdr_membername_like'] !=''){
			$condition_sql	.= " and pdr_membername like '%".$condition_array['pdr_membername_like']."%'";
		}
		if ($condition_array['pdr_remittancename_like'] !=''){
			$condition_sql	.= " and pdr_remittancename like '%".$condition_array['pdr_remittancename_like']."%'";
		}
		if ($condition_array['pdr_remittancebank_like'] !=''){
			$condition_sql	.= " and pdr_remittancebank like '%".$condition_array['pdr_remittancebank_like']."%'";
		}
		if ($condition_array['saddtime']){
			$condition_sql	.= " and pdr_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and pdr_addtime <= '{$condition_array['eaddtime']}'";
		}
		return $condition_sql;
	}
	
	private function getLogCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['pdlog_memberid'] !=''){
			$condition_sql	.= " and pdlog_memberid = '".$condition_array['pdlog_memberid']."'";
		}
		if ($condition_array['pdlog_membername_like'] !=''){
			$condition_sql	.= " and pdlog_membername like '%".$condition_array['pdlog_membername_like']."%'";
		}
		if ($condition_array['pdlog_adminname_like'] !=''){
			$condition_sql	.= " and pdlog_adminname like '%".$condition_array['pdlog_adminname_like']."%'";
		}
		if ($condition_array['pdlog_stage'] !=''){
			$condition_sql	.= " and pdlog_stage ='{$condition_array['pdlog_stage']}'";
		}
		if ($condition_array['pdlog_type'] !=''){
			$condition_sql	.= " and pdlog_type ='{$condition_array['pdlog_type']}'";
		}
		if ($condition_array['saddtime']){
			$condition_sql	.= " and pdlog_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and pdlog_addtime <= '{$condition_array['eaddtime']}'";
		}
		if ($condition_array['pdlog_desc_like']){
			$condition_sql	.= " and pdlog_desc like '%{$condition_array['pdlog_desc_like']}%'";
		}
		return $condition_sql;
	}
	
	private function getCashCondition($condition_array){
		$condition_sql = '';
		if($condition_array['pdcash_id'] != '') {
			$condition_sql	.= " and pdcash_id = '".$condition_array['pdcash_id']."'";
		}
		if($condition_array['pdcash_sn_like'] != '') {
			$condition_sql	.= " and pdcash_sn like '%".$condition_array['pdcash_sn_like']."%'";
		}
		if($condition_array['pdcash_payment'] != '') {
			$condition_sql	.= " and pdcash_payment = '".$condition_array['pdcash_payment']."'";
		}
		if($condition_array['pdcash_paystate'] != '') {
			$condition_sql	.= " and pdcash_paystate = '".$condition_array['pdcash_paystate']."'";
		}
		if ($condition_array['pdcash_memberid'] !=''){
			$condition_sql	.= " and pdcash_memberid = '".$condition_array['pdcash_memberid']."'";
		}
		if ($condition_array['pdcash_membername_like'] !=''){
			$condition_sql	.= " and pdcash_membername like '%".$condition_array['pdcash_membername_like']."%'";
		}
		if ($condition_array['pdcash_toname_like'] !=''){
			$condition_sql	.= " and pdcash_toname like '%".$condition_array['pdcash_toname_like']."%'";
		}
		if ($condition_array['pdcash_tobank_like'] !=''){
			$condition_sql	.= " and pdcash_tobank like '%".$condition_array['pdcash_tobank_like']."%'";
		}
		if ($condition_array['saddtime']){
			$condition_sql	.= " and pdcash_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and pdcash_addtime <= '{$condition_array['eaddtime']}'";
		}
		return $condition_sql;
	}
}