<?php



defined('haipinlegou') or exit('Access Invalid!');



class carmanageControl extends BaseCarmanageStoreControl {



	public function __construct(){
    
		parent::__construct();
        
    }
    public function indexOp(){
        if (!$_SESSION['cmservice']){

			@header("Location: index.php?act=carmanage&op=login");

			exit;

		}
        Language::read('member_home_index');
		$lang	= Language::getLangContent();	
        $_SESSION['store_id']=20;
	
		
		$model_article	= Model('article');
		$condition	= array();
		$condition['article_show'] = '1';
		$condition['ac_id'] = '1';
		$condition['order'] = 'article_sort desc,article_time desc';
		$condition['limit'] = '5';
		$show_article	= $model_article->getArticleList($condition);
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		Tpl::output('show_article',$show_article);
		$phone_array = explode(',',C('site_phone'));
		Tpl::output('phone_array',$phone_array);

		Tpl::output('menu_sign','index');
		Tpl::showpage('carhome');
        
    }
   

//汽配会员登陆

	public function loginOp(){
	   
        Language::read("home_login_index");
        $lang	= Language::getLangContent();
        
	    $model_member	= Model('carmanage');

	

		$model_member->checkloginMember();

		

		if (chksubmit()){


			Security::checkToken();



			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["user_name"],		"require"=>"true", "message"=>$lang['login_index_username_isnull']),

			array("input"=>$_POST["password"],		"require"=>"true", "message"=>$lang['login_index_password_isnull']),

			array("input"=>$_POST["captcha"],		"require"=>(C('captcha_status_login') ? "true" : "false"), "message"=>$lang['login_index_input_checkcode']),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}else {

				if (C('captcha_status_login')){

					$brr = explode( "\t", decrypt( cookie( "seccode".substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8) ), MD5_KEY ) );

					$brr[0] = strtolower($brr[0]);

					$_POST['captcha'] = strtolower($_POST['captcha']);

					if ($brr[0]!=$_POST['captcha']){

						showDialog($lang['login_index_wrong_checkcode']);

					}

				}



				

				$array	= array();

				$array['auser']	= trim($_POST['user_name']);

				$array['apassword']	= md5(trim($_POST['password']));

				$member_info = $model_member->infoMember($array);

				if(is_array($member_info) and !empty($member_info)) {

					setNcCookie('cm_login','',-3600);

				   /**

					 * 写入session

					 */

					$_SESSION['is_cmlogin']	= '1';

					$_SESSION['cmember_id']	= $member_info['aid'];

					$_SESSION['cmember_name']= $member_info['auser'];
                    
                    $_SESSION['cmservice']= $member_info['service'];
                }

            }

		}

		//文章输出

		$list = $this->_article();

		$_pic = @unserialize(C('login_pic'));

		if ($_pic[0] != ''){

			Tpl::output('lpic',SiteUrl.'/'.ATTACH_PATH.'/login/'.$_pic[array_rand($_pic)]);

		}else{

			Tpl::output('lpic',SiteUrl.'/'.ATTACH_PATH.'/login/'.rand(1,4).'.jpg');

		}



		/**

		 * 判断是否登录，如果登录，则跳转回首页

		 */

		if ($_SESSION['is_cmlogin'] == '1'){

			@header('location: index.php?act=carmanage&op=carmanage_order');

			exit;

		}

		if (C('captcha_status_login')){

			Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		}

		if(empty($_GET['ref_url'])) $_GET['ref_url'] = getReferer();

		Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);

		
        Tpl::showpage('carlogin','home_layout');

		

	}	

	

	


	

	public function logoutOp(){

		

		Language::read("home_login_index");

		$lang	= Language::getLangContent();

		session_unset();

		session_destroy();

		setNcCookie('uid','',-3600);

		setNcCookie('rp_reg','',-3600);

		setNcCookie('tm_login','',-3600);

		setNcCookie('goodsnum','',-3600);		

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$out_str = $model_ucenter->userLogout();

			$lang['login_logout_success'] = $lang['login_logout_success'].$out_str;	

			if(empty($_GET['ref_url'])){

				$ref_url = getReferer();

			}else {

				$ref_url = $_GET['ref_url'];

			}

			//showMessage($lang['login_logout_success'],'index.php?act=login&ref_url='.urlencode($ref_url));			

			showMessage($lang['login_logout_success'],'index.php?act=login');			

		}else{

			redirect();

		}

	}
    
    public function carmanage_orderOp() {
        
         if (!$_SESSION['cmservice']){

			@header("Location: index.php?act=carmanage&op=login");

			exit;

		}
		$model_store_order	= Model('store_order');
		$model_member = Model('member');
		/**
		 * 订单分页
		 */
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		/*搜索条件*/
		$array	= array();
		$array['order_state']	= trim($_GET['state_type'])=='' ? 'order_no_shipping' : trim($_GET['state_type']);
		$array['order_country']	= trim($_GET['order_country']);
        $array['order_provider']	= trim($_GET['order_provider']);
        $array['goods_item_no']	= trim($_GET['goods_item_no']);
        $array['buyer_name']	= trim($_GET['buyer_name']);
		$array['order_sn']		= trim($_GET['order_sn']);
		$array['order_evalseller_able']		= trim($_GET['eval']);
        $array['add_time_from'] = strtotime($_GET['add_time_from']);
        $array['add_time_to'] = strtotime($_GET['add_time_to']);
        if($array['add_time_to'] > 0) {
            $array['add_time_to'] +=86400;
        }
		$order_list		= $model_store_order->carmanageOrderList($array,$page);
		if (is_array($order_list) && !empty($order_list)){
		  
			$order_id_array = array();
			$order_array = array();
			$member_id_array = array();
			$member_array = array();
			$refund_array = array();
			$return_array = array();
			foreach ($order_list as $v) {
				if ($v['order_id'] == '') continue;
				$order_id_array[] = $v['order_id'];
				$order_array[$v['order_id']] = $v;				
				if(!in_array($v['buyer_id'],$member_id_array)) $member_id_array[] = $v['buyer_id'];
			}
			$goods_list = $model_store_order->carmanageOrderGoodsList(array('order_id'=>"'".implode("','",$order_id_array)."'"));
			$member_list = $model_member->getMemberList(array('in_member_id'=>"'".implode("','",$member_id_array)."'"));
			if (is_array($member_list) && !empty($member_list)){
				foreach ($member_list as $val) {
					$member_array[$val['member_id']] = $val;
				}
			}
			
			$model_refund	= Model('refund');
			$condition = array();
			$condition['seller_id'] = $_SESSION['member_id'];
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
			$condition['seller_id'] = $_SESSION['member_id'];
			$condition['order_ids'] = "'".implode("','",$order_id_array)."'";
			$condition['return_type'] = '1';
			$return_list= $model_return->getList($condition);
			if (is_array($return_list) && !empty($return_list)){
				foreach ($return_list as $val) {
					$return_array[$val['order_id']] = $val;
				}
			}
		}
		$goods_array = array();
		if (is_array($goods_list) && !empty($goods_list)){
			$store_class = Model('store');
			foreach ($goods_list as $val) {
				$order_id = $val['order_id'];
				if(is_array($refund_array[$order_id]) && !empty($refund_array[$order_id])) $val['refund'] = $refund_array[$order_id];
				if(is_array($return_array[$order_id]) && !empty($return_array[$order_id])) $val['return'] = $return_array[$order_id];
				$val['spec_info_arr'] = '';
				if (!empty($val['spec_info'])){
					$val['spec_info_arr']	= unserialize($val['spec_info']);
				}
				$val['state_info']		= orderStateInfo($val['order_state'],$val['refund_state']);
				
				if ($val['evalseller_status'] == 1){
					$val['able_evaluate'] = false;
				}else {
					$val['able_evaluate'] = true;
				}
				if ($val['able_evaluate'] && $val['evaluation_status'] == 0 && (intval($val['finnshed_time'])+60*60*24*15)<time()){
					$val['able_evaluate'] = false;
				}elseif ($val['able_evaluate'] && $val['evaluation_status'] == 1 && (intval($val['evaluation_time'])+60*60*24*15)<time()) {
					$val['able_evaluate'] = false;
				}
				$val['member_info'] = $member_array[$val['buyer_id']];
				$goods_array[$val['order_id']][] = $val;
			}
		}
		
		foreach($goods_array as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				$param['table'] = 'change';
				$param['field'] = 'order_id';
				$param['value'] =$v['order_id'];
				$arr = Db::getRow($param, "*" );
				$goods_array[$key][$k]['buyer_message'] = $arr['buyer_message'];
			}
		}
		
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
        
        foreach($goods_array as $key=>$val){
            $i=0;
            foreach($val as $keys=>$vals){
            preg_match("/服务地点:.*?&nbsp;/is", $vals['spec_info'], $matches);
			$str=$matches[0];
             $str=str_replace("服务地点:", "", $str);
              $str=str_replace("&nbsp;", "", $str);
            
             if($_SESSION['cmservice']==$str){
                $goods_array[$key][$i]=$vals;
                $i++;
             }
            }
            if($i==0){
                unset($goods_array[$key]);
            }
        }
      //  print_r($goods_array);
	
		Tpl::output('goods_array',$goods_array);
		Tpl::output('order_array',$order_array);
		
	
		Tpl::output('show_page',$page->show());
     
        Tpl::output('complain_time_limit',C('complain_time_limit'));

		self::profile_menu('store_order',$array['order_state']);
		Tpl::output('menu_sign','carmanage_order');
		Tpl::output('menu_sign_url','index.php?act=carmanage&op=carmanage_order');
		Tpl::output('menu_sign1',empty($_GET['state_type'])?'all_order':$_GET['state_type']);
		Tpl::showpage('carmanage_order');
	}

    public function gohandleOp(){
         if (!$_SESSION['cmservice']){
            @header("Location: index.php?act=carmanage&op=login");
        	exit;
           }
        
        $order_id=$_GET['order_id'];
        $rec_id=$_GET['rec_id'];
        
        	$param['table'] = 'order_goods';
			$param['field'] = 'rec_id';
			$param['value'] =$rec_id;
			$arr = Db::getRow($param, "*" );
            
             preg_match("/服务地点:.*?&nbsp;/is", $arr['spec_info'], $matches);
			 $str=$matches[0];
             $str=str_replace("服务地点:", "", $str);
               $str=str_replace("&nbsp;", "", $str);
             if($_SESSION['cmservice']==$str){
                    $param=array();
                    $param['order_state']=40;
                	$where = " order_id = '$order_id'";
		            $ispass= Db::update('order',$param,$where);
                    if($ispass){
                        showMessage("处理成功",'index.php?act=carmanage&op=carmanage_order');			
                    }else{
                        showMessage("处理失败，请重新操作",'index.php?act=carmanage&op=carmanage_order');
                    }
             }
        
    
        
    }

    
    private function profile_menu($menu_type,$menu_key='') {
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
		
			case 'store_order':
				$menu_array = array(
				4=>array('menu_key'=>'order_no_shipping',	'menu_name'=>Language::get('nc_member_nohanlde'),	'menu_url'=>'index.php?act=carmanage&op=carmanage_order&state_type=order_no_shipping'),
				6=>array('menu_key'=>'order_finish',		'menu_name'=>Language::get('nc_member_yeshanlde'),	'menu_url'=>'index.php?act=carmanage&op=carmanage_order&state_type=order_finish'),
                );
				break;
			
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
	
    
    	public function printOp() {

        if (!$_SESSION['cmservice']){
            @header("Location: index.php?act=carmanage&op=login");
        	exit;
           }
        

		Language::read('member_printorder');

		$order_id	= intval($_GET['order_id']);

		if ($order_id <= 0){

			showMessage(Language::get('wrong_argument'),'','html','error');

		}

		$order_model = Model('order');

		$condition['order_id'] = $order_id;

		$order_info = $order_model->getOrderById($order_id,'all',$condition);

		if (empty($order_info)){

			showMessage(Language::get('member_printorder_ordererror'),'','html','error');

		}

		if ($order_info['seller_id'] != 206){

			showMessage(Language::get('member_printorder_ordererror'),'','html','error');

		}

		Tpl::output('order_info',$order_info);

		$model_store	= Model('store');

		$store_info		= $model_store->shopStore(array('store_id'=>$order_info['store_id']));
        
        
        

		if (!empty($store_info['store_label'])){

			if (file_exists(BasePath.DS.ATTACH_STORE.DS.$store_info['store_label'])){

				$store_info['store_label'] = SiteUrl.DS.ATTACH_STORE.DS.$store_info['store_label'];

			}else {

				$store_info['store_label'] = '';

			}

		}

		if (!empty($store_info['store_stamp'])){

			if (file_exists(BasePath.DS.ATTACH_STORE.DS.$store_info['store_stamp'])){

				$store_info['store_stamp'] = SiteUrl.DS.ATTACH_STORE.DS.$store_info['store_stamp'];

			}else {

				$store_info['store_stamp'] = '';

			}

		}

		Tpl::output('store_info',$store_info);

		$ordergoods_model = Model('order_goods');

		$ordergoods_list= $ordergoods_model->getOrderGoodsList(array('order_id'=>$order_id));
        
        //获取汽配车牌
      


        $pnumlist=Db::getAll("SELECT * FROM haipinlegou_car_pnum WHERE memberid='".$order_info['buyer_id']."' ORDER BY addtime DESC LIMIT 1");

        Tpl::output('pnumlist',$pnumlist);
        
        
        $detaillist=Db::getAll("SELECT * FROM haipinlegou_car_detail WHERE order_sn='".$order_info['order_sn']."' ORDER BY d_addtime DESC");

        Tpl::output('detaillist',$detaillist);
        

		$ordergoods_listnew = array();

		$goods_allnum = 0;

		$goods_totleprice = 0;

		if (!empty($ordergoods_list)){

			$goods_count = count($ordergoods_list);

			$i = 1;

			foreach ($ordergoods_list as $k=>$v){

				$v['goods_name'] = str_cut($v['goods_name'],100);

				$v['spec_info'] = str_cut($v['spec_info'],40);

				$goods_allnum += $v['goods_num'];				

				$v['goods_allprice'] = ncPriceFormat($v['goods_num']*$v['goods_price']);

				$goods_totleprice += $v['goods_allprice'];

				$ordergoods_listnew[ceil($i/4)][$i] = $v;

				$i++;

			}

		}

		$order_info['discount'] = ncPriceFormat($order_info['voucher_price']);

		$order_info['shipping_fee'] = $order_info['shipping_fee'];

		Tpl::output('discount',$discount);

		Tpl::output('goods_allnum',$goods_allnum);

		Tpl::output('goods_totleprice',ncPriceFormat($goods_totleprice));

		Tpl::output('ordergoods_list',$ordergoods_listnew);

		Tpl::showpage('carmanage_orderprint',"null_layout");

	}

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



	

	//随机数

	public function xRandOp($leng=6,$min=0,$max=9)

	{

		$str='';

		for($x=1;$x<=$leng;$x++){

			$str.=rand($min,$max);

		}

		return $str;

	}
}

