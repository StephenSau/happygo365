<?php

defined('haipinlegou') or exit('Access Invalid!');
class goods_ciq_recordsModel extends Model 
{
	public function __construct(){
		
		parent::__construct('goods_ciq_records');
	}
	
	public function create_item($row)
	{
		if(!empty($row))
		{
			$items="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
			
			$items.="<Root>\r\n";
			
			/*表头*/
			$items.="<Head>\r\n";
			
			/*报文编号*/
			$items.="<MessageID>". $row['ciqmessageid'] ."</MessageID>\r\n";
			
			/*报文类型，默认661105*/
			$items.="<MessageType>661105</MessageType>\r\n";
			
			/*发送者*/
			$items.="<Sender>". $row['ent_ciq_num'] ."</Sender>\r\n";
			
			/*接收者*/
			$items.="<Receiver>". $row['ent_ciq_num'] ."</Receiver>\r\n";
			
			/*发送时间*/
			$items.="<SendTime>". substr($row['ciqmessageid'],7,-3) ."</SendTime>\r\n";
			
			/*业务类型（默认为0）*/
			$items.="<FunctionCode></FunctionCode>\r\n";
			
			/*版本 默认为1.0*/
			$items.="<Version>1.0</Version>\r\n";
	
			$items.="</Head>\r\n";
	
			/*表体*/
			$items.="<Body>\r\n";
			
			$items.="<GOODSRECORD>\r\n";
			
			$items.="<Record>\r\n";
			
			/*商品申请编号*/
			$items.="<CargoBcode>". $row['entcargobackcode'] ."</CargoBcode>\r\n";
			
			/*商检机构代码*/
			$items.="<Ciqbcode>". $row['ciqbcode'] ."</Ciqbcode>\r\n";
			
			/*电商企业备案编号*/
			$items.="<CbeComcode>". $row['ent_ciq_num'] ."</CbeComcode>\r\n";
			
			/*备注*/
			$items.="<Remark></Remark>\r\n";
			
			/*制单企业编号*/
			$items.="<Editccode>". $row['ent_ciq_num'] ."</Editccode>\r\n";
			
			/*申报日期*/
			$items.="<ApplyDate>". substr($row['ciqmessageid'],7,-3) ."</ApplyDate>\r\n";
			
			/*商品列表*/
			$items.="<CARGOLIST>\r\n";
			
			/*商品*/
			$items.="<Record>\r\n";
			
			/*商品货号，电商企业商品唯一编号*/
			$items.="<Gcode>". $row['gcode'] ."</Gcode>\r\n";
			
			/*商品名称*/
			$items.="<Gname>". $row['gname'] ."</Gname>\r\n";
			
			/*商品规格型号*/
			$items.="<Spec>". $row['spec'] ."</Spec>\r\n";
			
			/*商品海关编码*/
			$items.="<Hscode>". $row['hscode'] ."</Hscode>\r\n";
			
			/*商品计量单位编码*/
			$items.="<Unit>". $row['qtyunit'] ."</Unit>\r\n";
			
			/*商品条形码*/
			$items.="<GoodsBarcode>". $row['gbarcode'] ."</GoodsBarcode>\r\n";
			
			/*商品详细地址*/
			$items.="<GoodsDesc>". urlencode($row['goodsdesc']) ."</GoodsDesc>\r\n";
			
			/*备注*/
			$items.="<Remark></Remark>\r\n";
			
			/*生产企业名称*/
			$items.="<ComName>". $row['comname'] ."</ComName>\r\n";
			
			/*品牌*/
			$items.="<Brand>". $row['brand'] ."</Brand>\r\n";
			
			/*原产地*/
			$items.="<AssemCountry>". $row['assemcountry'] ."</AssemCountry>\r\n";
			
			$items.="</Record>\r\n";
			
			$items.="</CARGOLIST>\r\n";	
			
			$items.="</Record>\r\n";
	
			$items.="</GOODSRECORD>\r\n";
	
			$items.="</Body>\r\n";
			
			$items.="</Root>\r\n";
	
			return $items;
		}
	}	
	
	public function get_packets($items,$name='')
	{
		header( 'Expires:0' );
		header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-type:text/xml" );
		//header( "Content-Length: " . mb_strlen($items,'utf8') );
		header( "Content-Disposition: attachment; filename=". $name .".xml" );
		header( 'Content-Transfer-Encoding: binary' );
		echo $items;
	}
	
	//获取商品备案列表
	public function getGoodsRcords($param,$page = '',$field='*') 
	{
		$condition_str = $this->getCondition($param);

		$array	= array();
		$array['table']	= 'goods_ciq_records';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$array['order'] = $param['order'] ? $param['order'] : 'id desc';
		$list_goods		= Db::select($array,$page);
		
		return $list_goods;
	}
	
	
	private function getCondition($condition_array)
	{
		$condition_sql = '';
		         
        if($condition_array['entcargobackcode'] != ''){
			$condition_sql	.= " and entcargobackcode = '".$condition_array['entcargobackcode']."'";
		}
		
		if($condition_array['gcode'] != ''){
			$condition_sql	.= " and gcode = '".$condition_array['gcode']."'";
		}
		
		if(!empty($condition_array['ciqstatus'])){
			$str = "";
			foreach ($condition_array['ciqstatus'] as $v){
				if(empty($v))continue;
				$str .= "'" .$v . "',";
			}
			if(!empty($str)){
				$str = " ( ciqstatus in (". rtrim($str,',') . ") ";
			}else{
				$str = " ( ";
			}
			if(in_array(NULL, $condition_array['ciqstatus']) or in_array('', $condition_array['ciqstatus'])){
				$str	.= " or ciqstatus = '' or ciqstatus is NULL ) ";
			}else{
				$str .= " ) ";
			}

			$condition_sql	.= " and ".$str;
		}
		
		return $condition_sql;
	}

	/*商品备案删除*/
	public function del($id){
		if (intval($id) > 0){
			$where = " ( ciqstatus = '' OR ciqstatus = 'F' OR ciqstatus is NULL ) ";
			$where .= " AND id = '". intval($id) ."'";
			$result = Db::delete('goods_ciq_records',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/*
	 * 商检商品备案回执
	 */
	public function get_receipt(){

		if(!empty($_FILES['file']['name']))

		{

			$file = $_FILES['file'];

			/*文件大小检测，最大2M*/
			if($file['size'] > 2*1024*1024)
			{

				echo "<script>alert('上传文件超过2M');location.back(-1);</script>";

			}		

			/*文件类型检测，XML报文*/
			if($file['type']!=='text/xml')
			{
				echo "<script>alert('上传文件类型不对');location.back(-1);</script>";
			}			

			/*回执目录检测，不存在则创建目录*/
			if(!file_exists(dirname(dirname(__FILE__)).'/tmp/warehouse_list'))
			{
				mkdir(dirname(dirname(__FILE__)).'/declare/warehouse_list/','0777',true);
			}		

			$file_distination = dirname(dirname(__FILE__)).'/declare/warehouse_list/'.$file['name'];
			if(move_uploaded_file($file['tmp_name'],$file_distination))
			{
				return $file_distination;
				
			}else
			{
				
				echo "<script>alert('上传失败');location.back(-1);</script>";
				
			}

		}

	}
	
	/*
	 * 商检商品备案，批量上传
	 */
	public function upload_csv()
	{
		if(!empty($_FILES['file']['name']))
		{
			$file = $_FILES['file'];
	
			//判断文件大小
			if($file['size'] > 2*1024*1024)
			{
				echo "<script>alert('上传文件超过2M')</script>"; return ;
			}
				
			//判断文件类型
			$type = strchr($file['name'],'.');
			if($type !=='.csv')
			{
				echo "<script>alert('请上传csv格式文件');</script>"; return ;
			}
	
			if(!file_exists('tmp/warehouse_list'))
				mkdir('tmp/warehouse_list/','0777',true);
				
			$newName = MD5(time().rand(1000,9999)).strchr($file['name'],'.');
				
			if(move_uploaded_file($file['tmp_name'],'tmp/warehouse_list/'.$newName))
			{
				$arr = 'tmp/warehouse_list/'.$newName;
				
				$file = fopen($arr,'r');
				
				$n = 0;
				$regcodes = "";
				
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

					//第一行不读取，为表头注释。					
					if($n<1){
						++$n;
						continue;
					}
					
					$brr = array();
					$brr['entcargobackcode'] = date('Ymd',time()) . '_' . substr(uniqid(mt_rand()),0,10);
					$brr['gcode'] = $data[0];
					$brr['gname'] = $data[1];
					$brr['spec'] = $data[2];
					$brr['gbarcode'] = $data[3];
					$brr['brand'] = $data[4];
					$brr['assemcountry'] = $data[5];
					$brr['goodsdesc'] = $data[6];
					$brr['remark'] = trim($data[7]);
					$brr['comname'] = $data[8];
					$brr['ingredient'] = $data[9];
					$brr['hscode'] = $data[10];
					$brr['qtyunit'] = $data[11];
					$brr['ciqbcode'] = $data[12];
						
					//查看是否有重复商品导入
					$param['table']='goods_ciq_records';
					$param['field']='gcode';
					$param['value']=$brr['gcode'];
					$row = Db::getRow($param,"*" );
					
					if(!empty($row))
					{
						$regcodes .= $brr['gcode'].'；';
						continue;						
					}else
					{
						Db::insert('goods_ciq_records',$brr);
					}
	
				}
				 
				fclose($file);
	
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				if(!empty($regcodes)){
					echo "<script>alert('导入成功，以下产品重复未导入：".$regcodes."');</script>";
				}else{
					echo "<script>alert('导入成功')</script>";
				}
				return ;
			}else{					
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>alert('上传出错')</script>";
				return ;
			}
		}
	}
	
	/*
	 * 商检商品备案，导出为excel
	 */
	public  function get_xls($lists){
	
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=export_data.xls");
	
		 
		echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:x="urn:schemas-microsoft-com:office:excel"
        xmlns:dt="uuid:C2F41010-65B3-11d1-A29F-00AA00C14882"
        xmlns="http://www.w3.org/TR/REC-html40">
	
        <head>
        <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
        <meta name=ProgId content=Excel.Sheet>
        <meta name=Generator content="Microsoft Excel 11">
        <link rel=File-List href="订单信息模板v1.files/filelist.xml">
        <link rel=Edit-Time-Data href="订单信息模板v1.files/editdata.mso">
        <link rel=OLE-Object-Data href="订单信息模板v1.files/oledata.mso">
        <!--[if gte mso 9]><xml>
         <o:DocumentProperties>
          <o:Author>hplg1</o:Author>
          <o:LastAuthor>user</o:LastAuthor>
          <o:Created>2015-02-14T02:23:19Z</o:Created>
          <o:LastSaved>2015-03-20T03:31:02Z</o:LastSaved>
          <o:Version>11.9999</o:Version>
         </o:DocumentProperties>
         <o:CustomDocumentProperties>
          <o:KSOProductBuildVer dt:dt="string">2052-9.1.0.4984</o:KSOProductBuildVer>
         </o:CustomDocumentProperties>
        </xml><![endif]-->
        <style>
        <!--table
                {mso-displayed-decimal-separator:"\.";
                mso-displayed-thousand-separator:"\,";}
        @page
                {margin:1.0in .75in 1.0in .75in;
                mso-header-margin:.51in;
                mso-footer-margin:.51in;
                mso-page-orientation:landscape;}
        tr
                {mso-height-source:auto;
                mso-ruby-visibility:none;}
        col
                {mso-width-source:auto;
                mso-ruby-visibility:none;}
        br
                {mso-data-placement:same-cell;}
        .style0
                {mso-number-format:General;
                text-align:general;
                vertical-align:middle;
                white-space:nowrap;
                mso-rotate:0;
                mso-background-source:auto;
                mso-pattern:auto;
                color:windowtext;
                font-size:12.0pt;
                font-weight:400;
                font-style:normal;
                text-decoration:none;
                font-family:宋体;
                mso-generic-font-family:auto;
                mso-font-charset:134;
                border:none;
                mso-protection:locked visible;
                mso-style-name:常规;
                mso-style-id:0;}
        td
                {mso-style-parent:style0;
                padding-top:1px;
                padding-right:1px;
                padding-left:1px;
                mso-ignore:padding;
                color:windowtext;
                font-size:12.0pt;
                font-weight:400;
                font-style:normal;
                text-decoration:none;
                font-family:宋体;
                mso-generic-font-family:auto;
                mso-font-charset:134;
                mso-number-format:General;
                text-align:general;
                vertical-align:middle;
                border:none;
                mso-background-source:auto;
                mso-pattern:auto;
                mso-protection:locked visible;
                white-space:nowrap;
                mso-rotate:0;}
        .xl65
                {mso-style-parent:style0;
                text-align:center;
                border:.5pt solid windowtext;}
        .xl66
                {mso-style-parent:style0;
                border:.5pt solid windowtext;}
        .xl67
                {mso-style-parent:style0;
                mso-number-format:"General Date";
                border:.5pt solid windowtext;}
        .xl68
                {mso-style-parent:style0;
                text-align:center;}
        .xl69
                {mso-style-parent:style0;
                font-size:11.0pt;
                font-weight:700;
                text-align:center;
                border:.5pt solid windowtext;
                background:silver;
                mso-pattern:auto none;}
        .xl70
                {mso-style-parent:style0;
                font-size:14.0pt;
                text-align:center;}
        ruby
                {ruby-align:left;}
        rt
                {color:windowtext;
                font-size:9.0pt;
                font-weight:400;
                font-style:normal;
                text-decoration:none;
                font-family:宋体;
                mso-generic-font-family:auto;
                mso-font-charset:134;
                mso-char-type:none;
                display:none;}
        -->
        </style>
        <!--[if gte mso 9]><xml>
         <x:ExcelWorkbook>
          <x:ExcelWorksheets>
           <x:ExcelWorksheet>
            <x:Name>Sheet1</x:Name>
            <x:WorksheetOptions>
             <x:DefaultRowHeight>285</x:DefaultRowHeight>
             <x:StandardWidth>2304</x:StandardWidth>
             <x:Print>
              <x:ValidPrinterInfo/>
              <x:PaperSizeIndex>9</x:PaperSizeIndex>
              <x:Scale>75</x:Scale>
              <x:HorizontalResolution>600</x:HorizontalResolution>
              <x:VerticalResolution>600</x:VerticalResolution>
             </x:Print>
             <x:PageBreakZoom>100</x:PageBreakZoom>
             <x:Selected/>
             <x:Panes>
              <x:Pane>
               <x:Number>3</x:Number>
               <x:ActiveRow>4</x:ActiveRow>
               <x:ActiveCol>4</x:ActiveCol>
              </x:Pane>
             </x:Panes>
             <x:ProtectContents>False</x:ProtectContents>
             <x:ProtectObjects>False</x:ProtectObjects>
             <x:ProtectScenarios>False</x:ProtectScenarios>
            </x:WorksheetOptions>
           </x:ExcelWorksheet>
           <x:ExcelWorksheet>
            <x:Name>Sheet2</x:Name>
            <x:WorksheetOptions>
             <x:DefaultRowHeight>285</x:DefaultRowHeight>
             <x:StandardWidth>2304</x:StandardWidth>
             <x:Print>
              <x:ValidPrinterInfo/>
              <x:PaperSizeIndex>9</x:PaperSizeIndex>
              <x:VerticalResolution>0</x:VerticalResolution>
             </x:Print>
             <x:PageBreakZoom>100</x:PageBreakZoom>
             <x:ProtectContents>False</x:ProtectContents>
             <x:ProtectObjects>False</x:ProtectObjects>
             <x:ProtectScenarios>False</x:ProtectScenarios>
            </x:WorksheetOptions>
           </x:ExcelWorksheet>
           <x:ExcelWorksheet>
            <x:Name>Sheet3</x:Name>
            <x:WorksheetOptions>
             <x:DefaultRowHeight>285</x:DefaultRowHeight>
             <x:StandardWidth>2304</x:StandardWidth>
             <x:Print>
              <x:ValidPrinterInfo/>
              <x:PaperSizeIndex>9</x:PaperSizeIndex>
              <x:VerticalResolution>0</x:VerticalResolution>
             </x:Print>
             <x:PageBreakZoom>100</x:PageBreakZoom>
             <x:ProtectContents>False</x:ProtectContents>
             <x:ProtectObjects>False</x:ProtectObjects>
             <x:ProtectScenarios>False</x:ProtectScenarios>
            </x:WorksheetOptions>
           </x:ExcelWorksheet>
          </x:ExcelWorksheets>
          <x:WindowHeight>10350</x:WindowHeight>
          <x:WindowWidth>20730</x:WindowWidth>
          <x:WindowTopX>0</x:WindowTopX>
          <x:WindowTopY>0</x:WindowTopY>
          <x:ProtectStructure>False</x:ProtectStructure>
          <x:ProtectWindows>False</x:ProtectWindows>
         </x:ExcelWorkbook>
        </xml><![endif]-->
        </head>
	
        <body link=blue vlink=purple>
	
        <table x:str border=0 cellpadding=0 cellspacing=0 width=1305 style="border-collapse:
         collapse;table-layout:fixed;width:982pt">
         <col width=46 style="mso-width-source:userset;mso-width-alt:1472;width:35pt">
         <col width=150 style="mso-width-source:userset;mso-width-alt:4800;width:113pt">
         <col width=98 style="mso-width-source:userset;mso-width-alt:3136;width:74pt">
         <col width=82 style="mso-width-source:userset;mso-width-alt:2624;width:62pt">
         <col width=179 style="mso-width-source:userset;mso-width-alt:5728;width:134pt">
         <col width=82 style="mso-width-source:userset;mso-width-alt:2624;width:62pt">
         <col width=122 style="mso-width-source:userset;mso-width-alt:3904;width:92pt">
         <col width=129 style="mso-width-source:userset;mso-width-alt:4128;width:97pt">
         <col width=115 style="mso-width-source:userset;mso-width-alt:3680;width:86pt">
         <col width=151 style="mso-width-source:userset;mso-width-alt:4832;width:113pt">
         <col width=73 style="mso-width-source:userset;mso-width-alt:2336;width:55pt">
         <col width=78 style="mso-width-source:userset;mso-width-alt:2496;width:59pt">
         <tr height=19 style="height:14.25pt">
          <td height=19 class=xl69 style="height:14.25pt">商品名称（必填）	</td>
          <td class=xl69 style="border-left:none">规格型号（必填）</td>
          <td class=xl69 style="border-left:none">商品HS编码（必填）</td>
          <td class=xl69 style="border-left:none">计量单位(最小，必填)</td>
          <td class=xl69 style="border-left:none">商品条形码</td>
          <td class=xl69 style="border-left:none">商品描述</td>
          <td class=xl69 style="border-left:none">备注</td>
          <td class=xl69 style="border-left:none">生产企业名称</td>
          <td class=xl69 style="border-left:none">品牌（必填）</td>
          <td class=xl69 style="border-left:none">原产国/地区（必填）</td>     
         </tr>
          ';
		
		foreach($lists as $key=>$val){
			 
			echo'<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                           <td height=121 class=xl26 style=\'height:90.75pt\' x:num>'.$val['gname'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['spec'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['hscode'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['qtyunit'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['gbarcode'].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["goodsdesc"].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["remark"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["comname"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["brand"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["assemcountry"].'</td>
                 </tr>';
		}
		echo '
        </table>
	
        </body>
	
        </html>';
	
	}

}
