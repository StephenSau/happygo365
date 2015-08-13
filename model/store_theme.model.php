<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_themeModel{
	
	public function getRow($theme_id){
		$param	= array();
		$param['table']	= 'store_theme';
		$param['field']	= 'theme_id';
		$param['value']	= $theme_id;
		$result	= Db::getRow($param);
		return $result;
	}
	
	
	public function getList($condition = '',$page = ''){
		$condition_str = $this->getCondition($condition);
		$param = array();
		$param['table'] = 'store_theme';
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'theme_id desc';
		$param['limit'] = $condition['limit'];
		$result = Db::select($param,$page);
		return $result;
	}
	
	
	public function getStyleConfig(){
		$style_data = array();
		$style_configurl = BASE_TPL_PATH.DS.'store'.DS.'style'.DS."styleconfig.php";
		if (file_exists($style_configurl)){
			include_once($style_configurl);
		}
		
		if (strtoupper(CHARSET) == 'GBK'){
			$style_data = Language::getGBK($style_data);
		}
		return $style_data;
	}
	
	
	public function getShowStyle($style_id = 'default'){
		$style_data = array(
			'default' => 0,
			'style1' => 0,
			'style2' => 0,
			'style3' => 0,
			'style4' => 0,
			'style5' => 0,
			'style6' => 1,
			'style7' => 1,
			'style8' => 1,
			'style9' => 1,
			'style10' => 1
			);
		return $style_data[$style_id];
	}
	
	
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$result = Db::insert('store_theme',$param);
			return $result;
		}else {
			return false;
		}
	}
	
	public function update($condition,$param){
		$theme_id = $condition['theme_id'];
		if (intval($theme_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($param)){
			$result = Db::update('store_theme',$param,$where);
			return result;
		}else {
			return false;
		}
	}
	
	public function del($condition){
		$theme_id = $condition['theme_id'];
		if (intval($theme_id) < 1){
			return false;
		}
		$condition_str = $this->getCondition($condition);
		$where = $condition_str;
		if (is_array($condition)){
			$result = Db::delete('store_theme',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['theme_id'] !== null) {
			$condition_sql	.= " and theme_id = '".$condition_array['theme_id']."'";
		}
		if($condition_array['store_id'] !== null) {
			$condition_sql	.= " and store_id = '".$condition_array['store_id']."'";
		}
		if($condition_array['member_id'] !== null) {
			$condition_sql	.= " and member_id = '".$condition_array['member_id']."'";
		}
		if($condition_array['style_id'] !== null) {
			$condition_sql	.= " and style_id = '".$condition_array['style_id']."'";
		}
		if($condition_array['show_page'] !== null) {
			$condition_sql	.= " and show_page = '".$condition_array['show_page']."'";
		}
		return $condition_sql;
	}
	
}