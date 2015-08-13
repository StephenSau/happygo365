<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_voucherControl extends BaseMemberStoreControl{
	const SECONDS_OF_30DAY = 2592000;	
    private $applystate_arr;
    private $quotastate_arr;
    private $templatestate_arr;
    
	public function __construct() {
		parent::__construct() ;
		Language::read('member_layout,member_voucher');
		if (C('voucher_allow') != 1){
			showMessage(Language::get('voucher_unavailable'),'index.php?act=store','html','error');
		}
		$this->applystate_arr = array('new'=>array(1,Language::get('voucher_applystate_new')),'verify'=>array(2,Language::get('voucher_applystate_verify')),'cancel'=>array(3,Language::get('voucher_applystate_cancel')));
		$this->quotastate_arr = array('activity'=>array(1,Language::get('voucher_quotastate_activity')),'cancel'=>array(2,Language::get('voucher_quotastate_cancel')),'expire'=>array(3,Language::get('voucher_quotastate_expire')));
		$this->templatestate_arr = array('usable'=>array(1,Language::get('voucher_templatestate_usable')),'disabled'=>array(2,Language::get('voucher_templatestate_disabled')));
		Tpl::output('applystate_arr',$this->applystate_arr);
		Tpl::output('quotastate_arr',$this->quotastate_arr);
		Tpl::output('templatestate_arr',$this->templatestate_arr);
	}
	
	public function indexOp() {
        $model = Model('voucher');
        $current_quota = $model->getCurrentQuota($_SESSION['store_id']);
        $quota_flag = false;
        if(empty($current_quota)){
        	$this->quotalistOp();
        }else{
        	$this->templatelistOp();
        }
    }
	
	public function templatelistOp(){
        $this->check_voucher_template_expire();
        $model = Model('voucher');
        $current_quota = $model->getCurrentQuota($_SESSION['store_id']);
        Tpl::output('current_quota',$current_quota);
		$param = array();
		$param['voucher_t_store_id'] = $_SESSION['store_id'];
		if(trim($_GET['txt_keyword'])){
			$param['voucher_t_title'] = array('like','%'.trim($_GET['txt_keyword']).'%');
		}
		$select_state = intval($_GET['select_state']);
		if($select_state){
			$param['voucher_t_state'] = $select_state;
		}
		if($_GET['txt_startdate']){
			$param['voucher_t_end_date'] = array('egt',strtotime($_GET['txt_startdate']));
		}
		if($_GET['txt_enddate']){
			$param['voucher_t_start_date'] = array('elt',strtotime($_GET['txt_enddate']));
		}
		$list = $model->table('voucher_template')->where($param)->order('voucher_t_id desc')->page(10)->select();
		if(is_array($list)){
			foreach ($list as $key=>$val){
				if (!$val['voucher_t_customimg'] || !file_exists(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$val['voucher_t_customimg'])){
					$list[$key]['voucher_t_customimg'] = defaultGoodsImage('tiny');
				}else{
					$list[$key]['voucher_t_customimg'] = SiteUrl.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$val['voucher_t_customimg']."_small.".get_image_type($val['voucher_t_customimg']);
				}
			}
		}
		
        $this->profile_menu('voucher','templatelist');
		Tpl::output('list',$list);
		Tpl::output('show_page',$model->showpage(2));
		Tpl::output('menu_sign','store_voucher');
		Tpl::output('menu_sign_url','index.php?act=store_voucher');
		Tpl::output('menu_sign1','store_voucher');
		Tpl::showpage('store_voucher_template.index') ;
	}
	
    public function quotalistOp(){
        $model = Model('voucher');
        $newapply_flag = $model->getNewApply($_SESSION['store_id']);
        Tpl::output('newapply_flag',$newapply_flag);
        
        $time = time();
        $model->table('voucher_quota')->where(array('quota_storeid'=>$_SESSION['store_id'],'quota_endtime'=>array('lt',"$time"),'quota_state'=>"{$this->quotastate_arr['activity'][0]}"))->update(array('quota_state'=>$this->quotastate_arr['expire'][0]));
        $list = $model->table('voucher_quota')->where(array('quota_storeid'=>$_SESSION['store_id']))->order('quota_id desc')->page(10)->select();
        Tpl::output('show_page',$model->showpage(2));
        $this->profile_menu('voucher','quotalist');
        Tpl::output('list',$list);
        Tpl::output('menu_sign','store_voucher');
		Tpl::output('menu_sign_url','index.php?act=store_voucher');
        Tpl::showpage('store_voucher_quota.list');
    }
    
    public function applylistOp(){
    	$model = Model('voucher');
        $newapply_flag = $model->getNewApply($_SESSION['store_id']);
        Tpl::output('newapply_flag',$newapply_flag);
        
        $list = $model->table('voucher_apply')->where(array('apply_storeid'=>$_SESSION['store_id']))->order('apply_id desc')->page(10)->select();
        Tpl::output('show_page',$model->showpage(2));
        $this->profile_menu('voucher','applylist');
        Tpl::output('list',$list);
        Tpl::output('menu_sign','store_voucher');
		Tpl::output('menu_sign_url','index.php?act=store_voucher');
        Tpl::showpage('store_voucher_apply.list');
    }
	
	public function quotaaddOp(){
		if (chksubmit()){
	        $quota_quantity = intval($_POST['quota_quantity']);
	        if($quota_quantity <= 0 || $quota_quantity > 12) {
	            showDialog(Language::get('voucher_apply_num_error'));
	        }
	        $current_price = intval(C('promotion_voucher_price'));
	        $model = Model();
	        $member_info = $model->table('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
	        if(empty($member_info)) {
	            showDialog(Language::get('voucher_apply_fail'),"index.php?act=store_voucher&op=applylist");
	        }
	        $apply_price = $quota_quantity * $current_price;
	        if($apply_price > intval($member_info['member_goldnum'])) {
	        	$message = sprintf(Language::get('voucher_apply_goldnotenough'),$member_info['member_goldnum']);;
	            showDialog($message,"index.php?act=store_gbuy",'','',10);
	        }
            $param = array();
            $param['apply_memberid'] = $_SESSION['member_id'];
            $param['apply_membername'] = $_SESSION['member_name'];
            $param['apply_storeid'] = $_SESSION['store_id'];
            $param['apply_storename'] = $_SESSION['store_name'];
            $param['apply_quantity'] = $quota_quantity;
            $param['apply_datetime'] = time();
            $param['apply_state'] = 1;
            $rs = $model->table('voucher_apply')->insert($param);
            if(!$rs) {
                showDialog(Language::get('voucher_apply_fail'),'index.php?act=store_voucher&op=applylist');
            }
            
            
			$update_array = array();
			$update_array['member_goldnum'] = array('exp','member_goldnum - '.$apply_price);
			$update_array['member_goldnumminus'] = array('exp','member_goldnumminus + '.$apply_price);
			$result = $model->table('member')->where(array('member_id'=>$_SESSION['member_id']))->update($update_array);
			if(!$result){
				showDialog(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=applylist');
			}
			
			$param = array();
			$param['glog_memberid'] 	= $_SESSION['member_id'];
			$param['glog_membername'] 	= $_SESSION['member_name'];
			$param['glog_storeid'] 		= $_SESSION['store_id'];
			$param['glog_storename'] 	= $_SESSION['store_name'];
			$param['glog_adminid'] 		= '';
			$param['glog_adminname'] 	= 'system';
			$param['glog_goldnum'] 		= $apply_price;
			$param['glog_method'] 		= 2;
			$param['glog_addtime'] 		= time();
			$param['glog_desc'] 		= sprintf(Language::get('voucher_apply_goldlog'),$quota_quantity,$current_price,$apply_price);
			$param['glog_stage'] 		= 'voucher';
			$model->table('gold_log')->insert($param);
            
			$last_quota = $model->table('voucher_quota')->where(array('quota_storeid'=>$_SESSION['store_id'],'quota_state'=>$this->quotastate_arr['activity'][0]))->order('quota_endtime desc')->find();
			$start_time = time();
			if(!empty($last_quota)) {
				$last_end_time = intval($last_quota['quota_endtime']) + 1;
				if($last_end_time > $start_time) {
					$start_time = $last_end_time;
				}
			}
			$param = array();
			$param['quota_applyid']		= $rs;
			$param['quota_memberid']	= $_SESSION['member_id'];
			$param['quota_membername']	= $_SESSION['member_name'];
			$param['quota_storeid']		= $_SESSION['store_id'];
			$param['quota_storename']	= $_SESSION['store_name'];
			$param['quota_timeslimit']	= intval(C('promotion_voucher_storetimes_limit'));
			$param['quota_state']		= 1;

			$param_array = array();
			for($i=0;$i<$quota_quantity;$i++) {
				$end_time = $start_time + self::SECONDS_OF_30DAY - 1;
			    $param['quota_starttime'] = $start_time;
			    $param['quota_endtime'] = $end_time;
			    $param_array[] = $param;
			    $start_time += self::SECONDS_OF_30DAY;
			}
			$reault = $model->table('voucher_quota')->insertAll($param_array);
			if($reault !== true){
				showDialog(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=quotalist');
			}
			$result = $model->table('voucher_apply')->where(array('apply_id'=>$rs))->update(array('apply_state'=>$this->applystate_arr['verify'][0]));
			showDialog(Language::get('voucher_apply_buy_succ'),'index.php?act=store_voucher&op=quotalist','succ');
		}else {
	        self::profile_menu('quota_add','quotaadd');
	        Tpl::output('menu_sign','store_voucher');
			Tpl::output('menu_sign_url','index.php?act=store_voucher');
	        Tpl::showpage('store_voucher_quota.add');
		}
    }

    public function templateaddOp(){
        $model = Model('voucher');
        $quotainfo = $model->getCurrentQuota($_SESSION['store_id']);
        if(empty($quotainfo)){
        	showMessage(Language::get('voucher_template_quotanull'),'index.php?act=store_voucher&op=quotaadd','html','error');
        }
        $count = $model->table('voucher_template')->where(array('voucher_t_quotaid'=>$quotainfo['quota_id'],'voucher_t_state'=>$this->templatestate_arr['usable'][0]))->count();
        if ($count >= C('promotion_voucher_storetimes_limit')){
        	$message = sprintf(Language::get('voucher_template_noresidual'),C('promotion_voucher_storetimes_limit'));
        	showMessage($message,'index.php?act=store_voucher&op=templatelist','html','error');
        }
        $pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
        if(empty($pricelist)){
        	showMessage(Language::get('voucher_template_pricelisterror'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        if(chksubmit()){
	        $obj_validate = new Validate();
	        $obj_validate->validateparam = array(
	            array("input"=>$_POST['txt_template_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('voucher_template_title_error')),
	            array("input"=>$_POST['txt_template_total'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_total_error')),
	            array("input"=>$_POST['select_template_price'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_price_error')),
	            array("input"=>$_POST['txt_template_limit'], "require"=>"true","validator"=>"Double","message"=>Language::get('voucher_template_limit_error')),
	            array("input"=>$_POST['txt_template_describe'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('voucher_template_describe_error')),
	        );
	        $error = $obj_validate->validate();
	        $price = intval($_POST['select_template_price'])>0?intval($_POST['select_template_price']):0;
	        foreach($pricelist as $k=>$v){
        		if($v['voucher_price'] == $price){
        			$chooseprice = $v;
        		}
        	}
        	if(empty($chooseprice)){
        		$error.=Language::get('voucher_template_pricelisterror');
        	}
	        $limit = intval($_POST['txt_template_limit'])>0?intval($_POST['txt_template_limit']):0;
	        if($price>=$limit) $error.=Language::get('voucher_template_price_error');
	        if ($error){
	            showMessage($error,'','html','error');
	        }else {
	        	$insert_arr = array();
	        	$insert_arr['voucher_t_title'] = trim($_POST['txt_template_title']);
	        	$insert_arr['voucher_t_desc'] = trim($_POST['txt_template_describe']);
	        	$insert_arr['voucher_t_start_date'] = time();
	        	if ($_POST['txt_template_enddate']){
	        		$enddate = strtotime($_POST['txt_template_enddate']);
	        		if ($enddate > $quotainfo['quota_endtime']){
	        			$enddate = $quotainfo['quota_endtime'];
	        		}
	        		$insert_arr['voucher_t_end_date'] = $enddate;
	        	}else {
	        		$insert_arr['voucher_t_end_date'] = $quotainfo['quota_endtime'];
	        	}
	        	$insert_arr['voucher_t_price'] = $price;
	        	$insert_arr['voucher_t_limit'] = $limit;
	        	$insert_arr['voucher_t_store_id'] = $_SESSION['store_id'];
	        	$insert_arr['voucher_t_storename'] = $_SESSION['store_name'];
	        	$insert_arr['voucher_t_creator_id'] = $_SESSION['member_id'];
	        	$insert_arr['voucher_t_state'] = $this->templatestate_arr['usable'][0];
	        	$insert_arr['voucher_t_total'] = intval($_POST['txt_template_total'])>0?intval($_POST['txt_template_total']):0;
	        	$insert_arr['voucher_t_giveout'] = 0;
	        	$insert_arr['voucher_t_used'] = 0;
	        	$insert_arr['voucher_t_add_date'] = time();
	        	$insert_arr['voucher_t_quotaid'] = $quotainfo['quota_id'];
	        	$insert_arr['voucher_t_points'] = $chooseprice['voucher_defaultpoints'];
	        	$insert_arr['voucher_t_eachlimit'] = intval($_POST['eachlimit'])>0?intval($_POST['eachlimit']):0;
		        if (!empty($_FILES['customimg']['name'])){
		        	$upload = new UploadFile();
		        	$upload->set('default_dir',	ATTACH_VOUCHER.DS.$_SESSION['store_id']);
		        	$upload->set('thumb_width','160');
					$upload->set('thumb_height','160');
					$upload->set('thumb_ext','_small');
					$result = $upload->upfile('customimg');
					if ($result){
						$insert_arr['voucher_t_customimg'] =  $upload->file_name;
					}
				}
	            $rs = $model->table('voucher_template')->insert($insert_arr);
	            if($rs){
	                showMessage(Language::get('nc_common_save_succ'),'index.php?act=store_voucher&op=templatelist','succ'); 
	            }else{
	                showMessage(Language::get('nc_common_save_fail'),'index.php?act=store_voucher&op=templatelist','error'); 
	            }
	        }
        }else{
	        TPL::output('type','add');
	        TPL::output('quotainfo',$quotainfo);
	        TPL::output('pricelist',$pricelist);
	        $this->profile_menu('template','templateadd');
	        Tpl::output('menu_sign','store_voucher');
			Tpl::output('menu_sign_url','index.php?act=store_voucher');
	        Tpl::showpage('store_voucher_template.add');
        }        
    }
	
    public function templateeditOp(){
    	$t_id = intval($_GET['tid']);
    	if ($t_id <= 0){
    		$t_id = intval($_POST['tid']);
    	}
    	if ($t_id <= 0){
    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
    	}
        $model = Model('voucher');
        $param = array();
        $param['voucher_t_id'] = $t_id;
        $param['voucher_t_store_id'] = $_SESSION['store_id'];
        $param['voucher_t_state'] = $this->templatestate_arr['usable'][0];
        $param['voucher_t_giveout'] = array('elt','0');
        $param['voucher_t_end_date'] = array('gt',time());
        $t_info = $model->table('voucher_template')->where($param)->find();
        if (empty($t_info)){
        	showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        $quotainfo = $model->table('voucher_quota')->where(array('quota_id'=>$t_info['voucher_t_quotaid'],'quota_storeid'=>$_SESSION['store_id']))->find();
        if(empty($quotainfo)){
        	showMessage(Language::get('voucher_template_quotanull'),'index.php?act=store_voucher&op=quotaadd','html','error');
        }
        $pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
        if(empty($pricelist)){
        	showMessage(Language::get('voucher_template_pricelisterror'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        if(chksubmit()){
	        $obj_validate = new Validate();
	        $obj_validate->validateparam = array(
	            array("input"=>$_POST['txt_template_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('voucher_template_title_error')),
	            array("input"=>$_POST['txt_template_total'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_total_error')),
	            array("input"=>$_POST['select_template_price'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_price_error')),
	            array("input"=>$_POST['txt_template_limit'], "require"=>"true","validator"=>"Double","message"=>Language::get('voucher_template_limit_error')),
	            array("input"=>$_POST['txt_template_describe'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('voucher_template_describe_error')),
	        );
	        $error = $obj_validate->validate();
	        $price = intval($_POST['select_template_price'])>0?intval($_POST['select_template_price']):0;
	        foreach($pricelist as $k=>$v){
        		if($v['voucher_price'] == $price){
        			$chooseprice = $v;
        		}
        	}
        	if(empty($chooseprice)){
        		$error.=Language::get('voucher_template_pricelisterror');
        	}
	        $limit = intval($_POST['txt_template_limit'])>0?intval($_POST['txt_template_limit']):0;
	        if($price>=$limit) $error.=Language::get('voucher_template_price_error');
	        if ($error){
	            showMessage($error,'','html','error');
	        }else {
	        	$update_arr = array();
	        	$update_arr['voucher_t_title'] = trim($_POST['txt_template_title']);
	        	$update_arr['voucher_t_desc'] = trim($_POST['txt_template_describe']);
	        	if ($_POST['txt_template_enddate']){
	        		$enddate = strtotime($_POST['txt_template_enddate']);
	        		if ($enddate > $quotainfo['quota_endtime']){
	        			$enddate = $quotainfo['quota_endtime'];
	        		}
	        		$update_arr['voucher_t_end_date'] = $enddate;
	        	}else {
	        		$update_arr['voucher_t_end_date'] = $quotainfo['quota_endtime'];
	        	}
	        	$update_arr['voucher_t_price'] = $price;
	        	$update_arr['voucher_t_limit'] = $limit;
	        	$update_arr['voucher_t_state'] = intval($_POST['tstate']) == $this->templatestate_arr['usable'][0]?$this->templatestate_arr['usable'][0]:$this->templatestate_arr['disabled'][0];
	        	$update_arr['voucher_t_total'] = intval($_POST['txt_template_total'])>0?intval($_POST['txt_template_total']):0;
	        	$update_arr['voucher_t_points'] = $chooseprice['voucher_defaultpoints'];
	        	$update_arr['voucher_t_eachlimit'] = intval($_POST['eachlimit'])>0?intval($_POST['eachlimit']):0;
		        if (!empty($_FILES['customimg']['name'])){
		        	$upload = new UploadFile();
		        	$upload->set('default_dir',	ATTACH_VOUCHER.DS.$_SESSION['store_id']);
		        	$upload->set('thumb_width','160');
					$upload->set('thumb_height','160');
					$upload->set('thumb_ext','_small');
					$result = $upload->upfile('customimg');
					if ($result){
						if (!empty($t_info['voucher_t_customimg'])){
							@unlink(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']);
							@unlink(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']."_small.".get_image_type($t_info['voucher_t_customimg']));
						}
						$update_arr['voucher_t_customimg'] =  $upload->file_name;
					}
				}
	            $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->update($update_arr);
	            if($rs){
	                showMessage(Language::get('nc_common_op_succ'),'index.php?act=store_voucher&op=templatelist','succ'); 
	            }else{
	                showMessage(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=templatelist','error'); 
	            }
	        }
        }else{
	        if (!$t_info['voucher_t_customimg'] || !file_exists(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg'])){
	        	$t_info['voucher_t_customimg'] = defaultGoodsImage('small');
	        }else{
	        	$t_info['voucher_t_customimg'] = SiteUrl.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']."_small.".get_image_type($t_info['voucher_t_customimg']);
	        }
	        TPL::output('type','edit');
	        TPL::output('t_info',$t_info);
	        TPL::output('quotainfo',$quotainfo);
	        TPL::output('pricelist',$pricelist);
	        $this->profile_menu('templateedit','templateedit');
	        Tpl::output('menu_sign','store_voucher');
			Tpl::output('menu_sign_url','index.php?act=store_voucher');
	        Tpl::showpage('store_voucher_template.add');
        }
    }
    
    public function templatedelOp(){
    	$t_id = intval($_GET['tid']);
    	if ($t_id <= 0){
    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
    	}
        $model = Model();
        $param = array();
        $param['voucher_t_id'] = $t_id;
        $param['voucher_t_store_id'] = $_SESSION['store_id'];
        $param['voucher_t_giveout'] = array('elt','0');
        $t_info = $model->table('voucher_template')->where($param)->find();
    	if (empty($t_info)){
    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
    	}
        $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->delete();
        if ($rs){
        	if (trim($t_info['voucher_t_customimg'])){
        		@unlink(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']);
        		@unlink(BasePath.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']."_small.".get_image_type($t_info['voucher_t_customimg']));
        	}
        	showDialog(Language::get('nc_common_del_succ'),'reload','succ');
        }else {
        	showDialog(Language::get('nc_common_del_fail'));
        }
    }
    
    public function templateinfoOp(){
    	$t_id = intval($_GET['tid']);
    	if ($t_id <= 0){
    		showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
    	}
        $model = Model();
        $param = array();
        $param['voucher_t_id'] = $t_id;
        $param['voucher_t_store_id'] = $_SESSION['store_id'];
        $t_info = $model->table('voucher_template')->where($param)->find();
        TPL::output('t_info',$t_info);
        $this->profile_menu('templateinfo','templateinfo');
        Tpl::output('menu_sign','store_voucher');
        Tpl::output('menu_sign_url','index.php?act=store_voucher');
        Tpl::showpage('store_voucher_template.info');
    }
	
    private function check_voucher_template_expire($voucher_template_id=''){
        $where_array = array();
        if(empty($voucher_template_id)) {
            $where_array['voucher_t_end_date'] = array('lt',time());
        } else {
            $where_array['voucher_t_id'] = $voucher_template_id;
        }
        $where_array['voucher_t_state'] = $this->templatestate_arr['usable'][0];
        $model = Model();
        $model->table('voucher_template')->where($where_array)->update(array('voucher_t_state'=>$this->templatestate_arr['disabled'][0]));
    }
	
	private function profile_menu($menu_type,$menu_key='') {
		Language::read('member_layout');
		$menu_array	= array();
		switch ($menu_type) {
			case 'voucher':
				$menu_array = array(
				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
				2=>array('menu_key'=>'quotalist','menu_name'=>Language::get('voucher_quotalist'),	'menu_url'=>'index.php?act=store_voucher&op=quotalist'),
				3=>array('menu_key'=>'applylist','menu_name'=>Language::get('voucher_applyquota'),	'menu_url'=>'index.php?act=store_voucher&op=applylist'),
				);
				break;
			case 'quota_add':
				$menu_array = array(
				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
				2=>array('menu_key'=>'quotalist','menu_name'=>Language::get('voucher_quotalist'),	'menu_url'=>'index.php?act=store_voucher&op=quotalist'),
				3=>array('menu_key'=>'applylist','menu_name'=>Language::get('voucher_applyquota'),	'menu_url'=>'index.php?act=store_voucher&op=applylist'),
				4=>array('menu_key'=>'quotaadd','menu_name'=>Language::get('voucher_applyadd'),	'menu_url'=>'index.php?act=store_voucher&op=quotaadd')
				);
				break;
			case 'template':
				$menu_array = array(
				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
				2=>array('menu_key'=>'templateadd','menu_name'=>Language::get('voucher_templateadd'),	'menu_url'=>'index.php?act=store_voucher&op=templateadd'),
				);
				break;
			case 'templateedit':
				$menu_array = array(
				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
				2=>array('menu_key'=>'templateedit','menu_name'=>Language::get('voucher_templateedit'),	'menu_url'=>''),
				);
				break;
			case 'templateinfo':
				$menu_array = array(
				1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'),	'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
				2=>array('menu_key'=>'templateinfo','menu_name'=>Language::get('voucher_templateinfo'), 'menu_url'=>''),
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
