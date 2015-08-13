<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_snshomeControl extends BaseStoreControl{
	const MAX_RECORDNUM = 20;	
	public function __construct(){
		parent::__construct();
		Language::read('store_sns,member_sns');
		$store_id = intval($_GET['sid']);
		if($store_id > 0){
			$model = Model();
			$store_info = $this->getStoreInfo($store_id);
	
			$store_grade_info = $model->table('store_grade')->where(array('sg_id'=>$store_info['grade_id']))->find();
			$store_info['grade_name'] = $store_grade_info['sg_name'];
			$store_info['grade_goodslimit'] = $store_grade_info['sg_goods_limit'];
			$goods_count = $model->table('goods')->where(array('store_id'=>$store_info['store_id'],'goods_show'=>'1'))->count();
			$store_info['goods_count'] = $goods_count;
			$area_array	= array();
			$area_array = explode("\t",$store_info["area_info"]);
			$map_city	= Language::get('store_sns_city');
			$city	= '';
			if(strpos($area_array[0], $map_city) !== false){
				$city	= $area_array[0];
			}else {
				$city	= $area_array[1];
			}
			$store_info['city'] = $city;
		}
		Tpl::output('max_recordnum', MAX_RECORDNUM);
		Tpl::setDir('store');
		Tpl::setLayout('store_sns_layout');
	}
	
	
	
	public function indexOp(){
		$sid	= intval($_GET['sid']);
		if ($sid <= 0){
			showMessage(Language::get('store_sns_store_not_exists'),'','html','error');
		}
		
		$model	= Model();
		$where = array();
		$where['strace_state'] = 1;
		$where['strace_storeid'] = $sid;
		if($_GET['type'] != ''){
			switch (trim($_GET['type'])){
				case 'promotion':
					$where['strace_type'] = array('in', array(4,5,6,7,8));
					break;
				case 'new':
					$where['strace_type'] = 3;
					break;
				case 'hotsell':
					$where['strace_type'] = 10;
					break;
				case 'recommend':
					$where['strace_type'] = 9;
					break;
			}
		}
		
		$strace_array = $model->table('sns_s_tracelog')->where($where)->order('strace_id desc')->page(40)->select();
		if(!empty($strace_array) && is_array($strace_array)){
			foreach ($strace_array as $key=>$val){
				if($val['strace_content'] == ''){
					$val['strace_goodsdata'] = json_decode($val['strace_goodsdata'],true);
					if( CHARSET == 'GBK') {
						foreach ((array)$val['strace_goodsdata'] as $k=>$v){
							$val['strace_goodsdata'][$k] = Language::getGBK($v);
						}
					}
					$content = $this->spellingStyle($val['strace_type'], $val['strace_goodsdata']);
					$strace_array[$key]['strace_content'] = str_replace("%siteurl%", SiteUrl.DS, $content);
				}
			}
		}
		Tpl::output('strace_array', $strace_array);
		
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));		
		Tpl::output('max_recordnum',self::MAX_RECORDNUM);
		Tpl::output('show_page',$model->showpage(2));
		Tpl::showpage('store_snshome');
	}
	
	
	public function stracelistOp(){
		$this->checkLoginStatus();
		
		
		$model = Model();
		$where = array(
					'member_id'=>$_SESSION['member_id'],
					'fav_type'=>'store'
				);
		$fav_store_id = $model->table('favorites')->field('fav_id')->where($where)->select();
		if(!empty($fav_store_id) && is_array($fav_store_id)){
			$storeid_array = '';
			foreach($fav_store_id as $val){
				$storeid_array[] = $val['fav_id'];
			}
			$where = array(
						'strace_storeid'=>array('in',$storeid_array),
						'strace_state'=>1
					);
			$count = $model->table('sns_s_tracelog')->where($where)->count();
			$page	= new Page();
			$page->setEachNum(30);
			$page->setStyle('admin');
			$page->setTotalNum($count);
			$delaypage = intval($_GET['delaypage'])>0?intval($_GET['delaypage']):1;
			$lazy_arr = lazypage(10,$delaypage,$count,true,$page->getNowPage(),$page->getEachNum(),$page->getLimitStart());
			$limit = $lazy_arr['limitstart'].",".$lazy_arr['delay_eachnum'];
			$strace_array = $count = $model->table('sns_s_tracelog')->where($where)->order('strace_id desc')->limit($limit)->select();
			if (!empty($strace_array)){
				foreach ($strace_array as $key=>$val){
					if($val['strace_content'] == ''){
						$val['strace_goodsdata'] = json_decode($val['strace_goodsdata'],true);
						if( CHARSET == 'GBK') {
							foreach ((array)$val['strace_goodsdata'] as $k=>$v){
								$val['strace_goodsdata'][$k] = Language::getGBK($v);
							}
						}
						$content = $this->spellingStyle($val['strace_type'], $val['strace_goodsdata']);
						$strace_array[$key]['strace_content'] = str_replace("%siteurl%", SiteUrl.DS, $content);
					}
				}
			}
			Tpl::output('show_page',$page->show());
		}
		Tpl::output('hasmore',$lazy_arr['hasmore']);
		Tpl::output('strace_array',$strace_array);
		Tpl::output('type','index');
		Tpl::showpage('store_snstracelist','null_layout');
	}
	
	
	public function commenttopOp(){
		$sid = intval($_GET['id']);
		if($sid > 0){
			$model = Model();
			
			$where = array(
						'strace_id'=>$sid,
						'scomm_state'=>1
					);
			$countnum = $model->table('sns_s_comment')->where($where)->count();
			
			$commentlist = $model->table('sns_s_comment')->where($where)->limit(10)->order('scomm_id desc')->select();
			
			$model->table('sns_s_tracelog')->update(array('trace_id'=>$sid, 'trace_commentcount'=>$countnum));
		}
		$showmore = '0';
		if ($countnum > count($commentlist)){
			$showmore = '1';
		}
		Tpl::output('countnum',$countnum);
		Tpl::output('showmore',$showmore);
		Tpl::output('showtype',1);
		Tpl::output('stid',$_GET['id']);
		
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		
		Tpl::output('max_recordnum',self::MAX_RECORDNUM);
		
		Tpl::output('commentlist',$commentlist);
		Tpl::showpage('store_snscommentlist','null_layout');
	}
	
	public function commentlistOp(){
		$sid = intval($_GET['id']);
		if($sid > 0){
			$model = Model('sns_s_comment');
			$where = array(
						'strace_id'=>$sid,
						'scomm_state'=>1
					);
			$countnum = $model->where($where)->count();
			$commentlist = $model->where($where)->order('scomm_id desc')->page(10)->select();
			
			$commentlist = $model->table('sns_s_tracelog')->update(array('trace_id'=>$sid, 'trace_commentcount'=>$countnum));
		}
		
		Tpl::output('commentlist',$commentlist);
		Tpl::output('countnum',$countnum);
		Tpl::output('stid',$sid);
		Tpl::output('showtype','0');
		
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		
		Tpl::output('max_recordnum',self::MAX_RECORDNUM);
		
		Tpl::output('show_page',$model->showpage(2));
		Tpl::showpage('store_snscommentlist','null_layout');
	}
	
	public function addcommentOp(){
		$this->checkLoginStatus();
		
		$stid = intval($_POST['stid']);
		if($stid <= 0){
			showDialog(Language::get('wrong_argument'),'','error');
		}
		$obj_validate = new Validate();
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
		$model = Model();
		$member_info = $model->table('member')->where(array('member_state'=>1))->find($_SESSION['member_id']);
		if (empty($member_info)){
			showDialog(Language::get('sns_member_error'),'','error');
		}
		$insert_arr = array();
		$insert_arr['strace_id'] 			= $stid;
		$insert_arr['scomm_content']		= $_POST['commentcontent'];
		$insert_arr['scomm_memberid']		= $member_info['member_id'];
		$insert_arr['scomm_membername']		= $member_info['member_name'];
		$insert_arr['scomm_memberavatar']	= $member_info['member_avatar'];
		$insert_arr['scomm_time']			= time();
		$result = $model->table('sns_s_comment')->insert($insert_arr);
		if ($result){
			$update = array(
				'strace_id'=>$stid,
				'strace_comment'=>array('exp','strace_comment+1')
			);
			$rs = $model->table('sns_s_tracelog')->update($update);
			if (cookie('commentnum') != null && intval(cookie('commentnum')) >0){
				setNcCookie('commentnum',intval(cookie('commentnum'))+1,2*3600);
			}else{
				setNcCookie('commentnum',1,2*3600);
			}
			$js = "$('#content_comment".$stid."').html('');";
			if ($_POST['showtype'] == 1){
				$js .="$('#tracereply_".$stid."').load('index.php?act=store_snshome&op=commenttop&id=".$stid."');";
			}else {
				$js .="$('#tracereply_".$stid."').load('index.php?act=store_snshome&op=commentlist&id=".$stid."');";
			}
			showDialog(Language::get('sns_comment_succ'),'','succ',$js);
		}
	}
	
	public function addforwardOp(){
		$this->checkLoginStatus();
		
		$obj_validate = new Validate();
		$stid = intval($_POST["stid"]);
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
		$model = Model();
		$member_info = $model->table('member')->where(array('member_state'=>1))->find($_SESSION['member_id']);
		if (empty($member_info)){
			showDialog(Language::get('sns_member_error'),'','error');
		}
		$stracelog_info = $model->table('sns_s_tracelog')->find($stid);
		if (empty($stracelog_info)){
			showDialog(Language::get('sns_forward_fail'),'','error');
		}
		if($stracelog_info['strace_content'] == ''){
			$data = json_decode($stracelog_info['strace_goodsdata'],true);
			if( CHARSET == 'GBK') {
				foreach ((array)$data as $k=>$v){
					$data[$k] = Language::getUTF8($v);
				}
			}
			$stracelog_info['strace_content']	= $this->spellingStyle($stracelog_info['strace_type'], $data);
		}
		$stracelog_info['strace_storelogo']		= $stracelog_info['strace_storelogo'] != '' ? ATTACH_STORE.DS.$stracelog_info['strace_storelogo'] : ATTACH_COMMON.DS.C('default_store_logo');
		
		$insert_arr = array();
		$insert_arr['trace_originalid']			= 0;
		$insert_arr['trace_originalmemberid']	= 0;
		$insert_arr['trace_originalstate']		= 0;
		$insert_arr['trace_memberid'] 			= $member_info['member_id'];
		$insert_arr['trace_membername']			= $member_info['member_name'];
		$insert_arr['trace_memberavatar']		= $member_info['member_avatar'];
		$insert_arr['trace_title']				= $_POST['forwardcontent']?$_POST['forwardcontent']:Language::get('sns_forward');
		$insert_arr['trace_content']			= "<dl class=\"fd-wrap\">
														<dt>
															<h3><a href=\"index.php?act=store_snshome&sid=".$stracelog_info['strace_storeid']."\" target=\"_blank\">".$stracelog_info['strace_storename']."</a>".Language::get('nc_colon')."
															".$stracelog_info['strace_title']."</h3>
										      			</dt>
														<dd>".$stracelog_info['strace_content']."</dd>
													<dl>";
		$insert_arr['trace_addtime']			= time();
		$insert_arr['trace_state']				= 0;
		$insert_arr['trace_privacy']			= 0;
		$insert_arr['trace_commentcount']		= 0;
		$insert_arr['trace_copycount']			= 0;
		$insert_arr['trace_orgcommentcount']	= 0;
		$insert_arr['trace_orgcopycount']		= 0;
		$insert_arr['trace_from']				= 2;
		$result = $model->table('sns_tracelog')->insert($insert_arr);
		if ($result){
			$update	= array(
				'strace_id'=>$stid,
				'strace_spread'=>array('exp', 'strace_spread+1')
			);
			$model->table('sns_s_tracelog')->update($update);
			showDialog(Language::get('sns_forward_succ'),'','succ');
		}else {
			showDialog(Language::get('sns_forward_fail'),'','error');
		}
	}
	
	public function deltraceOp(){
		$this->checkLoginStatus();
		
		$stid = intval($_GET['id']);
		if ($stid <= 0){
			showDialog(Language::get('wrong_argument'),'','error');
		}
		$model = Model();
		$result = $model->table('sns_s_tracelog')->where(array('strace_id'=>$stid , 'strace_storeid'=>$_SESSION['store_id'] ))->delete();
		if ($result){
			$model->table('sns_s_comment')->where(array('strace_id' => $stid))->delete();
			$js = "$('[nc_type=\"tracerow_{$stid}\"]').remove();";
			showDialog(Language::get('nc_common_del_succ'),'','succ',$js);
		} else {
			showDialog(Language::get('nc_common_del_fail'),'','error');
		}
	}
	
	public function delcommentOp(){
		$this->checkLoginStatus();
		
		$scid = intval($_GET['scid']);$stid = intval($_GET['stid']);
		if ($scid <= 0 || $stid <= 0){
			showDialog(Language::get('wrong_argument'),'','error');
		}
		$model = Model();
		$where = array('strace_id'=>$stid, 'scomm_id' => $scid, 'scomm_memberid' => $_SESSION['member_id']);	// where条件
		$scomment_info	= $model->table('sns_s_comment')->where($where)->select();
		$scomment_info	= $scomment_info[0];
		if(empty($scomment_info)){
			showDialog(Language::get('wrong_argument'),'','error');
		}
		
		$result = $model->table('sns_s_comment')->where($where)->delete();
		if ($result){
			$update = array(
						'strace_id'=>$scomment_info['strace_id'],
						'strace_comment'=>array('exp','strace_comment-1')
					);
			$model->table('sns_s_tracelog')->update($update);
			
			$js .="$('.comment-list [nc_type=\"commentrow_".$scid."\"]').remove();";
			showDialog(Language::get('nc_common_del_succ'),'','succ',$js);
		}else {
			showDialog(Language::get('nc_common_del_fail'),'','error');
		}
	}
	
	public function straceinfoOp(){
		$st_id = intval($_GET['st_id']);
		if($st_id <= 0){
			showMessage(Language::get('miss_argument'),'','','error');
		}
		$model = Model();
		$strace_info = $model->table('sns_s_tracelog')->find($st_id);
		if(!empty($strace_info)){
			if($strace_info['strace_content'] == ''){
				$content = $this->spellingStyle($strace_info['strace_type'], json_decode($strace_info['strace_goodsdata'],true));
				$strace_info['strace_content'] = str_replace("%siteurl%", SiteUrl.DS, $content);
			}
		}
		Tpl::output('strace_info', $strace_info);
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		Tpl::showpage('store_snstraceinfo');
	}
	
	private function spellingStyle($type,$data){
		$rs = '';
		switch ($type){
			case '2':
				break;
			case '3':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\"><img src=\"".cthumb($data['goods_image'],'small',$data['store_id'])."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['goods_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-new\">".Language::get('store_sns_new_selease')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\">".$data['goods_name']."</a></dt>
							<dd>".Language::get('sns_sharegoods_price').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['goods_store_price'])."</dd>
							<dd>".($data['goods_transfee_charge'] == '1'?Language::get('store_sns_free_shipping'):Language::get('sns_sharegoods_freight').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['py_price']))."</dd>
	                  		<dd nctype=\"collectbtn_".$data['goods_id']."\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods('".$data['goods_id']."','succ','collectbtn_".$data['goods_id']."');\">".Language::get('sns_sharegoods_collect')."</a></dd>
	                  	</dl>
	                  </div>
	             </div>";
				break;
			case '4':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'coupon_store', 'op'=>'detail','coupon_id'=>$data['coupon_id'],'id'=>$data['store_id']), 'coupon_info')."\"><img src=\"".$data['coupon_pic']."\" onerror=\"this.src='".TEMPLATES_PATH."/images/default_coupon_image.png'\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['coupon_title']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-coupon\">".Language::get('store_sns_coupon')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'coupon_store', 'op'=>'detail','coupon_id'=>$data['coupon_id'],'id'=>$data['store_id']), 'coupon_info')."\">".$data['coupon_title']."</a></dt>
							<dd>".Language::get('store_sns_coupon_price').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['coupon_price'])."</dd>
							<dd>".Language::get('store_sns_start-stop_time').Language::get('nc_colon').date('Y-m-d H:i', $data['coupon_start_date'])."~".date('Y-m-d H:i', $data['coupon_end_date'])."</dd>
	                  	</dl>
	                  </div>
	             </div>";
				break;
			case '5':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\"><img src=\"".cthumb($data['goods_image'],'small',$data['store_id'])."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['goods_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-xianshi\">".Language::get('store_sns_xianshi')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\">".$data['goods_name']."</a></dt>
							<dd>".Language::get('sns_sharegoods_price').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['goods_store_price'])."</dd>
							<dd>".Language::get('store_sns_formerprice').Language::get('nc_colon').Language::get('currency').ncPriceFormat(intval($data['goods_store_price'])*intval($data['discount'])*0.1)."</dd>
	                  		<dd nctype=\"collectbtn_".$data['goods_id']."\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods('".$data['goods_id']."','succ','collectbtn_".$data['goods_id']."');\">".Language::get('sns_sharegoods_collect')."</a></dd>
	                  	</dl>
	                  </div>
	             </div>";
				break;
			case '6':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'show_store','id'=>$data['store_id']), 'store')."\"><img src=\"".TEMPLATES_PATH."/images/mjs-pic.gif\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['ansong_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-mansong\">".Language::get('store_sns_mansong')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'show_store','id'=>$data['store_id']), 'store')."\">".$data['mansong_name']."</a></dt>
							<dd>".Language::get('store_sns_start-stop_time').Language::get('nc_colon').date('Y-m-d H:i', $data['start_time'])."~".date('Y-m-d H:i', $data['end_time'])."</dd>
	                  	</dl>
	                  </div>
	             </div>";
				break;
			case '7':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'bundling', 'bundling_id'=>$data['bl_id'] ,'id'=>$data['store_id']), 'bungling')."\"><img src=\"".cthumb($data['bl_img'],'small',$data['store_id'])."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['bl_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-bundling\">".Language::get('store_sns_bundling')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'bundling', 'bundling_id'=>$data['bl_id'] ,'id'=>$data['store_id']), 'bungling')."\">".$data['bl_name']."</a></dt>
							<dd>".Language::get('store_sns_bundling_price').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['bl_discount_price'])."</dd>
							<dd>".(($data['bl_freight_choose']==1)?Language::get('store_sns_free_shipping'):Language::get('sns_sharegoods_freight').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['bl_freight']))."</dd>
						</dl>
	                  </div>
	             </div>";
				break;
			case '8':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\"><img src=\"".gthumb($data['group_pic'],'small',$data['store_id'])."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['group_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-groupbuy\">".Language::get('store_sns_gronpbuy')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\">".$data['group_name']."</a></dt>
							<dd>".Language::get('store_sns_goodsprice').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['goods_price'])."</dd>
							<dd>".Language::get('store_sns_groupprice').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['groupbuy_price'])."</dd>
							<dd>".Language::get('store_sns_start-stop_time').Language::get('nc_colon').date('Y-m-d H:i', $data['start_time'])."~".date('Y-m-d H:i', $data['end_time'])."</dd>
						</dl>
					</div>
				</div>";
				break;
			case '9':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\"><img src=\"".thumb($data,'small')."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['goods_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-recommend\">".Language::get('store_sns_store_recommend')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\">".$data['goods_name']."</a></dt>
							<dd>".Language::get('sns_sharegoods_price').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['goods_store_price'])."</dd>
							<dd>".Language::get('sns_sharegoods_freight').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['py_price'])."</dd>
	                  		<dd nctype=\"collectbtn_{$data['goods_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods('".$data['goods_id']."','succ','collectbtn_".$data['goods_id']."');\">".Language::get('sns_sharegoods_collect')."</a></dd>
	                  	</dl>
	                  </div>
	             </div>";
				break;
			case '10':
				$rs = "<div class=\"fd-media\">
					<div class=\"goodsimg\"><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\"><img src=\"".thumb($data,'small')."\" onload=\"javascript:DrawImage(this,120,120);\" alt=\"{$data['goods_name']}\"></a></div>
					<div class=\"goodsinfo\">
						<dl>
							<dt><i class=\"desc-type desc-type-hotsell\">".Language::get('store_sns_hotsell')."</i><a target=\"_blank\" href=\"%siteurl%".ncUrl(array('act'=>'goods','goods_id'=>$data['goods_id']), 'goods')."\">{$data['goods_name']}</a></dt>
							<dd>".Language::get('sns_sharegoods_price').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['goods_store_price'])."</dd>
							<dd>".Language::get('sns_sharegoods_freight').Language::get('nc_colon').Language::get('currency').ncPriceFormat($data['py_price'])."</dd>
	                  		<dd nctype=\"collectbtn_{$data['goods_id']}\"><a href=\"javascript:void(0);\" onclick=\"javascript:collect_goods('{$data['goods_id']}','succ','collectbtn_{$data['goods_id']}');\">".Language::get('sns_sharegoods_collect')."</a></dd>
	                  	</dl>
	                  </div>
	             </div>";
				break;
		}
		return $rs;
	}
	
	private function checkLoginStatus(){
		if ($_SESSION['is_login'] != 1){
			@header("location: index.php?act=login&ref_url=".urlencode('index.php?act=member_snshome'));
		}
		
	}
}
