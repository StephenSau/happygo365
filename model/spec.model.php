<?php

defined('haipinlegou') or exit('Access Invalid!');

class specModel {
	
	public function specList($param, $page = '', $field = '*') {
		$condition_str = $this->getCondition($param);
		$array = array();
		$array['table']		= 'spec';
		$array['where']		= $condition_str;
		$array['field']		= $field;
		$array['order']		= $param['order'];
		$list_spec		= Db::select($array, $page);
		return $list_spec;
	}
	
	public function specUpdate($update, $param, $table){
		$condition_str = $this->getCondition($param);
		if (empty($update)){
			return false;
		}
		if (is_array($update)){
			$tmp = array();
			foreach ($update as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::update($table,$tmp,$condition_str);
			return $result;
		}else {
			return false;
		}
	}
	
	public function apecAdd($param, $param_value, $files){
		
		
		$sp_id = Db::insert('spec', $param);
		
		
		$condition = '';
		if(!$sp_id){
			return false;
		}
		if(is_array($param_value) && !empty($param_value)){
			
			$string_value = '';
			$upload = new UploadFile();
			$upload->set('default_dir',ATTACH_SPEC);
			$upload->set('thumb_width',	'16');
			$upload->set('thumb_height','16');
			$upload->set('thumb_ext',	'_small');
			$upload->set('ifremove',	true);			
			foreach ($param_value as $k=>$val) {
				$upload->set('file_name','');
				$val['name']	= trim($val['name']);
				$val['sort']	= intval($val['sort']);
				
				
				$val['image'] = '';
				if($_POST['s_dtype'] == 'image'){
					if(!empty($files['s_value_'.$k]['name'])){
						$upload->error = '';
						$return = $upload->upfile('s_value_'.$k);
						if ($return){
							$val['image'] = $upload->thumb_image;
						}else{
							echo $upload->error;exit('a');
							showMessage($upload->error,'','','error');
						}
					}else{
						return false;
					}
				}
				if($val['name'] != ''){
					$condition .= '("' .$val['name'] .'", "'. $sp_id .'", "'. $val['image'] .'", "'. $val['sort'] .'"),';
				}
				$string_value	.= $val['name'].',';
			}
			$condition = rtrim($condition,',');
			if($condition != ''){
				$return = Db::query("insert into `".DBPRE."spec_value` (`sp_value_name`, `sp_id`, `sp_value_image`, `sp_value_sort`) values ".$condition);
				if($return){
					
					Db::query("update `".DBPRE."spec` set sp_value = '".rtrim($string_value,',')."' where sp_id = '".$sp_id."'");
				}
			}
			return true;
		}else{
			return false;
		}
	}
	
	public function specValueAdd($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('spec_value',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	public function specValueList($param, $page = '', $field = '*') {
		$condition_str = $this->getCondition($param);
		$array = array();
		$array['table']		= 'spec_value';
		$array['where']		= $condition_str;
		$array['field']		= $field;
		$array['order']		= $param['order'];
		$list_spec		= Db::select($array, $page);
		return $list_spec;
	}
	
	public function specValueOne($param, $field = '*') {
		$condition_str = $this->getCondition($param);
		$array = array();
		$array['table']		= 'spec_value';
		$array['where']		= $condition_str;
		$array['field']		= $field;
		$list_spec		= Db::select($array);
		return $list_spec['0'];
	}
	
	public function delSpec($table,$param){
		$condition_str = $this->getCondition($param);
		return Db::delete($table, $condition_str);
	}
	
	private function getCondition($condition_array) {
		$condition_str = '';
		if($condition_array['sp_id'] != ''){
			$condition_str .= ' and sp_id = "'.$condition_array['sp_id'].'"';
		}
		if($condition_array['in_sp_id'] != ''){
			$condition_str .= ' and sp_id in ('.$condition_array['in_sp_id'].')';
		}
		if($condition_array['sp_value_id'] != ''){
			$condition_str .= ' and sp_value_id = "'.$condition_array['sp_value_id'].'"';
		}
		if($condition_array['in_sp_value_id'] != ''){
			$condition_str .= ' and sp_value_id in ('.$condition_array['in_sp_value_id'].')';
		}
		return $condition_str;
	}
}