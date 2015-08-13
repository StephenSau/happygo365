<?php

defined('haipinlegou') or exit('Access Invalid!');

class member_favoritesControl extends BaseMemberControl{
	public function __construct(){
        parent::__construct();
        Language::read('member_layout,member_member_favorites');
    }
	
	public function favoritesgoodsOp(){
		$fav_id = intval($_GET['fid']);
		if ($fav_id <= 0){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
		$favorites_model = Model('favorites');
		$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$fav_id",'fav_type'=>'goods','member_id'=>"{$_SESSION['member_id']}"));
		if(!empty($favorites_info)){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_already_favorite_goods','UTF-8')));
			die;
		}
		$goods_model = Model('goods');
		$goods_info = $goods_model->getOne($fav_id);
		if ($goods_info['store_id'] == $_SESSION['store_id']){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_no_my_product','UTF-8')));
			die;
		}
		$insert_arr = array();
		$insert_arr['member_id'] = $_SESSION['member_id'];
		$insert_arr['fav_id'] = $fav_id;
		$insert_arr['fav_type'] = 'goods';
		$insert_arr['fav_time'] = time();
		$result = $favorites_model->addFavorites($insert_arr);
		if ($result){
			$goods_model->updateGoodsAllUser(array('goods_collect'=>array('sign'=>'increase','value'=>'1')),$fav_id);
			echo json_encode(array('done'=>true,'msg'=>Language::get('favorite_collect_success','UTF-8')));
			die;
		}else{
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
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
	
	public function favoritesstoreOp(){
		$fav_id = intval($_GET['fid']);		
		if ($fav_id <= 0){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
		$favorites_model = Model('favorites');
		$favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$fav_id",'fav_type'=>'store','member_id'=>"{$_SESSION['member_id']}"));
		if(!empty($favorites_info)){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_already_favorite_store','UTF-8')));
			die;
		}
		if ($fav_id == $_SESSION['store_id']){
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_no_my_store','UTF-8')));
			die;
		}
		$insert_arr = array();
		$insert_arr['member_id'] = $_SESSION['member_id'];
		$insert_arr['fav_id'] = $fav_id;
		$insert_arr['fav_type'] = 'store';
		$insert_arr['fav_time'] = time();
		$result = $favorites_model->addFavorites($insert_arr);
		if ($result){
			$store_model = Model('store');
			$store_model->storeUpdate(array('store_id'=>$fav_id,'store_collect'=>array('sign'=>'increase','value'=>'1')),$fav_id);
			echo json_encode(array('done'=>true,'msg'=>Language::get('favorite_collect_success','UTF-8')));
			die;
		}else{
			echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
			die;
		}
	}
	

	public function fglistOp(){
		$favorites_model = Model('favorites');
		$show_type = 'favorites_goods_picshowlist';
		$show = $_GET['show'];
		$store_array = array('list'=>'favorites_goods_index','pic'=>'favorites_goods_picshowlist','store'=>'favorites_goods_shoplist');
		if (array_key_exists($show,$store_array)) $show_type = $store_array[$show];
		$page	= new Page();
		$page->setEachNum(20);
		$page->setStyle('admin');
		$favorites_list = $favorites_model->getFavoritesList(array('member_id'=>"{$_SESSION['member_id']}",'fav_type'=>'goods'),$page);
		if (!empty($favorites_list) && is_array($favorites_list)){
			$favorites_id = array();
			foreach ($favorites_list as $key=>$favorites){
				$fav_id = $favorites['fav_id'];
				$favorites_id[] = $favorites['fav_id'];
				$favorites_key[$fav_id] = $key;
			}
			$goods_model = Model('goods');
			$goods_list = $goods_model->getGoods(array('goods_id_in'=>"'".implode("','",$favorites_id)."'"),'',
			'goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.goods_store_price,goods.commentnum,goods.salenum,goods.goods_collect,store.store_name,store.member_id,store.member_name,store.store_qq,store.store_ww','store');
			$store_array = array();
			if (!empty($goods_list) && is_array($goods_list)){
				$store_goods_list = array();
				foreach ($goods_list as $key=>$fav){
					$fav_id = $fav['goods_id'];
					$fav['goods_member_id'] = $fav['member_id'];
					$key = $favorites_key[$fav_id];
					$favorites_list[$key]['goods'] = $fav;
					$store_id = $fav['store_id'];
					if (!in_array($store_id,$store_array)) $store_array[] = $store_id;
					$store_goods_list[$store_id][] = $favorites_list[$key];
				}
			}
			$store_favorites = array();
			if (!empty($store_array) && is_array($store_array)){
				$store_list = $favorites_model->getFavoritesList(array('member_id'=>"{$_SESSION['member_id']}",'fav_type'=>'store','fav_id_in'=>"'".implode("','",$store_array)."'"));
				if (!empty($store_list) && is_array($store_list)){
					foreach ($store_list as $key=>$val){
						$store_id = $val['fav_id'];
						$store_favorites[] = $store_id;
					}
				}
			}
		}
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		$this->get_member_info();
		self::profile_menu('favorites','favorites');
		Tpl::output('menu_key',"fav_goods");
		Tpl::output('favorites_list',$favorites_list);
		Tpl::output('store_favorites',$store_favorites);
		Tpl::output('store_goods_list',$store_goods_list);
		Tpl::output('show_page',$page->show());
		Tpl::output('menu_sign','collect_list');
		Tpl::showpage($show_type);
	}
	
	public function fslistOp(){
		$favorites_model = Model('favorites');
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$favorites_list = $favorites_model->getFavoritesList(array('member_id'=>"{$_SESSION['member_id']}",'fav_type'=>'store'),$page);
		if (!empty($favorites_list) && is_array($favorites_list)){
			$favorites_id = array();
			foreach ($favorites_list as $key=>$favorites){
				$fav_id = $favorites['fav_id'];
				$favorites_id[] = $favorites['fav_id'];
				$favorites_key[$fav_id] = $key;
			}
			$store_model = Model('store');
			$store_list = $store_model->getStoreList(array('store_id_in'=>"'".implode("','",$favorites_id)."'"));
			$store_list = $store_model->getStoreInfoBasic($store_list,30);
			if (!empty($store_list) && is_array($store_list)){
				foreach ($store_list as $key=>$fav){
					$fav_id = $fav['store_id'];
					$key = $favorites_key[$fav_id];
					$favorites_list[$key]['store'] = $fav;
				}
			}
		}
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		$this->get_member_info();
		self::profile_menu('favorites','favorites');
		Tpl::output('menu_key',"fav_store");
		Tpl::output('favorites_list',$favorites_list);
		Tpl::output('show_page',$page->show());		
		Tpl::output('menu_sign','collect_store');
		Tpl::showpage("favorites_store_index");
	}
	
	public function delfavoritesOp(){
		if (!$_GET['fav_id'] || !$_GET['type']){
			showDialog(Language::get('member_favorite_del_fail'),'','error');
		}
		$fav_id = $_GET['fav_id'];
		$type = $_GET['type'];
		$favorites_model = Model('favorites');
		$fav_arr = explode(',',$_GET['fav_id']);

		if (!empty($fav_arr) && is_array($fav_arr)){
			$fav_str = "'".implode("','",$fav_arr)."'";
			$result = $favorites_model->delFavorites(array('fav_id_in'=>"$fav_str",'fav_type'=>"$type",'member_id'=>"{$_SESSION['member_id']}"));
			if ($result){
				$favorites_list = $favorites_model->getFavoritesList(array('fav_id_in'=>"$fav_str",'fav_type'=>"$type",'member_id'=>"{$_SESSION['member_id']}"));
				if (!empty($favorites_list)){
					foreach ($favorites_list as $k=>$v){
						unset($fav_arr[array_search($v['fav_id'],$fav_arr)]);
					}
				}
				if (!empty($fav_arr)){
					if ($type=='goods'){
						$fav_str = "'".implode("','",$fav_arr)."'";
						$goods_model = Model('goods');
						$goods_model->updateGoods(array('goods_collect'=>array('sign'=>'decrease','value'=>'1')),$fav_arr);
						showDialog(Language::get('favorite_del_success'),'index.php?act=member_favorites&op=fglist&show='.$_GET['show'],'succ');
					}else {
						$fav_str = "'".implode("','",$fav_arr)."'";
						$store_model = Model('store');
						$store_model->updateByCondtion(array('store_collect'=>array('sign'=>'decrease','value'=>'1')),array('store_id_in'=>$fav_str));
						showDialog(Language::get('favorite_del_success'),'index.php?act=member_favorites&op=fslist','succ');
					}
				}
			}else {
				showDialog(Language::get('favorite_del_fail'),'','error');
			}
			
		}else {
			showDialog(Language::get('member_favorite_del_fail'),'','error');
		}
	}
	
	public function store_goodsOp(){
		$store_id = intval($_GET["store_id"]);
		if($store_id > 0) {
			$condition = array();
			$add_time_to = date("Y-m-d");//当前日期
			$add_time_from = date("Y-m-d",(strtotime($add_time_to)-60*60*24*30));//30天
			$condition['store_id'] = $store_id;
			$condition['goods_show'] = 1;
			$condition['add_time_from']	= strtotime($add_time_from);
			$condition['add_time_to']	= time();
			$condition['limit'] = 50;
			$goods_model = Model('goods');
			$goods_list = $goods_model->getGoods($condition,'','goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.goods_store_price','goods');
			Tpl::output('goods_list',$goods_list);
			Tpl::showpage('favorites_store_goods','null_layout');
		}
	}
	
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array = array(
			1=>array('menu_key'=>'fav_goods','menu_name'=>Language::get('nc_member_path_collect_list'),	'menu_url'=>'index.php?act=member_favorites&op=fglist'),
			2=>array('menu_key'=>'fav_store','menu_name'=>Language::get('nc_member_path_collect_store'), 'menu_url'=>'index.php?act=member_favorites&op=fslist')
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
