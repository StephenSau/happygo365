<?php

defined('haipinlegou') or exit('Access Invalid!');
class cartControl extends BaseHomeControl {
    const MANSONG_STATE_PUBLISHED = 2;

	public function __construct() {
		parent::__construct();
		Language::read('home_cart_index');
		
		$op_arr = array('ajaxcart','add','drop');
		$op_str = '';
		$op_str = isset($_GET['op'])?$_GET['op']:$_POST['op'];
		if (!in_array($op_str,$op_arr) && !$_SESSION['member_id'] ){
			$current_url = request_uri();
			redirect('index.php?act=login&ref_url='.urlencode($current_url));
		}

		$noallowbuyop_arr = array('step1','step2');
		$noallowbuyop_str = '';
		$noallowbuyop_str = isset($_GET['op'])?$_GET['op']:$_POST['op'];
		if (in_array($noallowbuyop_str,$noallowbuyop_arr)){
			$member_model = Model('member');
	        $member_id = intval($_SESSION['member_id']);
	        $member_info = $member_model->infoMember(array('member_id'=>"{$member_id}"));
			if(empty($member_info) || !$member_info['is_buy']){
	        	showMessage(Language::get('cart_buy_noallow'),'','html','error');
	        }
	        unset($member_id);
	        unset($member_info);
	        unset($member_model);
		}
        //输出店铺控制器
        $shop_array = array('16'=>'act=dubai','19'=>'act=national','17'=>'act=japan','20'=>'act=car&op=sindex');
        Tpl::output('shop_array',$shop_array);

	}
	
	public function indexOp() {
		$model_cart	= Model('cart');
		
		//取出已有购物车信息
		$cart_goods	= array();
		$cart_goods	= $model_cart->listCart();

		//获取购物车列表信息
		//$cart = Model('cart');
		$cat = Model('goods_class');
		$cart_array	= array();
		if(!empty($cart_goods)) {
			//添加行邮税
			foreach ($cart_goods as $key=>$val) 
			{
                          
                            $model = Model();
                            $goods_spec_date = $model->table('goods_spec')->where(array('spec_id'=>$val['spec_id']))->find();
                                $goods_tax = $goods_spec_date['goods_tax'];
                                $val['ems']=$goods_tax;
                                $val['goods_store_prices']=$this->invitation($val['goods_store_price']);
                                $val['goods_all_prices']	=ncPriceFormat($val['goods_store_prices'] * $val['goods_num']);
				$val['goods_total_price'] = ncPriceFormat($val['goods_store_prices'] * $val['goods_num'])+ncPriceFormat($val['goods_store_price'] * $val['goods_num'] * $goods_tax);
				$cart_array[$val['store_id']][] = $val;
				$cart_array[$val['store_id']][0]['store_all_price'] += ncPriceFormat(floatval($val['goods_total_price']));
				$cart_array[$val['store_id']][0]['store_goods_all_price'] += ncPriceFormat(floatval($val['goods_all_prices']));
				$cart_array[$val['store_id']][0]['total_ems'] += ncPriceFormat($val['goods_all_prices']*$goods_tax);
            
                  
           }
                                
               //全部设置默认
            $changearr['checked']=1;
        	$result	= Db::update('cart',$changearr," AND store_id= '{$cart_goods[0]['store_id']}' AND member_id='{$_SESSION['member_id']}'"); 
            
            
          
			
			
			//头部我的商场和卖家中心按钮的输出
			if($_SESSION['is_login'] == '1'){
				$member_model	= Model('member');
				$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
				Tpl::output('member_info',$member_info);
			}
            
			
            //S脚部文章输出
			$list = $this->_article();
			//E脚部文章输出
          
			Tpl::output('cart_array',$cart_array);
			//输出满就送活动
            $mansong = $this->get_mansong(array_keys($cart_array));
            Tpl::output('mansong',$mansong);
            Tpl::showpage('cart');

        } else {
		
			//S脚部文章输出
			$list = $this->_article();
			//E脚部文章输出
		    //头部我的商场和卖家中心按钮的输出
			if($_SESSION['is_login'] == '1'){
				$member_model	= Model('member');
				$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
				Tpl::output('member_info',$member_info);
			}
			Tpl::showpage('cart_empty');
		}
		
	}
   
    private function get_mansong($store_id) {
      
        $mansong = array();
        $mansong_flag = FALSE;
        if (intval($GLOBALS['setting_config']['promotion_allow']) === 1){
            $model_mansong = Model('p_mansong');
            $param = array();
            $param['state'] = self::MANSONG_STATE_PUBLISHED;
            $current_time = time();
            $param['greater_than_start_time'] = $current_time;
            $param['less_than_end_time'] = $current_time;
            $param['store_id'] = $store_id;
            $param['limit'] = 5;
            $mansong_list = $model_mansong->getList($param);
            if(!empty($mansong_list) && is_array($mansong_list)) {
            	$model_mansong_rule = Model('p_mansong_rule');
            	foreach ($mansong_list as $v) {
            		$mansong_flag = FALSE;
	                $mansong_rule = $model_mansong_rule->getList(array('mansong_id'=>$v['mansong_id'],'order'=>'level asc'));
	                if(!empty($mansong_rule)) {
	                    $mansong_flag = TRUE;
	                    $mansong_info[$v['store_id']]['mansong_info'] = $v;
	                    $mansong_info[$v['store_id']]['mansong_rule'] = $mansong_rule;
	                    $mansong_info[$v['store_id']]['mansong_flag'] = $mansong_flag;
	                }
            	}
            }
        }
        return $mansong_info;
    }

	
	public function ajaxcartOp() {
		if ($_SESSION['member_id']){
			$model_cart	= Model('cart');
			$cart_goods	= array();
			$cart_goods	= $model_cart->listCart();			
			$cart_array	= array();
			if(!empty($cart_goods)){
				foreach ($cart_goods as $k=>$val){
					$cart_array['goodslist'][$k]['specid'] 	= $val['spec_id'];
					$cart_array['goodslist'][$k]['goodsid'] 	= $val['goods_id'];
					$cart_array['goodslist'][$k]['storeid'] 	= $val['store_id'];
					$cart_array['goodslist'][$k]['gname'] 	= $val['goods_name'];
					$cart_array['goodslist'][$k]['price'] 	= $val['goods_store_price'];
					$cart_array['goodslist'][$k]['images']	= thumb($val);
					$cart_array['goodslist'][$k]['num'] 		= $val['goods_num'];
					$cart_array['goodslist'][$k]['goods_all_price']	= ncPriceFormat($val['goods_store_price'] * $val['goods_num']);					
					$cart_array['goods_all_price'] += floatval($val['goods_store_price']) * intval($val['goods_num']);
				}
				$cart_array['goods_all_price'] = ncPriceFormat($cart_array['goods_all_price']);
			}
		}else{
			if (cookie('cart')){
				$cart_str = cookie('cart');
				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);
				$cookie_goods = unserialize($cart_str);
				if (!empty($cookie_goods)){
					foreach ($cookie_goods as $k=>$v){
						$v['specid'] 	= $k;
						$v['images']	= cthumb($v['images'],'tiny',$v['storeid']);
						$cart_array['goodslist'][] = $v;
						$cart_array['goods_all_price'] += floatval($v['price']) * intval($v['num']);
					}
					$cart_array['goods_all_price'] = ncPriceFormat($cart_array['goods_all_price']);
				}
			}
		}
		$cart_array['goods_all_num'] = count($cart_array['goodslist']);
		if (strtoupper(CHARSET) == 'GBK'){
			$cart_array = Language::getUTF8($cart_array);
		}
        $json_data = json_encode($cart_array);
        if (isset($_GET['callback']))   
        {  
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";  
        }  
        echo $json_data; 
        die;
	}
	/**
	 * 购物车添加商品
	 *
	 * @param
	 * @return
	 */
	public function addOp() {
		$spec_id	= intval($_GET['spec_id']);
		$quantity	= intval($_GET['quantity']);
                
                
                //查询店铺ID
                $mode_goods = Model();  
                $spec_info = $mode_goods->table('goods_spec')->where("spec_id= ".$spec_id."")->find();  
                $goods_info = $mode_goods->table('goods')->where("goods_id= ".$spec_info['goods_id']."")->find();               
                $store_id = $goods_info['store_id'];       
                
		if($spec_id <= 0 || $quantity <= 0) {
			echo json_encode(array('msg'=>Language::get('wrong_argument','UTF-8')));
			return;
		}
		$mode_goods= Model('goods');
		$goods_info	= $mode_goods->checkGoods(array('spec_id'=>"$spec_id"),'goods.*,goods_spec.*');
               
		if(empty($goods_info)) {
			echo json_encode(array('msg'=>Language::get('cart_add_goods_not_exists','UTF-8')));
			return ;
		}
		//登录状态，不能购买自己的商品
		if($_SESSION['member_id'] && $goods_info['store_id'] == $_SESSION['store_id']) {
			echo json_encode(array('msg'=>Language::get('cart_add_cannot_buy','UTF-8')));
			return ;
		}
		//判断库存
		if(intval($goods_info['spec_goods_storage'])<1) {
			echo json_encode(array('msg'=>Language::get('cart_add_stock_shortage','UTF-8')));
			return ;
		}
		if(intval($goods_info['spec_goods_storage'])<$quantity) {
			echo json_encode(array('msg'=>Language::get('cart_add_too_much','UTF-8')));
			return ;
		}
        $model_cart	= Model('cart');
		if(!empty($_SESSION['member_id'])){
			//登录状态
			$check_cart	= $model_cart->checkCart(array('cart_spec_id'=>"$spec_id",'cart_member_id'=>"{$_SESSION['member_id']}"));
			$check_cart	= $check_cart[0];
			//验证购物车商品是否已经存在
			if(empty($check_cart)) {
				$array				= array();
				$array['member_id']	= $_SESSION['member_id'];
				$array['store_id']	= $goods_info['store_id'];
				$array['goods_id']	= $goods_info['goods_id'];
				$array['goods_name']= $goods_info['goods_name'];
				$array['spec_id']	= $spec_id;
				//构造购物车规格信息
				$array['spec_info'] = '';
				if ($goods_info['spec_open'] == 1 && !empty($goods_info['spec_goods_spec']) && !empty($goods_info['spec_name'])){
					$spec_name = unserialize($goods_info['spec_name']);
					if (!empty($spec_name)){
						$spec_name = array_values($spec_name);
						$spec_goods_spec = unserialize($goods_info['spec_goods_spec']);
						$i = 0;
						foreach ($spec_goods_spec as $k=>$v){
							$array['spec_info'] .= $spec_name[$i].":".$v."&nbsp;";
							$i++;
						}
					}
				}

				$array['goods_store_price']	= $goods_info['spec_goods_price'];
				$array['goods_num']	= $quantity;
				$array['goods_images']	= $goods_info['goods_image'];
				
				//为商品添加行邮税和行邮率
				$cart = Model('cart');
				$cat = Model('goods_class');
				$gc_id = $mode_goods->where("goods_id= ".$array['goods_id']."")->find();
				$name = $cat->getGoodsClassNav($gc_id['gc_id']);
				
				//获取行邮税
				$param['table'] = 'goods_class';
				$param['field'] = 'gc_name';
				$param['value'] = $name[2]['title'];
				$gc_info = Db::getRow($param,"gc_ems" );

			//	$array['ems'] = floatval($gc_info['gc_ems']);
                                $array['ems'] = floatval($goods_info['goods_tax']);
				$array['normal_ems'] = $array['ems']*$goods_info['spec_goods_price']*$quantity;
				
				//（加入购物车前价格限制），一个订单有多件商品时，总价不能超过1000，但，一个订单单件商品（此商品为不可分割的）则不受此约束
				$goods_list = $model_cart->listCart($goods_info['store_id']);
				foreach($goods_list as $k=>$v)
				{
					if($goods_info['store_id'] == $v['store_id'])
					{
						$goods_num += $v['goods_num'];
						$goods_all_price += ncPriceFormat($v['goods_store_price'] * $v['goods_num']); //在此可添加行邮税						
					}
				}
				$goods_all_price = intval($goods_all_price);

//				if(($goods_info['goods_store_price']*$quantity+$goods_all_price) > 1000 && $goods_num == 1 && $store_id != 20)
//				{
//					$msg = '根据海关总署规定单笔订单不能超过1000元，单件商品超过1000元不在限制范围。';
//
//				}else if(($quantity+$goods_num) > 1 && ($goods_info['goods_store_price']*$quantity+$goods_all_price) > 1000 && $store_id != 20){
//					$msg = '根据海关总署规定单笔订单不能超过1000元';
//
//				}else{
//					$cart_state = $model_cart->addCart($array);
//				}
                $cart_state = $model_cart->addCart($array);
			}
			$all_price = 0;
			$cart_goods_num = 0;
			$cart_goods_num	= $model_cart->countCart(array('cart_member_id'=>"{$_SESSION['member_id']}"));
			$all_price		= $this->amountOp($model_cart);	
		}else {
		
			//非登录状态
			if (cookie('cart')){
				$cart_str = cookie('cart');
				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);//去除斜杠
				$cart_arr = unserialize($cart_str);
			}
			//判断商品是否已经加入购物车
			if (empty($cart_arr) || !in_array($spec_id,array_keys($cart_arr))){
				$cart_arr[$spec_id] = array('storeid'=>$goods_info['store_id'],'goodsid'=>$goods_info['goods_id'],'gname'=>$goods_info['goods_name'],'price'=>$goods_info['spec_goods_price'],'images'=>$goods_info['goods_image'],'num'=>$quantity);
			}
			//商品数量
			$cart_goods_num = 0;
			$cart_goods_num = count($cart_arr);
			//商品总价格
			$all_price = 0;
		
			if (!empty($cart_arr)){
				foreach ($cart_arr as $v){
					$all_price += ncPriceFormat($v['price'])*intval($v['num']);
				}
			}
//			if($all_price > 1000 && $cart_goods_num >1 && $store_id != 20)
//			{
//
//				$msg = '根据海关总署规定单笔订单不能超过1000元，单件商品超过1000元不在限制范围。';
//
//			}else
//			{
//				setNcCookie('cart',serialize($cart_arr),90*24*3600);//保存90天
//			}
			setNcCookie('cart',serialize($cart_arr),90*24*3600);//保存90天
		}
		
		setNcCookie('goodsnum',$cart_goods_num,2*3600);		// 购物车商品种数
		
		if(!empty($msg))
		{
			echo json_encode(array('msg'=>'购物总价已超过1000！'));
			
		}else
		{
			echo json_encode(array('done'=>'true','num'=>$cart_goods_num,'amount'=>ncPriceFormat($all_price)));
		}
	}
	
	public function updateOp() {
		$spec_id	= intval($_GET['spec_id']);
		$quantity	= intval($_GET['quantity']);
		$ems_triff	= $_GET['ems'];
		$mold	= $_GET['mold'];
		$store_id	= intval($_GET['store_id']);
                   

		if($spec_id <= 0 || $quantity <= 0) 
		{
			echo json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8')));
			die;
		}
		$mode_goods= Model('goods');
		$goods_info	= $mode_goods->checkGoods(array('spec_id'=>$spec_id),'goods.*,goods_spec.*');
		if(empty($goods_info)) {
			echo json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8')));
			die;
		}
		if(intval($goods_info['spec_goods_storage']) < $quantity) {
			echo json_encode(array('msg'=>Language::get('cart_index_stock_contact','UTF-8')));
			die;
		}
		$model_cart = Model('cart');
        
        if($_GET['checked']=='remove'){
                $changearr['checked']=0;
            	$result	= Db::update('cart',$changearr,"AND spec_id= '$spec_id' AND store_id= '$store_id' AND member_id='{$_SESSION['member_id']}'");
        }elseif($_GET['checked']=='add'){
            $changearr['checked']=1;
        	$result	= Db::update('cart',$changearr,"AND spec_id= '$spec_id' AND store_id= '$store_id' AND member_id='{$_SESSION['member_id']}'");
        }
        
        if($_GET['checkedall']=='remove'){
                $changearr['checked']=0;
            	$result	= Db::update('cart',$changearr," AND store_id= '$store_id' AND member_id='{$_SESSION['member_id']}'");
        }elseif($_GET['checkedall']=='add'){
            $changearr['checked']=1;
        	$result	= Db::update('cart',$changearr," AND store_id= '$store_id' AND member_id='{$_SESSION['member_id']}'");
        }
        
        
		
		//多件商品总物价超过1000就提示错误		
		$cart_info = $model_cart->listCart();
		/*
		//判断购物车内是否有多个店铺的商品
		$store_num = 0;
		foreach($cart_info as $k=>$v)
		{
			if($cart_info[$k]['store_id'] !== $cart_info[++$k]['store_id'])
			{
				$store_num++;
			}
		}
		*/
		//$total = $goods_info['spec_goods_price']+($ems_triff*$goods_info['spec_goods_price']);
		$total = $goods_info['spec_goods_price'];
		
		//计算总价
		$all_total = 0;
		$count_goods = 0;
		foreach($cart_info as $k=>$v)
		{
			if($v['store_id'] == $store_id && $v['spec_id']<>$goods_info['spec_id'] )
			{
				$all_total +=$v['goods_store_price']*$v['goods_num'];
				$count_goods++;					
			}			
			
		}
		if($cart_info){
			$onetotalprice=$goods_info['spec_goods_price']*$quantity;
			$count_goods++;
		}
		//判断是增加还是减少
		$all = 0;

		// if($mold == 'add'){
		
		// 	$all = $all_total+$onetotalprice;
			
		// }else if($mold == 'reduce'){
		
		// 	$all = $all_total+$onetotalprice;
		
		// }else{
		// 	$all = $all_total+$onetotalprice;
		// }
		$all = $all_total+$onetotalprice;

//        if($mold!='reduce') {
//            if ($count_goods >= 1 and $all > 1000 and $store_id != 20) {
//                echo json_encode(array('done' => 'true', 'message' => '叮，土豪，您已超过海关限额每单1000元喽！请低调，分次购买吧！'));
//                die;
//            }
//        }




		$ems = $ems_triff * $quantity*$goods_info['spec_goods_price'];
		$update_array['normal_ems'] = $ems; 
		Db::update('cart', $update_array,"spec_id = ".$spec_id."");
		$cart_state = $model_cart->updateCart(array('goods_num'=>$quantity),array('cart_spec_id'=>"$spec_id",'cart_member_id'=>"{$_SESSION['member_id']}"));

		if ($cart_state) 
		{
			$all_price	= $this->storeOp($model_cart,$goods_info['store_id']);
			$normal_price	= $this->store_oneOp($model_cart,$goods_info['store_id']);
            $total_ems = $this->emsOp($model_cart,$goods_info['store_id']);
          	echo json_encode(array('done'=>'true','subtotal'=>$goods_info['spec_goods_price']*$quantity+($ems_triff*$goods_info['spec_goods_price']*$quantity),'total'=>$goods_info['spec_goods_price']*$quantity,'amount'=>ncPriceFormat($all_price),'normal_amount'=>ncPriceFormat($normal_price),'total_ems'=>$total_ems,'ems'=>$ems));
			die;
		}else{
			echo json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8')));
			die;
		}
	}
	
	public function dropOp() {
		$spec_id	= intval($_GET['specid']);
		$store_id	= intval($_GET['storeid']);
		if($spec_id <= 0 || $store_id <= 0) {
			return;
		}		
		if ($_SESSION['member_id']){
			$model_cart	= Model('cart');
			$drop_state	= $model_cart->dropCartByCondition(array('cart_spec_id'=>"$spec_id",'cart_member_id'=>"{$_SESSION['member_id']}"));
			if($drop_state) {
				$quantity = $model_cart->countCart(array('cart_member_id'=>"{$_SESSION['member_id']}"));
				$store_quantity = $model_cart->countCart(array('cart_member_id'=>"{$_SESSION['member_id']}",'spec_store_id'=>"$store_id"));
				if ($quantity >0){
					$amount	= $this->amountOp($model_cart);
					$store_amount = $this->storeOp($model_cart,$store_id);
					$json_data = json_encode(array('done'=>'true','amount'=>ncPriceFormat($amount),'store_amount'=>ncPriceFormat($store_amount),'quantity'=>$quantity,'store_quantity'=>$store_quantity));
					setNcCookie('goodsnum',$quantity,2*3600);		
				}else {
					$json_data = json_encode(array('done'=>'true','amount'=>ncPriceFormat(0),'store_amount'=>ncPriceFormat(0),'quantity'=>0,'store_quantity'=>0));
					setNcCookie('goodsnum',0,2*3600);	
				}
			} else {
				$json_data = json_encode(array('msg'=>Language::get('cart_drop_del_fail','UTF-8')));
			}
		}else{
			$cart_arr = array();
			$all_price = 0;
			if (cookie('cart')){
				$cart_str = cookie('cart');
				if (get_magic_quotes_gpc()) $cart_str = stripslashes($cart_str);
				$cart_arr = unserialize($cart_str);
				if (!empty($cart_arr)){
					foreach ($cart_arr as $k=>$v){
						if ($k == $spec_id){
							unset($cart_arr[$k]);
						}else{
							$all_price += floatval($v['price'])*intval($v['num']);
						}
					}
					if (!empty($cart_arr)){
						$json_data = json_encode(array('done'=>'true','amount'=>ncPriceFormat($all_price),'quantity'=>count($cart_arr)));
						setNcCookie('cart',serialize($cart_arr),90*24*3600);
						setNcCookie('goodsnum',count($cart_arr),2*3600);		
					}else{
						$json_data = json_encode(array('done'=>'true','amount'=>ncPriceFormat(0),'quantity'=>0));
						setNcCookie('cart','',-1);
						setNcCookie('goodsnum',0,2*3600);		
					}
				}else {
					$json_data = json_encode(array('msg'=>Language::get('cart_drop_del_fail','UTF-8')));
				}
			}else {
				$json_data = json_encode(array('msg'=>Language::get('cart_drop_del_fail','UTF-8')));
			}
		}
        if (isset($_GET['callback']))   
        {  
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";  
        }  
        echo $json_data; 
        die;
	}
	
	private function amountOp($model_cart) {
		$cart_goods	= $model_cart->listCart();
		$all_price	= 0;
		if(!empty($cart_goods) && is_array($cart_goods)) {
			foreach ($cart_goods as $val) {
				$all_price	= ncPriceFormat($val['goods_store_price'] * $val['goods_num']) + $all_price;
			}
		}
		return $all_price;
	}
	
    private function invitation($normal_price){
        
        //是否邀请码会员
      if(C('predeposit_reg_switch')==1){
            $invitation_array=array();
            $invitation_array['table']='member';
            $invitation_array['field']='member_id';
            $invitation_array['value']=$_SESSION['member_id'];
            $invitation_data=DB::getRow($invitation_array);
            $predeposit_consume_month=C('predeposit_consume_month');//自注册起多少个月
            $predeposit_consume_discount=C('predeposit_consume_discount');//享多少折扣
            $predeposit_consume_discount=$predeposit_consume_discount/10;
            $member_reg_time=$invitation_data['member_time'];//注册时间
            $is_invitation=$invitation_data['is_invitation'];
            $predeposit_consume_month_endtime=$predeposit_consume_month*30*86400;
           
            
            if($is_invitation==1){
                $big_customer = "大客户";
                Tpl::output('big_customer',$big_customer);
                if(($predeposit_consume_month_endtime+$member_reg_time)>time()){
                    $d_money=$predeposit_consume_discount*$normal_price;
                    Tpl::output('is_invitation',$is_invitation);
                    Tpl::output('predeposit_consume_month_endtime',date("Y-m-d",$predeposit_consume_month_endtime+$member_reg_time));//折扣结束时间
                    return $normal_price=$d_money;
                }else{
                     return $normal_price;
                }
              
                
            }else{
                 return $normal_price;
            }
        }else{
             return $normal_price;
        }
    }
    
	private function storeOp($model_cart,$store_id) 
	{
		$cart_goods	= $model_cart->listCart($store_id);
		$store_price	= 0;
		if(!empty($cart_goods) && is_array($cart_goods)) {
			foreach ($cart_goods as $val) {
                //获取行邮税开始
                $model = Model();
                $goods_spec_date = $model->table('goods_spec')->where(array('spec_id'=>$val['spec_id']))->find();
                $goods_tax = $goods_spec_date['goods_tax'];
                //获取行邮税结束
                $val['goods_store_prices']=$this->invitation($val['goods_store_price']);
				$store_price	= (ncPriceFormat($val['goods_store_prices'] * $val['goods_num'])+ncPriceFormat($val['goods_store_price'] * $val['goods_num']*$goods_tax)) + $store_price;
			}
		}
		return $store_price;
	}	

	private function store_oneOp($model_cart,$store_id) 
	{
		$cart_goods	= $model_cart->listCart($store_id);
		$store_price	= 0;
		if(!empty($cart_goods) && is_array($cart_goods)) {
			foreach ($cart_goods as $val) {
			 $val['goods_store_price']=$this->invitation($val['goods_store_price']);
				$store_price	= ncPriceFormat($val['goods_store_price'] * $val['goods_num']) + $store_price;
			}
		}
		return $store_price;
	}	
	
	private function emsOp($model_cart,$store_id) {
		$cart_goods	= $model_cart->listCart($store_id);
        $modelGoods=Model('goods');
		$ems = 0;
		if(!empty($cart_goods) && is_array($cart_goods)) {
			foreach ($cart_goods as $val) {
                $goodsInfo=$modelGoods->getGoods([
                    'spec_id'=>$val['spec_id'],
                    'limit'=>1,
                ]);
			 //$val['goods_store_price']=$this->invitation($val['goods_store_price']);
				$ems = (ncPriceFormat($val['goods_store_price'] * $val['goods_num']))*$goodsInfo[0]['tax_money'] + $ems;
			}
		}
		return $ems;
	}
	
	public function checkOp()
	{
		if(!empty($_POST))
		{
			if(Db::update('member',$_POST,"member_id=".$_SESSION['member_id']))
			{
				echo "<script>alert('提交成功！等待管理员审核！')</script>";
			}
		}
		$param['table'] = 'member';
		$param['field'] = 'member_id';
		$param['value'] = $_SESSION['member_id'];
		$row = Db::getRow($param,"*" );
		
		if($row['pass'] == 1)
		{
			showMessage(Language::get('cart_index_exists_card'),'index.php?act=cart','','success');
		}
		
		Tpl::showpage('cart_check');
	 }
	
	public function step1Op(){

		$store_id = intval($_GET['store_id']);
        $goodsId=$_GET['goods_id'];
         Tpl::output('goodsId',$goodsId);
		if ($store_id <= 0){
			showMessage(Language::get('cart_index_not_exists_store'),'index.php?act=cart','','error');
		}
		
		
		$cart_goods_list = $this->getLegalGoods($store_id,$goodsId);
        
              
		$cart_goods_list['store_goods_price'] = 0;
		$cart = Model('cart');
        $ems1=$cart_goods_list['goods_list'][0]['goods_all_price']*$cart_goods_list['goods_list'][0]['goods_tax'];
        $ems2=$cart_goods_list['goods_list'][1]['goods_all_price']*$cart_goods_list['goods_list'][1]['goods_tax'];
		//添加行邮税begin
		foreach($cart_goods_list['goods_list'] as $key=>$val)
		{
		
			$param=array();
            $row=array();
            $param['table'] = 'cart';
			$param['field'] = array('spec_id','member_id');
			$param['value'] = array($val['spec_id'],$_SESSION['member_id']);
			$row = Db::getRow($param, "*" );
			$cart_goods_list['goods_list'][$key]['ems'] = $val['goods_all_price']*$val['goods_tax'];
			$cart_goods_list['goods_list'][0]['total_ems'] += $val['goods_all_price']*$val['goods_tax'];
			$cart_goods_list['goods_list'][$key]['goods_all_price'] = floatval($val['goods_all_price'])+floatval($val['goods_all_price']*$val['goods_tax']);
			$cart_goods_list['goods_list'][$key]['goods_normal_all_price'] = floatval($val['goods_all_price']);
		}
		foreach($cart_goods_list['goods_list'] as $key=>$val)
		{
			if($cart_goods_list['goods_list'][0]['total_ems'] >50)
			{
				$cart_goods_list['store_goods_price'] += $cart_goods_list['goods_list'][$key]['goods_all_price'];
			}else
			{
				$cart_goods_list['store_goods_price'] += $cart_goods_list['goods_list'][$key]['goods_normal_all_price'];			
			}
			
		}
		//添加行邮税end
	
                $mansong = $this->mansong($store_id,$cart_goods_list['store_goods_price']);      
                $cart_goods_list['store_goods_price'] -= $mansong['rule_discount'];
                Tpl::output('mansong_flag',$mansong['mansong_flag']);
                Tpl::output('rule_shipping_free',$mansong['rule_shipping_free']);
                Tpl::output('promotion_explain',$mansong['promotion_explain_show']);
        
                $cart_goods_list['goods_list'][0]['store_name'] =$cart_goods_list['store_info']['store_name'];
                $cart_goods_list['goods_list'][0]['store_domain'] = $cart_goods_list['store_info']['store_domain'];
		Tpl::output('cart_array',$cart_goods_list['goods_list']);
		Tpl::output('store_goods_price',$cart_goods_list['store_goods_price']);
        Tpl::output('store_info',$cart_goods_list['store_info']);

		$mode_address	= Model('address');
		$address_list	= $mode_address->getAddressList(array('member_id'=>"{$_SESSION['member_id']}"));
		$member = Model('member');
		$row = $member->where(array('member_id'=>$_SESSION['member_id']))->find();
        //默认收货地址放第一位
        if(!empty($row['member_address_id']) && !empty($address_list)) {
            foreach($address_list as $key=>$val) {
                if ($row['member_address_id'] == $val['address_id']) {
                    $tmp = $val;
                    unset($address_list[$key]);
                    array_unshift($address_list,$tmp);
                    break;
                }
            }
        }
        Tpl::output('address_list',$address_list);
		Tpl::output('row',$row);
        //输出是否需要完善个人资料标记
        $log = 1;
        if (empty($row['member_truename']) or empty($row['member_id_card']) or empty($row['member_mob_phone'])){
            $log = 2;
        }
        Tpl::output('log',$log);

                if(C('voucher_allow') == 1 && $mansong['mansong_flag'] == false){
                    $model = Model();
                    $where = array();
                    $where['voucher_owner_id'] = "{$_SESSION['member_id']}";
                   // $where['voucher_store_id'] = "{$store_id}";
                    $where['voucher_state'] = '1';
                    $where['voucher_limit'] = array('elt',$cart_goods_list['store_goods_price']);
                    $where['voucher_start_date'] = array('elt',time());
                    $where['voucher_end_date'] = array('egt',time());
                    $voucher_list = $model->table('voucher')->where($where)->order('voucher_price asc')->select();
                    Tpl::output('voucher_list',$voucher_list);
                }
		//头部我的商场和卖家中心按钮的输出
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}
		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出
		Tpl::showpage('cart_step1');
	}
	
	//上传身份证正面
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
				$update_array['idcard'] = $str;
				//if(Db::update('member',$update_array, $where))
				//{
					echo "<script>parent.stopSend('".$str."');</script>";
				//}
				
			}else
			{
				$msg = '上传出错';
				echo "<script>parent.error('".$msg."');</script>";
			}
		}
		Tpl::showpage('index_upload');
	}	
	//上传身份证反面
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
				//$update_array['idcard2'] = $str;
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
		Tpl::showpage('index_upload2');
	}
    
    private function mansong($store_id,$order_price,$gift_type='link') {

        $mansong = $this->get_mansong($store_id);
        $mansong = $mansong[$store_id];
        if($mansong['mansong_flag']) {
            $rule_discount = 0;
            $rule_shipping_free = FALSE;
            $promotion_explain = '';
            $promotion_explain_discount = '';
            $promotion_explain_shipping_free = '';
            $promotion_explain_gift = '';
            $promotion_explain_gift_show = '';
            foreach($mansong['mansong_rule'] as $rule) {
                if($order_price >= $rule['price']) {
                    $promotion_explain = Language::get('nc_mansong').Language::get('nc_colon').Language::get('nc_man').ncPriceFormat($rule['price']).Language::get('nc_yuan');
                    if(!empty($rule['discount'])) {
                        $promotion_explain_discount = Language::get('nc_comma').Language::get('nc_reduce').ncPriceFormat($rule['discount']).Language::get('nc_yuan').Language::get('nc_cash');
                        $rule_discount = $rule['discount'];
                    }
                    else {
                        $promotion_explain_discount = '';
                        $rule_discount = 0;
                    }
                    if(!empty($rule['shipping_free'])) {
                        $promotion_explain_shipping_free = Language::get('nc_comma').Language::get('nc_shipping_free');
                        $rule_shipping_free = TRUE;
                    }
                    else {
                        $promotion_explain_shipping_free = '';
                        $rule_shipping_free = FALSE;
                    }
                    if(!empty($rule['gift_name'])) {
                        if($gift_type == 'link') {
                            $promotion_explain_gift = Language::get('nc_comma').Language::get('nc_gift')."<a href=\'".$rule['gift_link']."\' target=\'blank\'>".$rule['gift_name']."</a>";
                            $promotion_explain_gift_show = Language::get('nc_comma').Language::get('nc_gift')."<a href='".$rule['gift_link']."' target='blank'>".$rule['gift_name']."</a>";
                        }
                        else {
                            $promotion_explain_gift = Language::get('nc_comma').Language::get('nc_gift').$rule['gift_name'];
                            $promotion_explain_gift_show = Language::get('nc_comma').Language::get('nc_gift').$rule['gift_name'];
                        }
                    }
                }
            }
            $promotion_explain_show = $promotion_explain;
            $promotion_explain_show .= $promotion_explain_discount;
            $promotion_explain_show .= $promotion_explain_shipping_free;
            $promotion_explain_show .= $promotion_explain_gift_show;
            
            $promotion_explain .= $promotion_explain_discount;
            $promotion_explain .= $promotion_explain_shipping_free;
            $promotion_explain .= $promotion_explain_gift;
        }
        $mansong['rule_shipping_free'] = $rule_shipping_free;
        $mansong['promotion_explain'] = $promotion_explain;
        $mansong['promotion_explain_show'] = $promotion_explain_show;
        $mansong['rule_discount'] = $rule_discount;
        return $mansong;
    }

    //step1取购物车商品
	private function getLegalGoods($store_id,$goodsId){

		$model_store = Model('store');
        $store_info = $model_store->shopStore(array('store_id'=>$store_id),'store_id,store_name,member_id,member_name');
        
        if(empty($store_info)) showMessage(Language::get('cart_index_not_exists_store'),'index.php?act=cart','','error');

		$model_cart	= Model('cart');
		$goods_list	= $model_cart->getCartGoods(array('spec_store_id'=>"$store_id",'cart_member_id'=>"{$_SESSION['member_id']}","goods_id"=>$goodsId));

		if(empty($goods_list)) {
			showMessage(Language::get('cart_record_error'),'index.php?act=cart','','error');
		}

		$cart_goods_list_new = array();
		if (!empty($goods_list)){
			foreach ($goods_list as $k=>$v){
				$cart_goods_list_new[$v['spec_id']] = $v;
			}
		}
		$cart_goodsid_arr = array_keys($cart_goods_list_new);
		if(empty($cart_goodsid_arr)) {

			showMessage(Language::get('cart_record_error'),'index.php?act=cart','','error');
		}
		$cart_goodsid_str = "'".implode("','",$cart_goodsid_arr)."'";
		unset($cart_goodsid_arr);
		unset($cart_goods_list);
		
		$model_goods = Model('goods');
		$goods_list = $model_goods->getGoods(array('goods_state'=>'0','goods_show'=>'1','spec_storage_enough'=>'yes','spec_id_in'=>$cart_goodsid_str),'',"goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.product_num,goods.goods_transfee_charge,goods.spec_open,goods.py_price,goods.kd_price,es_price,goods.transport_id,goods.country,goods.provider,goods.goods_serial,goods.foreign_language,goods_spec.*","groupbuy_goods_info");
		if (empty($goods_list)){
			showMessage(Language::get('cart_record_error'),'index.php?act=cart','','error');
		}
		
		$store_goods_price	= 0;
		foreach ($goods_list as $k => $v) {
			$goods_list[$k] = $v;
			$goods_list[$k]['cart_spec_info'] = '';
			if ($v['spec_open'] == 1 && !empty($v['spec_goods_spec']) && !empty($v['spec_name'])){
				$spec_name = unserialize($v['spec_name']);
				if (!empty($spec_name)){
					$spec_name = array_values($spec_name);
					$spec_goods_spec = unserialize($v['spec_goods_spec']);
					$i = 0;
					foreach ($spec_goods_spec as $speck=>$specv){
						$goods_list[$k]['cart_spec_info'] .= $spec_name[$i].":".$specv."&nbsp;";
						$i++;
					}
				}
			}
			$quantity = intval($cart_goods_list_new[$v['spec_id']]['goods_num']);
			if (intval($v['spec_goods_storage']) < $quantity){
				showMessage(Language::get('cart_index_store_goods').Language::get('nc_colon').$v['goods_name']
				.Language::get('nc_comma').Language::get('cart_index_freight_not_enough'),'index.php?act=cart','','error');
			}
			$goods_list[$k]['goods_num'] = $quantity;
			$goods_list[$k]['goods_all_price']	= ncPriceFormat(floatval($v['spec_goods_price']) * $quantity);
			$cart = Model('cart');
			$store_goods_price	= ncPriceFormat(floatval($goods_list[$k]['goods_all_price']) + $store_goods_price);
            
            $store_goods_price=$this->invitation($store_goods_price);
            $goods_list[$k]['goods_all_price']=$this->invitation($goods_list[$k]['goods_all_price']);
		}
		return array('goods_list'=>$goods_list,'store_goods_price'=>$store_goods_price,'store_info'=>$store_info);
	}
	
	public function step2Op() 
	{
           
               
		$store_id	= intval($_POST['store_id']);
              
		if ($store_id <= 0){
			showMessage(Language::get('cart_index_not_exists_store'),'index.php?act=cart','','error');
		}
        if($store_id != 20){        
    		$address_options = intval($_POST['address_options']);
    		if ($address_options <= 0){
    			showMessage(Language::get('cart_step1_chooseaddress_error'),'index.php?act=cart&op=step1&store_id='.$store_id,'','error');
    		}
    		$mode_address	= Model('address');
    		$address_info	= $mode_address->getOneAddress($address_options);
    
    		if (empty($address_info)){
    			showMessage(Language::get('cart_step1_chooseaddress_error'),'index.php?act=cart&op=step1&store_id='.$store_id,'','error');
    		}
        }            
		
                //判断是否汽车馆
                if($store_id != 20){
		//检测个人资料是否万完整
                
                    $member = Model('member');
                    if(!empty($_SESSION['member_id']))
                    {
                            $member_info = $member->where('member_id = '.$_SESSION['member_id'])->find();
                            if(empty($member_info['member_mob_phone']) || empty($member_info['member_id_card']) || empty($member_info['member_truename']) || $member_info['examine'] == 2 ||$member_info['examine'] == 0)
                            {
                                    showMessage('因海关要求，购物前请先完善个人资料','index.php?act=home&op=member');
                            }
                    }
                }
                
		//更新身份证信息
//		if(!empty($_POST['last_idcard']))@unlink($_POST['last_idcard']);
//		if(!empty($_POST['last_idcard2']))@unlink($_POST['last_idcard2']);
//		$param['id'] = $address_options;
//		$param['true_name'] = $_POST['true_name'];
//		$param['card'] = $_POST['card'];
//		$param['idcard'] = $_POST['idcard'];
//		$param['idcard2'] = $_POST['idcard2'];
//		$mode_address->upAddress($param);
		
		
		$legalGoods_list = $this->getLegalGoods($store_id,$_POST['goodsId']);



		$legalGoods_list['store_goods_price'] = 0;
		$cart = Model('cart');
		//计算行邮税begin
        $goodsTotal=0;
        $goodsNum=0;
        $goodsTotalPrice=0;
		foreach($legalGoods_list['goods_list'] as $key=>$val)
		{
			/*
			$param['table'] = 'cart';
			$param['field'] = 'spec_id';
			$param['value'] = $val['spec_id'];
			$row = Db::getRow($param, "*" );
			*/
            $goodsNum=$val['goods_num'];
            $goodsTotal++;
			$row = $cart->fetchBySpecId($val['spec_id']);
            $goodsTotalPrice+=$val['goods_all_price'];
			$legalGoods_list['goods_list'][$key]['ems'] = $val['goods_all_price']*$val['goods_tax'];
			$legalGoods_list['goods_list'][0]['total_ems'] += $val['goods_all_price']*$val['goods_tax'];
			$legalGoods_list['goods_list'][$key]['goods_all_price'] = floatval($val['goods_all_price'])+floatval($val['goods_all_price']*$val['goods_tax']);
			$legalGoods_list['goods_list'][$key]['goods_normal_all_price'] = floatval($val['goods_all_price']);
		}
		foreach($legalGoods_list['goods_list'] as $key=>$val)
		{
			if($legalGoods_list['goods_list'][0]['total_ems'] >50)
			{
				$legalGoods_list['store_goods_price'] += $legalGoods_list['goods_list'][$key]['goods_all_price'];				
			}else
			{
				$legalGoods_list['store_goods_price'] += $legalGoods_list['goods_list'][$key]['goods_normal_all_price'];			
			}
		}
        //商品大于一件进行订单金额计算，不能超过1000元
        if($store_id!=20&&$goodsNum>1&&$goodsTotalPrice>1000){
            showMessage('抱歉，您已超过海关限额￥1000，请分次购买',C('site_url').'/index.php?act=cart');
        }
        if($store_id!=20&&$goodsTotal>1&&$goodsTotalPrice>1000){
            showMessage('抱歉，您已超过海关限额￥1000，请分次购买',C('site_url').'/index.php?act=cart');
        }
		$goods = Model('goods');
		//添加毛重、净重、ieflag、商品海关备案号
		foreach($legalGoods_list['goods_list'] as $k=>$v)
		{
			/*
			$param['table']='goods';;
			$param['field']='goods_id';
			$param['value'] = $v['goods_id'];
			$row = Db::getRow($param, $fields = "*" );
			*/
			$param['goods_id'] = $v['goods_id'];
			$row = $goods->getGoods($param);
			$legalGoods_list['goods_list'][$k]['ieflag'] = $row['ieflag'];
			$legalGoods_list['goods_list'][$k]['product_num'] = $row['product_num'];
			$legalGoods_list['goods_list'][$k]['gross_weight'] = $row['gross_weight'];
			$legalGoods_list['goods_list'][$k]['net_weight'] = $row['net_weight'];
			$legalGoods_list['goods_list'][$k]['declaration_unit'] = $row['declaration_unit'];
			$legalGoods_list['goods_list'][$k]['goods_custom_num'] = $row['goods_custom_num'];
		}
		//计算行邮税end
		
		$shipping_fee_total = 0;
		
		$order_class= Model('order');
                
		$order_array		= array();
		$order_array['order_sn']		= $order_class->snOrder();
        
        //插入车牌
        if($legalGoods_list['store_info']['store_id']=='20'){
            $updatearr['order_sn']=$order_array['order_sn'];
            $pass= Db::update('car_pnum',$updatearr,"memberid='".$_SESSION['member_id']."' ORDER BY addtime DESC LIMIT 1");
        }
        $model = Model();
         $daddress = $model->table('daddress')->where(array('store_id'=>$legalGoods_list['store_info']['store_id'],'is_default'=>1))->find();     
        
		$order_array['seller_id']		= $legalGoods_list['store_info']['member_id'];
		$order_array['store_id']		= $legalGoods_list['store_info']['store_id'];
		$order_array['store_name']		= $legalGoods_list['store_info']['store_name'];
		$order_array['ems']		= $legalGoods_list['goods_list'][0]['total_ems'];
		$order_array['buyer_id']		= $_SESSION['member_id'];
		$order_array['buyer_name']		= $_SESSION['member_name'];
		$order_array['buyer_email']		= $_SESSION['member_email'];
		$order_array['add_time']		= TIMESTAMP;
		$order_array['out_sn']			= $order_class->outSnOrder();
		$order_array['invoice']			= '';	
		$order_array['evaluation_status'] = 0;
		$order_array['order_type'] 		= 0;
        $order_array['daddress_id'] = $daddress['address_id'];
		$order_array['order_message']	= trim($_POST['order_message']);
                
                //判断是否汽车馆
                if($legalGoods_list['store_info']['store_id'] == 20){
                    $order_array['examine'] =  2;
                }
                
                
		if (!empty($_POST['transport_type'])){
			$tmp = @explode('|',$_POST['transport_type']);
			if (is_array($tmp) && is_numeric($tmp[1])){
				$order_array['shipping_name'] = str_replace(array('py','kd','es'),array(Language::get('transport_type_py'),Language::get('transport_type_kd'),'EMS'),$tmp[0]);
				$shipping_fee_total =  $tmp[1];
			}
		}else{
			$order_array['shipping_name']	= '';					
		}
		$output_order = $order_array;
		//添加订单
		$order_id	= $order_class->addOrder($order_array);

		//$model_store_goods	= Model('goods');    
		$date = date('Ymd',time());  
		$model = Model();  
		$stat_model = Model('statistics');
		if(!empty($legalGoods_list['goods_list'])) {
			$output_goods_name = array();
				 
			foreach ($legalGoods_list['goods_list'] as $val) 
			{
				$order_goods_array	= array();
				$order_goods_array['order_id']		= $order_id;
				$order_goods_array['goods_id']		= $val['goods_id'];
				$order_goods_array['goods_name']	= $val['goods_name'];
				$order_goods_array['stores_id']		= $val['store_id'];
				$order_goods_array['spec_id']		= $val['spec_id'];
				$order_goods_array['spec_info']		= $val['cart_spec_info'];
				$order_goods_array['goods_price']	= $val['spec_goods_price'];
				$order_goods_array['goods_num']		= $val['goods_num'];
				$order_goods_array['goods_image']	= $val['goods_image'];
				$order_goods_array['ieflag']	= $val['ieflag'];
				$order_goods_array['product_num']	= $val['product_num'];
				$order_goods_array['gross_weight']	= $val['gross_weight'];
				$order_goods_array['net_weight']	= $val['net_weight'];
				$order_goods_array['declaration_unit']	= $val['declaration_unit'];
				$order_goods_array['goods_custom_num']	= $val['goods_custom_num'];
				$order_goods_array['ems']	= $val['ems'];
				//商品库存基数
				$order_goods_array['store_base']	= $val['store_base'];
                                //商品国家
                                $order_goods_array['order_country']	= $val['country'];
                                //商品供应商
                                $order_goods_array['order_provider']	= $val['provider'];
                                 //商品货号
                                $order_goods_array['goods_item_no']	= $val['goods_serial'];
                                //商品外文
                                 $order_goods_array['foreign_language']	= $val['foreign_language'];
                                 
                                  //商品明细
                                 $order_goods_array['d_specvalue']	= $val['spec_d_specvalue'];
                                
				if (count($output_goods_name)<3) $output_goods_name[] = $val['goods_name']; 
                                
                                //添加到order_goods 表
				$order_class->addGoodsOrder($order_goods_array);
                                
				$model_cart = Model('cart');
				$model_cart->dropCartByCondition(array('cart_spec_id'=>"{$val['spec_id']}",'cart_member_id'=>"{$_SESSION['member_id']}"));
				//$model_store_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$val['goods_num'],'sign'=>'decrease'),'spec_salenum'=>array('value'=>$val['goods_num'],'sign'=>'increase')),$val['spec_id']);
				//$model_store_goods->updateGoods(array('salenum'=>array('value'=>$val['goods_num'],'sign'=>'increase')),$val['goods_id']);
				$sale_date_array = $model->table('salenum')->where(array('date'=>$date,'goods_id'=>$val['goods_id']))->find();
				if(is_array($sale_date_array) && !empty($sale_date_array)){
					$update_param = array();
					$update_param['table'] = 'salenum';
					$update_param['field'] = 'salenum';
					$update_param['value'] = $val['goods_num'];
					$update_param['where'] = "WHERE date = '".$date."' AND goods_id = '".$val['goods_id']."'";
					$stat_model->updatestat($update_param);
				}else{
					$model->table('salenum')->insert(array('date'=>$date,'salenum'=>$val['goods_num'],'store_id'=>$store_id,'goods_id'=>$val['goods_id']));
				}
			}
		}

        $mansong = $this->mansong($store_id,$legalGoods_list['store_goods_price']);
        if($mansong['rule_shipping_free']) {
            $shipping_fee_total = 0;echo 'b'.$shipping_fee_total;
        }

		$address_array		= array();
		$address_array['order_id']		= $order_id;
		$address_array['true_name']		= $address_info['true_name'];
		$address_array['area_id']		= $address_info['area_id'];
		$address_array['area_info']		= $address_info['area_info'];
		$address_array['address']		= $address_info['address'];
		$address_array['zip_code']		= $address_info['zip_code'];
		$address_array['tel_phone']		= $address_info['tel_phone'];
		$address_array['mob_phone']		= $address_info['mob_phone'];
        $address_array['consignee_id_num']      = $address_info['card'];  //添加身份证号码
		$order_amount	= ncPriceFormat($legalGoods_list['store_goods_price']+$shipping_fee_total-$mansong['rule_discount']);
		$order_class->addAddressOrder($address_array);

		$order_sn		= $order_array['order_sn'];
		$order_array	= array();
		$order_array['goods_amount']	= $legalGoods_list['store_goods_price'];
		$order_array['discount']		= 0;
        $order_array['mansong_id']      = $mansong['mansong_info']['mansong_id'];
        $order_array['mansong_explain'] = $mansong['promotion_explain'];
        $voucher_id = intval($_POST['voucher_id']);
        $voucher_price = 0;
        if($voucher_id > 0 && C('voucher_allow') && $mansong['mansong_flag']==false ) {
            $model = Model();
            $where = array();
            $where['voucher_id'] = $voucher_id;
            $where['voucher_owner_id'] = $_SESSION['member_id'];
         //   $where['voucher_store_id'] = $store_id;
            $where['voucher_state'] = '1';
            $where['voucher_limit'] = array('elt',$order_amount);
            $where['voucher_start_date'] = array('elt',time());
            $where['voucher_end_date'] = array('egt',time());
            $voucherinfo = $model->table('voucher')->where($where)->find();
            if(!empty($voucherinfo)) {
                $voucher_price = $voucherinfo['voucher_price']; 
                $voucher_code = $voucherinfo['voucher_code'];
                $model->table('voucher')->where(array('voucher_id'=>$voucherinfo['voucher_id']))->update(array('voucher_state'=>'2','voucher_order_id'=>$order_id));
                $model->table('voucher_template')->where(array('voucher_t_id'=>$voucherinfo['voucher_t_id']))->update(array('voucher_t_used'=>array('exp','voucher_t_used+1')));
            }
        }
        if(empty($voucher_price)) {
            $order_array['voucher_id'] = 0;
            $order_array['voucher_price'] = 0;     
            $order_array['voucher_code'] ='';
        }else {
            $order_array['voucher_id'] = $voucher_id;
            $order_array['voucher_price'] = $voucher_price;     
            $order_array['voucher_code'] = $voucher_code;
            $order_amount -= $voucher_price; 
        }
	    $order_array['order_amount'] 	= $order_amount;
	    $order_array['shipping_fee']	= $shipping_fee_total;
		$order_class->updateOrder($order_array,$order_id);
		$order_class->addLogOrder(10,$order_id);

		$output_order = array_merge($output_order,$order_array,array('order_id'=>$order_id,'seller_name'=>$legalGoods_list['store_info']['member_name']));
		$param	= array(
			'site_url'		=> SiteUrl,
			'site_name'		=> $GLOBALS['setting_config']['site_name'],
			'buyer_name'	=> $_SESSION['member_name'],
			'seller_name'	=> $legalGoods_list['store_info']['member_name'],
			'order_sn'		=> $order_sn,
			'order_id'		=> $order_id
		);
		$this->send_notice($_SESSION['member_id'],'email_tobuyer_new_order_notify',$param);
		$this->send_notice($legalGoods_list['store_info']['member_id'],'email_toseller_new_order_notify',$param);
		@header("Location: index.php?act=cart&op=order_pay&order_id=".$order_id);
		exit;
	}
   
	public function order_payOp() {
		$order_id	= intval($_GET['order_id']);
		if ($order_id <= 0){
			showMessage(Language::get('cart_order_pay_not_exists'),'','html','error');
		}
		$model_order= Model('order');
		$order_info	= $model_order->getOrderById($order_id);
		
		if(empty($order_info) || $order_info['buyer_id'] != $_SESSION['member_id']){
			showMessage(Language::get('cart_order_pay_not_exists'),'','html','error');
		}
		
		$goods_list = Model()->table('order_goods')->field('goods_name')->where(array('order_id'=>$order_id))->limit(3)->select();
		$output_goods_name = array();
		foreach ((array)$goods_list as $v) {
			if (count($output_goods_name)<3) $output_goods_name[] = $v['goods_name'];
		}
		
   		if (!C('payment')){
			$model_payment = Model('gold_payment');
			$condition = array();
			$condition['payment_state'] = '1';
			
			$payment_list = $model_payment->getList($condition);
			foreach ((array)$payment_list as $k=>$v) {
				if ($v['payment_online'] == 1){
					$online_array[$k] = $v;
				}else{
					$offline_array[] = $v;
				}
			}
   		}else{
			$model_payment	= Model('payment');
			$online_array	= $model_payment->listStorePayment(1,$order_info['store_id']);
			$offline_array = $model_payment->listStorePayment(0,$order_info['store_id']);
   		}
        $output_order = $order_info;
        $output_order['seller_name'] = Model()->table('store')->getfby_store_id($order_info['store_id'],'member_name');
        //脚部文章输出
		$list=$this->_article();
		//头部我的商场和卖家中心按钮的输出
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}
		Tpl::output('online_array',$online_array);
		Tpl::output('offine_array',$offline_array);
		Tpl::output('goods_name',$output_goods_name);
		Tpl::output('order',$output_order);
		Tpl::showpage('cart_step2');
	} 
	
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
		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article.article_content,article_class.ac_name,article_class.ac_parent_id';
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
    
    public function tipsOp(){
        	Tpl::showpage('cart_tips','null_layout');
    }
    
     public function newaddressOp(){
     	if ($_GET['form_submit'] == 'ok')
		{
     		$phone_tel = trim($_GET['phone_tel']);
     		$phone_mob = trim($_GET['phone_mob']);
     		$obj_validate = new Validate();
     		$obj_validate->validateparam = array(
     			array("input"=>$_GET["consignee"],"require"=>"true","message"=>Language::get('cart_step1_input_receiver')),
     			array("input"=>$_GET["areaid"],"require"=>"true","validator"=>"Number","message"=>Language::get('cart_step1_choose_area')),
     			//array("input"=>$_GET["card"],"require"=>"true","message"=>Language::get('cart_step1_input_card1')),//验证是否有输入身份证号码
                array("input"=>$_GET['phone_mob'],"require"=>"true","message"=>"手机号码不能为空")
     		);

     		$error = $obj_validate->validate();
               
     		if ($phone_tel == '' && $phone_mob == ''){
     			$error .= Language::get('cart_step1_telphoneormobile').'<br/>';
     		}
			if ($error != ''){
				if (strtoupper(CHARSET) == 'GBK'){
					$error = Language::getUTF8($error);
				}
				echo json_encode(array('done'=>false,'msg'=>$error));
				die;
			}
			$address_model = Model('address');
			$insert_arr = array();
			$insert_arr['true_name'] = $_GET['consignee'];
			$insert_arr['area_id'] = intval($_GET['areaid']);
			$insert_arr['city_id'] = intval($_GET['city_id']);
			$insert_arr['area_info'] = $_GET['area_info'];
			$insert_arr['address'] = $_GET['address'];
			$insert_arr['zip_code'] = $_GET['zipcode'];
			$insert_arr['tel_phone'] = $phone_tel;
			$insert_arr['mob_phone'] = $phone_mob;
			//依照海关要求身份证号码最后一位是x时要转换成大写
            if (!empty($_GET['card'])) {
                $last = substr($_GET['card'],-1);
                if($last == 'x' || $last == 'X')
                {
                    $_GET['card'] = strtoupper($_GET['card']);
                }
            }
			$insert_arr['card'] = $_GET['card'];
			//$insert_arr['idcard'] = $_GET['idcard'];
			//$insert_arr['idcard2'] = $_GET['idcard2'];

            //完善个人资料开始
            $member_model = Model('member');
            $member_info  = $member_model->infoMember(array('member_id'=>$_SESSION['member_id']));
            $member_array = array();
            if (empty($member_info['member_truename'])) {
                $member_array['member_truename'] = trim($_GET['consignee']);
            }
            if (empty($member_info['member_id_card'])) {
                $member_array['member_id_card'] = $_GET['card'];
            }
            if (empty($member_info['member_mob_phone'])) {
                $member_array['member_mob_phone'] = intval($_GET['phone_mob']);
            }
            if (!empty($member_array)) {
                $res = $member_model->updateMember($member_array,$_SESSION['member_id']);
                if (!$res) {
                    echo json_encode(array('done'=>false,'msg'=>'完善个人资料失败'));
                    die;
                }
            }
            //完善个人资料结束

			if(strtoupper(CHARSET) == 'GBK'){
				$insert_arr = Language::getGBK($insert_arr);
			}
                 
			$rs = $address_model->addAddress($insert_arr);
			if ($rs){
				echo json_encode(array('done'=>true,'id'=>$rs));
				die; 
			}else {
				echo json_encode(array('done'=>false,'msg'=>Language::get('cart_step1_addaddress_fail','UTF-8')));
				die;
			}
			
     	}else{
     		$address_model = Model('address');
     		$choose_addressid = intval($_GET['addr_id']);
     		$address_info = array();
     		if ($choose_addressid > 0){
     			$address_info = $address_model->getOneAddress($choose_addressid);
     			if (!empty($address_info)){
     				$address_info['area_info_arr'] = explode("\t",$address_info['area_info']);
     			}
     		}
     		Tpl::output('areainfo_defaultid',$areainfo_defaultid);
     		Tpl::output('address_info',$address_info);
     		Tpl::showpage('cart_newaddress','null_layout');
            }
     }
     
     public function newpnumOp(){
     	if ($_GET['form_submit'] == 'ok')
		{
     		$pnum = trim($_GET['pnum']);
            $model = Model();
            $ispass = $model->table('car_pnum')->where(array('memberid'=>$_SESSION['member_id'],'punm'=>$pnum))->find();
            if($ispass){
                    $updatearr['addtime']=time();
                	$pass= Db::update('car_pnum',$updatearr,"pid='".$ispass['pid']."'");
                    echo json_encode(array('done'=>true,'id'=>1));
    				die; 
            }else{
            
         		
    			$insert_arr = array();
    			$insert_arr['memberid'] = $_SESSION['member_id'];
    			$insert_arr['punm'] = $pnum;
    			$insert_arr['addtime'] = time();
    		    $insert_id = Db::insert('car_pnum',$insert_arr);
    			if ($insert_id){
    			    
    				echo json_encode(array('done'=>true,'id'=>$insert_id));
    				die; 
    			}else {
    				echo json_encode(array('done'=>false,'msg'=>Language::get('cart_step1_new_pnum_wrong','UTF-8')));
    				die;
    			}
            }
			
     	}else{
     		 Tpl::showpage('cart_newpnum','null_layout');
        }
     }

	
	function calc_buyOp(){
		if (empty($_GET['hash'])) return false;
		$hash = decrypt($_GET['hash'],MD5_KEY.'CART');
		if (strpos($hash,'-') === false) return false;
		$hash = explode('-',$hash);	
		
		$tid = explode(',',$hash[0]);
		$tnum = explode(',',$hash[1]);
		if (count($tid) != count($tnum)) return false;
		$tmp = array();
		foreach($tid as $k=>$v){
			$tmp[$k]['num'] = $tnum[$k];
			$tmp[$k]['transport_id'] = $tid[$k];
		}
		
		$result = array();
		$result['py'] = 0;
		$result['kd'] = 0;
		$result['es'] = 0;
				
		if ((empty($hash[0]) || empty($hash[0]) && strpos($hash[2],'_'))){
				$_price = explode(',',$hash[2]);
				if ($_price[0] != ''){
					foreach ($_price as $value) {
						$_tprice = explode('_',$value);
						if (is_numeric($_tprice[0]) && $_tprice[0] > 0) $result['py'] += $_tprice[0];
						if (is_numeric($_tprice[1]) && $_tprice[1] > 0) $result['kd'] += $_tprice[1];
						if (is_numeric($_tprice[2]) && $_tprice[2] > 0) $result['es'] += $_tprice[2];
						 if($_tprice[2] == '0.00') $unset_es = true;
					}
				}
		}else{
				$model_transport = Model('transport');
				$extend = $model_transport->getExtendList(array('transport_id'=>array('in',$hash[0])));
				$new_extend = array();
				$unset_py = true;
				$unset_kd = true;
				$unset_es = true;
				if (!empty($extend) && is_array($extend)){
					foreach ($extend as $k => $v) {
						$new_extend[$v['transport_id']][] = $v;
						if ($v['type'] == 'py') $unset_py = false;
						if ($v['type'] == 'kd') $unset_kd = false;
						if ($v['type'] == 'es') $unset_es = false;						
					}
				}
				$calc = array();
				foreach ($tmp as $k => $v) {
					$calc[$k] = $this->calc_unit($_GET['area_id'],$v['num'],$new_extend[$v['transport_id']]);
				}
				foreach ($calc as $v) {
					$result['py'] += $v['py'];
					$result['kd'] += $v['kd'];
					$result['es'] += $v['es'];
				}
				if (strpos($hash[2],'_')){
					$_price = explode(',',$hash[2]);
					if ($_price[0] != ''){
						foreach ($_price as $value) {
							$_tprice = explode('_',$value);
							if (is_numeric($_tprice[0]) && $_tprice[0] > 0) $result['py'] += $_tprice[0];
							if (is_numeric($_tprice[1]) && $_tprice[1] > 0) $result['kd'] += $_tprice[1];
							if (is_numeric($_tprice[2]) && $_tprice[2] > 0) $result['es'] += $_tprice[2];
							 if($_tprice[2] == '0.00') $unset_es = true;
						}
					}
				}			
		}
		$result['py'] = sprintf('%.2f',$result['py']);
		$result['kd'] = sprintf('%.2f',$result['kd']);
		$result['es'] = sprintf('%.2f',$result['es']);
		if ($unset_es == true){
			unset($result['es']);
		}
		if ($unset_py == true){
			unset($result['py']);
		}
		if ($unset_kd == true){
			unset($result['kd']);
		}
		echo json_encode($result);
	}
	
	private function calc_unit($area_id,$num,$extend){
		if (!empty($extend) && is_array($extend)){
			$calc = array();
			$calc_default = array();
			foreach ($extend as $v) {
				if (strpos($v['area_id'],",".intval($_GET['area_id']).",") !== false){
					if ($num <= $v['snum']){
						$calc[$v['type']] = $v['sprice'];
					}else{
						$calc[$v['type']] = sprintf('%.2f',($v['sprice'] + ceil(($num-$v['snum'])/$v['xnum'])*$v['xprice']));
					}
				}
				if ($v['is_default']==1){
					if ($num <= $v['snum']){
						$calc_default[$v['type']] = $v['sprice'];
					}else{
						$calc_default[$v['type']] = sprintf('%.2f',($v['sprice'] + ceil(($num-$v['snum'])/$v['xnum'])*$v['xprice']));
					}
				}
			}
			foreach (array('py','kd','es') as $v){
				if (!isset($calc[$v]) && isset($calc_default[$v])){
					$calc[$v] = $calc_default[$v];
				}
			}
		}
		return $calc;
	}

	
	function order_amoutOp(){
		
		$order = model()->table('order')->where(array('order_id'=>intval($_GET['order_id']),'buyer_id'=>$_SESSION['member_id']))->field('order_amount')->find();
		echo ncPriceFormat($order['order_amount']);
	}
}
