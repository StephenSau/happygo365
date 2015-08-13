<?php 

defined('haipinlegou') or exit('Access Invalid!');

class coupon_storeControl extends BaseStoreControl {
	
	
	public function detailOp(){	
		
		Language::read('home_coupon_index');
		$lang	= Language::getLangContent();
		
		$id = intval($_GET['coupon_id']);
		if($id<1){
			showMessage(Language::get('coupon_index_error'),'','html','error');
		}
		$model_coupon = Model('coupon') ;
		$coupon_detail = $model_coupon->getOneById($id) ;
		if(is_array($coupon_detail)&&!empty($coupon_detail)){

            $store_info = $this->getStoreInfo($coupon_detail['store_id']);

			$_GET['id'] = $store_info['store_id'] ;
      $this->show_storeeval($store_info['store_id']);
			
			$this->offsaleCoupon($store_info['store_id']) ;
			
			$condition = array() ;
			$new = $model_coupon->getCoupon(array('order'=>'coupon_add_date desc','limit'=>3,'coupon_state'=>'2','store_id'=>$store_info['store_id'])) ;
			if(!empty($new)){
				foreach($new as $key=>$val){
				
					$new[$key]['coupon_pic'] = $val['coupon_pic'] != '' ? $val['coupon_pic'] : SiteUrl.DS.ATTACH_COUPON.DS.'default.gif' ;
				
				}			
			}
			Tpl::output('new',$new) ;
			
			
			$coupon_detail['coupon_pic'] = $coupon_detail['coupon_pic'] != '' ? $coupon_detail['coupon_pic'] : SiteUrl.DS.ATTACH_COUPON.DS.'default.gif' ;
			$model_coupon->update_coupon(array('coupon_click'=>array('sign'=>'increase','value'=>1)),array('coupon_id'=>$_GET['coupon_id'])) ;
			Tpl::output('index_sign', 'coupon') ;
			Tpl::output('detail',$coupon_detail) ;
			Tpl::output('page','coupon');
			
			Model('seo')->type('coupon')->param(array('name'=>$coupon_detail['coupon_title']))->show();
			Tpl::showpage('coupon_detail');
		}else{
			showMessage(Language::get('coupon_index_error'),'','html','error') ;
		}
	}

	private function offsaleCoupon($store_id){
		$coupon = Model('coupon') ;
		$coupon->update_coupon(array('coupon_state'=>'1'),array('coupon_state'=>'2','coupon_novalid'=>true,'store_id'=>$store_id));
	}

	
	public function coupon_printOp(){
		
		Language::read('member_store_index');
		
		Tpl::output('html_title',Language::get('store_coupon_print'));
	
		
		$validate = new Validate() ;
		$validate->validateparam = array(array('input'=>$_GET['coupon_id'],'require'=>true,'validator'=>'Number','message'=>Language::get('store_coupon_id_error')),
										array('input'=>$_GET['num'],'require'=>true,'validator'=>'Range','min'=>1,'max'=>8,'message'=>Language::get('store_coupon_num_error'))) ;
		$error = $validate->validate() ;
		if($error){
			showMessage($error,'','html','error');
		}else{
			
			$model = Model('coupon') ;
			$detail = $model->getOneById(intval($_GET['coupon_id'])) ;
			if(empty($detail)) {
				showMessage(Language::get('store_coupon_error'),'','html','error');
			}else{
				if(stripos($detail['coupon_pic'] ,'http://') === false){
					$pic = SiteUrl.DS.$detail['coupon_pic'] ;
				}else{
					$pic = $detail['coupon_pic'] ;
				}
				
				$model->update_coupon(array('coupon_usage'=>array('sign'=>'increase','value'=>1)),array('coupon_id'=>$_GET['coupon_id'])) ;
			
				Tpl::output('pic',$pic);
				Tpl::output('num',$_GET['num']);
				Tpl::showpage('coupon_print','null_layout');
			}
		}
	}
}
