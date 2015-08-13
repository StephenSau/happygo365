<?php 

defined('haipinlegou') or exit('Access Invalid!');
class coupon_classModel{

	
	private function _condition($condition){
	
		$condition_str = '' ;
		$condition['class_id'] != '' && $condition_str .= " and class_id = '".$condition['class_id']."'";
		$condition['class_id_in'] !='' && $condition_str .= " and class_id in (".$condition['class_id_in'].")";
		$condition['class_name'] != '' && $condition_str .= " and class_name = '".$condition['class_name']."'";
		$condition['coupon_state'] != '' && $condition_str .= " and `coupon`.coupon_state = '".$condition['coupon_state']."'";
		$condition['coupon_name_like'] !='' && $condition_str .= " and `coupon`.coupon_title like '%".$condition['coupon_name_like']."%'";
		$condition['class_show'] != '' && $condition_str .= " and class_show = '".$condition['class_show']."'";
		$condition['coupon_allowstate'] != '' && $condition_str	.= " and coupon_allowstate = '".$condition['coupon_allowstate']."'";
		$condition['coupon_allowstate2'] != '' && $condition_str	.= " and coupon_allowstate <> '1'";
		return $condition_str ;
		
	}
	
	public function addCouponClass($param){
	
		return Db::insert('coupon_class',$param) ;
	
	}
	
	
	public function updateCouponClass($update_array, $where_array){
	
		$where = $this->_condition($where_array) ;
		return Db::update('coupon_class',$update_array,$where) ;
	
	
	}
	
	
	public function delCouponClass($param){
	
		$where = $this->_condition($param) ;
		return Db::delete('coupon_class', $where) ;
	
	}

	
	public function getCouponClass($condition,$page='',$type='simple'){
	
		if($type=='simple'){	
		
			$param = array() ;
			$param['table'] = 'coupon_class' ;
			$param['where'] = $this->_condition($condition) ;
			$param['order'] = $condition['order'] ? $condition['order'] : ' class_sort asc,class_id desc ' ;
			$condition['limit'] && $param['limit'] = $condition['limit'] ;		
			
		}elseif($type=='join'){		
		
			$param = array() ;
			$param['table'] = 'coupon,coupon_class';
			$param['where'] = $this->_condition($condition) ;
			$param['join_on'] = array('coupon.coupon_class_id=coupon_class.class_id') ;
			$param['join_type'] = 'left join' ;
			$param['field'] = 'coupon_class.*,count(coupon.coupon_class_id) as num' ;
			$param['order'] = $condition['order']!=='' ? $condition['order'] : 'num' ;
			$param['group'] = 'coupon.coupon_class_id' ;
				
		}
		
		return Db::select($param,$page) ;
	
	}

}
