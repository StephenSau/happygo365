<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_ztcControl extends BaseMemberStoreControl {
	public function __construct() {
		parent::__construct();
		
		if ($GLOBALS['setting_config']['gold_isuse'] != 1 || $GLOBALS['setting_config']['ztc_isuse'] != 1){
			showMessage(Language::get('store_ztc_unavailable'),'index.php?act=store','html','error');
		}
	}
	public function indexOp(){
		$this->ztc_listOp();
	}
	
	public function ztc_listOp() {
		$condition_arr = array();
		$condition_arr['ztc_memberid'] = $_SESSION['member_id'];
		$s_goodsname = trim($_GET['zg_name']);
		if ($s_goodsname){
			$condition_arr['ztc_goodsname'] = $s_goodsname;			
		}
		if ($_GET['zg_state']){
			$condition_arr['ztc_state'] = intval($_GET['zg_state'])-1;			
		}
		if ($_GET['zg_paystate']){
			$condition_arr['ztc_paystate'] = intval($_GET['zg_paystate'])-1;			
		}
		if ($_GET['zg_type']){
			$condition_arr['ztc_type'] = intval($_GET['zg_type'])-1;			
		}
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$ztc_model = Model('ztc');
		$ztc_list = $ztc_model->getZtcList($condition_arr,$page);
		if(!empty($ztc_list) && is_array($ztc_list)){
			foreach($ztc_list  as $key => $val){
				$val['goods_image'] = $val['ztc_goodsimage'];
				$val['goods_id'] = $val['ztc_goodsid'];
				$val['store_id'] = $val['ztc_storeid'];
				$ztc_list[$key] = $val;
			}
		}
		self::profile_menu('ztc_list');
		Tpl::output('show_page',$page->show());
		Tpl::output('ztc_list',$ztc_list);
		Tpl::output('menu_sign','store_ztc');
		Tpl::output('menu_sign_url','index.php?act=store_ztc&op=ztc_list');
		Tpl::output('menu_sign1','ztc_list');
		Tpl::showpage('store_ztc.index');
	}
	
	public function ztc_addOp() {
		$member_model = Model('member');
		$member_array = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
		
		if (chksubmit()){
			$starttime = strtotime($_POST['ztc_stime']);
			$obj_validate = new Validate();
			$validate_arr[] = array("input"=>$_POST["goods_id"], "require"=>"true",'validator'=>'Compare',"operator"=>' > ','to'=>1,"message"=>Language::get('store_ztc_add_search_goodserror')); 
			$validate_arr[] = array("input"=>$_POST["ztc_type"], "require"=>"true","message"=>Language::get('store_ztc_add_audittype_nullerror'));
			$validate_arr[] = array("input"=>$_POST["ztc_goldnum"],"require"=>"true",'validator'=>'Range','min'=>1,'max'=>$member_array['member_goldnum'],"message"=>Language::get('store_ztc_add_goldnum_error'));
			$validate_arr[] = array("input"=>$_POST["ztc_remark"],"validator"=>"Length","max"=>100,"message"=>Language::get('store_ztc_add_remarkerror'));
			if (!$_POST['ztc_type'] == 1){
				$validate_arr[] = array("input"=>$starttime,"require"=>"true","validator"=>"Compare","operator"=>' > ','to'=>time(),"message"=>Language::get('store_ztc_add_starttime_error'));
			}
			$obj_validate -> validateparam = $validate_arr;
			$error = $obj_validate->validate();			
			if ($error != ''){
				showValidateError($error);
			}
			$goods_id = intval($_POST['goods_id']);
			$goods_model = Model('goods');
			$goods_info = $goods_model->checkGoods(array('goods_id'=>$goods_id));	
			if (!is_array($goods_info) || count($goods_info)<=0){
				showDialog(Language::get('store_ztc_add_search_goodserror'));
			}
			$ztc_model = Model('ztc');
			if ($_POST['ztc_type'] == 1){
				$ztc_dayprod = intval($GLOBALS['setting_config']['ztc_dayprod']);
				$datetime = date('Y-m-d',time());
				$datetime = strtotime($datetime);
				$goldall = ((($datetime - $goods_info['goods_ztclastdate'])/(3600*24)))*intval($ztc_dayprod);
				if ($goods_info['goods_isztc'] == 0 || !($goods_info['goods_isztc'] == 1 && $goods_info['goods_goldnum'] >= $goldall)){
					showDialog(Language::get('store_ztc_add_recharge_goodserror'));
				}
			}else{
				if ($goods_info['goods_isztc'] == 1){
					showDialog(Language::get('store_ztc_add_newaudit_goodserror'));
				}
				$ztc_info = $ztc_model->getZtcInfo(array('ztc_goodsid'=>$goods_id,'ztc_state'=>0,'ztc_type'=>0));
				if (is_array($ztc_info) && count($ztc_info)>0){
					showDialog(Language::get('store_ztc_add_newaudit_recordexist'));
				}
			}
			$ztc_array	= array();
			$ztc_array['ztc_goodsid']	= intval($_POST['goods_id']);
			$ztc_array['ztc_goodsname']	= $goods_info['goods_name'];
			$ztc_array['ztc_goodsimage']= $goods_info['goods_image'];
			$ztc_array['ztc_memberid']	= $_SESSION['member_id'];
			$ztc_array['ztc_membername']= $_SESSION['member_name'];
			$ztc_array['ztc_storeid']	= $_SESSION['store_id'];
			$ztc_array['ztc_storename']	= $_SESSION['store_name'];
			$ztc_array['ztc_gold']		= $_POST['ztc_goldnum'];
			$ztc_array['ztc_remark']	= $_POST['ztc_remark'];
			$ztc_array['ztc_startdate']	= strtotime($_POST['ztc_stime']);
			$ztc_array['ztc_addtime']	= time();
			if ($_POST['ztc_type'] == 1 && $_POST['ztc_paystate'] == 1){
				$ztc_array['ztc_state']	= 1;
			}else{
				$ztc_array['ztc_state']	= 0;
			}
			$ztc_array['ztc_paystate']	= $_POST['ztc_paystate'];			
			$ztc_array['ztc_type']	= $_POST['ztc_type'];
			
			$result = $ztc_model->addZtcGoods($ztc_array);
			if ($result){
				if ($ztc_array['ztc_paystate'] == 1){
					$newmember_goldnum = intval($member_array['member_goldnum']) - intval($_POST['ztc_goldnum']);
					$newmember_goldnumminus = intval($member_array['member_goldnumminus']) + intval($_POST['ztc_goldnum']);
					$member_model->updateMember(array('member_goldnum'=>$newmember_goldnum,'member_goldnumminus'=>$newmember_goldnumminus),$_SESSION['member_id']);
					$goldlog_model = Model('gold_log');
					$insert_goldlog = array();
					$insert_goldlog['glog_memberid'] = $_SESSION['member_id'];
					$insert_goldlog['glog_membername'] = $_SESSION['member_name'];
					$insert_goldlog['glog_storeid'] = $_SESSION['store_id'];
					$insert_goldlog['glog_storename'] = $_SESSION['store_name'];
					$insert_goldlog['glog_adminid'] = 0;
					$insert_goldlog['glog_adminname'] = '';
					$insert_goldlog['glog_goldnum'] = $_POST['ztc_goldnum'];
					$insert_goldlog['glog_method'] = 2;
					$insert_goldlog['glog_addtime'] = time();
					$insert_goldlog['glog_desc'] = Language::get('store_ztc_goldlog_minusgold');
					$insert_goldlog['glog_stage'] = 'ztc';
					$goldlog_model->add($insert_goldlog);
					if ($ztc_array['ztc_type'] == 1){
						$newgoods_goldnum = intval($goods_info['goods_goldnum']) + intval($_POST['ztc_goldnum']);
						$g_up_result = $goods_model->updateGoodsAllUser(array('goods_goldnum'=>$newgoods_goldnum),$goods_id);
						if ($g_up_result){
							$ztcgoldlog_model = Model('ztc_glodlog');
							$logarr = array();
							$logarr['glog_goodsid'] = $goods_id;
							$logarr['glog_goodsname'] = $goods_info['goods_name'];
							$logarr['glog_memberid'] = $_SESSION['member_id'];
							$logarr['glog_membername'] = $_SESSION['member_name'];
							$logarr['glog_storeid'] = $_SESSION['store_id'];
							$logarr['glog_storename'] = $_SESSION['store_name'];
							$logarr['glog_type'] = 1;
							$logarr['glog_goldnum'] = intval($_POST['ztc_goldnum']);
							$logarr['glog_addtime'] = time();
							$logarr['glog_desc'] = Language::get('store_ztc_ztclog_addgold');
							$ztcgoldlog_model->addlog($logarr);
						}
					}
				}
				showDialog(Language::get('store_ztc_add_success'),'index.php?act=store_ztc&op=ztc_list','succ');
			}else{
				showDialog(Language::get('store_ztc_add_fail'));
			}
		}else {
			Tpl::output('member_array',$member_array);
			Tpl::output('nowdate',date('Y-m-d',time()));
			self::profile_menu('ztc_add');
			Tpl::output('menu_sign','store_ztc');
			Tpl::output('menu_sign_url','index.php?act=store_ztc&op=ztc_add');
			Tpl::output('menu_sign1','ztc_add');
			Tpl::showpage('store_ztc.form');
		}
	}
	
	public function getselectgoodsOp(){
		
		Language::read('member_store_ztc');
		$lang	= Language::getLangContent();
		
		$select_string = '';
		$stc_class = Model('store_goods_class');
		$stc_tree = $stc_class->getStcTreeList($_SESSION['store_id']);
		if(!empty($stc_tree) && is_array($stc_tree)){
			foreach($stc_tree as $stc){
				$select_string .= '<option value="'.$stc['stc_id'].'">'.$stc['stc_name'].'</option>';
			}
		}
		$t = intval($_GET['t']);
		if (!in_array($t,array(0,1))){
			$t = 0;
		}
		Tpl::output('select_string',$select_string);
		Tpl::output('title',$lang['store_ztc_add_choose_goods']);
		Tpl::output('ztc_type',$t);
		Tpl::showpage('store_ztc.sgoods','null_layout');
	}
	
	public function getselectgoodslistOp(){
		$goods_class = Model('goods');
		$stc_id = intval($_GET['stc_id']);
		$goods_name = trim($_GET['stc_goods_name']);
		$condition_array = array();
		$condition_array['keyword'] = $goods_name;
		$condition_array['store_id'] = $_SESSION['store_id'];
		$t = intval($_GET['t']);
		if (!in_array($t,array(0,1))){
			$t = 0;
		}
		if ($t == 1){
			$condition_array['goods_isztc'] = 1;
		}else {
			$condition_array['goods_isztc'] = 0;
		}
		
		if ($stc_id){
			$model_store_class = Model('my_goods_class');
			$stc_id_arr = $model_store_class->getChildAndSelfClass($stc_id);
			if (is_array($stc_id_arr) && count($stc_id_arr)>0){
				$condition_array['stc_id_in'] = implode(',',$stc_id_arr);
			}else{
				$condition_array['stc_id'] = $stc_id_arr;
			}
		}
		$condition_array['limit'] = 50;
		$condition_array['order'] = 'goods.goods_id desc';
		$goods_list = $goods_class->getGoods($condition_array,'','`goods`.goods_id,`goods`.goods_name','stc');
		
		if (is_array($goods_list)){
			$data = array(
				'done'=>1,
				'length'=>count($goods_list),
				'retval'=>$goods_list
			);
		}else {
			$data = array(
				'done'=>1,
				'length'=>0,
				'retval'=>array()
			);
		}
		
		if (strtoupper(CHARSET) == 'GBK'){
			$data = Language::getUTF8($data);
		}
		echo json_encode($data);
	}
	
	public function ztc_payOp(){
		$zid = intval($_GET['zid']);
		if ($zid <= 0){
			showMessage(Language::get('store_ztc_parameter_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		$ztc_model = Model('ztc');
		$ztc_info = $ztc_model->getZtcInfo(array('ztc_id'=>$zid));
		if (!is_array($ztc_info) && count($ztc_info)<=0){
			showMessage(Language::get('store_ztc_record_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		$member_model = Model('member');
		$member_array = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
		if (!is_array($member_array) || count($member_array)<=0){
			showMessage(Language::get('store_ztc_userrecord_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		if ($ztc_info['ztc_memberid'] != $_SESSION['member_id']){
			showMessage(Language::get('store_ztc_operate_yourself_error'),'index.php?act=store_ztc&op=ztc_list','html','error');			
		}
		if ($ztc_info['ztc_state'] != 0){
			showMessage(Language::get('store_ztc_pay_reviewed_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		$goods_model = Model('goods');
		$goods_info = $goods_model->checkGoods(array('goods_id'=>$ztc_info['ztc_goodsid']));			
		if (!is_array($goods_info) || count($goods_info)<=0){
			showMessage(Language::get('store_ztc_goodsrecord_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		
		$up_ztcArr = array();
		$up_ztcArr['ztc_paystate'] = 1;
		if ($ztc_info['ztc_paystate'] == 1){
			$up_ztcArr['ztc_paystate'] = 0;
		}else {
			$up_ztcArr['ztc_paystate'] = 1;
			if (intval($member_array['member_goldnum'])< intval($ztc_info['ztc_gold'])){
				showMessage(Language::get('store_ztc_pay_goldnum_maxerror'),'index.php?act=store_ztc&op=ztc_list','html','error');
			}
		}
		if ($ztc_info['ztc_type'] == 1 && $up_ztcArr['ztc_paystate'] == 1){
			$up_ztcArr['ztc_state'] = 1;
		}
		$result = $ztc_model->updateZtcOne($up_ztcArr,array('ztc_id'=>$zid));
		if ($result){
			if ($up_ztcArr['ztc_paystate'] == 1){
				$newmember_goldnum = intval($member_array['member_goldnum']) - intval($ztc_info['ztc_gold']);
				$newmember_goldnumminus = intval($member_array['member_goldnumminus']) + intval($ztc_info['ztc_gold']);
				$member_model->updateMember(array('member_goldnum'=>$newmember_goldnum,'member_goldnumminus'=>$newmember_goldnumminus),$_SESSION['member_id']);
				$goldlog_model = Model('gold_log');
				$insert_goldlog = array();
				$insert_goldlog['glog_memberid'] = $_SESSION['member_id'];
				$insert_goldlog['glog_membername'] = $_SESSION['member_name'];
				$insert_goldlog['glog_storeid'] = $_SESSION['store_id'];
				$insert_goldlog['glog_storename'] = $_SESSION['store_name'];
				$insert_goldlog['glog_adminid'] = 0;
				$insert_goldlog['glog_adminname'] = '';
				$insert_goldlog['glog_goldnum'] = intval($ztc_info['ztc_gold']);
				$insert_goldlog['glog_method'] = 2;
				$insert_goldlog['glog_addtime'] = time();
				$insert_goldlog['glog_desc'] = Language::get('store_ztc_goldlog_minusgold');
				$insert_goldlog['glog_stage'] = 'ztc';
				$goldlog_model->add($insert_goldlog);
				if ($ztc_info['ztc_type'] == 1){
					$newgoods_goldnum = intval($goods_info['goods_goldnum']) + intval($ztc_info['ztc_gold']);
					$g_up_result = $goods_model->updateGoodsAllUser(array('goods_goldnum'=>$newgoods_goldnum),$ztc_info['ztc_goodsid']);
					if ($g_up_result){
						$ztcgoldlog_model = Model('ztc_glodlog');
						$logarr = array();
						$logarr['glog_goodsid'] = $ztc_info['ztc_goodsid'];
						$logarr['glog_goodsname'] = $ztc_info['ztc_goodsname'];
						$logarr['glog_memberid'] = $_SESSION['member_id'];
						$logarr['glog_membername'] = $_SESSION['member_name'];
						$logarr['glog_storeid'] = $_SESSION['store_id'];
						$logarr['glog_storename'] = $_SESSION['store_name'];
						$logarr['glog_type'] = 1;
						$logarr['glog_goldnum'] = intval($ztc_info['ztc_gold']);
						$logarr['glog_addtime'] = time();
						$logarr['glog_desc'] = Language::get('store_ztc_ztclog_addgold');
						$ztcgoldlog_model->addlog($logarr);
					}
				}
			}else {
				$newmember_goldnum = intval($member_array['member_goldnum']) + intval($ztc_info['ztc_gold']);
				$newmember_goldnumminus = intval($member_array['member_goldnumminus']) - intval($ztc_info['ztc_gold']);
				$member_model->updateMember(array('member_goldnum'=>$newmember_goldnum,'member_goldnumminus'=>$newmember_goldnumminus),$_SESSION['member_id']);
				$goldlog_model = Model('gold_log');
				$insert_goldlog = array();
				$insert_goldlog['glog_memberid'] = $_SESSION['member_id'];
				$insert_goldlog['glog_membername'] = $_SESSION['member_name'];
				$insert_goldlog['glog_storeid'] = $_SESSION['store_id'];
				$insert_goldlog['glog_storename'] = $_SESSION['store_name'];
				$insert_goldlog['glog_adminid'] = 0;
				$insert_goldlog['glog_adminname'] = '';				
				$insert_goldlog['glog_goldnum'] = intval($ztc_info['ztc_gold']);
				$insert_goldlog['glog_method'] = 1;
				$insert_goldlog['glog_addtime'] = time();
				$insert_goldlog['glog_desc'] = Language::get('store_ztc_goldlog_addgold');
				$insert_goldlog['glog_stage'] = 'ztc';
				$goldlog_model->add($insert_goldlog);
			}
			showMessage(Language::get('store_ztc_pay_update_success'),'index.php?act=store_ztc&op=ztc_list');
		}
	}
	
	public function drop_ztcOp(){
		$z_id = intval($_GET['z_id']);
		if (!$z_id){
			showDialog(Language::get('store_ztc_parameter_error'));
		}
		$ztc_model = Model('ztc');
		$ztc_info = $ztc_model->getZtcInfo(array('ztc_id'=>$z_id,'ztc_memberid'=>$_SESSION['member_id']));
		if (!is_array($ztc_info) || count($ztc_info)<=0){
			showDialog(Language::get('store_ztc_record_error'));
		}
		if ($ztc_info['ztc_state'] != 0){
			showDialog(Language::get('store_ztc_drop_reviewed_error'));
		}else {
			if ($ztc_info['ztc_paystate'] != 0){
				showDialog(Language::get('store_ztc_drop_paid_error'));
			}
		}
		$result = $ztc_model->dropZtcById($z_id);
		if($result) {
			showDialog(Language::get('store_ztc_drop_success'),'index.php?act=store_ztc&op=ztc_list');			
		} else {
			showDialog(Language::get('store_ztc_drop_fail'));
		}
	}
	
	public function dropall_ztcOp(){
		$z_id = $_GET['z_id'];
		$z_id = explode(',',$z_id);
		if (!$z_id || !is_array($z_id) || count($z_id)<=0){
			showDialog(Language::get('store_ztc_parameter_error'));
		}
		$z_id = "'".implode("','",$z_id)."'";
		$ztc_model = Model('ztc');
		$ztc_list = $ztc_model->getZtcList(array('ztc_id_in'=>$z_id,'ztc_memberid'=>$_SESSION['member_id']));
		if (!is_array($ztc_list) || count($ztc_list)<=0){
			showDialog(Language::get('store_ztc_record_error'));
		}
		$z_idnew = array();
		foreach ($ztc_list as $k=>$v){
			if ($v['ztc_state'] == 0 && $v['ztc_paystate'] == 0){
				$z_idnew[] = $v['ztc_id'];
			}
		}
		$result = true;
		if (is_array($z_idnew) && count($z_idnew)>0){
			$z_id = "'".implode("','",$z_idnew)."'";
			$result = $ztc_model->dropZtcByCondition(array('ztc_id_in_del'=>$z_id));
		}
		if($result) {
			showDialog(Language::get('store_ztc_drop_success'),'index.php?act=store_ztc&op=ztc_list');
		} else {
			showDialog(Language::get('store_ztc_drop_fail'));
		}
	}
	
	public function edit_ztcOp(){
		$z_id = intval($_GET['z_id']);
		if (!$z_id){
			showMessage(Language::get('store_ztc_parameter_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		$ztc_model = Model('ztc');
		$ztc_info = $ztc_model->getZtcInfo(array('ztc_id'=>$z_id,'ztc_memberid'=>$_SESSION['member_id']));
		if (!is_array($ztc_info) || count($ztc_info)<=0){
			showMessage(Language::get('store_ztc_record_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		if ($ztc_info['ztc_state'] != 0){
			showMessage(Language::get('store_ztc_edit_reviewed_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}else {
			if ($ztc_info['ztc_paystate'] != 0){
				showMessage(Language::get('store_ztc_edit_paid_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
			}
		}
		$member_model = Model('member');
		$member_array = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
		
		if (chksubmit()){
		
			$starttime = strtotime($_POST['ztc_stime']);
			$obj_validate = new Validate();
			$validate_arr[] = array("input"=>$_POST["goods_id"], "require"=>"true",'validator'=>'Compare',"operator"=>' > ','to'=>1,"message"=>Language::get('store_ztc_add_search_goodserror')); 
			$validate_arr[] = array("input"=>$_POST["ztc_goldnum"],"require"=>"true",'validator'=>'Range','min'=>1,'max'=>$member_array['member_goldnum'],"message"=>Language::get('store_ztc_add_goldnum_error'));
			$validate_arr[] = array("input"=>$_POST["ztc_remark"],"validator"=>"Length","max"=>100,"message"=>Language::get('store_ztc_add_remarkerror'));
			if (!$ztc_info['ztc_type'] == 1){
				$validate_arr[] = array("input"=>$starttime, "validator"=>"Compare","operator"=>' > ','to'=>time(),"message"=>Language::get('store_ztc_add_starttime_error'));
			}
			$obj_validate -> validateparam = $validate_arr;
			$error = $obj_validate->validate();			
			if ($error != ''){
				showValidateError($error);
			}
			$goods_id = intval($_POST['goods_id']);
			$goods_model = Model('goods');
			$goods_info = $goods_model->checkGoods(array('goods_id'=>$goods_id));			
			if (!is_array($goods_info) || count($goods_info)<=0){
				showDialog($lang['store_ztc_edit_search_goodserror']);
			}
			$ztc_model = Model('ztc');
			if ($ztc_info['ztc_type'] == 1){
				$ztc_dayprod = intval($GLOBALS['setting_config']['ztc_dayprod']);
				$datetime = date('Y-m-d',time());
				$datetime = strtotime($datetime);
				$goldall = ((($datetime - $goods_info['goods_ztclastdate'])/(3600*24)))*intval($ztc_dayprod);
				if ($goods_info['goods_isztc'] == 0 || !($goods_info['goods_isztc'] == 1 && $goods_info['goods_goldnum'] >= $goldall)){
					showDialog(Language::get('store_ztc_edit_recharge_goods_error'));
				}
			}else{
				if ($goods_info['goods_isztc'] == 1){
					showDialog(Language::get('store_ztc_edit_new_goods_error'));
				}
				$ztc_checkinfo = $ztc_model->getZtcInfo(array('ztc_goodsid'=>$goods_id,'ztc_state'=>0,'ztc_type'=>0));
				if ($ztc_checkinfo['ztc_id'] != $z_id && is_array($ztc_checkinfo) && count($ztc_checkinfo)>0){
					showDialog(Language::get('store_ztc_edit_new_goodsexist_error'));
				}
			}
			$ztc_array	= array();
			$ztc_array['ztc_goodsid']	= intval($_POST['goods_id']);
			$ztc_array['ztc_goodsname']	= $goods_info['goods_name'];
			$ztc_array['ztc_goodsimage']= $goods_info['goods_image'];
			$ztc_array['ztc_gold']		= $_POST['ztc_goldnum'];
			$ztc_array['ztc_remark']	= $_POST['ztc_remark'];
			$ztc_array['ztc_startdate']	= strtotime($_POST['ztc_stime']);
			$result = $ztc_model->updateZtcOne($ztc_array,array('ztc_id'=>$z_id));
			if($result) {
				showDialog(Language::get('store_ztc_edit_success'),'index.php?act=store_ztc&op=ztc_list','succ');
			} else {
				showDialog(Language::get('store_ztc_edit_fail'),'index.php?act=store_ztc&op=ztc_list');
			}
		} else {
			Tpl::output('member_array',$member_array);
			Tpl::output('ztc_info',$ztc_info);
			Tpl::output('nowdate',date('Y-m-d',time()));
			self::profile_menu('edit_ztc');
			Tpl::output('menu_sign','store_ztc');
			Tpl::output('menu_sign_url','index.php?act=store_ztc&op=edit_ztc');
			Tpl::output('menu_sign1','ztc_list');
			Tpl::showpage('store_ztc.editform');
		}
	}
	
	public function info_ztcOp(){
		$z_id = intval($_GET['z_id']);
		if (!$z_id){
			showMessage(Language::get('store_ztc_parameter_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}
		$ztc_model = Model('ztc');
		$ztc_info = $ztc_model->getZtcInfo(array('ztc_id'=>$z_id,'ztc_memberid'=>$_SESSION['member_id']));
		if (!is_array($ztc_info) || count($ztc_info)<=0){
			showMessage(Language::get('store_ztc_record_error'),'index.php?act=store_ztc&op=ztc_list','html','error');
		}

		Tpl::output('ztc_info',$ztc_info);
		self::profile_menu('ztc_list');
		Tpl::output('menu_sign','store_ztc');
		Tpl::output('menu_sign1','ztc_list');
		Tpl::showpage('store_ztc.info');
	}
	
	public function ztc_glistOp(){
		$ztc_model = Model('ztc');
		$ztc_model->updateZtcGoods(Language::get('store_ztc_glist_glog_desc'),$_SESSION['store_id']);
		
		$condition_arr = array();
		$state		= array('goods_open'=>array('goods_show'=>1),'goods_close'=>array('goods_show'=>'0'),'goods_commend'=>array('goods_commend'=>1),'goods_ban'=>array('goods_state'=>'0'));
		$condition_arr = $state[trim($_GET['goods_type'])];
		
		$condition_arr['store_id'] 		= $_SESSION['store_id'];
		$condition_arr['goods_isztc'] 	= '1';
		$condition_arr['keyword']		= trim($_GET['keyword']);
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$condition_arr['order'] = ' goods_goldnum ';
		$goods_model = Model('goods');
		$list_goods	= $goods_model->getGoods($condition_arr,$page,'*','goods');
		self::profile_menu('ztc_glist');
		Tpl::output('show_page',$page->show());
		Tpl::output('list_goods',$list_goods);
		Tpl::output('menu_sign','store_ztc');
		Tpl::output('menu_sign_url','index.php?act=store_ztc&op=ztc_glist');
		Tpl::output('menu_sign1','ztc_glist');
		Tpl::showpage('store_ztc.goodlist');
	}
	
	public function ztc_glogOp(){
		$condition_arr = array();
		$condition_arr['glog_storeid'] = "{$_SESSION['store_id']}";
		$condition_arr['glog_goodsname'] = trim($_GET['zg_name']);
		if ($_GET['zg_type']){
			$condition_arr['glog_type'] = $_GET['zg_type'];
		}
		$condition_arr['saddtime'] = strtotime($_GET['zg_stime']);
		$condition_arr['eaddtime'] = strtotime($_GET['zg_etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$ztcgoldlog_model = Model('ztc_glodlog');
		$list_log = $ztcgoldlog_model->getLogList($condition_arr,$page,'*','');
		self::profile_menu('ztc_glog');
		Tpl::output('show_page',$page->show());
		Tpl::output('list_log',$list_log);
		Tpl::output('menu_sign','store_ztc');
		Tpl::output('menu_sign_url','index.php?act=store_ztc&op=ztc_glog');
		Tpl::output('menu_sign1','ztc_glog');
		Tpl::showpage('store_ztc.glog');
	}
	
	public function ztc_gstateOp(){
		$id = 0;
		if (intval($_POST['gid']) > 0) $id = intval($_POST['gid']);		
		$goods_model = Model('goods');
		$condition_arr = array();
		$condition_arr['goods_id_in'] = $id;
		$condition_arr['store_id'] 		= $_SESSION['store_id'];
		$goods_list	= $goods_model->getGoods($condition_arr,'','*','goods');
		if(!empty($goods_list) && is_array($goods_list)) {
			$ztcstate= 2;
			if ($_POST['type'] == 'open'){
				$ztcstate= 1;
			}
			$id_new = array();
			foreach ($goods_list as $v){
				if ($v['goods_ztcstate'] != $ztcstate){
					$id_new[] = $v['goods_id'];
				}
			}
			if (is_array($id_new)){
				$up_arr = array();
				if ($ztcstate == 2){
					$ztc_model = Model('ztc');
					$ztc_model->updateZtcGoods(Language::get('store_ztc_glist_glog_desc'),$_SESSION['store_id'],$id_new);
					$up_arr['goods_ztcstate'] = 2;
				}else {
					$up_arr['goods_ztcstate'] = 1;
					$datetime = date('Y-m-d',time());
					$datetime = strtotime($datetime);
					$up_arr['goods_ztclastdate'] = $datetime;
				}
				$result = $goods_model->updateGoodsAllUser($up_arr,$id_new);
			}
		}
		if ($result) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
	
	private function profile_menu($menu_key='',$array=array()) {
		Language::read('member_layout');
		$lang	= Language::getLangContent();
		$menu_array		= array();
		$menu_array = array(
			1=>array('menu_key'=>'ztc_list',	'menu_name'=>$lang['nc_member_path_ztc_list'],	'menu_url'=>'index.php?act=store_ztc&op=ztc_list'),
			2=>array('menu_key'=>'ztc_glist',	'menu_name'=>$lang['nc_member_path_ztc_glist'],	'menu_url'=>'index.php?act=store_ztc&op=ztc_glist'),
			3=>array('menu_key'=>'ztc_glog',	'menu_name'=>$lang['nc_member_path_ztc_glog'],	'menu_url'=>'index.php?act=store_ztc&op=ztc_glog'),
		);
		if ($_GET['op'] == 'ztc_add'){
			$menu_array = array_merge($menu_array,array(4=>array('menu_key'=>'ztc_add',		'menu_name'=>$lang['nc_member_path_ztc_add'],	'menu_url'=>'index.php?act=store_ztc&op=ztc_add')));
		}elseif ($_GET['op'] == 'edit_ztc'){
			$menu_array = array_merge($menu_array,array(4=>array('menu_key'=>'edit_ztc',		'menu_name'=>$lang['store_ztc_index_edit_content'],	'menu_url'=>'index.php?act=store_ztc&op=edit_ztc&z_id='.intval($_GET['z_id']))));
		}

		if(!empty($array)) {
			$menu_array[] = $array;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
