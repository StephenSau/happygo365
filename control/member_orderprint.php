<?php



defined('haipinlegou') or exit('Access Invalid!');



class member_orderprintControl extends BaseMemberControl {

	public function __construct() {

		parent::__construct();

		Language::read('member_printorder');

	}

	

	public function indexOp() {

		

		$order_id	= intval($_GET['order_id']);

		if ($order_id <= 0){

			showMessage(Language::get('wrong_argument'),'','html','error');

		}

		$order_model = Model('order');

		$condition['order_id'] = $order_id;

		$order_info = $order_model->getOrderById($order_id,'all',$condition);

		if (empty($order_info)){

			showMessage(Language::get('member_printorder_ordererror'),'','html','error');

		}

		if ($order_info['seller_id'] != $_SESSION['member_id']){

			showMessage(Language::get('member_printorder_ordererror'),'','html','error');

		}

		Tpl::output('order_info',$order_info);

		$model_store	= Model('store');

		$store_info		= $model_store->shopStore(array('store_id'=>$order_info['store_id']));
        
        
        

		if (!empty($store_info['store_label'])){

			if (file_exists(BasePath.DS.ATTACH_STORE.DS.$store_info['store_label'])){

				$store_info['store_label'] = SiteUrl.DS.ATTACH_STORE.DS.$store_info['store_label'];

			}else {

				$store_info['store_label'] = '';

			}

		}

		if (!empty($store_info['store_stamp'])){

			if (file_exists(BasePath.DS.ATTACH_STORE.DS.$store_info['store_stamp'])){

				$store_info['store_stamp'] = SiteUrl.DS.ATTACH_STORE.DS.$store_info['store_stamp'];

			}else {

				$store_info['store_stamp'] = '';

			}

		}

		Tpl::output('store_info',$store_info);

		$ordergoods_model = Model('order_goods');

		$ordergoods_list= $ordergoods_model->getOrderGoodsList(array('order_id'=>$order_id));
        
        //»ñÈ¡ÆûÅä³µÅÆ
      


        $pnumlist=Db::getAll("SELECT * FROM haipinlegou_car_pnum WHERE memberid='".$order_info['buyer_id']."' ORDER BY addtime DESC LIMIT 1");

        Tpl::output('pnumlist',$pnumlist);
        
        
        $detaillist=Db::getAll("SELECT * FROM haipinlegou_car_detail WHERE order_sn='".$order_info['order_sn']."' ORDER BY d_addtime DESC");

        Tpl::output('detaillist',$detaillist);
        

		$ordergoods_listnew = array();

		$goods_allnum = 0;

		$goods_totleprice = 0;

		if (!empty($ordergoods_list)){

			$goods_count = count($ordergoods_list);

			$i = 1;

			foreach ($ordergoods_list as $k=>$v){

				$v['goods_name'] = str_cut($v['goods_name'],100);

				$v['spec_info'] = str_cut($v['spec_info'],40);

				$goods_allnum += $v['goods_num'];				

				$v['goods_allprice'] = ncPriceFormat($v['goods_num']*$v['goods_price']);

				$goods_totleprice += $v['goods_allprice'];

				$ordergoods_listnew[ceil($i/4)][$i] = $v;

				$i++;

			}

		}

		$order_info['discount'] = ncPriceFormat($order_info['voucher_price']);

		$order_info['shipping_fee'] = $order_info['shipping_fee'];

		Tpl::output('discount',$discount);

		Tpl::output('goods_allnum',$goods_allnum);

		Tpl::output('goods_totleprice',ncPriceFormat($goods_totleprice));

		Tpl::output('ordergoods_list',$ordergoods_listnew);

		Tpl::showpage('member_orderprint',"null_layout");

	}

	

}

