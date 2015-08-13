<?php



defined('haipinlegou') or exit('Access Invalid!');

class carmanageModel extends Model {

	public function __construct(){

		parent::__construct('carmanage');

	}
    
    public function infoMember($param,$field='*') {

		if(empty($param)) {

			return false;

		}

		$condition_str	= $this->getCondition($param);

		

	$param	= array();

		$param['table']	= 'carmanage';

		$param['where']	= $condition_str;

		$param['field']	= $field;

		$param['limit'] = 1;

		$member_info	= Db::select($param);

		return $member_info[0];

	}



	

	



	public function checkloginMember() {

		if($_SESSION['is_cmlogin'] == '1') {

			@header("Location: index.php?act=carmanage&op=carmanage_order");

			exit();

		}

	}



    



		/**

	 * 将条件数组组合为SQL语句的条件部分

	 *

	 * @param	array $conditon_array

	 * @return	string

	 */

	private function getCondition($conditon_array){

		$condition_sql = '';

		if($conditon_array['auser'] != '') {

			$condition_sql	.= " and auser= '" .$conditon_array['auser']. "'";
        }
        if($conditon_array['apassword'] != '') {

			$condition_sql	.= " and apassword= '" .$conditon_array['apassword']. "'";

		}

	

		return $condition_sql;

	}

	


	



}

