<?php

defined('haipinlegou') or exit('Access Invalid!');

class member_snshomeControl extends BaseSNSControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_sns,sns_home');
		$where = array();
		$where['name']	= !empty($this->member_info['member_truename'])?$this->member_info['member_truename']:$this->member_info['member_name'];
		Model('seo')->type('sns')->param($where)->show();
	}
	
	public function indexOp(){
		$this->get_visitor();	
		$this->sns_messageboard();	
		
		$model = Model();
		$where = array();
		$where['share_memberid']	= $this->master_id;
		$where['share_isshare']		= 1;
		switch ($this->relation){
			case 2:
				$where['share_privacy'] = array('in', array(0,1));
				break;
			case 1:
			default:
				$where['share_privacy'] = 0;
				break;
		}
		$goodslist = $model->table('sns_sharegoods,sns_goods')
						->on('sns_sharegoods.share_goodsid = sns_goods.snsgoods_goodsid')->join('inner')
						->where($where)->order('share_addtime desc')->limit(3)->select();
		if ($_SESSION['is_login'] == '1' && !empty($goodslist)){
			foreach ($goodslist as $k=>$v){
				if (!empty($v['snsgoods_likemember'])){
					$v['snsgoods_likemember_arr'] = explode(',',$v['snsgoods_likemember']);
					$v['snsgoods_havelike'] = in_array($_SESSION['member_id'],$v['snsgoods_likemember_arr'])?1:0;
				}
				$goodslist[$k] = $v;
			}
		}
		Tpl::output('goodslist', $goodslist);
		
		$pic_list = $model->table('sns_albumpic')->where(array('member_id'=>$this->master_id))->order('ap_id desc')->limit(3)->select();
		Tpl::output('pic_list', $pic_list);
		
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		$condition['limit']	= 1;
		switch ($this->relation){
			case 3:
				$condition['share_privacyin'] = "";
				break;
			case 2:
				$condition['share_privacyin'] = "0','1";
				break;
			case 1:
				$condition['share_privacyin'] = "0";
				break;
			default:
				$condition['share_privacyin'] = "0";
				break;
		}
		$sharestore_model = Model("sns_sharestore");
		$storelist = $sharestore_model->getShareStoreList($condition,'','*','detail');
		$storelist_new = array();
		if (!empty($storelist)){
			$store_model = Model('store');
			$storelist = $store_model->getStoreInfoBasic($storelist);
			$storeid_arr = '';
			foreach ($storelist as $k=>$v){
				$storelist_new[$v['store_id']] = $v;
			}
			$storeid_arr = array_keys($storelist_new);
			$storeid_str = implode("','",$storeid_arr);
			$goods_model = Model('goods');
			$goodslist = $goods_model->getCommenGoods(array('store_id_in'=>"$storeid_str"));
			if (!empty($goodslist)){
				foreach ($goodslist as $k=>$v){
					$v['goodsurl'] = ncUrl(array('act'=>'goods','goods_id'=>$v['goods_id']), 'goods');
					$storelist_new[$v['store_id']]['goods'][] = $v;
				}
			}
		}
		Tpl::output('storelist',$storelist_new);
		
		$where = array();
		$where['trace_memberid']	= $this->master_id;
		$where['trace_state']		= 0;
		switch ($this->relation){
			case 2:
				$where['trace_privacy']	= array('in',array(0,1));
				break;
			case 1:
			default:
				$where['trace_privacy']	= 0;
		}
		$tracelist = $model->table('sns_tracelog')->where($where)->order('trace_id desc')->limit(4)->select();
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
		Tpl::output('tracelist',$tracelist);
		
		Tpl::output('type','snshome');
		Tpl::output('menu_sign','snshome');
		Tpl::showpage('sns_home');
	}
	
	public function shareglistOp(){
		$page	= new Page();
		$page->setEachNum(20);
		$page->setStyle('admin');		
		$condition = array();
		$condition['share_memberid'] = $this->master_id;
		switch ($this->relation){
			case 3:
				$condition['share_privacyin'] = "";
				break;
			case 2:
				$condition['share_privacyin'] = "0','1";
				break;
			case 1:
				$condition['share_privacyin'] = "0";
				break;
			default:
				$condition['share_privacyin'] = "0";
				break;
		}
		if ($_GET['type'] == 'like'){
			$condition['share_islike'] = "1";
			$condition['order'] = " share_likeaddtime desc";
		}else {
			$condition['share_isshare'] = "1";
			$condition['order'] = " share_addtime desc";
		}
		$sharegoods_model = Model('sns_sharegoods');
		$goodslist = $sharegoods_model->getSharegoodsList($condition,$page,'*','detail');
		if($_GET['type'] != 'like' && !empty($goodslist)){
			$shareid_array = array();
			foreach($goodslist as $val){
				$shareid_array[]	= $val['share_id'];
			}
			$pic_array = Model()->table('sns_albumpic')->field('count(item_id) as count,item_id,ap_cover')->where(array('ap_type'=>1, 'item_id'=>array('in', $shareid_array)))->group('item_id')->select();
			if(!empty($pic_array)){
				$pic_list = array();
				foreach ($pic_array as $val){
					$val['ap_cover'] = SiteUrl.'/'.ATTACH_MALBUM.'/'.$this->master_id.'/'.$val['ap_cover'].'_max.'.get_image_type($val['ap_cover']);
					$pic_list[$val['item_id']]	= $val;
				}
				Tpl::output('pic_list', $pic_list);
			}
		}
		if ($_SESSION['is_login'] == '1' && !empty($goodslist)){
			foreach ($goodslist as $k=>$v){
				if (!empty($v['snsgoods_likemember'])){
					$v['snsgoods_likemember_arr'] = explode(',',$v['snsgoods_likemember']);
					$v['snsgoods_havelike'] = in_array($_SESSION['member_id'],$v['snsgoods_likemember_arr'])?1:0;
				}
				$goodslist[$k] = $v;
			}
		}
		Tpl::output('goodslist',$goodslist);
		Tpl::output('show_page',$page->show());
		Tpl::output('menu_sign','sharegoods');
		if ($_GET['type'] == 'like'){
			Tpl::showpage('sns_likegoodslist');
		}else {
			Tpl::showpage('sns_sharegoodslist');
		}
	}
	
	public function goodsinfoOp(){
		$share_id = intval($_GET['id']);
		if ($share_id <= 0){
			showDialog(Language::get('wrong_argument'),"index.php?act=member_snshome&mid={$this->master_id}",'error');
		}
		$sharegoods_model = Model('sns_sharegoods');
		$condition = array();
		$condition['share_id'] = "$share_id";
		$condition['share_memberid'] = "{$this->master_id}";
		$sharegoods_list = $sharegoods_model->getSharegoodsList($condition,'','','detail');
		unset($condition);
		if (empty($sharegoods_list)){
			showDialog(Language::get('wrong_argument'),"index.php?act=member_snshome&mid={$this->master_id}",'error');
		}
		$sharegoods_info = $sharegoods_list[0];
		if (!empty($sharegoods_info['snsgoods_goodsimage'])){
			$image_arr = explode('_small',$sharegoods_info['snsgoods_goodsimage']);
			$sharegoods_info['snsgoods_goodsimage'] = $image_arr[0];		
		}
		$sharegoods_info['snsgoods_goodsurl'] = ncUrl(array('act'=>'goods','goods_id'=>$sharegoods_info['snsgoods_goodsid']), 'goods');
		if ($_SESSION['is_login'] == '1'){
			if (!empty($sharegoods_info['snsgoods_likemember'])){
				$sharegoods_info['snsgoods_likemember_arr'] = explode(',',$sharegoods_info['snsgoods_likemember']);
				$sharegoods_info['snsgoods_havelike'] = in_array($_SESSION['member_id'],$sharegoods_info['snsgoods_likemember_arr'])?1:0;
			}
		}
		unset($sharegoods_list);
		
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		if ($_GET['type'] == 'like'){
			$condition['share_likeaddtime_gt'] = "{$sharegoods_info['share_likeaddtime']}";
			$condition['share_islike'] = "1";
			$condition['order'] = "share_likeaddtime asc";
		}else {
			$condition['share_addtime_gt'] = "{$sharegoods_info['share_addtime']}";
			$condition['share_isshare'] = "1";
			$condition['order'] = "share_addtime asc";
		}
		$condition['limit'] = "1";
		$sharegoods_list = $sharegoods_model->getSharegoodsList($condition);
		unset($condition);
		if (empty($sharegoods_list)){
			$sharegoods_info['snsgoods_isfirst'] = true;
		}else {
			$sharegoods_info['snsgoods_isfirst'] = false;
			$sharegoods_info['snsgoods_previd'] = $sharegoods_list[0]['share_id'];
		}
		unset($sharegoods_list);
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		if ($_GET['type'] == 'like'){
			$condition['share_likeaddtime_lt'] = "{$sharegoods_info['share_likeaddtime']}";
			$condition['share_islike'] = "1";
			$condition['order'] = "share_likeaddtime desc";
		}else {
			$condition['share_addtime_lt'] = "{$sharegoods_info['share_addtime']}";
			$condition['share_isshare'] = "1";
			$condition['order'] = "share_addtime desc";
		}
		$condition['limit'] = "1";
		
		$sharegoods_list = $sharegoods_model->getSharegoodsList($condition);
		unset($condition);
		if (empty($sharegoods_list)){
			$sharegoods_info['snsgoods_islast'] = true;
		}else {
			$sharegoods_info['snsgoods_islast'] = false;
			$sharegoods_info['snsgoods_nextid'] = $sharegoods_list[0]['share_id'];
		}
		unset($sharegoods_list);
		
		$model = Model();
		
		if ($_GET['type'] != 'like'){
			$pic_list = $model->table('sns_albumpic')->where(array('member_id'=>$this->master_id, 'ap_type'=>1, 'item_id'=>$share_id))->select();
			if(!empty($pic_list)) {
				foreach ($pic_list as $key=>$val){
					$pic_list[$key]['ap_cover']	= SiteUrl.'/'.ATTACH_MALBUM.'/'.$this->master_id.'/'.$val['ap_cover'].'_max.'.get_image_type($val['ap_cover']);
				}
				Tpl::output('pic_list', $pic_list);
			}
		}
		
		$where = array();
		$where['share_memberid']	= $this->master_id;
		$where['share_id']			= array('neq', $share_id);
		if ($_GET['type'] == 'like'){
			$where['share_islike']	= 1;
		}else{
			$where['share_isshare']	= 1;
		}
			
		$sharegoods_list = $model->table('sns_sharegoods,sns_goods')->join('inner')->on('sns_sharegoods.share_goodsid=sns_goods.snsgoods_goodsid')
							->where($where)->limit(9)->select();
		Tpl::output('sharegoods_list', $sharegoods_list);
		Tpl::output('sharegoods_info',$sharegoods_info);
		Tpl::output('menu_sign','sharegoods');
		Tpl::showpage('sns_goodsinfo');
	}
	
	public function commenttopOp(){
		$comment_model = Model('sns_comment');
		$condition = array();
		$condition['comment_originalid'] = "{$_GET['id']}";
		$condition['comment_originaltype'] = "{$_GET['type']}";
		$condition['comment_state'] = "0";
		$countnum = $comment_model->getCommentCount($condition);
		$condition['limit'] = "10";
		$commentlist = $comment_model->getCommentList($condition);
		$showmore = '0';
		if ($countnum > count($commentlist)){
			$showmore = '1';
		}
		Tpl::output('countnum',$countnum);
		Tpl::output('showmore',$showmore);
		Tpl::output('showtype',1);
		Tpl::output('tid',$_GET['id']);
		Tpl::output('type',$_GET['type']);
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		Tpl::output('commentlist',$commentlist);
		Tpl::showpage('sns_commentlist','null_layout');
	}
	
	public function commentlistOp(){
		$comment_model = Model('sns_comment');
		$condition = array();
		$condition['comment_originalid'] = "{$_GET['id']}";
		$condition['comment_originaltype'] = "{$_GET['type']}";
		$condition['comment_state'] = "0";
		$countnum = $comment_model->getCommentCount($condition);
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$commentlist = $comment_model->getCommentList($condition,$page);
		
		Tpl::output('countnum',$countnum);
		Tpl::output('tid',$_GET['id']);
		Tpl::output('type',$_GET['type']);
		Tpl::output('showtype','0');
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		Tpl::output('commentlist',$commentlist);
		Tpl::output('show_page',$page->show());
		Tpl::showpage('sns_commentlist','null_layout');
	}

	public function storelistOp(){
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');		
		$condition = array();
		$condition['share_memberid'] = "{$this->master_id}";
		switch ($this->relation){
			case 3:
				$condition['share_privacyin'] = "";
				break;
			case 2:
				$condition['share_privacyin'] = "0','1";
				break;
			case 1:
				$condition['share_privacyin'] = "0";
				break;
			default:
				$condition['share_privacyin'] = "0";
				break;
		}
		$sharestore_model = Model("sns_sharestore");
		$storelist = $sharestore_model->getShareStoreList($condition,$page,'*','detail');
		$storelist_new = array();
		if (!empty($storelist)){
			$store_model = Model('store');
			$storelist = $store_model->getStoreInfoBasic($storelist);
			$storeid_arr = '';
			foreach ($storelist as $k=>$v){
				$storelist_new[$v['store_id']] = $v;
			}			
			$storeid_arr = array_keys($storelist_new);
			$storeid_str = implode("','",$storeid_arr);
			$goods_model = Model('goods');
			$goodslist = $goods_model->getCommenGoods(array('store_id_in'=>"$storeid_str"));
			if (!empty($goodslist)){
				foreach ($goodslist as $k=>$v){
					$v['goodsurl'] = ncUrl(array('act'=>'goods','goods_id'=>$v['goods_id']), 'goods');
					$storelist_new[$v['store_id']]['goods'][] = $v;
				}
			}
		}
		Tpl::output('storelist',$storelist_new);
		Tpl::output('show_page',$page->show());
		Tpl::output('menu_sign','sharestore');
		Tpl::showpage('sns_storelist');
	}
	
	public function traceOp(){
		$this->get_visitor();	
		$this->sns_messageboard();	
		
		Tpl::output('menu_sign','snstrace');
		Tpl::showpage('sns_hometrace');
	}
	
	public function tracelistOp(){
		$tracelog_model = Model('sns_tracelog');
		$condition = array();
		$condition['trace_memberid'] = $this->master_id;
		switch ($this->relation){
			case 3:
				$condition['trace_privacyin'] = "";
				break;
			case 2:
				$condition['trace_privacyin'] = "0','1";
				break;
			case 1:
				$condition['trace_privacyin'] = "0";
				break;
			default:
				$condition['trace_privacyin'] = "0";
				break;
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
		Tpl::output('type','home');
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		Tpl::output('menu_sign', 'snstrace');
		Tpl::showpage('sns_tracelist','null_layout');
	}
	
	public function traceinfoOp(){
		$id = intval($_GET['id']);
		if ($id<=0){
			showDialog(Language::get('wrong_argument'),'','error');
		}
		$tracelog_model = Model('sns_tracelog');
		$condition = array();
		$condition['trace_id'] = "$id";
		$condition['trace_memberid'] = "{$this->master_id}";
		switch ($this->relation){
			case 3:
				$condition['trace_privacyin'] = "";
				break;
			case 2:
				$condition['trace_privacyin'] = "0','1";
				break;
			case 1:
				$condition['trace_privacyin'] = "0";
				break;
			default:
				$condition['trace_privacyin'] = "0";
				break;
		}
		$condition['trace_state'] = "0";
		$tracelist = $tracelog_model->getTracelogList($condition);
		$traceinfo = array();
		if (!empty($tracelist)){
			$traceinfo = $tracelist[0];
			if ($traceinfo['trace_title']){
				$traceinfo['trace_title'] = str_replace("%siteurl%", SiteUrl.DS, $traceinfo['trace_title']);
				$traceinfo['trace_title_forward'] = '|| @'.$traceinfo['trace_membername'].':'.preg_replace("/<a(.*?)href=\"(.*?)\"(.*?)>@(.*?)<\/a>([\s|:]|$)/is",'@${4}${5}',$traceinfo['trace_title']);
			}
			if(!empty($traceinfo['trace_content'])){
				$traceinfo['trace_content'] = str_replace("%siteurl%", SiteUrl.DS, $traceinfo['trace_content']);
			}
		}
		Tpl::output('traceinfo',$traceinfo);
		Tpl::output('menu_sign','snshome');
		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));
		Tpl::showpage('sns_traceinfo');
	}
	
	public function add_shareOp(){
		$sid = intval($_GET['sid']);
		$model = Model();
		if($sid > 0){
			$where = array();
			$where['member_id']	= $_SESSION['member_id'];
			$where['ap_type']	= 1;
			$where['item_id']	= $sid;
			$pic_list = $model->table('sns_albumpic')->where($where)->select();
			if(!empty($pic_list)) {
				foreach ($pic_list as $key=>$val){
					$pic_list[$key]['ap_cover']	= SiteUrl.'/'.ATTACH_MALBUM.'/'.$_SESSION['member_id'].'/'.$val['ap_cover'].'_240x240.'.get_image_type($val['ap_cover']);
				}
				Tpl::output('pic_list', $pic_list);
			}
		}
		$sharegoods_info = $model->table('sns_goods')->find(intval($_GET['gid']));
		Tpl::output('sharegoods_info', $sharegoods_info);
		Tpl::output('sid', $sid);
		Tpl::showpage('sns_addshare', 'null_layout');
	}
	
	public function image_uploadOp(){
		$ap_id = intval($_POST['apid']);
		
		$model = Model();
		$default_class = $model->table('sns_albumclass')->where(array('member_id'=>$_SESSION['member_id'], 'is_default'=>1))->find();
		if(empty($default_class)){	
			$default_class = array();
			$default_class['ac_name']		= Language::get('sns_buyershow');
			$default_class['member_id']		= $this->master_id;
			$default_class['ac_des']		= Language::get('sns_buyershow_album_des');
			$default_class['ac_sort']		= '255';
			$default_class['is_default']	= 1;
			$default_class['upload_time']	= time();
			$default_class['ac_id']			= $model->table('sns_albumclass')->insert($default_class);
		}
		
		$count = $model->table('sns_albumpic')->where(array('member_id'=>$_SESSION['member_id']))->count();
		if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
			$output	= array();
			$output['error']	= Language::get('sns_upload_img_max_num_error');
			$output = json_encode($output);
			echo $output;die;
		}
		
		
		$upload = new UploadFile();
		if($ap_id > 0){
			$pic_info = $model->table('sns_albumpic')->find($ap_id);
			if(!empty($pic_info)) $upload->set('file_name',$pic_info['ap_cover']);		
		}
		$upload_dir = ATTACH_MALBUM.DS.$_SESSION['member_id'].DS;
		
		$upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
		$thumb_width	= '240,1024';
		$thumb_height	= '2048,1024';
		$upload->set('max_size',C('image_max_filesize'));
		$upload->set('thumb_width',	$thumb_width);
		$upload->set('thumb_height',$thumb_height);
		
		$upload->set('fprefix',$_SESSION['member_id']);
		$upload->set('thumb_ext',	'_240x240,_max');
		$result = $upload->upfile(trim($_POST['id']));
		if (!$result){
			if (strtoupper(CHARSET) == 'GBK'){
				$upload->error = Language::getUTF8($upload->error);
			}
			$output	= array();
			$output['error']	= $upload->error;
			$output = json_encode($output);
			echo $output;die;
		}
			
		
		if($ap_id <= 0){		
			$img_path 		= $upload->getSysSetPath().$upload->file_name;
			list($width, $height, $type, $attr) = getimagesize(BasePath.DS.ATTACH_MALBUM.DS.$_SESSION['member_id'].DS.$img_path);

	
			$image = explode('.', $_FILES[trim($_POST['id'])]["name"]);
	
	
			if(strtoupper(CHARSET) == 'GBK'){
				$image['0'] = Language::getGBK($image['0']);
			}
			$insert = array();
			$insert['ap_name']		= $image['0'];
			$insert['ac_id']		= $default_class['ac_id'];
			$insert['ap_cover']		= $img_path;
			$insert['ap_size']		= intval($_FILES[trim($_POST['id'])]['size']);
			$insert['ap_spec']		= $width.'x'.$height;
			$insert['upload_time']	= time();
			$insert['member_id']	= $_SESSION['member_id'];
			$insert['ap_type']		= 1;
			$insert['item_id']		= intval($_POST['sid']);
			$result = $model->table('sns_albumpic')->insert($insert);
		}
		$data = array();
		$data['file_name']	= $ap_id > 0?$pic_info['ap_cover']:$upload->getSysSetPath().$upload->thumb_image;
		$data['file_id']	= $ap_id > 0?$pic_info['ap_id']:$result;
		
		
		$output = json_encode($data);
		echo  $output;die;
	}
	
	public function del_sharepicOp(){
		$ap_id = intval($_GET['apid']);
		$data = array();
		if($ap_id > 0){
			Model()->table('sns_albumpic')->where(array('ap_id'=>$ap_id, 'member_id'=>$_SESSION['member_id']))->delete();
			$data['type']	= 'true';
		}else{
			$data['type']	= 'false';
		}
		
		$output = json_encode($data);
		echo  $output;die;
	}
	
	private function sns_messageboard(){
		$model = Model();
		$where = array();
		$where['from_member_id']	= array('neq',0);
		$where['to_member_id']		= $this->master_id;
		$where['message_state']		= array('neq',2);
		$where['message_parent_id']	= 0;
		$where['message_type']		= 2;
		$message_list = $model->table('message')->where($where)->order('message_id desc')->limit(10)->select();
		if(!empty($message_list)){
			$pmsg_id = array();
			foreach ($message_list as $key=>$val){
				$pmsg_id[]	= $val['message_id'];
				$message_list[$key]['message_time'] = $this->formatDate($val['message_time']);
			}
			$where = array();
			$where['message_parent_id'] = array('in',$pmsg_id);
			$rmessage_array = $model->table('message')->where($where)->select();
			$rmessage_list	= array();
			if(!empty($rmessage_array)){
				foreach ($rmessage_array as $key=>$val){
					$val['message_time'] = $this->formatDate($val['message_time']);
					$rmessage_list[$val['message_parent_id']][] = $val;
				}
				foreach ($rmessage_list as $key=>$val){
					$rmessage_list[$key]	 = array_slice($val, -3, 3);
				}
			}
			Tpl::output('rmessage_list', $rmessage_list);
		}
		Tpl::output('message_list', $message_list);
	}
}
