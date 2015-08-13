<?php

defined('haipinlegou') or exit('Access Invalid!');
class flea_albumControl extends BaseMemberControl {
	
	public function __construct() {
		parent::__construct();
	
		Language::read('member_store_album');
	}

	public function pic_listOp(){
		
		$page	= new Page();
		$page->setEachNum(12);
		$page->setStyle('admin');
		
		$model_upload = Model('flea_upload');
		
		$param = array();
		$param['store_id']	= $_SESSION['member_id'];
		$param['item_id']	= $_GET['goods_id'] ? $_GET['goods_id'] : '0';
		$pic_list = $model_upload->getUploadList($param,$page);
		Tpl::output('pic_list',$pic_list);
		Tpl::output('show_page',$page->show());

		if($_GET['item'] == 'goods'){
			Tpl::showpage('store_flea_sample','null_layout');
		}elseif ($_GET['item'] == 'des'){
			Tpl::showpage('store_flea_sample_des','null_layout');
		}
	}
}
