<?php

defined('haipinlegou') or exit('Access Invalid!');
class favoritesModel{
	
	public function getFavoritesList($condition,$page = ''){
		$condition_str = $this->_condition($condition);
		$param = array(
					'table'=>'favorites',
					'where'=>$condition_str,
					'order'=>$condition['order'] ? $condition['order'] : 'fav_time desc'
				);		
		$result = Db::select($param,$page);
		return $result;
	}	
	
	public function getOneFavorites($condition,$field='*'){
		$param = array();
		$param['table'] = 'favorites';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	
	public function addFavorites($param){
		if (empty($param)){
			return false;
		}
		return Db::insert('favorites',$param);
	}
	
	
	public function updateFavoritesNum($table, $update, $param){
		$where = $this->_condition($param);
		return Db::update($table,$update,$where);
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
	
	
	public function delFavorites($condition){
		if (empty($condition)){
			return false;
		}
		$condition_str = '';	
		if ($condition['fav_id'] != ''){
			$condition_str .= " and fav_id='{$condition['fav_id']}' ";
		}
		if ($condition['member_id'] != ''){
			$condition_str .= " and member_id='{$condition['member_id']}' ";
		}
		if ($condition['fav_type'] != ''){
			$condition_str .= " and fav_type='{$condition['fav_type']}' ";
		}
		if ($condition['fav_id_in'] !=''){
			$condition_str .= " and fav_id in({$condition['fav_id_in']}) ";
		}
		return Db::delete('favorites',$condition_str);
	}
	
	public function _condition($condition){
		$condition_str = '';
		
		if ($condition['member_id'] != ''){
			$condition_str .= " and member_id = '".$condition['member_id']."'";
		}
		if ($condition['fav_type'] != ''){
			$condition_str .= " and fav_type = '".$condition['fav_type']."'";
		}
		if ($condition['goods_id'] != ''){
			$condition_str .= " and goods_id = '".$condition['goods_id']."'";
		}
		if ($condition['store_id'] != ''){
			$condition_str .= " and store_id = '".$condition['store_id']."'";
		}
		if ($condition['fav_id_in'] !=''){
			$condition_str .= " and favorites.fav_id in({$condition['fav_id_in']}) ";
		}
		return $condition_str;
	}
}