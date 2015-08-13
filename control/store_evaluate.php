<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_evaluateControl extends BaseMemberStoreControl{
	public function __construct(){
        parent::__construct() ;
        Language::read('member_layout,member_evaluate');
        Tpl::output('pj_act','store_evaluate');
    }
 
    public function addOp(){
		$order_id = intval($_GET['order_id']);
		if (!$order_id){
			showMessage(Language::get('wrong_argument'),'index.php?act=store&op=store_order','html','error');
		}
	
		$order_model = Model('order');
	
		$order_info = $order_model->getOrderById($order_id,'simple',array('seller_id'=>"{$_SESSION['member_id']}",'evalseller_status'=>'0','order_state'=>'order_finish','refund_state'=>'able_evaluate'));
    	if (empty($order_info)){
			showMessage(Language::get('member_evaluation_order_notexists'),'index.php?act=store&op=store_order','html','error');
		}
   
		$order_info['able_evaluate'] = true;
		if ($order_info['evaluation_status'] == 0 && (intval($order_info['finnshed_time'])+60*60*24*15)<time()){
			$order_info['able_evaluate'] = false;
		}elseif ($order_info['evaluation_status'] == 1 && (intval($order_info['evaluation_time'])+60*60*24*15)<time()) {
			$order_info['able_evaluate'] = false;
		}
		if (!$order_info['able_evaluate']){
			showMessage(Language::get('member_evaluation_order_notexists'),'index.php?act=store&op=store_order','html','error');
		}
		
		$member_model = Model('member');
		$member_info = $member_model->infoMember(array('member_id'=>"{$order_info['buyer_id']}"));
        if(empty($member_info)){
        	showMessage(Language::get('member_evaluation_member_notexists'),'index.php?act=store&op=store_order','html','error');
        }
  
        $ordergoods_model = Model('order_goods');
		$order_goodstmp = $ordergoods_model->getOrderGoodsList(array('order_id'=>"$order_id"));
		if(empty($order_goodstmp)){
			showMessage(Language::get('member_evaluation_order_notexists'),'index.php?act=store&op=store_order','html','error');
		}
		foreach ($order_goodstmp as $key=>$goods){
			$goods['goods_image'] = cthumb($goods['goods_image'],'tiny',$_SESSION['store_id']);
			$order_goods[$goods['rec_id']] = $goods;
		}
		unset($ordergoods_model);
		unset($order_goodstmp);

		if (!$_POST){
			$member_info['credit_arr'] = getCreditArr(intval($member_info['member_credit']));
	
			Tpl::output('left_show','order_view');
			Tpl::output('member_info',$member_info);
			Tpl::output('order_info',$order_info);
			Tpl::output('order_goods',$order_goods);
			Tpl::output('menu_sign','evaluateadd');
			Tpl::showpage('store_order.evaluation');
		}else {
	
			$is_goodslegal = false;
			if(!empty($_POST['goods'])){
				foreach ($_POST['goods'] as $k=>$v){
					if (intval($v['points'])>0){
						$is_goodslegal = true;
					}
				}
			}
			if ($is_goodslegal == false){
				showMessage(Language::get('member_evaluation_evaluation_not_null'),"index.php?act=store_evaluate&op=add&order_id=$order_id",'html','error');
			}
			$evaluate_model = Model('evaluate');
		
			if ($order_info['evaluation_status'] == 1){
				$bothstate = 2;
			
				$goodsevallist = $evaluate_model->getGoodsEvalList(array('geval_orderid'=>"$order_id",'geval_tomemberid'=>"{$_SESSION['member_id']}"));
				if (!empty($goodsevallist)){
					foreach ($goodsevallist as $k=>$v){
						$goodsevallist_new[$v['geval_ordergoodsid']] = $v;
					}
				}
			}else {
				$bothstate = 1;
			}
			foreach ($_POST['goods'] as $k=>$v){
				if (intval($v['points'])>0){
					$insert_arr = array();
					$insert_arr['geval_orderid'] = $order_id;
					$insert_arr['geval_orderno'] = $order_info['order_sn'];
					$insert_arr['geval_ordergoodsid'] = $k;
					$insert_arr['geval_goodsid'] = $order_goods[$k]['goods_id'];
					$insert_arr['geval_goodsname'] = $order_goods[$k]['goods_name'];
					$insert_arr['geval_specinfo'] = $order_goods[$k]['spec_info'];
					$insert_arr['geval_goodsprice'] = $order_goods[$k]['goods_price'];
				
					switch (intval($v['points'])){
						case 1:
							$insert_arr['geval_scores'] = 1;
							break;
						case 3:
							$insert_arr['geval_scores'] = -1;
							break;
						default:
							$insert_arr['geval_scores'] = 0;
							break;
					}
					$insert_arr['geval_content'] = trim($v['comment']);
					$insert_arr['geval_isanonymous'] = 0;
					$insert_arr['geval_addtime'] = time();
					$insert_arr['geval_storeid'] = $_SESSION['store_id'];
					$insert_arr['geval_storename'] = $_SESSION['store_name'];
					$insert_arr['geval_frommemberid'] = $_SESSION['member_id'];
					$insert_arr['geval_frommembername'] = $_SESSION['member_name'];
					$insert_arr['geval_tomemberid'] = $member_info['member_id'];
					$insert_arr['geval_tomembername'] = $member_info['member_name'];
					$insert_arr['geval_bothstate'] = $bothstate;
					if ($bothstate == 1){
						$showtime = time()+60*60*24*15;
					}else {
						if ($goodsevallist_new[$k]['geval_scores'] == 1 && $insert_arr['geval_scores'] == 1){
							$showtime = time();
						}else {
							$showtime = time()+60*60*24*2;
						}
					}
					$insert_arr['geval_showtime'] = $showtime;
					$insert_arr['geval_type'] = 2;
					$evaluate_model->addGoodsEval($insert_arr);
					
					if ($bothstate == 2){
						$evaluate_model->editGoodsEval(array('geval_bothstate'=>'2','geval_showtime'=>"$showtime"),array('geval_orderid'=>"$order_id",'geval_ordergoodsid'=>$k,'geval_tomemberid'=>"{$_SESSION['member_id']}"));
					}
					unset($insert_arr);
				}
			}
		
			$state = $order_model->updateOrder(array('evalseller_status'=>1,'evalseller_time'=>time()),$order_id);
			showMessage(Language::get('member_evaluation_evaluat_success'),'index.php?act=store&op=store_order');
		}
	}


	public function listOp(){
		$store_id = intval($_SESSION['store_id']);
		$evaluate_model = Model('evaluate');
		
		$store_model = Model('store');
		$store_info = $store_model->getOne($store_id);
		$store_info['credit_arr'] = getCreditArr(intval($store_info['store_credit']));
	
		$storestat_list = $evaluate_model->getOneStoreEvalStat($store_id);			
	
		$goodsstat_list = $evaluate_model->goodsEvalStatList(array('statstoreid'=>"{$store_id}"));
		Tpl::output('store_info',$store_info);
		Tpl::output('storestat_list',$storestat_list);
		Tpl::output('goodsstat_list',$goodsstat_list);
	
		$condition = array();
	
		if($_GET['evalscore']){
			switch($_GET['evalscore']){
				case 1:
					$condition['geval_scores'] = '1';
					break;
				case 2:
					$condition['geval_scores'] = '0';
					break;
				case 3:
					$condition['geval_scores'] = '-1';
					break;
			}
		}

		if($_GET['havecontent']){
			switch($_GET['havecontent']){
				case 1:
					$condition['havecontent'] = 'yes';
					break;
				case 2:
					$condition['havecontent'] = 'no';
					break;
			}
		}

		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		
		if ($_GET['type'] == 'toothers'){
			$condition['geval_frommemberid'] = "{$_SESSION['member_id']}";
			$goodsevallist = $evaluate_model->getGoodsEvalList($condition,$page,'*','store');
		}elseif ($_GET['type'] == 'fromseller'){
			$condition['geval_tomemberid'] = "{$_SESSION['member_id']}";
			$condition['geval_type'] = "2";
			$goodsevallist = $evaluate_model->getGoodsEvalList($condition,$page,'*','store');
		}else {
			if (empty($_SESSION['store_id'])){
				$condition['geval_storeid'] = "0";
			}else {
				$condition['geval_storeid'] = "{$_SESSION['store_id']}";
			}
			$condition['geval_type'] = "1";
			$goodsevallist = $evaluate_model->getGoodsEvalList($condition,$page,'*','member');
		}
		if (!empty($goodsevallist)){
			foreach ($goodsevallist as $k=>$v){
				switch ($v['geval_scores']){
					case 1:
						$v['geval_scoressign'] = 'good';
						break;
					case -1:
						$v['geval_scoressign'] = 'bad';
						break;
					default:
						$v['geval_scoressign'] = 'normal';
						break;
				}
			
				if ($v['geval_content'] == '' || $v['geval_state'] == '1'){
					$v['geval_content'] = Language::get('member_evaluation_defaultcontent_'.$v['geval_scoressign']);
				}
			
				if ($_GET['type'] == 'toothers' || $_GET['type'] == 'fromseller'){
					$v['credit_arr'] = getCreditArr(intval($v['store_credit']));
				}else{
					$v['credit_arr'] = getCreditArr(intval($v['member_credit']));
				}
			
				$v['geval_frommembername'] = $v['geval_isanonymous'] == 1?str_cut($v['geval_frommembername'],2).'***':$v['geval_frommembername'];
			
				$v['able_edit'] = $v['geval_showtime']<time()?false:true;
				
				$v['able_explain'] = $v['geval_addtime']+(3600*24*30-3600*4)<time()?false:true;
				$goodsevallist[$k] = $v;
			}
		}
		Tpl::output('goodsevallist',$goodsevallist);
		Tpl::output('show_page',$page->show());
		Tpl::output('menu_sign','evaluatemanage');
		Tpl::output('menu_sign_url','index.php?act=store_evaluate&op=list');
		Tpl::showpage('evaluation.index');
	}	

	public function editgoodOp(){
		$id = intval($_GET['id']);
		if ($id <= 0){
			showMessage(Language::get('wrong_argument'),'index.php?act=store_evaluate&op=list&type=toothers','html','error');
		}
		$evaluate_model = Model('evaluate');
	
		$info = $evaluate_model->getGoodsEvalInfo(array('geval_id'=>"$id",'geval_frommemberid'=>"{$_SESSION['member_id']}"));
		if (empty($info) || $info['geval_scores'] == 1 && $info['geval_showtime']<time()){
			showMessage(Language::get('no_record'),'index.php?act=store_evaluate&op=list&type=toothers','html','error');
		}
		if ($_POST['form_submit'] == 'ok'){
		
			$update = array();
			$update['geval_scores'] = 1;
			$update['geval_content'] = $_POST['content'];
			$update['geval_explain'] = '';
			$state = $evaluate_model->editGoodsEval($update,array('geval_id'=>"$id"));
			if ($state){
				showMessage(Language::get('member_evaluation_edit_success'),'index.php?act=store_evaluate&op=list&type=toothers','html');
			}else {
				showMessage(Language::get('member_evaluation_edit_fail'),'index.php?act=store_evaluate&op=list&type=toothers','html','error');
			}
		}else {
			Tpl::output('menu_sign','evaluatemanage');
			Tpl::output('info',$info);
			Tpl::showpage('member_evaluation_edit1');	
		}
	}

	public function editanonyOp(){
		$id = intval($_GET['id']);
		$evaluate_model = Model('evaluate');
	
		$update = array();
		$update['geval_isanonymous'] = 1;
		$state = $evaluate_model->editGoodsEval($update,array('geval_id'=>"$id",'geval_frommemberid'=>"{$_SESSION['member_id']}"));
		if ($state){
			showMessage(Language::get('member_evaluation_edit_success'),'index.php?act=store_evaluate&op=list&type=toothers','html');
		}else {
			showMessage(Language::get('member_evaluation_edit_fail'),'index.php?act=store_evaluate&op=list&type=toothers','html','error');
		}
	}

	public function delOp(){
		$id = intval($_GET['id']);
		$evaluate_model = Model('evaluate');
		
		$info = $evaluate_model->getGoodsEvalInfo(array('geval_id'=>"$id",'geval_frommemberid'=>"{$_SESSION['member_id']}"));
		if (empty($info) || $info['geval_scores'] == 1  && $info['geval_showtime']<time()){
			showDialog(Language::get('no_record'),'index.php?act=store_evaluate&op=list&type=toothers','error');
		}
		$state = $evaluate_model->delGoodsEval(array('geval_id_del'=>"$id"));
		if ($state){
			showDialog(Language::get('member_evaluation_delsuccess'),'index.php?act=store_evaluate&op=list&type=toothers','succ');
		}else {
			showDialog(Language::get('member_evaluation_delfail'),'','error');
		}
	}
	
	public function explainOp(){
		$id = intval($_GET['id']);
		if ($id <= 0){
			showMessage(Language::get('wrong_argument'),'index.php?act=store_evaluate&op=list','html','error');
		}
		$evaluate_model = Model('evaluate');
	
		$info = $evaluate_model->getGoodsEvalInfo(array('geval_id'=>"$id",'geval_tomemberid'=>"{$_SESSION['member_id']}"));
		if (empty($info) || $info['geval_scores'] == 1 && $info['geval_addtime']+3600*24*30<time()){
			showMessage(Language::get('no_record'),'index.php?act=store_evaluate&op=list','html','error');
		}
		if ($_POST['form_submit'] == 'ok'){
		
			$obj_validate = new Validate();
			$validate_arr[] = array("input"=>$_POST["content"],"require"=>"true","message"=>Language::get('member_evaluation_explain_nullerror'));
			$obj_validate -> validateparam = $validate_arr;
			$error = $obj_validate->validate();			
			if ($error != ''){
				showMessage($error,'','html','error');
			}
		
			$update = array();
			$update['geval_explain'] = $_POST['content'];
			$state = $evaluate_model->editGoodsEval($update,array('geval_id'=>"$id"));
			if ($state){
				showMessage(Language::get('member_evaluation_explain_success'),'index.php?act=store_evaluate&op=list','html');
			}else {
				showMessage(Language::get('member_evaluation_explain_fail'),'index.php?act=store_evaluate&op=list','html','error');
			}
		}else {
			switch ($info['geval_scores']){
				case 1:
					$info['geval_scoressign'] = 'good';
					break;
				case -1:
					$info['geval_scoressign'] = 'bad';
					break;
				default:
					$info['geval_scoressign'] = 'normal';
					break;
			}
			Tpl::output('menu_sign','evaluatemanage');
			Tpl::output('info',$info);
			Tpl::showpage('member_evaluation_edit2');	
		}
	}
}