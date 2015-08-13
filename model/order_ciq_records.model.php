<?php

defined('haipinlegou') or exit('Access Invalid!');
class order_ciq_recordsModel extends Model {
	
	public function __construct(){
	
		parent::__construct('orders_ciq_records');
	}
	
	public function getOrderList($param,$obj_page='',$field='*'){
		$array		= array();
		$condition_str	= $this->getCondition($param);
		$array['table']		= 'orders_ciq_records';
		$array['where'] 	= $condition_str;
		$array['order'] 	= " ".(empty($param['order'])?" id DESC ":$param['order']);
		$array['field']   = $field;
		$array['limit'] = $param['limit'];
		$order_list	= Db::select($array,$obj_page);
		return $order_list;
	}
	
	public function getOrderGoodList($orderid=""){
		
		$array['table'] = 'orders_ciq_records_goods,goods_ciq_records';
		
		$array['field']	= 'orders_ciq_records_goods.ordercode as ordercode, 
						   orders_ciq_records_goods.gcode as gcode, 
						   orders_ciq_records_goods.qty as qty, 
						   orders_ciq_records_goods.upric as upric, 
						   orders_ciq_records_goods.dectotal as dectotal, 
						   goods_ciq_records.ciqgoodsno as ciqgoodsno, 
						   goods_ciq_records.hscode as hscode, 
						   goods_ciq_records.gname as gname, 
						   goods_ciq_records.brand as brand, 
						   goods_ciq_records.spec as spec, 
						   goods_ciq_records.assemcountry as assemcountry, 
						   goods_ciq_records.qtyunit as qtyunit, 
						   goods_ciq_records.goodsdesc as goodsdesc ';
		
		$array['join_type'] = 'LEFT JOIN';
		
		$array['join_on'] = array(
		
				'orders_ciq_records_goods.gcode = goods_ciq_records.gcode'
		
		);
		
		$array['where'] 	= " orders_ciq_records_goods.ordercode = '".$orderid."'";
		$array['order'] 	= " goods_ciq_records.gcode DESC ";
		$order_list	= Db::select($array);
		
		return $order_list;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';	
			
		if($condition_array['ordercode'] != ''){
			$condition_sql	.= " and ordercode = '".$condition_array['ordercode']."'";
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
	
	public function create_item($row)
	{
		if(!empty($row))
		{
			$items="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
				
			$items.="<ROOT>\r\n";
				
			/*表头*/
			$items.="<Head>\r\n";
				
			/*报文编号*/
			$items.="<MessageID>". $row['ciqmessageid'] ."</MessageID>\r\n";
				
			/*报文类型，默认661101*/
			$items.="<MessageType>661101</MessageType>\r\n";
				
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
				
			$items.="<swbebtrade>\r\n";
				
			$items.="<Record>\r\n";
				
			/*订单号*/
			$items.="<EntInsideNo>". $row['ordercode'] ."</EntInsideNo>\r\n";
				
			/*商检机构代码*/
			$items.="<Ciqbcode>". $row['ciqbcode'] ."</Ciqbcode>\r\n";
				
			/*电商企业备案编号*/
			$items.="<CbeComcode>". $row['ent_ciq_num'] ."</CbeComcode>\r\n";

			/*电商平台备案编号*/
			$items.="<CbepComcode>". $row['entweb_ciq_num'] ."</CbepComcode>\r\n";

			/*订单状态*/
			$items.="<OrderStatus>". $row['ciqstatus'] ."</OrderStatus>\r\n";

			/*收货人*/
			$items.="<ReceiveName>". $row['recipientname'] ."</ReceiveName>\r\n";

			/*收货人地址*/
			$items.="<ReceiveAddr>". $row['recipientaddress'] ."</ReceiveAddr>\r\n";

			/*收货人证件号*/
			$items.="<ReceiveNo>". $row['recipientcredno'] ."</ReceiveNo>\r\n";

			/*收货人电话*/
			$items.="<ReceivePhone>". $row['recipienttel'] ."</ReceivePhone>\r\n";

			/*订单总货款*/
			$items.="<FCY>". $row['fcy'] ."</FCY>\r\n";

			/*币种*/
			$items.="<Fcode>". $row['fcode'] ."</Fcode>\r\n";
				
			/*制单企业编号*/
			$items.="<Editccode>". $row['ent_ciq_num'] ."</Editccode>\r\n";
				
			/*订单日期*/
			$items.="<DrDate>". $row['orderdate'] ."</DrDate>\r\n";
				
			/*商品列表*/
			$items.="<swbebtradeg>\r\n";
				
			/*商品*/
			foreach($row['goods'] as $rowk => $rowg){
				
			$items.="<Record>\r\n";
			
			/*商品序号*/
			$items.="<EntGoodsNo>". ($rowk+1) ."</EntGoodsNo>\r\n";
			
			/*商品货号*/
			$items.="<Gcode>". $rowg['gcode'] ."</Gcode>\r\n";
			
			/*商品HSCODE*/
			$items.="<Hscode>". $rowg['hscode'] ."</Hscode>\r\n";
				
			/*商品，商检备案号*/
			$items.="<CiqGoodsNo>". $rowg['ciqgoodsno'] ."</CiqGoodsNo>\r\n";
				
			/*商品名称*/
			$items.="<CopGName>". $rowg['gname'] ."</CopGName>\r\n";
				
			/*商品品牌*/
			$items.="<Brand>". $rowg['brand'] ."</Brand>\r\n";
			
			/*商品规格型号*/
			$items.="<Spec>". $rowg['spec'] ."</Spec>\r\n";

			/*商品产地*/
			$items.="<Origin>". $rowg['assemcountry'] ."</Origin>\r\n";

			/*商品，订单数量*/
			$items.="<Qty>". $rowg['qty'] ."</Qty>\r\n";

			/*商品，单位*/
			$items.="<QtyUnit>". $rowg['qtyunit'] ."</QtyUnit>\r\n";

			/*商品，单价*/
			$items.="<DecPrice>". $rowg['upric'] ."</DecPrice>\r\n";

			/*商品，总价*/
			$items.="<DecTotal>". $rowg['dectotal'] ."</DecTotal>\r\n";

			/*商品，销售地址*/
			$items.="<SellWebSite>". urlencode($rowg['goodsdesc']) ."</SellWebSite>\r\n";

			/*商品，备注，默认商品货号*/
			$items.="<Nots>". $rowg['gcode'] ."</Nots>\r\n";
				
			$items.="</Record>\r\n";
			
			}
				
			$items.="</swbebtradeg>\r\n";
				
			$items.="</Record>\r\n";
	
			$items.="</swbebtrade>\r\n";
	
			$items.="</Body>\r\n";
				
			$items.="</ROOT>\r\n";
	
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
	
}
?>
