<?php

defined('haipinlegou') or exit('Access Invalid!');
class brandControl extends BaseHomeControl {
	public function indexOp(){
		Language::read('home_brand_index');
		$nav_link = array(
			0=>array(
				'title'=>Language::get('homepage'),
				'link'=>'index.php'
			),
			1=>array(
				'title'=>Language::get('brand_index_all_brand')
			)
		);
		
		Tpl::output('nav_link_list',$nav_link);
		$model_store = Model('store');
		$store_r_list = $model_store->getRecommendStore(5);
		Tpl::output('store_r',$store_r_list);
		$model = Model();
		$brand_c_list = $model->table('brand')->where(array('brand_apply'=>'1'))->order('brand_sort')->select();
	 if (!empty($brand_c_list) && is_array($brand_c_list)){
	 
			 foreach($brand_c_list as $k=>$v)
			 {
					$class[] = $v['brand_class'];
			 }
		 }
		 $class = array_unique($class);
		 foreach($class as $k=>$v)
		{		
			 foreach($brand_c_list as $ka=>$va)
			{
					if($v==$va['brand_class']){
						$recommend[$v][] = $ka;
						$recommend[$v]=array_slice($recommend[$v],0,6);
					}
			}

		}

			
		$goods_class_model = Model('goods_class');	
		$goods_model = Model('goods');	
		$brand_list = $goods_class_model->table('goods_class')->field(array('gc_id','gc_name'))->select();
		
		foreach($brand_list as $key=> $v_brand){
			
		 // $brand_list1[] = $goods_model->where(array('gc_id'=>$v_brand['gc_id']))->field('brand_id,gc_name')->select();
		 }
		// print_r($brand_list);die;
		// $brand_list1 = $goods_model->field('brand_id,gc_name,')->select();
		//print_R($brand_list1);die;
		
		// foreach($brand_list1 as $key=>$brand_list){
		
			
				// $list2[] = $model-> where(array('brand_id'=>$brand_list[0]['brand_id']))->select();

				
		// }
		
		
	
		//Tpl::output('brand_c',$brand_listnew);
		// print_r($brand_r_list);exit;
		//Tpl::output('brand_r',$brand_r_list);

		//文章输出
		$list = $this->_article();
		Tpl::output('recommend',$recommend);
		Tpl::output('brand_list',$brand_list);
		Tpl::output('brand_r',$brand_c_list);
		Tpl::output('html_title',Language::get('brand_index_brand_list'));
		Tpl::output('index_sign','brand');
		Model('seo')->type('brand')->show();
		Tpl::showpage('brand');
	}
	
	public function listOp(){
		Language::read('home_brand_index');
		$lang	= Language::getLangContent();
		$model_brand = Model('brand');
		$brand_id = intval($_GET['brand']);
		$brand_lise = $model_brand->getOneBrand($brand_id);
		if(!$brand_lise){
			showMessage($lang['wrong_argument'],'index.php','html','error');
		}
		
		
		$model_type			= Model('type');
		

		if (!empty($_GET['price'])){
			$price = explode('-',$_GET['price']);
			if (intval($price[0]) > 0 || intval($price[1]) > 0){
				$price_interval[0] = empty($price[0]) ? 0 : $price[0];
				$price_interval[1] = empty($price[1]) ? 0 : $price[1];
			}
			Tpl::output('price_interval',$price_interval);
		}

		
		if (!empty($_GET['key'])){
			$order_tmp = trim($_GET['key']);
			$order = '';
			switch ($order_tmp){
				case 'sales':
					$order = 'salenum';
					break;
				case 'credit':
					$order = 'store_credit';
					break;
				case 'price':
					$order = 'goods_store_price';
					break;
				case 'click':
					$order = 'goods_click';
					break;
			}
			$order .=' '.trim($_GET['order']);
		}
		
		$brand_class = Model('brand');
		$brand_r_list = $brand_class->getBrandList(array(
			'brand_recommend'=>1,
			//'field'=>'brand_id,brand_name,brand_pic,sort_pic',
			'field'=>'brand_id,brand_name,brand_pic',
			'brand_apply'=>1,
			'limit'=>'0,10'
		));
		Tpl::output('brand_r',$brand_r_list);
	
		if(isset($_GET['form'])){
			switch (intval($_GET['form'])){
				case 1:
					$form		= $lang['brand_form_new'];
					$goods_form	= 1;
					break;
				case 2:
					$form		= $lang['brand_form_used'];
					$goods_form	= 2;
					break;
			}
			Tpl::output('form',$form);
		}
		
	
		$xianshi_flag = 0;
		$group_flag = 0;
		if (!empty($_GET['promotion'])){
			$promotion = '';
			switch ($_GET['promotion']){
				case 'xianshi':
					$promotion = $lang['nc_xianshi'];
					$xianshi_flag = 1;
					break;
				case 'groupbuy':
					$promotion = $lang['brand_index_groupbuy'];
					$group_flag = 1;
					break;
			}
		}
		Tpl::output('promotion',$promotion);
		
	
		$page	= new Page();
		$page->setEachNum(40);
		$page->setStyle('admin');
		
		
		if(intval($_GET['area_id']) > 0){
			$area_id = intval($_GET['area_id']);
		}

		
		$model_goods = Model('goods');
		$ext_order = C('promotion_allow') ? 'xianshi_flag desc,goods_id desc' : 'goods_id desc'; 
		$fieldstr = " goods.goods_id,goods.goods_name,goods.gc_id,goods.gc_name,goods.store_id,goods.goods_image,goods.goods_store_price,
		goods.goods_click,goods.goods_state,goods.goods_commend,goods.commentnum,goods.salenum,goods.goods_goldnum,goods.goods_isztc,
		goods.goods_ztcstartdate,goods.goods_ztclastdate,goods.group_flag,goods.group_price,goods.xianshi_flag,goods.xianshi_discount,
		goods.city_id,goods.province_id,goods.kd_price,goods.py_price,goods.es_price,
		store.store_name,store.grade_id,store.store_domain,store.store_credit,store.praise_rate,store.store_desccredit";
		$goods_list = $model_goods->getGoods(array(
				'brand_id'=>$brand_id,
				'price'=>$price_interval,
				'group_flag'=>$group_flag,
				'xianshi_flag'=>$xianshi_flag,
				'province_id'=>$area_id,
				'goods_show'=>'1',
				'goods_form'=>$goods_form,
				'order'=>$order ? $order.','.$ext_order:$ext_order,
		),$page,$fieldstr,'store');
		if (C('gold_isuse')==1 && C('ztc_isuse') == 1){
			$fieldstr = " goods.goods_id,goods.goods_name,goods.gc_id,goods.gc_name,goods.store_id,goods.goods_image,goods.goods_store_price,
			goods.goods_click,goods.goods_state,goods.goods_commend,goods.commentnum,goods.salenum,goods.goods_goldnum,goods.goods_isztc,
			goods.goods_ztcstartdate,goods.goods_ztclastdate";
			$ztc_list = $model_goods->getGoods(array(
					'brand_id'=>$brand_id,
					'goods_show'=>'1',
					'goods_isztc'=>'1',
					'goods_ztcopen'=>'1',
					'limit'=>'8',
					'order'=>rand(1,5),
			),'',$fieldstr,'goods');
			Tpl::output('ztc_list',$ztc_list);
		}
			
		
		Tpl::output('show_page',$page->show());

		
       $display_mode = $_COOKIE['goodsDisplayMode'] ? $_COOKIE['goodsDisplayMode'] : 'squares'; 
		if (!empty($goods_list) && is_array($goods_list)){
			
			
			$goods_count = $page->getTotalNum();
			Tpl::output('goods_count',$goods_count);
		}
		Tpl::output('goods_list',$goods_list);

       
		$viewed_goods = array();
		$cookie_i = 0;
		if(cookie('viewed_goods')){
			$string_viewed_goods = cookie('viewed_goods');
			if (get_magic_quotes_gpc()) $string_viewed_goods = stripslashes($string_viewed_goods);
			$cookie_array = array_reverse(unserialize($string_viewed_goods));
			$goods_id_in	= '';
			foreach ($cookie_array as $k=>$v){
				$info = explode("-", $v);
				$goods_id_in .= $info[0].',';
				                	
			}
			$goods_id_in	= rtrim($goods_id_in,',');
			$viewed_list		= $model_goods->getGoods(array('goods_id_in'=>$goods_id_in),'','goods_id, goods_name, goods_store_price, goods_image, store_id','goods');
			if(!empty($viewed_list) && is_array($viewed_list)){
				foreach ($viewed_list as $val){
					$viewed_goods[] = array(
					 "goods_id"          => $val['goods_id'],
					 "goods_name"        => $val['goods_name'],
					 "goods_image"       => $val['goods_image'],
					 "goods_store_price" => $val['goods_store_price'],
				 	 "store_id"        => $val['store_id']
					);
				}
			}
		}
		
	
		$area_url = BasePath.DS.'data'.DS.'area'.DS.'area.php';
		if (file_exists($area_url)){
			include_once($area_url);
		}
		if (strtoupper(CHARSET) == 'GBK'){
			$area_array = Language::getGBK($area_array);
		}
		Tpl::output('area_array',$area_array);
		
		
		$nav_link = array(
			0=>array(
				'title'=>$lang['homepage'],
				'link'=>'index.php'
			),
			1=>array(
				'title'=>$lang['brand_index_all_brand'],
				'link'=>'index.php?act=brand'
			),
			2=>array(
				'title'=>$brand_lise['brand_name']
			)
		);
		Tpl::output('nav_link_list',$nav_link);
		
		Tpl::output('display_mode',$display_mode);
		Tpl::output('viewed_goods',$viewed_goods);
		Tpl::output('index_sign','brand');

		Model('seo')->type('brand_list')->param(array('name'=>$brand_lise['brand_name']))->show();
		Tpl::showpage('brand_goods');
	}

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
