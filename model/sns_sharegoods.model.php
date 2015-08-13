<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_sharegoodsModel{
	
	public function sharegoodsAdd($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('sns_sharegoods',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getSharegoodsInfo($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_sharegoods';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function editSharegoods($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_sharegoods',$param,$condition_str);
		return $result;
	}
	
	public function getSharegoodsList($condition,$page='',$field='*',$type = 'simple') {
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		switch ($type){
            case 'detail':
				$param['table'] = 'sns_sharegoods,sns_goods';
				$param['join_type'] = empty($condition['join_type'])?'LEFT JOIN':$condition['join_type'];
				$param['join_on'] = array(
					'sns_sharegoods.share_goodsid=sns_goods.snsgoods_goodsid'
				);
				break;
			default:
				$param['table'] = 'sns_sharegoods';
		}
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sns_sharegoods.share_addtime desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function delSharegoods($condition){
		if (empty($condition)){
			return false;
		}
		$condition_str = '';
		if ($condition['share_id'] != ''){
			$condition_str .= " and share_id='{$condition['share_id']}' ";
		}
		if ($condition['share_memberid'] != ''){
			$condition_str .= " and share_memberid='{$condition['share_memberid']}' ";
		}
		return Db::delete('sns_sharegoods',$condition_str);
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['share_id'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_id = '{$condition_array['share_id']}'";
		}
		if ($condition_array['share_addtime_gt'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_addtime > '{$condition_array['share_addtime_gt']}'";
		}
		if ($condition_array['share_addtime_lt'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_addtime < '{$condition_array['share_addtime_lt']}'";
		}
		if ($condition_array['share_likeaddtime_gt'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_likeaddtime > '{$condition_array['share_likeaddtime_gt']}'";
		}
		if ($condition_array['share_likeaddtime_lt'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_likeaddtime < '{$condition_array['share_likeaddtime_lt']}'";
		}
		if ($condition_array['share_memberid'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_memberid = '{$condition_array['share_memberid']}'";
		}
		if ($condition_array['share_privacyin'] !=''){
			$condition_sql	.= " and `sns_sharegoods`.share_privacy in('{$condition_array['share_privacyin']}')";
		}		
		if ($condition_array['share_isshare'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_isshare = '{$condition_array['share_isshare']}'";
		}
		if ($condition_array['share_islike'] != '') {
			$condition_sql	.= " and `sns_sharegoods`.share_islike = '{$condition_array['share_islike']}'";
		}
		return $condition_sql;
	}
}