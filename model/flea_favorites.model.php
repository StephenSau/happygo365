<?php

defined('haipinlegou') or exit('Access Invalid!');
class flea_favoritesModel{
	
	public function getFavoritesList($condition,$page = ''){
		$condition_str = $this->_condition($condition);
		$param = array(
					'table'=>'flea_favorites',
					'where'=>$condition_str,
					'order'=>$condition['order'] ? $condition['order'] : 'fav_time desc'
				);		
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	public function _condition($condition){
		$condition_str = '';
		
		if ($condition['member_id'] != ''){
			$condition_str .= " and member_id = '{$condition['member_id']}'";
		}
		if ($condition['fav_type'] != ''){
			$condition_str .= " and fav_type = '{$condition['fav_type']}'";
		}
		
		return $condition_str;
	}
	
	
	public function getOneFavorites($fav_id,$type,$member_id){
		if (intval($fav_id) > 0){
			$param = array();
			$param['table'] = 'flea_favorites';
			$param['field'] = array('fav_id','fav_type','member_id');
			$param['value'] = array(intval($fav_id),$type,$member_id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function addFavorites($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('flea_favorites',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function checkFavorites($fav_id,$fav_type,$member_id){
		if (intval($fav_id) == 0 || empty($fav_type) || intval($member_id) == 0){
			return true;
		}
		$result = self::getOneFavorites($fav_id,$fav_type,$member_id);
		if ($result['member_id'] == $member_id){
			return true;
		}else {
			return false;
		}
	}
	
	
	public function delFavorites($id,$type){
		if (intval($id) > 0 && !empty($type) && self::checkFavorites($id,$type,$_SESSION['member_id'])){
			$where = ' `fav_id` = '. intval($id) ." and `fav_type` = '{$type}'";
			$result = Db::delete('flea_favorites',$where);
			return $result;
		}else {
			return false;
		}
	}
}