<?php



defined('haipinlegou') or exit('Access Invalid!');

class newIndexControl extends BaseHomeControl {

	public function indexOp(){

		Tpl::setLayout('null_layout');
		//新首页输出
		Tpl::showpage('newIndex');

	}

	
}

