<?php

defined('haipinlegou') or exit('Access Invalid!');

class returnControl extends BaseMemberStoreControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_return');
		$state_array = array(
			'1' => Language::get('return_state_confirm'),
			'2' => Language::get('return_state_yes'),
			'3' => Language::get('return_state_no')
			);
		Tpl::output('state_array',$state_array);
	}
	
	public function editOp(){
		$model_return	= Model('return');
		$condition = array();
		$condition['seller_id'] = $_SESSION['member_id'];
		$condition['return_id'] = intval($_GET["return_id"]);
		$return_list = $model_return->getList($condition);
		$return = $return_list[0];
		$order_id	= $return['order_id'];
		$order_goods_list= $model_return->getReturnGoodsList($condition);
		if(is_array($order_goods_list) && !empty($order_goods_list)) {
			foreach ($order_goods_list as $key => $val) {
				$val['store_id'] = $return['store_id'];
				$order_goods_list[$key] = $val;
			}
		}
		Tpl::output('return',$return);
		Tpl::output('order_goods_list',$order_goods_list);
		
		if (chksubmit()){
			$return_array = array();
			$goods_list = array();
			if(is_array($order_goods_list) && !empty($order_goods_list)) {
				$return_num = 0;
				$goods_num = 0;
				$model_order = Model('order');
				foreach ($order_goods_list as $key => $val) {
					$goods_id	= $val['goods_id'];
					$return_goodsnum = intval($val['goods_returnnum']);
					if (($return_goodsnum > 0) && ($val['goods_num'] >= $return_goodsnum)){
						$return_num += $return_goodsnum;
						$param = array();
						$param['goods_returnnum'] = $return_goodsnum;
						$model_order->updateOrderGoods($param,$val['rec_id']);
					}
				}
				if($return_num > 0) {
					$goods_num = $model_return->getOrderGoodsCount($order_id);
					
					$array['return_num'] = $return_num;
					$array['return_state'] = ($goods_num-$array['return_num'])?1:2;
					$state = $model_order->updateOrder($array,$order_id);
				}
			}
			
			if($state) {
				$return_array['return_goodsnum'] = $return_num;
				$condition = array();
				$condition['return_id'] = intval($_GET["return_id"]);
				$return_array['seller_time'] = time()-4*3600;	
				$return_array['return_state'] = $_POST["return_state"];
				$return_array['return_message'] = $_POST["return_message"];
				$model_return->update($condition,$return_array);
				showDialog(Language::get('return_add_success'),'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog(Language::get('return_add_fail'),'reload',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			}
		}
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::showpage('store_return_edit','null_layout');
	}
	
	public function indexOp(){
		
		$model_return	= Model('return');
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		
		$condition = array();
		$condition['seller_id'] = $_SESSION['member_id'];
		$condition['seller_return_state'] = '2';
		$keyword_type = array('order_sn','refund_sn','buyer_name');
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
		Tpl::output('last_num',count($return_list)-1);
		Tpl::output('return_list',$return_list);
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::output('show_page',$page->show());
		self::profile_menu('return','return');
		Tpl::output('menu_sign','store_return');
		Tpl::output('menu_sign_url','index.php?act=return');
		Tpl::output('menu_sign1','store_return');
		Tpl::showpage('store_return');
	}
	
	public function viewOp(){
		
		$model_return	= Model('return');
		$condition = array();
		$condition['seller_id'] = $_SESSION['member_id'];
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
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::output('return',$return);
		Tpl::output('order_goods_list',$order_goods_list);
		Tpl::showpage('store_return_view','null_layout');
	}
	
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'return':
				$menu_array	= array(
					1=>array('menu_key'=>'return','menu_name'=>Language::get('nc_member_path_store_return'),	'menu_url'=>'index.php?act=return')
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
