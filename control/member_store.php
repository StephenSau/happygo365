<?php

defined('haipinlegou') or exit('Access Invalid!');

class member_storeControl extends BaseMemberControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_store_index');
	}
	public function indexOp(){
		$this->createOp();
	}
	//S脚部内容显示
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
		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';
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
    //E脚部内容显示
	
	public function createOp() {
		Language::read('home_layout');
		if($GLOBALS['setting_config']['store_allow'] == '0') {
			showMessage(Language::get('store_create_right_closed'),'index.php?act=member_snsindex','html','error');
		}
		if($_SESSION['store_id'] != '') {
			showMessage(Language::get('store_create_created'),'index.php?act=member_snsindex','html','error');
		}

		Tpl::setLayout('member_goods_marketing_layout');
		
		$model_grade	= Model('store_grade');

		if(intval($_GET['grade_id']) != 0) {
			$store_grade = ($setting = F('store_grade')) ? $setting : H('store_grade',true,'file');
			$store_grade = $store_grade[intval($_GET['grade_id'])];			
			if(empty($store_grade)) {
				showMessage(Language::get('store_create_grade_not_exists'),'','html','error');
			}
		
			$model_store	= Model('store_class');
			$parent_list = $model_store->getTreeClassList(2);
			if (!empty($parent_list) && is_array($parent_list)){
				foreach ($parent_list as $k => $v){
					$parent_list[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
				}
			}
			//S脚部文章输出
			$list = $this->_article();
			//E脚部文章输出
			
			Tpl::output('parent_list',$parent_list);
			Tpl::showpage('seller_step2');
		}

		$grade_list = ($setting = F('store_grade')) ? $setting : H('store_grade',true,'file');
		if(!empty($grade_list) && is_array($grade_list)){
			foreach($grade_list as $key=>$grade){
				$sg_function = explode('|',$grade['sg_function']);
				if (!empty($sg_function[0]) && is_array($sg_function)){
					foreach ($sg_function as $key1=>$value){
						if ($value == 'editor_multimedia'){
							$grade_list[$key]['function_str'] .= Language::get('store_create_store_editor_multimedia').'   ';
						}
					}
				}else {
					$grade_list[$key]['function_str'] = Language::get('store_create_store_null');
				}
			}
		}
		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出
		Tpl::output('grade_list',$grade_list);

		Tpl::showpage('seller_step1');
	}
	
	public function saveOp() {
		if($_SESSION['store_id'] != '') {
			showDialog(Language::get('store_create_created'),'index.php?act=member_snsindex','error');
		}
		
		$store_grade = ($setting = F('store_grade')) ? $setting : H('store_grade',true,'file');
		$store_grade = $store_grade[intval($_POST['grade_id'])];
		if(empty($store_grade)) {
			showDialog(Language::get('store_create_grade_not_exists'),'','error');
		}
		
		$model_store	= Model('store');
		
		if (chksubmit()){
			$_GET['store_name'] = $_POST["store_name"];
			if(!$this->checknameinner()){
				showDialog(Language::get('store_create_store_name_exists'),'','error');
			}
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["store_name"],"require"=>"true","message"=>Language::get('store_save_store_name_null')),
			array("input"=>$_POST["sc_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('store_save_store_class_null')),
			array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('store_save_area_null')),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showDialog(Language::get('error').$error,'','error');
			}
			$shop_array		= array();
			$shop_array['grade_id']		= $_POST['grade_id'];
			$shop_array['store_owner_card']= $_POST['store_owner_card'];
			$shop_array['store_name']	= $_POST['store_name'];
			$shop_array['sc_id']		= $_POST['sc_id'];
			$shop_array['area_id']		= $_POST['area_id'];
			$shop_array['area_info']	= $_POST['area_info'];
			$shop_array['store_address']= $_POST['store_address'];
			$shop_array['store_zip']	= $_POST['store_zip'];
			$shop_array['store_tel']	= $_POST['store_tel'];
			$shop_array['store_zy']		= $_POST['store_zy'];
			$shop_array['store_state']	= ($store_grade['sg_confirm'] == 1 ? 2 : 1 );

			$upload = new UploadFile();
			$upload->set('default_dir',ATTACH_AUTH);

			if($_FILES['image']['name'] != '') {
				$result = $upload->upfile('image');
				if ($result){
					$shop_array['store_image'] 	= $upload->file_name;
					$shop_array['name_auth']	= '2';
                    $upload->file_name = '';
				}else {
					showdialog($upload->error,'','error');
				}
			}
			if($_FILES['image1']['name'] != '') {
				$result1 = $upload->upfile('image1');
				if ($result1){
					$shop_array['store_image1'] = $upload->file_name;
					$shop_array['store_auth']	= '2';
				}else {
					showdialog($upload->error,'','error');
				}
			}
			$state	= $model_store->createStore($shop_array);
			if($state) {
				$_SESSION['is_seller'] = 1;
				$_SESSION['store_id'] = $state;
                $_SESSION['store_name'] = $shop_array['store_name'];
                $_SESSION['grade_id'] = $shop_array['grade_id'];
                
                require_once(BasePath.DS.'resource'.DS.'phpqrcode'.DS.'index.php');
                $PhpQRCode	= new PhpQRCode();
                $PhpQRCode->set('date',SiteUrl.DS.ncUrl(array('act'=>'show_store','id'=>$state), 'store'));
                $PhpQRCode->set('pngTempDir',ATTACH_STORE.DS);
                $model_store->storeUpdate(array('store_code'=>$PhpQRCode->init(),'store_id'=>$state));
                
				$album_model = Model('album');
				$album_arr = array();
				$album_arr['aclass_name'] = Language::get('store_save_defaultalbumclass_name');
				$album_arr['store_id'] = $state;
				$album_arr['aclass_des'] = '';
				$album_arr['aclass_sort'] = '255';
				$album_arr['aclass_cover'] = '';
				$album_arr['upload_time'] = time();
				$album_arr['is_default'] = '1';
				$album_model->addClass($album_arr);
	
				$model = Model();
				$model->table('store_extend')->insert(array('store_id'=>$state));
				$msg = Language::get('store_save_create_success').($store_grade['sg_confirm'] == 1 ? Language::get('store_save_waiting_for_review') : ''); 
				showDialog($msg,'index.php?act=store','succ');
			} else {
				showDialog(Language::get('store_save_create_fail'),'','error');
			}
		}
	}
	
	public function checknameOp() {
		if(!$this->checknameinner()) {
			echo 'false';
		} else {
			echo 'true';
		}
	}
	
	public function checknameinner() {
	
		$model_store	= Model('store');

		$store_name	= trim($_GET['store_name']);
		$store_info	= $model_store->shopStore(array('store_name'=>$store_name));
		if($store_info['store_name'] != ''&&$store_info['member_id'] != $_SESSION['member_id']) {			
			return false;
		} else {			
			return true;
		}
	}
	
	public function update_gradeOp() {
		Language::read('home_layout');
		
		
		if(!$_SESSION['store_id']) {
			showMessage(Language::get('store_create_created'),'index.php?act=member_snsindex','html','error');
		}

		Tpl::setLayout('member_goods_marketing_layout');		
		
		$model_store_gradelog = Model('store_gradelog');
		$gradelog = $model_store_gradelog->getLogInfo(array('gl_shopid'=>$_SESSION['store_id'],'gl_allowstate'=>'0','order'=>' gl_id desc '));
		if (is_array($gradelog) && count($gradelog)>0){
			showMessage(Language::get('store_upgrade_exist_error'),'index.php?act=store&op=store_setting','html','error');
		}
		$grade_id	= intval($_GET['grade_id']);
	
		$model_grade	= Model('store_grade');
		$model_store	= Model('store');
		
		
		$store_info	= $model_grade->getGradeShopList(array('store_id'=>$_SESSION['store_id']));
		
		if (!is_array($store_info) || count($store_info)<=0 ){
			showMessage(Language::get('store_upgrade_store_error'),'index.php?act=member_snsindex','html','error');
		}
		
		if($grade_id != 0) {
			$store_grade = ($setting = F('store_grade')) ? $setting : H('store_grade',true,'file');
			$store_grade = $store_grade[intval($_GET['grade_id'])];
			if(empty($store_grade)) {
				showMessage(Language::get('store_create_grade_not_exists'),'','html','error');
				exit();
			}
			if ($store_grade['sg_sort'] <= $store_info[0]['sg_sort']){
				showMessage(Language::get('store_upgrade_gradesort_error'),'','html','error');
				exit();
			}
	
			$gl_insertarr = array();
			$gl_insertarr['gl_shopid'] = $_SESSION['store_id'];
			$gl_insertarr['gl_shopname'] = $_SESSION['store_name'];
			$gl_insertarr['gl_memberid'] = $_SESSION['member_id'];
			$gl_insertarr['gl_membername'] = $_SESSION['member_name'];
			$gl_insertarr['gl_sgid'] = $store_grade['sg_id'];
			$gl_insertarr['gl_sgname'] = $store_grade['sg_name'];
			$gl_insertarr['gl_sgconfirm'] = $store_grade['sg_confirm'];
			$gl_insertarr['gl_sgsort'] = $store_grade['sg_sort'];
			$gl_insertarr['gl_addtime'] = time();
			$gl_insertarr['gl_allowstate'] = $store_grade['sg_confirm'] == 1? 0 : 1;
			$gl_insertarr['gl_allowadminid'] = 0;
			$gl_insertarr['gl_allowadminname'] = $store_grade['sg_confirm'] == 1? '' : 'system';
			$model_gradelog = Model('store_gradelog');
			$log_result = $model_gradelog->addLog($gl_insertarr);
			if ($log_result){
				$update_state = true;
				if ($store_grade['sg_confirm'] == '0'){
					$array	= array();
					$array['grade_id']		= $store_grade['sg_id'];
					$array['store_id']		= $_SESSION['store_id'];
					$update_state	= $model_store->storeUpdate($array);
				}
				if ($update_state) {
					showMessage(Language::get('store_upgrade_submit'),'index.php?act=store&op=store_setting');
				}else {
					showMessage(Language::get('store_upgrade_submit_fail'),'index.php?act=store&op=update_grade','html','error');
				}
			}else {
					showMessage(Language::get('store_upgrade_submit_fail'),'index.php?act=store&op=update_grade','html','error');
			}
			exit();
		}
		$grade_list	= $model_grade->getGradeList(array('order'=>' sg_sort '));
		if(!empty($grade_list) && is_array($grade_list)){
			foreach($grade_list as $key=>$grade){
				$sg_function = explode('|',$grade['sg_function']);
				if (!empty($sg_function[0]) && is_array($sg_function)){
					foreach ($sg_function as $key1=>$value){
						if ($value == 'editor_multimedia'){
							$grade_list[$key]['function_str'] .= Language::get('store_create_store_editor_multimedia').'   ';
						}elseif ($value == 'groupbuy'){
							$grade_list[$key]['function_str'] .= Language::get('store_create_store_groupbuy').'   ';
						}
					}
				}else {
					$grade_list[$key]['function_str'] = Language::get('store_create_store_null');
				}
			}
		}
		Tpl::output('grade_list',$grade_list);
	
		Tpl::output('store_info',$store_info[0]);
		Tpl::showpage('store_update_grade');
	}
}
