<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_goods_classModel {
	
	public function getOneById($id){
		if(intval($id)<=0)return false;
		$param	= array();
		$param['table']	= 'store_goods_class';
		$param['field']	= 'stc_id';
		$param['value']	= intval($id);
		return Db::getRow($param);
	}
	
}