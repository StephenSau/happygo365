<?php

defined('haipinlegou') or exit('Access Invalid!');
class activity_detailModel{
	
	public function add($input){
		return Db::insert('activity_detail',$input);
	}
	
	public function update($input,$id){
		return Db::update('activity_detail',$input,'activity_detail_id in('.$id.')');
	}
	
	public function updateList($input,$condition){
		return Db::update('activity_detail',$input,$this->getCondition($condition));
	}
	
	public function del($id){
		return Db::delete('activity_detail','activity_detail_id in('.$id.')');
	}
	
	public function delList($condition){
		return Db::delete('activity_detail',$this->getCondition($condition));
	}
	
	public function getList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity_detail';
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	
	public function getGoodsJoinList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity_detail,goods,brand';
		$param['join_type']	= 'left join';
		$param['field']	= 'activity_detail.*,goods.*,brand.brand_name';
		$param['join_on']	= array('activity_detail.item_id=goods.goods_id','goods.brand_id=brand.brand_id');
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	
	public function getGoodsList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity_detail,goods';
		$param['join_type']	= 'left join';
		$param['field']	= 'activity_detail.activity_detail_sort,goods.goods_id,goods.store_id,goods.goods_name,goods.goods_store_price,goods.goods_image';
		$param['join_on']	= array('activity_detail.item_id=goods.goods_id');
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	
	private function getCondition($condition){
		$conditionStr	= '';
		if($condition['activity_id']>0){
			$conditionStr	.= " and activity_detail.activity_id = '{$condition['activity_id']}'";
		}
		if (isset($condition['activity_detail_id_in'])){
			if ($condition['activity_detail_id_in'] == ''){
				$conditionStr	.= " and activity_detail_id in ('')";
			}else{
				$conditionStr	.= " and activity_detail_id in ({$condition['activity_detail_id_in']})";
			}
		}
		if(isset($condition['activity_detail_state_in'])){
			if ($condition['activity_detail_state_in'] == ''){
				$conditionStr	.= " and activity_detail_state in ('')";
			}else{
				$conditionStr	.= " and activity_detail_state in ({$condition['activity_detail_state_in']})";
			}
		}
		if($condition['activity_detail_state'] != ''){
			$conditionStr	.= " and activity_detail.activity_detail_state='".$condition['activity_detail_state']."'";
		}
		if($condition['gc_id'] != ''){
		$conditionStr	.= " and goods.gc_id='{$condition['gc_id']}'";
		}
		if($condition['brand_id'] != ''){
			$conditionStr	.= " and goods.brand_id='{$condition['brand_id']}' ";
		}
		if($condition['name'] != ''){
			$conditionStr	.= " and goods.goods_name like '%{$condition['name']}%'";
		}
		if(intval($condition['item_id'])>0){
			$conditionStr	.= " and activity_detail.item_id='".intval($condition['item_id'])."'";
		}
		if($condition['item_name'] != ''){
			$conditionStr	.= " and activity_detail.item_name like '%{$condition['item_name']}%'";
		}
		if(intval($condition['store_id'])>0){
			$conditionStr	.= " and activity_detail.store_id='".intval($condition['store_id'])."'";
		}
		if($condition['store_name'] != ''){
			$conditionStr	.= " and activity_detail.store_name like '%{$condition['store_name']}%'";
		}
		if ($condition_array['goods_show'] != '') {
			$condition_sql	.= " and goods.goods_show= '{$condition_array['goods_show']}'";
		}
		return $conditionStr;
	}
}