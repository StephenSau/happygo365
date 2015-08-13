<?php

defined('haipinlegou') or exit('Access Invalid!');

class show_storeControl extends BaseStoreControl {
	public function __construct(){
		parent::__construct();
	}
	public function indexOp(){
           
	
		Language::read('store_show_store_index');
		
		$store_navigation_partner_class = Model('store_navigation_partner');
		$goods_class = Model('goods');
		 
		$store_id = intval($_GET['id']);
		if ($store_id <= 0){
			showMessage(Language::get('show_store_index_store_not_exists'),'','html','error');
		}

		
		$store_info = $this->getStoreInfo($store_id);

		$store_partner_list = $store_navigation_partner_class->getPartnerList(array('sp_store_id'=>$store_info['store_id']));
		Tpl::output('store_partner_list',$store_partner_list);
	
		if (!empty($_GET['article']) && intval(trim($_GET['article']))>0){
			$store_navigation_info = $store_navigation_partner_class->getOneNavigation(intval($_GET['article']));
			if (!empty($store_navigation_info) && is_array($store_navigation_info)){
				Tpl::output('store_navigation_info',$store_navigation_info);
				Tpl::output('page',$store_navigation_info['sn_id']);
				Tpl::showpage('article');
			}
		}
		
		$recommended_goods_list = $goods_class->getGoods(array(
			'store_id'=>$store_info['store_id'],
			'goods_show'=>1,
			'goods_commend'=>1,
			'order'=>'`goods`.goods_starttime desc',
			'limit'=>'0,12'
		),'','goods.*','goods',array('index'=>'store_id'));
		if (!empty($recommended_goods_list) && is_array($recommended_goods_list)){	
			foreach ($recommended_goods_list as $key=>$value){
				$recommended_goods_list[$key]['goods_price'] = ncPriceFormat($value['goods_price']);
			}
		}
	
		$new_goods_list = $goods_class->getGoods(array(
			'store_id'=>$store_info['store_id'],
			'goods_show'=>1,
			'order'=>'`goods`.goods_starttime desc',
			'limit'=>'0,12'
		),'','goods.*','goods',array('index'=>'store_id'));
		if (!empty($new_goods_list) && is_array($new_goods_list)){
			foreach ($new_goods_list as $key=>$value){
				$new_goods_list[$key]['goods_price'] = ncPriceFormat($value['goods_price']);
			}
		}

		Tpl::output('new_goods_list',$new_goods_list);

		$theme_model = Model('store_theme');
		$state = $theme_model->getShowStyle($store_info['store_theme']);
		if ($state == 1){
			$condition = array();
			$condition['style_id'] = $store_info['store_theme'];
			$condition['store_id'] = $store_id;
			$theme_list = $theme_model->getList($condition);
			Tpl::output('theme',$theme_list[0]);
			$style_js = '';
			$style_configurl = BASE_TPL_PATH.DS.'store'.DS.'style'.DS.$store_info['store_theme'].DS."style_config.php";
			if (file_exists($style_configurl) && !empty($theme_list[0]["theme_info"])){
				include_once($style_configurl);
				Tpl::output('style_js',$style_js);
			}
		}
        
        /*S把商店的产品全部遍历出来*/
        $page	= new Page();
		$page->setEachNum(12);
		$page->setStyle('admin');

		$conditionArr['store_id'] = $store_id;
               $conditionArr['goods_show'] = 1;
		$conditionArr['order'] = 'goods_sorting asc';
              
	    if ($_GET['stc_id']){
			$model_store_class = Model('my_goods_class');
			$stc_id_arr = $model_store_class->getChildAndSelfClass(intval($_GET['stc_id']),'1');
			if (is_array($stc_id_arr) && count($stc_id_arr)>0){
				$conditionArr['stc_id_in'] = implode(',',$stc_id_arr);
			}else{
				$conditionArr['stc_id'] = $stc_id_arr;
			}
			$goods_list = $goods_class->getGoods($conditionArr,$page,'goods.*','stc');
		}else {
			$goods_list = $goods_class->getGoods($conditionArr,$page,'goods.*','goods');
		}
		if (!empty($goods_list) && is_array($goods_list)){
			foreach ($goods_list as $key=>$value){
			
				$goods_list[$key]['goods_price'] = ncPriceFormat($value['goods_price']);
			
			}
		}
        /*E把商店的产品全部遍历出来*/

		if($store_info['store_slide'] != '' && $store_info['store_slide'] != ',,,,'){
			Tpl::output('store_slide', explode(',', $store_info['store_slide']));
			Tpl::output('store_slide_url', explode(',', $store_info['store_slide_url']));
		}
		
		//商店头部判断是否出现卖家中心的连接
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}	
		//判断进店逛逛所选择的act
		if(!empty($store_info['store_id'])){
		$aaa='';
		if($store_info['store_id'] == 19)
		{
		   $aaa=national;
		}
		else if($store_info['store_id'] == 17)
		{
		   $aaa=japan;
		}
		else
		{
		   $aaa=dubai;
		}
		}
		
		Tpl::output('aaa',$aaa);
		Tpl::output('page','index');
		Tpl::output('recommended_goods_list',$recommended_goods_list);
		Tpl::output('goods_list',$goods_list);
         
		Tpl::output('show_page',$page->show());
		
        //S脚部内容输出
          $list = $this->_article();
        //E脚部内容输出

		$seo_param = array();
		$seo_param['shopname'] = $store_info['store_name'];
		$seo_param['key']  = $store_info['store_keywords'];
		$seo_param['description'] = $store_info['store_description'];
		Model('seo')->type('shop')->param($seo_param)->show();
		Tpl::showpage('index');
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

	public function creditOp(){
	
		Language::read('store_show_store_index');
		$id = intval($_GET['id']);
		if ($id <= 0){
			showMessage(Language::get('show_store_index_id_null'),'','html','error');
		}			
	
		$store_info = $this->getStoreInfo($id);
        
        //文章输出
		$list = $this->_article();
		
		$this->show_storeeval($id);
	
		$evaluate_model = Model("evaluate");
		$goodsstat_list = $evaluate_model->goodsEvalStatList(array('statstoreid'=>"{$id}"));
		Tpl::output('goodsstat_list',$goodsstat_list);
		Tpl::output('page','credit');
		$seo_param = array();
		$seo_param['shopname'] = $store_info['store_name'];
		$seo_param['key']  = $store_info['store_keywords'];
		$seo_param['description'] = $store_info['store_description'];		
		Model('seo')->type('shop')->param($seo_param)->show();
		Tpl::output('html_title',$store_info['store_name'].' - '.Language::get('nc_credit'));
		Tpl::showpage('credit');

	}

	

	public function commentsOp() {
	
		Language::read('store_show_store_index');
		$store_id = intval($_GET['id']);
		$result = true;
		if ($store_id <= 0){
			$result = false;
		}
		if ($result){
		
			$evaluate_defaulttext = array('1'=>Language::get('nc_credit_defaultcontent_good'),'0'=>Language::get('nc_credit_defaultcontent_normal'),'-1'=>Language::get('nc_credit_defaultcontent_bad'));			
			$condition = array();
		
			if($_GET['evalscore']){
				switch($_GET['evalscore']){
					case 1:
						$condition['geval_scores'] = '1';
						break;
					case 2:
						$condition['geval_scores'] = '0';
						break;
					case 3:
						$condition['geval_scores'] = '-1';
						break;
				}
			}
		
			$condition['geval_storeid'] = "{$store_id}";
			$condition['geval_type'] = "1";
			$condition['geval_showtime_lt'] = time();
			$page	= new Page();
			$page->setEachNum(10);
			$page->setStyle('admin');
			$evaluate_model = Model("evaluate");
			$goodsevallist = $evaluate_model->getGoodsEvalList($condition,$page,'*','member');
			if (!empty($goodsevallist)){
				foreach ($goodsevallist as $k=>$v){
					
					if ($v['geval_content'] == '' || $v['geval_state'] == '1'){
						$v['geval_content'] = $evaluate_defaulttext[$v['geval_scores']];
					}
					switch ($v['geval_scores']){
						case 1:
							$v['geval_scoressign'] = 'good';
							break;
						case 0:
							$v['geval_scoressign'] = 'normal';
							break;
						case -1:
							$v['geval_scoressign'] = 'bad';
							break;
					}
				
					$v['geval_frommembername'] = $v['geval_isanonymous'] == 1?Language::get('show_store_credit_anonymousbuyer_title').str_cut($v['geval_frommembername'],2).'***':Language::get('show_store_credit_buyer').Language::get('nc_colon').$v['geval_frommembername'];
				
					$v['credit_arr'] = getCreditArr(intval($v['member_credit']));
					$goodsevallist[$k] = $v;
				}
			}
			Tpl::output('goodsevallist',$goodsevallist);
			Tpl::output('show_page',$page->show());
		}
		$id = intval($_GET['id']);

		$goodsstat_list = $evaluate_model->goodsEvalStatList(array('statstoreid'=>"{$id}"));
		Tpl::output('goodsstat_list',$goodsstat_list);
		Tpl::showpage('credit_comments','null_layout');
	}
	public function store_infoOp(){
	
		Language::read('store_show_store_index');
	
		$id = empty($_GET['id']) ? 0 : intval($_GET['id']);

		//文章输出
		$list = $this->_article();
		$store_info = $this->getStoreInfo($id);

		
		$member_model	= Model('member');
		$member_info	= $member_model->infoMember(array('member_id'=>$store_info['member_id']),'member_time,member_old_login_time,member_email,member_credit');
		$member_info['credit_arr'] = getCreditArr($member_info['member_credit']);
		Tpl::output('member_info', $member_info);
		Tpl::output('page','map');
		Tpl::output('html_title',$store_info['store_name'].' - '.Language::get('nc_store_info'));
		Tpl::showpage('store_info');
	}
	
	
	public function goods_allOp(){
		
		Language::read('store_show_store_index');
		$lang	= Language::getLangContent();
	
		$store_navigation_partner_class = Model('store_navigation_partner');
		$goods_class = Model('goods');
		
		$store_id = intval($_GET['id']);
		if ($store_id == 0){
			showMessage($lang['show_store_index_store_not_exists'],'','html','error');
		}

       
		$store_info = $this->getStoreInfo($store_id);

	
		$seo_keywords    = $store_info['store_keywords'];
		$seo_description = $store_info['store_description'];
		Tpl::output('seo_keywords',$seo_keywords);
		Tpl::output('seo_description',$seo_description);
		
		$store_partner_list = $store_navigation_partner_class->getPartnerList(array('sp_store_id'=>$store_info['store_id']));
		Tpl::output('store_partner_list',$store_partner_list);
		
		$page	= new Page();
		$page->setEachNum(24);
		$page->setStyle('admin');
		$conditionArr = array();
		$conditionArr['store_id']		= $store_info['store_id'];
		$conditionArr['goods_state']	= 0;
		$conditionArr['goods_show']		= 1;
		$conditionArr['keyword']		= trim($_GET['keyword']);
		$conditionArr['start_price']	= floatval($_GET['start_price']);
		$conditionArr['end_price']		= floatval($_GET['end_price']);
		if(trim($_GET['order'] != 'asc'))	$_GET['order']	= 'desc';
	
		switch (trim($_GET['key'])){
			case 'price':
				$conditionArr['order'] = 'goods.goods_store_price '.$_GET['order'];
				break;
			case 'sale':
				$conditionArr['order'] = 'goods.salenum '.$_GET['order'];
				break;
			case 'click':
				$conditionArr['order'] = 'goods.goods_click '.$_GET['order'];
				break;
			case 'collect':
				$conditionArr['order'] = 'goods.goods_collect '.$_GET['order'];
				break;
			case 'new':
				$conditionArr['order'] = 'goods.goods_starttime '.$_GET['order'];
				break;
			default:
				$conditionArr['order'] = 'goods.goods_starttime desc';
				break;
		}
		
		if ($_GET['stc_id']){
			$model_store_class = Model('my_goods_class');
			$stc_id_arr = $model_store_class->getChildAndSelfClass(intval($_GET['stc_id']),'1');
			if (is_array($stc_id_arr) && count($stc_id_arr)>0){
				$conditionArr['stc_id_in'] = implode(',',$stc_id_arr);
			}else{
				$conditionArr['stc_id'] = $stc_id_arr;
			}
			$recommended_goods_list = $goods_class->getGoods($conditionArr,$page,'goods.*','stc');
		}else {
			$recommended_goods_list = $goods_class->getGoods($conditionArr,$page,'goods.*','goods');
		}
		if (!empty($recommended_goods_list) && is_array($recommended_goods_list)){
			foreach ($recommended_goods_list as $key=>$value){
			
				$recommended_goods_list[$key]['goods_price'] = ncPriceFormat($value['goods_price']);
			
			}
		}
	
		$this->show_storeeval($store_id);

		//S脚部内容输出
        $list = $this->_article();
        //E脚部内容输出
	
		Tpl::output('show_page',$page->show());
		$stc_class = Model('store_goods_class');
		$stc_info = $stc_class->getOneById(intval($_GET['stc_id']));
		Tpl::output('stc_name',$stc_info['stc_name']);
		Tpl::output('page','index');
		Tpl::output('recommended_goods_list',$recommended_goods_list);
		Tpl::showpage('goods_list');
	}

	public function ajax_flowstat_recordOp(){
		if($_GET['id'] != '' && $_SESSION['store_id'] != $_GET['id']){

			$flow_tableid = 0;
			$len = strlen(strval(intval($_GET['id'])));
			$last_num = substr(strval(intval($_GET['id'])), $len-1,1);
			switch ($last_num){
				case 1:
					$flow_tableid = 1;
					break;
				case 2:
					$flow_tableid = 1;
					break;
				case 3:
					$flow_tableid = 2;
					break;
				case 4:
					$flow_tableid = 2;
					break;
				case 5:
					$flow_tableid = 3;
					break;
				case 6:
					$flow_tableid = 3;
					break;
				case 7:
					$flow_tableid = 4;
					break;
				case 8:
					$flow_tableid = 4;
					break;
				case 9:
					$flow_tableid = 5;
					break;
				case 0:
					$flow_tableid = 5;
					break;
			}
			$flow_tablename = 'flowstat_'.$flow_tableid; 
			
			$date = date('Ymd',time());
			$model = Model();
			$stat_model = Model('statistics');
			if($_GET['act_param'] == 'show_store' && ($_GET['op_param'] == 'index' || $_GET['op_param'] == 'credit' || $_GET['op_param'] == 'store_info')){
				$flow_date_array = $model->table($flow_tablename)->where(array('date'=>$date,'store_id'=>intval($_GET['id'])))->find();
			}else if($_GET['act_param'] == 'goods' && $_GET['op_param'] == 'index'){
				$flow_date_array = $model->table($flow_tablename)->where(array('date'=>$date,'goods_id'=>intval($_GET['goods_id'])))->find();
				$flow_date_array_sub = $model->table($flow_tablename)->where(array('date'=>$date,'store_id'=>intval($_GET['id'])))->find();
			}
		
			$update_param = array();
			$update_param['table'] = $flow_tablename;
			$update_param['field'] = 'clicknum';
			$update_param['value'] = 1;
			if(is_array($flow_date_array) && !empty($flow_date_array)){
				if($_GET['act_param'] == 'show_store' && ($_GET['op_param'] == 'index' || $_GET['op_param'] == 'credit' || $_GET['op_param'] == 'store_info')){
					$update_param['where'] = "WHERE date = '".$date."' AND store_id = '".intval($_GET['id'])."' AND goods_id = '0'";
					$stat_model->updatestat($update_param);
				}else if($_GET['act_param'] == 'goods' && $_GET['op_param'] == 'index'){
					$update_param['where'] = "WHERE date = '".$date."' AND goods_id = '".intval($_GET['goods_id'])."'";
					$stat_model->updatestat($update_param);
					$update_param['where'] = "WHERE date = '".$date."' AND store_id = '".intval($_GET['id'])."' AND goods_id = '0'";
					$stat_model->updatestat($update_param);
				}
			}else{
				if($_GET['act_param'] == 'show_store' && ($_GET['op_param'] == 'index' || $_GET['op_param'] == 'credit' || $_GET['op_param'] == 'store_info')){
					$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'store_id'=>intval($_GET['id']),'type'=>'sum','goods_id'=>0));
				}else if($_GET['act_param'] == 'goods' && $_GET['op_param'] == 'index'){
					if(is_array($flow_date_array_sub) && !empty($flow_date_array_sub)){
						$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'store_id'=>intval($_GET['id']),'type'=>'goods','goods_id'=>intval($_GET['goods_id'])));
						$update_param['where'] = "WHERE date = '".$date."' AND store_id = '".intval($_GET['id'])."' AND goods_id = '0'";
						$stat_model->updatestat($update_param);
					}else{
						$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'store_id'=>intval($_GET['id']),'type'=>'sum','goods_id'=>0));
						$model->table($flow_tablename)->insert(array('date'=>$date,'clicknum'=>1,'store_id'=>intval($_GET['id']),'type'=>'goods','goods_id'=>intval($_GET['goods_id'])));
					}
				}
			}
		}
		echo json_encode(array('done'=>true,'msg'=>'done'));
	}
}
?>
