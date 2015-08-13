<?php



defined('haipinlegou') or exit('Access Invalid!');





class BaseHomeControl{

	

	public function __construct(){


		$this->checkMessage();

		

		$this->queryCart();

	

		Language::read('common,home_layout');

		

		Tpl::setDir('home');

	

		Tpl::output('nav_list',($nav = F('nav'))? $nav :H('nav',true,'file'));



		

		Tpl::setLayout('new_home_layout');



		Tpl::output('hot_search',@explode(',',C('hot_search')));

		

		Tpl::output('show_goods_class',($nav = F('goods_class'))? $nav :H('goods_class',true,'file'));

        $model_tpl = Model("tpl_position");
        $menu = $model_tpl->getMenu([
            "left_index"=>"head",
            "position_id_isset"=>"1",
            "block_is_show"=>"1",
            "is_show"=>"1"
        ]);
        Tpl::output('head_menu',$menu['head-menu']);

		if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){

			$_GET = Language::getGBK($_GET);

		}
//var_dump(cookie(md5('auto')));die;
        $auto = cookie(md5('auto'));
        //检查自动登录
        if($auto) {
            $member_model = Model('member');
            $member_info  = $member_model->infoMember(array('member_name'=>$auto));
            if($member_info) {
                $_SESSION['is_login']	= '1';

                //$_SESSION['is_seller']	= intval($member_info['store_id']) == 0 ? '' : $member_info['store_id'];

                $_SESSION['member_id']	= $member_info['member_id'];

                $_SESSION['member_name']= $member_info['member_name'];

                $_SESSION['member_email']= $member_info['member_email'];

                $_SESSION['category'] = $member_info['category'];



                if ($GLOBALS['setting_config']['qq_isuse'] == 1 && trim($member_info['member_qqopenid'])){

                    $_SESSION['openid']		= $member_info['member_qqopenid'];

                }

                if ($GLOBALS['setting_config']['sina_isuse'] == 1 && trim($member_info['member_sinaopenid'])){

                    $_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];

                }

                //查询店铺信息

                if ($member_info['store_id'] > 0){

                    $store_model = Model('store');

                    $store_info = $store_model->shopStore(array('store_id'=>$member_info['store_id']));

                    if (is_array($store_info) && count($store_info)>0){

                        $_SESSION['store_id']	= $store_info['store_id'];

                        $_SESSION['store_name']	= $store_info['store_name'];

                        $_SESSION['grade_id']	= $store_info['grade_id'];

                    }

                }
            }
        }
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}

		$model_article	= Model('article');
		$hp_article=array();
		$index_article=$model_article->getArticleList($hp_article);
		Tpl::output('index_article',$index_article);
		
		
		if(!C('site_status')) halt(C('closed_reason'));

	}

	

	public function send_notice($receiver_id,$tpl_code,$param,$flag = true){

		

		$mail_tpl_model	= Model('mail_templates');

		$mail_tpl	= $mail_tpl_model->getOneTemplates($tpl_code);
                
               

		if(empty($mail_tpl) || $mail_tpl['mail_switch'] == 0)return false;

		

		$member_model	= Model('member');

		$receiver	= $member_model->infoMember(array('member_id'=>$receiver_id));
             
		if(empty($receiver))return false;



		

		$subject	= ncReplaceText($mail_tpl['title'],$param);

		$message	= ncReplaceText($mail_tpl['content'],$param);

		

		$result	= false;
              
		switch($mail_tpl['type']){

			case '0':

//				$email	= new Email();
//
//				$result	= true;
//
//				if($flag and $GLOBALS['setting_config']['email_enabled'] == '1' or $flag == false){
//
//					$result	= $email->send_sys_email($receiver['member_email'],$subject,$message);
//
//				}
                import('libraries.mail');
                $result=Mail::sendMess(['address'=>$receiver['member_email'],'content'=>$message,'subject'=>$subject]);
				break;

			case '1':

				$model_message = Model('message');

				$param = array(

				'member_id'=>$receiver_id,

				'to_member_name'=>$receiver['member_name'],

				'msg_content'=>$message,

				'message_type'=>1//��ʾϵͳ��Ϣ

				);

				$result = $model_message->saveMessage($param);

				break;

		}

		return $result;

	}

	

	private function checkMessage() {

		if($_SESSION['member_id'] == '') return ;

		$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

		if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){

			$countnum = intval(cookie($cookie_name));

		}else {

			$message_model = Model('message');

			$countnum = $message_model->countNewMessage($_SESSION['member_id']);

			setNcCookie($cookie_name,"$countnum",2*3600);

		}

		Tpl::output('message_num',$countnum);

	}

	

	/**

	 *  ��ѯ���ﳵ��Ʒ����

	 *

	 * @param 

	 * @return 

	 */

	private function queryCart() {

		if (cookie('goodsnum')){

			$goodsnum = intval(cookie('goodsnum'));

		}else {

			if (cookie('cart') != ''){

				$cart_str = cookie('cart');

				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);//ȥ��б��

				$cookie_goods = unserialize($cart_str);

				$goodsnum = count($cookie_goods);

			}elseif ($_SESSION['member_id'] != ''){

				$goodsnum = Model()->table('cart')->where(array('member_id'=>$_SESSION['member_id']))->count();

			}else{

				$goodsnum = 0;

			}

		}

		setNcCookie('goodsnum',$goodsnum,2*3600);//����1��

		Tpl::output('goods_num',$goodsnum);

	}

}

class BaseCarmanageStoreControl extends BaseHomeControl{
    public $store_info;

	

	public function __construct(){

		parent::__construct();

		

        $model_article	= Model('article');
		$hp_article=array();
		$index_article=$model_article->getArticleList($hp_article);
		Tpl::output('index_article',$index_article);

	

		
        Language::read('common,member_layout');
		Language::read('member_store_index');

        //���������������������������

		Tpl::output('hot_search',@explode(',',C('hot_search')));



		Tpl::setLayout('carmanage_store_layout');
    }
}



class BaseMemberControl{

	public function __construct(){



		if($GLOBALS['setting_config']['site_status'] == '0') {

			showMessage($GLOBALS['setting_config']['closed_reason']);

			exit();

		}

		

		Language::read('common,member_layout');

		

		if ($_GET['column'] && strtoupper(CHARSET) == 'GBK'){

			$_GET = Language::getGBK($_GET);

		}

		$model_article	= Model('article');
		$hp_article=array();
		$index_article=$model_article->getArticleList($hp_article);
		Tpl::output('index_article',$index_article);

		$this->checkLogin();

			

		$this->checkMessage();

		

		$this->queryCart();

		

		Tpl::setDir('member');

		

		Tpl::setLayout('member_layout');

		Tpl::output('header_menu_sign','setting');

		Tpl::output('nav_list',($nav = F('nav')) ? $nav : H('nav',true,'file'));

			

		if(empty($_SESSION['order_update_time'])) $this->updateOrder();

	}

	

	private function updateOrder($update_all = 0){

		if (empty($_SESSION['order_update_time']) || $update_all == 1) {

			$model_trade	= Model('trade');

			$model_trade->updateOrderPay($_SESSION['member_id']);

			$model_trade->updateRefund($_SESSION['member_id']);

			$_SESSION['order_update_time'] = time();

		}

	}

	

	public function send_notice($receiver_id,$tpl_code,$param,$flag = true){


        import('libraries.mail');
		$mail_tpl_model	= Model('mail_templates');

		$mail_tpl	= $mail_tpl_model->getOneTemplates($tpl_code);

		if(empty($mail_tpl) || $mail_tpl['mail_switch'] == 0)return false;

		

		

		$member_model	= Model('member');

		$receiver	= $member_model->infoMember(array('member_id'=>$receiver_id));

		if(empty($receiver))return false;

		

	

		$subject	= ncReplaceText($mail_tpl['title'],$param);

		$message	= ncReplaceText($mail_tpl['content'],$param);

		

		$result	= false;

		switch($mail_tpl['type']){

			case '0':

				$email	= new Email();

				$result	= true;

				if($flag and $GLOBALS['setting_config']['email_enabled'] == '1' or $flag == false){

					$result	= Mail::sendMess([
                        'address'=>$receiver['member_email'],
                        'subject'=>$subject,
                        'content'=>$message,
                        ]);

				}

				break;

			case '1':

				$model_message = Model('message');

				$param = array(

					'member_id'=>$receiver_id,

					'to_member_name'=>$receiver['member_name'],

					'msg_content'=>$message,

					'message_type'=>1//��ʾϵͳ��Ϣ

				);

				$result = $model_message->saveMessage($param);

				break;

		}

		return $result;

	}

	

	private function checkLogin(){

		if ($_SESSION['is_login'] !== '1'){

			if (trim($_GET['op']) == 'favoritesgoods' || trim($_GET['op']) == 'favoritesstore'){

				$lang = Language::getLangContent('UTF-8');

				echo json_encode(array('done'=>false,'msg'=>$lang['no_login']));

				die;

			}

			$ref_url = request_uri();

			if ($_GET['inajax']){

				showDialog('','','js',"login_dialog();",200);

			}else {

				@header("location: index.php?act=login&ref_url=".urlencode($ref_url));

			}

			exit;

		}

	}

	

	private function checkMessage() {

		if($_SESSION['member_id'] == '') return ;

		$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

		if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){

			$countnum = intval(cookie($cookie_name));

		}else {

			$message_model = Model('message');

			$countnum = $message_model->countNewMessage($_SESSION['member_id']);

			setNcCookie($cookie_name,"$countnum",2*3600);

		}

		Tpl::output('message_num',$countnum);

	}

	

	public function checknameinner() {

		

		$model_store	= Model('store');



		$store_name	= trim($_GET['store_name']);

		$store_info	= $model_store->shopStore(array('store_name'=>$store_name));

		if($store_info['store_name'] != ''&&$store_info['member_id'] != $_SESSION['member_id']) {			

			return false;

		} else {			

			return true;

		}

	}

	

	public function get_member_info() {		



		$hash_key = $_SESSION['member_id'];

		$cachekey_arr = array('member_name','store_id','member_avatar','member_qq','member_email','member_ww','member_goldnum','member_points',

							'available_predeposit','member_snsvisitnum','credit_arr','order_nopay','order_noreceiving','order_noeval','fan_count');

		

		if (false){

			foreach ($_cache as $k=>$v){

				$member_info[$k] = $v;

			}

		} else {

			$model = Model('my_order');

			$member_info = $model->table('member')->where(array('member_id'=>$_SESSION['member_id']))->find();

			$member_info['credit_arr'] = getCreditArr(intval($member_info['member_credit']));

			$member_info['order_nopay'] = $model->myOrderCount(array('buyer_id'=>"{$_SESSION['member_id']}",'order_state' => 'order_pay'));

			$member_info['order_noreceiving'] = $model->myOrderCount(array('buyer_id'=>"{$_SESSION['member_id']}",'order_state' => 'order_shipping'));

			$member_info['order_noeval'] = $model->myOrderCount(array('buyer_id'=>"{$_SESSION['member_id']}",'order_evalbuyer_able' => '1'));

			

			wcache($hash_key,$member_info,'member');

		}

		Tpl::output('member_info',$member_info);

		Tpl::output('header_menu_sign','snsindex');

	}

	

	private function queryCart() {

		if (cookie('goodsnum') != null && intval(cookie('goodsnum')) >=0){

			$goodsnum = intval(cookie('goodsnum'));

		}else {

			if (cookie('cart') != ''){

				$cart_str = cookie('cart');

				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);

				$cookie_goods = unserialize($cart_str);

				$goodsnum = count($cookie_goods);

			}elseif ($_SESSION['member_id'] != ''){

				$goodsnum = Model()->table('cart')->where(array('member_id'=>$_SESSION['member_id']))->count();

			}else{

				$goodsnum = 0;

			}

		}

		setNcCookie('goodsnum',$goodsnum,2*3600);

		Tpl::output('goods_num',$goodsnum);

	}

}





class BaseMemberStoreControl extends BaseMemberControl{

	

	public $store_info;

	

	public function __construct(){

		parent::__construct();

		if (!$_SESSION['store_id']){

			@header("Location: index.php?act=member_snsindex&op=nostoreindex");

			exit;

		}

        $model_article	= Model('article');
		$hp_article=array();
		$index_article=$model_article->getArticleList($hp_article);
		Tpl::output('index_article',$index_article);

		$model_store	= Model('store');

		$this->store_info = $store_info	= $model_store->shopStore(array('store_id'=>$_SESSION['store_id']));

		Tpl::output('store_info', $this->store_info);

		if ($store_info['store_center_quicklink'] != ''){

			$quick_link = @unserialize($store_info['store_center_quicklink']);

			Tpl::output('quick_link',$quick_link);

		}

		

		Language::read('member_store_index');

        //���������������������������

		Tpl::output('hot_search',@explode(',',C('hot_search')));



		Tpl::setLayout('member_store_layout');

	}

	

	

	public function storeAutoShare($data, $type){

		$param = array(3=>'new',4=>'coupon',5=>'xianshi',6=>'mansong',7=>'bundling',8=>'groupbuy');

		$param_flip = array_flip($param);

		if(!in_array($type, $param) || empty($data)){

			return false;

		}

		

		$model = Model();

		$auto_setting = $model->table('sns_s_autosetting')->find($_SESSION['store_id']);

		$auto_sign = false; 



		if($auto_setting['sauto_'.$type] == 1){

			$auto_sign = true;

			if( CHARSET == 'GBK') {

				foreach ((array)$data as $k=>$v){

					$data[$k] = Language::getUTF8($v);

				}

			}

			$goodsdata = addslashes(json_encode($data));

			if ($auto_setting['sauto_'.$type.'title'] != ''){

				$title = $auto_setting['sauto_'.$type.'title'];

			}else{

				$auto_title = 'nc_store_auto_share_'.$type.rand(1, 5);

				$title = Language::get($auto_title);

			}

		}

		if($auto_sign){

			$stracelog_array = array();

			$stracelog_array['strace_storeid']	= $this->store_info['store_id'];

			$stracelog_array['strace_storename']= $this->store_info['store_name'];

			$stracelog_array['strace_storelogo']= empty($this->store_info['store_logo'])?'':$this->store_info['store_logo'];

			$stracelog_array['strace_title']	= $title;

			$stracelog_array['strace_content']	= '';

			$stracelog_array['strace_time']		= time();

			$stracelog_array['strace_type']		= $param_flip[$type];

			$stracelog_array['strace_goodsdata']= $goodsdata;

			$model->table('sns_s_tracelog')->insert($stracelog_array);

			return true;

		}else{

			return false;

		}

	}

}





class BaseSNSControl {

	protected $relation = 0;

	protected $master_id = 0; 

	const MAX_RECORDNUM = 20;

	protected $member_info;

	

	public function __construct(){

		

		Tpl::setDir('sns');

	

		Tpl::setLayout('sns_layout');

		

		Language::read('common,sns_layout');

		

		Tpl::output('nav_list',($nav = F('nav'))? $nav :H('nav',true,'file'));	

		

		$this->checkMessage();

		

		$this->queryCart();



		

		$this->check_relation();

		

		

		$this->member_info = $this->get_member_info();

		

		

		$this->add_visit();

		

		

		$this->my_attention();

		

		

		$this->get_setting();

		

		Tpl::output('max_recordnum',self::MAX_RECORDNUM);

	}



	private function checkMessage() {

		if($_SESSION['member_id'] == '') return ;

		$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

		if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){

			$countnum = intval(cookie($cookie_name));

		}else {

			$message_model = Model('message');

			$countnum = $message_model->countNewMessage($_SESSION['member_id']);

			setNcCookie($cookie_name,"$countnum",2*3600);

		}

		Tpl::output('message_num',$countnum);

	}

	

	protected function formatDate($time){

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

	

	public function get_member_info() {

		$hash_key  = $this->master_id;

		if($hash_key <= 0){

			showMessage('�������', '', '', 'error');

		}

		$cachekey_arr = array('member_name','store_id','member_avatar','member_qq','member_email','member_ww','member_goldnum','member_points',

				'available_predeposit','member_snsvisitnum','credit_arr','fan_count','attention_count');

		if ($_cache = rcache($hash_key,'sns_member')){

			foreach ($_cache as $k=>$v){

				$member_info[$k] = $v;

			}

		} else {

			$model = Model();

			$member_info = $model->table('member')->where(array('member_id'=>$this->master_id))->find();

			$member_info['credit_arr'] = getCreditArr(intval($member_info['member_credit']));

			$fan_count = $model->table('sns_friend')->where(array('friend_tomid'=>$this->master_id))->count();

			$member_info['fan_count'] = $fan_count;

			$attention_count = $model->table('sns_friend')->where(array('friend_frommid'=>$this->master_id))->count();

			$member_info['attention_count'] = $attention_count;

			$mtag_list = $model->table('sns_membertag,sns_mtagmember')->field('mtag_name')->on('sns_membertag.mtag_id = sns_mtagmember.mtag_id')->join('inner')->where(array('sns_mtagmember.member_id'=>$this->master_id))->select();

			$tagname_array = array();

			if(!empty($mtag_list)){

				foreach ($mtag_list as $val){

					$tagname_array[] = $val['mtag_name'];

				}

			}

			$member_info['tagname'] = $tagname_array;

			

			wcache($hash_key,$member_info,'sns_member');

		}

		Tpl::output('member_info',$member_info);

		return $member_info;

	}



	protected function get_visitor(){

		$model = Model();

		$visitme_list = $model->table('sns_visitor')->where(array('v_ownermid'=>$this->master_id))->limit(9)->order('v_addtime desc')->select();

		if (!empty($visitme_list)){

			foreach ($visitme_list as $k=>$v){

				$v['adddate_text'] = $this->formatDate($v['v_addtime']);

				$v['addtime_text'] = @date('H:i',$v['v_addtime']);

				$visitme_list[$k] = $v;

			}

		}

		Tpl::output('visitme_list',$visitme_list);

		if($this->relation == 3){	

			$visitother_list = $model->table('sns_visitor')->where(array('v_mid'=>$this->master_id))->limit(9)->order('v_addtime desc')->select();

			if (!empty($visitother_list)){

				foreach ($visitother_list as $k=>$v){

					$v['adddate_text'] = $this->formatDate($v['v_addtime']);

					$visitother_list[$k] = $v;

				}

			}

			Tpl::output('visitother_list',$visitother_list);

		}

	}

	

	private function check_relation(){

		$model = Model();

		$this->master_id = intval($_GET['mid']);

		if ($this->master_id <= 0){

			if ($_SESSION['is_login'] == 1){

				$this->master_id = $_SESSION['member_id'];

			}else {

				@header("location: index.php?act=login&ref_url=".urlencode('index.php?act=member_snshome'));

			}

		}

		Tpl::output('master_id', $this->master_id);

		

		$this->member_info = $this->get_member_info();

		

		$model = Model();

		

		if ($_SESSION['is_login'] == '1'){

			if ($this->master_id == $_SESSION['member_id']){

				$this->relation = 3;

			}else{

				$this->relation = 1;

				$friend_arr = $model->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id'],'friend_tomid'=>$this->master_id))->find();

				if (!empty($friend_arr) && $friend_arr['friend_followstate'] == 2){

					$this->relation = 2;

				}elseif($friend_arr['friend_followstate'] == 1){

					$this->relation = 4;

				}

			}

		}

		Tpl::output('relation',$this->relation);

	}

	

	private function add_visit(){

		$model = Model();

		if ($_SESSION['is_login'] == '1' && $this->relation != 3){

			$visitor_info = $model->table('member')->find($_SESSION['member_id']);

			if (!empty($visitor_info)){

				$existevisitor_info = $model->table('sns_visitor')->where(array('v_ownermid'=>$this->master_id, 'v_mid'=>$visitor_info['member_id']))->find();

				if (!empty($existevisitor_info)){

					$update_arr = array();

					$update_arr['v_addtime'] = time();

					$model->table('sns_visitor')->update(array('v_id'=>$existevisitor_info['v_id'], 'v_addtime'=>time()));

				}else {

					$insert_arr = array();

					$insert_arr['v_mid']			= $visitor_info['member_id'];

					$insert_arr['v_mname']			= $visitor_info['member_name'];

					$insert_arr['v_mavatar']		= $visitor_info['member_avatar'];

					$insert_arr['v_ownermid']		= $this->member_info['member_id'];

					$insert_arr['v_ownermname']		= $this->member_info['member_name'];

					$insert_arr['v_ownermavatar']	= $this->member_info['member_avatar'];

					$insert_arr['v_addtime']		= time();

					$model->table('sns_visitor')->insert($insert_arr);

				}

			}

		}

		

		$cookie_str = cookie('visitor');

		$cookie_arr = array();

		$is_increase = false;

		if (empty($cookie_str)){

			$is_increase = true;

		}else{

			$cookie_arr = explode('_',$cookie_str);

			if(!in_array($this->master_id,$cookie_arr)){

				$is_increase = true;

			}

		}

		if ($is_increase == true){

			$model->table('member')->update(array('member_id'=>$this->master_id, 'member_snsvisitnum'=>array('exp', 'member_snsvisitnum+1')));

			$cookie_arr[] = $this->master_id;

			setNcCookie('visitor',implode('_',$cookie_arr),24*3600);

		}

	}

	

	private function my_attention(){

		if(intval($_SESSION['member_id']) >0){

			$my_attention = Model()->table('sns_friend')->where(array('friend_frommid'=>$_SESSION['member_id']))->order('friend_addtime desc')->limit(4)->select();

			Tpl::output('my_attention', $my_attention);

		}

	}

	

	private function get_setting(){

		$m_setting = Model()->table('sns_setting')->find($this->master_id);

		Tpl::output('skin_style', (!empty($m_setting['setting_skin'])?$m_setting['setting_skin']:'skin_01'));

	}

	

	private function queryCart() {

		if (cookie('goodsnum') != null && intval(cookie('goodsnum')) >=0){

			$goodsnum = intval(cookie('goodsnum'));

		}else {

			if (cookie('cart') != ''){

				$cart_str = cookie('cart');

				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);

				$cookie_goods = unserialize($cart_str);

				$goodsnum = count($cookie_goods);

			}elseif ($_SESSION['member_id'] != ''){

				$goodsnum = Model()->table('cart')->where(array('member_id'=>$_SESSION['member_id']))->count();

			}else{

				$goodsnum = 0;

			}

		}

		setNcCookie('goodsnum',$goodsnum,2*3600);

		Tpl::output('goods_num',$goodsnum);

	}

}





class BaseStoreControl{

	

	public function __construct(){

		

		Language::read('common,store_layout');
		
		Language::read('common,home_layout');

		

		if(C('site_status') == '0') {

			showMessage(C('closed_reason'));

			exit();

		}

		

		Tpl::setDir('store');

		

		Tpl::output('nav_list',($g = F('nav')) ? $g : H('nav',true,'file'));

		Tpl::output('show_goods_class',($nav = F('goods_class'))? $nav :H('goods_class',true,'file'));

		Tpl::setLayout('store_layout');

		

		$this->checkMessage();

		

		$this->queryCart();
		
		$model_article	= Model('article');
		$hp_article=array();
		$index_article=$model_article->getArticleList($hp_article);
		Tpl::output('index_article',$index_article);

        $model_tpl = Model("tpl_position");
        $menu = $model_tpl->getMenu([
            "left_index"=>"head",
            "position_id_isset"=>"1",
            "block_is_show"=>"1",
            "is_show"=>"1"
        ]);
        Tpl::output('head_menu',$menu['head-menu']);


	}

	

	public function send_notice($receiver_id,$tpl_code,$param,$flag = true){

		

		$mail_tpl_model	= Model('mail_templates');

		$mail_tpl	= $mail_tpl_model->getOneTemplates($tpl_code);

		if(empty($mail_tpl) || $mail_tpl['mail_switch'] == 0)return false;

		

		

		$member_model	= Model('member');

		$receiver	= $member_model->infoMember(array('member_id'=>$receiver_id));

		if(empty($receiver))return false;

		

		

		$subject	= ncReplaceText($mail_tpl['title'],$param);

		$message	= ncReplaceText($mail_tpl['content'],$param);

		

		$result	= false;

		switch($mail_tpl['type']){

			case '0':

				$email	= new Email();

				$result	= true;

				if($flag and $GLOBALS['setting_config']['email_enabled'] == '1' or $flag == false){

					$result	= $email->send_sys_email($receiver['member_email'],$subject,$message);

				}

				break;

			case '1':

				$model_message = Model('message');

				$param = array(

					'member_id'=>$receiver_id,

					'to_member_name'=>$receiver['member_name'],

					'msg_content'=>$message,

					'message_type'=>1//��ʾϵͳ��Ϣ

				);				

				$result = $model_message->saveMessage($param);

				break;

		}

		return $result;

	}

	

	private function checkMessage() {

		if($_SESSION['member_id'] == '') return ;

		$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

		if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){

			$countnum = intval(cookie($cookie_name));

		}else {

			$message_model = Model('message');

			$countnum = $message_model->countNewMessage($_SESSION['member_id']);

			setNcCookie($cookie_name,"$countnum",2*3600);//2Сʱ

		}

		Tpl::output('message_num',$countnum);

	}

	

	protected function getStoreInfo($store_id){

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



	

	public function show_storeeval($store_id){

		if ($store_id<=0){

			return array();

		}

		$evaluate_model = Model("evaluate");

		$storestat_list = $evaluate_model->getOneStoreEvalStat($store_id);

		for ($i=1;$i<4;$i++){

			$storestat_list[$i]['evalstat_fivenum_rate'] = @round($storestat_list[$i]['evalstat_fivenum']/$storestat_list[$i]['evalstat_timesnum']*100,2);

			$storestat_list[$i]['evalstat_fournum_rate'] = @round($storestat_list[$i]['evalstat_fournum']/$storestat_list[$i]['evalstat_timesnum']*100,2);

			$storestat_list[$i]['evalstat_threenum_rate'] = @round($storestat_list[$i]['evalstat_threenum']/$storestat_list[$i]['evalstat_timesnum']*100,2);

			$storestat_list[$i]['evalstat_twonum_rate'] = @round($storestat_list[$i]['evalstat_twonum']/$storestat_list[$i]['evalstat_timesnum']*100,2);

			$storestat_list[$i]['evalstat_onenum_rate'] = @round($storestat_list[$i]['evalstat_onenum']/$storestat_list[$i]['evalstat_timesnum']*100,2);

			$storestat_list[$i]['evalstat_average'] = $storestat_list[$i]['evalstat_average']>0?$storestat_list[$i]['evalstat_average']:0;

		}

		Tpl::output('storestat_list',$storestat_list);

	}

	

	private function queryCart() {

		if (cookie('goodsnum') != null && intval(cookie('goodsnum')) >=0){

			$goodsnum = intval(cookie('goodsnum'));

		}else {

			if (cookie('cart') != ''){

				$cart_str = cookie('cart');

				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);

				$cookie_goods = unserialize($cart_str);

				$goodsnum = count($cookie_goods);

			}elseif ($_SESSION['member_id'] != ''){

				$goodsnum = Model()->table('cart')->where(array('member_id'=>$_SESSION['member_id']))->count();

			}else{

				$goodsnum = 0;

			}

		}

		setNcCookie('goodsnum',$goodsnum,2*3600);

		Tpl::output('goods_num',$goodsnum);

	}

}

