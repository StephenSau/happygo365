<?php

defined('haipinlegou') or exit('Access Invalid!');

class store_albumControl extends BaseMemberStoreControl {

	public function indexOp(){

		$this->album_cateOp();

		exit;

	}

	public function __construct() {

		parent::__construct();

		

		Language::read('member_store_album');

	}

	

	public function album_cateOp(){

		

		$model_album = Model('album');



		

		$return = $model_album->checkAlbum(array('album_aclass.store_id'=>$_SESSION['store_id'],'is_default'=>'1'));

		if(!$return){

			$album_arr = array();

			$album_arr['aclass_name'] = Language::get('album_default_album');

			$album_arr['store_id'] = $_SESSION['store_id'];

			$album_arr['aclass_des'] = '';

			$album_arr['aclass_sort'] = '255';

			$album_arr['aclass_cover'] = '';

			$album_arr['upload_time'] = time();

			$album_arr['is_default'] = '1';

			$model_album->addClass($album_arr);

		}



		$param = array();

		$param['album_aclass.store_id']	= $_SESSION['store_id'];

		$param['order']					= 'aclass_sort desc';

		if($_GET['sort'] != ''){

			switch ($_GET['sort']){

				case '0':

					$param['order']		= 'upload_time desc';

					break;

				case '1':

					$param['order']		= 'upload_time asc';

					break;

				case '2':

					$param['order']		= 'aclass_name desc';

					break;

				case '3':

					$param['order']		= 'aclass_name asc';

					break;

				case '4':

					$param['order']		= 'aclass_sort desc';

					break;

				case '5':

					$param['order']		= 'aclass_sort asc';

					break;

			}

		}

		$aclass_info = $model_album->getClassList($param,$page);

		Tpl::output('aclass_info',$aclass_info);


        //S脚部文章输出
		$list = $this->_article();
        //E脚部文章输出


		Tpl::output('PHPSESSID',session_id());

		self::profile_menu('album','album');

		Tpl::output('menu_sign','album');

		Tpl::output('menu_sign_url','index.php?act=store_album&op=album_cate');

		Tpl::output('menu_sign1','my_album');

		Tpl::showpage('store_album_list');

	}

	//S脚部文章输出
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
	//E脚部文章输出

	

	public function album_addOp(){


		$model_album = Model('album');

		$class_count = $model_album->countClass($_SESSION['store_id']);

		Tpl::output('class_count',$class_count['count']);

		Tpl::showpage('store_album_class_add','null_layout');

	}

	

	public function album_add_saveOp(){

		if (chksubmit()){

			$model_album = Model('album');

			$class_count = $model_album->countClass($_SESSION['store_id']);
			
			/*
			if($class_count['count'] >= 20){

				showMessage(Language::get('album_class_save_max_20'),'','html','error');

			}
			*/
			$param = array();

			$param['aclass_name']	= $_POST['name'];

			$param['store_id']		= $_SESSION['store_id'];

			$param['aclass_des']	= $_POST['description'];

			$param['aclass_sort']	= $_POST['sort'];

			$param['upload_time']	= time();



			$return = $model_album->addClass($param);

			if($return){

				showDialog(Language::get('album_class_save_succeed'),'index.php?act=store_album','succ',empty($_GET['inajax'])?'':'CUR_DIALOG.close();');

			}

		}

		showDialog(Language::get('album_class_save_lose'));

	}

	

	public function album_editOp(){

		if(empty($_GET['id'])){

			echo Language::get('album_parameter_error');exit;

		}

		

		$model_album = Model('album');

		$param = array();

		$param['field']		= array('aclass_id','store_id');

		$param['value']		= array(intval($_GET['id']),$_SESSION['store_id']);

		$class_info = $model_album->getOneClass($param);

		Tpl::output('class_info',$class_info);

		

		Tpl::showpage('store_album_class_edit','null_layout');

	}

	

	public function album_edit_saveOp(){

		$param = array();

		$param['aclass_name']	= $_POST['name'];

		$param['aclass_des']	= $_POST['description'];

		$param['aclass_sort']	= $_POST['sort'];

		

		

		

		$model_album = Model('album');

		

		$return	= $model_album->checkAlbum(array('album_aclass.store_id'=>$_SESSION['store_id'],'album_aclass.aclass_id'=>intval($_POST['id'])));

		if($return){

			

			$re = $model_album->updateClass($param,intval($_POST['id']));

			if($re){

				showDialog(Language::get('album_class_edit_succeed'),'index.php?act=store_album','succ',empty($_GET['inajax'])?'':'CUR_DIALOG.close();');

			}

		}else{

			showDialog(Language::get('album_class_edit_lose'));

		}

	}

	

	public function album_delOp(){

		if(empty($_GET['id'])){	

			showMessage(Language::get('album_parameter_error'),'','html','error');

		}

		

		$model_album = Model('album');

		

		

		$return = $model_album->checkAlbum(array('album_aclass.store_id'=>$_SESSION['store_id'],'album_aclass.aclass_id'=>intval($_GET['id']),'is_default'=>'0'));

		if(!$return){

			showDialog(Language::get('album_class_file_del_lose'));

		}

		

		$return = $model_album->delClass(intval($_GET['id']));

		if(!$return){

			showDialog(Language::get('album_class_file_del_lose'));

		}

		

		$param = array();

		$param['field']		= array('is_default','store_id');

		$param['value']		= array('1',$_SESSION['store_id']);

		$class_info = $model_album->getOneClass($param);

		$param = array();

		$param['aclass_id'] = $class_info['aclass_id'];

		$return = $model_album->updatePic($param,array('aclass_id'=>intval($_GET['id'])));

		if($return){

			showDialog(Language::get('album_class_file_del_succeed'),'index.php?act=store_album','succ');

		}else{

			showDialog(Language::get('album_class_file_del_lose'));

		}

	}

	

	public function album_pic_listOp(){

		if(empty($_GET['id'])) {

			showMessage(Language::get('album_parameter_error'),'','html','error');

		}



		

		$page	= new Page();

		$page->setEachNum(12);

		$page->setStyle('admin');

		

	

		$model_album = Model('album');

		

		$param = array();

		$param['aclass_id']	= intval($_GET['id']);

		$param['album_pic.store_id']	= $_SESSION['store_id'];

		if($_GET['sort'] != ''){

			switch ($_GET['sort']){

				case '0':

					$param['order']		= 'upload_time desc';

					break;

				case '1':

					$param['order']		= 'upload_time asc';

					break;

				case '2':

					$param['order']		= 'apic_size desc';

					break;

				case '3':

					$param['order']		= 'apic_size asc';

					break;

				case '4':

					$param['order']		= 'apic_name desc';

					break;

				case '5':

					$param['order']		= 'apic_name asc';

					break;

			}

		}

		$pic_list = $model_album->getPicList($param,$page);
		


		Tpl::output('pic_list',$pic_list);

		Tpl::output('show_page',$page->show());

		

		

		$param = array();

		$param['album_class.un_aclass_id']	= intval($_GET['id']);

		$param['album_aclass.store_id']	= $_SESSION['store_id'];

		$class_list = $model_album->getClassList($param);


		Tpl::output('class_list',$class_list);

		

		$param = array();

		$param['field']		= array('aclass_id','store_id');

		$param['value']		= array(intval($_GET['id']),$_SESSION['store_id']);

		$class_info			= $model_album->getOneClass($param);

		Tpl::output('class_info',$class_info);

		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出

		Tpl::output('PHPSESSID',session_id());

		Tpl::output('menu_sign','album');

		Tpl::output('menu_sign_url','index.php?act=store_album&op=album_cate');

		Tpl::output('menu_sign1','album_pic_list');

		self::profile_menu('album_pic','pic_list');

		Tpl::showpage('store_album_pic_list');

	}

	

	public function pic_listOp(){



		

		$page	= new Page();

        if(in_array($_GET['item'] , array('groupbuy', 'bundling_add', 'bundling_add_desc', 'store_sns_normal'))) {

            $page->setEachNum(12);

        } else {

            $page->setEachNum(16);

        }

		$page->setStyle('admin');

		

		

		$model_album = Model('album');

		

		$param = array();

		$param['album_pic.store_id']	= $_SESSION['store_id'];

		if(!empty($_GET) && $_GET['id'] != '0'){

			$param['aclass_id']	= intval($_GET['id']);

		

			$cparam = array();

			$cparam['field']		= array('aclass_id','store_id');

			$cparam['value']		= array(intval($_GET['id']),$_SESSION['store_id']);

			$cinfo			= $model_album->getOneClass($cparam);

			Tpl::output('class_name',$cinfo['aclass_name']);

		}

		$pic_list = $model_album->getPicList($param,$page);

		Tpl::output('pic_list',$pic_list);

		Tpl::output('show_page',$page->show());

		

		$param = array();

		$param['album_aclass.store_id']	= $_SESSION['store_id'];

		$class_info			= $model_album->getClassList($param);

		Tpl::output('class_list',$class_info);

		

        switch($_GET['item']) {

        case 'goods':

			Tpl::showpage('store_album_sample','null_layout');

            break;

        case 'des':

			Tpl::showpage('store_album_sample_des','null_layout');

            break;

        case 'groupbuy':

			Tpl::showpage('groupbuy_store_album','null_layout');

            break;

        case 'bundling_add':

        	Tpl::showpage('store_promotion_bundling.album', 'null_layout');

        	break;

        case 'bundling_add_desc':

        	Tpl::showpage('store_promotion_bundling.album_des', 'null_layout');

        	break;

        case 'store_sns_normal':

        	Tpl::showpage('store_sns_add.album', 'null_layout');

        	break;

        default:

			Tpl::showpage('store_album_sample','null_layout');

        }

	}

	

	public function change_album_coverOp(){

		if(empty($_GET['id'])) {

			showDialog(Language::get('nc_common_op_fail'));

		}

		

		$model_album = Model('album');

		

		$param = array();

		$param['field']		= array('apic_id','store_id');

		$param['value']		= array(intval($_GET['id']),$_SESSION['store_id']);

		$pic_info			= $model_album->getOnePicById($param);

		$return = $model_album->checkAlbum(array('album_aclass.store_id'=>$_SESSION['store_id'],'album_aclass.aclass_id'=>$pic_info['aclass_id']));

		if($return){

			$re = $model_album->updateClass(array('aclass_cover'=>$pic_info['apic_cover'].'_small.'.get_image_type($pic_info['apic_cover'])),$pic_info['aclass_id']);

			if($re){

				showDialog(Language::get('nc_common_op_succ'),'reload','succ');

			}

		}else{

			showDialog(Language::get('nc_common_op_fail'));

		}

	}

	

	public function change_pic_nameOp(){

		if(empty($_POST['id']) && empty($_POST['name'])){

			echo 'false';

		}

		

		$model_album = Model('album');

		

		

		if(strtoupper(CHARSET) == 'GBK'){

			$_POST['name'] = Language::getGBK($_POST['name']);

		}

		$return = $model_album->updatePic(array('apic_name'=>$_POST['name']),array('apic_id'=>intval($_POST['id'])));

		if($return){

			echo 'true';

		}else{

			echo 'false';

		}

	}



	public function album_pic_infoOp(){

		if(empty($_GET['class_id']) && empty($_GET['id'])){

			showMessage(Language::get('album_parameter_error'),'','html','error');

		}

		

		$model_album = Model('album');

		

		

		$return = $model_album->checkAlbum(array('album_pic.store_id'=>$_SESSION['store_id'],'album_pic.apic_id'=>intval($_GET['id'])));

		if(!$return){

			showMessage(Language::get('album_parameter_error'),'','html','error');

		}

		

		

		$param = array();

		$param['aclass_id']			= intval($_GET['class_id']);

		$param['store_id']			= $_SESSION['store_id'];

		$pic_list					= $model_album->getPicList($param);

		Tpl::output('pic_list',$pic_list);

		

		

		$param['gt_apic_id']		= intval($_GET['id']);

		$pic_num					= $model_album->getPicList($param,'','count(*) as count');

		Tpl::output('pic_num',$pic_num['0']['count']);

		

		

		$param = array();

		$param['field']		= array('aclass_id','store_id');

		$param['value']		= array(intval($_GET['class_id']),$_SESSION['store_id']);

		$class_info			= $model_album->getOneClass($param);

		Tpl::output('class_info',$class_info);

		

		

		$param = array();

		$param['field']		= array('apic_id','store_id');

		$param['value']		= array(intval($_GET['id']),$_SESSION['store_id']);

		$pic_info			= $model_album->getOnePicById($param);

		$pic_info['apic_size'] = sprintf('%.2f',intval($pic_info['apic_size'])/1024);

		Tpl::output('pic_info',$pic_info);

		

		

		list($width, $height, $type, $attr) = @getimagesize(BasePath.DS.ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$pic_info['apic_cover'].'_small.'.get_image_type($pic_info['apic_cover']));

		Tpl::output('small_spec',$width.'x'.$height);

		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出

		Tpl::output('img_type',get_image_type($pic_info['apic_cover']));

		Tpl::output('id',intval($_GET['id']));

		Tpl::output('menu_sign','album');

		Tpl::output('menu_sign_url','index.php?act=store_album&op=album_cate');

		Tpl::output('menu_sign1','album_pic_info');

		self::profile_menu('album_pic_info','pic_info');

		Tpl::showpage('store_album_pic_info');

	}

	

	public function album_pic_delOp(){

		if(empty($_GET['id'])) {

			showDialog(Language::get('album_parameter_error'));

		}

		

		$model_album = Model('album');

		if(!empty($_GET['id']) && is_array($_GET['id'])){

			$id = "'".implode("','", $_GET['id'])."'";

		}else{

			$id = "'".intval($_GET['id'])."'";

		}

		

		$return = $model_album->checkAlbum(array('album_pic.store_id'=>$_SESSION['store_id'],'in_apic_id'=>$id));

		if(!$return){

			showDialog(Language::get('album_class_pic_del_lose'));

		}

	

		$return = $model_album->delPic($id);

		if($return){

		

			showDialog(Language::get('album_class_pic_del_succeed'),'reload','succ');

		}else{

			

			showDialog(Language::get('album_class_pic_del_lose'));

		}

	}

	

	public function album_pic_moveOp(){

		

		$model_album = Model('album');

		if(chksubmit()){

			if(empty($_REQUEST['id'])){

				showDialog(Language::get('album_parameter_error'));

			}

			if(!empty($_REQUEST['id']) && is_array($_REQUEST['id'])){

				$_REQUEST['id'] = trim(implode("','", $_REQUEST['id']),',');

			}

			

				$param = array();

				$param['in_apic_id'] = "'".$_REQUEST['id']."'";

				$list_pic = $model_album->getClassList($param);

				$class_cover = $list_pic['0']['aclass_cover'];

				$class_id	 = $list_pic['0']['aclass_id'];

				unset($list_pic);

				if($class_cover != ''){

					$list_pic = $model_album->getPicList($param);

					foreach ($list_pic as $val){

						if($val['apic_cover'].'_small.'.get_image_type($val['apic_cover']) == $class_cover){

							$model_album->updateClass(array('aclass_cover'=>''),$class_id);

							break;

						}

					}

				}



			$param = array();

			$param['aclass_id'] = $_REQUEST['cid'];

			$return = $model_album->updatePic($param,array('in_apic_id'=>"'".$_REQUEST['id']."'"));

			if($return){

				showDialog(Language::get('album_class_pic_move_succeed'),'reload','succ',empty($_GET['inajax'])?'':'CUR_DIALOG.close();');

			}else{

				showDialog(Language::get('album_class_pic_move_lose'));

			}

		}

		$param = array();

		$param['album_class.un_aclass_id']	= $_GET['cid'];

		$param['album_aclass.store_id']	= $_SESSION['store_id'];

		$class_list = $model_album->getClassList($param);



		if(isset($_GET['id']) && !empty($_GET['id'])){

			Tpl::output('id',$_GET['id']);

		}

		Tpl::output('class_list',$class_list);

		Tpl::showpage('store_album_move','null_layout');

	}

	

	public function replace_image_uploadOp() {

		$lang	= Language::getLangContent();

		Tpl::output('id',		intval($_GET['id']));

		if(chksubmit()){

			

			$model_album = Model('album');

			$param = array();

			$param['field']		= array('apic_id','store_id');

			$param['value']		= array(intval($_POST['id']),$_SESSION['store_id']);

			$apic_info = $model_album->getOnePicById($param);

			if(substr(strrchr($apic_info['apic_cover'],"."),1) != substr(strrchr($_FILES["file"]["name"],"."),1)){

				echo "<script type='text/javascript'>window.parent.img_replace_error('". Language::get('album_replace_same_type') ."');</script>";

				Tpl::showpage('replace_image','null_layout');

				die;

			}

			$pic_cover	= implode(DS, explode(DS, $apic_info['apic_cover'], -1));	

			$tmpvar = explode(DS, $apic_info['apic_cover']);

			$pic_name	= array_pop($tmpvar);			

			

			$upload = new UploadFile();

			$upload->set('default_dir',ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$pic_cover);

			$upload->set('max_size',C('image_max_filesize'));

			$thumb_width	= C('thumb_small_width').','.C('thumb_mid_width').','.C('thumb_max_width').','.C('thumb_tiny_width').',240';

			$thumb_height	= C('thumb_small_height').','.C('thumb_mid_height').','.C('thumb_max_height').','.C('thumb_tiny_height').',1024';



			$upload->set('thumb_width',	$thumb_width);

			$upload->set('thumb_height',$thumb_height);

			$upload->set('thumb_ext',	'_small,_mid,_max,_tiny,_240x240');			

			$upload->set('file_name',$pic_name);

			$return = $upload->upfile('file');

			if (!$return){

				echo "<script type='text/javascript'>window.parent.img_replace_error('" . $upload->error . "');</script>";

				Tpl::showpage('replace_image','null_layout');

				die;

			}

			

			list($width, $height, $type, $attr) = getimagesize(BasePath.DS.'upload'.DS.'store'.DS.'goods'.DS.$_SESSION['store_id'].DS.$apic_info['apic_cover']);

			if (C('ftp_open') && C('thumb.save_type')==3){

				import('function.ftp');

				$image_full_path = ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$apic_info['apic_cover'];

				file_put_contents('f:\b.txt',$image_full_path);

				$_ext = '.'.get_image_type($image_full_path);

				if(!ftpcmd('error')) ftpcmd('delete', $image_full_path);

				if(!ftpcmd('error')) ftpcmd('delete', $image_full_path.'_max'.$_ext);

				if(!ftpcmd('error')) ftpcmd('delete', $image_full_path.'_mid'.$_ext);

				if(!ftpcmd('error')) ftpcmd('delete', $image_full_path.'_small'.$_ext);

				if(!ftpcmd('error')) ftpcmd('delete', $image_full_path.'_tiny'.$_ext);

				if(!ftpcmd('error')) ftpcmd('delete', $image_full_path.'_240x240'.$_ext);

				remote_ftp(ATTACH_GOODS.DS.$_SESSION['store_id'],$apic_info['apic_cover']);			

			}

			

			$param = array();

			$param['apic_size']		= intval($_FILES['file']['size']);

			$param['apic_spec']		= $width.'x'.$height;

			$return = $model_album->updatePic($param,array('apic_id'=>intval($_POST['id'])));

			

			Tpl::output('id',		intval($_POST['id']));

			echo "<script type='text/javascript'>window.parent.img_refresh('" . intval($_POST['id']) . "');</script>";

		}

		Tpl::showpage('replace_image','null_layout');

	}

	

	public function album_pic_watermarkOp(){

		if(empty($_GET['id']) && !is_array($_GET['id'])) {

			showMessage(Language::get('album_parameter_error'),'','html','error');

		}

		

		$id = trim(implode(',', $_GET['id']),',');

		

		

		$model_album = Model('album');

		$param['in_apic_id']	= $id;

		$param['store_id']		= $_SESSION['store_id'];

		$wm_list = $model_album->getPicList($param);

		$model_store_wm = Model('store_watermark');

		

		$store_wm_info = $model_store_wm->getOneStoreWMByStoreId($_SESSION['store_id']);

		if ($store_wm_info['wm_image_name'] == '' && $store_wm_info['wm_text'] == ''){

			showMessage(Language::get('album_class_setting_wm'),"index.php?act=store_album&op=store_watermark",'html','error');

		}

		require_once(BasePath.DS.'framework'.DS.'libraries'.DS.'gdimage.php');

		$gd_image = new GdImage();

		$gd_image->setWatermark($store_wm_info);

		

		foreach ($wm_list as $v) {

			

			$gd_image->create(ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$v['apic_cover'].'_max.'.get_image_type($v['apic_cover']));

		}

		unset($store_wm_info);

		

		showMessage(Language::get('album_pic_plus_wm_succeed'));

	}

	

	public function store_watermarkOp(){

		

		Language::read('member_store_index');

		$model_store_wm = Model('store_watermark');

		

		$store_wm_info = $model_store_wm->getOneStoreWMByStoreId($_SESSION['store_id']);

	

		if (chksubmit()){

			$param = array();

			$param['wm_image_pos'] 			= $_POST['image_pos'];

			$param['wm_image_transition'] 	= $_POST['image_transition'];

			$param['wm_text']		 		= $_POST['wm_text'];

			$param['wm_text_size'] 			= $_POST['wm_text_size'];

			$param['wm_text_angle'] 		= $_POST['wm_text_angle'];

			$param['wm_text_font'] 			= $_POST['wm_text_font'];

			$param['wm_text_pos'] 			= $_POST['wm_text_pos'];

			$param['wm_text_color'] 		= $_POST['wm_text_color'];

			$param['jpeg_quality'] 			= $_POST['image_quality'];

			if (!empty($_FILES['image']['name'])){

				$upload = new UploadFile();

				$upload->set('default_dir',ATTACH_WATERMARK);

				$result = $upload->upfile('image');

				if ($result){

					$param['wm_image_name'] = $upload->file_name;

					

					if (!empty($store_wm_info['wm_image_name'])){

						@unlink(BasePath.DS.ATTACH_WATERMARK.DS.$store_wm_info['wm_image_name']);

					}

				}else {

					showDialog($upload->error);

				}

			}elseif ($_POST['is_del_image'] == 'ok'){

				

				if (!empty($store_wm_info['wm_image_name'])){

					$param['wm_image_name'] = '';

					@unlink(BasePath.DS.ATTACH_WATERMARK.DS.$store_wm_info['wm_image_name']);

				}

			}

			$param['wm_id'] = $store_wm_info['wm_id'];

			$result = $model_store_wm->updateStoreWM($param);

			if ($result){

				showDialog(Language::get('store_watermark_congfig_success'),'reload','succ');

			}else {

				showDialog(Language::get('store_watermark_congfig_fail'));

			}

		}

		

		$dir_list = array();

		readFileList(BasePath.DS.'resource'.DS.'font',$dir_list);

		if (!empty($dir_list) && is_array($dir_list)){

			$fontInfo = array();

			include BasePath.DS.'resource'.DS.'font'.DS.'font.info.php';

			foreach ($dir_list as $value){

				$d_array = explode('.',$value);

				if (strtolower(end($d_array)) == 'ttf' && file_exists($value)){

					$dir_array = explode('/', $value);

					$value = array_pop($dir_array);

					$tmp = explode('.',$value);

					$file_list[$tmp[0]] = $fontInfo[$tmp[0]];

				}

			}

			

			if (strtoupper(CHARSET) == 'GBK'){

				$file_list = Language::getGBK($file_list);

			}

			Tpl::output('file_list',$file_list);

		}

		if (empty($store_wm_info)){

			

			$model_store_wm->addStoreWM(array(

				'wm_text_font'=>'default',

				'store_id'=>$_SESSION['store_id']

			));

			$store_wm_info = $model_store_wm->getOneStoreWMByStoreId($_SESSION['store_id']);

		}
		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出

		self::profile_menu('album','watermark');

		Tpl::output('menu_sign','album');

		Tpl::output('menu_sign_url','index.php?act=store_album&op=album_cate');

		Tpl::output('menu_sign1','watermark');

		Tpl::output('store_wm_info',$store_wm_info);

		Tpl::showpage('store_watermark.form');

	}

	

	private function profile_menu($menu_type,$menu_key=''){

		$menu_array	= array();

		switch ($menu_type) {

			case 'album':

				$menu_array	= array(

				1=>array('menu_key'=>'album','menu_name'=>Language::get('nc_member_path_my_album'),'menu_url'=>'index.php?act=store_album'),

				2=>array('menu_key'=>'watermark','menu_name'=>Language::get('nc_member_path_watermark'),'menu_url'=>'index.php?act=store_album&op=store_watermark')

				);

				break;

			case 'album_pic':

				$menu_array	= array(

				1=>array('menu_key'=>'album','menu_name'=>Language::get('nc_member_path_my_album'),'menu_url'=>'index.php?act=store_album'),

				2=>array('menu_key'=>'pic_list','menu_name'=>Language::get('nc_member_path_album_pic_list'),'menu_url'=>'index.php?act=store_album&op=album_pic_list&id='.intval($_GET['id'])),

				3=>array('menu_key'=>'watermark','menu_name'=>Language::get('nc_member_path_watermark'),'menu_url'=>'index.php?act=store_album&op=store_watermark')

				);

				break;

			case 'album_pic_info':

				$menu_array	= array(

				1=>array('menu_key'=>'album','menu_name'=>Language::get('nc_member_path_my_album'),'menu_url'=>'index.php?act=store_album'),

				2=>array('menu_key'=>'pic_info','menu_name'=>Language::get('nc_member_path_album_pic_info'),'menu_url'=>'index.php?act=store_album&op=album_pic_info&id='.intval($_GET['id']).'&class_id='.intval($_GET['class_id'])),

				3=>array('menu_key'=>'watermark','menu_name'=>Language::get('nc_member_path_watermark'),'menu_url'=>'index.php?act=store_album&op=store_watermark')

				);

				break;

		}

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

	}

	

	public function ajax_change_imgmessageOp(){

		$str_array = explode('/', $_GET['url']);

		$str = array_pop($str_array);

		$str = explode('.', $str);

		

		$model_album = Model('album');

		$param = array();

		$param['like_cover']	= $str['0'];

		$pic_info = $model_album->getPicList($param);

		

		

		list($width, $height, $type, $attr) = getimagesize(BasePath.DS.ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$pic_info['0']['apic_cover'].'_small.'.get_image_type($pic_info['0']['apic_cover']));

		if(strtoupper(CHARSET) == 'GBK'){

			$pic_info['0']['apic_name'] = Language::getUTF8($pic_info['0']['apic_name']);

		}

		echo json_encode(array(

				'img_name'=>$pic_info['0']['apic_name'],

				'default_size'=>sprintf('%.2f',intval($pic_info['0']['apic_size'])/1024),

				'default_spec'=>$pic_info['0']['apic_spec'],

				'upload_time'=>date('Y-m-d',$pic_info['0']['upload_time']),

				'small_spec'=>$width.'x'.$height

			));

	}

	

	public function ajax_check_class_nameOp(){

		$ac_name	= trim($_GET['ac_name']);

		if($ac_name == ''){

			echo 'true';die;

		}

		$model_album	= Model('album');

		$param = array();

		$param['field']		= array('aclass_name','store_id');

		$param['value']		= array($ac_name,$_SESSION['store_id']);

		$class_info = $model_album->getOneClass($param);

		if(!empty($class_info)){

			echo 'false';die;

		}else{

			echo 'true';die;

		}

	}

}

?>

