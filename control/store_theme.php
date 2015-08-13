<?php

defined('haipinlegou') or exit('Access Invalid!');

class store_themeControl extends BaseMemberStoreControl {
	public function __construct() {
		parent::__construct();
		
		Language::read('member_store_index');
		if ($_SESSION['store_id']<1){
			@header("Location: index.php?act=home");
			exit;
		}
		
		Tpl::setLayout('member_goods_marketing_layout');
	}
	
	public function indexOp(){
		$site_url = SiteUrl;
		$tpl_name = TPL_NAME;
		$style_id	= 'default';
		$store_id	= $_SESSION['store_id'];
		
		$store_model = Model('store');
		$store_info = $store_model->shopStore(array('store_id'=>$store_id));
		$style_id	= $store_info['store_theme'];
		$theme_model = Model('store_theme');
		$style_data = $theme_model->getStyleConfig();
		$state = $theme_model->getShowStyle($style_id);
		if ($state !== 1){
			showMessage('当前主题不可编辑','index.php?act=store&op=theme');
		}
		
		
		if ($_POST['form_submit'] == 'ok'){
			$theme_array = array();
			$theme_array['member_id'] = $store_info['member_id'];
			$theme_array['member_name'] = $store_info['member_name'];
			$theme_array['store_id'] = $store_info['store_id'];
			$theme_array['store_name'] = $store_info['store_name'];
			$theme_array['style_id'] = $style_id;
			$theme_array['style_name'] = $style_data[$style_id]['truename'];
			$theme_array['update_time'] = time();
			$theme_array['theme_info'] = $_POST['content'];
			$theme_id	= intval($_POST['theme_id']);
			if($theme_id > 0){
				$theme_model->update(array('theme_id'=>$theme_id,'store_id'=>$store_id),$theme_array);
			}else {
				$theme_model->add($theme_array);
			}
			showMessage('保存成功','index.php?act=store_theme');
		}
		$condition = array();
		$condition['style_id'] = $style_id;
		$condition['store_id'] = $store_id;
		$theme_list = $theme_model->getList($condition);
		$editor_multimedia = true;
		Tpl::output('editor_multimedia',$editor_multimedia);
		
		
		$style_info = '';
		$style_template = '';
		$style_configurl = BASE_TPL_PATH.DS.'store'.DS.'style'.DS.$style_id.DS."style_config.php";
		if (file_exists($style_configurl)){
			include_once($style_configurl);
			Tpl::output('style_info',$style_info);
			Tpl::output('style_template',$style_template);
			if (empty($theme_list[0]["theme_info"])) $theme_list[0]["theme_info"] = $style_template;
		}
		Tpl::output('theme',$theme_list[0]);
		
		self::profile_menu('store_theme','store_theme');
		Tpl::output('menu_sign','store_theme');
		Tpl::output('menu_sign_url','index.php?act=store&op=theme');
		Tpl::output('menu_sign1','edit_theme');
		Tpl::showpage('store_theme_edit');
	}
	
	public function pic_listOp(){
		Tpl::setLayout('null_layout');
		
		require_once(BasePath.DS.'framework'.DS.'libraries'.DS.'page.php');
		$page	= new Page();
		$page->setEachNum(8);
		$page->setStyle('admin');
		
		
		$model_album = Model('album');
		
		$param = array();
		$param['album_pic.store_id']	= $_SESSION['store_id'];
		if(intval($_GET['id']) > 1){
			$param['aclass_id']	= intval($_GET['id']);
			Tpl::output('aclass_id',$aclass_id);
		}
		$pic_list = $model_album->getPicList($param,$page);
		Tpl::output('pic_list',$pic_list);
		Tpl::output('show_page',$page->show());
		
		$param = array();
		$param['album_aclass.store_id']	= $_SESSION['store_id'];
		$class_info			= $model_album->getClassList($param);
		Tpl::output('class_list',$class_info);
		Tpl::showpage('store_theme_pic_list');
	}
	
	public function goods_listOp(){
		Tpl::setLayout('null_layout');
		$gc	= Model('goods_class');
		$gc_list	= $gc->getTreeClassList(3,array('gc_show'=>1,'order'=>'gc_parent_id asc,gc_sort asc,gc_id asc'));
		foreach($gc_list as $k=>$gc){
			$gc_list[$k]['gc_name']	= '';
			$gc_list[$k]['gc_name']	= str_repeat("&nbsp;",$gc['deep']*2).$gc['gc_name'];
		}
		Tpl::output('gc_list',$gc_list);
	
		require_once(BasePath.DS.'framework'.DS.'libraries'.DS.'page.php');
		$page	= new Page();
		$page->setEachNum(8);
		$page->setStyle('admin');
		
		$condition	= array();
		if(intval($_GET['gc_id']) > 1){
			$condition['gc_id']	= intval($_GET['gc_id']);
		}
		$condition['store_id']		= $_SESSION['store_id'];
		$condition['goods_show']	= '1';
		$condition['goods_state']	= '0';
		$condition['goods_store_state']	= 'open';
		$goods	= Model('goods');
		$goods_list	= $goods->getGoods($condition,$page,'*');
		Tpl::output('goods_list',$goods_list);
		Tpl::output('show_page',$page->show());
		Tpl::showpage('store_theme_goods_list');
	}
	
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'store_theme':
				$menu_array = array(
				1=>array('menu_key'=>'store_theme','menu_name'=>Language::get('nc_member_path_valid_theme'),'menu_url'=>'index.php?act=store&op=theme')
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
