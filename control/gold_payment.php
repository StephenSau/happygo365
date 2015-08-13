<?php

defined('haipinlegou') or exit('Access Invalid!');
class gold_paymentControl extends BaseHomeControl{
	public function __construct(){
		parent::__construct();
		Language::read('member_store_gbuy');
	}
	
	
	public function notifyOp(){
		
		$success	= 'success';
		$fail		= 'fail';
		$gbuy_id = $_POST['out_trade_no'];
		if(intval($gbuy_id) > 0) {
			
			$model_gbuy	= Model('gold_buy');
			$condition = array();
			$condition['gbuy_id'] = $gbuy_id;
			$condition['gbuy_ispay'] = '0';
			$gbuy_list = $model_gbuy->getList($condition);
			$gold_buy = $gbuy_list[0];
			
			$payment_code = $gold_buy["gbuy_check_type"];
			
			$order_info = array();
			$order_info['order_sn'] = $gbuy_id;
			$order_info['order_amount'] = $gold_buy['gbuy_price'];
			$payment_info = $this->payment_info($payment_code);
			
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
			require_once($inc_file);
			$payment_api = new $payment_code($payment_info,$order_info);
			
			
			if($payment_api->notify_verify()) {
				$gbuy_array['gbuy_ispay'] = '1';
				$state = $model_gbuy->update($condition,$gbuy_array);
				if($state) {
					$this->gold_log($gold_buy);
				}
				exit($success);
			}
		}
		exit($fail);
	}
	
	public function returnOp(){
		$url	= SiteUrl."/index.php?act=store_gbuy";
		
		$gbuy_id = $_GET['out_trade_no'];
		if(intval($gbuy_id) > 0) {
			
			$model_gbuy	= Model('gold_buy');
			$condition = array();
			$condition['gbuy_id'] = $gbuy_id;
			$gbuy_list = $model_gbuy->getList($condition);
			$gold_buy = $gbuy_list[0];
			
			$payment_code = $gold_buy["gbuy_check_type"];
			
			$order_info = array();
			$order_info['order_sn'] = $gbuy_id;
			$order_info['order_amount'] = $gold_buy['gbuy_price'];
			$payment_info = $this->payment_info($payment_code);
			
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
			require_once($inc_file);
			$payment_api = new $payment_code($payment_info,$order_info);
			
			
			if($payment_api->return_verify()) {
				$gbuy_array = array();
				$gbuy_array['gbuy_ispay'] = '1';
				$condition['gbuy_ispay'] = '0';
				$state = $model_gbuy->update($condition,$gbuy_array);
				if($gold_buy['gbuy_ispay'] == '0' && $state) {
					$this->gold_log($gold_buy);
				}
				
				if ($payment_code == 'tenpay'){
					$url = SiteUrl."/index.php?act=gold_payment&op=payment_success";
					showMessage(Language::get('store_gbuy_pay_success'),$url,'tenpay');
				} else {
					showMessage(Language::get('store_gbuy_pay_success'),$url);
				}
			} else {
				showMessage(Language::get('miss_argument'),SiteUrl,'html','error');
			}
		} else {
			showMessage(Language::get('miss_argument'),SiteUrl,'html','error');
		}
	}
	
	public function payment_successOp(){
		$url	= SiteUrl."/index.php?act=store_gbuy";
		showMessage(Language::get('store_gbuy_pay_success'),$url);
	}
	
	public function predeposit_payOp(){
		$gbuy_id = intval($_GET['gbuy_id']);
		if($gbuy_id<=0){
			showMessage(Language::get('miss_argument'),'index.php?act=store_gbuy','html','error');
		}
		$model_gbuy	= Model('gold_buy');
		$condition = array();
		$condition['gbuy_id'] = "$gbuy_id";
		$condition['gbuy_ispay'] = '0';
		$condition['gbuy_mid'] = "{$_SESSION['member_id']}";
		$gbuy_list = $model_gbuy->getList($condition);
		$gold_buy = $gbuy_list[0];
		if (empty($gold_buy)){
			showMessage(Language::get('store_gbuy_no_record'),'index.php?act=store_gbuy','html','error');
		}
		$member_model	= Model('member');
		$buyer_info	= $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}"));
		if (empty($buyer_info)){
			showMessage(Language::get('store_gbuy_buyer_error'),'index.php?act=store_gbuy','html','error');
		}
		if (floatval($buyer_info['available_predeposit']) < floatval($gold_buy['gbuy_price'])){
			showMessage(Language::get('store_gbuy_predeposit_short_error'),'index.php?act=store_gbuy','html','error');
		}
		$predeposit_model = Model('predeposit');
		$log_arr = array();
		$log_arr['memberid'] = $_SESSION['member_id'];
		$log_arr['membername'] = $_SESSION['member_name'];
		$log_arr['logtype'] = '0';
		$log_arr['price'] = -$gold_buy['gbuy_price'];
		$log_arr['desc'] = Language::get('store_gbuy_predepositreduce_logdesc');
		$predeposit_model->savePredepositLog('order',$log_arr);
		unset($log_arr);
		$gbuy_array = array();
		$gbuy_array['gbuy_ispay'] = '1';
		$condition['gbuy_ispay'] = '0';
		$state = $model_gbuy->update($condition,$gbuy_array);
		if($gold_buy['gbuy_ispay'] == '0' && $state){
			$this->gold_log($gold_buy);
		}
		showMessage(Language::get('store_gbuy_edit_success'),"index.php?act=store_gbuy");
	}
	
	private function payment_info($payment_code){
		
		$model_payment = Model('gold_payment');
		$payment_info = array();
		$payment_condition = array();
		$payment_condition['payment_code'] = $payment_code;
		$payment_list = $model_payment->getList($payment_condition);
		$payment_info = $payment_list[0];
		
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		return $payment_info;
	}
	
	private function gold_log($gold_buy){
		
		$member_model = Model('member');
		$gbuy_num = intval($gold_buy["gbuy_num"]);
		$member_id = $gold_buy['gbuy_mid'];
		$member_array = array();
		$member_array['member_goldnum'] = array('value'=>$gbuy_num,'sign'=>'increase');
		$member_array['member_goldnumcount'] = array('value'=>$gbuy_num,'sign'=>'increase');
		$member_model->updateMember($member_array,$member_id);
		
		$model_glog	= Model('gold_log');
		$gold_log = array();
		$gold_log['glog_memberid'] = $gold_buy['gbuy_mid'];
		$gold_log['glog_membername'] = $gold_buy['gbuy_membername'];
		$gold_log['glog_storeid'] = $gold_buy['gbuy_storeid'];
		$gold_log['glog_storename'] = $gold_buy['gbuy_storename'];
		$gold_log['glog_adminid'] = 0;
		$gold_log['glog_adminname'] = 'system';
		$gold_log['glog_method'] = '1';
		$gold_log['glog_addtime'] = time();	
		$gold_log['glog_goldnum'] = $gold_buy['gbuy_num'];
		$gold_log['glog_stage'] = 'system';
		$gold_log['glog_desc'] = Language::get('store_gbuy_success');
		$model_glog->add($gold_log);
	}
	
}