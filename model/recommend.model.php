<?php

defined('haipinlegou') or exit('Access Invalid!');

class recommendModel{
	
	public function getRecommendList($condition,$type="simple",$page=''){
		$condition_str = $this->_condition($condition);
		$param = array();
		switch($type){
			case 'simple':
				$param['table'] = 'recommend';
				$param['order'] = $condition['order'] ? $condition['order'] : 'recommend.recommend_id desc';
				break;
			case 'count':
				$param['table']	= 'recommend,recommend_goods';
				$param['field']	= 'recommend.*,count(recommend_goods.recommend_id) as count';
				$param['join_type']	= 'left join';
				$param['join_on']	= array('recommend.recommend_id=recommend_goods.recommend_id');
				$param['group']	= 'recommend.recommend_id';
				$param['order'] = $condition['order'] ? $condition['order'] : 'recommend.recommend_id desc';
		}
		$param['where'] = $condition_str;
		$result = Db::select($param,$page);
		return $result;
	}

	
	private function _condition($condition){
		$condition_str = '';
		if ($condition['recommend_id'] != ''){
			$condition_str .= " and recommend_id = '". intval($condition['recommend_id']) ."'";
		}
		if ($condition['no_recommend_id'] != ''){
			$condition_str .= " and recommend_id != '". intval($condition['no_recommend_id']) ."'";
		}
		if ($condition['recommend_name'] != ''){
			$condition_str .= " and recommend_name = '". $condition['recommend_name'] ."'";
		}
		if ($condition['like_recommend_name'] != ''){
			$condition_str .= " and recommend_name like '%". $condition['like_recommend_name'] ."%'";
		}
		if ($condition['home_index_recommend'] != ''){
			$condition_str .= " and recommend_id <= 9";
		}
		return $condition_str;
	}
	
	
	public function getOneRecommend($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'recommend';
			$param['field'] = 'recommend_id';
			$param['value'] = intval($id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('recommend',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function update($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " recommend_id = '". $param['recommend_id'] ."'";
			$result = Db::update('recommend',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function del($id){
		if (intval($id) > 0){
			$where = " recommend_id = '". intval($id) ."' and recommend_id > '1'";
			$result = Db::delete('recommend',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function getRecommendGoodsList($condition,$page=''){
		$condition_str = $this->_conditionGoods($condition);
		
		$param = array(
			'table'=>'recommend,recommend_goods,goods,store,brand',
			'field'=>'recommend.recommend_name,recommend.recommend_code,recommend_goods.*,goods.*,store.store_name,brand.brand_name',
			'where'=>$condition_str,
			'limit'=>$condition['limit'],
			'join_type'=>'left join',
			'join_on'=>array(
				'recommend.recommend_id=recommend_goods.recommend_id',
				'recommend_goods.goods_id=goods.goods_id',
				'goods.store_id=store.store_id',
				'goods.brand_id=brand.brand_id'
				
			),
			'order'=>$condition['order']
		);		
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	public function getGoodsList($condition,$page=''){
		$condition_str = $this->_conditionGoods($condition);
		$param	= array();
		$param['table']	= 'recommend_goods,goods';
		$param['field'] = $condition['field'] ? $condition['field'] : 'recommend_goods.sort,goods.goods_id,goods.store_id,goods.goods_name,goods.goods_image,goods.goods_store_price';
		$param['where']	= $condition_str;
		$param['join_type']	= 'left join';
		$param['join_on']	= array('recommend_goods.goods_id=goods.goods_id');
		$param['order'] = $condition['order'] ? $condition['order'] : 'recommend_goods.sort asc,goods.goods_id desc';
		$param['limit'] = $condition['limit'] ? $condition['limit'] : 10;
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	public function getCount($condition){
		$condition_str	= $this->_conditionGoods($condition);
		
		$param	= array(
			'table'=>'recommend_goods',
			'field'=>'count(*) as count',
			'where'=>$condition_str
		);
		$result = Db::select($param,$page);
		return $result[0][0];
	}
	
	private function _conditionGoods($condition){
		$condition_str = '';
		
		if ($condition['recommend_id'] != ''){
			$condition_str .= " and recommend_goods.recommend_id = '". $condition['recommend_id'] ."'";
		}
		if ($condition['goods_id'] != ''){
			$condition_str .= " and recommend_goods.goods_id = '". $condition['goods_id'] ."'";
		}
		if ($condition['recommend_code'] != ''){
			$condition_str	.= " and recommend.recommend_code='".$condition['recommend_code']."'";
		}
		if ($condition['in_recommend_code'] != ''){
			$condition_str	.= " and recommend.recommend_code in (".$condition['in_recommend_code'].")";
		}
		if ($condition['goods_show'] != ''){
			$condition_str	.= " and goods.goods_show = '".$condition['goods_show']."'";
		}
		if ($condition['home_index_recommend'] != ''){
			$condition_str .= " and recommend_id <= 9";
		}
		return $condition_str;
	}
	
	
	public function delRecommendGoods($recommend_id='',$goods_id=''){
		$where = '';
		if ($recommend_id != ''){
			$where .= " and recommend_id = '". intval($recommend_id) ."'";
		}
		if ($goods_id != ''){
			$where .= " and goods_id = '". intval($goods_id) ."'";
		}
		if ($where != ''){
			$result = Db::delete('recommend_goods',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function addRecommendGoods($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('recommend_goods',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function updateRecommendGoods($param){
		if(empty($param) or !is_array($param)){
			return false;
		}
		$condition_str	= $this->_conditionGoods($param);
		$update_array	= array(
			'sort'	=> $param['sort']
		);
		$result	= Db::update('recommend_goods',$update_array,$condition_str);
		return $result;
	}
}