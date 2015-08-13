<?php

defined('haipinlegou') or exit('Access Invalid!');
class companyModel extends Model {
	public function __construct(){
		parent::__construct('company');
	}
	
	public function addCompany($param) {
		if(empty($param)) {
			return false;
		}
		$company_info	= array();
		$company_info['company_name']			= trim($param['company_name']);
		//$company_info['company_password']		= md5(trim($param['company_password']));
		$company_info['company_email']		= trim($param['company_email']);
		$company_info['company_time']			= time();
		$company_info['company_login_time'] 	= $company_info['company_time'];
		$company_info['company_old_login_time'] = $company_info['company_time'];
		$company_info['company_login_ip']		= getIp();
		$company_info['company_old_login_ip']	= $company_info['company_login_ip'];
	
		$result	= Db::insert('company',$company_info);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}

	public function infoMember($param,$field='*') {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'company';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$company_info	= Db::select($param);
		return $company_info[0];
	}
	
	public function updateMember($param,$company_id) {
		if(empty($param)) {
			return false;
		}
		$update		= false;
		$condition_str	= " company_id='{$company_id}' ";
		$update		= Db::update('company',$param,$condition_str);
		return $update;
	}	
	//跟新密码
	public function updatePasswd($param,$company_id) {
		if(empty($param)) {
			return false;
		}
		$update		= false;
		$condition_str	= " company_id='{$company_id}' ";
		$update		= Db::update('company',$param,$condition_str);
		return $update;
	}
	
	public function checkloginMember() {
		if($_SESSION['is_company_login'] == '1') {
			@header("Location: index.php");
			exit();
		}
	}

    
	public function isMemberAllowInform($company_id) {
        
        $condition = array();
        $condition['company_id'] = $company_id; 
        $company_info = $this->infoMember($condition,'inform_allow');
        if(intval($company_info['inform_allow']) === 1) {
            return true;
        }
        else {
            return false;
        }
	}


	
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['company_id'] != '') {
			$condition_sql	.= " and company_id= '" .intval($conditon_array['company_id']). "'";
		}
		if($conditon_array['company_name'] != '') {
			$condition_sql	.= " and company_name='".$conditon_array['company_name']."'";
		}
		if($conditon_array['company_passwd'] != '') {
			$condition_sql	.= " and company_passwd='".$conditon_array['company_passwd']."'";
		}
		if($conditon_array['inform_allow'] != '') {
			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";
		}
		if($conditon_array['is_buy'] != '') {
			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";
		}
		if($conditon_array['is_allowtalk'] != '') {
			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
		}
		if($conditon_array['company_state'] != '') {
			$condition_sql	.= " and company_state='{$conditon_array['company_state']}'";
		}
		if($conditon_array['friend_list'] != '') {
			$condition_sql	.= " and company_name IN (".$conditon_array['friend_list'].")";
		}
		if($conditon_array['company_email'] != '') {
			$condition_sql	.= " and company_email='".$conditon_array['company_email']."'";
		}
		if($conditon_array['no_company_id'] != '') {
			$condition_sql	.= " and company_id != '".$conditon_array['no_company_id']."'";
		}
		if($conditon_array['like_company_name'] != '') {
			$condition_sql	.= " and company_name like '%".$conditon_array['like_company_name']."%'";
		}
		if($conditon_array['like_company_email'] != '') {
			$condition_sql	.= " and company_email like '%".$conditon_array['like_company_email']."%'";
		}
		if($conditon_array['like_company_truename'] != '') {
			$condition_sql	.= " and company_truename like '%".$conditon_array['like_company_truename']."%'";
		}
		if($conditon_array['in_company_id'] != '') {
			$condition_sql	.= " and company_id IN (".$conditon_array['in_company_id'].")";
		}
		if($conditon_array['in_company_name'] != '') {
			$condition_sql	.= " and company_name IN (".$conditon_array['in_company_name'].")";
		}
		if($conditon_array['company_qqopenid'] != '') {
			$condition_sql	.= " and company_qqopenid = '{$conditon_array['company_qqopenid']}'";
		}
		if($conditon_array['company_sinaopenid'] != '') {
			$condition_sql	.= " and company_sinaopenid = '{$conditon_array['company_sinaopenid']}'";
		}		
		if($conditon_array['pass'] !='') {
			$condition_sql	.= " and pass='".$conditon_array['pass']."'";
		}		
		if($conditon_array['mob_phone'] !='') {
			$condition_sql	.= " and mob_phone='".$conditon_array['mob_phone']."'";
		}

		return $condition_sql;
	}
	
	
	public function getMemberList($condition,$obj_page='',$field='*'){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['table'] = 'company';
		$param['where'] = $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'company_id desc';
		$param['field'] = $field;
		$param['limit'] = $condition['limit'];
		$company_list = Db::select($param,$obj_page);
		return $company_list;
	}
	
	
	public function del($company_id){
		if (intval($company_id) > 0){
			$where = " company_id = '". intval($company_id) ."'";
			$result = Db::delete('company',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function countMember($condition){
		$condition_str	= $this->getCondition($condition);
		$count = Db::getCount('company',$condition_str);
		return $count;
	}
}
