<?php

defined('haipinlegou') or exit('Access Invalid!');

class member_sharemanageControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
		Language::read('member_sharemanage');
		if (C('share_isuse') != 1){
			showMessage(Language::get('sharebind_unused'),'index.php?act=home&op=member','html','error');
		}
		Tpl::setLayout('member_pub_layout');
	}
	
	public function indexOp(){
		$model = Model('sns_binding');
		$app_arr = $model->getUsableApp($_SESSION['member_id']);
		Tpl::output('app_arr',$app_arr);
		Tpl::output('menu_sign','sharemanage');
		Tpl::showpage('member_sharebind');
	}
	
	public function unbindOp(){
		$type = $_GET['type'];
		if (empty($type)){
			showDialog(Language::get('wrong_argument'),'index.php?act=member_sharemanage','error');
		}
		$model = Model('sns_binding');
		$app_arr = $model->getApps();
		$appkey_arr = array_keys($app_arr); 
		if (empty($appkey_arr) || !in_array($type,$appkey_arr)){
			showDialog(Language::get('wrong_argument'),'index.php?act=member_sharemanage','error');
		}
		$where_arr = array();
		$where_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$where_arr['snsbind_appsign'] = $type;
		$result = $model->table('sns_binding')->where($where_arr)->delete();
		if ($result){
			$js = "location.reload();";
			showDialog(Language::get('sharebind_unbind_succ'),'','succ',$js);
		}else {
			showDialog(Language::get('sharebind_unbind_fail'),'','succ');
		}
	}
	
	public function checkbindOp(){
		$app_key = $_GET['k'];
        $result = '';
        if (empty($app_key)){
            $result = json_encode(array('done'=>false,'msg'=>Language::get('sharebind_bind_fail')));
        } else {
            $model = Model('sns_binding');
            $bind_info = $model->getUsableOneApp($_SESSION['member_id'],$app_key);
            if (empty($bind_info)){
                $result = json_encode(array('done'=>false,'msg'=>Language::get('sharebind_bind_fail'))); 
            }else {
                $result = json_encode(array('done'=>true));
            }
        }
        if (isset($_GET['callback']))   
        {  
            $result = $_GET['callback']=='?' ? '('.$result.')' : $_GET['callback']."($result);";  
        }  
        echo $result;
        exit;
    }
	
	public function share_qqzoneOp(){
		if (C('share_qqzone_isuse') != 1){
			showMessage(Language::get('sharebind_unused'),'index.php?act=member_sharemanage','html','error');
		}
		include_once 'api/snsapi/qqzone/oauth/qq_callback.php';
		if (!$_SESSION['qqzone']){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
			echo "<script>window.close();</script>";
			exit;
		}
		$update_arr = array();
		$update_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$update_arr['snsbind_membername'] = $_SESSION['member_name'];
		$update_arr['snsbind_appsign'] = 'qqzone';
		$update_arr['snsbind_updatetime'] = time();
		$update_arr['snsbind_accesstoken'] = $_SESSION['qqzone']['access_token'];
		$update_arr['snsbind_expiresin'] = $_SESSION['qqzone']['expires_in'];
		$update_arr['snsbind_openid'] = $_SESSION['qqzone']['openid'];
		require_once (BasePath.DS.'api'.DS.'snsapi'.DS.'qqzone'.DS.'user'.DS.'get_user_info.php');
		$qquser_info = get_user_info();
		$update_arr['snsbind_openinfo'] = $qquser_info['nickname'];
		
		$model = Model();
		$where_arr = array();
		$where_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$where_arr['snsbind_appsign'] = 'qqzone';
		$bind_info = $model->table('sns_binding')->where($where_arr)->find();
		if (empty($bind_info)){
			$result = $model->table('sns_binding')->insert($update_arr);
		}else {
			$result = $model->table('sns_binding')->where($where_arr)->update($update_arr);
		}
		if (!$result){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
		}
		echo "<script>window.close();</script>";
		exit;
	}
	
	public function share_sinaweiboOp(){
		if (C('share_sinaweibo_isuse') != 1){
			showMessage(Language::get('sharebind_unused'),'index.php?act=member_sharemanage','html','error');
		}
		include_once 'api/snsapi/sinaweibo/callback.php';
		if (!$_SESSION['slast_key']){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
			echo "<script>window.close();</script>";
			exit;
		}
		$update_arr = array();
		$update_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$update_arr['snsbind_membername'] = $_SESSION['member_name'];
		$update_arr['snsbind_appsign'] = 'sinaweibo';
		$update_arr['snsbind_updatetime'] = time();
		$update_arr['snsbind_accesstoken'] = $_SESSION['slast_key']['access_token'];
		$update_arr['snsbind_expiresin'] = $_SESSION['slast_key']['expires_in'];		
		$update_arr['snsbind_openid'] = $_SESSION['slast_key']['uid'];
		require_once (BasePath.DS.'api'.DS.'snsapi'.DS.'sinaweibo'.DS.'saetv2.ex.class.php');
		$c = new SaeTClientV2( C('sina_wb_akey'), C('sina_wb_skey') , $_SESSION['slast_key']['access_token']);
		$sinauser_info = $c->show_user_by_id($_SESSION['slast_key']['uid']);
		$update_arr['snsbind_openinfo'] = $sinauser_info['name'];
		
		$model = Model();
		$where_arr = array();
		$where_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$where_arr['snsbind_appsign'] = 'sinaweibo';
		$bind_info = $model->table('sns_binding')->where($where_arr)->find();
		if (empty($bind_info)){
			$result = $model->table('sns_binding')->insert($update_arr);
		}else {
			$result = $model->table('sns_binding')->where($where_arr)->update($update_arr);
		}
		if (!$result){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
		}
		echo "<script>window.close();</script>";
		exit;
	}
	
	public function share_qqweiboOp(){
		if (C('share_qqweibo_isuse') != 1){
			showMessage(Language::get('sharebind_unused'),'index.php?act=member_sharemanage','html','error');
		}
		include_once 'api/snsapi/qqweibo/callback.php';
		if (!$_SESSION['qqweibo']){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
			echo "<script>window.close();</script>";
			exit;
		}
		$update_arr = array();
		$update_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$update_arr['snsbind_membername'] = $_SESSION['member_name'];
		$update_arr['snsbind_appsign'] = 'qqweibo';
		$update_arr['snsbind_updatetime'] = time();
		$update_arr['snsbind_accesstoken'] = $_SESSION['qqweibo']['t_access_token'];
		$update_arr['snsbind_expiresin'] = $_SESSION['qqweibo']['t_expire_in'];
		$update_arr['snsbind_refreshtoken'] = $_SESSION['qqweibo']['t_refresh_token'];
		$update_arr['snsbind_openid'] = $_SESSION['qqweibo']['t_openid'];
		$update_arr['snsbind_openinfo'] = $_SESSION['qqweibo']['t_uname'];
		
		$model = Model();
		$where_arr = array();
		$where_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$where_arr['snsbind_appsign'] = 'qqweibo';
		$bind_info = $model->table('sns_binding')->where($where_arr)->find();
		if (empty($bind_info)){
			$result = $model->table('sns_binding')->insert($update_arr);
		}else {
			$result = $model->table('sns_binding')->where($where_arr)->update($update_arr);
		}
		if (!$result){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
		}
		echo "<script>window.close();</script>";
		exit;
	}
	
	public function share_renrenOp(){
		if (C('share_renren_isuse') != 1){
			showMessage(Language::get('sharebind_unused'),'index.php?act=member_sharemanage','html','error');
		}
		include_once 'api/snsapi/renren/callback.php';
		if (!$_SESSION['renren']){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
			echo "<script>window.close();</script>";
			exit;
		}
		$update_arr = array();
		$update_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$update_arr['snsbind_membername'] = $_SESSION['member_name'];
		$update_arr['snsbind_appsign'] = 'renren';
		$update_arr['snsbind_updatetime'] = time();
		$update_arr['snsbind_accesstoken'] = $_SESSION['renren']['access_token'];
		$update_arr['snsbind_expiresin'] = $_SESSION['renren']['expires_in'];
		$update_arr['snsbind_refreshtoken'] = $_SESSION['renren']['refresh_token'];
		$update_arr['snsbind_openid'] = $_SESSION['renren']["uid"];
		$update_arr['snsbind_openinfo'] = $_SESSION['renren']["uname"];
		
		$model = Model();
		$where_arr = array();
		$where_arr['snsbind_memberid'] = $_SESSION['member_id'];
		$where_arr['snsbind_appsign'] = 'renren';
		$bind_info = $model->table('sns_binding')->where($where_arr)->find();
		if (empty($bind_info)){
			$result = $model->table('sns_binding')->insert($update_arr);
		}else {
			$result = $model->table('sns_binding')->where($where_arr)->update($update_arr);
		}
		if (!$result){
			echo "<script>alert('".Language::get('sharebind_bind_fail')."');</script>";
		}
		echo "<script>window.close();</script>";
		exit;
	}
}
