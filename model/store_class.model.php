<?php

defined('haipinlegou') or exit('Access Invalid!');

class store_classModel{
	
	public function getClassList($condition){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'store_class';
		$param['order'] = $condition['order'] ? $condition['order'] : 'sc_parent_id asc,sc_sort asc,sc_id asc';
		$param['where'] = $condition_str;
		$result = Db::select($param);
		return $result;
	}
	
	
	private function _condition($condition){
		$condition_str = '';
		
		if ($condition['sc_parent_id'] != ''){
			$condition_str .= " and sc_parent_id = '". intval($condition['sc_parent_id']) ."'";
		}
		if ($condition['no_sc_id'] != ''){
			$condition_str .= " and sc_id != '". intval($condition['no_sc_id']) ."'";
		}
		if ($condition['sc_name'] != ''){
			$condition_str .= " and sc_name = '". $condition['sc_name'] ."'";
		}
		
		return $condition_str;
	}
	
	
	public function getOneClass($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'store_class';
			$param['field'] = 'sc_id';
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
			$result = Db::insert('store_class',$tmp);
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
			$where = " sc_id = '". $param['sc_id'] ."'";
			$result = Db::update('store_class',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function del($id){
		if (intval($id) > 0){
			$where = " sc_id = '". intval($id) ."'";
			$result = Db::delete('store_class',$where);
			return $result;
		}else {
			return false;
		}
	}

	
	public function getTreeList(){
		$show_class = array();
		$class_list = $this->getClassList(array('order'=>'sc_parent_id asc,sc_sort asc,sc_id asc'));
		if(is_array($class_list) && !empty($class_list)) {
			foreach ($class_list as $val) {
				if($val['sc_parent_id'] == 0) {
					$show_class[$val['sc_id']] = $val;
				} else {
					if(isset($show_class[$val['sc_parent_id']])){
						$show_class[$val['sc_parent_id']]['child'][] = $val;
					}
				}
			}
			unset($class_list);
		}
		return $show_class;
	}

	
	public function getTreeClassList($show_deep='2', $condition = array()){
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
				$sc_id = $val['sc_id'];
				$sc_parent_id	= $val['sc_parent_id'];
				if($sc_parent_id == $parent_id) {
					$val['deep'] = $deep;
					$show_class[] = $val;
					if($deep < $show_deep && $deep < 2) {
						$this->_getTreeClassList($show_deep,$class_list,$deep+1,$sc_id,$i+1);
					}
				}
				if($sc_parent_id > $parent_id) break;
			}
		}
		return $show_class;
	}
	
	
	public function getChildClass($parent_id){
		$all_class = $this->getClassList(array('order'=>'sc_parent_id asc,sc_sort asc,sc_id asc'));
		if (is_array($all_class)){
			if (!is_array($parent_id)){
				$parent_id = array($parent_id);
			}
			$result = array();
			foreach ($all_class as $k => $v){
				$sc_id	= $v['sc_id'];
				$sc_parent_id	= $v['sc_parent_id'];
				if (in_array($sc_id,$parent_id) || in_array($sc_parent_id,$parent_id)){
					$result[] = $v;
				}
			}
			return $result;
		}else {
			return false;
		}
	}
	
	public function getChildAndSelfClass($sc_id){
		$sc_id_arr = array();
		$sclasslist = $this->getClassList(array('sc_parent_id'=>$sc_id));
		if (is_array($sclasslist) && count($sclasslist)>0){
			foreach ($sclasslist as $v){
				$sc_id_arr[] = $v['sc_id'];
			}
		}
		if (is_array($sc_id_arr) && count($sc_id_arr)>0){
			$sc_id_arr[] = $sc_id;
			return $sc_id_arr; 
		}else{
			return $sc_id;
		}
	}
}