<?php

defined('haipinlegou') or exit('Access Invalid!');
class pointorder_paymentControl extends BaseHomeControl{
	public function __construct(){
		parent::__construct();
		Language::read('home_pointcart');
	}
	
	public function notifyOp(){
		
		$success	= 'success';
		$fail		= 'fail';
		$pointorder_sn = trim($_POST['out_trade_no']);
		if(!empty($pointorder_sn)) {
			
			$pointorder_model	= Model('pointorder');
			$condition = array();
			$condition['point_ordersn'] = $pointorder_sn;
			$condition['point_shippingcharge'] = "1";
			$condition['point_orderstate'] = '10';
			$pointorderorder_info = $pointorder_model->getPointOrderInfo($condition,'simple');
			if (!is_array($pointorderorder_info) || count($pointorderorder_info)<=0){
				exit($fail);
			}
			$payment_code = $pointorderorder_info["point_paymentcode"];
			
			$order_info = array();
			$order_info['order_sn'] = $pointorder_sn;
			$order_info['order_amount'] = $pointorderorder_info['point_shippingfee'];
			$payment_info = $this->payment_info($payment_code);
			
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
			require_once($inc_file);
			$payment_api = new $payment_code($payment_info,$order_info);
			
			if($payment_api->notify_verify()) {
				$update_arr = array();
				$update_arr['point_orderstate'] = '11';
				$state = $pointorder_model->updatePointOrder($condition,$update_arr);
				if($state) {
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
		$url	= SiteUrl.DS."index.php?act=member_pointorder";
		
		$pointorder_sn = trim($_GET['out_trade_no']);
		if(!empty($pointorder_sn)) {
			
			$pointorder_model	= Model('pointorder');
			$condition = array();
			$condition['point_ordersn'] = $pointorder_sn;
			$condition['point_shippingcharge'] = "1";
			
			$pointorderorder_info = $pointorder_model->getPointOrderInfo($condition,'simple');
			if (!is_array($pointorderorder_info) || count($pointorderorder_info)<=0){
				showMessage(Language::get('pointcart_step3_payfail'),$url,'html','error');
			}
			$payment_code = $pointorderorder_info["point_paymentcode"];
			
			$order_info = array();
			$order_info['order_sn'] = $pointorder_sn;
			$order_info['order_amount'] = $pointorderorder_info['point_shippingfee'];
			$payment_info = $this->payment_info($payment_code);
			
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_code.DS.$payment_code.'.php';
			require_once($inc_file);
			$payment_api = new $payment_code($payment_info,$order_info);
			
			
			if($payment_api->return_verify()) {
				$condition['point_orderstate'] = '10';
				$update_arr = array();
				$update_arr['point_orderstate'] = '11';
				$state = $pointorder_model->updatePointOrder($condition,$update_arr);
				if ($payment_code == 'tenpay'){
					$url = SiteUrl."/index.php?act=pointorder_payment&op=payment_success";
					showMessage(Language::get('pointcart_step3_paysuccess'),$url,'tenpay');
				} else {
					showMessage(Language::get('pointcart_step3_paysuccess'),$url);
				}
			} else {
				showMessage(Language::get('pointcart_step3_payfail'),$url,'html','error');
			}
		} else {
			showMessage(Language::get('pointcart_step3_payfail'),$url,'html','error');
		}
	}
	
	public function predeposit_payOp(){
		$order_id = intval($_GET['id']);
		if($order_id<=0){
			showMessage(Language::get('miss_argument'),'index.php?act=member_pointorder','html','error');
		}
		$pointorder_model = Model('pointorder');
		$condition = array();
		$condition['point_orderid'] = "$order_id";
		$condition['point_buyerid'] = "{$_SESSION['member_id']}";
		$condition['point_orderstate'] = '10';
		$order_info = $pointorder_model->getPointOrderInfo($condition,'simple');
		if (empty($order_info)){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=member_pointorder','html','error');
		}
		$member_model	= Model('member');
		$buyer_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
		if (!is_array($buyer_info) || count($buyer_info)<=0){
			showMessage(Language::get('pointcart_buyer_error'),'index.php?act=member_pointorder','html','error');
		}
		if (floatval($buyer_info['available_predeposit']) < $order_info['order_amount']){
			showMessage(Language::get('payment_predeposit_short_error'),"index.php?act=pointcart&op=step3&order_id=$order_id",'html','error');
		}
		$input	= array();
		$input['point_orderstate']	= 20; 
		$result = $pointorder_model->updatePointOrder($condition,$input);
		if($result){
			$predeposit_model = Model('predeposit');
			$log_arr = array();
			$log_arr['memberid'] = $_SESSION['member_id'];
			$log_arr['membername'] = $_SESSION['member_name'];
			$log_arr['logtype'] = '0';
			$log_arr['price'] = -$order_info['point_shippingfee'];
			$log_arr['desc'] = Language::get('pointcart_step3_predepositreduce_logdesc');
			$predeposit_model->savePredepositLog('order',$log_arr);
			unset($log_arr);
			showMessage(Language::get('pointcart_step3_paysuccess'),SiteUrl."/index.php?act=member_pointorder");
		}else{
			showMessage(Language::get('pointcart_step3_payfail'),SiteUrl.'index.php?act=member_pointorder','html','error');
		}
	}

	public function payment_successOp(){
		$url	= SiteUrl.DS."index.php?act=member_pointorder";
		showMessage(Language::get('pointcart_step3_paysuccess'),$url);
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
}