<?php

defined('haipinlegou') or exit('Access Invalid!');

class sns_swfuploadControl extends BaseHomeControl {
		
	public function swfuploadOp() {
	
		Language::read('sns_home');
		$lang	= Language::getLangContent();
		$member_id	= intval($_POST['mid']);
		$class_id	= intval($_POST['category_id']);
		if ($member_id <= 0 && $class_id <= 0){
			echo json_encode(array('state'=>'false','message'=>Language::get('sns_upload_pic_fail')));
			exit;
		}
		
		$model = Model();
	
		$count = $model->table('sns_albumpic')->where(array('member_id'=>$member_id))->count();
		if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
			echo json_encode(array('state'=>'false','message'=>Language::get('sns_upload_img_max_num_error')));
			exit;
		}
		
		
		$upload = new UploadFile();
		$upload_dir = ATTACH_MALBUM.DS.$member_id.DS;

		$upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
		$thumb_width	= '240,1024';
		$thumb_height	= '2048,1024';

		$upload->set('max_size',C('image_max_filesize'));
		$upload->set('thumb_width',	$thumb_width);
		$upload->set('thumb_height',$thumb_height);
		$upload->set('fprefix',$member_id);
		$upload->set('thumb_ext',	'_240x240,_max');	
		$result = $upload->upfile('Filedata');
		if (!$result){
			echo json_encode(array('state'=>'false','message'=>Language::get('sns_upload_pic_fail')));
			exit;
		}
		
		$img_path 		= $upload->getSysSetPath().$upload->file_name;
		list($width, $height, $type, $attr) = getimagesize(BasePath.DS.ATTACH_MALBUM.DS.$member_id.DS.$img_path);
		



		$image = explode('.', $_FILES["Filedata"]["name"]);
		
		
		if(strtoupper(CHARSET) == 'GBK'){
			$image['0'] = Language::getGBK($image['0']);
		}
		$insert = array();
		$insert['ap_name']		= $image['0'];
		$insert['ac_id']		= $class_id;
		$insert['ap_cover']		= $img_path;
		$insert['ap_size']		= intval($_FILES['Filedata']['size']);
		$insert['ap_spec']		= $width.'x'.$height;
		$insert['upload_time']	= time();
		$insert['member_id']	= $member_id;
		$result = $model->table('sns_albumpic')->insert($insert);

		$data = array();
		$data['file_id']	= $result;
		$data['file_name']	= $img_path;
		$data['file_path']	= $img_path;
		$data['state']		= 'true';
	
		$output = json_encode($data);
		echo $output;
		
	}
}
?>