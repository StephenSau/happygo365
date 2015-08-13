<?php

defined('haipinlegou') or exit('Access Invalid!');

class store_gbuyControl extends BaseMemberStoreControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_store_gbuy');
		
		$gold_isuse	= $GLOBALS['setting_config']['gold_isuse'];
		if($gold_isuse != '1') {
			showMessage(Language::get('store_gbuy_gold_isuse'),'index.php?act=store','html','error');
		}
	}

	public function paymentOp(){
	
		$model_gbuy	= Model('gold_buy');
	
		$payment_list = $this->getPayment(1);
	
		if (chksubmit()){
			$gbuy_id = intval($_POST["gbuy_id"]);
			$payment_code = $_POST["gbuy_check_type"];
			$condition = array();
			$condition['gbuy_id'] = $gbuy_id;
			$condition['gbuy_ispay'] = '0';
			$condition['gbuy_mid'] = $_SESSION['member_id'];
			$gold_buy = array();
			$gold_buy['gbuy_check_type'] = $payment_code;
			$gold_buy['gbuy_user_remark'] = $_POST["gbuy_user_remark"];
			$state = $model_gbuy->update($condition,$gold_buy);
			if($payment_code != 'offline' && $state) {
				switch ($payment_code){
					case 'predeposit':
						
						@header("Location: index.php?act=gold_payment&op=predeposit_pay&gbuy_id=$gbuy_id");
						exit;
						break;
					default:
						break;
				}
				
				$payment_info = array();
				foreach ($payment_list as $k => $v){
					if ($payment_code == $v['payment_code']){
						$payment_info = $v;
						$payment_info['payment_config'] = unserialize($v['payment_config']);
						break;
					}
				}
				$gbuy_list = $model_gbuy->getList($condition);
				$gold_buy = $gbuy_list[0];
				$order_info = array();
				$order_info['order_sn'] = $gbuy_id;
				$order_info['order_amount'] = $gold_buy['gbuy_price'];
				$order_info['modeltype']		= '1';
				$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
				
				require_once($inc_file);
				$payment_api = new $payment_code($payment_info,$order_info);
				if($payment_code == 'chinabank') {
					$payment_api->submit();
					exit;
				} else {
					@header("Location: ".$payment_api->get_payurl());
					exit;
				}
			} else {
				showMessage(Language::get('store_gbuy_edit_success'),'index.php?act=store_gbuy');
			}
		}
		$gbuy_id = intval($_GET["gbuy_id"]);
		if($gbuy_id > 0) {
			$condition = array();
			$condition['gbuy_id'] = $gbuy_id;
			$condition['gbuy_ispay'] = '0';
			$condition['gbuy_mid'] = $_SESSION['member_id'];
			$gbuy_list = $model_gbuy->getList($condition);
			Tpl::output('gold_buy',$gbuy_list[0]);
		
			$member_model = Model('member');
			$member_info = $member_model->infoMember(array('member_id'=> $_SESSION['member_id']),'member_id,available_predeposit');
			Tpl::output('member_info',$member_info);
		}
		Tpl::output('payment_list',$payment_list);
		Tpl::showpage('store_gbuy_payment','null_layout');
	}

	public function addOp(){
	
		$model_gbuy	= Model('gold_buy');
		
		$gold_rmbratio	= $GLOBALS['setting_config']['gold_rmbratio'];
		if (chksubmit()){
			$gbuy_price = intval($_POST["gbuy_price"]);
			$gbuy_num = $gold_rmbratio*$gbuy_price;
			$gold_buy = array();
			$gold_buy['gbuy_num'] = $gbuy_num;
			$gold_buy['gbuy_price'] = $gbuy_price;
			$gold_buy['gbuy_addtime'] = time();	
			$gold_buy['gbuy_mid'] = $_SESSION['member_id'];
			$gold_buy['gbuy_membername'] = $_SESSION['member_name'];
			$gold_buy['gbuy_storeid'] = $_SESSION['store_id'];
			$gold_buy['gbuy_storename'] = $_SESSION['store_name'];
			$gold_buy['gbuy_ispay'] = '0';
			$gold_buy['gbuy_check_type'] = '';
			$state = $model_gbuy->add($gold_buy);
			if($state) {
				showDialog(Language::get('nc_common_save_succ'),'index.php?act=store_gbuy','succ',empty($_GET['inajax'])?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('nc_common_save_fail'));
			}
		}
		Tpl::output('gold_rmbratio',$gold_rmbratio);
		Tpl::showpage('store_gbuy_add','null_layout');
	}
	
	public function delOp(){
		$gbuy_id = intval($_GET["gbuy_id"]);
		if($gbuy_id > 0) {
		
			$model_gbuy	= Model('gold_buy');
			$condition = array();
			$condition['gbuy_mid'] = $_SESSION['member_id'];
			$condition['gbuy_id'] = $gbuy_id;
			$condition['gbuy_ispay'] = '0';
			$model_gbuy->del($condition);
		}
		showDialog(Language::get('nc_common_del_succ'),'index.php?act=store_gbuy','succ');
	}
	
	public function indexOp(){
	
		$member_model = Model('member');
		$member_array = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
	
		$model_gbuy	= Model('gold_buy');
	
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		$payment_array = $this->getPayment_name();
		$condition = array();
		$condition['gbuy_mid'] = $_SESSION['member_id'];
		
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
		$gbuy_list = $model_gbuy->getList($condition,$page);				//脚部文章输出		$article = $this->_article();
		Tpl::output('last_num',count($gbuy_list)-1);
		Tpl::output('member_goldnum',$member_array['member_goldnum']);
		Tpl::output('gbuy_list',$gbuy_list);
		Tpl::output('payment_array',$payment_array);
		Tpl::output('show_page',$page->show());
		self::profile_menu('gold_buy','gold_buy');
		Tpl::output('menu_sign','store_gbuy');
		Tpl::output('menu_sign_url','index.php?act=store_gbuy');
		Tpl::output('menu_sign1','gold_buy');
		Tpl::showpage('store_gbuy');
	}
	
	public function gold_logOp(){
		$condition	= array();
		$condition['glog_memberid'] = $_SESSION['member_id'];
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
		if(trim($_GET['method']) != ''){
			$condition['glog_method']	= $_GET['method'];
		}
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		$model_log	= Model('gold_log');
		$glog_list	= $model_log->getList($condition,$page);
		//脚部文章输出		$article = $this->_article();
		Tpl::output('last_num',count($glog_list)-1);
		Tpl::output('glog_list',$glog_list);
		Tpl::output('show_page',$page->show());
		Tpl::output('search',$_GET);
		self::profile_menu('gold_buy','gold_log');
		Tpl::output('menu_sign','store_gbuy');
		Tpl::output('menu_sign_url','index.php?act=store_gbuy');
		Tpl::output('menu_sign1','gold_log');
		Tpl::showpage('gold_log');
	}

	private function getPayment($payment_state = '') {
	
		$model_payment = Model('gold_payment');
		
		$condition = array();
		if($payment_state > 0) $condition['payment_state'] = '1';
		$payment_list = $model_payment->getList($condition);

		return $payment_list;
	}
	
	private function getPayment_name() {
		$payment_list = $this->getPayment();
		$payment_array = array();
		foreach ($payment_list as $k => $v){
			$payment_code = $v['payment_code'];
			$payment_array[$payment_code] = $v['payment_name'];
		}
		return $payment_array;
	}
	

	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'gold_buy':
				$menu_array	= array(
					1=>array('menu_key'=>'gold_buy','menu_name'=>Language::get('nc_member_path_gold_buy'),	'menu_url'=>'index.php?act=store_gbuy'),
					2=>array('menu_key'=>'gold_log','menu_name'=>Language::get('nc_member_path_gold_log'),	'menu_url'=>'index.php?act=store_gbuy&op=gold_log')
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}		//文章输出	private function _article() {		if (file_exists(BasePath.'/cache/index/article.php')){			include(BasePath.'/cache/index/article.php');			Tpl::output('show_article',$show_article);			Tpl::output('article_list',$article_list);			return ;				}		$model_article_class	= Model('article_class');		$model_article	= Model('article');		$show_article = array();//商城公告		$article_list	= array();//下方文章		$notice_class	= array('notice','store','about');		$code_array	= array('member','store','payment','sold','service','about');		$notice_limit	= 5;		$faq_limit	= 5;		$class_condition	= array();		$class_condition['home_index'] = 'home_index';		$class_condition['order'] = 'ac_sort asc';		$article_class	= $model_article_class->getClassList($class_condition);		$class_list	= array();		if(!empty($article_class) && is_array($article_class)){			foreach ($article_class as $key => $val){				$ac_code = $val['ac_code'];				$ac_id = $val['ac_id'];				$val['list']	= array();//文章				$class_list[$ac_id]	= $val;			}		}				$condition	= array();		$condition['article_show'] = '1';		$condition['home_index'] = 'home_index';		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';		$condition['order'] = 'article_sort desc,article_time desc';		$condition['limit'] = '300';		$article_array	= $model_article->getJoinList($condition);		if(!empty($article_array) && is_array($article_array)){			foreach ($article_array as $key => $val){				$ac_id = $val['ac_id'];				$ac_parent_id = $val['ac_parent_id'];				if($ac_parent_id == 0) {//顶级分类					$class_list[$ac_id]['list'][] = $val;				} else {					$class_list[$ac_parent_id]['list'][] = $val;				}			}		}		if(!empty($class_list) && is_array($class_list)){			foreach ($class_list as $key => $val){				$ac_code = $val['ac_code'];				if(in_array($ac_code,$notice_class)) {					$list = $val['list'];					array_splice($list, $notice_limit);					$val['list'] = $list;					$show_article[$ac_code] = $val;				}				if (in_array($ac_code,$code_array)){					$list = $val['list'];					$val['class']['ac_name']	= $val['ac_name'];					array_splice($list, $faq_limit);					$val['list'] = $list;					$article_list[] = $val;				}			}		}		$string = "<?php\n\$show_article=".var_export($show_article,true).";\n";		$string .= "\$article_list=".var_export($article_list,true).";\n?>";		file_put_contents(BasePath.'/cache/index/article.php',compress_code($string));		Tpl::output('show_article',$show_article);		Tpl::output('article_list',$article_list);	}
}
