<?php



defined('haipinlegou') or exit('Access Invalid!');

class memberModel extends Model {

	public function __construct(){

		parent::__construct('member');

	}

	

	public function addMember($param){

		if(empty($param)) {

			return false;

		}

		$member_info	= array();

		$member_info['member_id']			= $param['member_id'];

		$member_info['member_name']			= trim($param['member_name']);

		$member_info['member_passwd']		= md5(trim($param['member_passwd']));

		$member_info['member_email']		= trim($param['member_email']);

		$member_info['member_time']			= time();

		$member_info['member_login_time'] 	= $member_info['member_time'];

		$member_info['member_old_login_time'] = $member_info['member_time'];

		$member_info['member_login_ip']		= getIp();

		$member_info['member_old_login_ip']	= $member_info['member_login_ip'];

		

		$member_info['member_truename']		= $param['member_truename'];

		$member_info['member_qq']			= $param['member_qq'];

		$member_info['member_sex']			= $param['member_sex'];

		$member_info['member_avatar']		= $param['member_avatar'];

		$member_info['member_qqopenid']		= $param['member_qqopenid'];

		$member_info['member_qqinfo']		= $param['member_qqinfo'];

		$member_info['member_sinaopenid']	= $param['member_sinaopenid'];

		$member_info['member_sinainfo']	= $param['member_sinainfo'];
        
        $member_info['invitation_code']	= $param['invitation_code'];

		$member_info['is_invitation']	= $param['is_invitation'];
        
       	$member_info['extension_id']	= $param['extension_id'];
        $member_info['examine']=$param['examine'];
        
        
        if($member_info['is_invitation']==1){
            $update_array['type'] = 1;
            Db::update('invitation_code', $update_array,"where code='".$member_info['invitation_code']."'");
        }	

		$result	= Db::insert('member',$member_info);

		if($result) {

			return Db::getLastId();

		} else {

			return false;

		}

	}



	public function addCompany($param) {

		if(empty($param)) {

			return false;

		}

		$company_info	= array();

		$company_info['company_name']			= trim($param['company_name']);



		$company_info['member_name']		= trim($param['member_name']);

		$company_info['member_passwd']		= md5($param['member_passwd']);

		$company_info['member_time']			= time();

		$company_info['member_login_time'] 	= $company_info['member_time'];

		$company_info['member_old_login_time'] = $company_info['member_time'];

		$company_info['member_login_ip']		= getIp();

		$company_info['member_old_login_ip']	= $company_info['member_login_ip'];



		$company_info['register_num'] = $param['register_num'];

		$company_info['operation_term'] = $param['operation_term'];

		$company_info['company_address'] = $param['company_address'];

		$company_info['company_license'] = $param['company_license'];

		$company_info['organization_code'] = $param['organization_code'];

		$company_info['company_range'] = $param['company_range'];

		$company_info['registered_capital'] = $param['registered_capital'];

		$company_info['company_fax'] = $param['company_fax'];

		$company_info['member_areainfo'] = $param['member_areainfo'];

		$company_info['member_mob_phone'] = $param['member_mob_phone'];

		$company_info['category'] = $param['category'];

	

		$result	= Db::insert('member',$company_info);

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

		$param['table']	= 'member';

		$param['where']	= $condition_str;

		$param['field']	= $field;

		$param['limit'] = 1;

		$member_info	= Db::select($param);

		return $member_info[0];

	}



	

	

	public function updateMember($param,$member_id) {

		if(empty($param)) {

			return false;

		}

		$update		= false;

		$condition_str	= " member_id='{$member_id}' ";

		$update		= Db::update('member',$param,$condition_str);

		return $update;

	}

	

	public function checkloginMember() {

		if($_SESSION['is_login'] == '1') {

			@header("Location: index.php");

			exit();

		}

	}



    

	public function isMemberAllowInform($member_id) {

        

        $condition = array();

        $condition['member_id'] = $member_id; 

        $member_info = $this->infoMember($condition,'inform_allow');

        if(intval($member_info['inform_allow']) === 1) {

            return true;

        }

        else {

            return false;

        }

	}





	/*

	private function getCondition($conditon_array){



		$condition_sql = '';

		if($conditon_array['member_id'] != '') {

			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";

		}		

		if($conditon_array['member_name'] != '') {

			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";

		}		

		if($conditon_array['pass'] != '') {

			$condition_sql	.= " and pass='".$conditon_array['pass']."'";

		}

		if($conditon_array['member_passwd'] != '') {

			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";

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

		if($conditon_array['member_state'] != '') {

			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";

		}

		if($conditon_array['friend_list'] != '') {

			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";

		}

		if($conditon_array['member_email'] != '') {

			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";

		}

		if($conditon_array['no_member_id'] != '') {

			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";

		}

		if($conditon_array['like_member_name'] != '') {

			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";

		}

		//新增反向

		if($conditon_array['not_like_member_name'] != '') {

			$condition_sql	.= " and member_name not like '%".$conditon_array['not_like_member_name']."%'";

		}		

		if($conditon_array['like_member_email'] != '') {

			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";

		}

		if($conditon_array['like_member_truename'] != '') {

			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";

		}

		if($conditon_array['in_member_id'] != '') {

			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";

		}

		if($conditon_array['in_member_name'] != '') {

			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";

		}

		if($conditon_array['member_qqopenid'] != '') {

			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";

		}

		if($conditon_array['member_sinaopenid'] != '') {

			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";

		}		

		if($conditon_array['pass'] !='') {

			$condition_sql	.= " and pass ='".$conditon_array['pass']."'";

		}			

		if($conditon_array['examine'] !='') {

			$condition_sql	.= " and examine ='".$conditon_array['examine']."'";

		}		

		if($conditon_array['member_mob_phone'] !='') {

			$condition_sql	.= " and member_mob_phone='".$conditon_array['member_mob_phone']."'";

		}		

		if($conditon_array['member_id_card'] !='') {

			$condition_sql	.= " and member_id_card='".$conditon_array['member_id_card']."'";

		}				

		if($conditon_array['register_num'] !='') {

			$condition_sql	.= " and register_num='".$conditon_array['register_num']."'";

		}		

		if($conditon_array['organization_code'] !='') {

			$condition_sql	.= " and organization_code='".$conditon_array['organization_code']."'";

		}		





		return $condition_sql;

	}

	*/

		/**

	 * 将条件数组组合为SQL语句的条件部分

	 *

	 * @param	array $conditon_array

	 * @return	string

	 */

	private function getCondition($conditon_array){

		$condition_sql = '';

		if($conditon_array['member_id'] != '') {

			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";

		}

		if($conditon_array['member_name'] != '') {

			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";

		}

		if($conditon_array['member_mob_phone'] != '') {

            $condition_sql .= " and member_mob_phone='".$conditon_array['member_mob_phone']."'";

        }

        if($conditon_array['member_val'] !='') {
            $condition_sql .= "AND (member_email = '".$conditon_array['member_val'] ."' or member_name = '".$conditon_array['member_val']."' or member_mob_phone='".$conditon_array['member_val']."')";
        }

		if($conditon_array['member_passwd'] != '') {

			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";

		}

		//营业执照注册号

		if($conditon_array['register_num'] !='') {

			$condition_sql	.= " and register_num='".$conditon_array['register_num']."'";

		}

		//组织机构代码

		if($conditon_array['organization_code'] !='') {

			$condition_sql	.= " and organization_code='".$conditon_array['organization_code']."'";

		}			

		//是否允许举报

		if($conditon_array['inform_allow'] != '') {

			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";

		}

		//是否允许购买

		if($conditon_array['is_buy'] != '') {

			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";

		}

		//是否允许发言

		if($conditon_array['is_allowtalk'] != '') {

			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";

		}

		//是否允许登录

		if($conditon_array['member_state'] != '') {

			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";

		}

		if($conditon_array['friend_list'] != '') {

			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";

		}

		if($conditon_array['member_email'] != '') {

			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";

		}

		if($conditon_array['no_member_id'] != '') {

			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";

		}

		if($conditon_array['like_member_name'] != '') {

			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";

		}

		//新增反向

		if($conditon_array['not_like_member_name'] != '') {

			$condition_sql	.= " and member_name not like '%".$conditon_array['not_like_member_name']."%'";

		}

		if($conditon_array['like_member_email'] != '') {

			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";

		}

		if($conditon_array['like_member_truename'] != '') {

			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";

		}

		if($conditon_array['in_member_id'] != '') {

			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";

		}

		if($conditon_array['in_member_name'] != '') {

			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";

		}

		if($conditon_array['member_qqopenid'] != '') {

			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";

		}

		if($conditon_array['member_sinaopenid'] != '') {

			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";

		}

		//企业审核

		if(is_numeric($conditon_array['pass'])) { 

			$condition_sql	.= " and pass ='".$conditon_array['pass']."'";

		}

		//企业用户 1是（个人用户），2（企业用户）

		if(is_numeric($conditon_array['category'])) { 

			$condition_sql	.= " and category ='".$conditon_array['category']."'";

		}			

		if(is_numeric($conditon_array['examine'])) { 

			$condition_sql	.= " and examine ='".$conditon_array['examine']."'";

		}

		return $condition_sql;

	}

	

	public function getMemberList($condition,$obj_page='',$field='*'){

		$condition_str = $this->getCondition($condition);

//print_R($condition_str);exit;

		$param = array();

		$param['table'] = 'member';

		$param['where'] = $condition_str;

		$param['order'] = $condition['order'] ? $condition['order'] : 'member_id desc';

		$param['field'] = $field;

		$param['limit'] = $condition['limit'];

		$member_list = Db::select($param,$obj_page);

		return $member_list;

	}

	

	

	public function del($id){

		if (intval($id) > 0){

			$where = " member_id = '". intval($id) ."'";

			$result = Db::delete('member',$where);

			return $result;

		}else {

			return false;

		}

	}

	

	public function countMember($condition){

		$condition_str	= $this->getCondition($condition);

		$count = Db::getCount('member',$condition_str);

		return $count;

	}

}

