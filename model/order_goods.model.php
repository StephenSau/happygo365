<?php

defined('haipinlegou') or exit('Access Invalid!');
class order_goodsModel {
	
	public function getOrderGoodsList($param,$field='*',$page = '') {

		$condition_str	= $this->getCondition($param);
		$array['table']		= 'order_goods';
		$array['field']		= $field;
		$array['where'] 	= $condition_str;
		$array['order'] 	= $param['order']?$param['order']:"order_goods.rec_id";
		$order_goods_list	= Db::select($array,$page);
		return $order_goods_list;
	}

    
    public function getoneOrderGoods($rec_id) {
        
        $param = array() ;
    	$param['table'] = 'order_goods';
    	$param['field'] = 'rec_id' ;
    	$param['value'] = intval($rec_id);
    	return Db::getRow($param) ;

    }

	
	private function getCondition($condition_array){
		$condition_sql = '';
		if(!empty($condition_array['order_id'])) {
			$condition_sql	.= " and order_id='{$condition_array['order_id']}'";
		}
		if(!empty($condition_array['in_order_id'])) {
			$condition_sql	.= " and order_id in({$condition_array['in_order_id']})";
		}
		return $condition_sql;
	}
}
