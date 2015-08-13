<?php



defined('haipinlegou') or exit('Access Invalid!');

class baby_productControl extends BaseHomeControl {

	public function indexOp(){

		Tpl::setLayout('null_layout');
		//新首页输出
		Tpl::showpage('baby_product');

	}

	
}

