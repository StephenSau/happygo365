<?php

defined('haipinlegou') or exit('Access Invalid!');

class pointorderModel {
	private $product_sn;	
	
	
	public function point_snOrder() {
		$this->product_sn = 'gift'.date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
		return $this->product_sn;
	}
	
	public function point_outSnOrder() {
		if($this->product_sn) {
			return $this->product_sn;
		}
	}
	
	public function addPointOrder($param) {
		if(is_array($param) and !empty($param)) {
			$result = Db::insert('points_order',$param);
			return $result;
		} else {
			return false;
		}
	}
	
	public function addPointOrderProd($param) {
		if(is_array($param) && count($param)>0) {
			$result = Db::insert('points_ordergoods',$param);
			return $result;
		} else {
			return false;
		}
	}
	
	public function getPointOrderProdList($condition,$page,$field='*',$type='simple') {		
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		switch($type){
			case 'all':
				$param['table']	= 'points_ordergoods,points_order';
				$param['join_type']	= 'left join';
				$param['join_on']	= array('`points_ordergoods`.point_orderid = `points_order`.point_orderid');
				break;
			case 'simple':
				$param['table']	= 'points_ordergoods';
				break;
		}
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_ordergoods.point_recid desc ';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		$prod_list	= Db::select($param,$page);
		return $prod_list;
	}
	
	public function dropPointOrderProd($condition){
		$condition_str	= $this->getCondition($condition);
		$result = Db::delete('points_ordergoods',$condition_str);
		return $result;
	}
	
	public function dropPointOrderAddress($condition){
		$condition_str	= $this->getCondition($condition);
		$result = Db::delete('points_orderaddress',$condition_str);
		return $result;
	}
	
	public function addPointOrderAddress($param){
		if(is_array($param) and count($param)) {
			$result = Db::insert('points_orderaddress',$param);
			return $result;
		} else {
			return false;
		}
	}
	
	public function getPointOrderInfo($condition,$type='all',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		switch($type){
			case 'all':
				$param['table']	= 'points_order,points_orderaddress';
				$param['join_type']	= 'left join';
				$param['join_on']	= array('`points_order`.point_orderid=`points_orderaddress`.point_orderid');
				break;
			case 'simple':
				$param['table']	= 'points_order';
				break;
		}
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$order_list	= Db::select($param);
		return $order_list[0];
	}
	
	public function getPointOrderList($condition,$page,$type='all',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		switch($type){
			case 'all':
				$param['table']	= 'points_order,points_orderaddress';
				$param['join_type']	= 'left join';
				$param['join_on']	= array('`points_order`.point_orderid=`points_orderaddress`.point_orderid');
				break;
			case 'simple':
				$param['table']	= 'points_order';
				break;
		}
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_order.point_orderid desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		$order_list	= Db::select($param,$page);
		return $order_list;
	}
	
	public function updatePointOrder($condition,$param) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('points_order',$param,$condition_str);
		return $result;
	}
	
	public function dropPointOrder($condition){
		$condition_str	= $this->getCondition($condition);
		$result = Db::delete('points_order',$condition_str);
		return $result;
	}
	
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['point_orderid']) {
			$condition_sql	.= " and `points_order`.point_orderid = '{$condition_array['point_orderid']}'";
		}
		if ($condition_array['point_orderid_del']) {
			$condition_sql	.= " and point_orderid = '{$condition_array['point_orderid_del']}'";
		}
		if (isset($condition_array['point_orderid_in'])) {
			if ($condition_array['point_orderid_in'] == ''){
				$condition_sql	.= " and point_orderid in('') ";
			}else {
				$condition_sql	.= " and point_orderid in({$condition_array['point_orderid_in']}) ";
			}
		}
		if ($condition_array['point_buyerid']) {
			$condition_sql	.= " and `points_order`.point_buyerid = '{$condition_array['point_buyerid']}'";
		}
		if ($condition_array['point_shippingcharge']) {
			$condition_sql	.= " and `points_order`.point_shippingcharge = '{$condition_array['point_shippingcharge']}'";
		}
		if ($condition_array['point_orderstate']) {
			$condition_sql	.= " and `points_order`.point_orderstate = '{$condition_array['point_orderstate']}'";
		}
		if ($condition_array['point_orderstatetxt']) {
			switch ($condition_array['point_orderstatetxt']){
				case 'canceled':
					$condition_sql	.= " and `points_order`.point_orderstate = 2 ";
					break;
				case 'waitpay':
					$condition_sql	.= " and `points_order`.point_orderstate = 10 ";
					break;
				case 'waitconfirmpay':
					$condition_sql	.= " and `points_order`.point_orderstate = 11 ";
					break;
				case 'waitship':
					$condition_sql	.= " and `points_order`.point_orderstate = 20 ";
					break;
				case 'waitreceiving':
					$condition_sql	.= " and `points_order`.point_orderstate = 30 ";
					break;
				case 'finished':
					$condition_sql	.= " and `points_order`.point_orderstate = 40 ";
					break;
			}
		}
		if ($condition_array['point_ordersn']) {
			$condition_sql	.= " and `points_order`.point_ordersn = '{$condition_array['point_ordersn']}' ";
		}
		if ($condition_array['point_ordersn_like']) {
			$condition_sql	.= " and `points_order`.point_ordersn like '%{$condition_array['point_ordersn_like']}%' ";
		}
		if ($condition_array['point_buyername_like']) {
			$condition_sql	.= " and `points_order`.point_buyername like '%{$condition_array['point_buyername_like']}%' ";
		}
		if ($condition_array['point_paymentcode']) {
			$condition_sql	.= " and `points_order`.point_paymentcode = '{$condition_array['point_paymentcode']}' ";
		}
		if (isset($condition_array['point_orderstate_in'])) {
			if ($condition_array['point_orderstate_in'] == ''){
				$condition_sql	.= " and point_orderstate in ('') ";
			}else {
				$condition_sql	.= " and point_orderstate in ({$condition_array['point_orderstate_in']}) ";
			}
		}
		if ($condition_array['prod_orderid']) {
			$condition_sql	.= " and points_ordergoods.point_orderid = '{$condition_array['prod_orderid']}' ";
		}
		if (isset($condition_array['prod_orderid_in'])) {
			if ($condition_array['prod_orderid_in'] == ''){
				$condition_sql	.= " and points_ordergoods.point_orderid in('')";
			}else{
				$condition_sql	.= " and points_ordergoods.point_orderid in({$condition_array['prod_orderid_in']})";
			}
		}
		if ($condition_array['prod_orderid_del']) {
			$condition_sql	.= " and point_orderid = '{$condition_array['prod_orderid_del']}' ";
		}
		if (isset($condition_array['prod_orderid_in_del'])) {
			if ($condition_array['prod_orderid_in_del'] == ''){
				$condition_sql	.= " and point_orderid in('')";
			}else{
				$condition_sql	.= " and point_orderid in({$condition_array['prod_orderid_in_del']})";
			}
		}
		if ($condition_array['prod_goodsid']) {
			$condition_sql	.= " and points_ordergoods.point_goodsid = '{$condition_array['prod_goodsid']}' ";
		}
		if ($condition_array['point_order_enablecancel']) {
			$condition_sql	.= " and ((points_order.point_shippingcharge = 1 and points_order.point_orderstate =10) or (points_order.point_shippingcharge = 0 and points_order.point_orderstate =20))";
		}
		if ($condition_array['address_orderid_del']) {
			$condition_sql	.= " and point_orderid = '{$condition_array['address_orderid_del']}' ";
		}
		if (isset($condition_array['address_orderid_in_del'])) {
			if ($condition_array['address_orderid_in_del'] == ''){
				$condition_sql	.= " and point_orderid in('')";
			}else{
				$condition_sql	.= " and point_orderid in({$condition_array['address_orderid_in_del']})";
			}
		}
		return $condition_sql;
	}
}