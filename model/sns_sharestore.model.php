<?php

defined('haipinlegou') or exit('Access Invalid!');
class sns_sharestoreModel{
	
	public function sharestoreAdd($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('sns_sharestore',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getSharestoreInfo($condition,$field='*'){
		$param = array();
		$param['table'] = 'sns_sharestore';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}

	public function editSharestore($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('sns_sharestore',$param,$condition_str);
		return $result;
	}
	
	public function getShareStoreList($condition,$page='',$field='*',$type = 'simple') {
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		switch ($type){
            case 'detail':
				$param['table'] = 'sns_sharestore,store';
				$param['join_type'] = empty($condition['join_type'])?'LEFT JOIN':$condition['join_type'];
				$param['join_on'] = array(
					'sns_sharestore.share_storeid=store.store_id'
				);
				break;
			default:
				$param['table'] = 'sns_sharestore';
		}
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sns_sharestore.share_addtime desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function delSharestore($condition){
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
		return Db::delete('sns_sharestore',$condition_str);
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['share_id'] != '') {
			$condition_sql	.= " and `sns_sharestore`.share_id = '{$condition_array['share_id']}'";
		}
		if ($condition_array['share_memberid'] != '') {
			$condition_sql	.= " and `sns_sharestore`.share_memberid = '{$condition_array['share_memberid']}'";
		}
		if ($condition_array['share_privacyin'] !=''){
			$condition_sql	.= " and `sns_sharestore`.share_privacy in('{$condition_array['share_privacyin']}')";
		}
		return $condition_sql;
	}
}