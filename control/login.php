<?php



defined('haipinlegou') or exit('Access Invalid!');



class loginControl extends BaseHomeControl {



	public function __construct(){

		parent::__construct();
        
    }



	//会员登陆

	public function indexOp(){
	   
       //转移链接（会员邀请奖励）
        $url=$_SERVER["REQUEST_URI"];
        if(stristr($url,'popularize')){
           $url=str_replace('&amp;',"&",$url);
           @header('location:'.$url);exit();
        }

		Language::read("home_login_index");

		$lang	= Language::getLangContent();
        
		/**

		 * 实例化模型

		 */

		$model_member	= Model('member');

		/**

		 * 检查登录状态

		 */

		$model_member->checkloginMember();

		

		if (chksubmit()){

//			if (cookie('tm_login') == 5){
//
//				showDialog($lang['nc_common_op_repeat'],SiteUrl);
//
//			}



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



				if(C('ucenter_status')) {

					$model_ucenter = Model('ucenter');

					$member_id = $model_ucenter->userLogin(trim($_POST['user_name']),trim($_POST['password']));

					if(intval($member_id) == 0) {

						if (cookie('tm_login') >= 6){

							showDialog($lang['nc_common_op_repeat']);

						}

						log_times('login');

						showDialog($lang['login_index_login_again']);

					}

				}

				$array	= array();

				$array['member_name']	= trim($_POST['user_name']);

				$array['member_passwd']	= md5(trim($_POST['password']));

				$member_info = $model_member->infoMember($array);

				if(is_array($member_info) and !empty($member_info)) {

					setNcCookie('tm_login','',-3600);

					if(!$member_info['member_state']){

						showDialog($lang['nc_notallowed_login']);

					}

                    //设置自动登录时间期限
                    if($_POST['auto'] == 1 ) {
                        $time = 3600 * 24 * 14;
                        setNcCookie(md5('auto'),$member_info['member_name'],$time);
                    }

					/**

					 * 登录时间更新

					 */

					$update_info	= array(

					'member_login_num'=>($member_info['member_login_num']+1),

					'member_login_time'=>time(),

					'member_old_login_time'=>$member_info['member_login_time'],

					'member_login_ip'=>getIp(),

					'member_old_login_ip'=>$member_info['member_login_ip']);

					$model_member->updateMember($update_info,$member_info['member_id']);



					/**

					 * 写入session

					 */

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

					// cookie中的cart存入数据库

					$this->mergecart();

					
                                       
					//添加会员积分
                                        
					if (C('points_isuse')){

						//一天内只有第一次登录赠送积分
//                                                echo date('Y-m-d',$member_info['member_login_time']).'<br/>';
//                                                echo date('Y-m-d');
                                                  //die;
                                                
						if(trim(@date('Y-m-d',$member_info['member_login_time']))!=trim(date('Y-m-d'))){

							$points_model = Model('points');

							$points_model->savePointsLog('login',array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name']),true);
						}
                                              
					}

					$evaluate_model = Model('evaluate');

					$evaluate_model->updateMemberStat($_SESSION['member_id'],$_SESSION['store_id']);//统计更新:会员信用,卖家信用,店铺评分

					$_POST['ref_url']	= strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_snsindex';

					if(C('ucenter_status')) {

						$extrajs = $model_ucenter->outputLogin($member_info['member_id'],trim($_POST['password']));						

					}elseif(empty($_GET['inajax'])){
                        if(!empty($_POST['ref_url'])){
                            header("Location:{$_POST['ref_url']}");
                            exit();
                        }
						header('location:index.php');exit();

					}



					$extrajs = empty($_GET['inajax']) ? $extrajs : $extrajs.'<script>CUR_DIALOG.close();</script>';

					$_POST['ref_url'] 	= empty($_GET['inajax']) ? $_POST['ref_url'] : 'reload';

					showDialog($lang['login_index_login_success'],$_POST['ref_url'],'succ',$extrajs);

				} else {

					log_times('login');

					showDialog($lang['login_index_login_fail']);

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

		if ($_SESSION['is_login'] == '1'){

			@header('location: index.php');

			exit;

		}

		if (C('captcha_status_login')){

			Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		}

		if(empty($_GET['ref_url'])) $_GET['ref_url'] = getReferer();

		Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);

		if ($_GET['inajax'] == 1){

			Tpl::showpage('login_inajax','null_layout');

		}else{

			Tpl::showpage('login');

		}

	}	

	

	//企业登录

	public function company_loginOp(){

		Language::read("home_login_index");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('company');

		

		$model_member->checkloginMember();

		

		if (chksubmit()){

			//操作频繁

			if (cookie('tm_login') == 5){

				showDialog($lang['nc_common_op_repeat'],SiteUrl);

			}



			Security::checkToken();



			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["company_email"],		"require"=>"true", "message"=>$lang['login_index_company_isnull']),

			array("input"=>$_POST["company_passwd"],		"require"=>"true", "message"=>$lang['login_index_password_isnull']),

			array("input"=>$_POST["captcha"],		"require"=>(C('captcha_status_login') ? "true" : "false"), "message"=>$lang['login_index_input_checkcode']),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}else {

				if (C('captcha_status_login')){

					$brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

					$brr[0] = strtolower($brr[0]);

					$_POST['captcha'] = strtolower($_POST['captcha']);

					if ($brr[0]!=$_POST['captcha']){

						showDialog($lang['login_index_wrong_checkcode']);

					}

				}



				if(C('ucenter_status')) {

					$model_ucenter = Model('ucenter');

					$member_id = $model_ucenter->userLogin(trim($_POST['company_email']),trim($_POST['company_passwd']));

					if(intval($member_id) == 0) {

						
                                            

						log_times('login');

						showDialog($lang['login_index_login_again']);

					}

				}

				$array	= array();

				$array['company_email']	= trim($_POST['company_email']);

				$array['company_passwd']	= md5(trim($_POST['company_passwd']));

				$member_info = $model_member->infoMember($array);



				if(is_array($member_info) and !empty($member_info) and $member_info['pass'] == 2){

					setNcCookie('tm_login','',-3600);

					if(!$member_info['company_state']){

						showDialog($lang['nc_notallowed_login']);

					}

					

					$update_info	= array(

					'company_login_num'=>($member_info['company_login_num']+1),

					'company_login_time'=>time(),

					'company_old_login_time'=>$member_info['company_member_login_time'],

					'company_login_ip'=>getIp(),

					'company_old_login_ip'=>$member_info['company_login_ip']);

					$model_member->updateMember($update_info,$member_info['member_id']);

					

					//判断是不是个人账号

					if(empty($member_info['company_id']))

					{

						showDialog($lang['login_index_login_personal']);

					}

					

					$_SESSION['is_company_login']	= '1';

					$_SESSION['is_seller']	= intval($member_info['store_id']) == 0 ? '' : 1;

					$_SESSION['company_id']	= $member_info['company_id'];

					$_SESSION['company_name']= $member_info['company_name'];

					$_SESSION['company_email']= $member_info['company_email'];



					if ($GLOBALS['setting_config']['qq_isuse'] == 1 && trim($member_info['company_qqopenid'])){

						$_SESSION['openid']		= $member_info['member_qqopenid'];

					}

					if ($GLOBALS['setting_config']['sina_isuse'] == 1 && trim($member_info['company_sinaopenid'])){

						$_SESSION['slast_key']['uid'] = $member_info['company_sinaopenid'];

					}

					if ($member_info['store_id'] > 0){

						$store_model = Model('store');

						$store_info = $store_model->shopStore(array('store_id'=>$member_info['store_id']));

						if (is_array($store_info) && count($store_info)>0){

							$_SESSION['store_id']	= $store_info['store_id'];

							$_SESSION['store_name']	= $store_info['store_name'];

							$_SESSION['grade_id']	= $store_info['grade_id'];

						}

					}

					$this->company_mergecart();

					

					if (C('points_isuse')){

						if(trim(@date('Y-m-d',$member_info['company_login_time']))!=trim(date('Y-m-d'))){

							$points_model = Model('points');

							$points_model->savePointsLog('login',array('pl_memberid'=>$member_info['company_id'],'pl_membername'=>$member_info['company_name']),true);

						}

					}

					$evaluate_model = Model('evaluate');

					$evaluate_model->updateMemberStat($_SESSION['company_id'],$_SESSION['store_id']);

					$_POST['ref_url']	= strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_snsindex';

					if(C('ucenter_status')) {

						$extrajs = $model_ucenter->outputLogin($member_info['company_id'],trim($_POST['company_passwd']));						

					}elseif(empty($_GET['inajax'])){

						@header('location: '.$_POST['ref_url']);exit();

					}



					$extrajs = empty($_GET['inajax']) ? $extrajs : $extrajs.'<script>CUR_DIALOG.close();</script>';

					$_POST['ref_url'] 	= empty($_GET['inajax']) ? $_POST['ref_url'] : 'reload';

					showDialog($lang['login_index_login_success'],$_POST['ref_url'],'succ',$extrajs);

				} else {

					log_times('login');

					showDialog($lang['login_index_login_fail']);

				}

			}

		}



		$_pic = @unserialize(C('login_pic'));

		if ($_pic[0] != ''){

			Tpl::output('lpic',SiteUrl.'/'.ATTACH_PATH.'/login/'.$_pic[array_rand($_pic)]);

		}else{

			Tpl::output('lpic',SiteUrl.'/'.ATTACH_PATH.'/login/'.rand(1,4).'.jpg');

		}



	

		if ($_SESSION['is_login'] == '1'){

			@header('location: index.php');

			exit;

		}

		if (C('captcha_status_login')){

			Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		}

		if(empty($_GET['ref_url'])) $_GET['ref_url'] = getReferer();

		Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);

		if ($_GET['inajax'] == 1){

			Tpl::showpage('login_inajax','null_layout');

		}else{

			Tpl::showpage('login');

		}

	}



	

	public function logoutOp(){

		

		Language::read("home_login_index");

		$lang	= Language::getLangContent();

		session_unset();

		session_destroy();

        setNcCookie(md5('auto'),'',-3600);

		setNcCookie('uid','',-3600);

		setNcCookie('rp_reg','',-3600);

		setNcCookie('tm_login','',-3600);
        
        setNcCookie('cm_login','',-3600);

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



	public function checknameOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_POST['user_name']));

			if($result == 1) {

				echo 0;

			} else {

				echo 1;

			}

		} else {

		

			$model_member	= Model('member');



			$check_member_name	= $model_member->infoMember(array('member_name'=>trim($_POST['user_name'])));

			if(is_array($check_member_name) and count($check_member_name)>0) {

				echo 1;

			} else {

				echo 0;

			}

		}

	}	

    public function checkemailOp(){

    	if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkEmailExit(trim($_POST['email']));

			if($result == 1) {

				echo 0;

			} else {

				echo 1;

			}



		} else {

		

			$model_member	= Model('member');



			$check_member_email	= $model_member->infoMember(array('member_email'=>trim($_POST['email'])));

			if(is_array($check_member_email) and count($check_member_email)>0) {

				echo 1;

			} else {

				echo 0;

			}

		}



    }


    /* 个人注册 */

	public function selfregister1Op(){
	 

		/*

		if (check_repeat('reg',3000)){

			showDialog(Language::get('nc_common_op_repeat'),'index.php');

		}

		*/

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

        

        $model_member	= Model('member');

		

		$model_member->checkloginMember();



		

		$obj_validate = new Validate();

		$obj_validate->validateparam = array(

		array("input"=>$_POST["password"],		"require"=>"true",		"message"=>$lang['login_usersave_password_isnull']),

		array("input"=>$_POST["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$_POST["password"],"message"=>$lang['login_usersave_password_not_the_same']),

		array("input"=>$_POST["email"],			"require"=>"true",		"validator"=>"email", "message"=>$lang['login_usersave_wrong_format_email']),

		//array("input"=>strtoupper($_POST["captcha"]),"require"=>(C('captcha_status_register') == '1' ? "true" : "false"),"message"=>$lang['login_usersave_code_isnull']),

		array("input"=>$_POST["agree"],	"require"=>"true", 		"message"=>$lang['login_usersave_you_must_agree']),
       

		array("input"=>$_POST["mobile"],		"require"=>"true",		"message"=>$lang['login_usersave_login_phonesave_phone_isnull']),

        //array("input"=>$_POST["mobile"]),       "validator"=>"mobile",  "message"=>"请输入正确的手机号码",

		array("input"=>$_POST["mobile_code"],		"require"=>"true",		"message"=>$lang['login_usersave_login_phonesave_phone_vertify_isnull']),

		);

      

		$error = $obj_validate->validate();

		if ($error != ''){

			showValidateError($error);

		}

	

		//检测手机验证码是否正确

        $model = Model('mobile_vertify');
        $res = $model->where(array('mobile_num'=>$_POST['mobile']))->order('id DESC')->select();
        $mobile_info = $res[0];

		//判断验证码是否过期

		if($mobile_info['dead_time'] < time()){

			showMessage($lang['login_register_mobile_vertify_fail'],'','html','error');

		}

		//判断输入的验证码是否正确

		if($mobile_info['num'] != trim($_POST["mobile_code"])){

			showMessage($lang['login_register_mobile_vertify_wrong'],'','html','error');

		}		

		

				//检测用户名是否已经注册

		$check_member_name	= $model_member->infoMember(array('member_name'=>trim($_POST['mobile'])));

		if(is_array($check_member_name) and count($check_member_name)>0) {

			showDialog($lang['login_usersave_your_username_exists']);

		}

		//检测邮箱是已经注册

		$check_member_email	= $model_member->infoMember(array('member_email'=>trim($_POST['email'])));

		if(is_array($check_member_email) and count($check_member_email)>0) {

			showDialog($lang['login_usersave_your_email_exists']);

		}



		//检测手机号是已经注册

/*		$check_member_phone	= $model_member->infoMember(array('member_mob_phone'=>trim($_POST['mob_phone'])));

		if(is_array($check_member_phone) and count($check_member_phone)>0) {

			showDialog($lang['login_usersave_your_phone_exists']);

		}*/
        $user_array	= array();
        //邀请码
        if($_POST['invitation_code']){
            $invitation_array=array();
            $invitation_array['table']='invitation_code';
            $invitation_array['field'][1]='code';
            $invitation_array['value'][1]=$_POST['invitation_code'];
            $invitation_array['field'][2]='type';
            $invitation_array['value'][2]=0;
            $invitation_data=DB::getRow($invitation_array);
            if($invitation_data){
                $user_array['invitation_code']		= $_POST['invitation_code'];
                $user_array['is_invitation']		= 1;
            }else{
                showMessage($lang['invitation_code_wrong'],'','html','error');
            }
        }


		

//$user_array	= array();

		if(C('ucenter_status')) {

		

			$model_ucenter = Model('ucenter');

			$uid = $model_ucenter->addUser(trim($_POST['mobile']),trim($_POST['password']),trim($_POST['email']),trim($_POST['mob_phone']));

			if($uid<1) showMessage($lang['login_usersave_regist_fail'],'','html','error');

			$user_array['member_id']		= $uid;

		}



		$user_array['member_name']		= $_POST['mobile'];

		$user_array['member_passwd']	= $_POST['password'];

		$user_array['member_email']		= $_POST['email'];

		$user_array['member_mob_phone']		= $_POST['mob_phone'];
        
        $user_array['examine']		= 1;
        
        //推广人  
        $promoman['member_name'] =strtolower(trim($_POST['promoman']));
        if(!empty($promoman)){
           $member_info = $model_member -> infoMember($promoman); 
           //推广人是否存在
           if(!empty($member_info)){
            $user_array['extension_id'] = $member_info['member_id'];//推广人
           }else{
            showMessage($lang['login_register_ispromoman'],'','html','error');
           }
          
        }
       

		//print_r($user_array);exit;

        $user_array['examine']=1;

		$result	= $model_member->addMember($user_array);

		if($result) {
            
            
            //注册送预存款
            if(C('predeposit_reg_switch')==1){
               // C('predeposit_reg_month');//
                $endtime=strtotime(C('predeposit_reg_month'));//是否在奖励时间
				$starttime=strtotime(C('predeposit_reg_month_s'));//是否在奖励时间内内
                if(time()<$endtime+86400 && time()>$starttime){
                    $predeposit_reg_money=C('predeposit_reg_money');//注册奖励金额
                    
                    
                   // $upmember_array['available_predeposit'] = array('sign'=>'increase','value'=>$predeposit_reg_money);
                    //$obj_member->updateMember($upmember_array,$result);
                    
                    //代金券

                    $predeposit_limit_money = C('predeposit_limit_money');
                    
                    $obj_voucher = Model('voucher');
                    
                    $insert_arr = array();

            		$insert_arr['voucher_code'] = $obj_voucher->get_voucher_code();
            
            		$insert_arr['voucher_t_id'] = 1;
            
            		$insert_arr['voucher_title'] = "代金券"; 
            
            		$insert_arr['voucher_desc'] = "会员注册奖励";
            
            	    $insert_arr['voucher_price'] = $predeposit_reg_money;
            
            		$insert_arr['voucher_limit'] = empty($predeposit_limit_money) ? 100 : $predeposit_limit_money;
            
            		$insert_arr['voucher_store_id'] = 0;
            
            		$insert_arr['voucher_state'] = 1;
                    
                    $insert_arr['voucher_start_date'] = time();
                    
                    $insert_arr['voucher_end_date'] = time()+(86400*C('predeposit_voucher_time')); // 代金卷结束时间
            
            		$insert_arr['voucher_active_date'] = time();
            
            		$insert_arr['voucher_owner_id'] = $result;
                    
            
            	    $obj_voucher->table('voucher')->insert($insert_arr);
                    
                    
                    //插入预存款类型记录
                    $insertpredeposit_array['mid'] = $result;
                    $insertpredeposit_array['type'] = 1;
                    $insertpredeposit_array['money'] = $predeposit_reg_money;
                    $insertpredeposit_array['addtime'] = time();
                    Db::insert('predeposit_record', $insertpredeposit_array);
                }
           }
           //邀请奖励
           if(C('predeposit_reg_switch')==1){
               if($_SESSION['extension_id']){
                    $endtime=strtotime(C('predeposit_reg_month'));//是否在奖励时间内
					$starttime=strtotime(C('predeposit_reg_month_s'));//是否在奖励时间内内
                	if(time()<$endtime+86400 && time()>$starttime){
                      //  $predeposit_reg_money=C('predeposit_invitation_getmoney');//邀请奖励金额
                       // $obj_member = Model('member');
                       // $upmember_array = array();
                        //$upmember_array['available_predeposit'] = array('sign'=>'increase','value'=>$predeposit_reg_money);
                        //$obj_member->updateMember($upmember_array,$_SESSION['extension_id']);
                        
                     //   $obj_voucher = Model('voucher');
                        
                      //  $insert_arr = array();
    
                	//	$insert_arr['voucher_code'] = $obj_voucher->get_voucher_code();
                
                	//	$insert_arr['voucher_t_id'] = 1;
                
                	//	$insert_arr['voucher_title'] = "代金券";
                
                	//	$insert_arr['voucher_desc'] = "会员注册和邀请";
                
                	 //   $insert_arr['voucher_price'] = $predeposit_reg_money;
                
                	//	$insert_arr['voucher_limit'] = 100;
                
                	//	$insert_arr['voucher_store_id'] = 0;
                
                	//	$insert_arr['voucher_state'] = 1;
                
                	//	$insert_arr['voucher_active_date'] = time();
                
                	//	$insert_arr['voucher_owner_id'] = $_SESSION['extension_id'];
                
                	 //   $obj_voucher->table('voucher')->insert($insert_arr);
                        
                        
                        
                      //  插入预存款类型记录
                      //  $insertpredeposit_array2['mid'] = $_SESSION['extension_id'];
                    //    $insertpredeposit_array2['type'] = 2;
                     //   $insertpredeposit_array2['money'] = $predeposit_reg_money;
                    //    $insertpredeposit_array2['addtime'] = time();
                    //    Db::insert('predeposit_record', $insertpredeposit_array2);
                        
                        //绑定谁邀请的会员ID
                        $extensionupdate_array['extension_id'] = $_SESSION['extension_id'];
                        Db::update('member', $extensionupdate_array,"where member_id=$result");
                        unset($_SESSION['extension_id']);
                    }
               }
           }
            
            
			setNcCookie('rp_reg',time());



			$_SESSION['is_login']	= '1';

			$_SESSION['member_id']	= $result;

			$_SESSION['member_name']= trim($user_array['member_name']);

			$_SESSION['member_email']= trim($user_array['member_email']);

			$_SESSION['pass']= 0;

            $_SESSION['category'] = 1;


			$this->mergecart();	

		

			if ($GLOBALS['setting_config']['points_isuse'] == 1){

				$points_model = Model('points');

				$points_model->savePointsLog('regist',array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name']),false);

			}



			//header('location:index.php?act=login&op=step5');

			//showDialog('据海关要求，请完善个人资料','index.php?act=home&op=member');
            header('location:./index.php');
			

		} else {

			showDialog(Language::get('login_usersave_regist_fail'));

		}





	}



	/* 企业注册 */

	public function comregisterOp(){



		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		



		if(!empty($_POST))

		{

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["email"],		"require"=>"true",		"message"=>$lang['login_usersave_login_compnysave_email_isnull']),

			array("input"=>$_POST["agree"],			"require"=>"true", 		"message"=>$lang['login_usersave_you_must_agree']),

            

            array("input"=>$_POST["company_password"],		"require"=>"true",		"message"=>$lang['login_usersave_password_isnull']),

			array("input"=>$_POST["company_password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$_POST["company_password"],"message"=>$lang['login_usersave_password_not_the_same']),



			array("input"=>$_POST["company_name"],"require"=>"true","message"=>'企业名称不能为空'),

			array("input"=>$_POST["register_num"],"require"=>"true", "message"=>'营业执照注册号不能为空'),

			array("input"=>$_POST["member_areainfo"],"require"=>"true","message"=>'营业执照所在地不能为空'),

			array("input"=>$_POST["operation_term"],"require"=>"true","message"=>'营业期限不能为空'),

			array("input"=>$_POST["company_address"],"require"=>"true","message"=>'常用地址不能为空'),

			array("input"=>$_POST["mob_phone"],"require"=>"true",	"message"=>'联系电话不能为空'),

			array("input"=>$_POST["company_license"],"require"=>"true",	"message"=>'营业执照副本扫描件不能为空'),

			array("input"=>$_POST["organization_code"],"require"=>"true","message"=>'组织机构代码不能为空'),

			array("input"=>$_POST["company_range"],"require"=>"true","message"=>'营业范围不能为空'),

			array("input"=>$_POST["registered_capital"],"require"=>"true","message"=>'注册资金不能为空'),

			array("input"=>$_POST["company_fax"],"require"=>"true","message"=>'传真不能为空'),

						array("input"=>strtoupper($_POST["captcha"]),"require"=>(C('captcha_status_register') == '1' ? "true" : "false"),"message"=>$lang['login_usersave_code_isnull']),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}



			if (C('captcha_status_login')){

				$brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

				$brr[0] = strtolower($brr[0]);

				$_POST['captcha'] = strtolower($_POST['captcha']);

				if ($brr[0]!==$_POST['captcha']){

					showDialog($lang['login_usersave_wrong_code']);

				}

			}

			

			//检测企业用户邮箱是否已经注册

			$check_member_email	= $model_member->infoMember(array('member_name'=>trim($_POST['email'])));

			if(is_array($check_member_email) and count($check_member_email)>0 ) {

				showDialog($lang['login_usersave_your_email_exists']);

			}

            

            //检测企业营业执照注册号是否已经注册

			$check_register_num	= $model_member->infoMember(array('register_num'=>trim($_POST['register_num'])));

			if(is_array($check_register_num) and count($check_register_num)>0) {

                showDialog("企业营业执照注册号被注册");

			}



			//检测企业组织机构代码是否已经注册

			$check_organization_code	= $model_member->infoMember(array('organization_code'=>trim($_POST['organization_code'])));

			if(is_array($check_organization_code) and count($check_organization_code>0)) {

                showDialog("企业组织机构代码被注册");

			}

			$data = array();

			$data['member_name'] = $_POST['email'];

			$data['member_passwd'] = $_POST['company_password'];

			$data['company_name'] = $_POST['company_name'];

			$data['register_num'] = $_POST['register_num'];

			$data['operation_term'] = $_POST['operation_term'];

			$data['company_address'] = $_POST['company_address'];

			$data['company_license'] = $_POST['company_license'];

			$data['organization_code'] = $_POST['organization_code'];

			$data['company_range'] = $_POST['company_range'];

			$data['registered_capital'] = $_POST['registered_capital'];

			$data['company_fax'] = $_POST['company_fax'];

			$data['member_areainfo'] = $_POST['member_areainfo'];

			$data['member_mob_phone'] = $_POST['mob_phone'];

			//print_r($data);exit;



			$result	= $model_member->addCompany($data);

		    if($result){

		    	header('location:index.php?act=login&op=step3');

		    }else{

		    	showDialog(Language::get('login_usersave_regist_fail'));

		    }



		}

			

	



	}

    

    /* 企业注册1 */

	public function comregister1Op(){



		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		



		if(!empty($_POST))

		{

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["email"],		"require"=>"true",		"message"=>$lang['login_usersave_login_compnysave_email_isnull']),

			array("input"=>$_POST["agree"],			"require"=>"true", 		"message"=>$lang['login_usersave_you_must_agree']),

            

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}



			

			//检测企业用户邮箱是否已经注册

			$check_member_email	= $model_member->infoMember(array('member_name'=>trim($_POST['email'])));

			if(is_array($check_member_email) and count($check_member_email)>0 ) {

				showDialog($lang['login_usersave_your_email_exists']);

			}

            



			$data = array();

			$_SESSION['com_email'] = trim($_POST['email']);





		    if($_SESSION['com_email']!=null){

		    	header('location:index.php?act=login&op=step3');

		    }else{

		    	showDialog(Language::get('login_usersave_regist_fail'));

		    }



		}

		

	}



	/* 企业注册2 */

	public function comregister2Op(){



		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		



		if(!empty($_POST))

		{

			

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(



            array("input"=>$_POST["company_password"],		"require"=>"true",		"message"=>$lang['login_usersave_password_isnull']),

			array("input"=>$_POST["company_password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$_POST["company_password"],"message"=>$lang['login_usersave_password_not_the_same']),



			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}



			$_SESSION['com_pwd'] = trim($_POST["company_password"]);



		    if($_SESSION['com_pwd']!=null){

		    	header('location:index.php?act=login&op=step4');

		    }else{

		    	showDialog(Language::get('login_usersave_regist_fail'));

		    }



		}

			

	}



    /* 企业注册3 */

	public function comregister3Op(){



		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		

		if(!empty($_POST))

		{

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(



			array("input"=>$_POST["company_name"],"require"=>"true","message"=>'企业名称不能为空'),

			array("input"=>$_POST["register_num"],"require"=>"true", "message"=>'营业执照注册号不能为空'),

			array("input"=>$_POST["member_areainfo"],"require"=>"true","message"=>'营业执照所在地不能为空'),

			array("input"=>$_POST["operation_term"],"require"=>"true","message"=>'营业期限不能为空'),

			array("input"=>$_POST["company_address"],"require"=>"true","message"=>'常用地址不能为空'),

			array("input"=>$_POST["mob_phone"],"require"=>"true",	"message"=>'联系电话不能为空'),

			array("input"=>$_POST["company_license"],"require"=>"true",	"message"=>'营业执照副本扫描件不能为空'),

			array("input"=>$_POST["organization_code"],"require"=>"true","message"=>'组织机构代码不能为空'),

			array("input"=>$_POST["company_range"],"require"=>"true","message"=>'营业范围不能为空'),

			array("input"=>$_POST["registered_capital"],"require"=>"true","message"=>'注册资金不能为空'),

			array("input"=>$_POST["company_fax"],"require"=>"true","message"=>'传真不能为空'),

			array("input"=>strtoupper($_POST["captcha"]),"require"=>(C('captcha_status_register') == '1' ? "true" : "false"),"message"=>$lang['login_usersave_code_isnull']),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}



			if (C('captcha_status_login')){

				$brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

				$brr[0] = strtolower($brr[0]);

				$_POST['captcha'] = strtolower($_POST['captcha']);

				if ($brr[0]!==$_POST['captcha']){

					showDialog($lang['login_usersave_wrong_code']);

				}

			}

			

			//检测企业用户邮箱是否已经注册

			$check_member_email	= $model_member->infoMember(array('member_name'=>trim($_SESSION['com_email'])));

			if(is_array($check_member_email) and count($check_member_email)>0 ) {

				showDialog($lang['login_usersave_your_email_exists']);

			}

            

            //检测企业营业执照注册号是否已经注册

			$check_register_num	= $model_member->infoMember(array('register_num'=>trim($_POST['register_num'])));

			if(is_array($check_register_num) and count($check_register_num)>0) {

                showDialog("企业营业执照注册号被注册");

			}



			//检测企业组织机构代码是否已经注册

			$check_organization_code	= $model_member->infoMember(array('organization_code'=>trim($_POST['organization_code'])));

			if(is_array($check_organization_code) and count($check_organization_code>0)) {

                showDialog("企业组织机构代码被注册");

			}

			$data = array();

			$data['member_name'] = $_SESSION['com_email'];

			$data['member_passwd'] = $_SESSION['com_pwd'];



			$data['company_name'] = $_POST['company_name'];

			$data['register_num'] = $_POST['register_num'];

			$data['operation_term'] = $_POST['operation_term'];

			$data['company_address'] = $_POST['company_address'];

			$data['company_license'] = $_POST['company_license'];

			$data['organization_code'] = $_POST['organization_code'];

			$data['company_range'] = $_POST['company_range'];

			$data['registered_capital'] = $_POST['registered_capital'];

			$data['company_fax'] = $_POST['company_fax'];

			$data['member_areainfo'] = $_POST['member_areainfo'];

			$data['member_mob_phone'] = $_POST['mob_phone'];

			$data['category'] = 2;
	

			if($_POST['change'] == 1){
				$result	= $model_member->updateMember($data);
			}else{
				$result	= $model_member->addCompany($data);
			}
	



		    if($result){

		    	unset($_SESSION['com_email']);

		    	unset($_SESSION['com_pwd']);

		    	//header('location:index.php?act=login&op=step6');

				$_SESSION['is_login']	= '1';

				$_SESSION['member_id']	= $result;

				$_SESSION['member_name']= trim($data['member_name']);

				$_SESSION['member_email']= trim($data['member_email']);

				$_SESSION['category']= 2;

				showDialog(Language::get('login_company_success'),'index.php');

		    }else{

		    	showDialog(Language::get('login_usersave_regist_fail'));

		    }



		}

			

	



	}



	

	public function registerOp($type = 'start') {

		

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');
        //获取邀请编号
        if($_GET['popularize']){
            $extension_id=decrypt($_GET['popularize'],MD5_KEY);
            $extension_array=array();
            $extension_array['table']='member';
            $extension_array['field']='member_id';
            $extension_array['value']=$extension_id;
            $extension_data=DB::getRow($extension_array);
            if($extension_data){
             $_SESSION['extension_id']=$extension_id;
            }
        }
		

		$model_member->checkloginMember();

		if(C('captcha_status_register') == '1'){

			Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		}
        $this->_article();

		Tpl::output('html_title',C('site_name').' - '.$lang['login_register_join_us']);

		Tpl::output('type',$type);

		Tpl::showpage('register');

	}

	

	public function usersaveOp() {

		if (check_repeat('reg',40)){

			showDialog(Language::get('nc_common_op_repeat'),'index.php');

		}



	

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		

		$model_member->checkloginMember();



		

		$obj_validate = new Validate();

		$obj_validate->validateparam = array(

		array("input"=>$_POST["user_name"],		"require"=>"true",		"message"=>$lang['login_usersave_username_isnull']),

		array("input"=>$_POST["password"],		"require"=>"true",		"message"=>$lang['login_usersave_password_isnull']),

		array("input"=>$_POST["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$_POST["password"],"message"=>$lang['login_usersave_password_not_the_same']),

		array("input"=>$_POST["email"],			"require"=>"true",		"validator"=>"email", "message"=>$lang['login_usersave_wrong_format_email']),

		array("input"=>strtoupper($_POST["captcha"]),"require"=>(C('captcha_status_register') == '1' ? "true" : "false"),"message"=>$lang['login_usersave_code_isnull']),

		array("input"=>$_POST["agree"],			"require"=>"true", 		"message"=>$lang['login_usersave_you_must_agree'])

		);

		$error = $obj_validate->validate();

		if ($error != ''){

			showValidateError($error);

		}

		if (C('captcha_status_login')){

			$brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

			$brr[0] = strtolower($brr[0]);

			$_POST['captcha'] = strtolower($_POST['captcha']);

			if ($brr[0]!==$_POST['captcha']){

				showDialog($lang['login_usersave_wrong_code']);

			}

		}

		//检测用户名是否已经注册

		$check_member_name	= $model_member->infoMember(array('member_name'=>trim($_POST['user_name'])));

		if(is_array($check_member_name) and count($check_member_name)>0) {

			showDialog($lang['login_usersave_your_username_exists']);

		}

		//检测邮箱是已经注册

		$check_member_email	= $model_member->infoMember(array('member_email'=>trim($_POST['email'])));

		if(is_array($check_member_email) and count($check_member_email)>0) {

			showDialog($lang['login_usersave_your_email_exists']);

		}

		$user_array	= array();



		if(C('ucenter_status')) {

		

			$model_ucenter = Model('ucenter');

			$uid = $model_ucenter->addUser(trim($_POST['user_name']),trim($_POST['password']),trim($_POST['email']));

			if($uid<1) showMessage($lang['login_usersave_regist_fail'],'','html','error');

			$user_array['member_id']		= $uid;

		}



		$user_array['member_name']		= $_POST['user_name'];

		$user_array['member_passwd']	= $_POST['password'];

		$user_array['member_email']		= $_POST['email'];

		$result	= $model_member->addMember($user_array);

		if($result) {

			setNcCookie('rp_reg',time());



			$_SESSION['is_login']	= '1';

			$_SESSION['member_id']	= $result;

			$_SESSION['member_name']= trim($user_array['member_name']);

			$_SESSION['member_email']= trim($user_array['member_email']);



			$this->mergecart();	

		

			if ($GLOBALS['setting_config']['points_isuse'] == 1){

				$points_model = Model('points');

				$points_model->savePointsLog('regist',array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name']),false);

			}

			$_POST['ref_url']	= (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=home&op=member');



			//showDialog(str_replace('site_name',C('site_name'),$lang['login_usersave_regist_success_ajax']),$_POST['ref_url'],'succ','',3);



			//跳到第二步

			header('location:index.php?act=login&op=step2');

			

		} else {

			showDialog(Language::get('login_usersave_regist_fail'));

		}

	}

	



	public function step2Op($type = 'step2')

	{

		//register_shutdown_function("check_abort");

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		Tpl::output('type',$type);

		Tpl::showpage('register');

	}

	

	//保存个人注册第二部手机号码

	public function phonesaveOp(){

	

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		

		//$model_member->checkloginMember();

		

		$obj_validate = new Validate();

		$obj_validate->validateparam = array(

		array("input"=>$_POST["mob_phone"],		"require"=>"true",		"message"=>$lang['login_usersave_login_phonesave_phone_isnull']),

		array("input"=>$_POST["examine"],		"require"=>"true",		"message"=>$lang['login_usersave_password_isnull']),

		);

		$error = $obj_validate->validate();

		if ($error != ''){

			showValidateError($error);

		}

		

		//检测手机号是已经注册

		$check_member_phone	= $model_member->infoMember(array('mob_phone'=>trim($_POST['mob_phone'])));

		if(is_array($check_member_phone) and count($check_member_phone)>0) {

			showDialog($lang['login_usersave_your_email_exists']);

		}

		//跟新数据

		$user_array['mob_phone']		= $_POST['mob_phone'];

		$result	= $model_member->updateMember($user_array,$_SESSION['member_id']);



		if($result) {

		

			//header('location:index.php');

			//showDialog(str_replace('site_name',C('site_name'),$lang['login_usersave_regist_success_ajax']),'index.php','succ','',3);

			header('location:index.php?act=login&op=step5');

		}else {

			showDialog(Language::get('login_usersave_regist_fail'));

		}

	}

	

	//企业注册

	public function companysaveOp(){

		

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		

		//$model_member->checkloginMember();

		if(!empty($_POST))

		{

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["email"],		"require"=>"true",		"message"=>$lang['login_usersave_login_compnysave_email_isnull']),

			array("input"=>strtoupper($_POST["captcha"]),"require"=>(C('captcha_status_register') == '1' ? "true" : "false"),"message"=>$lang['login_usersave_code_isnull']),

			array("input"=>$_POST["agree"],			"require"=>"true", 		"message"=>$lang['login_usersave_you_must_agree'])

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}



			if (C('captcha_status_login')){

				$brr = explode( "\t", decrypt( cookie( "seccode".$_POST['nchash'] ), MD5_KEY ) );

				$brr[0] = strtolower($brr[0]);

				$_POST['captcha'] = strtolower($_POST['captcha']);

				if ($brr[0]!==$_POST['captcha']){

					showDialog($lang['login_usersave_wrong_code']);

				}

			}

			

			//检测邮箱是已经注册

			$check_member_email	= $model_member->infoMember(array('member_name'=>trim($_POST['email'])));

			if(is_array($check_member_email) and count($check_member_email)>0 and $check_member_email['is_done'] == 1) {

				showDialog($lang['login_usersave_your_email_exists']);

			}

			

			if(!empty($check_member_email['member_name']))

			{

				

				$_SESSION['member_id'] = $check_member_email['member_id'];

				header('location:index.php?act=login&op=step3');

				

			}else

			{

				//插入数据

				$user_array['member_email']	= $_POST['email'];

				//print_r($user_array);exit;

				$result	= $model_member->addCompany($user_array);

				$_SESSION['member_id'] = $result;

				if($result){

			

				//跳到第二步

				header('location:index.php?act=login&op=step3');

				//showDialog(str_replace('site_name',C('site_name'),$lang['login_usersave_regist_success_ajax']),'index.php','succ','',3);

			

				}else {

				showDialog(Language::get('login_usersave_regist_fail'));

				}

			}	

		}

		

		

	}

	//企业注册第二部

	public function step3Op($type = 'step3')

	{    



        //print_r($_SESSION['member_id']);exit;

        

		Language::read("home_login_register");

		$lang	= Language::getLangContent();



		$email = $_SESSION['com_email'];





		Tpl::output('email',$email);

		Tpl::output('type',$type);

		Tpl::showpage('register');

	}		



	public function company_password_saveOp()

	{

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_company = Model('member');



			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["company_password"],		"require"=>"true",		"message"=>$lang['login_usersave_password_isnull']),

			array("input"=>$_POST["company_password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$_POST["company_password"],"message"=>$lang['login_usersave_password_not_the_same']),

			);

			

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}

			/*

			//查询元素

			$param['id'] = $_SESSION['company_id'];

			$arr = $model_company->infoMember($param,'*');		

			*/

			//插入数据

			//updateMember($param,$company_id)

			$param['member_passwd'] = md5(trim($_POST['company_password']));

			$result	= $model_company->updateMember($param,$_SESSION['member_id']);



			if($result) {

			

				//跳到第三步

				header('location:index.php?act=login&op=step4');

				//showDialog(str_replace('site_name',C('site_name'),$lang['login_usersave_regist_success_ajax']),'index.php','succ','',3);

			

			}else {

				showDialog(Language::get('login_usersave_regist_fail'));

			}



		

	}	

	

	public function company_info_saveOp()

	{

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		$model_company = Model('member');

		

		$obj_validate = new Validate();

		$obj_validate->validateparam = array(

		array("input"=>$_POST["company_name"],"require"=>"true","message"=>'企业名称不能为空'),

		array("input"=>$_POST["register_num"],"require"=>"true", "message"=>'营业执照注册号不能为空'),

		array("input"=>$_POST["member_areainfo"],"require"=>"true","message"=>'营业执照所在地不能为空'),

		array("input"=>$_POST["operation_term"],"require"=>"true","message"=>'营业期限不能为空'),

		array("input"=>$_POST["company_address"],"require"=>"true","message"=>'常用地址不能为空'),

		array("input"=>$_POST["mob_phone"],"require"=>"true",	"message"=>'联系电话不能为空'),

		array("input"=>$_POST["company_license"],"require"=>"true",	"message"=>'营业执照副本扫描件不能为空'),

		array("input"=>$_POST["organization_code"],"require"=>"true","message"=>'组织机构代码不能为空'),

		array("input"=>$_POST["company_range"],"require"=>"true","message"=>'营业范围不能为空'),

		array("input"=>$_POST["registered_capital"],"require"=>"true","message"=>'注册资金不能为空'),

		array("input"=>$_POST["company_fax"],"require"=>"true","message"=>'传真不能为空'),

		array("input"=>strtoupper($_POST["captcha"]),"require"=>(C('captcha_status_register') == '1' ? "true" : "false"),"message"=>$lang['login_usersave_code_isnull']),

		);

		

		$error = $obj_validate->validate();

		if ($error != ''){

			showValidateError($error);

		}



		//插入数据

		//updateMember($param,$company_id)

		unset($_POST['captcha']);

		$_POST['is_done'] = 1;

		if(!empty($_POST['company_info_save']))

		{

			@unlink('upload/company/'.$_POST['company_info_save']);

		}

		$result	= $model_company->updateMember($_POST,$_SESSION['member_id']);



		

		$company_info = $model_company->infoMember(array('member_id'=>$_SESSION['member_id']),'*');

		

		/**

		 * 写入session

		

		$_SESSION['is_company_login']	= '1';

		$_SESSION['is_seller']	= intval($company_info['store_id']) == 0 ? '' : 1;

		$_SESSION['company_id']	= $company_info['company_id'];

		$_SESSION['company_name']= $company_info['company_name'];

		$_SESSION['company_email']= $company_info['company_email'];

		*/

		

		if($result) {

		

			//跳到第三步

			header('location:index.php?act=login&op=step5');

			//showDialog(str_replace('site_name',C('site_name'),$lang['login_usersave_regist_success_ajax']),'index.php','succ','',3);

		

		}else {

			showDialog(Language::get('login_usersave_regist_fail'));

		}

		

	}

	

	//企业注册第三部

	public function step4Op($type = 'step4')

	{

		



		Language::read("home_login_register");

		$lang	= Language::getLangContent();

		

		Tpl::output('type',$type);

		Tpl::showpage('register');

	}	

	//企业注册成功

	public function step5Op($type = 'step5')

	{

		Language::read("home_login_register");

		$lang	= Language::getLangContent();

			

		

		Tpl::output('type',$type);

		Tpl::showpage('register');

	}

	

	//检测是否已有用户名注册

	public function check_memberOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_GET['user_name']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}

		} else {

		

			$model_member	= Model('member');



			$check_member_name	= $model_member->infoMember(array('member_name'=>trim($_GET['user_name'])));

			if(is_array($check_member_name) and count($check_member_name)>0) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}
    
    
    //检测是否已手机用户名注册

	public function check_membermobileOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_GET['mobile']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}

		} else {

		

			$model_member	= Model('member');



			$check_member_name	= $model_member->infoMember(array('member_name'=>trim($_GET['mobile'])));

			if(is_array($check_member_name) and count($check_member_name)>0) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}		

	//检测是否已企业名注册

	public function check_companyOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_GET['user_name']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}

		} else {

		

			$model_member	= Model('member');



			$check_member_name	= $model_member->infoMember(array('company_name'=>trim($_GET['company_name'])));

			if(is_array($check_member_name) and count($check_member_name)>0) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}		

	//检测是否已有营业注册号注册

	public function check_register_numOp() {

		

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_GET['register_num']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}

		} else {

		      

			$model_member	= Model('member');



			$check_member_name	= $model_member->infoMember(array('register_num'=>trim($_GET['register_num'])));

			

			if(is_array($check_member_name) and count($check_member_name)>0) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}		

	//检测是否已有组织机构代码注册

	public function check_organization_codeOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_GET['user_name']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}

		} else {

		

			$model_member	= Model('member');



			$check_member_name	= $model_member->infoMember(array('organization_code'=>trim($_GET['organization_code'])));

			if(is_array($check_member_name) and count($check_member_name)>0) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}	

	//检测是否已有手机号注册

	public function check_mob_phoneOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkUserExit(trim($_GET['mob_phone']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}

		} else {

		

			$model_member	= Model('member');

			$check_member_mob_phone	= $model_member->infoMember(array('member_mob_phone'=>trim($_GET['mob_phone'])));

			if(is_array($check_member_mob_phone) and count($check_member_mob_phone)>0) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}

	//会员购物车

	private function mergecart(){

		if (cookie('cart') && $_SESSION['member_id']){

			$cart_str = cookie('cart');

			if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);

			$cookie_cart = unserialize($cart_str);

			if (!empty($cookie_cart)){

				$model_cart	= Model('cart');

				$cart_goods_arr	= $model_cart->listCart();				

				$cart_goodsspecid_arr = array();

				if(!empty($cart_goods_arr)) {

					foreach ($cart_goods_arr as $v){

						$cart_goodsspecid_arr[] = $v['spec_id'];

					}

				}

				foreach ($cookie_cart as $k=>$v){

					if (is_array($cart_goodsspecid_arr) && in_array($k,$cart_goodsspecid_arr)){

						unset($cookie_cart[$k]);

					}

				}

				unset($cart_goodsspecid_arr);

				unset($cart_goods_arr);

				

				if (!empty($cookie_cart)){

					$mode_goods= Model('goods');

					$cookie_cart_goods = $mode_goods->getGoods(array('no_store_id'=>"{$_SESSION['store_id']}",'goods_state'=>'0','goods_show'=>'1','spec_storage_enough'=>'yes','spec_id_in'=>"'".implode("','",array_keys($cookie_cart))."'"),'',"goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.spec_open,goods_spec.*","groupbuy_goods_info");

					if (!empty($cookie_cart_goods)){

						foreach ($cookie_cart_goods as $k=>$v){

							$insert_cart = array();

							$insert_cart						= array();

							$insert_cart['member_id']			= $_SESSION['member_id'];

							$insert_cart['store_id']			= $v['store_id'];

							$insert_cart['goods_id']			= $v['goods_id'];

							$insert_cart['goods_name']			= $v['goods_name'];

							$insert_cart['spec_id']				= $v['spec_id'];

							$insert_cart['spec_info'] = '';

							if ($v['spec_open'] == 1 && !empty($v['spec_goods_spec']) && !empty($v['spec_name'])){

								$spec_name = unserialize($v['spec_name']);

								if (!empty($spec_name)){

									$spec_name = array_values($spec_name);

									$spec_goods_spec = unserialize($v['spec_goods_spec']);

									$i = 0;

									foreach ($spec_goods_spec as $k=>$specv){

										$insert_cart['spec_info'] .= $spec_name[$i].":".$specv."&nbsp;";

										$i++;

									}

								}

							}

							$insert_cart['goods_store_price']	= $v['spec_goods_price'];

							$insert_cart['goods_num']			= intval($cookie_cart[$v['spec_id']]['num']);

							if ($insert_cart['goods_num'] > $v['spec_goods_storage']){

								$insert_cart['goods_num'] = $v['spec_goods_storage'];

							}

							$insert_cart['goods_images']		= $v['goods_image'];

							$model_cart->addCart($insert_cart);

						}

					}

				}

			}

			setNcCookie('cart','',-3600);

			setNcCookie('goodsnum','',-3600);		

		}

	}	

	//企业购物车

	private function company_mergecart(){

		if (cookie('cart') && $_SESSION['company_id']){

			$cart_str = cookie('cart');

			if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);

			$cookie_cart = unserialize($cart_str);

			if (!empty($cookie_cart)){

				$model_cart	= Model('cart');

				$cart_goods_arr	= $model_cart->listCart();				

				$cart_goodsspecid_arr = array();

				if(!empty($cart_goods_arr)) {

					foreach ($cart_goods_arr as $v){

						$cart_goodsspecid_arr[] = $v['spec_id'];

					}

				}

				foreach ($cookie_cart as $k=>$v){

					if (is_array($cart_goodsspecid_arr) && in_array($k,$cart_goodsspecid_arr)){

						unset($cookie_cart[$k]);

					}

				}

				unset($cart_goodsspecid_arr);

				unset($cart_goods_arr);

				

				if (!empty($cookie_cart)){

					$mode_goods= Model('goods');

					$cookie_cart_goods = $mode_goods->getGoods(array('no_store_id'=>"{$_SESSION['store_id']}",'goods_state'=>'0','goods_show'=>'1','spec_storage_enough'=>'yes','spec_id_in'=>"'".implode("','",array_keys($cookie_cart))."'"),'',"goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.spec_open,goods_spec.*","groupbuy_goods_info");

					if (!empty($cookie_cart_goods)){

						foreach ($cookie_cart_goods as $k=>$v){

							$insert_cart = array();

							$insert_cart						= array();

							$insert_cart['company_id']			= $_SESSION['company_id'];

							$insert_cart['store_id']			= $v['store_id'];

							$insert_cart['goods_id']			= $v['goods_id'];

							$insert_cart['goods_name']			= $v['goods_name'];

							$insert_cart['spec_id']				= $v['spec_id'];

							$insert_cart['spec_info'] = '';

							if ($v['spec_open'] == 1 && !empty($v['spec_goods_spec']) && !empty($v['spec_name'])){

								$spec_name = unserialize($v['spec_name']);

								if (!empty($spec_name)){

									$spec_name = array_values($spec_name);

									$spec_goods_spec = unserialize($v['spec_goods_spec']);

									$i = 0;

									foreach ($spec_goods_spec as $k=>$specv){

										$insert_cart['spec_info'] .= $spec_name[$i].":".$specv."&nbsp;";

										$i++;

									}

								}

							}

							$insert_cart['goods_store_price']	= $v['spec_goods_price'];

							$insert_cart['goods_num']			= intval($cookie_cart[$v['spec_id']]['num']);

							if ($insert_cart['goods_num'] > $v['spec_goods_storage']){

								$insert_cart['goods_num'] = $v['spec_goods_storage'];

							}

							$insert_cart['goods_images']		= $v['goods_image'];

							$model_cart->addCart($insert_cart);

						}

					}

				}

			}

			setNcCookie('cart','',-3600);

			setNcCookie('goodsnum','',-3600);		

		}

	}
    

	//检测是否已有个人邮箱注册

	public function check_emailOp() {

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkEmailExit(trim($_GET['email']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}



		} else {

		

			$model_member	= Model('member');



			$check_member_email	= $model_member->infoMember(array('member_email'=>trim($_GET['email'])));

			$company_email = $model_member->infoMember(array('member_name'=>trim($_GET['email'])));

			if((is_array($check_member_email) and count($check_member_email)>0)||(!empty($company_email['member_name']))) {

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}	

	//检测是否已有企业邮箱注册

	public function check_company_emailOp(){

		if(C('ucenter_status')) {

			

			$model_ucenter = Model('ucenter');

			$result = $model_ucenter->checkEmailExit(trim($_GET['email']));

			if($result == 1) {

				echo 'true';

			} else {

				echo 'false';

			}



		} else {

		

			$model_member	= Model('member');



			$check_member_email	= $model_member->infoMember(array('member_name'=>trim($_GET['email'])));

			$member_email = $model_member->infoMember(array('member_email'=>trim($_GET['email'])));

			//if(!empty($check_member_email) and $check_member_email['member_name']!='' ){

			if((!empty($check_member_email) and $check_member_email['member_name']!='' )||(!empty($member_email))){		

				echo 'false';

			} else {

				echo 'true';

			}

		}

	}

	

	public function forget_passwordOp(){

		

		Language::read('home_login_register');

		$_pic = @unserialize(C('login_pic'));

		if ($_pic[0] != ''){

			Tpl::output('lpic',SiteUrl.'/'.ATTACH_PATH.'/login/'.$_pic[array_rand($_pic)]);

		}else{

			Tpl::output('lpic',SiteUrl.'/'.ATTACH_PATH.'/login/'.rand(1,4).'.jpg');

		}

		Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

		Tpl::output('html_title',C('site_name').' - '.Language::get('login_index_find_password'));

		Tpl::showpage('find_password2');

	}

	//忘记密码第一步

	public function find_password1Op(){

		if($_SERVER['REQUEST_METHOD']!='POST'){

			header('Location:'.SiteUrl.'/index.php?act=login&op=forget_password');

			exit;

		}

		 //读取语言包

		Language::read('home_login_register');

		$lang	= Language::getLangContent();

		 //表单合法性验证

		if($_POST['form_submit']!='ok'){

			showMessage($lang['login_password_enter_find'],'index.php?act=login&op=forget_password');

		}

		 //验证码验证

		if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

			showMessage($lang['login_usersave_wrong_code'],'','html','error');

		}

		 //邮箱验证

		if(empty($_POST['email'])){

			showMessage($lang['login_password_input_email'],'','html','error');

		}

		$member_model	= Model('member');

		$member	= $member_model->infoMember(array('member_email'=>$_POST['email']));

		if(empty($member) or !is_array($member)){

			showMessage($lang['login_password_username_not_exists'],'','html','error');

		}

		if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){

			showMessage($lang['login_password_email_not_exists'],'','html','error');

		}

		

		$_SESSION['mid'] = $member['member_id'];

		Tpl::showpage('find_password2');

	}

	//忘记密码第二步

	public function find_password2Op(){
        //读取语言包
        Language::read('home_login_register');
        $lang = Language::getLangContent();
		$checkMode = $_POST['checkMode'];

		$code = $_POST['mobile_num'];
                
        $member_email = $_POST['email'];
               
        $member_val = empty($member_email) ? $code : $member_email;
        if(empty($member_val)) {
            showMessage("请正确操作",'','html','error');
        }
        $member_model	= Model('member');
        //获取会员信息
		$member	= $member_model->infoMember(array('member_val'=>$member_val));

		if(empty($member) or !is_array($member)){

            showMessage($lang['login_password_username_not_exists'],'','html','error');

		}

        $_SESSION['mid'] = $member['member_id'];

		if($_SERVER['REQUEST_METHOD']!='POST' || empty($_SESSION['mid']) || empty($checkMode)){

			header('Location:'.SiteUrl.'/index.php?act=login&op=forget_password');

			exit;

		}

		if($checkMode=='2' && empty($code)){

			header('Location:'.SiteUrl.'/index.php?act=login&op=forget_password');

			exit;

		}


		$member_model	= Model('member');

		$member	= $member_model->infoMember(array('member_id'=>$_SESSION['mid']));

        //产生密码

        $new_password	= rand(100000,999999);

		if($checkMode== 1){//用户选择邮箱验证时的操作

			if(!($member_model->updateMember(array('member_passwd'=>md5($new_password)),$member['member_id']))){

				showMessage($lang['login_password_email_fail'],'','html','error');

			}
		

			 //发送邮件
                     
                        
			$result	= $this->send_notice($member['member_id'],'email_touser_find_password',array(

			'site_name'	=> $GLOBALS['setting_config']['site_name'],

			'site_url'	=> SiteUrl.'/index.php?act=login&op=find_password3&pwd='.md5($new_password),

			'user_name'	=> $member['member_name'],

			'new_password'	=> $new_password

			),false);



			if($result){

				$_SESSION['pwd'] = md5($new_password);

				if(C('ucenter_status')) {

					//Ucenter处理

					$model_ucenter = Model('ucenter');

					$model_ucenter->userEdit(array('login_name'=>$_POST['username'],'','password'=>trim($new_password)));

				}

				showMessage($lang['login_password_email_success'],SiteUrl);

			}else{

				showMessage($lang['login_password_email_fail'],'','html','error');

			}

		}else{//用户选择手机验证时的操作

//检测手机验证码是否正确


            $model = Model('mobile_vertify');
            $res = $model->where(array('mobile_num'=>$_POST['mobile_num']))->order('id DESC')->select();

            $mobile_info = $res[0];

			//判断验证码是否过期

			if($mobile_info['dead_time'] < time()){

				showMessage($lang['login_register_mobile_vertify_fail'],'','html','error');

			}

			//判断输入的验证码是否正确

			if($mobile_info['num'] != trim($_POST["num"])){

				showMessage($lang['login_register_mobile_vertify_wrong'],'','html','error');

			}
            //更改密码(文档还没修改)
            if(!($member_model->updateMember(array('member_passwd'=>md5($new_password)),$member['member_id']))){

                showMessage($lang['login_password_email_fail'],'','html','error');

            }

            $res = $this->send($_POST['mobile_num'],$new_password);
            if ($res) {
                showMessage('找回密码成功，请留意短息',SiteUrl.'/index.php?act=login','html','error');
            }
            return;



			/*			if($_POST['code'] == $_SESSION['code']){

				$_SESSION['pwd'] = $member['member_passwd'];

				Tpl::output('pwd',$member['member_passwd']);

				Tpl::showpage('find_password3');

			}

*/		}

		

	}

	

	//忘记密码第三步

	public function find_password3Op(){
            
            
            

		if($_SERVER['REQUEST_METHOD']=='POST'){

			$pwd = $_POST['oldpassword'];

			$spwd = $_SESSION['pwd'];

			if(empty($pwd) || $pwd!=$spwd){

				header('Location:'.SiteUrl.'/index.php?act=login&op=forget_password');

				exit;

			}

			$password = $_POST['password'];

			$repassword = $_POST['repassword'];

			if(empty($password) || $password!=$repassword){

				header('Location:'.SiteUrl.'/index.php?act=login&op=forget_password');

				exit;

			}

			$member_model	= Model('member');

			$update	= $member_model->update(array('member_passwd'=>md5($password),'member_id'=>$_SESSION['mid']));

			if(C('ucenter_status')) {

				//Ucenter处理

				$member_info	= $model_member->where(array('member_id'=>$_SESSION['mid']))->find();

				$model_ucenter = Model('ucenter');

				$model_ucenter->userEdit(array('login_name'=>$member_info['member_name'],'','password'=>$password));

			}

			

			Tpl::showpage('find_successful');

		}else{

			$pwd = $_GET['pwd'];

			$spwd = $_SESSION['pwd'];

			if(empty($pwd) || $pwd!=$spwd){

				header('Location:'.SiteUrl.'/index.php?act=login&op=forget_password');

				exit;

			}

			Tpl::output('pwd',$pwd);

			Tpl::showpage('find_password3');

		}

	}

	

	public function find_passwordOp(){

	

		Language::read('home_login_register');

		$lang	= Language::getLangContent();

		

		if($_POST['form_submit']!='ok'){

			showMessage($lang['login_password_enter_find'],'index.php?act=login&op=forget_password');

		}

		

		if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){

			showMessage($lang['login_usersave_wrong_code'],'','html','error');

		}

		

		if(empty($_POST['username'])){

			showMessage($lang['login_password_input_username'],'','html','error');

		}

		$member_model	= Model('member');

		$member	= $member_model->infoMember(array('member_name'=>$_POST['username']));

		if(empty($member) or !is_array($member)){

			showMessage($lang['login_password_username_not_exists'],'','html','error');

		}

		

		if(empty($_POST['email'])){

			showMessage($lang['login_password_input_email'],'','html','error');

		}

		if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){

			showMessage($lang['login_password_email_not_exists'],'','html','error');

		}

		

		$new_password	= rand(100000,999999);

		if(!($member_model->updateMember(array('member_passwd'=>md5($new_password)),$member['member_id']))){

			showMessage($lang['login_password_email_fail'],'','html','error');

		}

		

		$result	= $this->send_notice($member['member_id'],'email_touser_find_password',array(

		'site_name'	=> $GLOBALS['setting_config']['site_name'],

		'site_url'	=> SiteUrl,

		'user_name'	=> $_POST['username'],

		'new_password'	=> $new_password

		),false);

		if($result){

			if(C('ucenter_status')) {

				

				$model_ucenter = Model('ucenter');

				$model_ucenter->userEdit(array('login_name'=>$_POST['username'],'','password'=>trim($new_password)));

			}

			showMessage($lang['login_password_email_success'],SiteUrl);

		}else{

			showMessage($lang['login_password_email_fail'],'','html','error');

		}

	}

	

	//企业营业执照上传

	public function uploadimgOp()

	{

		if(!empty($_FILES['file']['name']))

		{

			$file = $_FILES['file'];

			

			if($file['size'] > 2*1024*1024)

			{

				$msg = '图片大小超过2m';

				echo "<script>parent.error('".$msg."');</script>";

			}

			

			$arr = explode('|','image/jpeg|image/png|image/gif');

			if(!in_array($file['type'],$arr))

			{

				$msg = '上传文件类型不对';

				echo "<script>parent.error('".$msg."');</script>";

			}

			

			

			if(!file_exists('upload/company/'))

				mkdir('upload/company/','0777',true);

			

			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');

			//move_uploaded_file($file['tmp_name'],'upload/company/'.$newName);

			//print_r($newName);exit;

			//$newName = $_SESSION['member_id'].strchr($file['name'],'.');

			

			if(move_uploaded_file($file['tmp_name'],'upload/company/'.$newName))

			{

				

				$str = '/upload/company/'.$newName;

				echo "<script>parent.stopSend('".$str."');</script>";



			}else

			{

				$msg = '上传出错';

				echo "<script>parent.error('".$msg."');</script>";

			}

		}

		Tpl::showpage('index_upload');

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


    public function sendsmsnewOp()
    {
        $mobile = $_GET['mobile']; //手机号码
        $mobile_code = $this->xRandOp();//手机验证码
        $post_data = array();
        $post_data['userid'] = 83;
        $post_data['account'] = 'guangwu';
        $post_data['password'] = '123456';
        $post_data['content'] = '您的验证码是：'.$mobile_code.'，请勿把验证码泄露给其他人，如非本人操作，可不用理会！【海品乐购】'; //短信内容需要用urlencode编码下
        $post_data['mobile'] = $mobile;
        $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
        $url='http://113.11.210.114:5888/sms.aspx?action=send';
        $o='';
        foreach ($post_data as $k=>$v)
        {
            $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);
        $ispass=stripos($result, 'ok');
        if($ispass>0){
                $insert_array['mobile_num'] = $mobile;
                $insert_array['create_time'] = time();
                $insert_array['dead_time'] = time()+3600;//设五分钟间隔
                $insert_array['num'] = $mobile_code;
                DB::insert('mobile_vertify', $insert_array);
            echo 1;
        }else{
            echo 0;
        }
        die;
    }


	//发送短信

	public function sendSMSOp()

	{

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))

		{	

			$mobile = $_GET['mobile']; //手机号码



			$mobile_code = $this->xRandOp();//手机验证码



			

			$returnStr = "";

			$username="gdlt_tst";//用户名

			$pwd="zrp87215";//密码

			$proxy="octest";//代理商

			//$mobile要发送的手机号

			$content=$mobile_code.'【海品乐购】';//发送的内容

			// $mobile = '18675866207';



			$extno="";//扩展号

			$seqno="";//序列号

			$url="http://58.249.48.146:8080/ocservice/service/msgNormWebService?wsdl";



			//$result = include('Soap.class.php');

			$result = include(BasePath.'/framework/libraries/Soap.class.php');



			if(!$result){

				echo "<script>alert('加载错误');</script>";

				return false;

			}

			

			$data=array('in0'=>$username,'in1'=>$pwd,'in2'=>$proxy,'in3'=>$mobile,'in4'=>$content,'in5'=>$extno,'in6'=>$seqno);



			$result=Client::Init($url);



			$result = Client::sendGWMsg($data);







			$res=$result->out;

			$resarr=explode(":",$res);

			if($resarr[0]==1){

				$insert_array['mobile_num'] = $mobile;

				$insert_array['create_time'] = time();

				$insert_array['dead_time'] = time()+3600;//设五分钟间隔

				$insert_array['num'] = $mobile_code;

				DB::insert('mobile_vertify', $insert_array);

				

				echo 1;

				//return true;

			}else{

				

				echo 0;

				//return false;

			}

			

		}





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


    //发短信
    public function send($phone,$content)
    {
        $post_data = array();
        $post_data['userid'] = 83;
        $post_data['account'] = 'guangwu';
        $post_data['password'] = '123456';
        $post_data['content'] = '您的密码是：'.$content.'，请勿把密码泄露给其他人，如非本人操作，请联系客服！【海品乐购】';//短信内容需要用urlencode编码下
        $post_data['mobile'] = $phone;
        $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
        $url='http://113.11.210.114:5888/sms.aspx?action=send';
        $o='';
        foreach ($post_data as $k=>$v)
        {
            $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);
        $ispass=stripos($result, 'ok');
        if($ispass>0){
            $insert_array['message_parent_id'] = 0;
            $insert_array['to_member_id'] = $phone;
            $insert_array['message_time'] = time();
            $insert_array['message_title'] = '发送信息';
            $insert_array['message_body'] = '您的密码是：'.$content.'，请勿把密码泄露给其他人，如非本人操作，请联系客服！【海品乐购】';
            DB::insert('message', $insert_array);
            return 1;
        }else{
            return 0;
        }
    }


    public function testOp()
    {
        $mobile = '15915878173';
        $content = '123456';
        $this->send($mobile,$content);
    }

}

