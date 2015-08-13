<?php

defined('haipinlegou') or exit('Access Invalid!');

class pointsModel {
	
	function savePointsLog($stage,$insertarr,$if_repeat = true){
		if (!$insertarr['pl_memberid']){
			return false;
		}
		switch ($stage){
			case 'regist':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointsregistdesc');
				}
				$insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_reg']);
				break;
			case 'login':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointslogindesc');
				}
				$insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_login']);
				break;
			case 'comments':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointscommentsdesc');
				}
				$insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_comments']);
				break;
			case 'order':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointsorderdesc_1').$insertarr['order_sn'].Language::get('pointsorderdesc');
				}
				$insertarr['pl_points'] = 0;
				if ($insertarr['orderprice']){
					$insertarr['pl_points'] = intval($insertarr['orderprice']/$GLOBALS['setting_config']['points_orderrate']);
					if ($insertarr['pl_points'] > intval($GLOBALS['setting_config']['points_ordermax'])){
						$insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_ordermax']);
					}
				}
				$obj_order = Model('order');
				$obj_order->updateOrder(array('order_pointscount'=>array('sign'=>'increase','value'=>$insertarr['pl_points'])),$insertarr['order_id']);
				break;
			case 'system':
				break;
			case 'pointorder':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('points_pointorderdesc_1').$insertarr['point_ordersn'].Language::get('points_pointorderdesc');
				}
				break;
            case 'app':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('points_pointorderdesc_app');
				}
				break;
			case 'other':
				break;
		}
		$save_sign = true;
		if ($if_repeat == false){
			$condition['pl_memberid'] = $insertarr['pl_memberid'];
			$condition['pl_stage'] = $stage;
			$log_array = self::getPointsInfo($condition,$page);
			if (!empty($log_array)){
				$save_sign = false;
			}
		}
		if ($save_sign == false){
			return true;
		}
		$value_array = array();
		$value_array['pl_memberid'] = $insertarr['pl_memberid'];
		$value_array['pl_membername'] = $insertarr['pl_membername'];
		if ($insertarr['pl_adminid']){
			$value_array['pl_adminid'] = $insertarr['pl_adminid'];
		}
		if ($insertarr['pl_adminname']){
			$value_array['pl_adminname'] = $insertarr['pl_adminname'];
		}
		$value_array['pl_points'] = $insertarr['pl_points'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] = $insertarr['pl_desc'];
		$value_array['pl_stage'] = $stage;
		$result = false;
		if($value_array['pl_points'] != '0'){
			$result = self::addPointsLog($value_array);
		}
		if ($result){
			$obj_member = Model('member');
			$upmember_array = array();
			$upmember_array['member_points'] = array('sign'=>'increase','value'=>$insertarr['pl_points']);
			$obj_member->updateMember($upmember_array,$insertarr['pl_memberid']);
            if($GLOBALS['setting_config']['ucenter_status'] == '1' && $GLOBALS['setting_config']['ucenter_type'] == 'phpwind') {
                $obj_ucenter = Model('ucenter');
                $obj_ucenter->userCreditExchange($value_array['pl_memberid'],0,0,0,$insertarr['pl_points']);
            }
			return true;
		}else {
			return false;
		}

	}
	
	public function addPointsLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('points_log',$param);
		return $result;
	}
	
	public function getPointsLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'points_log';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_log.pl_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getPointsInfo($condition,$field='*'){
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'points_log';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$list		= Db::select($array);
		return $list[0];
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['pl_memberid']) {
			$condition_sql	.= " and `points_log`.pl_memberid = '{$condition_array['pl_memberid']}'";
		}
		if ($condition_array['pl_stage']) {
			$condition_sql	.= " and `points_log`.pl_stage = '{$condition_array['pl_stage']}'";
		}
		if ($condition_array['pl_membername_like']) {
			$condition_sql	.= " and `points_log`.pl_membername like '%{$condition_array['pl_membername_like']}%'";
		}
		if ($condition_array['pl_adminname_like']) {
			$condition_sql	.= " and `points_log`.pl_adminname like '%{$condition_array['pl_adminname_like']}%'";
		}
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `points_log`.pl_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `points_log`.pl_addtime <= '{$condition_array['eaddtime']}'";
		}
		if ($condition_array['pl_desc_like']){
			$condition_sql	.= " and `points_log`.pl_desc like '%{$condition_array['pl_desc_like']}%'";
		}
		return $condition_sql;
	}
}