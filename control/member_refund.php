<?php

defined('haipinlegou') or exit('Access Invalid!');

class member_refundControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_member_index');
		Language::read('member_refund');
		
		$state_array = array(
			'1' => Language::get('refund_state_confirm'),
			'2' => Language::get('refund_state_yes'),
			'3' => Language::get('refund_state_no')
			);
		Tpl::output('state_array',$state_array);
	}

	//S脚部内容显示
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
		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';
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
    //E脚部内容显示
	
	public function addOp(){
		$model_order = Model('order');
		$model_refund	= Model('refund');
		$order_id	= intval($_GET["order_id"]);
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$order_list = $model_order->getOrderList($condition);
		$order = $order_list[0];
		Tpl::output('order',$order);
		$order_amount = $order['order_amount'];
		$condition = array();
		$condition['buyer_id'] = $order['buyer_id'];
		$condition['order_id'] = $order['order_id'];
		$condition['refund_type'] = '1';
		$refund_list = $model_refund->getList($condition);
		$refund = array();
		if(!empty($refund_list) && is_array($refund_list)) {
			$refund = $refund_list[0];
			$log_id = $refund["log_id"];
			Tpl::output('refund',$refund);
		}
		
		
		if (chksubmit()){
			if(!(($order['order_state'] >= 20 && $order['order_state'] < 40) && ($order['payment_code'] == 'predeposit' || C('payment') == 0))) {
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$order_refund = floatval($_POST["order_refund"]);
			$refund_array = array();
			if (($order_refund < 0) || ($order_refund > $order_amount)) $order_refund = $order_amount;
			$refund_array['order_refund'] = ncPriceFormat($order_refund);
			$refund_array['buyer_message'] = $_POST["buyer_message"];
			if($log_id > 0) {
				$condition = array();
				$condition['log_id'] = $log_id;
				if($refund["refund_state"] == 1) $state = $model_refund->update($condition,$refund_array);
			} else {
				$refund_paymentcode = 'predeposit';
				$refund_array['refund_type'] = '1';
				$refund_array['refund_state'] = '1';
				$refund_array['order_amount'] = $order_amount;
				
				$refund_array['order_id'] = $order['order_id'];
				$refund_array['order_sn'] = $order['order_sn'];
				$refund_array['seller_id'] = $order['seller_id'];
				$refund_array['store_id'] = $order['store_id'];
				$refund_array['store_name'] = $order['store_name'];
				$refund_array['buyer_id'] = $order['buyer_id'];
				$refund_array['buyer_name'] = $order['buyer_name'];
				
				$refund_array['add_time'] = time();	
				$refund_array['refund_paymentname'] = Language::get('refund_payment_'.$refund_paymentcode);
				$refund_array['refund_paymentcode'] = $refund_paymentcode;
				$state = $model_refund->add($refund_array);
	    	$order_array = array();
	    	$order_array['refund_state'] = '1';
	    	if($state) $state = Model()->table('order')->where(array('order_id'=>$order['order_id']))->update($order_array);
			}
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
		if($refund["refund_state"] > 1) {
			Tpl::showpage('member_refund_view','null_layout');
		}
		Tpl::showpage('member_refund_add','null_layout');
	}
	
	public function indexOp(){
		
		$model_refund	= Model('refund');
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['refund_type'] = '1';
		
		$keyword_type = array('order_sn','refund_sn','store_name');
		if(trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)){
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
		$this->get_member_info();
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		Tpl::output('refund_list',$refund_list);
		Tpl::output('show_page',$page->show());
		self::profile_menu('member_order','buyer_refund');
		Tpl::output('menu_sign','myorder');
		Tpl::output('menu_sign_url','index.php?act=member&op=order');
		Tpl::output('menu_sign1','buyer_refund');
		Tpl::showpage('member_refund');
	}
	
	public function viewOp(){
		
		$model_refund	= Model('refund');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['refund_type'] = '1';
		$condition['log_id'] = intval($_GET["log_id"]);
		$refund_list = $model_refund->getList($condition);
		$refund = $refund_list[0];
		Tpl::output('refund',$refund);
		Tpl::showpage('member_refund_view','null_layout');
	}
	
	public function buyer_confirmOp(){
		
		$model_refund	= Model('refund');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['refund_type'] = '1';
		$condition['log_id'] = intval($_GET["log_id"]);
		$refund_list = $model_refund->getList($condition);
		$refund = $refund_list[0];
		Tpl::output('refund',$refund);
		
		if (chksubmit()){
			if($refund['buyer_confirm'] != '1') {
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$model_trade	= Model('trade');
			$refund_array = array();
			$refund_array['log_id'] = $refund['log_id'];
			$refund_array['order_id'] = $refund['order_id'];
			$refund_array['order_refund'] = $refund['order_refund'];
			$refund_array['confirm_time'] = time();	
			$refund_array['buyer_confirm'] = $_POST["buyer_confirm"];
			if($refund_array['buyer_confirm'] == '2') {
				if($refund['refund_paymentcode'] == 'offline') $state = $model_trade->updateOfflineRefund($refund_array);
			}
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
		Tpl::showpage('member_refund_buyer_confirm','null_layout');
	}
	
	public function offline_addOp(){
		$model_order = Model('order');
		$model_refund	= Model('refund');
		$order_id	= intval($_GET["order_id"]);
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$order_list = $model_order->getOrderList($condition);
		$order = $order_list[0];
		Tpl::output('order',$order);
		$order_amount = $order['order_amount'];
		$condition = array();
		$condition['buyer_id'] = $order['buyer_id'];
		$condition['order_id'] = $order['order_id'];
		$condition['refund_type'] = '1';
		$refund_list = $model_refund->getList($condition);
		$refund = array();
		if(!empty($refund_list) && is_array($refund_list)) {
			$refund = $refund_list[0];
			$log_id = $refund["log_id"];
			Tpl::output('refund',$refund);
		}
		
		if (chksubmit()){
			if($order['order_state'] < 20 || $order['order_state'] > 30 || $order['payment_code'] == 'predeposit' || $order['payment_code'] == 'cod' || C('payment') == 0) {//检查订单状态,防止页面刷新不及时造成数据错误
				showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
			}
			$order_refund = floatval($_POST["order_refund"]);
			$refund_array = array();
			if (($order_refund < 0) || ($order_refund > $order_amount)) $order_refund = $order_amount;
			$refund_array['order_refund'] = ncPriceFormat($order_refund);
			$refund_array['buyer_message'] = $_POST["buyer_message"];
			if($log_id > 0) {
				$condition = array();
				$condition['log_id'] = $log_id;
				if($refund["refund_state"] == 1) $state = $model_refund->update($condition,$refund_array);
			} else {
				$refund_paymentcode = 'offline';
				$refund_array['refund_type'] = '1';
				$refund_array['refund_state'] = '1';
				$refund_array['buyer_confirm'] = '1';
				$refund_array['order_amount'] = $order_amount;
				
				$refund_array['order_id'] = $order['order_id'];
				$refund_array['order_sn'] = $order['order_sn'];
				$refund_array['seller_id'] = $order['seller_id'];
				$refund_array['store_id'] = $order['store_id'];
				$refund_array['store_name'] = $order['store_name'];
				$refund_array['buyer_id'] = $order['buyer_id'];
				$refund_array['buyer_name'] = $order['buyer_name'];
				
				$refund_array['add_time'] = time();	
				$refund_array['refund_paymentname'] = Language::get('refund_payment_'.$refund_paymentcode);
				$refund_array['refund_paymentcode'] = $refund_paymentcode;
				$state = $model_refund->add($refund_array);
	    	$order_array = array();
	    	$order_array['refund_state'] = '1';
	    	if($state) $state = Model()->table('order')->where(array('order_id'=>$order['order_id']))->update($order_array);
			}
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
			}
		}
		
		if($refund["refund_state"] > 1) {
			Tpl::showpage('member_refund_view','null_layout');
		}
		Tpl::showpage('member_refund_offline_add','null_layout');
	}
	
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'member_order':
				$menu_array = array(
				1=>array('menu_key'=>'member_order','menu_name'=>Language::get('nc_member_path_order_list'),	'menu_url'=>'index.php?act=member&op=order'),
				2=>array('menu_key'=>'buyer_refund','menu_name'=>Language::get('nc_member_path_buyer_refund'),	'menu_url'=>'index.php?act=member_refund'),
				3=>array('menu_key'=>'buyer_return','menu_name'=>Language::get('nc_member_path_buyer_return'),	'menu_url'=>'index.php?act=member_return'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
