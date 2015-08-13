<?php



defined('haipinlegou') or exit('Access Invalid!');



class homeControl extends BaseMemberControl {

	

	public function indexOp() {

		$this->memberOp();	

	}

	

	public function memberOp() {



		Language::read('member_home_member');

		$lang	= Language::getLangContent();



		$model_member	= Model('member');



		if (chksubmit()){



			$member_array	= array();

			$member_array['member_truename']	= $_POST['member_truename'];

			$member_array['member_sex']			= $_POST['member_sex'];

			$member_array['member_qq']			= $_POST['member_qq'];

			$member_array['member_ww']			= $_POST['member_ww'];

			$member_array['member_areaid']		= $_POST['area_id'];

			$member_array['member_cityid']		= $_POST['city_id'];

			$member_array['member_provinceid']	= $_POST['province_id'];

			$member_array['member_areainfo']	= $_POST['area_info'];

			$member_array['member_mob_phone']	= $_POST['member_mob_phone'];

			//依照海关要求身份证号码最后一位是x时要转换成大写

			$last = substr($_POST['member_id_card'],-1);		

			if($last == 'x' || $last == 'X')

			{

				$_POST['member_id_card'] = strtoupper($_POST['member_id_card']); 

			}

			$member_array['member_id_card']	= $_POST['member_id_card'];

			$member_array['member_idcard']	= $_POST['member_idcard'];

			$member_array['member_idcard2']	= $_POST['member_idcard2'];

			$member_array['member_id']			= $_SESSION['member_id'];
			
			$member_array['examine']			= 1;
			

			if (strlen($_POST['birthday']) == 10){

				$member_array['member_birthday']	= $_POST['birthday'];

			}

			if(!empty($_POST['change']) && $_POST['change']== 1){
				$member_array['examine']	= 3;
			}

			//$update = $model_member->update($member_array);

			$update = $model_member->updateMember($member_array,$_SESSION['member_id']);

			$message = $update? $lang['nc_common_save_succ'] : $lang['nc_common_save_fail']; 

			//showDialog($message,'reload',$update ? 'succ' : 'error');

			showDialog($message,'index.php?act=home&op=member');

		}



		$member_info = $model_member->find($_SESSION['member_id']);

		Tpl::output('member_info',$member_info);

        

        //S脚部内容显示



		$list = $this->_article();



        //E脚部内容显示

        

		self::profile_menu('member','member');

		Tpl::output('menu_sign','profile');

		Tpl::output('menu_sign_url','index.php?act=home&op=member');

		Tpl::output('menu_sign1','baseinfo');

		Tpl::setLayout('member_pub_layout');
        
        //推广链接
        $uid=$_SESSION['member_id'];
        $popularize_msg=encrypt($uid,MD5_KEY);
        $popularize_url=SiteUrl."/index.php?act=login&op=register&popularize=".$popularize_msg."";
        
        Tpl::output('popularize_url',$popularize_url);
        
		Tpl::showpage('member_profile');

	}

	//检查身份证号码是否重复

	public function check_id_cardOp()

	{

		$model_member	= Model('member');

		if(!empty($_GET['member_id_card']))

		{

			//$member_id_card	= $model_member->infoMember(array('member_id_card'=>$_GET['member_id_card']));

			$member_id_card	= $model_member->where('member_id_card = '.$_GET['member_id_card'].'')->find();

			$member_info = $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));

	

			if(!empty($member_id_card))

			{

				if($member_id_card['member_id_card'] == $member_info['member_id_card'])

				{

					echo 0;exit;

				}else

				{

					echo 1;exit;

				}

			}else{



				echo 0;exit;

			}

		}

	}	

	//检查手机号码是否重复

	public function check_phoneOp()

	{

		$model_member	= Model('member');

		if(!empty($_GET['member_mob_phone']))

		{

			$member_mob_phone	= $model_member->where('member_mob_phone = '.$_GET['member_mob_phone'].'')->find();

			$member_info	= $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));

	

			if(!empty($member_mob_phone))

			{

				if($member_mob_phone['member_mob_phone'] == $member_info['member_mob_phone'])

				{

					echo 0;exit;

					

				}else{

				

					echo 1;exit;

				}

			}else{



				echo 0;exit;

			}

		}

	}	
	
	//检查邮箱是否重复

	public function check_emailOp()

	{

		$model_member	= Model('member');

		if(!empty($_GET['member_email']))

		{

			$member_email	= $model_member->where('member_email = '.$_GET['member_email'].'')->find();

			$member_info	= $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));

	

			if(!empty($member_email))

			{

				if($member_email['member_email'] == $member_info['member_email'])

				{

					echo 0;exit;

					

				}else{

				

					echo 1;exit;

				}

			}else{



				echo 0;exit;

			}

		}	}



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



	public function moreOp(){

		

		Language::read('member_home_member');

		

		$model = Model();

		

		if(chksubmit()){

			$model->table('sns_mtagmember')->where(array('member_id'=>$_SESSION['member_id']))->delete();

			if(!empty($_POST['mid'])){

				$insert_array = array();

				foreach ($_POST['mid'] as $val){

					$insert_array[] = array('mtag_id'=>$val,'member_id'=>$_SESSION['member_id']);

				}

				$model->table('sns_mtagmember')->insertAll($insert_array,'',true);

			}

			showDialog(Language::get('nc_common_op_succ'),'','succ');

		}

		

		$mtag_array = $model->table('sns_membertag')->order('mtag_sort asc')->select();

		

		$mtm_array = $model->table('sns_mtagmember')->where(array('member_id'=>$_SESSION['member_id']))->select();

		$mtag_list	= array();

		$mtm_list	= array();

		if(!empty($mtm_array) && is_array($mtm_array)){

			$elect_array = array();

			foreach($mtm_array as $val){

				$elect_array[]	= $val['mtag_id'];

			}

			foreach ((array)$mtag_array as $val){

				if(in_array($val['mtag_id'], $elect_array)){

					$mtm_list[] = $val;

				}else{

					$mtag_list[] = $val;

				}

			}

		}else{

			$mtag_list = $mtag_array;

		}



		//S脚部文章输出

		$list=$this->_article();

		//E脚部文章输出

		Tpl::output('mtag_list', $mtag_list);

		Tpl::output('mtm_list', $mtm_list);

		

		self::profile_menu('member','more');

		Tpl::output('menu_sign','profile');

		Tpl::output('menu_sign_url','index.php?act=home&op=member');

		Tpl::output('menu_sign1','baseinfo');

		Tpl::setLayout('member_pub_layout');

		Tpl::showpage('member_profile.more');

	}

	

	public function passwdOp() {

		

		Language::read('member_home_member');

		$lang	= Language::getLangContent();



		

		$model_member	= Model('member');

					

		if(chksubmit()) {

		

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["orig_password"],		"require"=>"true",		"message"=>$lang['home_member_old_password_null']),

			array("input"=>$_POST["new_password"],		"require"=>"true",		"message"=>$lang['home_member_new_password_null']),

			array("input"=>$_POST["confirm_password"],	"require"=>"true",		"validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>$lang['home_member_input_two_password_again']),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}



			$member_info	= $model_member->where(array('member_name'=>trim($_SESSION['member_name']),'member_passwd'=>md5(trim($_POST['orig_password']))))->find();

			if(empty($member_info)) {

				showDialog($lang['home_member_old_password_wrong']);

			}

			$update	= $model_member->update(array('member_passwd'=>md5(trim($_POST['new_password'])),'member_id'=>$_SESSION['member_id']));

			if($update) {

				if(C('ucenter_status')) {

					$model_ucenter = Model('ucenter');

					$model_ucenter->userEdit(array('login_name'=>$_SESSION['member_name'],'old_password'=>trim($_POST['orig_password']),'password'=>trim($_POST['new_password'])));

				}



				$message = $lang['nc_common_save_succ'];

			} else {

				$message = $lang['nc_common_save_fail'];

			}

			showDialog($message,'index.php?act=login&op=logout',$update ? 'succ' : 'error');

		}

		//S我的商场左侧头像显示

		$member_info = $model_member->find($_SESSION['member_id']);

		Tpl::output('member_info',$member_info);

		//E我的商场左侧头像显示



		//S脚部文章输出

		$list=$this->_article();

		//E脚部文章输出



		self::profile_menu('member','passwd');

		Tpl::output('menu_sign','profile');

		Tpl::output('menu_sign_url','index.php?act=home&op=member');

		Tpl::output('menu_sign1','passwd');

		Tpl::setLayout('member_pub_layout');

		Tpl::showpage('member_passwd');

	}

	

	public function emailOp() {

		

		Language::read('member_home_member');

		$lang	= Language::getLangContent();



		

		$model_member	= Model('member');

		if(chksubmit()) {

			

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

			array("input"=>$_POST["orig_password"],		"require"=>"true",		"message"=>$lang['home_member_password_null']),

			array("input"=>$_POST["email"],				"require"=>"true",		"validator"=>"email",	"message"=>$lang['home_member_input_email_again_format']),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}

					

			$email_array	= $model_member->getby_member_email(trim($_POST['email']));

			if(!empty($email_array) && is_array($email_array)) {

				showDialog($lang['home_member_input_email_again_exists']);

			}

			

			$member_info	= $model_member->where(array('member_name'=>trim($_SESSION['member_name']),'member_passwd'=>md5(trim($_POST['orig_password']))))->find();

			if(empty($member_info)) {

				showDialog($lang['home_member_input_password_again_wrong']);

			}

			

			$update	= $model_member->update(array('member_email'=>trim($_POST['email']),'member_id'=>$_SESSION['member_id']));

			if($update) {

				if(C('ucenter_status')) {

					$model_ucenter = Model('ucenter');

					$model_ucenter->userEdit(array('login_name'=>$_SESSION['member_name'],'old_password'=>trim($_POST['orig_password']),'email'=>trim($_POST['email'])));

				}

				$_SESSION['member_email'] = trim($_POST['email']);

				$message = $lang['nc_common_save_succ'];

			} else {

				$message = $lang['nc_common_save_fail'];

			}

			showDialog($message,'reload',$update ? 'succ' : 'error');

		}

		//S我的商场左侧头像显示

		$member_info = $model_member->find($_SESSION['member_id']);

		Tpl::output('member_info',$member_info);

		//E我的商场左侧头像显示



		//S脚部文章输出

		$list=$this->_article();

		//E脚部文章输出



		self::profile_menu('member','email');

		Tpl::output('menu_sign','profile');

		Tpl::output('menu_sign_url','index.php?act=home&op=member');

		Tpl::output('menu_sign1','email');

		Tpl::setLayout('member_pub_layout');

		Tpl::showpage('member_email');

	}

	

	public function messageOp() {

		Language::read('member_home_message');

		$model_message	= Model('message');

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

		$message_array	= $model_message->listMessage(array('message_type'=>'2','to_member_id_common'=>$_SESSION['member_id'],'no_message_state'=>'2'),$page);

		

		//S脚部文章输出

		$list=$this->_article();

		//E脚部文章输出

		Tpl::output('show_page',$page->show());

		Tpl::output('message_array',$message_array);

		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		Tpl::output('isallowsend',$isallowsend);

		Tpl::output('drop_type','msg_list');

		self::profile_menu('message','message');

		Tpl::output('menu_sign','message');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','receivemsg');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_box');

	}

	

	public function personalmsgOp() {

		Language::read('member_home_message');

		$model_message	= Model('message');

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

		$message_array	= $model_message->listMessage(array('message_type'=>'0','to_member_id_common'=>$_SESSION['member_id'],'no_message_state'=>'2'),$page);
		//脚部文章输出

		$list = $this->_article();
		Tpl::output('show_page',$page->show());

		Tpl::output('message_array',$message_array);

		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		Tpl::output('isallowsend',$isallowsend);

		Tpl::output('drop_type','msg_list');

		self::profile_menu('message','close');

		Tpl::output('menu_sign','message');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','close');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_box');

	}

	

	private function receivedCommonNewNum(){

		$model_message	= Model('message');

		$countnum = $model_message->countMessage(array('message_type'=>'2','to_member_id_common'=>$_SESSION['member_id'],'no_message_state'=>'2','message_open_common'=>'0'));

		return $countnum;

	}

	

	private function receivedSystemNewNum(){

		$message_model = Model('message');

		$condition_arr = array();

		$condition_arr['message_type'] = '1';

		$condition_arr['to_member_id'] = $_SESSION['member_id'];

		$condition_arr['no_del_member_id'] = $_SESSION['member_id'];

		$condition_arr['no_read_member_id'] = $_SESSION['member_id'];

		$countnum = $message_model->countMessage($condition_arr);

		return $countnum; 

	}

	

	private function receivedPersonalNewNum(){

		$model_message = Model('message');

		$countnum = $model_message->countMessage(array('message_type'=>'0','to_member_id_common'=>$_SESSION['member_id'],'no_message_state'=>'2','message_open_common'=>'0'));

		return $countnum; 

	}

	

	private function allowSendMessage($member_id){

        $member_model = Model('member');

        $member_id = intval($member_id);

        $member_info = $member_model->infoMember(array('member_id'=>"{$member_id}"));

        if ($member_info['is_allowtalk'] == '1'){

        	return true;

        }else{

        	return false;

        }

	}

	

	public function privatemsgOp(){

		Language::read('member_home_message');

		$model_message	= Model('message');

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

		$message_array	= $model_message->listMessage(array('message_type'=>'0','from_member_id'=>"{$_SESSION['member_id']}",'no_message_state'=>'1'),$page);
//脚部文章输出
		$list = $this->_article();
		Tpl::output('show_page',$page->show());

		Tpl::output('message_array',$message_array);

		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		Tpl::output('isallowsend',$isallowsend);

		Tpl::output('drop_type','msg_private');

		Tpl::output('menu_sign','message');

		self::profile_menu('message','private');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','privatemsg');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_sendlist');

	}

	

	public function systemmsgOp(){

		Language::read('member_home_message');

		$model_message	= Model('message');

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

		$message_array	= $model_message->listMessage(array('from_member_id'=>'0','message_type'=>'1','to_member_id'=>$_SESSION['member_id'],'no_del_member_id'=>$_SESSION['member_id']),$page);

		if (!empty($message_array) && is_array($message_array)){

			foreach ($message_array as $k=>$v){

				$v['message_open'] = '0';

				if (!empty($v['read_member_id'])){

					$tmp_readid_arr = explode(',',$v['read_member_id']);

					if (in_array($_SESSION['member_id'],$tmp_readid_arr)){

						$v['message_open'] = '1';

					}

				}

				$v['from_member_name'] = Language::get('home_message_system_message');

				$message_array[$k]	= $v;

			}

		}
//脚部文章输出
		$list = $this->_article();
		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);

		Tpl::output('show_page',$page->show());

		Tpl::output('message_array',$message_array);

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		Tpl::output('isallowsend',$isallowsend);

		Tpl::output('drop_type','msg_system');

		self::profile_menu('message','system');

		Tpl::output('menu_sign','message');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','systemmsg');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_box');

	}

	

	public function sendmsgOp(){

		Language::read('member_home_message');

		$referer_url = getReferer();

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		if (!$isallowsend){

			showMessage(Language::get('home_message_noallowsend'),$referer_url,'html','error');

		}

		$model_member	= Model('member');

		$member_name_string	= '';

		$member_id = intval($_GET['member_id']);

		if($member_id > 0) {

			$member_info	= $model_member->infoMember(array('member_id'=>$member_id));

			if (empty($member_info)){

				showMessage(Language::get('wrong_argument'),$referer_url,'html','error');

			}

			$member_name_string	= $member_info['member_name'];

			Tpl::output('member_name',$member_name_string);

		}else {

			$friend_model = Model('sns_friend');

			$friend_list = $friend_model->listFriend(array('friend_frommid'=>"{$_SESSION['member_id']}"));

			Tpl::output('friend_list',$friend_list);

		}

		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);

		self::profile_menu('message','sendmsg');

		Tpl::output('menu_sign','message');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','sendmsg');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_send');

	}

	

	public function savemsgOp() {

		Language::read('member_home_message');

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		if (!$isallowsend){

			showDialog(Language::get('home_message_noallowsend'));

		}

		if (chksubmit()) {

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

				array("input"=>$_POST["to_member_name"],"require"=>"true","message"=>Language::get('home_message_receiver_null')),

				array("input"=>$_POST["msg_content"],"require"=>"true","message"=>Language::get('home_message_content_null')),

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showDialog($error);

			}

			$msg_content = trim($_POST['msg_content']);

			$membername_arr = explode(',',$_POST['to_member_name']);

			if (empty($membername_arr)){

				showDialog(Language::get('home_message_receiver_null'));

			}

			if (in_array("{$_SESSION['member_name']}",$membername_arr)){

				unset($membername_arr[array_search("{$_SESSION['member_name']}",$membername_arr)]);

			}

			$membername_str = "'".implode("','",$membername_arr)."'";

			$member_model = Model('member');

			$member_list = $member_model->getMemberList(array('friend_list'=>$membername_str));

			if (!empty($member_list)){

				$model_message	= Model('message');

				foreach ($member_list as $k=>$v){

					$insert_arr = array();

					$insert_arr['from_member_id'] = $_SESSION['member_id'];

					$insert_arr['from_member_name'] = $_SESSION['member_name'];

					$insert_arr['member_id'] = $v['member_id'];

					$insert_arr['to_member_name'] = $v['member_name'];

					$insert_arr['msg_content'] = $msg_content;

					$insert_arr['message_type'] = intval($_POST['msg_type']);

					$return = $model_message->saveMessage($insert_arr);

				}

			}else {

				showDialog(Language::get('home_message_receiver_null'));

			}

			if($_GET['type'] == 'sns_board'){

				$insert_arr['msg_id']		= $return;

				$insert_arr['msg_content']	= parsesmiles($insert_arr['msg_content']);

				if (strtoupper(CHARSET) == 'GBK') {

					$insert_arr['msg_content'] = Language::getUTF8($insert_arr['msg_content']);

				}

				$data = json_encode($insert_arr);

				$js = "leaveMsgSuccess(".$data.")";

				showDialog(Language::get('home_message_send_success'),'','succ',$js);

			}else{

				showDialog(Language::get('home_message_send_success'),'index.php?act=home&op=privatemsg','succ');

			}

		}

	}

	

	public function showmsgcommonOp() {

		Language::read('member_home_message');

		$model_message	= Model('message');

		$message_id =  intval($_GET['message_id']);

		$drop_type = trim($_GET['drop_type']);

		$referer_url = getReferer();

		if(!in_array($drop_type,array('msg_list')) || $message_id<=0){

			showMessage(Language::get('wrong_argument'),$referer_url,'html','error');

		}

		$param = array();

		$param['message_id'] = "$message_id";

		$param['to_member_id_common'] = "{$_SESSION['member_id']}";

		$param['no_message_state'] = "2";

		$message_info = $model_message->getRowMessage($param);

		if (empty($message_info)){

			showMessage(Language::get('home_message_no_record'),$referer_url,'html','error');

		}

		unset($param);

		if ($message_info['message_parent_id'] >0){

			$parent_array	= $model_message->getRowMessage(array('message_id'=>"{$message_info['message_parent_id']}",'message_type'=>'0','no_message_state'=>'2'));

			$reply_array	= $model_message->listMessage(array('message_parent_id'=>"{$message_info['message_parent_id']}",'message_type'=>'0','no_message_state'=>'2'));


		}else {

			$parent_array = $message_info;

			$reply_array	= $model_message->listMessage(array('message_parent_id'=>"$message_id",'message_type'=>'0','no_message_state'=>'2'));

		}

		$message_list = array();

		if (!empty($reply_array)){

			foreach ($reply_array as $k=>$v){

				$message_list[$v['message_id']] = $v;
			}

		}

		if (!empty($parent_array)){

			$message_list[$parent_array['message_id']] = $parent_array;

		}

		unset($parent_array);

		unset($reply_array);

		$messageid_arr = array_keys($message_list);

		if (!empty($messageid_arr)){

			$messageid_str = "'".implode("','",$messageid_arr)."'";

			$model_message->updateCommonMessage(array('message_open'=>'1'),array('message_id_in'=>"$messageid_str"));

		}

		$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

		$countnum = $model_message->countNewMessage($_SESSION['member_id']);

		setNcCookie($cookie_name,$countnum,2*3600);

		Tpl::output('message_num',$countnum);

		//脚部文章输出
		$list = $this->_article();

		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);

		Tpl::output('message_id',$message_id);

		Tpl::output('message_list',$message_list);

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		Tpl::output('isallowsend',$isallowsend);

    	self::profile_menu('message','showmsg');

		Tpl::output('menu_sign','message');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','showmsg');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_view');

	}

	

	public function showmsgbatchOp() {

		Language::read('member_home_message');

		$model_message	= Model('message');

		$message_id =  intval($_GET['message_id']);

		$drop_type = trim($_GET['drop_type']);

		$referer_url = getReferer();

		if(!in_array($drop_type,array('msg_system','msg_seller')) || $message_id<=0){

			showMessage(Language::get('wrong_argument'),$referer_url,'html','error');

		}

		$param = array();

		$param['message_id'] = "$message_id";

		$param['to_member_id'] = "{$_SESSION['member_id']}";

		$param['no_del_member_id'] = "{$_SESSION['member_id']}";

		$message_info = $model_message->getRowMessage($param);

		if (empty($message_info)){

			showMessage(Language::get('home_message_no_record'),$referer_url,'html','error');

		}

		if ($drop_type == 'msg_system'){

			$message_info['from_member_name'] =  Language::get('home_message_system_message');

		}

		if ($drop_type == 'msg_seller'){

			$model_store = Model('store');

			$store_info = $model_store->shopStore(array('member_id'=>"{$message_info['from_member_id']}"));

			$message_info['from_member_name'] =  $store_info['store_name'];

			$message_info['store_id'] =  $store_info['store_id'];

		}

		$message_list[0] = $message_info;

		Tpl::output('message_list',$message_list);

		$tmp_readid_str = '';

		if (!empty($message_info['read_member_id'])){

			$tmp_readid_arr = explode(',',$message_info['read_member_id']);

			if (!in_array($_SESSION['member_id'],$tmp_readid_arr)){

				$tmp_readid_arr[] = $_SESSION['member_id'];

			}

			foreach ($tmp_readid_arr as $readid_k=>$readid_v){

				if ($readid_v == ''){

					unset($tmp_readid_arr[$readid_k]);

				}

			}

			$tmp_readid_arr = array_unique ($tmp_readid_arr);

			sort($tmp_readid_arr);

			$tmp_readid_str = ",".implode(',',$tmp_readid_arr).",";

		}else {

			$tmp_readid_str = ",{$_SESSION['member_id']},";

		}

		$model_message->updateCommonMessage(array('read_member_id'=>$tmp_readid_str),array('message_id'=>"{$message_id}"));

		$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

		$countnum = $model_message->countNewMessage($_SESSION['member_id']);

		setNcCookie($cookie_name,$countnum,2*3600);

		Tpl::output('message_num',$countnum);

		$newcommon = $this->receivedCommonNewNum();

		Tpl::output('newcommon',$newcommon);

		$newsystem = $this->receivedSystemNewNum();

		Tpl::output('newsystem',$newsystem);

		$newpersonal = $this->receivedPersonalNewNum();

		Tpl::output('newpersonal',$newpersonal);	

		Tpl::output('drop_type',$drop_type);	

    	self::profile_menu('message','showmsg');

		Tpl::output('menu_sign','message');

		Tpl::output('menu_sign_url','index.php?act=home&op=message');

		Tpl::output('menu_sign1','showmsg');

		$this->get_member_info();

		Tpl::output('header_menu_sign','message');

		Tpl::showpage('message_view');

	}

	

	public function savereplyOp() {

		Language::read('member_home_message');

		$isallowsend = $this->allowSendMessage($_SESSION['member_id']);

		if (!$isallowsend){

			if($_GET['inajax'] == 1){

				showDialog(Language::get('home_message_noallowsend'));

			}else{

				showMessage(Language::get('home_message_noallowsend'),'index.php?act=home&op=message','html','error');

			}

		}

		if ($_POST['form_submit'] == 'ok') {

			$message_id = intval($_POST["message_id"]);

			if ($message_id <=0){

				showMessage(Language::get('wrong_argument'),'index.php?act=home&op=message','html','error');

			}

			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

				array("input"=>$_POST["msg_content"],"require"=>"true","message"=>Language::get('home_message_reply_content_null'))

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				if($_GET['inajax'] == 1){

					showDialog(Language::get('error'));

				}else{

					showMessage(Language::get('error').$error,'','html','error');

				}

			}

			$model_message	= Model('message');

			$param = array();

			$param['message_id'] = "$message_id";

			$param['no_message_state'] = "2";

			$message_info = $model_message->getRowMessage($param);

			if (empty($message_info)){

				if($_GET['inajax'] == 1){

					showDialog(Language::get('home_message_no_record'));

				}else{

					showMessage(Language::get('home_message_no_record').$error,'','html','error');

				}

			}

			if ($message_info['from_member_id'] == $_SESSION['member_id']){

				showMessage(Language::get('home_message_no_record'),'','html','error');

			}

			$insert_arr = array();

			if ($message_info['message_parent_id'] > 0){

				$insert_arr['message_parent_id'] = $message_info['message_parent_id'];

			}else {

				$insert_arr['message_parent_id'] = $message_info['message_id'];

			}

			$insert_arr['from_member_id'] = $_SESSION['member_id'];

			$insert_arr['from_member_name'] = $_SESSION['member_name'];

			$insert_arr['member_id'] = $message_info['from_member_id'];

			$insert_arr['to_member_name'] = $message_info['from_member_name'];

			$insert_arr['msg_content'] = $_POST['msg_content'];

			$insert_state = $model_message->saveMessage($insert_arr);

			if ($insert_state){

				$update_arr = array();

				$update_arr['message_update_time'] = time();

				$update_arr['message_open'] = 1;

				$model_message->updateCommonMessage($update_arr,array('message_id'=>"{$insert_arr['message_parent_id']}"));

			}

			if($_GET['inajax'] == 1){

				$insert_arr['msg_id']		= $insert_state;

				if(strtoupper(CHARSET) == 'GBK'){

					$insert_arr['msg_content'] = Language::getUTF8($insert_arr['msg_content']);

				}

				$insert_arr['msg_content']	= parsesmiles($insert_arr['msg_content']);

				$data = json_encode($insert_arr);

				$js = "replyMsgSuccess(".$data.")";

				showDialog(Language::get('home_message_send_success'),'','succ',$js);

			}else{

				showMessage(Language::get('home_message_send_success'),'index.php?act=home&op=privatemsg');

			}

		}else {

			if($_GET['inajax'] == 1){

				showDialog(Language::get('home_message_reply_command_wrong'));

			}else{

				showMessage(Language::get('home_message_reply_command_wrong'),'','html','error');

			}

		}

	}

	

	public function dropcommonmsgOp() {
		Language::read('member_home_message');
		$message_id = trim($_GET['message_id']);
		$drop_type = trim($_GET['drop_type']);
		if(!in_array($drop_type,array('msg_private','msg_list','sns_msg')) || empty($message_id)) {
			showMessage(Language::get('wrong_argument'),'','html','error');
		}
		$messageid_arr = explode(',',$message_id);
		$messageid_str = '';
		if (!empty($messageid_arr)){
			$messageid_str = "'".implode("','",$messageid_arr)."'";
		}
		$model_message	= Model('message');
		$param	= array('message_id_in'=>$messageid_str);
		if($drop_type == 'msg_private'){
			$param['from_member_id'] = "{$_SESSION['member_id']}";
		}elseif($drop_type == 'msg_list'){
			$param['to_member_id_common']	= "{$_SESSION['member_id']}";
		}elseif($drop_type == 'sns_msg'){
			$param['from_to_member_id']	= $_SESSION['member_id'];
		}
		$drop_state	= $model_message->dropCommonMessage($param,$drop_type);
		if ($drop_state){
			//更新未读站内信数量cookie值
			$cookie_name = 'msgnewnum'.$_SESSION['member_id'];
			$countnum = $model_message->countNewMessage($_SESSION['member_id']);
			setNcCookie($cookie_name,$countnum,2*3600);//保存2小时
			showDialog(Language::get('home_message_delete_success'),'reload','succ');
		}else {
			showDialog(Language::get('home_message_delete_fail'),'','error');
		}
	}

	

	public function dropbatchmsgOp() {

		Language::read('member_home_message');

		$message_id = trim($_GET['message_id']);

		$drop_type = trim($_GET['drop_type']);

		if(!in_array($drop_type,array('msg_system','msg_seller')) || empty($message_id)){

			showDialog(Language::get('home_message_delete_request_wrong'));

		}

		$messageid_arr = explode(',',$message_id);

		$messageid_str = '';

		if (!empty($messageid_arr)){

			$messageid_str = "'".implode("','",$messageid_arr)."'";

		}

		$model_message	= Model('message');

		$param	= array('message_id_in'=>$messageid_str);

		if($drop_type == 'msg_system'){

			$param['message_type'] = '1';

			$param['from_member_id'] = '0';

		}

		if($drop_type == 'msg_seller'){

			$param['message_type'] = '2';

		}

		$drop_state	= $model_message->dropBatchMessage($param,$_SESSION['member_id']);

		if ($drop_state){

			

			$cookie_name = 'msgnewnum'.$_SESSION['member_id'];

			$countnum = $model_message->countNewMessage($_SESSION['member_id']);

			setNcCookie($cookie_name,$countnum,2*3600);

			showDialog(Language::get('home_message_delete_success'),'reload','succ');

		}else {

			showDialog(Language::get('home_message_delete_fail'),'','error');

		}

	}

	

	public function avatarOp() {

		

		Language::read('member_home_member');

		$lang	= Language::getLangContent();

		

		$model_member	= Model('member');

		$member_id = $_SESSION['member_id'];

		

		if ($_GET['avatar'] == '1'){

			$member_array['member_avatar'] 	= "avatar_$member_id.jpg";

			$member_array['member_id']		=  $member_id;

			$avatarfile = BasePath.DS."upload".DS."avatar".DS.$member_array['member_avatar'];

			if (file_exists($avatarfile)) $model_member->update($member_array);

			

		}

		$member_info	= $model_member->getby_member_id($member_id);

		

		

		require_once(BasePath.DS."resource".DS."avatar".DS."index.php");



		//S脚部文章输出

		$list=$this->_article();

		//E脚部文章输出

		$avatarflash = getavatarflash($member_id);

		Tpl::output('avatarflash',$avatarflash);

		Tpl::output('member_info',$member_info);

		self::profile_menu('member','avatar');

		Tpl::output('menu_sign','profile');

		Tpl::output('menu_sign_url','index.php?act=home&op=member');

		Tpl::output('menu_sign1','avatar');

		Tpl::setLayout('member_pub_layout');

		Tpl::showpage('member_profile.avatar');

	}

	

	public function store_reapplyOp(){

		Model()->table('store')->update(array('store_id'=>$_SESSION['store_id'], 'store_state'=>2));

		showDialog('申请成功', 'reload', 'succ');

	}

	

	private function profile_menu($menu_type,$menu_key='') {

		$menu_array		= array();

		switch ($menu_type) {

			case 'member':

				$menu_array	= array(

				1=>array('menu_key'=>'member',	'menu_name'=>Language::get('home_member_base_infomation'),'menu_url'=>'index.php?act=home&op=member'),

				2=>array('menu_key'=>'more',	'menu_name'=>Language::get('home_member_more'),'menu_url'=>'index.php?act=home&op=more'),

				3=>array('menu_key'=>'passwd',	'menu_name'=>Language::get('home_member_modify_password'),'menu_url'=>'index.php?act=home&op=passwd'),

				4=>array('menu_key'=>'email',	'menu_name'=>Language::get('home_member_modify_email'),'menu_url'=>'index.php?act=home&op=email'),

				5=>array('menu_key'=>'avatar',	'menu_name'=>Language::get('home_member_modify_avatar'),'menu_url'=>'index.php?act=home&op=avatar'));

				break;

			case 'message':

				$menu_array	= array(

				1=>array('menu_key'=>'message',	'menu_name'=>Language::get('home_message_received_message'),'menu_url'=>'index.php?act=home&op=message'),

				2=>array('menu_key'=>'private',	'menu_name'=>Language::get('home_message_private_message'),	'menu_url'=>'index.php?act=home&op=privatemsg'),

				3=>array('menu_key'=>'system',	'menu_name'=>Language::get('home_message_system_message'),	'menu_url'=>'index.php?act=home&op=systemmsg'),

				4=>array('menu_key'=>'close',	'menu_name'=>Language::get('home_message_close'),	'menu_url'=>'index.php?act=home&op=personalmsg'),

				);

				if($menu_key == 'sendmsg') {

					$menu_array[5] = array('menu_key'=>'sendmsg','menu_name'=>Language::get('home_message_send_message'),'menu_url'=>'index.php?act=home&op=sendmsg');

				}elseif($menu_key == 'showmsg') {

					$menu_array[5] = array('menu_key'=>'showmsg','menu_name'=>Language::get('home_message_view_message'),'menu_url'=>'#');

				}

				break;

		}

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

	}

}

