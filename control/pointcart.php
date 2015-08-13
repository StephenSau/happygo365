<?php

defined('haipinlegou') or exit('Access Invalid!');
class pointcartControl extends BaseHomeControl {
	public function __construct() {
		parent::__construct();
		
		Language::read('home_pointcart');
		
		if ($GLOBALS['setting_config']['points_isuse'] != 1 || $GLOBALS['setting_config']['pointprod_isuse'] != 1){
			showMessage(Language::get('pointcart_unavailable'),'index.php','html','error');
		}
		if ($_SESSION['is_login'] != '1'){
			showMessage(Language::get('pointcart_unlogin_error'),'index.php?act=login','html','error');
		}
	}
	
	public function indexOp() {
		$cart_goods	= array();
		$pointcart_model	= Model('pointcart');
		$cart_goods	= $pointcart_model->getPointCartList(array('pmember_id'=>$_SESSION['member_id']));
		$cart_array	= array();
		if(is_array($cart_goods) and !empty($cart_goods)) {
			$pgoods_pointall = 0;
			foreach ($cart_goods as $val) {
				$val['pgoods_image'] 			= ATTACH_POINTPROD.'/'.$val['pgoods_image'].'_small.'.get_image_type($val['pgoods_image']);
				$val['pgoods_pointone']			= intval($val['pgoods_points']) * intval($val['pgoods_choosenum']);
				$cart_array[] = $val;
				$pgoods_pointall = $pgoods_pointall + $val['pgoods_pointone'];
			}
			Tpl::output('pgoods_pointall',$pgoods_pointall);
			Tpl::output('cart_array',$cart_array);
		}
		Tpl::showpage('pointcart_list');
	}
	
	public function addOp() {		
		$pgid	= intval($_GET['pgid']);
		$quantity	= intval($_GET['quantity']);
		if($pgid <= 0 || $quantity <= 0) {
			showMessage(Language::get('pointcart_cart_addcart_fail'),'index.php?act=pointprod','html','error');
		}
		$pointcart_model	= Model('pointcart');
		$check_cart	= $pointcart_model->getPointCartInfo(array('pgoods_id'=>$pgid,'pmember_id'=>$_SESSION['member_id']));
		if(!empty($check_cart)) {
			@header("Location:index.php?act=pointcart");exit;
		}
		
		$pointprod_model = Model('pointprod');
		$prod_info	= $pointprod_model->getPointProdInfo(array('pgoods_id'=>$pgid,'pgoods_show'=>'1','pgoods_state'=>'0'));
		if (!is_array($prod_info) || count($prod_info)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$ex_state = $pointprod_model->getPointProdExstate($prod_info);
		switch ($ex_state){
			case 'willbe':
				showMessage(Language::get('pointcart_cart_addcart_willbe'),getReferer(),'html','error');
				break;
			case 'end':
				showMessage(Language::get('pointcart_cart_addcart_end'),getReferer(),'html','error');
				break;
		}
		$quantity = $pointprod_model->getPointProdExnum($prod_info,$quantity);
		if ($quantity <= 0){
			showMessage(Language::get('pointcart_cart_addcart_end'),getReferer(),'html','error');
		}
		$points_all = intval($prod_info['pgoods_points'])*intval($quantity);
		$member_model = Model('member');
		$member_info = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_points');
		if (intval($member_info['member_points']) < $points_all){
			showMessage(Language::get('pointcart_cart_addcart_pointshort'),getReferer(),'html','error');
		}
		$array						= array();
		$array['pmember_id']		= $_SESSION['member_id'];
		$array['pgoods_id']			= $prod_info['pgoods_id'];
		$array['pgoods_name']		= $prod_info['pgoods_name'];
		$array['pgoods_points']		= $prod_info['pgoods_points'];
		$array['pgoods_choosenum']	= $quantity;
		$array['pgoods_image']		= $prod_info['pgoods_image'];
		$cart_state = $pointcart_model->addPointCart($array);
		@header("Location:index.php?act=pointcart");
		exit;
	}
	
	public function updateOp() {
		$pcart_id	= intval($_GET['pc_id']);
		$quantity	= intval($_GET['quantity']);
		$msg = Language::get('pointcart_cart_modcart_fail');
		if (strtoupper(CHARSET) == 'GBK'){
			$msg = Language::getUTF8($msg);
		}
		if($pcart_id <= 0 || $quantity <= 0) {
			echo json_encode(array('msg'=>$msg));
			die;
		}
		$pointcart_model	= Model('pointcart');
		$cart_info	= $pointcart_model->getPointCartInfo(array('pcart_id'=>$pcart_id,'pmember_id'=>$_SESSION['member_id']));
		if (!is_array($cart_info) || count($cart_info)<=0){
			echo json_encode(array('msg'=>$msg)); die;
		}
		$pointprod_model = Model('pointprod');
		$prod_info	= $pointprod_model->getPointProdInfo(array('pgoods_id'=>$cart_info['pgoods_id'],'pgoods_show'=>'1','pgoods_state'=>'0'));
		if (!is_array($prod_info) || count($prod_info)<=0){
			$pointcart_model->dropPointCartById($pcart_id);
			echo json_encode(array('msg'=>$msg)); die;
		}
		$ex_state = $pointprod_model->getPointProdExstate($prod_info);
		switch ($ex_state){
			case 'going':
				$quantity = $pointprod_model->getPointProdExnum($prod_info,$quantity);
				if ($quantity <= 0){
					$pointcart_model->dropPointCartById($pcart_id);
					echo json_encode(array('msg'=>$msg)); die;
				}
				break;
			default:
				$pointcart_model->dropPointCartById($pcart_id);
				echo json_encode(array('msg'=>$msg)); die;
				break;
		}
		
		$cart_state = $pointcart_model->updatePointCart(array('pgoods_choosenum'=>$quantity),array('pcart_id'=>$pcart_id,'pmember_id'=>$_SESSION['member_id']));
		if ($cart_state) {
			$all_price	= $this->amountOp();
			echo json_encode(array('done'=>'true','subtotal'=>$prod_info['pgoods_points']*$quantity,'amount'=>$all_price,'quantity'=>$quantity));
			die;
		}
	}
	
	public function dropOp() {
		$pcart_id	= intval($_GET['pc_id']);
		if($pcart_id==0) {
			die;
		}
		$pointcart_model	= Model('pointcart');
		$drop_state	= $pointcart_model->dropPointCartById($pcart_id);
		die;
	}
	
	private function amountOp() {
		$pointcart_model	= Model('pointcart');
		$cart_goods	= $pointcart_model->getPointCartList(array('pmember_id'=>$_SESSION['member_id']));
		$all_points	= 0;
		if(is_array($cart_goods) and !empty($cart_goods)) {
			foreach ($cart_goods as $val) {
				$all_points	= $val['pgoods_points'] * $val['pgoods_choosenum'] + $all_points;
			}
		}
		return $all_points;
	}
	
	public function step1Op(){
		$pointprod_arr = $this->getLegalPointGoods();
		Tpl::output('pointprod_arr',$pointprod_arr);

		$mode_address	= Model('address');
		$address_list	= $mode_address->getAddressList(array('member_id'=>$_SESSION['member_id'],'order'=>'address_id desc'));
		Tpl::output('address_list',$address_list);		

		Tpl::showpage('pointcart_step1');
	}

	public function step2Op() {
		$pointprod_arr = $this->getLegalPointGoods();
		$member_model = Model('member');
		$member_info = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_points');
		if (intval($member_info['member_points']) < $pointprod_arr['pgoods_pointall']){
			showMessage(Language::get('pointcart_cart_addcart_pointshort'),'index.php?act=member_points','html','error');
		}
		$pointorder_model= Model('pointorder');
		$order_array		= array();
		$order_array['point_ordersn']		= $pointorder_model->point_snOrder();
		$order_array['point_buyerid']		= $_SESSION['member_id'];
		$order_array['point_buyername']		= $_SESSION['member_name'];
		$order_array['point_buyeremail']	= $_SESSION['member_email'];
		$order_array['point_addtime']		= time();
		$order_array['point_outsn']			= $pointorder_model->point_outSnOrder();
		$order_array['point_allpoint']		= $pointprod_arr['pgoods_pointall'];
		$order_array['point_orderamount']	= $pointprod_arr['pgoods_freightall'];
		$order_array['point_shippingcharge']= $pointprod_arr['pgoods_freightcharge'];
		$order_array['point_shippingfee']	= $pointprod_arr['pgoods_freightall'];
		$order_array['point_ordermessage']	= trim($_POST['pcart_message']);
		if ($order_array['point_shippingcharge'] == 1){
			$order_array['point_orderstate']	= 10;
		}else {
			$order_array['point_orderstate']	= 20;
		}
		$order_id	= $pointorder_model->addPointOrder($order_array);
		if (!$order_id){
			showMessage(Language::get('pointcart_step2_fail'),'index.php?act=pointcart','html','error');
		}
		$points_model = Model('points');
		$insert_arr['pl_memberid'] = $_SESSION['member_id'];
		$insert_arr['pl_membername'] = $_SESSION['member_name'];
		$insert_arr['pl_points'] = -$pointprod_arr['pgoods_pointall'];
		$insert_arr['point_ordersn'] = $order_array['point_ordersn'];
		$points_model->savePointsLog('pointorder',$insert_arr,true);
		
		$pointprod_model = Model('pointprod');
		if(is_array($pointprod_arr['pointprod_list']) && count($pointprod_arr['pointprod_list'])>0) {
			$output_goods_name = array();
			foreach ($pointprod_arr['pointprod_list'] as $val) {
				$order_goods_array	= array();
				$order_goods_array['point_orderid']		= $order_id;
				$order_goods_array['point_goodsid']		= $val['pgoods_id'];
				$order_goods_array['point_goodsname']	= $val['pgoods_name'];
				$order_goods_array['point_goodspoints']	= $val['pgoods_points'];
				$order_goods_array['point_goodsnum']	= $val['quantity'];
				$order_goods_array['point_goodsimage']	= $val['pgoods_image'];
				$pointorder_model->addPointOrderProd($order_goods_array);
				
				if (count($output_goods_name)<3) $output_goods_name[] = $val['pgoods_name'];
				
				$pointprod_uparr = array();
				$pointprod_uparr['pgoods_salenum'] = array('value'=>$val['quantity'],'sign'=>'increase');
				$pointprod_uparr['pgoods_storage'] = array('value'=>$val['quantity'],'sign'=>'decrease');
				$pointprod_model->updatePointProd($pointprod_uparr,array('pgoods_id'=>$val['pgoods_id']));
				unset($pointprod_uparr);
				unset($order_goods_array);
			}
		}
		$pointcart_model = Model('pointcart');
		
		$address_model		= Model('address');
		if(intval($_POST['address_options']) > 0) {
			$address_info = $address_model->getOneAddress(intval($_POST['address_options']));
			if (!empty($address_info) && !get_magic_quotes_gpc()){
				foreach ($address_info as $k=>$v){
					$address_info[$k] = addslashes(trim($v));
				}
			}
		} else {
			$address_info['true_name']	= trim($_POST['consignee']);
			$address_info['area_id']	= intval($_POST['area_id']);
			$address_info['area_info']	= trim($_POST['area_info']);
			$address_info['address']	= trim($_POST['address']);
			$address_info['zip_code']	= trim($_POST['zipcode']);
			$address_info['tel_phone']	= trim($_POST['phone_tel']);
			$address_info['mob_phone']	= trim($_POST['phone_mob']);
			if (intval($_POST['save_address']) == 1) {
				$address_model->addAddress($address_info);
			}
		}
		if (is_array($address_info) && count($address_info)>0){
			$address_array		= array();
			$address_array['point_orderid']		= $order_id;
			$address_array['point_truename']	= $address_info['true_name'];
			$address_array['point_areaid']		= $address_info['area_id'];
			$address_array['point_areainfo']	= $address_info['area_info'];
			$address_array['point_address']		= $address_info['address'];
			$address_array['point_zipcode']		= $address_info['zip_code'];
			$address_array['point_telphone']	= $address_info['tel_phone'];
			$address_array['point_mobphone']	= $address_info['mob_phone'];
			$pointorder_model->addPointOrderAddress($address_array);
		}
		$step3_arr = array();
		$step3_arr['order_id'] = $order_id;
		$step3_arr['order_sn'] = $order_array['point_ordersn'];
		$step3_arr['pgoods_pointall'] = $pointprod_arr['pgoods_pointall'];
		$step3_arr['pgoods_freightcharge'] = $pointprod_arr['pgoods_freightcharge'];
		$step3_arr['pgoods_freightall'] = $pointprod_arr['pgoods_freightall'];
		$step3_arr['output_goods_name'] = $output_goods_name;
		$this->step3Op($step3_arr);
	}
	
	public function step3Op($order_arr=array()) {
		if (!is_array($order_arr) || count($order_arr)<=0){
			$order_id = intval($_GET['order_id']);
			if ($order_id <= 0){
				showMessage(Language::get('pointcart_record_error'),'index.php','html','error');	
			}
			$pointorder_model = Model('pointorder');
			$condition = array();
			$condition['point_orderid'] = "$order_id";
			$condition['point_buyerid'] = "{$_SESSION['member_id']}";
			$condition['point_orderstate'] = '10';
			$order_info = $pointorder_model->getPointOrderInfo($condition,'simple');
			unset($condition);
			if (!is_array($order_info) || count($order_info)<=0){
				showMessage(Language::get('pointcart_record_error'),'index.php','html','error');
			}
			$prod_list = $pointorder_model->getPointOrderProdList(array('prod_orderid'=>"{$order_id}"),$page);
			$output_goods_name = array();
			foreach ($prod_list as $val) {
				if (count($output_goods_name)<3) $output_goods_name[] = $val['point_goodsname'];
			}
			$order_arr['order_id'] = $order_info['point_orderid'];
			$order_arr['output_goods_name'] = $output_goods_name;
			$order_arr['order_sn'] = $order_info['point_ordersn'];
			$order_arr['pgoods_pointall'] = $order_info['point_allpoint'];
			$order_arr['pgoods_freightcharge'] = $order_info['point_shippingcharge'];
			$order_arr['pgoods_freightall'] = $order_info['point_shippingfee'];
		}
		$model_payment = Model('gold_payment');
		$condition = array();
		$condition['payment_state'] = '1';
		$payment_list = $model_payment->getList($condition);
		foreach ((array)$payment_list as $v) {
			if ($v['payment_online'] == 1){
				$online_array[] = $v;
			}else{
				$offline_array[] = $v;
			}
		}
		Tpl::output('offine_array',$offline_array);
		Tpl::output('online_array',$online_array);


		Tpl::output('payment_list',$payment_list);
		Tpl::output('order_arr',$order_arr);
		Tpl::showpage('pointcart_step2');
	}
	
	private function getLegalPointGoods(){
		$return_array = array();
		$pointcart_model	= Model('pointcart');
		$cart_goods	= $pointcart_model->getPointCartList(array('pmember_id'=>$_SESSION['member_id']));
		if(!is_array($cart_goods) || count($cart_goods)<=0) {
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$cart_goods_new = array();
		foreach ($cart_goods as $val) {
			$cart_goods_new[$val['pgoods_id']] = $val;
		}
		$cart_goodsid_arr = array_keys($cart_goods_new);
		if(!is_array($cart_goodsid_arr) || count($cart_goodsid_arr)<=0) {
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$cart_goodsid_str = implode(',',$cart_goodsid_arr);
		unset($cart_goodsid_arr);
		unset($cart_goods);
		
		$pointprod_model = Model('pointprod');
		$pointprod_list = $pointprod_model->getPointProdList(array('pgoods_id_in'=>$cart_goodsid_str,'pgoods_show'=>'1','pgoods_state'=>'0'));
		if (!is_array($pointprod_list) || count($pointprod_list)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$cart_delid_arr = array();
		$pgoods_pointall = 0;
		$pgoods_freightall = 0;
		$pgoods_freightcharge = false;
		foreach ($pointprod_list as $k=>$v){
			$v['pgoods_image_new'] = ATTACH_POINTPROD.DS.$v['pgoods_image'].'_small.'.get_image_type($v['pgoods_image']);
			$pointprod_list[$k] = $v;
			$ex_state = $pointprod_model->getPointProdExstate($v);
			switch ($ex_state){
				case 'going':
					$quantity = $pointprod_model->getPointProdExnum($v,$cart_goods_new[$v['pgoods_id']]['pgoods_choosenum']);
					if ($quantity <= 0){
						$cart_delid_arr[] = $cart_goods_new[$v['pgoods_id']]['pcart_id'];
						unset($pointprod_list[$k]);
					}else {
						$pointprod_list[$k]['quantity'] = $quantity;
						$pointprod_list[$k]['onepoints'] = intval($quantity)*intval($v['pgoods_points']);
						$pgoods_pointall = $pgoods_pointall + $pointprod_list[$k]['onepoints'];
						if ($v['pgoods_freightcharge'] == 1){
							$pgoods_freightcharge = true;
							$pgoods_freightall = $pgoods_freightall + $v['pgoods_freightprice'];
						}
					}
					break;
				default:
					$cart_delid_arr[] = $cart_goods_new[$v['pgoods_id']]['pcart_id'];
					unset($pointprod_list[$k]);
					break;
			}
		}
		if (is_array($cart_delid_arr) && count($cart_delid_arr)>0){
			$pointcart_model->dropPointCartById($cart_delid_arr);
		}
		if (!is_array($pointprod_list) || count($pointprod_list)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php?act=pointprod','html','error');
		}
		$pgoods_freightall = ncPriceFormat($pgoods_freightall); 
		$return_array = array('pointprod_list'=>$pointprod_list,'pgoods_freightcharge'=>$pgoods_freightcharge,'pgoods_pointall'=>$pgoods_pointall,'pgoods_freightall'=>$pgoods_freightall);
		
		return $return_array;
	}
	
	public function paymentOp(){
		$order_id = intval($_GET['order_id']);
		if ($order_id <= 0){
			showMessage(Language::get('pointcart_record_error'),'index.php','html','error');
		}
		$payment_id = intval($_POST['payment_id']);
		if ($payment_id<=0){
			showMessage(Language::get('pointcart_step3_choosepayment'),'index.php?act=pointcart&op=step3&order_id='.$order_id,'html','error');
		}
		$pointorder_model = Model('pointorder');
		$condition = array();
		$condition['point_orderid'] = "$order_id";
		$condition['point_buyerid'] = "{$_SESSION['member_id']}";
		$condition['point_shippingcharge'] = "1";
		$condition['point_orderstate'] = '10';
		$order_info = $pointorder_model->getPointOrderInfo($condition,'simple');
		unset($condition);
		if (!is_array($order_info) || count($order_info)<=0){
			showMessage(Language::get('pointcart_record_error'),'index.php','html','error');
		}
		$goldpayment_model = Model('gold_payment');
		$payment_list = $goldpayment_model->getList(array('payment_id'=>$payment_id,'payment_state'=>'1'));
		
		if (!is_array($payment_list) || count($payment_list)<=0){
			showMessage(Language::get('pointcart_step3_paymenterror'),'index.php?act=pointcart&op=step3&order_id='.$order_id,'html','error');
		}else {
			$payment_info = $payment_list[0];
		}
		$condition = array();
		$condition['point_orderid'] = "$order_id";
		$update_arr = array();
		$update_arr['point_paymentid'] 		= $payment_id;
		$update_arr['point_paymentname'] 	= $payment_list[0]['payment_name'];
		$update_arr['point_paymentcode'] 	= $payment_list[0]['payment_code'];
		$update_arr['point_paymentdirect'] 	= 1;
		$update_arr['point_paymenttime'] 	= time();
		if (is_array($_POST['offline'])){
			$update_arr['point_paymessage']	= serialize($this->stripslashes_deep($_POST['offline']));
		}

		if($payment_info['payment_code'] == 'offline') {
			$update_arr['point_orderstate'] 	= '11';
		}
		$state = $pointorder_model->updatePointOrder($condition,$update_arr);
		if($payment_info['payment_code'] != 'offline') {
			switch ($payment_info['payment_code']){
				case 'predeposit':
					@header("Location: index.php?act=pointorder_payment&op=predeposit_pay&id=$order_id");
					break;
				default:
					break;
			}
			$payment_orderinfo = array();
			$payment_orderinfo['order_sn'] = $order_info['point_ordersn'];
			$payment_orderinfo['order_amount'] = $order_info['point_shippingfee'];
			$payment_orderinfo['discount']		= 0;
			$payment_orderinfo['modeltype']		= '2';
			$payment_info['payment_config'] = unserialize($payment_info['payment_config']);
			$inc_file = BasePath.DS.'api'.DS.'gold_payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
			require_once($inc_file);
			$payment_api = new $payment_info['payment_code']($payment_info,$payment_orderinfo);
			if($payment_info['payment_code'] == 'chinabank') {
				$payment_api->submit();
				exit;
			} else {
				@header("Location: ".$payment_api->get_payurl());
				exit;
			}
		} else {
			showMessage(Language::get('pointcart_step3_paysuccess'),'index.php?act=member_pointorder');
		}
	}
	
	public function stripslashes_deep($value){
	    $value = is_array($value) ? array_map(array($this,'stripslashes_deep'), $value) : stripslashes($value);
	    return $value;
	}	
}