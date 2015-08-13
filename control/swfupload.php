<?php

defined('haipinlegou') or exit('Access Invalid!');

class swfuploadControl extends BaseMemberStoreControl {
	
	public function swfuploadOp() {
		
		Language::read('iswfupload');
		$lang	= Language::getLangContent();
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		}
		
		$upload = new UploadFile();
		$upload_dir = ATTACH_GOODS.DS.$_SESSION['store_id'].DS;

		$upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
		if (trim($_GET['instance'])=='goods_image') {
			$upload->set('ifresize',true);
		}
		$result = $upload->upfile('Filedata');
		if (empty($upload->error_msg)){
			if (trim($_GET['instance'])=='goods_image') {
				$_POST['pic'] 		= $upload->getSysSetPath().$result['image'];
				$_POST['pic_thumb'] = $upload->getSysSetPath().$result['image_thumb'];
				
				$model_store_wm = Model('store_watermark');
			
				$store_wm_info = $model_store_wm->getOneStoreWMByStoreId($_SESSION['store_id']);
			
				if ($store_wm_info['wm_is_open']){
					require_once(BasePath.DS.'framework'.DS.'libraries'.DS.'gdimage.php');
					$gd_image = new GdImage();
					$gd_image->setWatermark($store_wm_info);
					$gd_image->create(ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$upload->getSysSetPath().$result['image_thumb']);//缩略图加水印
					$gd_image->set('save_file',ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$upload->getSysSetPath().'wm_'.$result['image']);
					$gd_image->set('src_image_name',ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$upload->getSysSetPath().$result['image']);
					$gd_create = $gd_image->create();
					if ($gd_create) $_POST['pic_wm'] = $upload->getSysSetPath().'wm_'.$result['image'];
				}
				unset($store_wm_info);
			} else {
				$_POST['pic'] = $upload->getSysSetPath().$result;
			}

		}else {
			echo json_encode(array('state'=>'false','message'=>$lang['iswfupload_upload_pic_fail']));
			exit;
		}
		
		$model_upload = Model('upload');
		
		$insert_array = array();
		$image_type	  = array('goods_image'=>2,'desc_image'=>3);
		$insert_array['file_name'] = $_POST['pic'];
		$insert_array['file_thumb'] = empty($_POST['pic_thumb']) ? $_POST['pic'] : $_POST['pic_thumb'];
		$insert_array['file_wm']	= $_POST['pic_wm'];
		$insert_array['file_size']	= intval($_FILES['Filedata']['size']);
		$insert_array['upload_time']= time();
		$insert_array['item_id']	= intval($_POST['item_id']);
		$insert_array['store_id']	= $_SESSION['store_id'];
		$insert_array['upload_type'] = $image_type[trim($_GET['instance'])];
		$result = $model_upload->add($insert_array);
		if ($result){
			if ($_POST['pic_wm']) {
				$_POST['pic'] = $_POST['pic_wm'];
			}
			$data = array();
			$data['file_id'] = $result;
			$data['file_name'] = $_POST['pic'];
			$data['file_path'] = $_POST['pic'];
			$data['instance'] = $_GET['instance'];
			
			$output = json_encode($data);
			echo $output;
		}
	}
}
?>