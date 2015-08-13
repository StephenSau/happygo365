<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_snsControl extends BaseMemberStoreControl{
	public function __construct(){
		parent::__construct();
		Language::read('store_sns,member_sns');
	}
	
	public function indexOp(){
		$this->addOp();
	}
	
	
	public function addOp(){
		
		$model = Model('goods');
	
		$where = array(
				'store_id'=>$_SESSION['store_id'],
				'goods_show'=>1,
				'salenum'=>array('egt',10)
			);
		$hotsell_list	= $model->field('goods_id,goods_name,goods_image,goods_store_price,salenum,store_id')
							->where($where)			
							->order('salenum desc')->limit(9)->select();
		Tpl::output('hotsell_list', $hotsell_list);
		
		
		$where = array(
				'store_id'=>$_SESSION['store_id'],
				'goods_show'=>1,
				'goods_add_time'=>array('gt',time()-60*60*24)
			);
		$new_list		= $model->field('goods_id,goods_name,goods_image,goods_store_price,store_id')
							->where($where)			
							->order('goods_id desc')->limit(9)->select();
		Tpl::output('new_list', $new_list);
		
		$this->profile_menu('store_sns_add');
		Tpl::showpage('store_sns_add');
	}
	
	
	public function store_sns_saveOp(){
		
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
				array("input"=>$_POST["content"],"require"=>"true","validator"=>"Length","max"=>140,"min"=>1,"message"=>Language::get('store_sns_center_error')),
				array("input"=>$_POST["goods_url"],"require"=>"false","validator"=>"url","message"=>Language::get('store_goods_index_goods_price_null')),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			showDialog($error);
		}
		$model = Model();
		
		
		$goodsdata	= '';
		$content	= '';
		$_POST['type'] = intval($_POST['type']);
		switch ($_POST['type']){
			case '2':
				$sns_image	= trim($_POST['sns_image']);
				if($sns_image != '') $content	= '<div class="fd-media">
									<div class="thumb-image"><a href="javascript:void(0);" nc_type="thumb-image"><img src="'.$sns_image.'" /><i></i></a></div>
									<div class="origin-image"><a href="javascript:void(0);" nc_type="origin-image"></a></div>
								</div>';
				break;
			case '9':
				$data = $this->getGoodsByUrl(html_entity_decode($_POST['goods_url']));
				if( CHARSET == 'GBK') {
					foreach ((array)$data as $k=>$v){
						$data[$k] = Language::getUTF8($v);
					}
				}
				$goodsdata	= addslashes(json_encode($data));
				break;
			case '10':
				if(is_array($_POST['goods_id'])){
					$goods_id_array = $_POST['goods_id'];
				}else{
					showDialog(Language::get('store_sns_choose_goods'));
				}
				$goods_array = $model->table('goods')->field("goods_id,store_id,goods_name,goods_image,goods_store_price,py_price")->where(array('store_id'=>$_SESSION['store_id'],'goods_id'=>array('in',$goods_id_array)))->select();
				if(!empty($goods_array) && is_array($goods_array)){
					$goodsdata	= array();
					foreach ($goods_array as $val){
						if( CHARSET == 'GBK') {
							foreach ((array)$val as $k=>$v){
								$val[$k] = Language::getUTF8($v);
							}
						}
						$goodsdata[]	= addslashes(json_encode($val));
					}
				}
				break;
			case '3':
				if(is_array($_POST['goods_id'])){
					$goods_id_array = $_POST['goods_id'];
				}else{
					showDialog(Language::get('store_sns_choose_goods'));
				}
				$goods_array = $model->table('goods')->field("goods_id,store_id,goods_name,goods_image,goods_store_price,py_price")->where(array('store_id'=>$_SESSION['store_id'],'goods_id'=>array('in',$goods_id_array)))->select();
				if(!empty($goods_array) && is_array($goods_array)){
					$goodsdata	= array();
					foreach($goods_array as $val){
						if( CHARSET == 'GBK') {
							foreach ((array)$val as $k=>$v){
								$val[$k] = Language::getUTF8($v);
							}
						}
						$goodsdata[]	= addslashes(json_encode($val));
					}
				}
				break;
			default:
				showDialog(Language::get('miss_argument'));
		}
		
		$store_info		= $model->table('store')->find($_SESSION['store_id']);
		
		$stracelog_array = array();
		$stracelog_array['strace_storeid']	= $store_info['store_id'];
		$stracelog_array['strace_storename']= $store_info['store_name'];
		$stracelog_array['strace_storelogo']= empty($store_info['store_logo'])?'':$store_info['store_logo'];
		$stracelog_array['strace_title']	= $_POST['content'];
		$stracelog_array['strace_content']	= $content;
		$stracelog_array['strace_time']		= time();
		$stracelog_array['strace_type']		= $_POST['type'];
		if(isset($goodsdata) && is_array($goodsdata)){
			$stracelog	= array();
			foreach($goodsdata as $val){
				$stracelog_array['strace_goodsdata']	= $val;
				$stracelog[]	= $stracelog_array;
			}
			$rs	= $model->table('sns_s_tracelog')->insertAll($stracelog);
		}else{
			$stracelog_array['strace_goodsdata']	= $goodsdata;
			$rs	= $model->table('sns_s_tracelog')->insert($stracelog_array);
		}
		if($rs){
			showDialog(Language::get('nc_common_op_succ'), 'index.php?act=store_sns', 'succ');
		}else{
			showDialog(Language::get('nc_common_op_fail'));
		}
	}

	public function settingOp(){
		$model = Model();
		if(chksubmit()){
			$update = array();
			$update['sauto_storeid']		= $_SESSION['store_id'];
			$update['sauto_new']			= isset($_POST['new'])?1:0;
			$update['sauto_newtitle']		= trim($_POST['new_title']);
			$update['sauto_coupon']			= isset($_POST['coupon'])?1:0;
			$update['sauto_coupontitle']	= trim($_POST['coupon_title']);
			$update['sauto_xianshi']		= isset($_POST['xianshi'])?1:0;
			$update['sauto_xianshititle']	= trim($_POST['xianshi_title']);
			$update['sauto_mansong']		= isset($_POST['mansong'])?1:0;
			$update['sauto_mansongtitle']	= trim($_POST['mansong_title']);
			$update['sauto_bundling']		= isset($_POST['bundling'])?1:0;
			$update['sauto_bundlingtitle']	= trim($_POST['bundling_title']);
			$update['sauto_groupbuy']		= isset($_POST['groupbuy'])?1:0;
			$updata['sauto_groupbuytitle']	= trim($_POST['groupbuy_title']);
			$result = $model->table('sns_s_autosetting')->insert($update,true);
			showDialog(Language::get('nc_common_save_succ'), '', 'succ');
		}
		$sauto_info	= $model->table('sns_s_autosetting')->find($_SESSION['store_id']);
		Tpl::output('sauto_info', $sauto_info);
		$this->profile_menu('store_sns_setting');
		Tpl::showpage('store_sns_setting');
	}
	
	
	private function profile_menu($menu_key) {
		$menu_array	= array(
				1=>array('menu_key'=>'store_sns_add', 'menu_name'=>Language::get('store_sns_add'), 'menu_url'=>'index.php?act=store_sns&op=add'),
				2=>array('menu_key'=>'store_sns_setting', 'menu_name'=>Language::get('store_sns_setting'), 'menu_url'=>'index.php?act=store_sns&op=setting'),
				3=>array('menu_key'=>'store_sns_brower', 'menu_name'=>Language::get('store_sns_browse'), 'menu_url'=>'index.php?act=store_snshome&sid='.$_SESSION['store_id'], 'target'=>'_blank')
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
		Tpl::output('menu_sign','store_sns');
		Tpl::output('menu_sign_url','index.php?act=store_sns');
		Tpl::output('menu_sign1',$menu_key);
	}
	
	
	private function getGoodsByUrl($url){
		if($url == '' || preg_match("/^http:(\\/){2}[A-Za-z0-9]+.[A-Za-z0-9]+[\\/=?%-&_~`@\\[\\]':+!]*([^<>\"\"])*$/", $url) == 0){
			showDialog(Language::get('store_sns_goods_url_error'));
		}
		$url_array = get_headers($url);
		$rs	= strpos($url_array['0'], '404');
		if($rs === false){
			$array = explode('/', $url);
			$array = end($array);
			if(C('rewrite_enabled') == 1){
				$id = preg_replace(array('/goods-(\d+)-(\d+)\.html/i','/goods-(\d+)\.html/i'), '$1', $array);
			}else{
				parse_str($array['query'],$arr);
				$id = $arr['goods_id'];
			}
			if(intval($id) > 0){
				$result = Model('goods')->field('goods_id,store_id,goods_name,goods_image,goods_store_price,py_price')->find($id);
				if(!empty($result) && is_array($result)){
					return $result;
				}else{
					showDialog(Language::get('store_sns_goods_url_error'));
				}
			}else{
				showDialog(Language::get('store_sns_goods_url_error'));
			}
		}else{
			showDialog(Language::get('store_sns_goods_url_error'));
		}
	}
}