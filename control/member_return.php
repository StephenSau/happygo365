<?php

defined('haipinlegou') or exit('Access Invalid!');

class member_returnControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_return');
		Language::read('member_member_index');
		$state_array = array(
			'1' => Language::get('return_state_confirm'),
			'2' => Language::get('return_state_yes'),
			'3' => Language::get('return_state_no')
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
		$model_return	= Model('return');
		$order_id	= intval($_GET['order_id']);
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$order_list = $model_order->getOrderList($condition);
		$order = $order_list[0];
		$order_id	= $order['order_id'];
		$order_goods_list= $model_return->getOrderGoodsList($order_id);
		
		if(is_array($order_goods_list) && !empty($order_goods_list)) {
			foreach ($order_goods_list as $key => $val) {
				$val['store_id'] = $order['store_id'];
				$order_goods_list[$key] = $val;
			}
		}
		Tpl::output('order',$order);
		Tpl::output('order_goods_list',$order_goods_list);
		$model_return	= Model('return');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$return_list = $model_return->getList($condition);
		$return = $return_list[0];
		Tpl::output('return',$return);
		if($return["return_state"] > 0) {
			Tpl::showpage('member_return_view','null_layout');
		}
		
		if (chksubmit()){
			$return_array = array();
			$goods_list = array();
			if(is_array($order_goods_list) && !empty($order_goods_list)) {
				$return_num = 0;
				$goods_num = 0;
				foreach ($order_goods_list as $key => $val) {
					$goods_id	= $val['goods_id'];
					$goods_num += $val['goods_num'];
					$return_goodsnum = intval($_POST["goods_".$goods_id]);
					if (($return_goodsnum > 0) && ($val['goods_num'] >= ($val['goods_returnnum']+$return_goodsnum))){
						$return_num += $return_goodsnum;
						$return_goods = array();
						$return_goods['goods_returnnum'] = $return_goodsnum;
						$return_goods['order_id'] = $val['order_id'];
						$return_goods['goods_id'] = $val['goods_id'];
						$return_goods['goods_name'] = $val['goods_name'];
						$return_goods['spec_id'] = $val['spec_id'];
						$return_goods['spec_info'] = $val['spec_info'];
						$return_goods['goods_price'] = $val['goods_price'];
						$return_goods['goods_num'] = $val['goods_num'];
						$return_goods['goods_image'] = $val['goods_image'];
						
						$goods_list[] = $return_goods;
					}
				}
				$return_array['return_goodsnum'] = $return_num;
				
				$return_array['order_id'] = $order['order_id'];
				$return_array['order_sn'] = $order['order_sn'];
				$return_array['seller_id'] = $order['seller_id'];
				$return_array['store_id'] = $order['store_id'];
				$return_array['store_name'] = $order['store_name'];
				$return_array['buyer_id'] = $order['buyer_id'];
				$return_array['buyer_name'] = $order['buyer_name'];
				
				$return_array['return_type'] = '1';
				$return_array['return_state'] = '1';
				
				$return_array['add_time'] = time()-4*3600;	
				$return_array['buyer_message'] = $_POST["buyer_message"];
                                
				$return_id = $model_return->add($return_array);
                              
	    	$order_array = array();
	    	$order_array['return_state'] = '1';
	    	Model()->table('order')->where(array('order_id'=>$order['order_id']))->update($order_array);
				if(is_array($goods_list) && !empty($goods_list)) {
					foreach ($goods_list as $key => $val) {
						$val['return_id'] = $return_id;
						$model_return->addGoods($val);
					}
				}
			}
			
			if($return_id) {
				showDialog(Language::get('return_add_success'),'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('return_add_fail'),'reload',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			}
		}
		Tpl::showpage('member_return_add','null_layout');
	}
	
	public function indexOp(){
		
		$model_return	= Model('return');
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['return_type'] = '1';
		
		$keyword_type = array('order_sn','return_sn','store_name');
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
		$return_list = $model_return->getList($condition,$page);
		$this->get_member_info();

		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		Tpl::output('return_list',$return_list);
		Tpl::output('show_page',$page->show());
		self::profile_menu('member_order','buyer_return');
		Tpl::output('menu_sign','myorder');
		Tpl::output('menu_sign_url','index.php?act=member&op=order');
		Tpl::output('menu_sign1','buyer_return');
		Tpl::showpage('member_return');
	}

	public function viewOp(){
		
		$model_return	= Model('return');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['return_id'] = intval($_GET["return_id"]);
		$return_list = $model_return->getList($condition);
		$return = $return_list[0];
		$order_goods_list= $model_return->getReturnGoodsList($condition);
		if(is_array($order_goods_list) && !empty($order_goods_list)) {
			foreach ($order_goods_list as $key => $val) {
				$val['store_id'] = $return['store_id'];
				$order_goods_list[$key] = $val;
			}
		}
		Tpl::output('return',$return);
		Tpl::output('order_goods_list',$order_goods_list);
		Tpl::showpage('member_return_view','null_layout');
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
