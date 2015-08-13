<?php

defined('haipinlegou') or exit('Access Invalid!');
class goods_recordsModel extends Model 
{
	public function __construct(){
		
		parent::__construct('goods_records');
	}
	
	public function create_item($row,$n=0)
	{
		if(!empty($row))
		{
			$items="<?xml version=\"1.0\" encoding=\"utf-8\"?> \n";

				$items.="<Manifest>\n";

				  $items.="<Head>\n";
				  	/*报文编号*/
					$items.="<MessageID>881101".date('YmdHis').rand(1000,9999)."</MessageID>\n";
					/*业务类型（默认为0）*/
					$items.="<FunctionCode>0</FunctionCode>\n";
					/*报文类型 （默认为881104）*/
					$items.="<MessageType>881101</MessageType>\n";
					
					if(is_array($row[0]))
					{
						/*平台企业备案号*/
						$items.="<SenderID>".$row[0]['company_num']."</SenderID>\n";
						/*平台企业备案号*/
						$items.="<ReceiverID>".$row[0]['company_num']."</ReceiverID>\n";
						
					}else
					{
						/*平台企业备案号*/
						$items.="<SenderID>".$row['company_num']."</SenderID>\n";
						/*平台企业备案号*/
						$items.="<ReceiverID>".$row['company_num']."</ReceiverID>\n";						
					}
					/*发送时间*/
					$items.="<SendTime>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</SendTime>\n";
					/*版本 默认为1.0*/
					$items.="<Version>1.0</Version>\n";

				  $items.="</Head>\n";

				  $items.="<Declaration>\n";

				  if(!empty($row[0]))
				  {
					$items.="<EBill>\n";
						/*商品申请编号*/
						$items.="<EntInsideNo>".$row[0]['goods_apply_num']."</EntInsideNo>\n";
						/*申报地海关*/
						$items.="<MasterCustoms>".$row[0]['apply_custom']."</MasterCustoms>\n";
						/*录入日期*/
						$items.="<InputDate>".$row[0]['input_date']."</InputDate>\n";
						/*申报日期*/
						$items.="<DeclareDate>".$row[0]['declare_date']."</DeclareDate>\n";
						/*备注*/
						$items.="<Nots>".$row[0]['head_not']."</Nots>\n";

					$items.="</EBill>\n";					
				  }else
				  {
					$items.="<EBill>\n";
						/*商品申请编号*/
						$items.="<EntInsideNo>".$row['goods_apply_num']."</EntInsideNo>\n";
						/*申报地海关*/
						$items.="<MasterCustoms>".$row[0]['apply_custom']."</MasterCustoms>\n";
						/*录入日期*/
						$items.="<InputDate>".$row['input_date']."</InputDate>\n";
						/*申报日期*/
						$items.="<DeclareDate>".$row['declare_date']."</DeclareDate>\n";
						/*备注*/
						$items.="<Nots>".$row['head_not']."</Nots>\n";

					$items.="</EBill>\n";					
				  }
					$items.="<EBillLists>\n";
					if(!empty($row[0]))
					{
                                           
						foreach($row as $k=>$v)
						{
							$items.="<EBillList>\n";
								/*商品序号*/
								$items.="<GNo>".$v['product_num']."</GNo>\n";
								/*操作类型*/
								$items.="<OperatingType>".$v['operation_type']."</OperatingType>\n";
								/*商品货号*/
								$items.="<CopGNo>".$v['goods_article_num']."</CopGNo>\n";
								/*商品名称*/
								$items.="<GName>".$v['goods_name']."</GName>\n";
								/*规格型号*/
								$items.="<GModel>".$v['goods_format']."</GModel>\n";
								/*商品编码*/
								$items.="<CodeTS>".$v['goods_commodity_code']."</CodeTS>\n";
								/*申报计量单位*/
								$items.="<Unit>".$v['declaration_unit']."</Unit>\n";
								/*申报单价*/
								$items.="<DecPrice>".$v['declaration_price']."</DecPrice>\n";
								/*行邮税号*/
								$items.="<PostTariffCode>".$v['tax_num']."</PostTariffCode>\n";
								/*毛重*/
								$items.="<GrossWt>".$v['gross_weight']."</GrossWt>\n";
								/*净重*/
								$items.="<NetWt>".$v['net_weight']."</NetWt>\n";
								/*备注*/
								$items.="<Nots>".$v['goods_not']."</Nots>\n";
								/*进出口标志*/
								$items.="<IEFlag>".$v['ieflag']."</IEFlag>\n";
								/*行邮税名称*/
								$items.="<PostTariffName>".$v['tax_name']."</PostTariffName>\n";
								/*商品描述*/
								$items.="<GNote>".$v['goods_note']."</GNote>\n";
								/*商品条形码*/
								$items.="<BARCode>".$v['bar_code']."</BARCode>\n";

							$items.="</EBillList>";								
						}					
					}else
					{
							$items.="<EBillList>\n";
								/*商品序号*/
								$items.="<GNo>".$row['product_num']."</GNo>\n";
								/*操作类型*/
								$items.="<OperatingType>".$row['operation_type']."</OperatingType>\n";
								/*商品货号*/
								$items.="<CopGNo>".$row['goods_article_num']."</CopGNo>\n";
								/*商品名称*/
								$items.="<GName>".$row['goods_name']."</GName>\n";
								/*规格型号*/
								$items.="<GModel>".$row['goods_format']."</GModel>\n";
								/*商品编码*/
								$items.="<CodeTS>".$row['goods_commodity_code']."</CodeTS>\n";
								/*申报计量单位*/
								$items.="<Unit>".$row['declaration_unit']."</Unit>\n";
								/*申报单价*/
								$items.="<DecPrice>".$row['declaration_price']."</DecPrice>\n";
								/*行邮税号*/
								$items.="<PostTariffCode>".$row['tax_num']."</PostTariffCode>\n";
								/*毛重*/
								$items.="<GrossWt>".$row['gross_weight']."</GrossWt>\n";
								/*净重*/
								$items.="<NetWt>".$row['net_weight']."</NetWt>\n";
								/*备注*/
								$items.="<Nots>".$row['goods_not']."</Nots>\n";
								/*进出口标志*/
								$items.="<IEFlag>".$row['ieflag']."</IEFlag>\n";
								/*行邮税名称*/
								$items.="<PostTariffName>".$row['tax_name']."</PostTariffName>\n";
								/*商品描述*/
								$items.="<GNote>".$row['goods_note']."</GNote>\n";
								/*商品条形码*/
								$items.="<BARCode>".$row['bar_code']."</BARCode>\n";

							$items.="</EBillList>\n";						
					}					
					$items.="</EBillLists>\n";

				  $items.="</Declaration>\n";

				$items.="</Manifest>\n";
				
				return $items;
		}
	}
	
	//创建进仓报文
	public function create_warehouse_item($row,$n=0)
	{
		
		$items="<?xml version=\"1.0\" encoding=\"utf-8\"?> \n";
		
		$items.="<Manifest> \n";
		
		  $items.="<Head>\n"; 
			/*报文编号*/
			$items.="<MessageID>881103".date('YmdHis',time()).rand(1000,9999)."</MessageID>\n";
			/*业务类型（默认为0）*/
			$items.="<FunctionCode>0</FunctionCode>\n";		
			/*报文类型 （默认为881103）*/
			$items.="<MessageType>881103</MessageType> \n";
			/*接入企业备案号*/
			$items.="<SenderID>".$row[0]['company_num']."</SenderID> \n";
			/*接入企业备案号*/
			$items.="<ReceiverID>".$row[0]['company_num']."</ReceiverID>\n";		
			/*发送时间*/
			$items.="<SendTime>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</SendTime> \n";
			/*版本 默认为1.0*/
			$items.="<Version>1.0</Version> \n";
			
		  $items.="</Head> \n";
		  
		  $items.="<Declaration> \n";
		  
			$items.="<EBill> \n";
			
				if(!empty($row[0]))
				{	
					/*企业内部编号*/
					$items.="<EntInsideNo>".$row[0]['company_inside_num']."</EntInsideNo> \n";
					/*申报地海关*/
					$items.="<MasterCustoms>".$row[0]['apply_custom']."</MasterCustoms> \n";
					/*起抵国*/
					$items.="<CountryCode>".$row[0]['arrive_country']."</CountryCode>\n";
					/*对应单据类型 */
					$items.="<CorrtDocType>".$row[0]['according_type']."</CorrtDocType> \n";
					/*对应单证编号 */
					$items.="<CorrDocCode>".$row[0]['according_num']."</CorrDocCode> \n";
					/*账册编号 */
					$items.="<EmsNo>".$row[0]['zhangce_num']."</EmsNo> \n";
					/*进出口岸 */
					$items.="<IoPort>".$row[0]['inout_port']."</IoPort> \n";
					/*监管方式 */
					$items.="<TradeMode>".$row[0]['oversight']."</TradeMode> \n";
					/*进出仓日期 */
					$items.="<DrDate>".$row[0]['inout_date']."</DrDate> \n";
					/*录入日期 */
					$items.="<InputDate>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</InputDate> \n";
					/*备注  */
					$items.="<Nots>".$row[0]['remark']."</Nots> \n";				
				}

			$items.="</EBill> \n";
			
			$items.="<EBillLists> \n";

			foreach($row as $k=>$v)
			{
				$items.="<EBillList>\n"; 
					/*起抵国*/
					$items.="<GNo>".$v['product_num']."</GNo> \n";
					/*起抵国*/
					$items.="<CustomsListNO >".$v['goods_custom_num']."</CustomsListNO>\n"; 
					/*起抵国*/
					$items.="<Unit>".$v['declaration_unit']."</Unit> \n";
					/*起抵国*/
					$items.="<Qty>".$v['goods_number']."</Qty> \n";
					/*起抵国*/
					$items.="<DecPrice>".$v['declaration_price']."</DecPrice> \n";
					/*起抵国*/
					$items.="<DecTotal>".(floatval($v['declaration_price'])*floatval($v['goods_number']))."</DecTotal> \n";
					/*起抵国*/
					$items.="<GrossWt>".$v['gross_weight']."</GrossWt>\n"; 
					/*起抵国*/
					$items.="<NetWt>".$v['net_weight']."</NetWt>\n"; 
					/*起抵国*/
					$items.="<Nots>".$v['goods_remark']."</Nots>\n"; 
					
				$items.="</EBillList>\n";						
			}			

				
			$items.="</EBillLists>\n"; 
			
		  $items.="</Declaration>\n"; 
		  
		$items.="</Manifest>\n"; 	

		return $items;
	}
	
	public function get_packets($items,$name='')
	{
		$dir = 'save/';
		
		$newName = $name.date('YmdHis',time()).rand(1000,9999);
		
		if(!file_exists($dir))
		{
			mkdir($dir,'0777',true);
		}
		file_put_contents($dir.$newName.".xml",$items);
		file_put_contents($newName.".xml",$items);
		
		
		$dw= new download($newName.".xml"); 
		$dw->getfiles();
	}
	
	//上传商品csv文件
	public function upload_csv()
	{
		$model = Model('store');
		$goods_class = Model('goods_class');
		if(!empty($_FILES['file']['name']))
		{
			$file = $_FILES['file'];
	
			//判断文件大小
			if($file['size'] > 2*1024*1024)
			{
				echo "<script>parent.error('上传文件超过2M')</script>";exit;
			}
			
			//判断文件类型
			$type = strchr($file['name'],'.');
			if($type !=='.csv')
			{
				echo "<script>parent.error('请上传csv格式文件');</script>";exit;
			}
			
			/*
			if($file['type']!=='application/vnd.ms-excel')
			{
				echo "<script>parent.error('请上传csv文件格式')</script>";
			}
			*/
						
			if(!file_exists('tmp/warehouse_list'))
				mkdir('tmp/warehouse_list/','0777',true);
			
			$newName = MD5(time().rand(1000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'tmp/warehouse_list/'.$newName))
			{
				$arr = 'tmp/warehouse_list/'.$newName;
			
				$file = fopen($arr,'r'); 
                
				
				while ($data = fgetcsv($file)) 
				{   
					$data[0] = mb_convert_encoding(trim($data[0]), "UTF-8", "GBK");
					$data[1] = mb_convert_encoding(trim($data[1]), "UTF-8", "GBK");
					$data[2] = mb_convert_encoding(trim($data[2]), "UTF-8", "GBK");
					$data[3] = mb_convert_encoding(trim($data[3]), "UTF-8", "GBK");
					$data[4] = mb_convert_encoding(trim($data[4]), "UTF-8", "GBK");
					$data[5] = mb_convert_encoding(trim($data[5]), "UTF-8", "GBK");
					$data[6] = mb_convert_encoding(trim($data[6]), "UTF-8", "GBK");
					$data[7] = mb_convert_encoding(trim($data[7]), "UTF-8", "GBK");
					$data[8] = mb_convert_encoding(trim($data[8]), "UTF-8", "GBK");
					$data[9] = mb_convert_encoding(trim($data[9]), "UTF-8", "GBK");
					$data[10] = mb_convert_encoding(trim($data[10]), "UTF-8", "GBK");
					$data[11] = mb_convert_encoding(trim($data[11]), "UTF-8", "GBK");
					$data[12] = mb_convert_encoding(trim($data[12]), "UTF-8", "GBK");
					$data[13] = mb_convert_encoding(trim($data[13]), "UTF-8", "GBK");
					$data[14] = mb_convert_encoding(trim($data[14]), "UTF-8", "GBK");
					$data[15] = mb_convert_encoding(trim($data[15]), "UTF-8", "GBK");
					$data[16] = mb_convert_encoding(trim($data[16]), "UTF-8", "GBK");
					$data[17] = mb_convert_encoding(trim($data[17]), "UTF-8", "GBK");
					$data[18] = mb_convert_encoding(trim($data[18]), "UTF-8", "GBK");
					$data[19] = mb_convert_encoding(trim($data[19]), "UTF-8", "GBK");
					$data[20] = mb_convert_encoding(trim($data[20]), "UTF-8", "GBK");//商品国家
					$data[21] = mb_convert_encoding(trim($data[21]), "UTF-8", "GBK");//商品供应商
					$data[22] = mb_convert_encoding(trim($data[22]), "UTF-8", "GBK");//一级分类
					$data[23] = mb_convert_encoding(trim($data[23]), "UTF-8", "GBK");//二级分类
					$data[24] = mb_convert_encoding(trim($data[24]), "UTF-8", "GBK");//商品外文
					$data[25] = mb_convert_encoding(trim($data[25]), "UTF-8", "GBK");//商品申报地海关
					//$data[21] = mb_convert_encoding($data[21], "UTF-8", "GBK");
                   
                  
					
					//前三行和最后一行都不要读取
					//if($data[0]=='商品序号' || $data[0]=='填写说明' )continue;
					$n = 0;
                                 
					foreach($data as $k=>$v)
					{
						if(!empty($v))
						{
							++$n;
						}
					}
					if($n<=1 || trim($data[0])=='序号' || trim($data[0]) == '说明')continue;		
					
					$brr = array();
					$brr['is_seller'] = 1;
					//$brr['goods_apply_num'] = date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8); 
					$brr['declare_date'] = date('Y-m-d H:i:s',time());
					$brr['input_date'] = date('Y-m-d H:i:s',time());
					$brr['head_not'] = $data[3];
					$brr['product_num'] = $data[4];
					$brr['operation_type'] = $data[5]; 
					$brr['goods_article_num'] = $data[6]; 
					$brr['goods_name'] = trim($data[7]);
					$brr['goods_format'] = $data[8]; 
					$brr['goods_commodity_code'] = $data[9]; 
					$brr['declaration_unit'] = $data[10]; 
					$brr['declaration_price'] = $data[11]; 
					$brr['tax_name'] = $data[12];
					$brr['tax_num'] = $data[13];
					$brr['gross_weight'] = $data[14]; 
					$brr['net_weight'] = $data[15]; 
					$brr['ieflag'] = $data[16]; 
                    $brr['goods_note'] = $data[17];
					$brr['goods_note'] = $data[18]; 
					$brr['bar_code'] = $data[19]; 
					$brr['goods_not'] = $data[20]; 
					//$brr['store_name'] = $data[20]; 
					$brr['goods_country'] = $data[21];//商品国家 
					$brr['goods_provider'] = $data[22];//商品供应商
					$brr['top_category'] = $data[23];//一级分类
					$brr['second_category'] = $data[24];//二级分类
					
					//判断是否韩文或者迪拜文或者为日文
					if($brr['goods_country'] == '韩国'){
						$data[25] = self::languageOp($brr['goods_name'],"zh","kor");
					}else if($brr['goods_country'] == '迪拜'){
						$data[25] = self::languageOp($brr['goods_name'],"zh","ara");
					}else if($brr['goods_country'] == '日本'){
						$data[25] = self::languageOp($brr['goods_name'],"zh","jp");
					}
					
					$brr['foreign_language'] = $data[25];//商品外文名称
					$brr['custom_type'] = $data[26];//业务模式
                    $brr['goods_bccustom_num']=$data[13];//BC模式下，商品海关备案号等于等于行邮税号。
                    if(strtolower($brr['custom_type'])=='bc'){
                        $brr['examine']=1;
                    }
                    /*                    
					$par['table']='store';
					$par['field']='store_name';
					$par['value']=$brr['store_name'];
					$store = Db::getRow($par,"*" );
					if(!empty($store))
					{
						$brr['store_id'] = $store['store_id'];
					}
					*/
                   // print_r($brr);
									
					//查看是否有重复商品导入
					$param['table']='goods_records';
					$param['field']='goods_article_num';
					$param['value']=$brr['goods_article_num'];
					$row = Db::getRow($param,"*" );
					if(!empty($row))
					{
						//修改，保留原商品备案申请编号
						if(empty($row['goods_apply_num'])){
							$brr['goods_apply_num'] = date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
						}else{
							$brr['goods_apply_num'] = $row['goods_apply_num'];
						}
						
						//修改，检测BBC是否需要重新备案。
						if(strtolower($brr['custom_type'])=='bbc' && empty($row['goods_custom_num'])){
							$brr['operation_type'] = 'A';
							$brr['examine']=0;
						}else{
							$brr['operation_type'] = 'M';
							$brr['examine']=0;
						}
						
						Db::update('goods_records',$brr,"goods_article_num='".$brr['goods_article_num']."'");
						
					}else
					{
						//新增，操作类型为A。
						$brr['operation_type'] = 'A';
						//新增，生成商品备案申请编号
						$brr['goods_apply_num'] = date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
						Db::insert('goods_records',$brr);  
					}
					 
				}
                               
				fclose($file);  

				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>parent.success('上传成功');</script>";
			}else{
			
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>parent.error('文件上传失败，请联系管理员。')</script>";
			}
		}
	}
    
	
	public function languageOp($value,$from="auto",$to="auto")
	{
	  $value_code=urlencode($value);
	  #首先对要翻译的文字进行 urlencode 处理
	  $appid="VnFR1rEXSa2OhIzQ5Ft7ADGv";
	  #您注册的API Key
	  $languageurl = "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=" . $appid ."&q=" .$value_code. "&from=".$from."&to=".$to;
	  #生成翻译API的URL GET地址
	  $text=json_decode(self::language_textOp($languageurl));
	  $text = $text->trans_result;
	  return $text[0]->dst;
	}
	//获取目标URL所打印的内容
	public function language_textOp($url)  
	{
	  if(!function_exists('file_get_contents')) {
	   $file_contents = file_get_contents($url);
	  } else {
	  $ch = curl_init();
	  $timeout = 5;
	  curl_setopt ($ch, CURLOPT_URL, $url);
	  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	  $file_contents = curl_exec($ch);
	  curl_close($ch);
	  }
	   return $file_contents;
	}

	/*进仓信息导入*/
	/*
	public function jincang_cupload_csv()
	{
		$model = Model('store');
		if(!empty($_FILES['file']['name']))
		{
			
			$file = $_FILES['file'];

			if($file['size'] > 2*1024*1024)
			{
				echo "<script>parent.error('上传文件超过2M')</script>";
			}
			
			
			if($file['type']!=='application/vnd.ms-excel')
			{
				echo "<script>parent.error('上传文件类型不对')</script>";
			}
			
			
			if(!file_exists('warehouse_list'.$dir))
				mkdir('warehouse_list/','0777',true);
			
			$newName = MD5(time().rand(1000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'warehouse_list/'.$newName))
			{
				$arr = 'warehouse_list/'.$newName;
				
				$file = fopen($arr,'r'); 
				
				while ($data = fgetcsv($file)) 
				{   
					
					$data[0] = mb_convert_encoding($data[0], "UTF-8", "GBK");
					$data[1] = mb_convert_encoding($data[1], "UTF-8", "GBK");
					$data[2] = mb_convert_encoding($data[2], "UTF-8", "GBK");
					$data[3] = mb_convert_encoding($data[3], "UTF-8", "GBK");
					$data[4] = mb_convert_encoding($data[4], "UTF-8", "GBK");
					$data[5] = mb_convert_encoding($data[5], "UTF-8", "GBK");
					$data[6] = mb_convert_encoding($data[6], "UTF-8", "GBK");
					$data[7] = mb_convert_encoding($data[7], "UTF-8", "GBK");
					$data[8] = mb_convert_encoding($data[8], "UTF-8", "GBK");
					$data[9] = mb_convert_encoding($data[9], "UTF-8", "GBK");
					$data[10] = mb_convert_encoding($data[10], "UTF-8", "GBK");
					$data[11] = mb_convert_encoding($data[11], "UTF-8", "GBK");
					$data[12] = mb_convert_encoding($data[12], "UTF-8", "GBK");
					$data[13] = mb_convert_encoding($data[13], "UTF-8", "GBK");
					$data[14] = mb_convert_encoding($data[14], "UTF-8", "GBK");
					$data[15] = mb_convert_encoding($data[15], "UTF-8", "GBK");
					$data[16] = mb_convert_encoding($data[16], "UTF-8", "GBK");
					if($data[0]=='企业内部编号')continue;
					
					$brr = array();
					$brr['is_seller'] = $_SESSION['is_seller'];
					$brr['store_id'] = $_SESSION['store_id'];
					$brr['company_inside_num'] = $data[0]; 
					$brr['apply_custom'] = $data[1]; 
					$brr['according_type'] = $data[2]; 
					$brr['according_num'] = $data[3]; 
					$brr['zhangce_num'] = $data[4]; 
					$brr['inout_port'] = $data[5]; 
					$brr['oversight'] = $data[6]; 
					$brr['inout_date'] = $data[7]; 
					$brr['remark'] = $data[8]; 
					$brr['goods_custom_num'] = $data[9]; 
					$brr['declaration_unit'] = $data[10]; 
					$brr['goods_number'] = $data[11]; 
					$brr['declaration_price'] = $data[12]; 
					$brr['declaration_total_price'] = $data[13]; 
					$brr['gross_weight'] = $data[14]; 
					$brr['net_weight'] = $data[15]; 
					$brr['goods_remark'] = $data[16]; 
					
					$brr['declare_date'] = date('Y-m-d h:i:s',time());
					$brr['input_date'] = date('Y-m-d h:i:s',time());
					$brr['jincang_input'] = date('Y-m-d h:i:s',time());
					$row = $model->where(array('store_id'=>$_SESSION['store_id']))->find();
					$brr['store_name'] = $row['store_name'];
					$param['table'] = 'goods_records';
					$param['field']='goods_custom_num';
					$param['value'] = $brr['goods_custom_num'];
					$row = Db::getRow($param,"*" );
		
					if(!empty($row) && !empty($row['goods_custom_num']) && $row['examine']==1)
					{
						if($row['operation_type']=='M')
						{
							$brr['operation_type'] = 'M';
						}
						$brr['warehouse'] = 1;
						
						Db::update('goods_records',$brr,"goods_custom_num='".$brr['goods_custom_num']."'");
					}
				}
				fclose($file);  
				echo "<script>parent.success();</script>";
				
			}else
			{
				echo "<script>parent.error('上传出错')</script>";
			}
		}		
	}
	*/
	//获取商品备案列表
	public function getGoodsRcords($param,$page = '',$field='*') 
	{
		$condition_str = $this->getCondition($param);

		$array	= array();
		$array['table']	= 'goods_records';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$array['order'] = $param['order'] ? $param['order'] : 'id desc';
		$list_goods		= Db::select($array,$page);
		
		return $list_goods;
	}
	
	
	private function getCondition($condition_array)
	{
		$condition_sql = '';
		if ($condition_array['store_id'] != '') {
			$condition_sql	.= " `goods_records`.store_id= '{$condition_array['store_id']}'";
		}		

		if(is_array($condition_array['examine']))
		{

			foreach($condition_array['examine'] as $k=>$v)
			{
				$arr .= "'".$v."',";
			}
			$arr = substr($arr,0,-1);

			$condition_sql	.= " `goods_records`.examine in(".$arr.") ";
			
		}else if($condition_array['examine'] !='')
		{
			$condition_sql	.= " `goods_records`.examine = '{$condition_array['examine']}'";
		}
		if ($condition_array['is_show'] != '') {
			$condition_sql	.= " and `goods_records`.is_show= '{$condition_array['is_show']}'";
		}	
		if ($condition_array['goods_custom_num'] == 'notnull') {
			$condition_sql	.= " and `goods_records`.goods_custom_num != ''";
		}
		if($condition_array['warehouse'] !='')
		{
			if ($condition_array['warehouse'] == 'notnull') {
				$condition_sql	.= " and `goods_records`.warehouse != ''";
			}else
			{
				$condition_sql	.= " and `goods_records`.warehouse= '{$condition_array['warehouse']}'";
			}
		}
		if($condition_array['like_goods_name'] != ''){
			$condition_sql	.= " and goods_name like '%".$condition_array['like_goods_name']."%'";
		}
        
        if($condition_array['custom_type'] != ''){
			$condition_sql	.= " and custom_type = '".$condition_array['custom_type']."'";
		}
        
         
        if($condition_array['goods_apply_num'] != ''){
			$condition_sql	.= " and goods_apply_num = '".$condition_array['goods_apply_num']."'";
		}
        
         
        if($condition_array['goods_article_num'] != ''){
			$condition_sql	.= " and goods_article_num = '".$condition_array['goods_article_num']."'";
		}
		
		if($condition_array['store_name'] != ''){
			$condition_sql	.= " and store_name = '".$condition_array['store_name']."'";
		}
		
		return $condition_sql;
	}

	/*商品备案删除*/
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('goods_records',$where);
			return $result;
		}else {
			return false;
		}
	}

	//判断导出时商品备案的商品申请编号是否相同
	public function check()
	{
		$goods_records =Model('goods_records');
		$n = 0; //用于判断是否有相同的商品申请编号
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			if(!empty($_GET['id']))
			{
				$id = intval($_GET['id']);
				$id = substr($_GET['id'],0,-1);
				$goods_info = $goods_records->where("id in (".$id.")")->field('goods_apply_num')->select();
				foreach($goods_info as $k=>$v)
				{
					if($goods_info[$k]!==$goods_info[++$k])
					{
						++$n;
					}
				}
				if($n > 0)
				{
					echo 0;exit;
					
				}else{

					echo 1;exit;
				}
			}			
		}
	}
	/*
	//获取商品备案列表
	public function goods_record_list($param,$field='*'){
		if(empty($param)){
			return false;
		}
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'goods_records';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$member_info	= Db::select($param);
		return $member_info[0];
	}
	
	private function getCondition($conditon_array){

		$condition_sql = '';
		if($conditon_array['like_goods_name'] != ''){
			$condition_sql	.= " and goods_name like '%".$conditon_array['like_goods_name']."%'";
		}	
		return $condition_sql;
	}*/
	

}
