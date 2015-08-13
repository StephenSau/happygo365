<?php

defined('haipinlegou') or exit('Access Invalid!');

class goods_class_tagModel{
	
	public function getTagList($condition = array() , $page = '', $field='*'){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'goods_class_tag';
		$param['field'] = $field;
		$param['where'] = $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'gc_tag_id asc';
		$result = Db::select($param, $page);

		return $result;
	}
	
	
	public function tagAdd($param){
		$class_id_1		= '';
		$class_id_2		= '';
		$class_id_3		= '';
		$class_name_1	= '';
		$class_name_2	= '';
		$class_name_3	= '';
		$class_id		= '';
		$type_id		= '';
		$condition_str	= '';
		
		if(is_array($param) && !empty($param)){	
			foreach ($param as $value){
				$class_id_1		= $value['gc_id'];
				$class_name_1	= trim($value['gc_name']);
				$class_id		= $value['gc_id'];
				$type_id		= $value['type_id'];
				$class_id_2		= '';
				$class_id_3		= '';
				$class_name_2	= '';
				$class_name_3	= '';
				
				if(is_array($value['sub_class']) && !empty($value['sub_class'])){	
					foreach ($value['sub_class'] as $val){
						$class_id_2		= $val['gc_id'];
						$class_name_2	= trim($val['gc_name']);
						$class_id		= $val['gc_id'];
						$type_id		= $val['type_id'];
						
						if(is_array($val['sub_class']) && !empty($val['sub_class'])){	
							foreach ($val['sub_class'] as $v){
								$class_id_3		= $v['gc_id'];
								$class_name_3	= trim($v['gc_name']);
								$class_id		= $v['gc_id'];
								$type_id		= $v['type_id'];
								
								$condition_str .= '("'.$class_id_1.'", "'.$class_id_2.'", "'.$class_id_3.'", "'.$class_name_1.'&nbsp;&gt;&nbsp;'.$class_name_2.'&nbsp;&gt;&nbsp;'.$class_name_3.'", "'.$class_name_1.','.$class_name_2.','.$class_name_3.'", "'.$class_id.'", "'.$type_id.'"),';
							}
						}else{
							$condition_str .= '("'.$class_id_1.'", "'.$class_id_2.'", "", "'.$class_name_1.'&nbsp;&gt;&nbsp;'.$class_name_2.'", "'.$class_name_1.','.$class_name_2.'", "'.$class_id.'", "'.$type_id.'"),';
						}
						
					}
				}else{
					$condition_str .= '("'.$class_id_1.'", "", "", "'.$class_name_1.'", "'.$class_name_1.'", "'.$class_id.'", "'.$type_id.'"),';
				}
				
			}
		}else{
			return false;
		}
		
		$condition_str = trim($condition_str,',');
		return Db::query("insert into `".DBPRE."goods_class_tag` (`gc_id_1`,`gc_id_2`,`gc_id_3`,`gc_tag_name`,`gc_tag_value`,`gc_id`,`type_id`) values ".$condition_str);
	}
	
	
	public function clearTag(){
		return Db::query("TRUNCATE TABLE `".DBPRE."goods_class_tag`");
	}

	
	public function updateTag($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " gc_tag_id = '". $param['gc_tag_id'] ."'";
			$result = Db::update('goods_class_tag',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	public function delTag($id){
		if(!empty($id)) {
			return Db::delete('goods_class_tag',' gc_tag_id in ('.$id.')');
		}else{
			return false;
		}
	}
	
	
	public function delByCondition($condition){
		return Db::delete('goods_class_tag', $this->_condition($condition));
	}
	
	
	public function addOneTag($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('goods_class_tag',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	
	private function _condition($condition = array()){
		$condition_str = '';

		if ($condition['gc_parent_id'] != ''){
			$condition_str .= " and gc_parent_id = '". intval($condition['gc_parent_id']) ."'";
		}
		if ($condition['gc_tag_id'] != ''){
			$condition_str .= " and gc_tag_id = '".$condition['gc_tag_id']."'";
		}
		if ($condition['in_tag_id'] != ''){
			$condition_str .= " and gc_tag_id in (".$condition['in_tag_id'].")";
		}
		if ($condition['gc_tag_value'] != ''){
			$condition_str .= " and gc_tag_value = '".$condition['gc_tag_value']."'";
		}
		if ($condition['gc_condition'] != ''){
			$condition_str .= " and ( gc_id_1='".$condition['gc_condition']."' or gc_id_2='".$condition['gc_condition']."' or gc_id_3='".$condition['gc_condition']."')";
		}
		if($condition['gc_id'] != '') {
			$condition_str .= ' and gc_id = "'. $condition['gc_id'] .'"';
		}
		if($condition['type_id'] != '') {
			$condition_str .= ' and type_id = "'. $condition['type_id'] .'"';
		}

		return $condition_str;
	}
}