<?php

defined('haipinlegou') or exit('Access Invalid!');

class memberControl extends BaseMemberControl{
	
	public function addressOp() {
		Language::read('member_member_index');
		$lang	= Language::getLangContent();
		

		$address_class = Model('address');
		
                
                
		if (!empty($_GET['type'])){
		
			if (intval($_GET['id']) > 0){
				
				$address_info = $address_class->getOneAddress(intval($_GET['id']));

				if (empty($address_info) && !is_array($address_info)){
					showMessage($lang['member_address_wrong_argument'],'index.php?act=member&op=address','html','error');
				}
				Tpl::output('address_info',$address_info);
			}
			
			Tpl::output('type',$_GET['type']);
			Tpl::showpage('address_form','null_layout');
			exit();
		}
		
		if (chksubmit()){

                        $obj_validate = new Validate();
                        //var_dump($_POST);die;
                        //判断是否汽车馆
                $obj_validate->validateparam = array(
				array("input"=>$_POST["true_name"],"require"=>"true","message"=>$lang['member_address_receiver_null']),
				array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>$lang['member_address_wrong_area']),
				array("input"=>$_POST["city_id"],"require"=>"true","validator"=>"Number","message"=>$lang['member_address_wrong_area']),
				array("input"=>$_POST["area_info"],"require"=>"true","message"=>$lang['member_address_area_null']),
				array("input"=>$_POST["address"],"require"=>"true","message"=>$lang['member_address_address_null']),
				array("input"=>$_POST['mob_phone'],'require'=>'true','message'=>$lang['member_address_phone']),
				// array("input"=>$_POST['card'],'require'=>'true','message'=>$lang['member_address_card_null'])
                    );
                    
                          
                    
						
			$error = $obj_validate->validate();
			if ($error != ''){
				showValidateError($error);
			}
			if (intval($_POST['id']) > 0){
				$rs = $address_class->updateAddress($_POST);
				if (!$rs){
					showDialog($lang['member_address_modify_fail'],'','error');
				}
			}else {
				$rs = $address_class->addAddress($_POST);
				if (!$rs){
					showDialog($lang['member_address_add_fail'],'','error');
				}
			}
			showDialog($lang['nc_common_op_succ'],'reload','succ','CUR_DIALOG.close()');
		}
		$del_id = isset($_GET['id']) ? intval(trim($_GET['id'])) : 0 ;
		if ($del_id > 0){
			$rs = $address_class->delAddress($del_id);
			if ($rs){
				showDialog(Language::get('member_address_del_succ'),'index.php?act=member&op=address','succ');
			}else {
				showDialog(Language::get('member_address_del_fail'),'','error');
			}
		}
		$address_list = $address_class->getAddressList(array('member_id'=>$_SESSION['member_id']));
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出

		//获取用户信用分数，头像，名字
		$model_member	= Model('member');
		$member_info = $model_member->find($_SESSION['member_id']);
        //默认收货地址放第一位
        if(!empty($member_info['member_address_id']) && !empty($address_list)) {
            foreach($address_list as $key=>$val) {
                if ($member_info['member_address_id'] == $val['address_id']) {
                    $tmp = $val;
                    unset($address_list[$key]);
                    array_unshift($address_list,$tmp);
                    break;
                }
            }
        }
		Tpl::output('member_info',$member_info);
		
		self::profile_menu('address','address');
		Tpl::output('menu_sign','address');
		Tpl::output('address_list',$address_list);
		Tpl::output('menu_sign_url','index.php?act=member&op=address');
		Tpl::output('menu_sign1','address_list');
		Tpl::setLayout('member_pub_layout');
		Tpl::showpage('address_index');
	}
	
	public function orderOp() {
		
		Language::read('member_member_index');
		$lang	= Language::getLangContent();
		
		$model_order = Model('order');
		
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		$model_my_order = Model('my_order');
		$array	= array();
		//print_R(trim($_GET['order_sn']));exit;
		$array['order_sn']		= trim($_GET['order_sn']);		
		$array['order_state']	= (in_array(trim($_GET['state_type']),array('order_pay','order_submit','order_pay_confirm','order_no_shipping','order_shipping','order_finish','order_cancal','order_refer','order_confirm')) ? trim($_GET['state_type']) : '');
		if($_GET['state_type'] == 'noeval'){
			$array['order_evalbuyer_able']		= '1';
		}
		
		$array['add_time_from'] = strtotime($_GET['add_time_from']);
        $array['add_time_to'] = strtotime($_GET['add_time_to']);
        if($array['add_time_to'] > 0) {
            $array['add_time_to'] +=86400;
        }
		$order_list		= $model_my_order->myOrderList($array,$page,'order_id,store_id');
		
		if(is_array($order_list) && !empty($order_list)) {
			$order_id_array = array();
			$store_id_array = array();
			$store_array = array();
			$refund_array = array();
			$return_array = array();
			$model_store = Model('store');
			foreach ($order_list as $v) {
				$order_id_array[] = $v['order_id'];
				if(!in_array($v['store_id'],$store_id_array)) $store_id_array[] = $v['store_id'];
			}
			$order_list		= array();
			$order_list		= $model_my_order->myOrderGoodsList(array('order_id_string'=>"'".implode("','",$order_id_array)."'"));
			$store_list = $model_store->getStoreList(array('store_id_in'=>"'".implode("','",$store_id_array)."'"));
			if (is_array($store_list) && !empty($store_list)){
				foreach ($store_list as $val) {
					$store_array[$val['store_id']] = $val;
				}
			}
			
			$model_refund	= Model('refund');
			$condition = array();
			$condition['buyer_id'] = $_SESSION['member_id'];
			$condition['order_ids'] = "'".implode("','",$order_id_array)."'";
			$condition['refund_type'] = '1';
			$refund_list = $model_refund->getList($condition);
			if (is_array($refund_list) && !empty($refund_list)){
				foreach ($refund_list as $val) {
					$refund_array[$val['order_id']] = $val;
				}
			}
			
			$model_return	= Model('return'); 
			$condition = array();
			$condition['buyer_id'] = $_SESSION['member_id'];
			$condition['order_ids'] = "'".implode("','",$order_id_array)."'";
			$condition['return_type'] = '1';
			$return_list= $model_return->getList($condition);
			if (is_array($return_list) && !empty($return_list)){
				foreach ($return_list as $val) {
					$return_array[$val['order_id']] = $val;
				}
			}
			
			$order_array	= array();
			if(is_array($order_list) && !empty($order_list)) {
				$store_id	= 0;
				$store = array();
				foreach ($order_list as $val) {
					$order_id = $val['order_id'];
					if(is_array($refund_array[$order_id]) && !empty($refund_array[$order_id])) $val['refund'] = $refund_array[$order_id];
					if(is_array($return_array[$order_id]) && !empty($return_array[$order_id])) $val['return'] = $return_array[$order_id];
					$val['spec_info_arr'] = '';
					if (!empty($val['spec_info'])){
						$val['spec_info_arr']	= unserialize($val['spec_info']);
					}
					$val['state_info']		= orderStateInfo($val['order_state'],$val['refund_state']);
					$store_id	= $val['store_id'];
					$store = $store_array[$store_id];
					
					$val['member_id'] = $store['member_id'];
					$val['member_name'] = $store['member_name'];
					$val['store_qq'] = $store['store_qq'];
					$val['store_ww'] = $store['store_ww'];
					unset($store);
					if ($val['evaluation_status'] == 1){
						$val['able_evaluate'] = false;
					}else {
						$val['able_evaluate'] = true;
					}
					if ($val['able_evaluate'] && $val['evalseller_status'] == 0 && (intval($val['finnshed_time'])+60*60*24*15)<time()){
						$val['able_evaluate'] = false;
					}elseif ($val['able_evaluate'] && $val['evalseller_status'] == 1 && (intval($val['evalseller_time'])+60*60*24*15)<time()) {
						$val['able_evaluate'] = false;
					}
					$order_array[$val['order_id']][] = $val;
				}
			}
		}
		Tpl::output('order_array',$order_array);
		Tpl::output('show_page',$page->show());
        Tpl::output('complain_time_limit',$GLOBALS['setting_config']['complain_time_limit']);
		
		//S脚部内容显示
		$list = $this->_article();
        //E脚部内容显示

		$this->get_member_info();
		self::profile_menu('member_order','member_order');
		Tpl::output('menu_sign','myorder');
		Tpl::output('menu_sign_url','index.php?act=member&op=order');
		Tpl::output('menu_sign1','myorder_list');
		Tpl::showpage('member_order');
	}
	
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
    
	public function change_stateOp() {
        import('libraries.mail');
		Language::read('member_member_index');
		$lang	= Language::getLangContent();
		$state_type	= trim($_GET['state_type']);
		$order_id	= intval($_GET['order_id']);

		if($state_type == '' || $order_id<=0) showMessage($lang['member_change_parameter_error'],'index.php?act=member&op=order','html','error');
		$model_order = Model('order');
		$order	= $model_order->getOrderById($order_id,'simple');
        
		if (!is_array($order) || empty($order)){
			showDialog($lang['member_change_orderrecord_error'],'index.php?act=member&op=order');
		}
		if ($_SESSION['member_id'] != $order['buyer_id']){
			showDialog($lang['member_change_order_member_error'],'index.php?act=member&op=order');	
		}
		$array	= array();
		switch ($state_type) {
			case 'cancel_order':	
			$temp_file	= 'member_order_cancel';
			$state_code = 0;
			break;
            case 'confirm_order':
            $temp_file	= 'member_order_confirm';
			$array['finnshed_time'] = time();
			$state_code = 40;
			break;
		}
		if (intval($order['order_state']) ==  $state_code){
			showDialog($lang['member_change_changed_error'],'index.php?act=member&op=order');
		}
		if(chksubmit()) {
			$array['order_state'] = $state_code;
            $model_my_order = Model('my_order');
            $goods_list = $model_my_order->myOrderGoodsList(array('order_id'=>$order_id));
			if($state_code == 0) {
				$model_goods= Model('goods');
				if(is_array($goods_list) and !empty($goods_list)) {
					foreach ($goods_list as $val) {
						$model_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$val['goods_num'],'sign'=>'increase'),'spec_salenum'=>array('value'=>$val['goods_num'],'sign'=>'decrease')),$val['spec_id']);
						$model_goods->updateGoods(array('salenum'=>array('value'=>$val['goods_num'],'sign'=>'decrease')),$val['goods_id']);
					}
				}
			}
			$model_member	= Model('member');
			$seller	= $model_member->infoMember(array('member_id'=>$order['seller_id']));
           
			if ($state_code == 40){
                $goods_count = 0;
                foreach($goods_list as $value) {
                    $goods_count += $value['goods_num'];
                }
                $model_store = Model('store');
                $update_array = array();
                $update_array['store_sales'] = array('sign'=>'increase','value'=>$goods_count);
                $update_array['store_id'] = $order['store_id']; 
                $model_store->storeUpdate($update_array);

                if (($order['payment_code'] == 'predeposit')){
					$model_member = Model('member');
					$buyer_info = $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));
					if (!is_array($buyer_info) || count($buyer_info)<=0){
						showDialog($lang['member_change_order_member_error'],'index.php?act=member&op=order');
					}
					if (floatval($order['order_amount']) > floatval($buyer_info['freeze_predeposit'])){
						showDialog($lang['member_change_freezepredeposit_short_error'],'index.php?act=member&op=order','error','','3');
					}
					$predeposit_model = Model('predeposit');
					$log_arr = array();
					$log_arr['memberid'] = $seller['member_id'];
					$log_arr['membername'] = $seller['member_name'];
					$log_arr['logtype'] = '0';
					$log_arr['price'] = $order['order_amount'];
					$log_arr['desc'] = Language::get('member_change_order_no').$order['order_sn'].Language::get('member_change_ensurereceive_predeposit_logdesc');
					$predeposit_model->savePredepositLog('order',$log_arr);
					unset($log_arr);
					$log_arr = array();	
					$log_arr['memberid'] = $_SESSION['member_id'];
					$log_arr['membername'] = $_SESSION['member_name'];
					$log_arr['logtype'] = '1';
					$log_arr['price'] = -$order['order_amount'];
					$log_arr['desc'] = Language::get('member_change_order_no').$order['order_sn'].Language::get('member_change_ensurereceive_predepositfreeze_logdesc');
					$predeposit_model->savePredepositLog('order',$log_arr);
					unset($log_arr);
				}elseif (!C('payment')){	
					$model_member = Model('member');
					$buyer_info = $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));
					if (!is_array($buyer_info) || count($buyer_info)<=0){
						showDialog($lang['member_change_order_member_error'],'index.php?act=member&op=order');
					}
					$predeposit_model = Model('predeposit');
					$log_arr = array();
					$log_arr['memberid'] = $seller['member_id'];
					$log_arr['membername'] = $seller['member_name'];
					$log_arr['logtype'] = '0';
					$log_arr['price'] = $order['order_amount'];
					$log_arr['desc'] = Language::get('member_change_order_no').$order['order_sn'].Language::get('member_change_ensurereceive_predeposit_logdesc');
					$predeposit_model->savePredepositLog('income',$log_arr);
				}
                
                 if(C('predeposit_reg_switch')==1){
                
                //验证会员是否第一张确认订单及是否有推广人
                if($buyer_info['extension_id']>0){
                   
                    $model = Model();
                    $where['buyer_id'] = $_SESSION['member_id'];
                    $where['order_state'] = '40';
                    $order_num = $model->table('order')->where($where)->count();
                   
                   if(!$order_num){
                    
                    $obj_voucher = Model('voucher');
                    
                    //插入邀请人的代金卷
                    $insert_extension_arr = array();

            		$insert_extension_arr['voucher_code'] = $obj_voucher->get_voucher_code();
            
            		$insert_extension_arr['voucher_t_id'] = 1;
            
            		$insert_extension_arr['voucher_title'] = "代金券"; 
            
            		$insert_extension_arr['voucher_desc'] = "推荐会员奖励";
            
            	    $insert_extension_arr['voucher_price'] = 10;
            
            		$insert_extension_arr['voucher_limit'] = 100;
            
            		$insert_extension_arr['voucher_store_id'] = 0;
            
            		$insert_extension_arr['voucher_state'] = 1;
                    
                    $insert_extension_arr['voucher_start_date'] = time();
                    
                    $insert_extension_arr['voucher_end_date'] = time()+(86400*C('predeposit_voucher_time')); // 代金卷结束时间
            
            		$insert_extension_arr['voucher_active_date'] = time();
                    
            		$insert_extension_arr['voucher_owner_id'] = $buyer_info['extension_id'];
                  
                    $obj_voucher->table('voucher')->insert($insert_extension_arr);
                    
                    
                    //插入用户代金卷
                    
                    $insert_arr_ = array();

            		$insert_arr['voucher_code'] = $obj_voucher->get_voucher_code();
            
            		$insert_arr['voucher_t_id'] = 1;
            
            		$insert_arr['voucher_title'] = "代金券"; 
            
            		$insert_arr['voucher_desc'] = "首次消费奖励";
            
            	    $insert_arr['voucher_price'] = 10;
            
            		$insert_arr['voucher_limit'] = 100;
            
            		$insert_arr['voucher_store_id'] = 0;
            
            		$insert_arr['voucher_state'] = 1;
                    
                    $insert_arr['voucher_start_date'] = time();
                    
                    $insert_arr['voucher_end_date'] = time()+(86400*C('predeposit_voucher_time')); // 代金卷结束时间
            
            		$insert_arr['voucher_active_date'] = time();
                    
            		$insert_arr['voucher_owner_id'] = $_SESSION['member_id'];
                    
                    $obj_voucher->table('voucher')->insert($insert_arr);
                    
                   }
                
               }
                
                //邀请好友消费奖励
                $order_amount=  $order['order_amount'];//消费金额
                $order_add_time=  $order['add_time'];//订单生成时间
                $predeposit_invitation_day=C('predeposit_invitation_day');//好友在多少天内消费额的   
                $predeposit_invitation_percent=C('predeposit_invitation_percent');//提成百分数
                $predeposit_invitation_percent=$predeposit_invitation_percent/100;
                $invitation_money=$predeposit_invitation_percent*$order_amount;//提成 
               
                $buyerinfo	= $model_member->infoMember(array('member_id'=>$order['buyer_id']));
                if($buyerinfo['extension_id']>0){
                    $member_addtime=$buyerinfo['member_time'];//会员注册时间
                    $function_endtime=$predeposit_invitation_day*86400;//邀请奖励消费结束时间
                        if(($member_addtime+$function_endtime)>time()){
                            $obj_member = Model('member');
                            $upmember_array = array();
                            $upmember_array['available_predeposit'] = array('sign'=>'increase','value'=>$invitation_money);
                            $obj_member->updateMember($upmember_array,$buyerinfo['extension_id']);
                            //插入预存款类型记录
                            $insert_array['mid'] = $buyerinfo['extension_id'];
                            $insert_array['type'] = 2;
                            $insert_array['money'] = $invitation_money;
                            $insert_array['addtime'] = time();
                            Db::insert('predeposit_record', $insert_array);
                            
                        }
                    
                }
                
               
                }
                
                
			}
			$model_order->addLogOrder($state_code,$order_id,($_POST['state_info1']!=''? $_POST['state_info1'] :$_POST['state_info'] ));
			$res = $model_order->updateOrder($array,$order_id);

            //取消订单返回代金券
            $model = Model();
            $voucher_model = Model('voucher');
            $where['order_id'] = $order_id;
            $order_info = $model->table('order')->where($where)->find();

            if($order_info['voucher_id'] >0 && $state_code == 0 && $res) {

                $vou['voucher_state']    = 1;
                $vou['voucher_order_id'] = '';
                $voucher_model->updateVoucherState($vou,$order_info['voucher_id']);
            }


			if ($GLOBALS['setting_config']['points_isuse'] == 1 && $state_code == 40){
				$points_model = Model('points');
				$points_model->savePointsLog('order',array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],'orderprice'=>$order['order_amount'],'order_sn'=>$order['order_sn'],'order_id'=>$order['order_id']),true);
			}

			
			$param	= array(
				'site_url'	=> SiteUrl,
				'site_name'	=> $GLOBALS['setting_config']['site_name'],
				'buyer_name'	=> $order['buyer_name'],
				'seller_name'	=> $seller['member_name'],
				'reason'	=> $_POST['state_info1']!=''? $_POST['state_info1'] :$_POST['state_info'],
				'order_sn'	=> $order['order_sn'],
				'order_id'	=> $order['order_id']
			);
			$code	= '';
			switch ($state_type) {
				case 'cancel_order':
					$code	= 'email_toseller_cancel_order_notify';
					break;
				case 'confirm_order':
					$code	= 'email_toseller_finish_notify';
					break;
			}
			$this->send_notice($order['seller_id'],$code,$param);
			showDialog(Language::get('nc_common_save_succ'),'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			exit;
		} else {
			Tpl::output('order_id',$order_id);
			Tpl::showpage($temp_file,'null_layout');
		}
	}
	
	public function show_orderOp() {
		
		Language::read('member_member_index');
		$lang	= Language::getLangContent();
		$order_id	= intval($_GET['order_id']);
		
		$model_order	= Model('order');
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$order_info = $model_order->getOrderById($order_id,'all',$condition);
		$order_id	= intval($order_info['order_id']);
		if($order_id == 0) {
			showMessage($lang['miss_argument'],'','html','error');
		}
		$order_info['state_info'] = orderStateInfo($order_info['order_state'],$order_info['refund_state']);
		if(is_array($order_info[0]))
		{
			foreach($order_info as $k=>$v)
			{
				$param['table'] = 'address';
				$param['field'] = 'member_id';;
				$param['value'] = $v['buyer_id'];
				$address = Db::getRow( $param,"*");
				$v['card'] = $address['card'];
				$v['card_type'] = '身份证';
			}
			
		}else
		{
			$param['table'] = 'address';
			$param['field'] = 'member_id';;
			$param['value'] = $order_info['buyer_id'];
			$address = Db::getRow($param,"*");
			$order_info['card'] = $address['card'];
			$order_info['card_type'] = '身份证';
		}		
		Tpl::output('order_info',$order_info);
		
		if(!empty($order_info['group_id']) && is_numeric($order_info['group_id'])){
			$group_name = Model()->table('goods_group')->getfby_group_id($order_info['group_id'],'group_name');
			Tpl::output('group_name',$group_name);
		}
		
		$model_store	= Model('store');
		$store_info		= $model_store->shopStore(array('store_id'=>$order_info['store_id']));
		Tpl::output('store_info',$store_info);
		
		$model_my_order = Model('my_order');
		
		$order_goods_list= $model_my_order->myOrderGoodsList(array('order_id'=>$order_id));
		
		Tpl::output('order_goods_list',$order_goods_list);
		
	
		$log_list	= $model_order->orderLoglist($order_id);
		Tpl::output('order_log',$log_list);
	
		$model_refund	= Model('refund');
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$condition['refund_state'] = '2';
		$condition['order']	=  'log_id asc';
		$refund_list = $model_refund->getList($condition);
		Tpl::output('refund_list',$refund_list);
		
		$model_return	= Model('return'); 
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$condition['return_state'] = '2';
		$condition['order']	=  'return.return_id asc';
		$return_list= $model_return->getReturnGoodsList($condition);
		Tpl::output('return_list',$return_list);
        $this->get_member_info();
       // $order_info['shipping_express_id']=29;
      // echo $order_info['shipping_code'];
        //顺丰物流跟踪
        if($order_info['shipping_express_id']>0 && $order_info['shipping_express_id']==29){
        
             define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
             
             require_once (_ROOT . "/../vendor/shunfeng/class/SFforHttpPost.class.php");
              $SF = new SFapi();
             $search_orderid = $order_info['order_sn']; 
            //$search_orderid = '444004747793';
             $datasearch = $SF->OrderSearchService($search_orderid)->Send()->readJSON();
             @preg_match ("/\"mailno\":\".*?\"/is", $datasearch, $m) ;
             $str=@str_replace("\"", "",$m[0]);
             $strarr=@explode(":",$str);
           // $strarr[1]='444004747793';
            if($strarr[1]){
            $route_mailno = $strarr[1];
          // $route_mailno='444004747793';
            $data = $SF->RouteService($route_mailno)->Send()->View();
    
            $xml = simplexml_load_string($data); 
            $data=json_decode(json_encode($xml->Body->RouteResponse), TRUE);
            $routreorder=array();
            if($data['Route']){
            foreach($data['Route'] as $key=>$val){
                foreach($val as $v){
                    $routreorder[]=$v;    
                }
            }
            }
            
    //print_r($routreorder);
            	Tpl::output('routreorder',$routreorder);
    		}
        }
        //韵达
        if($order_info['shipping_express_id']>0 && $order_info['shipping_express_id']==41){
       // header("Content-type: text/html; charset=utf-8");
             define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
             include _ROOT . "/../vendor/yunda/lib/YundaClient.php";

            //调用运单检索对象
            $requestOrder = new QueryOrderTrace();
            //需要检索的运单号,现不支持批量查找.
            $requestOrder->mailno = $order_info['shipping_code'];
            
            //调用检索接口,输入数据检索的地址,用户名,密码.
            $client = new YundaClient('http://dev.yundasys.com:15105/join', 'order', '123456');
            //输入检索的uri与对象
            $test = $client->request('/query/json.php', $requestOrder);
           //$routreorder= json_decode($test, TRUE);
            //$test = simplexml_load_string($test); 
            $datayunda=json_decode($test, TRUE);
            
            foreach($datayunda['steps'] as $key=>$val){
                $routreorder[$key]['accept_address']=$val['address'];
                $routreorder[$key]['remark']=$val['remark'];
                $routreorder[$key]['accept_time']=$val['time'];
                
            }
            
            

            
    //print_r($routreorder);
            	Tpl::output('routreorder',$routreorder);
    		
        }
        
        $this->get_member_info();
		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出
		Tpl::output('left_show','order_view');
		Tpl::showpage('member_order_view');
	}
	

	
	public function show_expressOp() {		
		
		Language::read('member_member_index');
		$lang	= Language::getLangContent();
		$order_id	= intval($_GET['order_id']);
		
		$model_order	= Model('order');
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['order_id'] = $order_id;
		$order_info = $model_order->getOrderById($order_id,'all',$condition);

		$order_id	= intval($order_info['order_id']);
		if($order_id == 0) {
			showMessage($lang['miss_argument'],'','html','error');
		}
		$order_info['state_info'] = orderStateInfo($order_info['order_state']);
		Tpl::output('order_info',$order_info);
		
		$model_store	= Model('store');
		$store_info		= $model_store->shopStore(array('store_id'=>$order_info['store_id']));
		Tpl::output('store_info',$store_info);
		
		$model_my_order = Model('my_order');
		
		$order_goods_list= $model_my_order->myOrderGoodsList(array('order_id'=>$order_id));
		Tpl::output('order_goods_list',$order_goods_list);

		$daddress_info = Model('daddress')->find($order_info['daddress_id']);
		Tpl::output('daddress_info',$daddress_info);		
		
		$express_list  = ($h = F('express')) ? $h : H('express',true,'file');

		Tpl::output('e_code',$express_list[$order_info['shipping_express_id']]['e_code']);
		Tpl::output('e_name',$express_list[$order_info['shipping_express_id']]['e_name']);
		Tpl::output('e_url',$express_list[$order_info['shipping_express_id']]['e_url']);

		Tpl::output('shipping_code',$order_info['shipping_code']);
		Tpl::output('left_show','order_view');
		Tpl::showpage('member_order_express_detail');
	}

	
	public function get_expressOp(){

		$url = 'http://www.kuaidi100.com/query?type='.$_GET['e_code'].'&postid='.$_GET['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
		import('function.ftp');
		$content = dfsockopen($url);
		$content = json_decode($content,true);
		

		if ($content['status'] != 200) exit(json_encode(false));
		$content['data'] = array_reverse($content['data']);
		$output = '';
		if (is_array($content['data'])){
			foreach ($content['data'] as $k=>$v) {
				if ($v['time'] == '') continue;
				$output .= '<li>'.$v['time'].'&nbsp;&nbsp;'.$v['context'].'</li>';
			}
		}
		if ($output == '') exit(json_encode(false));
		if (strtoupper(CHARSET) == 'GBK'){
			$output = Language::getUTF8($output);
		}
		echo json_encode($output);
	}

	
	private function profile_menu($menu_type,$menu_key='') {
		
		Language::read('member_layout');
		$menu_array	= array();
		switch ($menu_type) {
			case 'address':
				$menu_array = array(
				1=>array('menu_key'=>'address','menu_name'=>Language::get('nc_member_path_address_list'),	'menu_url'=>'index.php?act=member&op=address'));
				break;
			case 'member_order':
				$menu_array = array(
				1=>array('menu_key'=>'member_order','menu_name'=>Language::get('nc_member_path_order_list'),	'menu_url'=>'index.php?act=member&op=order'),
				2=>array('menu_key'=>'buyer_refund','menu_name'=>Language::get('nc_member_path_buyer_refund'),	'menu_url'=>'index.php?act=member_refund'),
				3=>array('menu_key'=>'buyer_return','menu_name'=>Language::get('nc_member_path_buyer_return'),	'menu_url'=>'index.php?act=member_return'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
	
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
			
			if(!file_exists('upload/member/'))
				mkdir('upload/member/','0777',true);
			
		
			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'upload/member/'.$newName))
			{
				
				$str = 'upload/member/'.$newName;
				$where = "member_id = ".$_SESSION['member_id']."";
				$update_array['idcard2'] = $str;
				//if(Db::update('address',$update_array, $where))
				//{
					echo "<script>parent.stopSend('".$str."');</script>";
				//}
				
			}else
			{
				$msg = '上传出错';
				echo "<script>parent.error('".$msg."');</script>";
			}
		}
		Tpl::showpage('member_upload');
	}	
	
	public function uploadimg2Op()
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
			
		
			if(!file_exists('upload/member/'))
				mkdir('upload/member/','0777',true);
			
			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'upload/member/'.$newName))
			{
				$str = 'upload/member/'.$newName;
				$where = "member_id = ".$_SESSION['member_id']."";
				$update_array['idcard2'] = $str;
				//if(Db::update('address',$update_array, $where))
				//{
					echo "<script>parent.stop('".$str."');</script>";
				//}

			}else
			{
				$msg = '上传出错';
				echo "<script>parent.error('".$msg."');</script>";
			}
		}
		Tpl::showpage('member_upload2');
	}
}
