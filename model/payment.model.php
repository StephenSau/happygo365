<?php

defined('haipinlegou') or exit('Access Invalid!');
class paymentModel{
	
	public function getPaymentList(){
		$payment_dir = BasePath.DS.'api'.DS.'payment';
		$result = readDirList($payment_dir);

		if (is_array($result)){
			
			$payment_list = array();
			foreach ($result as $k => $v){
				$info_file = $payment_dir.DS.$v.DS.'info.php';
				if (file_exists($info_file)){
					require_once($info_file);
					if (is_array($info_array) && !empty($info_array)){
						$payment_list[] = $info_array;
					}
				}
				unset($info_file);
			}
			return $payment_list;
		}else {
			return false;
		}
	}

	
	public function setPaymentInc($payment_code,$payment_state){
		Language::read('model_lang_index');
		$lang	= Language::getLangContent();		
		$payment_list = $this->getPaymentList();
		if (is_array($payment_list)){
			try {
				$inc_file = BasePath.DS.'api'.DS.'payment'.DS.'payment.inc.php';
				if (file_exists($inc_file)){
					
					require_once($inc_file);
					if (empty($payment_inc)){
						$payment_inc = array();
					}
				}else {
					$payment_inc = array();
				}
				
				$fp = @fopen($inc_file,'wb+');
				if ($fp === false){
					$error = $lang['payment_api_file_not_found'].'api'.DS.'payment'.DS.'payment.inc.php'.$lang['payment_api_touch_file_and_go_on'];
					throw new Exception($error);
				}
			}catch (Exception $e){
				showMessage($e->getMessage(),'','exception');
			}
			foreach ($payment_list as $k => $v){
				if ($v['code'] == $payment_code){
					$file_content = '';
					$file_content .= '<?php'."\r\n";
					$file_content .= "defined('haipinlegou') or exit('Access Invalid!');"."\r\n";
					$file_content .= '$payment_inc = array('."\r\n";
					switch ($payment_state){
						case 'open':
							$payment_inc = @array_merge($payment_inc,array($payment_code));
							break;
						case 'close':
							foreach ($payment_inc as $k_inc => $v_inc){
								if ($v_inc == $payment_code){
									unset($payment_inc[$k_inc]);
								}
							}
							break;
						default:
							return false;
					}
					foreach ($payment_inc as $k_inc => $v_inc){
						$file_content .= "\t'". $v_inc ."',"."\r\n";
					}
					$file_content .= ');'."\r\n";
					
					@fwrite($fp,$file_content);
					@fclose($fp);
					return true;
				}
			}
			return false;
		}else {
			return false;
		}
	}
	
	public function getPaymentInfo($file_dir,$type='') {
		if($file_dir == '') {
			return false;
		}

		$payment_info	= array();
		if($type == 'file') {
			$info_file = BasePath.DS.'api'.DS.'payment'.DS.$file_dir.DS.'info.php';
			if (file_exists($info_file)){
				require_once($info_file);
				if (is_array($info_array) && !empty($info_array)){
					$payment_info = $info_array;
				}
			}
			unset($info_file);
		} else {
			$payment_info	= Db::select(array('table'=>'payment','where'=>" WHERE payment_code='$file_dir' and store_id='{$_SESSION['store_id']}' ",'field'=>'*'));
			$payment_info	= $payment_info[0];
		}
		return $payment_info;
	}
	
	public function savePayment($config_array) {
		$payment_array	= array();
		$payment_config	= '';

		if(is_array($config_array) and !empty($config_array)) {
			$config_info	= array();
			foreach ($config_array as $k => $v) {
				$_POST[$k] = str_replace(array('\\','\''),array('',''),$_POST[$k]);
				$config_info[$k] = trim($_POST[$k]);
			}
			$payment_config	= serialize($config_info);
		}

		$payment_array['payment_code']	= trim($_POST['payment_code']);
		$payment_array['payment_name']	= trim($_POST['payment_name']);
		$payment_array['payment_info']	= trim($_POST['payment_desc']);
		$payment_array['payment_config']= $payment_config;
		$payment_array['payment_online']= intval($_POST['payment_online']);
		$payment_array['store_id']		= $_SESSION['store_id'];
		$payment_array['payment_state']	= intval($_POST['payment_state']);
		$payment_array['payment_sort']	= intval($_POST['sort_order']);
		if (intval($_POST['payment_id']) != 0) {
			return Db::update('payment',$payment_array,"where payment_id='".intval($_POST['payment_id'])."' and store_id='{$_SESSION['store_id']}' ");
		} else {
		$param	= array();
		$param['table']	= 'payment';
		$param['where']	= "store_id=".$_SESSION['store_id']." and payment_code='".trim($_POST['payment_code'])."'";
		$param['field']	= 'payment_id';
		$param['limit'] = 1;
		$member_info	= Db::select($param);
		if (!empty($member_info)) return true;
		return Db::insert('payment',$payment_array);
		}
	}
	
	public function checkinstallPayment($payment_list) {
		if (is_array($payment_list)and !empty($payment_list)) {
			$my_payment = Db::select(array('table'=>'payment','where'=>"where store_id='{$_SESSION['store_id']}'",'field'=>'payment_id,payment_code,payment_state'));
			
			if (is_array($my_payment) && !empty($my_payment)){
				foreach ($my_payment as $k=>$v) {
					$my_payment[$v['payment_code']] = $v;
					unset($my_payment[$k]); 
				}
			}
			if (is_array($my_payment) && !empty($my_payment)){
				foreach ($payment_list as $key => $val) {
					if (array_key_exists($val['code'],$my_payment)){
						$payment_list[$key]['install'] = 1;
						$payment_list[$key]['payment_state'] = $my_payment[$val['code']]['payment_state'];
						$payment_list[$key]['payment_id']	 = $my_payment[$val['code']]['payment_id'];					
					}
				}
			}

			return $payment_list;
		
		} else {
			return '';
		}
	}
	
	public function uninstallPayment($payment_id) {
		if($payment_id == 0) {
			return false;
		}
		$state = Db::delete('payment',"where payment_id='$payment_id' and store_id='{$_SESSION['store_id']}'");
		if($state) {
			return true;
		} else {
			return false;
		}
	}
	
	public function checkPayment($payment_code) {
		if (file_exists(BasePath.DS.'api'.DS.'payment'.DS.'payment.inc.php')){
			require_once(BasePath.DS.'api'.DS.'payment'.DS.'payment.inc.php');
		}
		if (empty($payment_inc)){
			return false;
		}
		if (in_array($payment_code,$payment_inc)){
			return true;
		}else {
			return false;
		}
	}
	
	public function checkStorePayment() {
		$payment_list	= Db::select(array('table'=>'payment','where'=>" WHERE store_id='{$_SESSION['store_id']}'",'field'=>'*'));
		if (is_array($payment_list) and !empty($payment_list)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function listStorePayment($payment_type,$store_id,$payment_state=1) {
		$payment_list	= array();
		$payment_list	= Db::select(array('table'=>'payment','where'=>" WHERE store_id='$store_id' and payment_online='$payment_type' and payment_state='$payment_state'"));
		
		$payment_inc = array();
		
		if (file_exists(BasePath.DS.'api'.DS.'payment'.DS.'payment.inc.php')){
			
			require(BasePath.DS.'api'.DS.'payment'.DS.'payment.inc.php');
		}
		if(!empty($payment_list) && is_array($payment_list)){
			$payment = array();
			foreach($payment_list as $k => $v){
				if(@in_array($v['payment_code'],$payment_inc)) $payment[] = $v;
			}
			$payment_list	= $payment;
		}
		return $payment_list;
	}
	
	public function getPaymentById($payment_id,$type=''){
        $param = array();
        switch($type) {
            case 'condition':
                if(is_array($payment_id)&&!empty($payment_id)){
                    $param['table'] = 'payment';
                    $param['where'] = $payment_id['where'];
                }
                break;
            default:
            $param['table'] = 'payment';
            $param['where'] = "where payment_id='$payment_id'";
        }
        $payment_list = Db::select($param);
        return $payment_list[0];
	}
	
	
	public function upload_yinlian()
	{
		if(!empty($_FILES['file']['name']))
		{
			
			if($_GET['payment_code']=='yinlianbank' || $_GET['payment_code']=='gnete'){
			     $yinlian=$_GET['payment_code'];
            }
            $file = $_FILES['file'];
			
			if($file['size'] > 2*1024*1024)
			{
				echo "<script>parent.error('上传文件超过2M')</script>";
			}
			
			if($file['type']!=='application/octet-stream')
			{
				echo "<script>parent.error('上传文件类型不对')</script>";
			}
			
			if(!file_exists('api/payment/'.$yinlian.'/cer/'))
				mkdir('api/payment/'.$yinlian.'/cer/','0777',true);
				
			$newName = $_SESSION['store_id'].strchr($file['name'],'.');
	
			if(move_uploaded_file($file['tmp_name'],'api/payment/'.$yinlian.'/cer/'.$newName))
			{
				$arr = 'api/payment/'.$yinlian.'/cer/'.$newName;
				echo "<script>parent.success('".$arr."');</script>";
				
			}else
			{
				echo "<script>parent.error('上传出错')</script>";
			}
		}
	}	
	
	public function upload_shanghu()
	{
		if(!empty($_FILES['file']['name']))
		{
			
            if($_GET['payment_code']=='yinlianbank' || $_GET['payment_code']=='gnete'){
			     $shanghu=$_GET['payment_code'];
            }
            
			$file = $_FILES['file'];
			
			if($file['size'] > 2*1024*1024)
			{
				echo "<script>parent.error('上传文件超过2M')</script>";
			}
			
			if($file['type']!=='application/octet-stream')
			{
				echo "<script>parent.error('上传文件类型不对')</script>";
			}
			
			if(!file_exists('api/payment/'.$shanghu.'/cer/'))
				mkdir('api/payment/'.$shanghu.'/cer/','0777',true);
				
			$newName = $_SESSION['store_id'].strchr($file['name'],'.');
				
			if(move_uploaded_file($file['tmp_name'],'api/payment/'.$shanghu.'/cer/'.$newName))
			{
				$arr = 'api/payment/'.$shanghu.'/cer/'.$newName;
				echo "<script>parent.success2('".$arr."');</script>";
				
			}else
			{
				echo "<script>parent.error('上传出错')</script>";
			}
		}
	}
	
}