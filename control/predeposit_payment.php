<?php

defined('haipinlegou') or exit('Access Invalid!');
class predeposit_paymentControl extends BaseHomeControl{
	public function __construct(){
		parent::__construct();
		Language::read('member_member_predeposit');
	}
	
	public function notifyOp(){
		$success	= 'success';
		$fail		= 'fail';
		$rechargeorder_sn = trim($_POST['out_trade_no']);
		if(!empty($rechargeorder_sn)) {
			$predeposit_model	= Model('predeposit');
			$condition = array();			
			$condition['pdr_sn'] = $rechargeorder_sn;
			$condition['pdr_paystate'] = '0';		
			$recharge_info = $predeposit_model->getRechargeRow($condition);
			if (!is_array($recharge_info) || count($recharge_info)<=0){
				exit($fail);
			}
			$payment_code = $recharge_info["pdr_payment"];
			
			$order_info = array();
			$order_info['order_sn'] = $rechargeorder_sn;
			$order_info['order_amount'] = $recharge_info['pdr_price'];
			$payment_info = $this->payment_info($payment_code);
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
			require_once($inc_file);
			$payment_api = new $payment_code($payment_info,$order_info);
			if($payment_api->notify_verify()) {
				$update_arr = array();
				$update_arr['pdr_paystate'] = '1';
				$update_arr['pdr_onlinecode'] = $_POST['trade_no'];
				$state = $predeposit_model->rechargeUpdate($condition,$update_arr);
				if($state) {
					$log_arr = array();
					$log_arr['memberid'] = $recharge_info['pdr_memberid'];
					$log_arr['membername'] = $recharge_info['pdr_membername'];
					$log_arr['logtype'] = '0';
					$log_arr['price'] = $recharge_info['pdr_price'];
					$predeposit_model->savePredepositLog('recharge',$log_arr);
					exit($success);
				}else {
					exit($fail);
				}
			}else {
				exit($fail);
			}
		}
		exit($fail);
	}
	
	public function returnOp(){
		$url	= SiteUrl.DS."index.php?act=predeposit&op=rechargelist";
		
		$rechargeorder_sn = trim($_GET['out_trade_no']);
		if(!empty($rechargeorder_sn)) {
			$predeposit_model	= Model('predeposit');
			$condition = array();
			$condition['pdr_sn'] = $rechargeorder_sn;						
			$recharge_info = $predeposit_model->getRechargeRow($condition);
			if (!is_array($recharge_info) || count($recharge_info)<=0){
				showMessage(Language::get('predeposit_payment_pay_fail'),$url,'html','error');
			}
			$payment_code = $recharge_info["pdr_payment"];
			
			$order_info = array();
			$order_info['order_sn'] = $rechargeorder_sn;
			$order_info['order_amount'] = $recharge_info['pdr_price'];
			$payment_info = $this->payment_info($payment_code);
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
			require_once($inc_file);
			$payment_api = new $payment_code($payment_info,$order_info);
			if($payment_api->return_verify()) {
				$condition['pdr_paystate'] = '0';
				$update_arr = array();
				$update_arr['pdr_paystate'] = '1';
				$update_arr['pdr_onlinecode'] = $_GET['trade_no'];
				$state = $predeposit_model->rechargeUpdate($condition,$update_arr);
				if ($recharge_info['pdr_paystate'] == '0' && $state){
					$log_arr = array();
					$log_arr['memberid'] = $recharge_info['pdr_memberid'];
					$log_arr['membername'] = $recharge_info['pdr_membername'];
					$log_arr['logtype'] = '0';
					$log_arr['price'] = $recharge_info['pdr_price'];
					$predeposit_model->savePredepositLog('recharge',$log_arr);
				}
				if ($payment_code == 'tenpay'){
					$url = SiteUrl."/index.php?act=predeposit_payment&op=payment_success";
					showMessage(Language::get('predeposit_payment_pay_success'),$url,'tenpay');
				} else {
					showMessage(Language::get('predeposit_payment_pay_success'),$url);
				}
			} else {
				showMessage(Language::get('predeposit_payment_pay_fail'),$url,'html','error');
			}
		} else {
			showMessage(Language::get('predeposit_payment_pay_fail'),$url,'html','error');
		}
	}
	
	public function payment_successOp(){
		$url	= SiteUrl.DS."index.php?act=predeposit&op=rechargelist";
		showMessage(Language::get('predeposit_payment_pay_success'),$url);
	}
	
	private function payment_info($payment_code){
		$goldpayment_model = Model('gold_payment');
		$payment_info = array();
		$payment_info = $goldpayment_model->getRowByCondition(array('payment_code','payment_state'),array($payment_code,1));
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		return $payment_info;
	}
}