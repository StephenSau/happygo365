<?php

defined('haipinlegou') or exit('Access Invalid!');
class member_sconnectControl extends BaseMemberControl {
	
	public function __construct() {		
		parent::__construct();
		
		Language::read('member_member_sconnect');
		if ($GLOBALS['setting_config']['sina_isuse'] != 1){
			showMessage(Language::get('member_sconnect_unavailable'),'index.php?act=member_snsindex','html','error');
		}
		Tpl::setLayout('member_pub_layout');
	}
	public function sinabindOp(){
		$model_member	= Model('member');
		$member_info	= $model_member->infoMember(array('member_id'=>$_SESSION['member_id']),'member_sinaopenid,member_sinainfo');
		if (trim($member_info['member_sinainfo'])){
			$member_info['member_sinainfoarr'] = unserialize($member_info['member_sinainfo']);
		}
		Tpl::output('member_info',$member_info);
		self::profile_menu('sina_bind');
		Tpl::output('menu_sign','sina_bind');
		Tpl::output('menu_sign_url','index.php?act=member_sconnect&op=sinabind');
		Tpl::output('menu_sign1','sina_bind');
		Tpl::showpage('member_sinabind');
	}
	public function unbindOp(){
		$model_member	= Model('member');
		$update_arr = array();
		if ($_POST['is_editpw'] == 'yes'){
			
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["new_password"],		"require"=>"true","validator"=>"Length","min"=>6,"max"=>20,"message"=>Language::get('member_sconnect_password_null')),
				array("input"=>$_POST["confirm_password"],	"require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>Language::get('member_sconnect_input_two_password_again')),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error,'','html','error');
			}
			$update_arr['member_passwd'] = md5(trim($_POST['new_password']));
		}
		$update_arr['member_sinaopenid'] = '';
		$update_arr['member_sinainfo'] = '';
		$edit_state		= $model_member->updateMember($update_arr,$_SESSION['member_id']);
		
		if($edit_state) {
			if($GLOBALS['setting_config']['ucenter_status'] == '1' && $_POST['is_editpw'] == 'yes') {
				
				$model_ucenter = Model('ucenter');
				$model_ucenter->userEdit(array('login_name'=>$_SESSION['member_name'],'old_password'=>trim($_POST['orig_password']),'password'=>trim($_POST['new_password'])));
			}
		} else {
			showMessage(Language::get('member_sconnect_password_modify_fail'),'','html','error');
		}
		session_unset();
		session_destroy();
		
		if($GLOBALS['setting_config']['ucenter_status'] == '1') {
			
			$model_ucenter = Model('ucenter');
			$out_str = $model_ucenter->userLogout();
		}
		showMessage(Language::get('member_sconnect_unbind_success'),'index.php?act=login&ref_url='.urlencode('index.php?act=member_sconnect&op=sinabind'));
	}
	
	private function profile_menu($menu_key='',$array=array()) {
		Language::read('member_layout');
		$lang	= Language::getLangContent();
		$menu_array		= array();
		$menu_array = array(
			1=>array('menu_key'=>'sina_bind',	'menu_name'=>$lang['nc_member_path_sina_bind'],	'menu_url'=>'index.php?act=member_sconnect&op=sina_bind'),
		);
		if(!empty($array)) {
			$menu_array[] = $array;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}