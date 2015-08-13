<?php

defined('haipinlegou') or exit('Access Invalid!');
class flea_goodsControl extends BaseHomeControl {
	
	public function __construct(){
		parent::__construct();
		Language::read('home_flea_index');
		if($GLOBALS['setting_config']['flea_isuse']!='1'){
			showMessage(Language::get('flea_index_unable'),'index.php');
		}
	}
	
	public function indexOp() {
	
		Language::read('home_layout,home_flea_goods_index');
		$lang	= Language::getLangContent();
		
		$area_model	= Model('flea_area');
		$area_array	= $area_model->area_show();
		Tpl::output('area_one_level', $area_array['area_one_level']);
		Tpl::output('area_two_level', $area_array['area_two_level']);
		
		if(empty($_GET['goods_id']))showMessage($lang['miss_argument'],'','html','error');
		$goods_id	= intval($_GET['goods_id']);
		if(!empty($_GET['succ'])){
			if($_GET['succ']=='succ'){
				$succ_link = 'location:index.php?act=flea_goods&goods_id='.$_GET['goods_id']."#flea_message";
				@header($succ_link);
			}
		}
		
		$model_store_goods	= Model('flea');
		$goods_array		= $model_store_goods->listGoods(array('goods_id'=>intval($_GET['goods_id']),'goods_show'=>'1'),'','flea.*');
		
		if(empty($goods_array))showMessage($lang['goods_index_no_goods'],'','html','error');
	
		
		$goods_image_path = ATTACH_FLEAS.'/'.$goods_array[0]['member_id'].'/';	
		$goods_array[0]['goods_image']	= $goods_array[0]['goods_image']!='' ? $goods_image_path.$goods_array[0]['goods_image'] :ATTACH_COMMON.DS.$GLOBALS['setting_config']['default_goods_image'];
		$goods_array[0]['goods_tag']	= explode(',',str_replace('，',',',$goods_array[0]['goods_tag']));
		
		
		Tpl::output('goods_title',$goods_array[0]['goods_name']);
		Tpl::output('seo_keywords',$goods_array[0]['seo_keywords']);
		Tpl::output('seo_description',$goods_array[0]['seo_description']);
		
		$desc_image	= $model_store_goods->getListImageGoods(array('image_store_id'=>$goods_array[0]['member_id'],'item_id'=>$goods_array[0]['goods_id'],'image_type'=>12));
		$model_store_goods->getThumb($desc_image,$goods_image_path);
		
		$image_key = 0;
		if(!empty($desc_image) && is_array($desc_image)) {
			$goods_image_1	= $goods_array[0]['goods_image'];
			foreach ($desc_image as $key => $val) {
				if($goods_image_1 == $val['thumb_small']){
					$image_key = $key;break;
				}
			}
			if($image_key > 0) {
				$desc_image_0	= $desc_image[0];
				$desc_image[0]	= $desc_image[$image_key];
				$desc_image[$image_key]	= $desc_image_0;
			}
		}

		Tpl::output('goods',$goods_array[0]);
		Tpl::output('goods_image',$desc_image[0]);
		Tpl::output('desc_image',$desc_image);
		Tpl::output('goods_image_path',$goods_image_path);
		
		$model_member = Model('member');
		$member_info = $model_member->infoMember(array('member_id'=>$goods_array[0]['member_id']));
		Tpl::output('flea_member_info',$member_info);
		
		$other_flea_info = $model_store_goods->listGoods(array('goods_id_diff'=>intval($_GET['goods_id']),'goods_show'=>'1','limit'=>'2'),'','flea.*');
		$other_flea_info2 = $model_store_goods->listGoods(array('member_id'=>$goods_array[0]['member_id'],'goods_show'=>'1'),'','flea.*');
		Tpl::output('goods_commend2',$other_flea_info);
		Tpl::output('goods_commend3',$other_flea_info2);
		
		$seo_keywords    = $goods_array[0]['goods_keywords'];
		$seo_description = $goods_array[0]['goods_description'];
		Tpl::output('seo_keywords',$seo_keywords);
		Tpl::output('seo_description',$seo_description);
		
		$consult		= Model('flea_consult');
		$consult_list	= $consult->getConsultList(array('goods_id'=>$goods_id,'order'=>'consult_id desc'),'','seller');
		Tpl::output('consult_list',$consult_list);
		
		$model_store_goods->updateGoods(array('goods_click'=>($goods_array[0]['goods_click']+1)),$goods_id);
		
		$goods_list = $model_store_goods->listGoods(array('limit'=>'27','pic_input'=>'2'));
		list($goods_commend_list, $goods_commend_list4, $goods_commend_list5)	= @array_chunk($goods_list, 9);
		Tpl::output('goods_commend',$goods_commend_list);
		Tpl::output('goods_commend4',$goods_commend_list4);
		Tpl::output('goods_commend5',$goods_commend_list5);
		Tpl::showpage('flea_goods','flea_layout');
	}
	
	public function save_consultOp(){
		if(empty($_SESSION['member_id'])){
			showMessage(Language::get('flea_consult_notice'),'','','error');
		}
		Language::read('home_flea_goods_index');
		$lang	= Language::getLangContent();
		
		$goods	= Model('flea');
		$condition	= array();
		$goods_info	= array();
		if($_POST['type_name']==''){
			$condition['goods_id']	= $_POST['goods_id'];
			$goods_info	= $goods->listGoods($condition);
		}
		if(empty($goods_info)){
			if($_POST['type_name']==''){
				showMessage($lang['goods_index_goods_not_exists'],'','html','error');
			}
		}
		
		if(trim($_POST['content'])===""){
			showMessage($lang['goods_index_input_consult'],'','html','error');
		}
		$model_member = Model('member');
		$member_info = $model_member->infoMember(array('member_id'=>$_GET['goods_id']));
		
		$input	= array();
		$input['seller_id']			= $member_info['member_id'];
		$input['member_id']			= $_POST['hide_name']?0:(empty($_SESSION['member_id'])?0:$_SESSION['member_id']);
		$input['goods_id']			= $_POST['goods_id'];
		$input['email']				= $_POST['email'];
		$input['consult_content']	= $_POST['content'];
		if($_POST['type_name']==''){
			$input['type']			= 'flea';
		}else{
			$input['type']			= $_POST['type_name'];
		}
		$consult	= Model('flea_consult');
		if($consult->addConsult($input)){
			$condition['commentnum']['value']='1';
			$condition['commentnum']['sign']='increase';
			$goods->updateGoods($condition,intval($_POST['goods_id']));
			$succ_link = 'index.php?act=flea_goods&goods_id='.intval($_POST['goods_id']).'&succ=succ';
			showMessage($lang['goods_index_consult_success'],$succ_link);
		}else{
			showMessage($lang['goods_index_consult_fail'],'','html','error');
		}
	}
}


function checkQuality($flea_quality){
	if($flea_quality==''){
		return false;
	}
	Language::read('common_flea');
	$lang	= Language::getLangContent();
	switch ($flea_quality){
		case '10':
			echo $lang['new_10'];
			break;
		case '9':
			echo $lang['new_9'];
			break;
		case '8':
			echo $lang['new_8'];
			break;
		case '7':
			echo $lang['new_7'];
			break;
	}
}


function checkTime($time){
	if($time==''){
		return false;
	}
	Language::read('common_flea');
	$lang	= Language::getLangContent();
	$catch_time = (time() - $time);
	if($catch_time < 60){
		echo $catch_time.$lang['second'];
	}elseif ($catch_time < 60*60){
		echo intval($catch_time/60).$lang['minute'];
	}elseif ($catch_time < 60*60*24){
		echo intval($catch_time/60/60).$lang['hour'];
	}elseif ($catch_time < 60*60*24*365){
		echo intval($catch_time/60/60/24).$lang['day'];
	}elseif ($catch_time < 60*60*24*365*999){
		echo intval($catch_time/60/60/24/365).$lang['year'];
	}
}
