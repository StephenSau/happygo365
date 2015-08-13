<?php

defined('haipinlegou') or exit('Access Invalid!');

class my_goods_classModel extends Model {
	public function __construct(){
		parent::__construct('store_goods_class');
	}
	
	public function getClassInfo($param,$field='*') {
		if(empty($param)) {
			return false;
		}
		
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'store_goods_class';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$class_info	= Db::select($param);
		//return $class_info[0];
		return $class_info;
	}
	
	public function addGoodsClass($param) {
		if(empty($param)) {
			return false;
		}
		$class_array		= array();
		$class_array['stc_name']	= $param['stc_name'];
		$class_array['stc_parent_id']= $param['stc_parent_id'];
		$class_array['stc_state']	= $param['stc_state'];
		$class_array['store_id']	= $_SESSION['store_id'];
		$class_array['stc_sort']	= $param['stc_sort'];

		$result	= Db::insert('store_goods_class',$class_array);
		if($result) {
			return true;
		} else {
			return false;
		}
	}
	
	public function importGoodsClass($param) {
		if(empty($param)) {
			return false;
		}
		$class_array		= array();
		$class_array['stc_name']	= $param['stc_name'];
		$class_array['stc_parent_id']= $param['stc_parent_id'];
		$class_array['stc_state']	= $param['stc_state'];
		$class_array['store_id']	= $_SESSION['store_id'];
		$class_array['stc_sort']	= $param['stc_sort'];

		$result	= Db::insert('store_goods_class',$class_array);
		if($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	public function editGoodsClass($param,$class_id) {
		if(empty($param)) {
			return false;
		}
		$class_array	= array();
		if($param['stc_name'] != ''){
			$class_array['stc_name']	= $param['stc_name'];
		}
		if($param['stc_parent_id'] != ''){
			$class_array['stc_parent_id']	= $param['stc_parent_id'];
		}
		if($param['stc_state'] != ''){
			$class_array['stc_state']	= $param['stc_state'];
		}
		if($param['stc_sort'] != ''){
			$class_array['stc_sort']	= $param['stc_sort'];
		}
		
		$state	= Db::update('store_goods_class',$class_array," WHERE stc_id='$class_id'");
		if($state) {
			return true;
		} else {
			return false;
		}
	}
	
	public function dropGoodsClass($store_class_id) {
		if(empty($store_class_id)) {
			return false;
		}
		$array	= explode(',',$store_class_id);
		foreach ($array as $val) {
			$class_info	= $this->getClassInfo(array('stc_id'=>$val,'store_id'=>$_SESSION['store_id']),'stc_id');
			//if($class_info['stc_id'] != '') {
			if($val['stc_id'] != '') {
				Db::delete('store_goods_class'," WHERE stc_id='$val'");
			}
		}
		return true;
	}
	
	public function getClassList($condition){
		$where	= $this->getCondition($condition);
		$where	= ltrim($where, ' and');
		$order	= $condition['order']?$condition['order']:'stc_parent_id asc,stc_sort asc,stc_id asc';
		$result	= $this->where($where)->order($order)->select();
		return $result;
	}
	
	public function getShowTreeList($store_id){
		$show_class = array();
		$class_list = $this->getClassList(array('store_id'=>$store_id,'stc_state'=>'1','order'=>'stc_parent_id asc,stc_sort asc,stc_id asc'));
		if(is_array($class_list) && !empty($class_list)) {
			foreach ($class_list as $val) {
				if($val['stc_parent_id'] == 0) {
					$show_class[$val['stc_id']] = $val;
				} else {
					if(isset($show_class[$val['stc_parent_id']])){
						$show_class[$val['stc_parent_id']]['children'][] = $val;
					}
				}
			}
			unset($class_list);
		}
		return $show_class;
	}
	
	public function getTreeClassList($condition,$show_deep='2'){
		$class_list = $this->getClassList($condition);
		$show_deep = intval($show_deep);
		$result = array();
		if(is_array($class_list) && !empty($class_list)) {
			$result = $this->_getTreeClassList($show_deep,$class_list);
		}
		return $result;
	}
	
	private function _getTreeClassList($show_deep,$class_list,$deep=1,$parent_id=0,$i=0){
		static $show_class = array();
		if(is_array($class_list) && !empty($class_list)) {
			$size = count($class_list);
			if($i == 0) $show_class = array();
			for ($i;$i < $size;$i++) {
				$val = $class_list[$i];
				$stc_id = $val['stc_id'];
				$stc_parent_id	= $val['stc_parent_id'];
				if($stc_parent_id == $parent_id) {
					$val['deep'] = $deep;
					$show_class[] = $val;
					if($deep < $show_deep && $deep < 2) {
						$this->_getTreeClassList($show_deep,$class_list,$deep+1,$stc_id,$i+1);
					}
				}
				if($stc_parent_id > $parent_id) break;
			}
		}
		return $show_class;
	}
	
	public function getClassTree($condition){
		$condition['order'] = ' stc_parent_id asc,stc_sort asc,stc_id asc';
		$class_list = $this->getClassList($condition);
		$d = array();
		if (is_array($class_list)){
			foreach($class_list as $v) {
				if($v['stc_parent_id'] == 0) {
					$d[$v['stc_id']] = $v;
				}else {
					if(isset($d[$v['stc_parent_id']])) $d[$v['stc_parent_id']]['child'][] = $v;
				}
			}
		}
		return $d;
	}
	
	public function getChildAndSelfClass($stc_id,$stc_state=''){
		$condition = array('stc_parent_id'=>$stc_id,'order'=>'stc_parent_id asc,stc_sort asc,stc_id asc');
		if ($stc_state != '') {
			$condition['stc_state'] = $condition['stc_state'];
		}
		$stc_id_arr = array();
		$sgoodsclasslist = $this->getClassList($condition);
		if (is_array($sgoodsclasslist) && count($sgoodsclasslist)>0){
			foreach ($sgoodsclasslist as $v){
				$stc_id_arr[] = $v['stc_id'];
			}
		}
		if (is_array($stc_id_arr) && count($stc_id_arr)>0){
			$stc_id_arr[] = $stc_id;
			return $stc_id_arr; 
		}else{
			return $stc_id;
		}
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['stc_top'] != '') {
			$condition_sql	.= " and stc_parent_id=0";
		}
		if ($condition_array['stc_id'] != '') {
			$condition_sql	.= " and stc_id= '{$condition_array['stc_id']}'";
		}
		if($condition_array['store_id'] != '') {
			$condition_sql	.= " and store_id= '{$condition_array['store_id']}'";
		}
		if($condition_array['stc_parent_id'] != '') {
			$condition_sql	.= " and stc_parent_id= '{$condition_array['stc_parent_id']}'";
		}
		if ($condition_array['stc_state'] == '0' || $condition_array['stc_state'] == '1') {
			$condition_sql	.= " and stc_state= '{$condition_array['stc_state']}'";
		}
		return $condition_sql;
	}
}
?>