<?php



defined('haipinlegou') or exit('Access Invalid!');



class member_snsindexControl extends BaseMemberControl {

	const MAX_RECORDNUM = 20;

	public function __construct(){

		parent::__construct();

		Tpl::output('relation','3');

		Language::read('member_sns');

		Tpl::output('max_recordnum',self::MAX_RECORDNUM);

	}





	public function indexOp(){

		Language::read('member_member_index');

		$lang	= Language::getLangContent();

        define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));

        require_once (_ROOT . "/../vendor/shunfeng/class/SFforHttpPost.class.php");

		$model_order = Model('order');



		

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

		

		$model_my_order = Model('my_order');

		$array	= array();

		$array['order_sn']		= trim($_GET['order_sn']);		

		$array['order_state']	= (in_array(trim($_GET['state_type']),array('order_pay','order_submit','order_pay_confirm','order_no_shipping','order_shipping','order_finish','order_cancal','order_refer','order_confirm')) ? trim($_GET['state_type']) : '');

		if($_GET['state_type'] == 'noeval'){

			$array['order_evalbuyer_able']		= '1';

		}

		

		$array['add_time_from'] = strtotime($_GET['add_time_from']);

        $array['add_time_to'] = strtotime($_GET['add_time_to']);

        if($array['add_time_to'] > 0) {

            $array['add_time_to'] +=86400;

        }

		$order_list		= $model_my_order->myOrderList($array,$page,'order_id,store_id');

		

		if(is_array($order_list) && !empty($order_list)) {

			$order_id_array = array();

			$store_id_array = array();

			$store_array = array();

			$refund_array = array();

			$return_array = array();

			$model_store = Model('store');

			foreach ($order_list as $v) {

				$order_id_array[] = $v['order_id'];

				if(!in_array($v['store_id'],$store_id_array)) $store_id_array[] = $v['store_id'];

			}

			$order_list		= array();

			$order_list		= $model_my_order->myOrderGoodsList(array('order_id_string'=>"'".implode("','",$order_id_array)."'"));

			$store_list = $model_store->getStoreList(array('store_id_in'=>"'".implode("','",$store_id_array)."'"));

			if (is_array($store_list) && !empty($store_list)){

				foreach ($store_list as $val) {

					$store_array[$val['store_id']] = $val;

				}

			}

			

			$model_refund	= Model('refund');

			$condition = array();

			$condition['buyer_id'] = $_SESSION['member_id'];

			$condition['order_ids'] = "'".implode("','",$order_id_array)."'";

			$condition['refund_type'] = '1';

			$refund_list = $model_refund->getList($condition);

			if (is_array($refund_list) && !empty($refund_list)){

				foreach ($refund_list as $val) {

					$refund_array[$val['order_id']] = $val;

				}

			}

			

			$model_return	= Model('return'); 

			$condition = array();

			$condition['buyer_id'] = $_SESSION['member_id'];

			$condition['order_ids'] = "'".implode("','",$order_id_array)."'";

			$condition['return_type'] = '1';

			$return_list= $model_return->getList($condition);

			if (is_array($return_list) && !empty($return_list)){

				foreach ($return_list as $val) {

					$return_array[$val['order_id']] = $val;

				}

			}

			

			$order_array	= array();

			if(is_array($order_list) && !empty($order_list)) {//var_dump($order_list);die;

				$store_id	= 0;

				$store = array();

				foreach ($order_list as $val) {

                    /**************获取物流信息******************/

                    if($val['shipping_express_id']>0 && $val['shipping_express_id']==29){
                        $SF = new SFapi();
                        $search_orderid = $val['order_sn'];
                        //$search_orderid = '2015072291029954';
                        $datasearch = $SF->OrderSearchService($search_orderid)->Send()->readJSON();
                        @preg_match ("/\"mailno\":\".*?\"/is", $datasearch, $m) ;
                        $str=@str_replace("\"", "",$m[0]);
                        $strarr=@explode(":",$str);
                        // $strarr[1]='444004747793';
                        if($strarr[1]){
                            $route_mailno = $strarr[1];
                            // $route_mailno='444004747793';
                            $data = $SF->RouteService($route_mailno)->Send()->View();

                            $xml = simplexml_load_string($data);
                            $data=json_decode(json_encode($xml->Body->RouteResponse), TRUE);
                            $routreorder=array();
                            if($data['Route']){
                                foreach($data['Route'] as $k1=>$v2){
                                    foreach($v2 as $v3){
                                        $routreorder[]=$v3;
                                    }
                                }
                            }

                        }
                    }
                    //发货后没有物流信息客户中心显示已付款等待卖家发货

                    if($val['order_state'] == 30 && empty($routreorder)) {
                        $val['order_state'] = 20;
                    }

                    /**************获取物流信息结束**********************/
					$order_id = $val['order_id'];

					if(is_array($refund_array[$order_id]) && !empty($refund_array[$order_id])) $val['refund'] = $refund_array[$order_id];

					if(is_array($return_array[$order_id]) && !empty($return_array[$order_id])) $val['return'] = $return_array[$order_id];

					$val['spec_info_arr'] = '';

					if (!empty($val['spec_info'])){

						$val['spec_info_arr']	= unserialize($val['spec_info']);

					}

					$val['state_info']		= orderStateInfo($val['order_state'],$val['refund_state']);

					$store_id	= $val['store_id'];

					$store = $store_array[$store_id];

					

					$val['member_id'] = $store['member_id'];

					$val['member_name'] = $store['member_name'];

					$val['store_qq'] = $store['store_qq'];

					$val['store_ww'] = $store['store_ww'];

					unset($store);

					if ($val['evaluation_status'] == 1){

						$val['able_evaluate'] = false;

					}else {

						$val['able_evaluate'] = true;

					}

					if ($val['able_evaluate'] && $val['evalseller_status'] == 0 && (intval($val['finnshed_time'])+60*60*24*15)<time()){

						$val['able_evaluate'] = false;

					}elseif ($val['able_evaluate'] && $val['evalseller_status'] == 1 && (intval($val['evalseller_time'])+60*60*24*15)<time()) {

						$val['able_evaluate'] = false;

					}

					$order_array[$val['order_id']][] = $val;

				}

			}

		}

		Tpl::output('order_array',$order_array);

		Tpl::output('show_page',$page->show());

        Tpl::output('complain_time_limit',$GLOBALS['setting_config']['complain_time_limit']);

		

		//S脚部内容显示

		$list = $this->_article();

        //E脚部内容显示
        
        
        //我的代金券
        $model = Model();
        $this->voucherstate_arr = array('unused'=>array(1,Language::get('voucher_voucher_state_unused')),'used'=>array(2,Language::get('voucher_voucher_state_used')),'expire'=>array(3,Language::get('voucher_voucher_state_expire')));
        $model->table('voucher')->where(array('voucher_owner_id'=>$_SESSION['member_id'],'voucher_state'=>$this->voucherstate_arr['unused'][0],'voucher_end_date'=>array('lt',time())))->update(array('voucher_state'=>$this->voucherstate_arr['expire'][0]));
        $model = Model();

		$where = array('voucher_owner_id'=>$_SESSION['member_id']);


	    $vouchertotal = $model->table('voucher')->where($where)->order('voucher_id desc')->count();
        Tpl::output('vouchertotal',$vouchertotal);
        
        
	    $this->get_member_info();

		self::profile_menu('member_order','member_order');

		Tpl::output('menu_sign','myorder');

		Tpl::output('menu_sign_url','index.php?act=member&op=order');

		Tpl::output('menu_sign1','myorder_list');

		Tpl::showpage('member_snsindex');

	}





    private function profile_menu($menu_type,$menu_key='') {

		

		Language::read('member_layout');

		$menu_array	= array();

		switch ($menu_type) {

			case 'address':

				$menu_array = array(

				1=>array('menu_key'=>'address','menu_name'=>Language::get('nc_member_path_address_list'),	'menu_url'=>'index.php?act=member&op=address'));

				break;

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



	private function formatDate($time){

		$handle_date = @date('Y-m-d',$time);

		$reference_date = @date('Y-m-d',time());

		$handle_date_time = strtotime($handle_date);

		$reference_date_time = strtotime($reference_date);

		if ($reference_date_time == $handle_date_time){

			$timetext = @date('H:i',$time);

		}elseif (($reference_date_time-$handle_date_time)==60*60*24){

			$timetext = Language::get('sns_yesterday');

		}elseif ($reference_date_time-$handle_date_time==60*60*48){

			$timetext = Language::get('sns_beforeyesterday');

		}else {

			$month_text = Language::get('nc_month');

			$day_text = Language::get('nc_day');

			$timetext = @date("m{$month_text}d{$day_text}",$time);

		}

		return $timetext;

	}

	

	public function addtraceOp(){

		$obj_validate = new Validate();

		$validate_arr[] = array("input"=>$_POST["content"], "require"=>"true","message"=>Language::get('sns_sharemood_content_null'));

		$validate_arr[] = array("input"=>$_POST["content"], "validator"=>'Length',"min"=>0,"max"=>140,"message"=>Language::get('sns_content_beyond'));

		if(intval(cookie('weibonum'))>=self::MAX_RECORDNUM){

			$validate_arr[] = array("input"=>$_POST["captcha"], "require"=>"true","message"=>Language::get('wrong_null'));

		}

		$obj_validate -> validateparam = $validate_arr;

		$error = $obj_validate->validate();			

		if ($error != ''){

			showDialog($error,'','error');

		}

		if(intval(cookie('weibonum'))>=self::MAX_RECORDNUM){

			if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

				showDialog(Language::get('wrong_checkcode'),'','error');

			}

		}

		$member_model = Model('member');

		$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));

		if (empty($member_info)){

			showDialog(Language::get('sns_member_error'),'','error');

		}

		$tracelog_model = Model('sns_tracelog');

		$insert_arr = array();

		$insert_arr['trace_originalid'] = '0';

		$insert_arr['trace_originalmemberid'] = '0';

		$insert_arr['trace_memberid'] = $_SESSION['member_id'];

		$insert_arr['trace_membername'] = $_SESSION['member_name'];

		$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];

		$insert_arr['trace_title'] = $_POST['content'];

		$insert_arr['trace_content'] = '';

		$insert_arr['trace_addtime'] = time();

		$insert_arr['trace_state'] = '0';

		$insert_arr['trace_privacy'] = intval($_POST["privacy"])>0?intval($_POST["privacy"]):0;

		$insert_arr['trace_commentcount'] = 0;

		$insert_arr['trace_copycount'] = 0;

		$result = $tracelog_model->tracelogAdd($insert_arr);

		if ($result){

			if (cookie('weibonum') != null && intval(cookie('weibonum')) >0){

				setNcCookie('weibonum',intval(cookie('weibonum'))+1,2*3600);//保存2小时

			}else{

				setNcCookie('weibonum',1,2*3600);//保存2小时

			}

			$js = "var obj = $(\"#weiboform\").find(\"[nc_type='formprivacytab']\");$(obj).find('span').removeClass('selected');$(obj).find('ul li:nth-child(1)').find('span').addClass('selected');";

			$js .= "$(\"#content_weibo\").val('');$(\"#privacy\").val('0');$('#friendtrace').lazyshow({url:\"index.php?act=member_snsindex&op=tracelist&curpage=1\",'iIntervalId':true});";

			showDialog(Language::get('sns_share_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('sns_share_fail'),'','error');

		}

	}

	

	public function sharegoodsOp(){

		if ($_POST['form_submit'] == 'ok'){

			$obj_validate = new Validate();

			$validate_arr[] = array("input"=>$_POST["choosegoodsid"], "require"=>"true","message"=>Language::get('sns_sharegoods_choose'));

			$validate_arr[] = array("input"=>$_POST["comment"], "validator"=>'Length',"min"=>0,"max"=>140,"message"=>Language::get('sns_content_beyond'));

			if(intval(cookie('weibonum'))>=self::MAX_RECORDNUM){

				$validate_arr[] = array("input"=>$_POST["captcha"], "require"=>"true","message"=>Language::get('wrong_null'));

			}

			$obj_validate -> validateparam = $validate_arr;

			$error = $obj_validate->validate();

			if (intval($_POST["choosegoodsid"]) <= 0){

				$error .= Language::get('sns_sharegoods_goodserror');

			}

			if ($error != ''){

				showDialog($error,'','error');

			}

			if(intval(cookie('weibonum'))>=self::MAX_RECORDNUM){

				if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

					showDialog(Language::get('wrong_checkcode'),'','error');

				}

			}

			$member_model = Model('member');

			$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));

			if (empty($member_info)){

				showDialog(Language::get('sns_member_error'),'','error');

			}

			$goods_model = Model('goods');

			$condition = array();

			$condition['goods_id'] = intval($_POST['choosegoodsid']);

			$condition['goods_state'] = '0';

			$goods_list = $goods_model->getGoods($condition,'','goods_id,goods_name,goods_image,goods_store_price,py_price,goods_collect,store.store_id,store.store_name','store');

			if (empty($goods_list)){

				showDialog(Language::get('sns_sharegoods_goodserror'),'','error');

			}

			$goods_info = $goods_list[0];

			$sharegoods_model = Model('sns_sharegoods');

			$sharegoods_info = $sharegoods_model->getSharegoodsInfo(array('share_memberid'=>"{$_SESSION['member_id']}",'share_goodsid'=>"{$goods_info['goods_id']}"));

			$result = false;

			if (empty($sharegoods_info)){

				$insert_arr = array();

				$insert_arr['share_goodsid'] = $goods_info['goods_id'];

				$insert_arr['share_memberid'] = $_SESSION['member_id'];

				$insert_arr['share_membername'] = $_SESSION['member_name'];

				$insert_arr['share_content'] = $_POST['comment']?$_POST['comment']:Language::get('sns_sharegoods_title');

				$insert_arr['share_addtime'] = time();

				$insert_arr['share_privacy'] = intval($_POST["gprivacy"])>0?intval($_POST["gprivacy"]):0;

				$insert_arr['share_commentcount'] = 0;

				$insert_arr['share_isshare'] = 1;

				$result = $sharegoods_model->sharegoodsAdd($insert_arr);

				unset($insert_arr);

			}else {

				$update_arr = array();

				$update_arr['share_content'] = $_POST['comment']?$_POST['comment']:Language::get('sns_sharegoods_title');

				$update_arr['share_addtime'] = time();

				$update_arr['share_privacy'] = intval($_POST["gprivacy"])>0?intval($_POST["gprivacy"]):0;

				$update_arr['share_isshare'] = 1;

				$result = $sharegoods_model->editSharegoods($update_arr,array('share_id'=>"{$sharegoods_info['share_id']}"));

				unset($update_arr);

			}

			if ($result){

				

				$hash_key = $goods_info['goods_id'];

				if ($_cache = rcache($hash_key,'product')){

					$_cache['sharenum'] = intval($_cache['sharenum'])+1;

					wcache($hash_key,$_cache,'product');

				}

				$snsgoods_model = Model('sns_goods');

				$snsgoods_info = $snsgoods_model->getGoodsInfo(array('snsgoods_goodsid'=>"{$goods_info['goods_id']}"));

				if (empty($snsgoods_info)){

					$insert_arr = array();

					$insert_arr['snsgoods_goodsid'] = $goods_info['goods_id'];

					$insert_arr['snsgoods_goodsname'] = $goods_info['goods_name'];

					$insert_arr['snsgoods_goodsimage'] = $goods_info['goods_image'];

					$insert_arr['snsgoods_goodsprice'] = $goods_info['goods_store_price'];

					$insert_arr['snsgoods_storeid'] = $goods_info['store_id'];

					$insert_arr['snsgoods_storename'] = $goods_info['store_name'];

					$insert_arr['snsgoods_addtime'] = time();

					$insert_arr['snsgoods_likenum'] = 0;

					$insert_arr['snsgoods_sharenum'] = 1;

					$snsgoods_model->goodsAdd($insert_arr);

					unset($insert_arr);

				}else {

					$update_arr = array();

					$update_arr['snsgoods_sharenum'] = intval($snsgoods_info['snsgoods_sharenum'])+1;

					$snsgoods_model->editGoods($update_arr,array('snsgoods_goodsid'=>"{$goods_info['goods_id']}"));

				}

				$tracelog_model = Model('sns_tracelog');

				$insert_arr = array();

				$insert_arr['trace_originalid'] = '0';

				$insert_arr['trace_originalmemberid'] = '0';

				$insert_arr['trace_memberid'] = $_SESSION['member_id'];

				$insert_arr['trace_membername'] = $_SESSION['member_name'];

				$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];

				$insert_arr['trace_title'] = $_POST['comment']?$_POST['comment']:Language::get('sns_sharegoods_title');

				$content_str = '';

				$content_str .= "<div class=\"fd-media\">

					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$goods_info['goods_id']), 'goods')."\"><img src=\"".thumb($goods_info,'small')."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$goods_info['goods_name']}\"></a></div>

					<div class=\"goodsinfo\">

						<dl>

							<dt><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$goods_info['goods_id']), 'goods')."\">".$goods_info['goods_name']."</a></dt>

							<dd>".Language::get('sns_sharegoods_price').Language::get('nc_colon').Language::get('currency').$goods_info['goods_store_price']."</dd>

							<dd>".Language::get('sns_sharegoods_freight').Language::get('nc_colon').Language::get('currency').$goods_info['py_price']."</dd>

	                  		<dd nctype=\"collectbtn_{$goods_info['goods_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods(\'{$goods_info['goods_id']}\',\'succ\',\'collectbtn_{$goods_info['goods_id']}\');\">".Language::get('sns_sharegoods_collect')."</a></dd>

	                  	</dl>

	                  </div>

	             </div>";

				$insert_arr['trace_content'] = $content_str;

				$insert_arr['trace_addtime'] = time();

				$insert_arr['trace_state'] = '0';

				$insert_arr['trace_privacy'] = intval($_POST["gprivacy"])>0?intval($_POST["gprivacy"]):0;

				$insert_arr['trace_commentcount'] = 0;

				$insert_arr['trace_copycount'] = 0;

				$result = $tracelog_model->tracelogAdd($insert_arr);

				if (cookie('weibonum') != null && intval(cookie('weibonum')) >0){

					setNcCookie('weibonum',intval(cookie('weibonum'))+1,2*3600);

				}else{

					setNcCookie('weibonum',1,2*3600);

				}

				if (C('share_isuse') == 1){

					$model = Model('sns_binding');

					$bind_list = $model->getUsableApp($_SESSION['member_id']);

					$params = array();

					$params['title'] = Language::get('sns_sharegoods_title');

					$params['url'] = SiteUrl.DS.ncUrl(array('act'=>'goods','goods_id'=>$goods_info['goods_id']), 'goods');

					$params['comment'] = $goods_info['goods_name'].$_POST['comment'];

					$params['images'] = thumb($goods_info,'small');

					if (isset($_POST['checkapp_qqweibo']) && !empty($_POST['checkapp_qqweibo']) && $bind_list['qqweibo']['isbind'] == true){

						$model->addQQWeiboPic($bind_list['qqweibo'],$params);

					}

					if (isset($_POST['checkapp_qqzone']) && !empty($_POST['checkapp_qqzone']) && $bind_list['qqzone']['isbind'] == true){

						$model->addQQZoneFeed($bind_list['qqzone'],$params);

					}

					if (isset($_POST['checkapp_sinaweibo']) && !empty($_POST['checkapp_sinaweibo']) && $bind_list['sinaweibo']['isbind'] == true){

						$model->addSinaWeiboUpload($bind_list['sinaweibo'],$params);

					}

					if (isset($_POST['checkapp_renren']) && !empty($_POST['checkapp_renren']) && $bind_list['renren']['isbind'] == true){

						$model->addRenrenFeed($bind_list['renren'],$params);

					}

				}

				$js = "DialogManager.close('sharegoods');var countobj=$('[nc_type=\'sharecount_{$goods_info['goods_id']}\']');$(countobj).html(parseInt($(countobj).text())+1);";

				$url = '';

				if ($_GET['irefresh']){

					$js .= "$('#friendtrace').lazyshow({url:\"index.php?act=member_snsindex&op=tracelist&curpage=1\",'iIntervalId':true});";

				}else{

					$url = 'reload';

				}

				showDialog(Language::get('sns_share_succ'),$url,'succ',$js);

			}else {

				showDialog(Language::get('sns_share_fail'),$url,'error');

			}

		} else {

			$order_model = Model('order');

			$condition = array();

			$condition['buyer_id'] = "{$_SESSION['member_id']}";

			$condition['order_state'] = "order_finish";

			$ordergoods_list = $order_model->OrderGoodsList($condition);

			unset($condition);

			$order_goodsid = array();

			if (!empty($ordergoods_list)){

				foreach ($ordergoods_list as $v){

					$order_goodsid[] = $v['goods_id'];

				}

			}

			

			$favorites_list = Model()->table('favorites')->field('fav_id')->where(array('member_id'=>$_SESSION['member_id'], 'fav_type'=>'goods'))->select();

			$favorites_goodsid = array();

			if(!empty($favorites_list)){

				foreach ($favorites_list as $v){

					$favorites_goodsid[] = $v['fav_id'];

				}

			}

			

			$goods_id = array_merge($order_goodsid, $favorites_goodsid);

			$goods_model = Model('goods');

			$condition = array();

			$condition['goods_id_in'] = "'".implode("','",$goods_id)."'";

			$condition['goods_state'] = '0';

			$goods_list = $goods_model->getGoods($condition,'','goods_id,goods_name,goods_image,store_id','goods');

			if(!empty($goods_list)){

				foreach ($goods_list as $k=>$v){

					if(in_array($v['goods_id'], $order_goodsid)){

						$goods_list[$k]['order'] = true;

					}

					if(in_array($v['goods_id'], $favorites_goodsid)){

						$goods_list[$k]['favorites'] = true;

					}

				}

			}

			

			Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

			Tpl::output('goods_list',$goods_list);

			Tpl::showpage('member_snssharegoods','null_layout');

		}

	}

	

	public function sharestoreOp(){

		if ($_POST['form_submit'] == 'ok'){

			$obj_validate = new Validate();

			$validate_arr[] = array("input"=>$_POST["choosestoreid"], "require"=>"true","message"=>Language::get('sns_sharestore_choose'));

			$validate_arr[] = array("input"=>$_POST["comment"], "validator"=>'Length',"min"=>0,"max"=>140,"message"=>Language::get('sns_content_beyond'));

			if(intval(cookie('weibonum'))>=self::MAX_RECORDNUM){

				$validate_arr[] = array("input"=>$_POST["captcha"], "require"=>"true","message"=>Language::get('wrong_null'));

			}

			$obj_validate -> validateparam = $validate_arr;

			$error = $obj_validate->validate();			

			if ($error != ''){

				showDialog($error,'','error');

			}

			if(intval(cookie('weibonum'))>=self::MAX_RECORDNUM){

				if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

					showDialog(Language::get('wrong_checkcode'),'','error');

				}

			}

			$member_model = Model('member');

			$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));

			if (empty($member_info)){

				showDialog(Language::get('sns_member_error'),'','error');

			}

			$store_model = Model('store');

			$condition = array();		

			$condition['store_id'] = "{$_POST['choosestoreid']}";

			$condition['store_state'] = '1';

			$store_info = $store_model->shopStore($condition);

			if (empty($store_info)){

				showDialog(Language::get('sns_store_error'),'','error');

			}

			$sharestore_model = Model('sns_sharestore');

			$sharestore_info = $sharestore_model->getSharestoreInfo(array('share_memberid'=>"{$_SESSION['member_id']}",'share_storeid'=>"{$store_info['store_id']}"));

			$result = false;

			if (empty($sharestore_info)){

				$insert_arr = array();

				$insert_arr['share_storeid'] = $store_info['store_id'];

				$insert_arr['share_storename'] = $store_info['store_name'];

				$insert_arr['share_memberid'] = $_SESSION['member_id'];

				$insert_arr['share_membername'] = $_SESSION['member_name'];

				$insert_arr['share_content'] = $_POST['comment'];

				$insert_arr['share_addtime'] = time();

				$insert_arr['share_privacy'] = intval($_POST["sprivacy"])>0?intval($_POST["sprivacy"]):0;

				$result = $sharestore_model->sharestoreAdd($insert_arr);

				unset($insert_arr);

			}else {

				$update_arr = array();

				$update_arr['share_content'] = $_POST['comment'];

				$update_arr['share_addtime'] = time();

				$update_arr['share_privacy'] = intval($_POST["sprivacy"])>0?intval($_POST["sprivacy"]):0;

				$result = $sharestore_model->editSharestore($update_arr,array('share_id'=>"{$sharestore_info['share_id']}"));

				unset($update_arr);

			}

			if ($result){

				$tracelog_model = Model('sns_tracelog');

				$insert_arr = array();

				$insert_arr['trace_originalid'] = '0';

				$insert_arr['trace_originalmemberid'] = '0';

				$insert_arr['trace_memberid'] = $_SESSION['member_id'];

				$insert_arr['trace_membername'] = $_SESSION['member_name'];

				$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];

				$insert_arr['trace_title'] = $_POST['comment']?$_POST['comment']:Language::get('sns_sharestore_title');

				$content_str = '';

				$store_info['store_logo'] = empty($store_info['store_logo']) ? ATTACH_COMMON.DS.$GLOBALS['setting_config']['default_store_logo'] : ATTACH_STORE.DS.$store_info['store_logo'];

				$store_info['store_url'] = ncUrl(array('act'=>'show_store','id'=>$store_info['store_id']),'store',$store_info['store_domain']);

				$content_str .= "<div class=\"fd-media\">

					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%{$store_info['store_url']}\"><img src=\"%siteurl%{$store_info['store_logo']}\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$store_info['store_name']}\"></a></div>

					<div class=\"goodsinfo\">

						<dl>

							<dt><a target=\"_blank\" href=\"%siteurl%{$store_info['store_url']}\">".$store_info['store_name']."</a></dt>

	                  		<dd nctype=\"storecollectbtn_{$store_info['store_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_store(\'{$store_info['store_id']}\',\'succ\',\'storecollectbtn_{$store_info['store_id']}\');\">".Language::get('sns_sharestore_collect')."</a></dd>

	                  	</dl>

	                  </div>

	             </div>";

				$insert_arr['trace_content'] = $content_str;

				$insert_arr['trace_addtime'] = time();

				$insert_arr['trace_state'] = '0';

				$insert_arr['trace_privacy'] = intval($_POST["sprivacy"])>0?intval($_POST["sprivacy"]):0;

				$insert_arr['trace_commentcount'] = 0;

				$insert_arr['trace_copycount'] = 0;

				$result = $tracelog_model->tracelogAdd($insert_arr);

				if (cookie('weibonum') != null && intval(cookie('weibonum')) >0){

					setNcCookie('weibonum',intval(cookie('weibonum'))+1,2*3600);

				}else{

					setNcCookie('weibonum',1,2*3600);

				}

				$js = "DialogManager.close('sharestore');";

				$url = '';

				if ($_GET['irefresh']){

					$js.="$('#friendtrace').lazyshow({url:\"index.php?act=member_snsindex&op=tracelist&curpage=1\",'iIntervalId':true});";

				}else{

					$url = 'reload';

				}

				showDialog(Language::get('sns_share_succ'),$url,'succ',$js);

			}else {

				showDialog(Language::get('sns_share_fail'),$url,'error');

			}

		} else {

			$favorites_model = Model('favorites');

			$condition = array();

			$condition['fav_type'] = "store";

			$condition['member_id'] = "{$_SESSION['member_id']}";

			$favorites_list = $favorites_model->getFavoritesList($condition);

			unset($condition);

			$store_list = array();

			if (!empty($favorites_list)){

				$store_id = array();

				foreach ($favorites_list as $v){

					$store_id[] = $v['fav_id'];

				}

				$store_model = Model('store');

				$condition = array();

				$condition['store_id_in'] = "'".implode("','",$store_id)."'";

				$condition['store_state'] = '1';

				$store_list = $store_model->getStoreList($condition);

			}

			Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

			Tpl::output('store_list',$store_list);

			Tpl::showpage('member_snssharestore','null_layout');

		}

	}

	

	public function deltraceOp(){

		$id = intval($_GET['id']);

		if ($id <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$tracelog_model = Model('sns_tracelog');

		$condition = array();

		$condition['trace_id'] = "$id";

		$condition['trace_memberid'] = "{$_SESSION['member_id']}";		

		$result = $tracelog_model->delTracelog($condition);

		if ($result){

			$tracelog_model->tracelogEdit(array('trace_originalstate'=>'1'),array('trace_originalid'=>"$id"));

			$comment_model = Model('sns_comment');

			$condition = array();

			$condition['comment_originalid'] = "$id";

			$condition['comment_originaltype'] = "0";

			$comment_model->delComment($condition);

			if ($_GET['type'] == 'href'){

				showDialog(Language::get('nc_common_del_succ'),'index.php?act=member_snshome&op=trace&mid='.$_SESSION['member_id'],'succ');

			}else {

				$js = "location.reload();";

				showDialog(Language::get('nc_common_del_succ'),'','succ',$js);

			}

		} else {

			showDialog(Language::get('nc_common_del_fail'),'','error');

		}

	}

	

	public function tracelistOp(){

		$friend_model = Model('sns_friend');

		$friend_list = $friend_model->listFriend(array('friend_frommid'=>"{$_SESSION['member_id']}"),'*','','simple');

		$mutualfollowid_arr = array();

		$followid_arr = array();

		if (!empty($friend_list)){

			foreach ($friend_list as $k=>$v){

				$followid_arr[] = $v['friend_tomid'];

				if ($v['friend_followstate'] == 2){

					$mutualfollowid_arr[] = $v['friend_tomid'];

				}

			}

		}

		$tracelog_model = Model('sns_tracelog');

		$condition = array();

		$condition['allowshow'] = '1';

		$condition['allowshow_memberid'] = "{$_SESSION['member_id']}";

		$condition['allowshow_followerin'] = "";

		if (!empty($followid_arr)){

			$condition['allowshow_followerin'] = implode("','",$followid_arr);

		}

		$condition['allowshow_friendin'] = "";

		if (!empty($mutualfollowid_arr)){

			$condition['allowshow_friendin'] = implode("','",$mutualfollowid_arr);

		}

		$condition['trace_state'] = "0";

		$count = $tracelog_model->countTrace($condition);

		$page	= new Page();

		$page->setEachNum(30);

		$page->setStyle('admin');

		$page->setTotalNum($count);

		$delaypage = intval($_GET['delaypage'])>0?intval($_GET['delaypage']):1;

		$lazy_arr = lazypage(10,$delaypage,$count,true,$page->getNowPage(),$page->getEachNum(),$page->getLimitStart());

		$condition['limit'] = $lazy_arr['limitstart'].",".$lazy_arr['delay_eachnum'];

		$tracelist = $tracelog_model->getTracelogList($condition);

		if (!empty($tracelist)){

			foreach ($tracelist as $k=>$v){

				if ($v['trace_title']){

					$v['trace_title'] = str_replace("%siteurl%", SiteUrl.DS, $v['trace_title']);

					$v['trace_title_forward'] = '|| @'.$v['trace_membername'].Language::get('nc_colon').preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:]|$)/is",'@${4}${5}',$v['trace_title']);

				}

				if(!empty($v['trace_content'])){

					$v['trace_content'] = str_replace("%siteurl%", SiteUrl.DS, $v['trace_content']);

				}

				$tracelist[$k] = $v;

			}

		}

		Tpl::output('hasmore',$lazy_arr['hasmore']);

		Tpl::output('tracelist',$tracelist);

		Tpl::output('show_page',$page->show());

		Tpl::output('type','index');

		Tpl::showpage('member_snstracelist','null_layout');

	}

	

	public function editprivacyOp(){

		$id = intval($_GET['id']);

		if ($id <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$sharegoods_model = Model("sns_sharegoods");

		$condition = array();

		$condition['share_id'] = "$id";

		$condition['share_memberid'] = "{$_SESSION['member_id']}";

		$privacy = in_array($_GET['privacy'],array(0,1,2))?$_GET['privacy']:0;

		$result = $sharegoods_model->editSharegoods(array('share_privacy'=>"$privacy"),$condition);

		if ($result){

			$privacy_item = $privacy+1;

			$js = "var obj = $(\"#recordone_{$id}\").find(\"[nc_type='privacytab']\"); $(obj).find('span').removeClass('selected');$(obj).find('li:nth-child(".$privacy_item.")').find('span').addClass('selected');";

			showDialog(Language::get('sns_setting_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('sns_setting_fail'),'','error');

		}

	}

	

	public function delgoodsOp(){

		$id = intval($_GET['id']);

		if ($id <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$sharegoods_model = Model("sns_sharegoods");

		$condition = array();

		$condition['share_id'] = "$id";

		$condition['share_memberid'] = "{$_SESSION['member_id']}";

		if ($_GET['type'] == 'like'){

			$condition['share_islike'] = "1";

		}elseif ($_GET['type'] == 'share'){

			$condition['share_isshare'] = "1";

		}

		$sharegoods_info = $sharegoods_model->getSharegoodsInfo($condition);

		if (empty($sharegoods_info)){

			showDialog(Language::get('nc_common_del_fail'),'','error');

		}

		unset($condition);

		$update_arr = array();

		if ($_GET['type'] == 'like'){

			$update_arr['share_islike'] = "0";

		}elseif ($_GET['type'] == 'share'){

			$update_arr['share_isshare'] = "0";

		}

		$result = $sharegoods_model->editSharegoods($update_arr,array('share_id'=>"{$sharegoods_info['share_id']}"));

		if ($result){

			if ($_GET['type'] == 'like'){

				$snsgoods_model = Model('sns_goods');

				$snsgoods_info = $snsgoods_model->getGoodsInfo(array('snsgoods_goodsid'=>"{$sharegoods_info['share_goodsid']}"));

				if (!empty($snsgoods_info)){

					$update_arr = array();

					$update_arr['snsgoods_likenum'] = (intval($snsgoods_info['snsgoods_likenum'])-1)>0?(intval($snsgoods_info['snsgoods_likenum'])-1):0;

					$likemember_arr = array();

					if (!empty($snsgoods_info['snsgoods_likemember'])){

						$likemember_arr = explode(',',$snsgoods_info['snsgoods_likemember']);						

						unset($likemember_arr[array_search($_SESSION['member_id'],$likemember_arr)]);

					}

					$update_arr['snsgoods_likemember'] = implode(',',$likemember_arr);

					$snsgoods_model->editGoods($update_arr,array('snsgoods_goodsid'=>"{$snsgoods_info['snsgoods_goodsid']}"));

				}

			}

			$js = "location.reload();";

			showDialog(Language::get('nc_common_del_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('nc_common_del_fail'),'','error');

		}

	}

	

	public function delstoreOp(){

		$id = intval($_GET['id']);

		if ($id <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$sharestore_model = Model("sns_sharestore");

		$condition = array();

		$condition['share_id'] = "$id";

		$condition['share_memberid'] = "{$_SESSION['member_id']}";

		$result = $sharestore_model->delSharestore($condition);

		if ($result){

			$js = "location.reload();";			

			showDialog(Language::get('nc_common_del_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('nc_common_del_fail'),'','error');

		}

	}

	

	public function storeprivacyOp(){

		$id = intval($_GET['id']);

		if ($id <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$sharestore_model = Model("sns_sharestore");

		$condition = array();

		$condition['share_id'] = "$id";

		$condition['share_memberid'] = "{$_SESSION['member_id']}";

		$privacy = in_array($_GET['privacy'],array(0,1,2))?$_GET['privacy']:0;

		$result = $sharestore_model->editSharestore(array('share_privacy'=>"$privacy"),$condition);

		if ($result){

			$privacy_item = $privacy+1;

			$js = "var obj = $(\"#recordone_{$id}\").find(\"[nc_type='privacytab']\"); $(obj).find('span').removeClass('selected');$(obj).find('li:nth-child(".$privacy_item.")').find('span').addClass('selected');";

			showDialog(Language::get('sns_setting_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('sns_setting_fail'),'','error');

		}

	}

	

	public function addcommentOp(){

		$originalid = intval($_POST["originalid"]);

		if($originalid <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$obj_validate = new Validate();

		$originaltype = intval($_POST['originaltype'])>0?intval($_POST['originaltype']):0;

		$validate_arr[] = array("input"=>$_POST["commentcontent"], "require"=>"true","message"=>Language::get('sns_comment_null'));

		$validate_arr[] = array("input"=>$_POST["commentcontent"], "validator"=>'Length',"min"=>0,"max"=>140,"message"=>Language::get('sns_content_beyond'));

		if(intval(cookie('commentnum'))>=self::MAX_RECORDNUM){

			$validate_arr[] = array("input"=>$_POST["captcha"], "require"=>"true","message"=>Language::get('wrong_null'));

		}

		$obj_validate -> validateparam = $validate_arr;

		$error = $obj_validate->validate();			

		if ($error != ''){

			showDialog($error,'','error');

		}

		if(intval(cookie('commentnum'))>=self::MAX_RECORDNUM){

			if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

				showDialog(Language::get('wrong_checkcode'),'','error');

			}

		}

		$member_model = Model('member');

		$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));

		if (empty($member_info)){

			showDialog(Language::get('sns_member_error'),'','error');

		}

		$owner_id = 0;

		if ($originaltype == 1){

			$sharegoods_model = Model('sns_sharegoods');

			$sharegoods_info = $sharegoods_model->getSharegoodsInfo(array('share_id'=>"{$originalid}"));

			if (empty($sharegoods_info)){

				showDialog(Language::get('sns_comment_fail'),'','error');

			}

			$owner_id = $sharegoods_info['share_memberid'];

		}else {

			$tracelog_model = Model('sns_tracelog');

			$tracelog_info = $tracelog_model->getTracelogRow(array('trace_id'=>"{$originalid}",'trace_state'=>'0'));

			if (empty($tracelog_info)){

				showDialog(Language::get('sns_comment_fail'),'','error');

			}

			$owner_id = $tracelog_info['trace_memberid'];

		}

		$comment_model = Model('sns_comment');

		$insert_arr = array();

		$insert_arr['comment_memberid'] = $_SESSION['member_id'];

		$insert_arr['comment_membername'] = $_SESSION['member_name'];

		$insert_arr['comment_memberavatar'] = $member_info['member_avatar'];

		$insert_arr['comment_originalid'] = $originalid;

		$insert_arr['comment_originaltype'] = $originaltype;

		$insert_arr['comment_content'] = $_POST['commentcontent'];

		$insert_arr['comment_addtime'] = time();

		$insert_arr['comment_ip'] = getIp();

		$insert_arr['comment_state'] = '0';

		$result = $comment_model->commentAdd($insert_arr);

		if ($result){

			if ($originaltype == 1){

				$update_arr = array();

				$update_arr['share_commentcount'] = array('sign'=>'increase','value'=>'1');

				$sharegoods_model->editSharegoods($update_arr,array('share_id'=>"{$originalid}"));

			}else {

				$update_arr = array();

				$update_arr['trace_commentcount'] = array('sign'=>'increase','value'=>'1');

				if (intval($tracelog_info['trace_originalid'])== 0){

					$update_arr['trace_orgcommentcount'] = array('sign'=>'increase','value'=>'1');

				}

				$tracelog_model->tracelogEdit($update_arr,array('trace_id'=>"$originalid"));

				unset($update_arr);

				if (intval($tracelog_info['trace_originalid'])== 0){

					$tracelog_model->tracelogEdit(array('trace_orgcommentcount'=>$tracelog_info['trace_orgcommentcount']+1),array('trace_originalid'=>"$originalid"));

				}

			}

			if (cookie('commentnum') != null && intval(cookie('commentnum')) >0){

				setNcCookie('commentnum',intval(cookie('commentnum'))+1,2*3600);

			}else{

				setNcCookie('commentnum',1,2*3600);

			}

			$js = "$(\"#content_comment{$originalid}\").val('');";

			if ($_POST['showtype'] == 1){

				$js .="$(\"#tracereply_{$originalid}\").load('index.php?act=member_snshome&op=commenttop&mid={$owner_id}&id={$originalid}&type={$originaltype}');";

			}else {

				$js .="$(\"#tracereply_{$originalid}\").load('index.php?act=member_snshome&op=commentlist&mid={$owner_id}&id={$originalid}&type={$originaltype}');";

			}

			showDialog(Language::get('sns_comment_succ'),'','succ',$js);

		}

	}

	

	public function delcommentOp(){

		$id = intval($_GET['id']);

		if ($id <= 0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		$comment_model = Model('sns_comment');

		$comment_info = $comment_model->getCommentRow(array('comment_id'=>"$id",'comment_memberid'=>"{$_SESSION['member_id']}"));

		if (empty($comment_info)){

			showDialog(Language::get('sns_comment_recorderror'),'','error');

		}

		$condition = array();

		$condition['comment_id'] = "$id";		

		$result = $comment_model->delComment($condition);

		if ($result){

			if ($comment_info['comment_originaltype'] == 1){

				$sharegoods_model = Model('sns_sharegoods');

				$update_arr = array();

				$update_arr['share_commentcount'] = array('sign'=>'decrease','value'=>'1');

				$sharegoods_model->editSharegoods($update_arr,array('share_id'=>"{$comment_info['comment_originalid']}"));				

			}else {

				$tracelog_model = Model('sns_tracelog');

				$update_arr = array();

				$update_arr['trace_commentcount'] = array('sign'=>'decrease','value'=>'1');

				$tracelog_model->tracelogEdit($update_arr,array('trace_id'=>"{$comment_info['comment_originalid']}"));

			}

			$js .="$('.comment-list [nc_type=\"commentrow_{$id}\"]').remove();";

			showDialog(Language::get('nc_common_del_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('nc_common_del_fail'),'','error');

		}

	}

	

	public function editlikeOp(){

		$obj_validate = new Validate();

		$validate_arr[] = array("input"=>$_GET["id"], "require"=>"true","message"=>Language::get('sns_likegoods_choose'));			

		$obj_validate -> validateparam = $validate_arr;

		$error = $obj_validate->validate();			

		if ($error != ''){

			showDialog($error,'','error');

		}

		$member_model = Model('member');

		$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));

		if (empty($member_info)){

			showDialog(Language::get('sns_member_error'),'','error');

		}

		$goods_model = Model('goods');

		$condition = array();

		$condition['goods_id'] = intval($_GET["id"]);

		$goods_list = $goods_model->getGoods($condition,'','goods_id,goods_name,goods_image,goods_store_price,py_price,goods_collect,store.store_id,store.store_name','store');

		if (empty($goods_list)){

			showDialog(Language::get('sns_goods_error'),'','error');

		}

		$goods_info = $goods_list[0];

		$sharegoods_model = Model('sns_sharegoods');

		$sharegoods_info = $sharegoods_model->getSharegoodsInfo(array('share_memberid'=>"{$_SESSION['member_id']}",'share_goodsid'=>"{$goods_info['goods_id']}"));

		if (!empty($sharegoods_info) && $sharegoods_info['share_islike'] == 1){

			showDialog(Language::get('sns_likegoods_exist'),'','error');

		}

		if (empty($sharegoods_info)){

			$insert_arr = array();

			$insert_arr['share_goodsid'] = $goods_info['goods_id'];

			$insert_arr['share_memberid'] = $_SESSION['member_id'];

			$insert_arr['share_membername'] = $_SESSION['member_name'];

			$insert_arr['share_content'] = '';

			$insert_arr['share_likeaddtime'] = time();

			$insert_arr['share_privacy'] = 0;

			$insert_arr['share_commentcount'] = 0;

			$insert_arr['share_islike'] = 1;

			$result = $sharegoods_model->sharegoodsAdd($insert_arr);

			unset($insert_arr);

		}else {

			$update_arr = array();

			$update_arr['share_likeaddtime'] = time();

			$update_arr['share_islike'] = 1;

			$result = $sharegoods_model->editSharegoods($update_arr,array('share_id'=>"{$sharegoods_info['share_id']}"));

			unset($update_arr);

		}

		if ($result){

			

			$hash_key = $goods_info['goods_id'];

			if ($_cache = rcache($hash_key,'product')){

				$_cache['likenum'] = intval($_cache['likenum'])+1;

				wcache($hash_key,$_cache,'product');

			}

			$snsgoods_model = Model('sns_goods');

			$snsgoods_info = $snsgoods_model->getGoodsInfo(array('snsgoods_goodsid'=>"{$goods_info['goods_id']}"));

			if (empty($snsgoods_info)){

				$insert_arr = array();

				$insert_arr['snsgoods_goodsid'] = $goods_info['goods_id'];

				$insert_arr['snsgoods_goodsname'] = $goods_info['goods_name'];

				$insert_arr['snsgoods_goodsimage'] = $goods_info['goods_image'];

				$insert_arr['snsgoods_goodsprice'] = $goods_info['goods_store_price'];

				$insert_arr['snsgoods_storeid'] = $goods_info['store_id'];

				$insert_arr['snsgoods_storename'] = $goods_info['store_name'];

				$insert_arr['snsgoods_addtime'] = time();

				$insert_arr['snsgoods_likenum'] = 1;

				$insert_arr['snsgoods_likemember'] = "{$_SESSION['member_id']}";

				$insert_arr['snsgoods_sharenum'] = 0;

				$snsgoods_model->goodsAdd($insert_arr);

				unset($insert_arr);

			}else {

				$update_arr = array();

				$update_arr['snsgoods_likenum'] = intval($snsgoods_info['snsgoods_likenum'])+1;

				$likemember_arr = array();

				if (!empty($snsgoods_info['snsgoods_likemember'])){

					$likemember_arr = explode(',',$snsgoods_info['snsgoods_likemember']);

				}

				$likemember_arr[] = $_SESSION['member_id'];

				$update_arr['snsgoods_likemember'] = implode(',',$likemember_arr);

				$snsgoods_model->editGoods($update_arr,array('snsgoods_goodsid'=>"{$goods_info['goods_id']}"));

			}

			$tracelog_model = Model('sns_tracelog');

			$insert_arr = array();

			$insert_arr['trace_originalid'] = '0';

			$insert_arr['trace_originalmemberid'] = '0';

			$insert_arr['trace_memberid'] = $_SESSION['member_id'];

			$insert_arr['trace_membername'] = $_SESSION['member_name'];

			$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];

			$insert_arr['trace_title'] = Language::get('sns_likegoods_title');

			$content_str = '';

			$content_str .= "<div class=\"fd-media\">

				<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$goods_info['goods_id']), 'goods')."\"><img src=\"".thumb($goods_info,'small')."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$goods_info['goods_name']}\"></a></div>

				<div class=\"goodsinfo\">

					<dl>

						<dt><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$goods_info['goods_id']), 'goods')."\">".$goods_info['goods_name']."</a></dt>

						<dd>".Language::get('sns_sharegoods_price').Language::get('nc_colon').Language::get('currency').$goods_info['goods_store_price']."</dd>

						<dd>".Language::get('sns_sharegoods_freight').Language::get('nc_colon').Language::get('currency').$goods_info['py_price']."</dd>

                  		<dd nctype=\"collectbtn_{$goods_info['goods_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods(\'{$goods_info['goods_id']}\',\'succ\',\'collectbtn_{$goods_info['goods_id']}\');\">".Language::get('sns_sharegoods_collect')."</a>&nbsp;&nbsp;(".$goods_info['goods_collect'].Language::get('sns_collecttip').")</dd>

                  	</dl>

                  </div>

             </div>";

			$insert_arr['trace_content'] = $content_str;

			$insert_arr['trace_addtime'] = time();

			$insert_arr['trace_state'] = '0';

			$insert_arr['trace_privacy'] = 0;

			$insert_arr['trace_commentcount'] = 0;

			$insert_arr['trace_copycount'] = 0;

			$result = $tracelog_model->tracelogAdd($insert_arr);

			$js = "var obj = $(\"#likestat_{$goods_info['goods_id']}\"); $(\"#likestat_{$goods_info['goods_id']}\").find('i').addClass('noaction');$(obj).find('a').addClass('noaction'); var countobj=$('[nc_type=\'likecount_{$goods_info['goods_id']}\']');$(countobj).html(parseInt($(countobj).text())+1);";

			showDialog(Language::get('nc_common_op_succ'),'','succ',$js);

		}else {

			showDialog(Language::get('nc_common_op_fail'),'','error');		

		}

	}

	

	public function addforwardOp(){

		$obj_validate = new Validate();

		$originalid = intval($_POST["originalid"]);

		$validate_arr[] = array("input"=>$originalid, "require"=>"true",'validator'=>'Compare',"operator"=>' > ','to'=>0,"message"=>Language::get('sns_forward_fail'));

		$validate_arr[] = array("input"=>$_POST["forwardcontent"], "validator"=>'Length',"min"=>0,"max"=>140,"message"=>Language::get('sns_content_beyond'));

		if(intval(cookie('forwardnum'))>=self::MAX_RECORDNUM){

			$validate_arr[] = array("input"=>$_POST["captcha"], "require"=>"true","message"=>Language::get('wrong_null'));

		}

		$obj_validate -> validateparam = $validate_arr;

		$error = $obj_validate->validate();

		if ($error != ''){

			showDialog($error,'','error');

		}

		if(intval(cookie('forwardnum'))>=self::MAX_RECORDNUM){

			if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

				showDialog(Language::get('wrong_checkcode'),'','error');

			}

		}

		$member_model = Model('member');

		$member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}",'member_state'=>'1'));

		if (empty($member_info)){

			showDialog(Language::get('sns_member_error'),'','error');

		}

		$tracelog_model = Model('sns_tracelog');

		$tracelog_info = $tracelog_model->getTracelogRow(array('trace_id'=>"{$originalid}",'trace_state'=>"0"));

		if (empty($tracelog_info)){

			showDialog(Language::get('sns_forward_fail'),'','error');

		}

		$insert_arr = array();

		$insert_arr['trace_originalid'] = $tracelog_info['trace_originalid']>0?$tracelog_info['trace_originalid']:$originalid;

		$insert_arr['trace_originalmemberid'] = $tracelog_info['trace_originalid']>0?$tracelog_info['trace_originalmemberid']:$tracelog_info['trace_memberid'];

		$insert_arr['trace_memberid'] = $_SESSION['member_id'];

		$insert_arr['trace_membername'] = $_SESSION['member_name'];

		$insert_arr['trace_memberavatar'] = $member_info['member_avatar'];

		$insert_arr['trace_title'] = $_POST['forwardcontent']?$_POST['forwardcontent']:Language::get('sns_forward');

		if ($tracelog_info['trace_originalid'] > 0 || $tracelog_info['trace_from'] != 1){

			$insert_arr['trace_content'] = addslashes($tracelog_info['trace_content']);

		}else {

			$content_str ="<div class=\"title\"><a href=\"%siteurl%index.php?act=member_snshome&mid={$tracelog_info['trace_memberid']}\" target=\"_blank\" class=\"uname\">{$tracelog_info['trace_membername']}</a>";

			$content_str .= Language::get('nc_colon')."{$tracelog_info['trace_title']}</div>";

			$content_str .=addslashes($tracelog_info['trace_content']);

			$insert_arr['trace_content'] = $content_str;

		}

		$insert_arr['trace_addtime'] = time();

		$insert_arr['trace_state'] = '0';

		if ($tracelog_info['trace_privacy'] >0){

			$insert_arr['trace_privacy'] = 2;

			$insert_arr['trace_privacy'] = 0;

		}

		$insert_arr['trace_commentcount'] = 0;

		$insert_arr['trace_copycount'] = 0;

		$insert_arr['trace_orgcommentcount'] = $tracelog_info['trace_orgcommentcount'];

		$insert_arr['trace_orgcopycount'] = $tracelog_info['trace_orgcopycount'];

		$result = $tracelog_model->tracelogAdd($insert_arr);

		if ($result){

			$tracelog_model = Model('sns_tracelog');

			$update_arr = array();

			$update_arr['trace_copycount'] = array('sign'=>'increase','value'=>'1');

			$update_arr['trace_orgcopycount'] = array('sign'=>'increase','value'=>'1');

			$condition = array();

			if ($tracelog_info['trace_originalid'] > 0){

				$condition['traceid_in'] = "{$tracelog_info['trace_originalid']}','{$originalid}";

			}else {

				$condition['trace_id'] = "$originalid";

			}

			$tracelog_model->tracelogEdit($update_arr,$condition);

			unset($condition);

			$condition = array();

			if ($tracelog_info['trace_originalid'] > 0){

				$condition['trace_originalid'] = "{$tracelog_info['trace_originalid']}";

			}else {

				$condition['trace_originalid'] = "$originalid";

			}

			$tracelog_model->tracelogEdit(array('trace_orgcopycount'=>$tracelog_info['trace_orgcopycount']+1),$condition);

			if ($_GET['irefresh']){

				if (cookie('forwardnum') != null && intval(cookie('forwardnum')) >0){

					setNcCookie('forwardnum',intval(cookie('forwardnum'))+1,2*3600);

				}else{

					setNcCookie('forwardnum',1,2*3600);

				}

				if ($_GET['type']=='home'){

					$js = "$('#friendtrace').lazyshow({url:\"index.php?act=member_snshome&op=tracelist&mid={$tracelog_info['trace_memberid']}&curpage=1\",'iIntervalId':true});";

				}else if ($_GET['type']=='snshome'){

					$js = "$('#forward_".$originalid."').hide();";

				}else {

					$js = "$('#friendtrace').lazyshow({url:\"index.php?act=member_snsindex&op=tracelist&curpage=1\",'iIntervalId':true});";

				}

				showDialog(Language::get('sns_forward_succ'),'','succ',$js);			

			}else {

				showDialog(Language::get('sns_forward_succ'),'','succ');

			}

		}else {

			showDialog(Language::get('sns_forward_fail'),'','error');

		}

	}

	

	public function sharegoods_oneOp(){

		Language::read('member_sharemanage');

		$gid = intval($_GET['gid']);

		if ($gid<=0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		if ($_GET['dialog']){

			$js = "CUR_DIALOG = ajax_form('sharegoods', '".Language::get('sns_sharegoods_tofriend')."', 'index.php?act=member_snsindex&op=sharegoods_one&gid={$gid}', 480);";

			showDialog('','','js',$js);

		}

		$goods_model = Model();

		$goods_info = $goods_model->table('goods')->where(array('goods_id'=>$gid))->find();

		$goods_info['goods_url'] = ncUrl(array('act'=>'goods','goods_id'=>$goods_info['goods_id']), 'goods');

		if (C('share_isuse') == 1){

			$model = Model('sns_binding');

			$app_arr = $model->getUsableApp($_SESSION['member_id']);

			Tpl::output('app_arr',$app_arr);

		}

		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		Tpl::output('goods_info',$goods_info);

		Tpl::showpage('member_snssharegoods_one','null_layout');

	}

	

	public function sharestore_oneOp(){

		$sid = intval($_GET['sid']);

		if ($sid<=0){

			showDialog(Language::get('wrong_argument'),'','error');

		}

		if ($_GET['dialog']){

			$js = "ajax_form('sharestore', '".Language::get('sns_sharestore')."', 'index.php?act=member_snsindex&op=sharestore_one&sid={$sid}', 480);";

			showDialog('','','js',$js);

		}

		$store_model = Model('store');		

		$store_info = $store_model->getOne($sid);

		if (empty($store_info) || $store_info['store_state'] == 0){

			showDialog(Language::get('sns_sharestore_storeerror'),'','error');

		}

		$store_info['store_url'] = ncUrl(array('act'=>'show_store','id'=>$store_info['store_id']),'store',$store_info['store_domain']);

		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		Tpl::output('store_info',$store_info);

		Tpl::showpage('member_snssharestore_one','null_layout');

	}





	public function nostoreindexOp(){

		Tpl::setLayout('member_store_layout');

		Language::read('member_home_index');

		$lang	= Language::getLangContent();	

		

		$member = Model('member');

		$member_info = $member->where('member_id = '.$_SESSION['member_id'])->find();

		//S脚部文章输出

		$list = $this->_article();

		//E脚部文章输出

		Tpl::output('menu_sign','index');

		Tpl::output('member_info',$member_info);

		Tpl::showpage('store_none_index');

	}	

}

