<?php



defined('haipinlegou') or exit('Access Invalid!');

define('MYSQL_RESULT_TYPE',1);

class carControl extends BaseHomeControl{

	

	//对应国家馆

	public function indexOp(){

		

	

		Language::read('home_index_index');

		Tpl::output('index_sign','japan');

		

		/*

		$store_id = intval($_GET['id']);

		if ($store_id <= 0){

			showMessage(Language::get('show_store_index_store_not_exists'),'','html','error');

		}

		

		

		//获取店铺信息

		$store_info = $this->getStoreInfo($store_id);

		

		//获取父级分类

		$goods_class = Model('goods_class');

		$goods = Model('goods');

		$param['gc_parent_id'] = 0;

		$father_class = $goods_class->getClassList($param ,$field='*');		

		Tpl::output('father_class',$father_class);



		//获取对应顶级分类的所有子分类id

		foreach($father_class as $key=>$value)

		{

			$father_class[$key]['child'] = $goods_class->getChildClass($value['gc_id']);

			foreach($father_class[$key]['child'] as $k=>$v)

			{

				$arr[$value['gc_id']][] = $v['gc_id'];

			}

		}

		

		//获取对应分类的产品

		foreach($arr as $k=>$v)

		{

			$param['gc_id'] = join(",",$v);

			$param['order'] = 'salenum';

			$param['country'] = '日本';

			$goods_list[$k] = $goods->getGoods($param);

		}

		Tpl::output('goods_list',$goods_list);

		

		//获取对应分类的产品（点击率）

		foreach($arr as $k=>$v)

		{

			$param['gc_id'] = join(",",$v);

			$param['order'] = 'goods.goods_click desc';

			$param['limit'] = '0,2';

			$param['country'] = '日本';

			$goods_rank[$k] = $goods->getGoods($param);

		}

		Tpl::output('goods_rank',$goods_rank);

		

		//获取对应分类的产品（最新产品）

		foreach($arr as $k=>$v)

		{

			$param['gc_id'] = join(",",$v);

			$param['order'] =  'goods.goods_id';

			$param['limit'] = '1';

			$param['country'] = '日本';

			$goods_newest[$k] = $goods->getGoods($param);

		}

		Tpl::output('goods_newest',$goods_newest);

		*/

		

		//获取对应国家馆的产品

		$goods = Model('goods');

		

		//分页

		$page	= new Page();

		$page->setEachNum(24);

		$page->setStyle('admin');

		

		$param['gc_id'] = @join(",",$v);
               
		$param['order'] = 'goods_sorting asc,goods_serial asc';

		$param['store_id'] = 20;

		$goods_list = $goods->getGoods($param,$page);

		Tpl::output('page',$page->show());

		Tpl::output('goods_list',$goods_list);	

		

		//商店头部判断是否出现卖家中心的连接

		if($_SESSION['is_login'] == '1'){

			$member_model	= Model('member');

			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');

			Tpl::output('member_info',$member_info);

		}





		//S脚部内容输出

		$list = $this->_article();

		//E脚部内容输出



		

		Tpl::output('show_flink',($link = F('link')) ? $link : H('link',true,'file'));

		

		Tpl::showpage('car');

		

	}

    /* 轮播首页*/
	public function sindexOp(){
	   //	$model = Model();
      // $brandlist=$model->table('brand')->where("brand_class='汽车用品'")->select();
      // Tpl::output('brandlist',$brandlist);
      $lang	= Language::getLangContent();
	 // Tpl::setLayout('member_goods_marketing_layout');
      
      $model_class	= Model('goods_class');
			$param_array	= array();
			$param_array['gc_parent_id']	= '388';
            //$param_array['in_gc_id']	= '388';
			$param_array['gc_show']	= '1';
		
			$goods_class	= $model_class->getClassList($param_array);
	
			//S脚部文章输出
			$list=$this->_article();
			//E脚部文章输出
		
			Tpl::output('goods_class', $goods_class);
			Tpl::output('menu_sign', 'add_goods_step1');
      
      
	   Tpl::showpage('scarindexs');
    }
    
    public function ajax_goods_classOp(){
		if(empty($_GET['gc_id']) or $_GET['gc_id'] == '0')exit;
		$gc	= Model('goods_class');
		$list = $gc->getClassList(array('gc_parent_id'=>intval($_GET['gc_id']),'gc_show'=>1));
		
		if (strtoupper(CHARSET) == 'GBK'){
			$list = Language::getUTF8($list);
		}
		echo json_encode($list);
	}

	/*

	//获取对应店铺信息

	public function getStoreInfo($store_id){

		$lang	= Language::getLangContent();

		$model_store	= Model('store');

        $store_info = array();

        if(intval($store_id) > 0) {

            $store_info	= $model_store->shopStore(array('store_id'=>$store_id));

        } else {

            showMessage($lang['nc_store_close'], '', '', 'error');

        }

		

		if($store_info['store_state'] == '0' || (intval($store_info['store_end_time']) != 0 && $store_info['store_end_time'] <= time())){

            showMessage($lang['nc_store_close'], '', '', 'error');

		}

        $store_info = $model_store->getStoreInfoDetail($store_info);



        Tpl::output('store_info',$store_info);

		Tpl::output('hot_sales',$store_info['hot_sales']);

		Tpl::output('hot_collect',$store_info['hot_collect']);

		Tpl::output('goods_class_list',$store_info['goods_class_list']);

		Tpl::output('page_title',$store_info['store_name']);

		if($store_info['store_theme'] == 'style8'){

			$theme_model = Model('store_theme');

			$condition = array();

			$condition['style_id'] = $store_info['store_theme'];

			$condition['store_id'] = $id;

			$theme_list = $theme_model->getList($condition);

			Tpl::output('theme',$theme_list[0]);

		}

		return $store_info;

	}

	*/

	//文章输出

	private function _article() {



		if (file_exists(BasePath.'/cache/index/article.php')){

			include(BasePath.'/cache/index/article.php');

			Tpl::output('show_article',$show_article);

			Tpl::output('article_list',$article_list);

			return ;		

		}

		$model_article_class	= Model('article_class');

		$model_article	= Model('article');

		$show_article = array();//商城公告

		$article_list	= array();//下方文章

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

				$val['list']	= array();//文章

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

				if($ac_parent_id == 0) {//顶级分类

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

