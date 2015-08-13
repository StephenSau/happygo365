<?php

defined('haipinlegou') or exit('Access Invalid!');
class advclickControl extends BaseHomeControl {
	
	public function advclickOp(){
		
		$adv = Model('adv');
		$condition['adv_id'] = intval($_GET['adv_id']);
		$adv_info = $adv->getList($condition);
		$adv_info = $adv_info['0'];
		$param['ap_id'] = $adv_info['ap_id']; 
		$ap_info        = $adv->getApList($param);
		$ap_info        = $ap_info['0']; 
		$adv_content = unserialize($adv_info['adv_content']);
		switch ($ap_info['ap_class']) {
			case '0':
				$url = $adv_content['adv_pic_url'];
			break;
			case '1':
			    $url = $adv_content['adv_word_url'];
			break;
			case '2':
				$url = $adv_content['adv_slide_url'];
			break;
		}
		$url = str_replace(array('&amp;', 'http://'), array('&', ''), $url);
		
		$adv_param['adv_id']    = intval($_GET['adv_id']);
		$adv_param['click_num'] = $adv_info['click_num']+1;
		$adv->update($adv_param);
		$ap_param['ap_id']     = $adv_info['ap_id'];
		$ap_param['click_num'] = $ap_info['click_num']+1;
		$adv->ap_update($ap_param);
		$date = date('Y-m-d',time());
		$date = explode('-', $date);
		$year = $date['0'];
		$month= $date['1'];
		$click_info = $adv->getOneClickById(intval($_GET['adv_id']),$year,$month);
		if(empty($click_info)){
			$param['adv_id']      = intval($_GET['adv_id']);
			$param['ap_id']       = $adv_info['ap_id'];
			$param['adv_name']    = $adv_info['adv_title'];
			$param['ap_name']     = $ap_info['ap_name'];
			$param['click_year']  = $year;
			$param['click_month'] = $month;
			$param['click_num']   = '1';
			$adv->adv_click_add($param);
		}else{
			$param['adv_id']      = intval($_GET['adv_id']);
			$param['click_year']  = $year;
			$param['click_month'] = $month;
			$param['click_num']   = $click_info['click_num']+1;
			$adv->adv_click_update($param);
		}
		
		header("location:http://$url");
	}
	
}