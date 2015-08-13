<?php

defined('haipinlegou') or exit('Access Invalid!');
class pointvoucherControl extends BaseHomeControl {
	private $templatestate_arr;
	public function __construct() {
		parent::__construct();
		Language::read('home_pointprod,home_voucher');
		if (C('points_isuse') != 1 || C('voucher_allow') !=1){
			showMessage(Language::get('voucher_pointunavailable'),'index.php','html','error');
		}
		$this->templatestate_arr = array('usable'=>array(1,Language::get('voucher_templatestate_usable')),'disabled'=>array(2,Language::get('voucher_templatestate_disabled')));
		$this->voucherstate_arr = array('unused'=>array(1,Language::get('voucher_voucher_state_unused')),'used'=>array(2,Language::get('voucher_voucher_state_used')),'expire'=>array(3,Language::get('voucher_voucher_state_expire')));
		Tpl::output('index_sign','pointprod');
	}
	public function indexOp(){
		$this->pointvoucherOp();
	}
	
	public function pointvoucherOp(){
		$model = Model();
		$member_info = $model->table('member')->field('member_points')->where(array('member_id'=>$_SESSION['member_id']))->find();
		Tpl::output('member_info',$member_info);
		$vouchercount = 0;
		if($_SESSION['is_login'] == '1'){
			$vouchercount = $model->table('voucher')->where(array('voucher_owner_id'=>$_SESSION['member_id'],'voucher_state'=>$this->voucherstate_arr['unused'][0]))->count();
		}
		Tpl::output('vouchercount',$vouchercount);
		$pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
		$voucherlist = array();
		if (!empty($pricelist)){
			foreach ($pricelist as $k=>$v){
				$voucherlist[$v['voucher_price']]['price'] = $v;
			}
		}
		$field = 'voucher_template.*,store.store_id,store.store_label,store.store_name,store.store_domain';
		$on = 'voucher_template.voucher_t_store_id=store.store_id';
		$voucher = $model->table('voucher_template,store')->field($field)->join('left')->on($on)->where(array('voucher_t_state'=>$this->templatestate_arr['usable'][0],'voucher_t_end_date'=>array('gt',time())))->select();
		if (!empty($voucher)){
			foreach ($voucher as $k=>$v){
				if (!empty($v['voucher_t_customimg'])){
					$v['voucher_t_customimg'] = SiteUrl.DS.ATTACH_VOUCHER.DS.$v['voucher_t_store_id'].DS.$v['voucher_t_customimg']."_small.".get_image_type($v['voucher_t_customimg']);
				}else{
					$v['voucher_t_customimg'] = defaultGoodsImage('small');
				}
				if (!empty($v['store_label'])){
					$v['store_label'] = SiteUrl.DS.ATTACH_STORE.DS.$v['store_label'];
				}
				$v['voucher_t_limit'] = intval($v['voucher_t_limit']);
				if (!empty($voucherlist[$v['voucher_t_price']])){
					$voucherlist[$v['voucher_t_price']]['voucher'][] = $v;
				}
			}
		}
		Tpl::output('voucherlist',$voucherlist);
		Tpl::showpage('pointvoucher');
	}

	public function voucherexchangeOp(){
		$vid = intval($_GET['vid']);
		if($vid <= 0){
			$vid = intval($_POST['vid']);
		}
		if($_SESSION['is_login'] != '1'){
			$js = "login_dialog();";
			showDialog('','','js',$js);
		}elseif ($_GET['dialog']){
			$js = "CUR_DIALOG = ajax_form('vexchange', '".Language::get('home_voucher_exchangtitle')."', 'index.php?act=pointvoucher&op=voucherexchange&vid={$vid}', 480);";
			showDialog('','','js',$js);
			die;
		}
		$result = true;
		$message = "";
		if ($vid <= 0){
			$result = false;
			$message = Language::get('wrong_argument');
		}
		if ($result){
			$model = Model();
			$field = 'voucher_template.*,store.store_id,store.store_label,store.store_name,store.store_domain';
			$on = 'voucher_template.voucher_t_store_id=store.store_id';
			$voucher_info = $model->table('voucher_template,store')->field($field)->join('left')->on($on)->where(array('voucher_t_id'=>$vid,'voucher_t_state'=>$this->templatestate_arr['usable'][0],'voucher_t_end_date'=>array('gt',time())))->find();
			if (empty($voucher_info)){
				$result = false;
				$message = Language::get('home_voucher_recorderror');
			}else {
				$member_info = $model->table('member')->field('member_points')->where(array('member_id'=>$_SESSION['member_id']))->find();
				Tpl::output('member_info',$member_info);
				Tpl::output('voucher_info',$voucher_info);
			}
		}
		Tpl::output('message',$message);
		Tpl::output('result',$result);
		Tpl::showpage('pointvoucher.exchange','null_layout');
	}
	
	public function voucherexchange_saveOp(){
		if($_SESSION['is_login'] != '1'){
			$js = "login_dialog();";
			showDialog('','','js',$js);
		}
		$vid = intval($_POST['vid']);
		$js = "DialogManager.close('vexchange');";
		if ($vid <= 0){
			showDialog(Language::get('wrong_argument'),'','error',$js);
		}
		$model = Model('voucher');
		$voucher_info = $model->getUsableTemplateInfo($vid);
		if (empty($voucher_info)){
			showDialog(Language::get('home_voucher_recorderror'),'','error',$js);
		}
		if ($_SESSION['store_id'] == $voucher_info['voucher_t_store_id']){
			showDialog(Language::get('home_voucher_unableexchangself'),'','error',$js);
		}
		$voucher_list= $model->table('voucher')->where(array('voucher_owner_id'=>$_SESSION['member_id'],'voucher_store_id'=>$voucher_info['voucher_t_store_id'],'voucher_end_date'=>array('gt',time())))->select();
		if (!empty($voucher_list)){
			$voucher_count = 0;
			$voucherone_count = 0;
			foreach ($voucher_list as $k=>$v){
				if ($v['voucher_state'] == 1){
					$voucher_count += 1;
				}
				if ($v['voucher_t_id'] == $voucher_info['voucher_t_id']){
					$voucherone_count += 1;
				}
			}
			if ($voucher_count >= intval(C('promotion_voucher_buyertimes_limit'))){
				$message = sprintf(Language::get('home_voucher_alreadyfull'),C('promotion_voucher_buyertimes_limit'));
				showDialog($message,'','error',$js);
			}
			if ($voucherone_count >= $voucher_info['voucher_t_eachlimit']){
				$message = sprintf(Language::get('home_voucher_reachedeachlimit'),$voucher_info['voucher_t_eachlimit']);
				showDialog($message,'','error',$js);
			}
		}
		$member_info = $model->table('member')->field('member_points')->where(array('member_id'=>$_SESSION['member_id']))->find();
		if (empty($member_info)){
			showDialog(Language::get('wrong_argument'),'','error',$js);
		}
		if (intval($member_info['member_points']) < intval($voucher_info['voucher_t_points'])){
			showDialog(Language::get('home_voucher_pointsnotenough'),'','error',$js);
		}
		$insert_arr = array();
		$insert_arr['voucher_code'] = $model->get_voucher_code();
		$insert_arr['voucher_t_id'] = $voucher_info['voucher_t_id'];
		$insert_arr['voucher_title'] = $voucher_info['voucher_t_title'];
		$insert_arr['voucher_desc'] = $voucher_info['voucher_t_desc'];
		$insert_arr['voucher_start_date'] = time();
		$insert_arr['voucher_end_date'] = $voucher_info['voucher_t_end_date'];
		$insert_arr['voucher_price'] = $voucher_info['voucher_t_price'];
		$insert_arr['voucher_limit'] = $voucher_info['voucher_t_limit'];
		$insert_arr['voucher_store_id'] = $voucher_info['voucher_t_store_id'];
		$insert_arr['voucher_state'] = 1;
		$insert_arr['voucher_active_date'] = time();
		$insert_arr['voucher_owner_id'] = $_SESSION['member_id'];
		$insert_arr['voucher_owner_name'] = $_SESSION['member_name'];
		$result = $model->table('voucher')->insert($insert_arr);
		if ($result){
			$model->table('voucher_template')->where(array('voucher_t_id'=>$voucher_info['voucher_t_id']))->update(array('voucher_t_giveout'=>array('exp','voucher_t_giveout+1')));
			showDialog(Language::get('home_voucher_exchangsucc'),'','succ',$js);
		}else {
			showDialog(Language::get('home_voucher_exchangfail'),'','error',$js);
		}
	}
}