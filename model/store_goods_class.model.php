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
	
	public function getStcTreeList($store_id = 0){
		$param	= array();
		$param['table']	= 'store_goods_class';
		if (intval($store_id) > 0){
			$param['where'] = "store_id = '{$store_id}'";
		}
		$list	= Db::select($param);
		return	$this->getStcTree($list);
	}
	private function getStcTree($list,$pid='0',$deep=1){
		$return	= array();
		if(is_array($list)){
			foreach($list as $k=>$stc){
				if($stc['stc_parent_id'] == $pid){
					for($i=0;$i<$deep;$i++){
						$stc['stc_name']	= '&nbsp;&nbsp;'.$stc['stc_name'];
					}
					$return[]	= $stc;
					$temp	= $this->getStcTree($list,$stc['stc_id'],$deep+1);
					if(!empty($temp)){
						$return	= array_merge($return,$temp);
					}
					unset($temp);
				}
			}
		}
		return $return;
	}
}