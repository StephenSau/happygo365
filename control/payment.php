<?php

defined('haipinlegou') or exit('Access Invalid!');

class paymentControl extends BaseHomeControl{
	public $payment_info;
	public $order_info;

    //国内商家
    private static $internal=[20];
	
	public function indexOp(){
		
		Language::read('home_payment_index');
		
		if(empty($_GET['order_id'])){
			showMessage(Language::get('miss_argument'),SiteUrl,'html','error');
		}
		$payment_id = intval($_POST['payment_id']);
		if($payment_id <= 0){
			showMessage(Language::get('miss_argument'),SiteUrl.'/index.php?act=cart&op=order_pay&order_id='.$_GET['order_id'],'html','error');
		}

		if (!C('payment')){
			$payment_model = Model('gold_payment');
			$condition = array();
			$condition['payment_id'] = $payment_id;
			$payment_info = $payment_model->getRow($payment_id);
		}else{
			$payment_model	= Model('payment');
			$payment_info	= $payment_model->getPaymentById($payment_id);
		}
		if (empty($payment_info) || $payment_info['payment_state'] != 1){
			showMessage(Language::get('payment_index_store_not_support'),SiteUrl.'/index.php?act=cart&op=order_pay&order_id='.$_GET['order_id'],'html','error');
		}
		if(!$payment_model->checkPayment($payment_info['payment_code'])){
			showMessage(Language::get('payment_index_sys_not_support').$payment_info['payment_code'],SiteUrl.'/index.php?act=cart&op=order_pay&order_id='.$_GET['order_id'],'html','error');
		}
		$order = Model('order');
		
		$order_info	= $order->getOrderById(intval($_GET['order_id']));

		if(empty($order_info) or !is_array($order_info)){
			showMessage(Language::get('payment_index_order').Language::get('payment_index_not_exists'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
		
		if($_SESSION['member_id'] != $order_info['buyer_id']){
			showMessage(Language::get('payment_index_order').$order_info['order_sn'].Language::get('payment_index_not_exists'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
		
		if($order_info['order_state'] != '10'){
			showMessage(Language::get('payment_index_order').$order_info['order_sn'].Language::get('payment_index_pay_finish'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
        if(in_array($order_info['store_id'],static::$internal)&&($payment_info['payment_code']=='yinlianbank')){
            $modelPayment=Model('payment');
            $payment_info=$modelPayment->getPaymentById(['where'=>"store_id={$order_info['store_id']} and payment_code='gnete'"],'condition');
        }
		$input	= array();
        if($payment_info['payment_code'] == 'cod') $input['order_state'] = 50;
		$input['payment_id']	= $payment_id;
		$input['payment_name']	= $payment_info['payment_name'];
		$input['payment_code']	= $payment_info['payment_code'];
		if(!$order->updateOrder($input,intval($_GET['order_id']))){
			showMessage(Language::get('payment_index_add_info_fail'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
	
		$order_info	= $order->getOrderById(intval($_GET['order_id']));
		//插入日志表
		$insert_array['order_state'] = '选择支付方式';
		$insert_array['change_state'] = '选择支付方式';
		$insert_array['operator'] = $_SESSION['member_name'];
		$insert_array['order_id'] = $order_info['order_id'];
		$insert_array['log_time'] = time();
		$id = Db::insert('order_log', $insert_array);
		$order_info['log_id'] = $id;
		
		//添加银联信息
		$yinlian = Model('yinlian');
		$row = $yinlian->where(array('store_id'=>$order_info['store_id']))->find();
        
        if($order_info['payment_code'] == 'yinlianbank'){
            $order_info['shanghu_id'] = '0HY';
   		    $order_info['yinlian'] ='api/payment/cer/1.cer';
   		    $order_info['shanghu'] = 'api/payment/cer/1.pem';
    		$order_info['code'] = 'ckq5ef0n6unds9dl3fae6sswyi2dthvh';
        }elseif($order_info['payment_code'] == 'gnete'){
            $order_info['shanghu_id'] = '0HX';
    	//	$order_info['yinlian'] ='api/payment/cer/1.cer';
    		//$order_info['shanghu'] = 'api/payment/cer/1.pem';
    		$order_info['code'] = 'u3p3ds1yo5wco8dacpi62equs17ttn88';	
        }
		
		//添加买家信息
		$member = Model('member');
		$row = $member->where(array('member_id'=>$order_info['buyer_id']))->find();
		$order_info['member_id_card'] = $row['member_id_card'];	
		$order_info['member_truename'] = $row['member_truename'];
		
		//添加平台企业备案号
		$order_info['company_num'] = C( "hs.company_num" );
		
		$_SESSION['payment_info'] = $payment_info;
		$_SESSION['order_info'] = $order_info;
		if(empty($order_info) or !is_array($order_info)){
			showMessage(Language::get('payment_index_order').$order_info['order_sn'].Language::get('payment_index_refresh_fail'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
		
			if($payment_info['payment_online']=='1' && $order_info['payment_code'] != 'predeposit'){
				$inc_file = BasePath.DS.'api'.DS.'payment'.DS.$order_info['payment_code'].DS.$order_info['payment_code'].'.php';
                if(!file_exists($inc_file)){
					showMessage(Language::get('payment_index_lose_file').$payment_info['payment_name'],SiteUrl.'/index.php?act=member&op=order','html','error');
				}
				require_once($inc_file);
				$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
	    		$payment_api	= new $order_info['payment_code']($payment_info,$order_info);
				if($order_info['payment_code'] == 'chinabank' || $order_info['payment_code'] == 'yinlianbank' || $order_info['payment_code'] == 'gnete') {
					
					$payment_api->submit();
					exit;
				} else {
					@header("Location:".$payment_api->get_payurl());
					exit;
				}
			}else{
				if ($payment_info['payment_code'] == 'offline'){
					$this->offline_pay($order_info);
				}elseif ($payment_info['payment_code'] == 'predeposit'){
					$this->predeposit_pay($order_info);
				}elseif ($payment_info['payment_code'] == 'cod'){
					$this->cod_pay($order_info);
				}else {
					showMessage(Language::get('payment_index_store_not_support'),SiteUrl.'/index.php?act=member&op=order','html','error');
				}
			}
		
	}
	
	public function notifyOp(){

		$success	= 'success';
		$fail		= 'fail';
		
		if(empty($_POST['out_trade_no']))exit($fail);
        $trade_no = $_POST['trade_no'];
		$order		= Model('order');
		$order_info	= $order->getOrderByOutSn($_POST['out_trade_no']);
		
		if(!is_array($order_info) or empty($order_info))exit($fail);
		if(empty($order_info['payment_id']))exit($fail);
		
		$payment_id	= $order_info['payment_id'];
		if (!C('payment')){
			$payment_model = Model('gold_payment');
			$condition = array();
			$condition['payment_id'] = $payment_id;
			$payment_info = $payment_model->getRow($payment_id);					
		}else{
			$payment_model	= Model('payment');
			$payment_info	= $payment_model->getPaymentById($payment_id);
		}
		
		if(!is_array($payment_info) or empty($payment_info))exit($fail);
		
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.$order_info['payment_code'].DS.$order_info['payment_code'].'.php';
		
		if(!file_exists($inc_file))exit($fail);
		
		require_once($inc_file);
		
		$payment_api	= new $order_info['payment_code']($payment_info,$order_info);
		
		if(!$payment_api->notify_verify())exit($fail);

        //判断是否支付成功
        if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序
        }else{
            exit($_POST['trade_status']);
        }

		$input	= $payment_api->getUpdateParam($_POST);
		
		if ($order_info['payment_code'] == 'alipay'){
			if($input['order_state']>0 and $input['order_state']<40) $input['payment_direct']	= '2';
			if($input['order_state']==40 and $order_info['payment_direct']==1){ 
				$input['order_state'] = 20;
                $input['examine'] = 1;
                $input['payno']=$trade_no;
				$input['payment_time']	= time();
				$input['finnshed_time']	= '';
                $this->cart_additional($order_info['order_id'],$order_info['order_sn']);
			}
		}
			$input['payno']=$_POST['out_trade_no'];
		
		if(empty($input)){
			exit($success);
		}elseif(!empty($input['error'])){
			exit($fail);
		}elseif($input['order_state']>0 and $order->updateOrder($input,$order_info['order_id']) and $order->addLogOrder($input['order_state'],$order_info['order_id'])){
			$model_member	= Model('member');
			
			$buyer	= $model_member->infoMember(array('member_id'=>$order_info['buyer_id']));
			if ($GLOBALS['setting_config']['points_isuse'] == 1 && $input['order_state'] == 40){
				$points_model = Model('points');
				$points_model->savePointsLog('order',array('pl_memberid'=>$buyer['member_id'],'pl_membername'=>$buyer['member_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
			}
		
			$seller	= $model_member->infoMember(array('member_id'=>$order_info['seller_id']));
			$param	= array(
				'site_url'	=> SiteUrl,
				'site_name'	=> $GLOBALS['setting_config']['site_name'],
				'buyer_name'	=> $order_info['buyer_name'],
				'seller_name'	=> $seller['member_name'],
				'order_sn'	=> $order_info['order_sn'],
				'order_id'	=> $order_info['order_id']
			);
			switch($input['order_state']){
				case 20:
					
					if($order_info['order_state'] != 20){
					//付款成功，扣减库存//支付成功后更新商品库存
					$order_id = array('order_id' => (int)$order_info['order_id']);
					$order_goods = $order->OrderGoodsList($order_id);
					//更新订单商品库存和销售记录
					$model_store_goods	= Model('goods');
					foreach ($order_goods as $key => $val ){
						//获取商品库存扣减基数
						//$goods_store_base = $model_store_goods->getSpecGoodsWhere(array('spec_id' => $val['spec_id']),"store_base");
						//计算商品实际购买数量
						$goods_num = $val['goods_num'] * $val['store_base'];
			
						$model_store_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$goods_num,'sign'=>'decrease'),'spec_salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['spec_id']);
						$model_store_goods->updateGoods(array('salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['goods_id']);
					}
					}
					
					$this->send_notice($order_info['seller_id'],'email_toseller_online_pay_success_notify',$param);
					break;
				case 30:					
					$this->send_notice($order_info['buyer_id'],'email_tobuyer_shipped_notify',$param);
					break;
				case 40:
					$this->send_notice($order_info['seller_id'],'email_toseller_finish_notify',$param);
					$this->send_notice($order_info['buyer_id'],'email_tobuyer_cod_order_finish_notify',$param);
					break;
			}
            $modelAliDeclare=Model('ali_order_declare');
            $aliDeclareInfo=$modelAliDeclare->getAliDeclare([
                'where'=>"order_out_sn={$_POST['out_trade_no']}",
                'field'=>"*"
            ]);
            //支付宝支付成功后发送支付信息到支付宝报关接口
            if(empty($aliDeclareInfo)) {
                if (!in_array((int)$order_info['store_id'], static::$internal) && (string)$order_info['payment_code'] === 'alipay') {
                    $this->alidelare([
                        'out_sn' => $_POST['out_trade_no'],
                        'trade_no' => $trade_no,
                        'order_amount' => $order_info['order_amount']
                    ]);
                }
            }

			exit($success);
		}else{
			exit($fail);
		}
	}
    
    
    /**
     * 
     * 汽车馆附加数据
     * */
    private function cart_additional($orderid,$order_sn){
        $param=array();
        $param['table']='order_goods';
		$param['field'] = '*';
		$param['where']="order_id=".$orderid;
		$list = Db::select($param);
        
        foreach($list as $vallist){
                 if($vallist['d_specvalue']){
                        $content=unserialize($vallist['d_specvalue']);
                      if($content){
                     foreach($content as $key=>$val){
                         $i=0;
                         
                         
                         foreach($val['c_detail'] as $keys=>$vals){
                            if($i==0){
                                 $i++;
                                continue;
                            }
                        $dspecarr=array(); 
                        $dspecarr['type']=$val['c_type'][0]; 
                        $dspecarr['detail']=$val['c_detail'][$keys];
                        $dspecarr['d_num']=$val['c_num'][$keys];
                        $dspecarr['d_price']=$val['c_price'][$keys];
                        $dspecarr['d_omodel']=$val['c_omodel'][$keys];
                        $dspecarr['d_rbrand']=$val['c_rbrand'][$keys];
                        $dspecarr['d_rmodel']=$val['c_rmodel'][$keys];
                        $dspecarr['d_cost']=$val['c_cost'][$keys];
                       
                        $dspecarr['d_specid']=$vallist['spec_id'];
                        $dspecarr['og_goods_num']=$vallist['goods_num'];
                        $dspecarr['og_rid']=$vallist['rec_id'];
                        $dspecarr['order_sn']=$order_sn;
                        $dspecarr['d_addtime']=time();
                        $insert_id = Db::insert('car_detail',$dspecarr);
                        }
                   }
                   
                   }
                   /**
                       $arr = explode("\n",$d_specid_array);
                       $dspecarr['type']=$arr[0]; 
                       unset($arr[0]);
                       foreach($arr as $key=>$val){
                            $arra=array();
                            $arra = explode(":",$val);
                            $dspecarr['detail']=$arra[0];
                            $arrs = explode("|",$arra[1]);
                            $dspecarr['d_num']=$arrs[0];
                            $dspecarr['d_price']=$arrs[1];
                            $dspecarr['d_omodel']=$arrs[2];
                            $dspecarr['d_rbrand']=$arrs[3];
                            $dspecarr['d_rmodel']=$arrs[4];
                            $dspecarr['d_cost']=$arrs[5];
                            $dspecarr['d_specid']=$vallist['spec_id'];
                            $dspecarr['og_goods_num']=$vallist['goods_num'];
                            $dspecarr['og_rid']=$vallist['rec_id'];
                            $dspecarr['order_sn']=$order_sn;
                            $dspecarr['d_addtime']=time();
                            $insert_id = Db::insert('car_detail',$dspecarr);
                            
                       }
                        
                    */
                }
            
        }
        
    }
	
      //发送短信new
    private function sendsmsnew($ordernum,$mobile)
    {
           if(!preg_match("/1[3458]{1}\d{9}$/",trim($mobile))){  
              //  echo 0;
            } 
       
        $post_data = array();
        $post_data['userid'] = 83;
        $post_data['account'] = 'guangwu';
        $post_data['password'] = '123456';
        $post_data['content'] = '您的订单【'.$ordernum.'】已确认收到，感谢您在海品乐购购物! 【海品乐购】'; //短信内容需要用urlencode编码下
        $post_data['mobile'] = $mobile;
        $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
        $url='http://113.11.210.114:5888/sms.aspx?action=send';
        $o='';
        foreach ($post_data as $k=>$v)
        {
           $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
         $result = curl_exec($ch);
          $ispass=stripos($result, 'ok');
         if($ispass>0){
            //   echo 1;
         }else{
            //  echo 0;
         }
    }
    
    public function chinapayNotifyReturnOp(){
        
        Language::read('home_payment_index');
		$url	= SiteUrl."/index.php?act=member&op=order";
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.'yinlianbank'.DS.'yinlianbank.php';
		require_once($inc_file);
        $chinapay = new yinlianbank($_SESSION['payment_info'], $_SESSION['order_info']);
		$payResult = $chinapay->return_verify();
        if($payResult){
            
            $orderSnExp = explode('-',$payResult['OrderNo']);

    		$order_sn = $orderSnExp[0];
    		
    		$param['table']='order';
    		$param['field'] = 'order_sn';
    		$param['value']=$order_sn;
    		$row = Db::getRow($param,"*");
    
    		if(!empty($row))
    		{
    			if($row['order_state']<20){
    			 
                    $data['order_state'] = 20;
                    $data['payno'] = $payResult['PayNo'];
        			$data['payment_time'] = time();
                    $data['examine'] = 1;
        			if(Db::update('order',$data,"order_sn='".$order_sn."'"))
        			{
        				
                       $this->cart_additional($row['order_id'],$order_sn);
                        
                        // 插入商检电子订单
                       $this->ciq_orderrecord_add($row['order_id'],$row);
                       
                       //付款成功，扣减库存//支付成功后更新商品库存
                       $order		= Model('order');
                       $order_id = array('order_id' => (int)$row['order_id']);
                       $order_goods = $order->OrderGoodsList($order_id);
                       //更新订单商品库存和销售记录
                       $model_store_goods	= Model('goods');
                       foreach ($order_goods as $key => $val ){
                       	//获取商品库存扣减基数
                       	//$goods_store_base = $model_store_goods->getSpecGoodsWhere(array('spec_id' => $val['spec_id']),"store_base");
                       	//计算商品实际购买数量
                       	$goods_num = $val['goods_num'] * $val['store_base'];
                       		
                       	$model_store_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$goods_num,'sign'=>'decrease'),'spec_salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['spec_id']);
                       	$model_store_goods->updateGoods(array('salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['goods_id']);
                       }                        
                        
                        $this->sendsmsnew($order_sn,$row['buyer_name']);
                        echo "OK";
        			}
                }else{
                    echo "OK";
                }
    				
    		}else{
    		  echo "OK";
    		}
            
        }else{
            echo "OK";
        }
	
		
		
        
    }

	public function chinapayReturnOp(){

		Language::read('home_payment_index');
		$url	= SiteUrl."/index.php?act=member&op=order";
		
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.'yinlianbank'.DS.'yinlianbank.php';
		
		if(!file_exists($inc_file))showMessage(Language::get('payment_index_lose_file'),SiteUrl,'html','error');
		
		require_once($inc_file);

		$chinapay = new yinlianbank($_SESSION['payment_info'], $_SESSION['order_info']);
		
		$payResult = $chinapay->return_verify();

		if(!$payResult)showMessage(Language::get('payment_index_identify_fail'),SiteUrl,'html','error');
		
		$orderSnExp = explode('-',$payResult['OrderNo']);

		$order_sn = $orderSnExp[0];
		
		$param['table']='order';
		$param['field'] = 'order_sn';
		$param['value']=$order_sn;
		$row = Db::getRow($param,"*");

		if(!empty($row))
		{
		  if($row['order_state']<20){
		      
                $data['order_state'] = 20;
                $data['payno'] = $payResult['PayNo'];
    			$data['payment_time'] = time();
                $data['examine'] = 1;
    			if(Db::update('order',$data,"order_sn='".$order_sn."'"))
    			{
    				
                    $this->cart_additional($row['order_id'],$order_sn);
                    
                    // 插入商检电子订单
                    $this->ciq_orderrecord_add($row['order_id'],$row);
                    
                    //支付成功更新商品库存
					$order		= Model('order');	
					//订单商品
					$order_id = array('order_id' => (int)$row['order_id']);
					$order_goods = $order->OrderGoodsList($order_id);
					//更新订单商品库存和销售记录
					$model_store_goods	= Model('goods');
					foreach ($order_goods as $key => $val ){
						//获取商品库存扣减基数
						//$goods_store_base = $model_store_goods->getSpecGoodsWhere(array('spec_id' => $val['spec_id']),"store_base");
						//计算商品实际购买数量
						$goods_num = $val['goods_num'] * $val['store_base'];
						
						$model_store_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$goods_num,'sign'=>'decrease'),'spec_salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['spec_id']);
						$model_store_goods->updateGoods(array('salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['goods_id']);			
					}                 
                    
                    
                    $this->sendsmsnew($order_sn,$row['buyer_name']);
                    echo "<script>alert('支付成功！');location.href='".$url."';</script>";
    			}
             }else{
                  echo "<script>location.href='".$url."';</script>";
             }
				
		}
		
	}
    
    
     public function gnetepayNotifyReturnOp(){

		Language::read('home_payment_index');
		$url	= SiteUrl."/index.php?act=member&op=order";
		
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.'gnete'.DS.'gnete.php';
		
		require_once($inc_file);

		$chinapay = new gnete($_SESSION['payment_info'], $_SESSION['order_info']);
		
		$payResult = $chinapay->return_verify();
        if($payResult){
	
    		$order_sn=$payResult['OrderNo'];
    		$param['table']='order';
    		$param['field'] = 'order_sn';
    		$param['value']=$order_sn;
    		$row = Db::getRow($param,"*");
    
    		if(!empty($row))
    		{
    			if($row['order_state']<20){
    			 
                    $data['order_state'] = 20;
                    $data['payno'] = $payResult['PayNo'];
        			$data['payment_time'] = time();
                    $data['examine'] = 1;
        			if(Db::update('order',$data,"order_sn='".$order_sn."'"))
        			{
        			   $this->cart_additional($row['order_id'],$order_sn);                   
                        
                        
                        $this->sendsmsnew($order_sn,$row['buyer_name']); 
        				echo "OK";
        			}
               }else{
                    echo "OK";
               }
    				
    		}else{
    		  echo "OK";
    		}
      }else{
        echo "OK";
      }
		
	}
    
    public function gnetepayReturnOp(){

		Language::read('home_payment_index');
		$url	= SiteUrl."/index.php?act=member&op=order";
		
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.'gnete'.DS.'gnete.php';
		
		if(!file_exists($inc_file))showMessage(Language::get('payment_index_lose_file'),SiteUrl,'html','error');
		
		require_once($inc_file);

		$chinapay = new gnete($_SESSION['payment_info'], $_SESSION['order_info']);
		
		$payResult = $chinapay->return_verify();

		if(!$payResult)showMessage(Language::get('payment_index_identify_fail'),SiteUrl,'html','error');
		
	//	$orderSnExp = explode('-',$payResult['OrderNo']);

		//$order_sn = $orderSnExp[0];
      // print_r($payResult['OrderNo']);
		$order_sn=$payResult['OrderNo'];
		$param['table']='order';
		$param['field'] = 'order_sn';
		$param['value']=$order_sn;
		$row = Db::getRow($param,"*");

		if(!empty($row))
		{
		    if($row['order_state']<20){
            
            	$data['order_state'] = 20;
                $data['payno'] = $payResult['PayNo'];
    			$data['payment_time'] = time();
                $data['examine'] = 1;
    			if(Db::update('order',$data,"order_sn='".$order_sn."'"))
    			{
    			  $this->cart_additional($row['order_id'],$order_sn);        
                    
                    
                    $this->sendsmsnew($order_sn,$row['buyer_name']); 
    				echo "<script>alert('支付成功！');location.href='".$url."';</script>";
    			}
		    }else{
		      	echo "<script>location.href='".$url."';</script>";
		    }
		}
		
	}
	

	public function returnOp(){
		
		Language::read('home_payment_index');
		$url	= SiteUrl."/index.php?act=member&op=order";
	
		if(empty($_GET['out_trade_no']))showMessage(Language::get('miss_argument'),SiteUrl,'','html','error');
	
		$order		= Model('order');
		
		$order_info	= $order->getOrderByOutSn($_GET['out_trade_no']);

        //支付宝交易流水号
        $trade_no=$_GET['trade_no'];

		
		if(!is_array($order_info) or empty($order_info))showMessage(Language::get('payment_index_spec_order_not_exists1').$_GET['out_trade_no'].Language::get('payment_index_spec_order_not_exists2'),SiteUrl,'html','error');
		if(empty($order_info['payment_id']))showMessage(Language::get('payment_index_miss_pay_method'),SiteUrl,'html','error');
		
		$payment_id	= $order_info['payment_id'];
		if (!C('payment')){
			$payment_model = Model('gold_payment');
			$condition = array();
			$condition['payment_id'] = $payment_id;
			$payment_info = $payment_model->getRow($payment_id);					
		}else{
			$payment_model	= Model('payment');
			$payment_info	= $payment_model->getPaymentById($payment_id);
		}
		
		if(!is_array($payment_info) or empty($payment_info))showMessage(Language::get('payment_index_miss_pay_method_data'),SiteUrl,'html','error');
		
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.$order_info['payment_code'].DS.$order_info['payment_code'].'.php';
		
		if(!file_exists($inc_file))showMessage(Language::get('payment_index_lose_file').$payment_info['payment_name'],SiteUrl,'html','error');
		
		require_once($inc_file);
		
		$payment_api	= new $order_info['payment_code']($payment_info,$order_info);
	
		if(!$payment_api->return_verify())showMessage(Language::get('payment_index_identify_fail'),SiteUrl,'html','error');

        //判断是否支付成功
        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序
        }else{
            showMessage("支付异常",SiteUrl."/index.php?act=member_snsindex");
        }
		
		$input	= $payment_api->getUpdateParam($_GET);
		if ($order_info['payment_code'] == 'alipay'){
			if($input['order_state']>0 and $input['order_state']<40) $input['payment_direct']	= '2';
			if($input['order_state']==40 and $order_info['payment_direct']==1){ 
				$input['order_state'] = 20;
				$input['payment_time']	= time();
				$input['finnshed_time']	= '';
			}
		}
		
		if($order_info['order_state'] != 20 && $input['order_state'] == 20){
		//支付成功后更新商品库存
		$order_id = array('order_id' => (int)$order_info['order_id']);
		$order_goods = $order->OrderGoodsList($order_id);
		//更新订单商品库存和销售记录
		$model_store_goods	= Model('goods');
		foreach ($order_goods as $key => $val ){
			//获取商品库存扣减基数
			//$goods_store_base = $model_store_goods->getSpecGoodsWhere(array('spec_id' => $val['spec_id']),"store_base");
			//计算商品实际购买数量
			$goods_num = $val['goods_num'] * $val['store_base'];
			
			$model_store_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$goods_num,'sign'=>'decrease'),'spec_salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['spec_id']);
			$model_store_goods->updateGoods(array('salenum'=>array('value'=>$goods_num,'sign'=>'increase')),$val['goods_id']);
		}
		}
		
        //支付宝支付成功后发送支付信息到支付宝报关接口
        if(!in_array((int)$order_info['store_id'],static::$internal)&&(string)$order_info['payment_code']==='alipay'){
            $this->alidelare([
                'out_sn'=>$_GET['out_trade_no'],
                'trade_no'=>$trade_no,
                'order_amount'=>$order_info['order_amount']
            ]);
        }
        $input['payno']=$trade_no;
		if(empty($input)){
			showMessage(Language::get('payment_index_order_ensure'),$url);
		}elseif(!empty($input['error'])){
			showMessage(Language::get('miss_argument'),SiteUrl,'html','error');
		}elseif($input['order_state']>0 and $order->updateOrder($input,$order_info['order_id']) and $order->addLogOrder($input['order_state'],$order_info['order_id'])){
			if ($order_info['payment_code'] == 'tenpay'){
				$url = SiteUrl."/index.php?act=payment&op=payment_success";
				showMessage(Language::get('payment_index_deal_order_success'),$url,'tenpay');
			}else {
				showMessage(Language::get('payment_index_deal_order_success'),$url);
			}
		}else{
			showMessage(Language::get('payment_index_deal_order_fail'),'','html','error');
		}
	}

    /**
     *  调用情景：支付宝支付成功后，跨境订单
     *  推送支付信息去支付宝报关接口
     *
     */
    protected function alidelare($param)
    {
        include_once(BasePath."/api/alilibs/alipay_submit.class.php");
        include_once(BasePath."/api/alilibs/alipay.config.php");
        /**************************请求参数**************************/

        //报关流水号
        $out_request_no = $param['out_sn'];

        //支付宝交易号
        $trade_no = $param['trade_no'];

        //电商平台编号
        $merchant_customs_code = HPLEGO_PLATFORM_NO;

        //商户海关备案名称
        $merchant_customs_name = HPLEGO_REGNAME;

        //报关金额
        $amount = $param['order_amount'];

        //海关编号
        $customs_place = HG_GUANGZHOU;

        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.acquire.customs",
            "partner" => trim($alipay_config['partner']),
            "out_request_no"	=> $out_request_no,
            "trade_no"	=> $trade_no,
            "merchant_customs_code"	=> $merchant_customs_code,
            "merchant_customs_name"	=> $merchant_customs_name,
            "amount"	=> $amount,
            "customs_place"	=> $customs_place,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $timeNow=time();
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
        $result=simplexml_load_string($html_text,'SimpleXMLElement',LIBXML_NOCDATA );
        if((string)$result->response->alipay->result_code==='SUCCESS'&&(string)$result->is_success==='T') {
            $modelAliDeclare = Model('ali_order_declare');
            $modelAliDeclare->addAliDeclare([
                'order_out_sn' => $out_request_no,
                'declare_time' => $timeNow,
                'declare_status' => 1,
                'declare_type' => 1,
                'declare_no' => (string)$result->response->alipay->alipay_declare_no,
                'custom_no' => HG_GUANGZHOU
            ]);
        }
    }
	
	public function payment_successOp(){
		
		Language::read('home_payment_index');
		$url	= SiteUrl."/index.php?act=member&op=order";
		
		showMessage(Language::get('payment_index_deal_order_success'),$url);
	}
	
	public function cod_pay($order_info){
		
		Language::read('home_payment_index');
		$order	= Model('order');
		$input	= array();
		$input['pay_message']	= trim($_POST['pay_message']);
		$input['payment_time']	= time();
		if($order->updateOrder($input,intval($order_info['order_id']))){
			showMessage(Language::get('payment_index_deal_order_success'),SiteUrl.'/index.php?act=member&op=order');
		}else{
			showMessage(Language::get('payment_index_deal_order_fail'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
	}
	
	public function offline_pay($order_info){
		Language::read('home_payment_index');
		$order	= Model('order');
		$input	= array();
		$input['order_state']	= $order_info['order_state'] == 50 ? 50 : 11;
		$input['pay_message']	= serialize($this->stripslashes_deep($_POST['offline']));
		$input['payment_time']	= TIMESTAMP;
		if($order->updateOrder($input,intval($order_info['order_id']))){
			
			$member_model	= Model('member');
			$seller	= $member_model->infoMember(array('member_id'=>$order_info['seller_id']));
			$param	= array(
				'site_url'	=> SiteUrl,
				'site_name'	=> $GLOBALS['setting_config']['site_name'],
				'buyer_name'	=> $order_info['buyer_name'],
				'seller_name'	=> $seller['member_name'],
				'order_sn'	=> $order_info['order_sn'],
				'order_id'	=> intval($order_info['order_id']),
				'pay_message'	=> trim($_POST['pay_message'])
			);
			$this->send_notice($order_info['seller_id'],'email_toseller_offline_pay_notify',$param);
			showMessage(Language::get('payment_index_deal_order_success'),SiteUrl.'/index.php?act=member&op=order');
		}else{
			showMessage(Language::get('payment_index_deal_order_fail'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
	}
    
   
    
	
	public function predeposit_pay($order_info){
		
		Language::read('home_payment_index');
		$order	= Model('order');
		if ($order_info['order_state'] != 10){
			showMessage(Language::get('payment_index_spec_order_not_exists1').$order_info['order_sn'].Language::get('payment_index_pay_finish'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
		$member_model	= Model('member');
		$buyer_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
		if (!is_array($buyer_info) || count($buyer_info)<=0){
			showMessage(Language::get('payment_index_spec_order_not_exists1').$order_info['order_sn'].Language::get('payment_index_buyerinfo_error'),SiteUrl.'/index.php?act=member&op=order','html','error');
		}
		if (floatval($buyer_info['available_predeposit']) < $order_info['order_amount']){
			showMessage(Language::get('payment_predeposit_short_error'),SiteUrl.'/index.php?act=predeposit','html','error');
		}
		$predeposit_model = Model('predeposit');
		$log_arr = array();
		$log_arr['memberid'] = $_SESSION['member_id'];
		$log_arr['membername'] = $_SESSION['member_name'];
		$log_arr['logtype'] = '0';
		$log_arr['price'] = -$order_info['order_amount'];
		$log_arr['desc'] = Language::get('payment_index_order').$order_info['order_sn'].Language::get('payment_order_predeposit_logdesc');
		$predeposit_model->savePredepositLog('order',$log_arr);
		$log_arr['logtype'] = '1';
		$log_arr['price'] = $order_info['order_amount'];
		$log_arr['desc'] = Language::get('payment_index_order').$order_info['order_sn'].Language::get('payment_order_predepositfreeze_logdesc');
		$predeposit_model->savePredepositLog('order',$log_arr);
		unset($log_arr);
		
		$input	= array();
		$input['order_state']	= 20; 
		$input['pay_message']	= trim($_POST['pay_message']);
		$input['payment_time']	= time();
        $input['examine'] = 1;
		$result = $order->updateOrder($input,intval($order_info['order_id']));
		if($result){
			$order->addLogOrder('20',intval($order_info['order_id']));
			
			$member_model	= Model('member');
			$seller	= $member_model->infoMember(array('member_id'=>$order_info['seller_id']));
			$param	= array(
				'site_url'	=> SiteUrl,
				'site_name'	=> $GLOBALS['setting_config']['site_name'],
				'buyer_name'	=> $order_info['buyer_name'],
				'seller_name'	=> $seller['member_name'],
				'order_sn'	=> $order_info['order_sn'],
				'order_id'	=> intval($order_info['order_id']),
				'pay_message'	=> trim($_POST['pay_message'])
			);
			$this->send_notice($order_info['seller_id'],'email_toseller_online_pay_success_notify',$param);
            
            $this->cart_additional($order_info['order_id'],$order_info['order_sn']);
            
            $this->sendsmsnew($order_info['order_sn'],$order_info['buyer_name']);
            
            
			showMessage(Language::get('payment_index_deal_order_success'),SiteUrl."/index.php?act=member&op=order");
		}else{
			showMessage(Language::get('payment_index_deal_order_fail'),SiteUrl."/index.php?act=member&op=order",'html','error');
		}
	}
	
	public function sendOp(){
		if(empty($_GET['order_id'])){
			showMessage(Language::get('miss_argument'),SiteUrl."/index.php?act=store&op=store_order",'html','error');
		}
		$payment_api	= $this->getPaymentApiByOrderId(intval($_GET['order_id']),'send');
		$payment_api->sendGoods();
	}
	
	public function receiveOp(){
		if(empty($_GET['order_id'])){
			showMessage(Language::get('miss_argument'),SiteUrl."/index.php?act=member&op=order",'html','error');
		}
		$payment_api	= $this->getPaymentApiByOrderId(intval($_GET['order_id']),'receive');
		$payment_api->receiveGoods();
	}
	
	private function getPaymentApiByOrderId($order_id,$type){
		Language::read('home_payment_index');
		$order		= Model('order');
		$order_info	= $order->getOrderById($order_id,'simple');
		if(!is_array($order_info) || empty($order_info)){
			showMessage(Language::get('payment_index_spec_order_not_exists1').Language::get('payment_index_spec_order_not_exists2'),'','html','error');
		}
		switch($type){
			case 'send':
				if($_SESSION['member_id'] != $order_info['seller_id']){
					showMessage(Language::get('payment_index_spec_order_not_exists1').$order_info['order_sn'].Language::get('payment_index_not_belong_you'),'','html','error');
				}
				break;
			case 'receive':
				if($_SESSION['member_id'] != $order_info['buyer_id']){
					showMessage(Language::get('payment_index_spec_order_not_exists1').$order_info['order_sn'].Language::get('payment_index_not_belong_you'),'','html','error');
				}
				break;
		}
		if(empty($order_info['payment_id']))showMessage(Language::get('payment_index_miss_pay_method'),SiteUrl,'html','error');
		$payment_id	= $order_info['payment_id'];
		if (!C('payment')){
			$payment_model = Model('gold_payment');
			$condition = array();
			$condition['payment_id'] = $payment_id;
			$payment_info = $payment_model->getRow($payment_id);					
		}else{
			$payment_model	= Model('payment');
			$payment_info	= $payment_model->getPaymentById($payment_id);
		}
		if(!is_array($payment_info) or empty($payment_info))showMessage(Language::get('payment_index_miss_pay_method_data'),SiteUrl,'html','error');
		$payment_info['payment_config']	= unserialize($payment_info['payment_config']);
		
		$inc_file = BasePath.DS.'api'.DS.'payment'.DS.$order_info['payment_code'].DS.$order_info['payment_code'].'.php';
		
		if(!file_exists($inc_file))showMessage(Language::get('payment_index_lose_file').$payment_info['payment_name'],SiteUrl,'html','error');
		
		require_once($inc_file);
		
		$payment_api	= new $order_info['payment_code']($payment_info,$order_info);
		return $payment_api;
	}

	
	public function stripslashes_deep($value){
	    $value = is_array($value) ? array_map(array($this,'stripslashes_deep'), $value) : stripslashes($value);
	    return $value;
	}
	
	public function ciq_orderrecord_add($order_id, $order){
		// 插入商检电子订单
		if(!empty($order) && is_array($order)){
			$row = $order;
		}else{
			$param ['table'] = 'order';
			$param ['field'] = 'order_id';
			$param ['value'] = $order_id;
			$row = Db::getRow ( $param, "*" );			
		}
		$ciqorder = array ();
		// 订单信息
		$ciqorder ['order_id'] = $row ['order_id'];
		$ciqorder ['ordercode'] = $row ['order_sn'];
		$ciqorder ['orderdate'] = date ( 'Y-m-d H:i:s', $row ['add_time'] );
		$ciqorder ['fcy'] = $row ['goods_amount'];
		$ciqorder ['fcode'] = 'CNY';
		// 订单地址及收货人
		$param = array ();
		$param ['table'] = 'order_address';
		$param ['field'] = 'order_id';
		$param ['value'] = $row ['order_id'];
		$rowaddress = Db::getRow ( $param, "*" );
		$ciqorder ['recipientname'] = $rowaddress ['true_name'];
		$ciqorder ['recipientcredno'] = $rowaddress ['consignee_id_num'];
		$ciqorder ['recipienttel'] = $rowaddress ['mob_phone'];
		$ciqorder ['recipientaddress'] = $rowaddress ['area_info'] . " " . $row ['address'];
		$ciqorder ['zipcode'] = $rowaddress ['zip_code'];
		Db::insert ( 'orders_ciq_records', $ciqorder );
		// 插入商检电子订单产品
		$param = array ();
		$param ['table'] = 'order_goods';
		$param ['field'] = '*';
		$param ['where'] = "order_id=" . $row ['order_id'];
		$list = Db::select ( $param );
		foreach ( $list as $ordergood ) {
			$ciqordergood = array ();
			$ciqordergood ['ordercode'] = $row ['order_sn'];
			$ciqordergood ['gcode'] = $ordergood ['goods_item_no'];
			$ciqordergood ['qty'] = $ordergood ['goods_num'];
			$ciqordergood ['upric'] = $ordergood ['goods_price'];
			$ciqordergood ['dectotal'] = $ordergood ['goods_price'] * $ordergood ['goods_num'];
			Db::insert ( 'orders_ciq_records_goods', $ciqordergood );
		}
	}
}