<?php

defined('haipinlegou') or exit('Access Invalid!');
define('MYSQL_RESULT_TYPE',1);
class scanControl extends BaseHomeControl{

	public function indexOp(){
		if (empty($_GET['type'])) return ;
		foreach (explode('|',$_GET['type']) as $v) {
			$func = $v.'Op';
			if (method_exists($this,$func)){
				$this->$func();
			}
		}
	}

	
	private function updownOp(){
		$model = Model();
		$condition = array();
		$condition['goods_state'] = 0;
		$condition['goods_starttime'] = array('lt',TIMESTAMP);
		$condition['goods_endtime'] = array('gt',TIMESTAMP);
		
		
		$model->table('goods')->where($condition)->attr('LOW_PRIORITY')->update(array('goods_show'=>1));

		
		$data['goods_starttime'] = TIMESTAMP;
		$data['goods_endtime'] =array('exp',C('product_indate')*86400+TIMESTAMP);	
		$model->table('goods')->where(array('goods_show'=>1,'goods_endtime' => array('lt',TIMESTAMP)))->attr('LOW_PRIORITY')->update($data);
	}
}
