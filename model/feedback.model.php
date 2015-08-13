<?php


defined('haipinlegou') or exit('Access Invalid!');

class feedbackModel{
	
	public function getList($condition,$page=''){
		$param = array();
		$param['table'] = 'feedback';
		$param['order'] = 'ftime desc';
		$result = Db::select($param,$page);
		return $result;
	}

	
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('feedback',$where);
			return $result;
		}else {
			return false;
		}
	}
}