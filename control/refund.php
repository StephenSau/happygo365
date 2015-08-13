<?php

defined('haipinlegou') or exit('Access Invalid!');

class refundControl extends BaseMemberStoreControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_refund');
		$state_array = array(
			'1' => Language::get('refund_state_confirm'),
			'2' => Language::get('refund_state_yes'),
			'3' => Language::get('refund_state_no')
			);
		Tpl::output('state_array',$state_array);
		
	}
	
	public function indexOp(){
		
		$model_refund	= Model('refund');
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		
		$condition = array();
		$condition['seller_id'] = $_SESSION['member_id'];
		$condition['seller_refund_state'] = '2';
		$keyword_type = array('order_sn','refund_sn','buyer_name');
		if(trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)){
		//print_r('1');exit;
			$condition['type']	= $_GET['type'];
			$condition['keyword']	= $_GET['key'];
		}
		if(trim($_GET['add_time_from']) != ''){
			$add_time_from	= strtotime(trim($_GET['add_time_from']));
			if($add_time_from !== false){
				$condition['add_time_from']	= $add_time_from;
			}
		}
		if(trim($_GET['add_time_to']) != ''){
			$add_time_to	= strtotime(trim($_GET['add_time_to']));
			if($add_time_to !== false){
				$condition['add_time_to']	= $add_time_to+86400;
			}
		}
		$refund_list = $model_refund->getList($condition,$page);
                
		Tpl::output('refund_list',$refund_list);
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::output('show_page',$page->show());
		self::profile_menu('refund','refund');
		Tpl::output('menu_sign','store_refund');
		Tpl::output('menu_sign_url','index.php?act=refund');
		Tpl::output('menu_sign1','store_refund');
		Tpl::showpage('store_refund');
	}
	
	public function editOp(){
		
		$model_refund	= Model('refund');
		$condition = array();
		$condition['seller_id'] = $_SESSION['member_id'];
		$condition['log_id'] = intval($_GET["log_id"]);
		$condition['refund_type'] = '1';
		$refund_list = $model_refund->getList($condition);
		$refund = $refund_list[0];
	
		if (chksubmit()){
			if($refund['refund_state'] != '1') {
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$model_trade	= Model('trade');
			$refund_array = array();
			$refund_array['log_id'] = $refund['log_id'];
			$refund_array['order_id'] = $refund['order_id'];
			$refund_array['order_refund'] = $refund['order_refund'];
			$refund_array['seller_time'] = time();	
			$refund_array['refund_state'] = $_POST["refund_state"];
			$refund_array['refund_message'] = $_POST["refund_message"];
			if($refund_array['refund_state'] == '2' || $refund_array['refund_state'] == '3') {
				if($refund['refund_paymentcode'] == 'predeposit') $state = $model_trade->updateOrderRefund($refund_array);
				if($refund_array['refund_state'] == '3') $refund_array['refund_state'] = '0';
				if($refund['refund_paymentcode'] == 'offline') $state = $model_trade->updateOfflineRefund($refund_array);
			}
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
		Tpl::output('refund',$refund);
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::showpage('store_refund_edit','null_layout');
	}
	
	public function viewOp(){
		
		$model_refund	= Model('refund');
		$condition = array();
		$condition['seller_id'] = $_SESSION['member_id'];
		$condition['log_id'] = intval($_GET["log_id"]);
		$refund_list = $model_refund->getList($condition);
		$refund = $refund_list[0];
		Tpl::output('refund',$refund);
		//脚部文章输出
		$article = $this->_article();
		Tpl::showpage('store_refund_view','null_layout');
	}
	
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'refund':
				$menu_array	= array(
					1=>array('menu_key'=>'refund','menu_name'=>Language::get('nc_member_path_store_refund'),	'menu_url'=>'index.php?act=refund')
				);
				break;
			case 'seller_refund':
				$menu_array	= array(
					1=>array('menu_key'=>'seller_refund','menu_name'=>Language::get('nc_member_path_seller_refund'),	'menu_url'=>'index.php?act=refund&op=seller')
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
	
	//S脚部文章输出

	private function _article() {



		if (file_exists(BasePath.'/cache/index/article.php')){

			include(BasePath.'/cache/index/article.php');

			Tpl::output('show_article',$show_article);

			Tpl::output('article_list',$article_list);

			return ;		

		}

		$model_article_class	= Model('article_class');

		$model_article	= Model('article');

		$show_article = array();

		$article_list	= array();

		$notice_class	= array('notice','store','about');

		$code_array	= array('member','store','payment','sold','service','about');

		$notice_limit	= 5;

		$faq_limit	= 5;



		$class_condition	= array();

		$class_condition['home_index'] = 'home_index';

		$class_condition['order'] = 'ac_sort asc';

		$article_class	= $model_article_class->getClassList($class_condition);

		$class_list	= array();

		if(!empty($article_class) && is_array($article_class)){

			foreach ($article_class as $key => $val){

				$ac_code = $val['ac_code'];

				$ac_id = $val['ac_id'];

				$val['list']	= array();

				$class_list[$ac_id]	= $val;

			}

		}

		

		$condition	= array();

		$condition['article_show'] = '1';

		$condition['home_index'] = 'home_index';

		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article.article_content,article_class.ac_name,article_class.ac_parent_id';

		$condition['order'] = 'article_sort desc,article_time desc';

		$condition['limit'] = '300';

		$article_array	= $model_article->getJoinList($condition);



		if(!empty($article_array) && is_array($article_array)){

			foreach ($article_array as $key => $val){

				$ac_id = $val['ac_id'];

				$ac_parent_id = $val['ac_parent_id'];

				if($ac_parent_id == 0) {

					$class_list[$ac_id]['list'][] = $val;

				} else {

					$class_list[$ac_parent_id]['list'][] = $val;

				}

			}

		}

		if(!empty($class_list) && is_array($class_list)){

			foreach ($class_list as $key => $val){

				$ac_code = $val['ac_code'];

				if(in_array($ac_code,$notice_class)) {

					$list = $val['list'];

					array_splice($list, $notice_limit);

					$val['list'] = $list;

					$show_article[$ac_code] = $val;

				}

				if (in_array($ac_code,$code_array)){

					$list = $val['list'];

					$val['class']['ac_name']	= $val['ac_name'];

					array_splice($list, $faq_limit);

					$val['list'] = $list;

					$article_list[] = $val;

				}

			}

		}

		$string = "<?php\n\$show_article=".var_export($show_article,true).";\n";

		$string .= "\$article_list=".var_export($article_list,true).";\n?>";

		file_put_contents(BasePath.'/cache/index/article.php',compress_code($string));

		Tpl::output('show_article',$show_article);

		Tpl::output('article_list',$article_list);

	}
}
