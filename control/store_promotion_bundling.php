<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_promotion_bundlingControl extends BaseMemberStoreControl {

    public function __construct() {

        parent::__construct() ;

       
        Language::read('member_layout,member_store_promotion_bundling');
        if (intval(C('gold_isuse')) !== 1 || intval(C('promotion_allow')) !== 1){
            showMessage(Language::get('promotion_unavailable'),'index.php?act=store','','error');
        }

    }

    public function indexOp() {
        $this->bundling_listOp();
    }

    
    public function bundling_listOp() {
        $model = Model();
		
        $bundling_quota	= $this->bundlingQuotaCheck();
		Tpl::output('bundling_quota_flag', $bundling_quota);
		
		if($bundling_quota){
			$count = $model->table('p_bundling')->where('store_id='.$_SESSION['store_id'])->count();
			$bundling_published		= intval($count);
			$bundling_surplus	= intval(C('promotion_bundling_sum')) - $bundling_published;
			
			
			$where = 'store_id='.$_SESSION['store_id'];	
			if($_GET['bundling_name'] != ''){
				$where .= ' and bl_name like "%'.trim($_GET['bundling_name']).'%"';
				Tpl::output('bundling_name', trim($_GET['bundling_name']));
			}
			if(is_numeric($_GET['state'])){
				$where .= ' and bl_state ='.$_GET['state'];
				Tpl::output('state', $_GET['state']);
			}
			$bundling_count = $model->table('p_bundling')->where($where)->count();
			$bundling_list = $model->table('p_bundling,p_bundling_goods')->field('count(p_bundling_goods.bl_id) as count,p_bundling.*')
									->join('left')->on('p_bundling.bl_id=p_bundling_goods.bl_id')->group('bl_id')
									->where($where)->order('p_bundling.bl_id desc')->page(10, $bundling_count)->select();
			if(is_array($bundling_list)){
				foreach ($bundling_list as $key=>$val){
					$a = explode(',', $val['bl_img_more']);
					$bundling_list[$key]['img'] = cthumb($a[0],'tiny',$_SESSION['store_id']);
				}
			}
			$page = $model->showpage(2);
			Tpl::output('show_page',$page);
			Tpl::output('list', $bundling_list);
			
			
			Tpl::output('bundling_quota', $bundling_quota);
			Tpl::output('bundling_published', $bundling_published);
			Tpl::output('bundling_surplus', $bundling_surplus);
			$this->bundlingState();
		}
		$this->profile_menu('bundling_list', 'bundling_list');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_bundling.list');

    }

   
	public function bundling_quota_addOp() {
		if(chksubmit()){
			$quantity = intval($_POST['bundling_quota_quantity']);				
			$glod_quantity	= $quantity*intval(C('promotion_bundling_price'));	
			if($quantity <= 0 || $quantity > 12){
				showDialog(Language::get('bundling_quota_price_fail'),'index.php?act=store_promotion_bundling&op=bundling_quota_add','','error');
			}
			$model	= Model();
			
			$member_info = $model->table('member')->field('member_goldnum')->find($_SESSION['member_id']);
			if(intval($member_info['member_goldnum']) < $glod_quantity ){
				showDialog(Language::get('bundling_gold_not_enough'));
			}
			
			$data = array();
			$data['store_id']			= $_SESSION['store_id'];
			$data['store_name']			= $_SESSION['store_name'];
			$data['member_id']			= $_SESSION['member_id'];
			$data['member_name']		= $_SESSION['member_name'];
			$data['bl_quota_month']		= $quantity;
			$data['bl_quota_starttime']	= time();
			$data['bl_quota_endtime']	= time()+60*60*24*30*$quantity;
			$data['bl_quota_state']		= 1;
			$data['bl_pay_gold']		= $quantity*intval(C('promotion_bundling_price'));
			
			$return = $model->table('p_bundling_quota')->insert($data);
			if($return){
				$update_array = array();
				$update_array['member_id']				= $_SESSION['member_id'];
				$update_array['member_goldnum']			= array('exp','member_goldnum-'.$glod_quantity);
				$update_array['member_goldnumminus']	= array('exp','member_goldnumminus+'.$glod_quantity);
				$result = $model->table('member')->update($update_array);
				
				$param = array();
				$param['glog_memberid']		= $_SESSION['member_id'];
				$param['glog_membername']	= $_SESSION['member_name'];
				$param['glog_storeid']		= $_SESSION['store_id'];
				$param['glog_storename']	= $_SESSION['store_name'];
				$param['glog_adminid']		= '0';
				$param['glog_adminname']	= '';
				$param['glog_goldnum']		= $glod_quantity;
				$param['glog_method']		= 2;
				$param['glog_addtime']		= time();
				$param['glog_desc']			= sprintf(Language::get('bundling_quota_success_glog_desc'),$quantity,intval(C('promotion_bundling_price')),$glod_quantity);
				$param['glog_stage']		= 'bundling';
				$model->table('gold_log')->insert($param);
				
				$param = array();
				$param['buy_month']			= $quantity;
				$param['bundling_price']	= C('promotion_bundling_price');
				$param['pay_gold']			= $quantity*intval(C('promotion_bundling_price'));
				self::send_notice($_SESSION['member_id'], 'msg_toseller_bundling_gold_consume_notify', $param);
				
				showDialog(Language::get('bundling_quota_price_succ'), 'index.php?act=store_promotion_bundling&op=bundling_list', 'succ');
			}else{
				showDialog(Language::get('bundling_quota_price_fail'), 'index.php?act=store_promotion_bundling&op=bundling_quota_add');
			}
		}
        self::profile_menu('bundling_quota_add', 'bundling_quota_add');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_bundling_quota.add');
    }
    
   
    public function bundling_addOp(){
    	
		$model	= Model();
		
		if(!$this->bundlingQuotaCheck()){
			showMessage(Language::get('bundling_quota_current_error'),'','','error');
		}
		
		if(intval(C('promotion_bundling_sum')) != 0 && !isset($_REQUEST['bundling_id'])){
			$count = $model->table('p_bundling')->where('store_id='.$_SESSION['store_id'])->count();
			if(intval(C('promotion_bundling_sum')) <= intval($count)) showMessage(Language::get('bundling_add_fail_quantity_beyond'), '', '', 'error');
		}
		
    	if(chksubmit()){
    		
    		$data	= array();
    		if(isset($_POST['bundling_id'])) $data['bl_id'] = intval($_POST['bundling_id']);
    		$data['bl_name']			= $_POST['bundling_name'];
    		$data['store_id']			= $_SESSION['store_id'];
    		$data['store_name']			= $_SESSION['store_name'];
    		$data['bl_img_more']		= empty($_POST['image_path'])?'':implode(',', array_filter($_POST['image_path']));
    		$data['bl_discount_price']	= $_POST['discount_price'];
    		$data['bl_freight_choose']	= $_POST['bundling_freight_choose'];
    		$data['bl_freight']			= $_POST['bundling_freight'];
    		$data['bl_desc']			= $_POST['bundling_desc'];
    		$data['bl_state']			= intval($_POST['state']);
    		$return	= $model->table('p_bundling')->insert($data, true);
    		if(!$return) showDialog(Language::get('nc_common_op_fail'), '', '', 'error');
    		if(!isset($_POST['bundling_id'])){
    			
    			$data_array = array();
    			$data_array['bl_id']				= $return;
    			$data_array['bl_name']				= $data['bl_name'];
    			$data_array['bl_img']				= empty($_POST['image_path'])?'':$_POST['image_path'][0];
    			$data_array['bl_discount_price']	= $data['bl_discount_price'];
    			$data_array['bl_freight_choose']	= $data['bl_freight_choose'];
    			$data_array['bl_freight']			= $data['bl_freight'];
    			$data_array['store_id']				= $_SESSION['store_id'];
    			$this->storeAutoShare($data_array, 'bundling');
    		}
			
    		
    		$data_goods	= array();
    		
    		$model->table('p_bundling_goods')->where('bl_id='.intval($_POST['bundling_id']))->delete();
    		if(!empty($_POST['goods']) && is_array($_POST['goods'])){
    			foreach($_POST['goods'] as $key=>$val){
    				if(isset($val['bundling_goods_id'])) {
    					$data_goods[$key]['bl_goods_id'] = intval($val['bundling_goods_id']);
    				}else{
    					$data_goods[$key]['bl_goods_id'] = null;
    				}
    				$data_goods[$key]['bl_id']		= isset($_POST['bundling_id'])?intval($_POST['bundling_id']):$return;
    				$data_goods[$key]['goods_id']	= intval($val['goods_id']);
    				$data_goods[$key]['goods_name']	= trim($val['goods_name']);
    			}
    		}
    		$data_goods = array_values($data_goods);
    		$return = $model->table('p_bundling_goods')->insertAll($data_goods);

    		showDialog(Language::get('nc_common_op_succ'), 'index.php?act=store_promotion_bundling&op=bundling_list', 'succ');
    	}
		$store_info		= $model->table('store')->find($_SESSION['store_id']);
    	$store_grade	= $model->table('store_grade')->find($store_info['grade_id']);
    	$editor_multimedia = false;
    	$sg_fun = @explode('|',$store_grade['sg_function']);
    	if(!empty($sg_fun) && is_array($sg_fun)){
    		foreach($sg_fun as $fun){
    			if ($fun == 'editor_multimedia'){
    				$editor_multimedia = true;
    			}
    		}
    	}
		Tpl::output('editor_multimedia',$editor_multimedia);
		
		if (intval($_GET['bundling_id']) > 0){
			$bundling_info	= $model->table('p_bundling')->find(intval($_GET['bundling_id']));
			
			if($bundling_info['store_id'] != $_SESSION['store_id']){
				showMessage(Language::get('wrong_argument'), 'index.php?act=store_promotion_bundling&op=bundling_list', '', 'error');
			}
			
			
			$b_goods_list	= $model->table('p_bundling_goods,goods')
									->field('p_bundling_goods.bl_goods_id, p_bundling_goods.goods_id, p_bundling_goods.goods_name, goods.goods_image, goods.goods_store_price, goods.goods_show')
									->join('inner')->on('p_bundling_goods.goods_id=goods.goods_id')
									->where('bl_id='.intval($_GET['bundling_id']))->select();
			$bundling_info['bl_img_more']	= empty($bundling_info['bl_img_more'])?'':explode(',', $bundling_info['bl_img_more']);
			Tpl::output('bundling_info', $bundling_info);
			Tpl::output('b_goods_list', $b_goods_list);
			self::profile_menu('bundling_edit', 'bundling_edit');
		}else{
	    	self::profile_menu('bundling_add', 'bundling_add');
		}				//脚部文章输出		$article = $this->_article();
    	Tpl::showpage('store_promotion_bundling.add');
    }
    
  
    public function bundling_add_goodsOp(){
    	
    	$model = Model('goods');
    	$model_store_class = Model('my_goods_class');
    	
    	$where = 'store_id='.$_SESSION['store_id'].' and goods_show=1';
    	if(intval($_GET['stc_id']) > 0){
    		$stc_id = intval($_GET['stc_id']);
    		if ($stc_id){
    			$stc_id_arr = $model_store_class->getChildAndSelfClass($stc_id,'1');
    			if (is_array($stc_id_arr) && count($stc_id_arr)>0){
    				$where .= ' and store_class_goods.stc_id in ('.implode(',',$stc_id_arr).')';
    			}else{
    				$where .= ' and store_class_goods.stc_id = '.$stc_id_arr;
    			}
    		}
    		Tpl::output('stc_id', $stc_id);
    	}
    	if($_GET['keyword'] != ''){
    		$where .= ' and goods_name like \'%'.trim($_GET['keyword']).'%\'';
    		Tpl::output('b_search_keyword', trim($_GET['keyword']));
    	}
    	
    	if(intval($_GET['stc_id']) > 0){
    		$goods_list = $model->table('goods,store_class_goods')->field('DISTINCT goods.goods_id, goods.goods_name, goods.goods_image, goods.goods_store_price, goods.goods_serial')->join('left')->on('goods.goods_id=store_class_goods.goods_id')->where($where)->order('goods.goods_id desc')->page(5)->select();
    	}else{
    		$goods_list = $model->table('goods')->field('goods_id, goods_name, goods_image, goods_store_price, goods_serial')->where($where)->order('goods_id desc')->page(5)->select();
       	}
       	
    	if(!empty($goods_list) && is_array($goods_list)){
    		$goods_id_array	= array();
    		foreach($goods_list as $val){
    			$goods_id_array[]	= $val['goods_id'];
    		}
    		$goods_storage = $model->table('goods_spec')->field('sum(spec_goods_storage) as sum, goods_id')->where('goods_id in ('.implode(',', $goods_id_array).')')->group('goods_id')->select();
    		$storage_array = array();
    		foreach ($goods_storage as $val){
    			$storage_array[$val['goods_id']] = $val['sum'];
    		}
    		Tpl::output('storage_array', $storage_array);
    	}
    	$page = $model->showpage(2);
    	Tpl::output('show_page',$page);
    	Tpl::output('goods_list', $goods_list);
    	
    	
    	$store_goods_class	= $model_store_class->getClassTree(array('store_id'=>$_SESSION['store_id'],'stc_state'=>'1'));
    	Tpl::output('store_goods_class',$store_goods_class);
    	//脚部文章输出		$article = $this->_article();
    	Tpl::showpage('store_promotion_bundling.add_goods', 'null_layout');
    	 
    }
    
    
    public function bundling_purchase_historyOp(){
    	$model = Model('p_bundling_quota');
    	$quota_list = $model->where('store_id='.$_SESSION['store_id'])->order('bl_quota_id desc')->page(10)->select();
    	
    	Tpl::output('quota_list', $quota_list);
    	$page = $model->showpage(2);
    	Tpl::output('show_page',$page);
    	
    	self::profile_menu('bundling_list', 'bundling_purchase_history');				//脚部文章输出		$article = $this->_article();
    	Tpl::showpage('store_promotion_bundling.history');
    }
    
    
    public function drop_bundlingOp(){
    	
    	$bl_id = trim($_GET['bundling_id']);
    	if(empty($bl_id)) {
    		showdialog(Language::get('miss_argument'),'','error');
    	}
    	
    	$bl_id_array	= explode(',',$bl_id);
    	$input_bl_count	= count($bl_id_array);
    	$model = Model();
    	$verify_count = $model->table('p_bundling')->where('bl_id in('.implode(',', $bl_id_array).') and store_id='.$_SESSION['store_id'])->count();
    	if($input_bl_count !== intval($verify_count)) {
    		showdialog(Language::get('para_error'),'','html','error');
    	}
    	$state = $model->table('p_bundling')->where('bl_id in('.implode(',', $bl_id_array).')')->delete();
    	$model->table('p_bundling_goods')->where('bl_id in('.implode(',', $bl_id_array).')')->delete();
    	if($state) {
    		showDialog(Language::get('bundling_delete_success'),'reload','succ');
    	} else {
    		showDialog(Language::get('bundling_delete_fail'),'','error');
    	}
    }
  
    private function profile_menu($menu_type,$menu_key='') {
    	$menu_array	= array();
    	$menu_array	= array(
    		1=>array('menu_key'=>'bundling_list','menu_name'=>Language::get('bundling_list'),'menu_url'=>'index.php?act=store_promotion_bundling&op=bundling_list'),
    		2=>array('menu_key'=>'bundling_purchase_history','menu_name'=>Language::get('bundling_purchase_history'),'menu_url'=>'index.php?act=store_promotion_bundling&op=bundling_purchase_history'),
    		3=>array('menu_key'=>'bundling_quota_add','menu_name'=>Language::get('bundling_quota_add'),'menu_url'=>'index.php?act=store_promotion_bundling&op=bundling_quota_add'),
    		4=>array('menu_key'=>'bundling_add','menu_name'=>Language::get('bundling_add'),'menu_url'=>'index.php?act=store_promotion_bundling&op=bundling_add'),
    		5=>array('menu_key'=>'bundling_edit','menu_name'=>Language::get('bundling_edit'),'menu_url'=>'index.php?act=store_promotion_bundling&op=bundling_edit'),
		);
    	switch ($menu_type) {
    		case 'bundling_list':
    		case 'bundling_quota_list':
    			unset($menu_array[3]);
    			unset($menu_array[4]);
    			unset($menu_array[5]);
    			break;
    		case 'bundling_quota_add':
    			unset($menu_array[4]);
    			unset($menu_array[5]);
    			break;
    		case 'bundling_add':
    			unset($menu_array[3]);
    			unset($menu_array[5]);
    			break;
    		case 'bundling_edit':
    			unset($menu_array[3]);
    			unset($menu_array[4]);
    			break;
    	}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
		Tpl::output('menu_sign','bundling');
		Tpl::output('menu_sign_url','index.php?act=store_promotion_bundling');
		Tpl::output('menu_sign1',$menu_key);
    }
    private function bundlingState(){
    	$state_array = array(0=>Language::get('bundling_status_0') , 1=>Language::get('bundling_status_1'));
    	Tpl::output('state_array', $state_array);
    }
    private function bundlingQuotaCheck(){
    	$bundling_quota			= Model()->table('p_bundling_quota')->where('store_id='.$_SESSION['store_id'].' and bl_quota_starttime<'.time().' and bl_quota_endtime>'.time().' and bl_quota_state=1')->find();
    	return empty($bundling_quota)?false:$bundling_quota;
    }		//S脚部文章输出	private function _article() {		if (file_exists(BasePath.'/cache/index/article.php')){			include(BasePath.'/cache/index/article.php');			Tpl::output('show_article',$show_article);			Tpl::output('article_list',$article_list);			return ;				}		$model_article_class	= Model('article_class');		$model_article	= Model('article');		$show_article = array();		$article_list	= array();		$notice_class	= array('notice','store','about');		$code_array	= array('member','store','payment','sold','service','about');		$notice_limit	= 5;		$faq_limit	= 5;		$class_condition	= array();		$class_condition['home_index'] = 'home_index';		$class_condition['order'] = 'ac_sort asc';		$article_class	= $model_article_class->getClassList($class_condition);		$class_list	= array();		if(!empty($article_class) && is_array($article_class)){			foreach ($article_class as $key => $val){				$ac_code = $val['ac_code'];				$ac_id = $val['ac_id'];				$val['list']	= array();				$class_list[$ac_id]	= $val;			}		}				$condition	= array();		$condition['article_show'] = '1';		$condition['home_index'] = 'home_index';		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article.article_content,article_class.ac_name,article_class.ac_parent_id';		$condition['order'] = 'article_sort desc,article_time desc';		$condition['limit'] = '300';		$article_array	= $model_article->getJoinList($condition);		if(!empty($article_array) && is_array($article_array)){			foreach ($article_array as $key => $val){				$ac_id = $val['ac_id'];				$ac_parent_id = $val['ac_parent_id'];				if($ac_parent_id == 0) {					$class_list[$ac_id]['list'][] = $val;				} else {					$class_list[$ac_parent_id]['list'][] = $val;				}			}		}		if(!empty($class_list) && is_array($class_list)){			foreach ($class_list as $key => $val){				$ac_code = $val['ac_code'];				if(in_array($ac_code,$notice_class)) {					$list = $val['list'];					array_splice($list, $notice_limit);					$val['list'] = $list;					$show_article[$ac_code] = $val;				}				if (in_array($ac_code,$code_array)){					$list = $val['list'];					$val['class']['ac_name']	= $val['ac_name'];					array_splice($list, $faq_limit);					$val['list'] = $list;					$article_list[] = $val;				}			}		}		$string = "<?php\n\$show_article=".var_export($show_article,true).";\n";		$string .= "\$article_list=".var_export($article_list,true).";\n?>";		file_put_contents(BasePath.'/cache/index/article.php',compress_code($string));		Tpl::output('show_article',$show_article);		Tpl::output('article_list',$article_list);	}
}
