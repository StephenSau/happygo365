<?php

defined('haipinlegou') or exit('Access Invalid!');
class orderModel{

	private $product_sn;	

	
	public function addOrder($param) {
		if(is_array($param) and !empty($param)) {
			return Db::insert('order',$param);
		} else {
			return false;
		}
	}
	
	public function addAddressOrder($param) {
		if(is_array($param) and !empty($param)) {
			return Db::insert('order_address',$param);
		} else {
			return false;
		}
	}
	
	public function addGoodsOrder($param) {
		if(is_array($param) and !empty($param)) {
			return Db::insert('order_goods',$param);
		} else {
			return false;
		}
	}
	
	public function OrderGoodsList($param,$obj_page='',$field='*') {
		$array		= array();
		$condition_str	= $this->getCondition($param);
		$array['table']		= 'order,order_goods,store,order_address';
		$array['field']		= '`order`.*,`order_goods`.*,`store`.*,`order_address`.*';
		$array['join_type']	= 'LEFT JOIN';
		$array['join_on']	= array('order.order_id=order_goods.order_id','order.store_id=store.store_id','order.order_id=order_address.order_id');
		$array['where'] 	= $condition_str;
		$array['order'] 	= " {$param['order']}order.add_time DESC\n";
		$order_list	= Db::select($array,$obj_page);
		return $order_list;
	}
	//我的商场订单评价
	public function OrderGoodsList_two($param,$obj_page='',$field='*') {
		$array		= array();
		$condition_str	= $this->getCondition($param);
		$array['table']		= 'order,order_goods,store,order_address,goods';
		$array['field']		= '`order`.*,`order_goods`.*,`store`.*,`order_address`.*,`goods`.*';
		$array['join_type']	= 'LEFT JOIN';
		$array['join_on']	= array('order.order_id=order_goods.order_id','order.store_id=store.store_id','order.order_id=order_address.order_id','order_goods.goods_id = goods.goods_id');
		$array['where'] 	= $condition_str;
		$array['order'] 	= " {$param['order']}order.add_time DESC\n";
		$order_list	= Db::select($array,$obj_page);
		return $order_list;
	}
	
	public function OrderCount($param) {
		$array		= array();
		$condition_str	= $this->getCondition($param);
		$ordercount	= Db::getCount('order',$condition_str);
		return $ordercount;
	}
	
	public function OrderGoodsRowByID($id) {
		if (intval($id) > 0){
			$array = array();
			$array['table'] = 'order_goods';
			$array['field'] = 'rec_id';
			$array['value'] = $id;
			$result = Db::getRow($array);
			return $result;
		}else {
			return false;
		}
	}
	
	public function getOrderList($param,$obj_page='',$field='*'){
		$array		= array();
		$condition_str	= $this->getCondition($param);
		$array['table']		= 'order';
		$array['where'] 	= $condition_str;
		$array['order'] 	= " ".(empty($param['order'])?"":($param['order'].","))."`order`.add_time DESC";
		$array['field']   = $field;
        $array['limit'] = $param['limit'];
        $order_list	= Db::select($array,$obj_page);
		return $order_list;
	}
	
	public function getOrderGoodsList($param,$page='') {
		$array				= array();
		$condition_str		= $this->getCondition($param);
		$array['table']		= 'order,order_goods';
		$array['join_type']	= 'LEFT JOIN';
		$array['join_on']	= array('order.order_id=order_goods.order_id');
		$array['order'] 	= empty($param['order'])?"`order`.add_time DESC":$param['order'];
		$array['where'] 	= $condition_str;
		$order_list			= Db::select($array,$page);
		return $order_list;
	}
	
	public function addLogOrder($order_step,$order_id,$message='',$lang=array()) {
		Language::read('model_lang_index');
		$lang	= Language::getLangContent();
		$log_array	= array();
		switch ($order_step) {
			case 10:
				$log_array['order_state']	= $lang['order_state_submitted'];
				$log_array['change_state']	= $lang['order_state_pending_payment'];
				break;
			case 11:
				$log_array['order_state']	= $lang['order_state_submitted'];
				$log_array['change_state']	= $lang['order_state_pending_payment'];
				break;
			case 0:
				$log_array['order_state']	= $lang['order_state_canceled'];
				$log_array['change_state']	= $lang['order_state_null'];
				break;
			case 20:
				$log_array['order_state']	= $lang['order_state_paid'];
				$log_array['change_state']	= $lang['order_state_to_be_shipped'];
				break;
			case 30:
				$log_array['order_state']	= $lang['order_state_shipped'];
				$log_array['change_state']	= $lang['order_state_be_receiving'];
				break;
			case 40:
				$log_array['order_state']	= $lang['order_state_completed'];
				$log_array['change_state']	= $lang['order_state_to_be_evaluated'];
				break;
		      case 50:
		        $log_array['order_state'] = $lang['order_state_submitted'];
		        $log_array['change_state'] = $lang['order_state_to_be_confirmed'];
		        break;
		      case 60:
		        $log_array['order_state'] = $lang['order_state_confirmed'];
		        $log_array['change_state'] = $lang['order_state_to_be_shipped'];
		        break;
					default:
						$log_array['order_state']	= $lang['order_state_unknown'];
						$log_array['change_state']	= $lang['order_state_unknown'];
				}
		
		$log_array['order_id']		= $order_id;
		$log_array['state_info']	= is_null($message) ? '' : $message;
		$log_array['log_time']		= time();
		$log_array['operator']		= $_SESSION['member_name'];
		return $this->addOrderLog($log_array);
	}
	
	public function addOrderLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('order_log',$param);
		return $result;
	}
	
	public function updateOrder($param,$order_id) {
		if(is_array($param) and !empty($param)) {
			$where = " order_id = '$order_id'";
			return Db::update('order',$param,$where);
		} else {
			return false;
		}
	}
	
	public function updateOrderGoods($param,$rec_id) {
		if(is_array($param) and !empty($param)) {
			$where = " rec_id = '$rec_id'";
			return Db::update('order_goods',$param,$where);
		} else {
			return false;
		}
	}
	
	public function updateOrderAddress($param,$order_id) {
		if(is_array($param) and !empty($param)) {
			$where = " order_id = '$order_id'";
			return Db::update('order_address',$param,$where);
		} else {
			return false;
		}
	}
	
	public function orderLoglist($order_id) {
		$array		= array();
		$array['table']		= 'order_log';
		$array['where'] 	= "where order_id='$order_id'";
		$array['order']     = 'log_id';
		$log_list	= Db::select($array);
		return $log_list;
	}
	
	public function snOrder() {
		$this->product_sn = date('Ymd').substr( implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
		return $this->product_sn;
	}
	
	public function outSnOrder() {
		if($this->product_sn) {
			return $this->product_sn;
		}
	}
	
	public function getOrderById($order_id,$type='all',$condition=array()){
		$param	= array();
		$condition_str	= $this->getCondition($condition);
		switch($type){
			case 'all':
				$param['table']	= 'order,order_address';
				$param['join_type']	= 'inner join';
				$param['join_on']	= array('`order`.order_id=`order_address`.order_id');
				$param['cache'] = false;
				break;
			case 'simple':
				$param['table']	= 'order';
				break;
			default:
				break;
		}
		$param['where']	= 'where `order`.order_id='.$order_id.$condition_str;
		$order_list	= Db::select($param);
		return $order_list[0];
	}

	
    public function checkOrderBelongStore($order_id,$store_id){

        $order_info = self::getOrderById($order_id,$type='simple');
        if(!empty($order_info) && intval($order_info['store_id']) === intval($store_id)) {
            return true;
        }
        else {
            return false;
        }
    }

	
	public function getOrderByOutSn($out_sn,$type='all'){
		$param	= array();
		switch($type){
			case 'all':
				$param['table']	= 'order,order_address';
				$param['join_type']	= 'inner join';
				$param['join_on']	= array('`order`.order_id=`order_address`.order_id');
				break;
			case 'simple':
				$param['table']	= 'order';
				break;
			default:
				break;
		}
		$param['where']	= "where `order`.out_sn='".$out_sn."'";
		$order_list	= Db::select($param);
		return $order_list[0];
	}
	
	public function dropOrderByOrder($order_id_str) {
		if(empty($order_id_str)) {
			return false;
		}
		$order_id = explode(',',$order_id_str);
		foreach ($order_id as $v){
			$where = " order_id = '". intval($v) ."'";
			$result = Db::delete('order',$where);
			$where = " order_id = '". intval($v) ."'";
			$result = Db::delete('order_goods',$where);
		}

		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if($condition_array['store_id'] != ''){
			$condition_sql	.= " and `order`.store_id='{$condition_array['store_id']}'";
		}
		if(is_array($condition_array['examine']))
		{

			foreach($condition_array['examine'] as $k=>$v)
			{
				$arr .= "'".$v."',";
			}
			$arr = substr($arr,0,-1);

			$condition_sql	.= " and `order`.examine in(".$arr.") ";
			
		}else if($condition_array['examine'] !='')
		{
			$condition_sql	.= " and `order`.examine = '{$condition_array['examine']}'";
		}
		if(is_array($condition_array['send_examine']))
		{
			
			foreach($condition_array['send_examine'] as $k=>$v)
			{
				$brr .= "'".$v."',";
			}
			$brr = substr($brr,0,-1);

			$condition_sql	.= " and `order`.send_examine in(".$brr.") ";
			
		}else if($condition_array['send_examine'] !='')
		{
			$condition_sql	.= " and `order`.send_examine = '{$condition_array['send_examine']}'";
		}		
		if(is_array($condition_array['entry_examine']))
		{
			
			foreach($condition_array['entry_examine'] as $k=>$v)
			{
				$arr .= "'".$v."',";
			}
			$arr = substr($arr,0,-1);

			$condition_sql	.= " and `order`.entry_examine in(".$arr.") ";
			
		}else if($condition_array['entry_examine'] !='')
		{
			$condition_sql	.= " and `order`.entry_examine = '{$condition_array['entry_examine']}'";
		}		
		if(is_array($condition_array['deliver']))
		{
			
			foreach($condition_array['deliver'] as $k=>$v)
			{
				$arr .= "'".$v."',";
			}
			$arr = substr($arr,0,-1);

			$condition_sql	.= " and `order`.deliver in(".$arr.") ";
			
		}else if($condition_array['deliver'] !='')
		{
			$condition_sql	.= " and `order`.deliver = '{$condition_array['deliver']}'";
		}
		/*
		if($condition_array['deliver'] !=''){
			$condition_sql	.= " and `order`.deliver='{$condition_array['deliver']}'";
		}*/
		if($condition_array['change_a'] !=''){
			$condition_sql	.= " and `order`.change_a='{$condition_array['change_a']}'";
		}					
		if(!empty($condition_array['group_id'])) {
			$condition_sql	.= " and order.group_id ='{$condition_array['group_id']}'";
		}
		if($condition_array['buyer_id'] != '') {
			$condition_sql	.= " and order.buyer_id='{$condition_array['buyer_id']}'";
		}
		if($condition_array['seller_id'] != '') {
			$condition_sql	.= " and order.seller_id='{$condition_array['seller_id']}'";
		}
		if($condition_array['evaluation_status'] != ''){
			$condition_sql	.= " and `order`.evaluation_status = '{$condition_array['evaluation_status']}'";
		}
		if($condition_array['evalseller_status'] != ''){
			$condition_sql	.= " and `order`.evalseller_status = '{$condition_array['evalseller_status']}'";
		}
		if($condition_array['order_id'] != ''){
			$condition_sql	.= " and `order`.order_id='{$condition_array['order_id']}'";
		}
		if($condition_array['order_ids'] != ''){
			$condition_sql	.= " and `order`.order_id in(".$condition_array['order_ids'].")";
		}
		if($condition_array['store_name'] != ''){
			$condition_sql	.= " and `order`.store_name like '%".$condition_array['store_name']."%'";
		}
		if($condition_array['buyer_name'] != ''){
			$condition_sql	.= " and `order`.buyer_name like '%".$condition_array['buyer_name']."%'";
		}
		if($condition_array['payment_name'] != ''){
			$condition_sql	.= " and `order`.payment_name like '%".$condition_array['payment_name']."%'";
		}
		if($condition_array['order_sn'] != ''){
			$condition_sql	.= " and `order`.order_sn like '%".$condition_array['order_sn']."%'";
		}
		if($condition_array['in_order_sn'] != ''){
			$condition_sql	.= " and `order`.order_sn in (".$condition_array['in_order_sn'].")";
		}
		if($condition_array['status'] != ''){
			$condition_sql	.= " and `order`.order_state='{$condition_array['status']}'";
		}
		if($condition_array['status_no'] != ''){
			$condition_sql	.= " and `order`.order_state > 0";
		}
		if($condition_array['add_time_from'] != ''){
			$condition_sql	.= " and `order`.add_time >= '{$condition_array['add_time_from']}'";
		}
		if($condition_array['add_time_to'] != ''){
			$condition_sql	.= " and `order`.add_time <= '{$condition_array['add_time_to']}'";
		}
		if($condition_array['order_amount_from'] != ''){
			$condition_sql	.= " and `order`.order_amount >= '{$condition_array['order_amount_from']}'";
		}
		if($condition_array['order_amount_to'] != ''){
			$condition_sql	.= " and `order`.order_amount <= '{$condition_array['order_amount_to']}'";
		}
		if($condition_array['order_state'] == 'order_cancal') {
			$condition_sql	.= ' and order.order_state=0';
		}
		if($condition_array['order_state'] == 'order_submit') {
			$condition_sql	.= ' and order.order_state=10';
		}
		if($condition_array['order_state'] == 'order_pay') {
			$condition_sql	.= ' and order.order_state>0 and order.order_state<20';
		}
		if($condition_array['order_state'] == 'order_no_shipping') {
			$condition_sql	.= ' and order.order_state=20';
		}
		if($condition_array['order_state'] == 'order_shipping') {
			$condition_sql	.= ' and order.order_state=30';
		}
		if($condition_array['order_state'] == 'order_finish') {
			$condition_sql	.= ' and order.order_state=40';
		}
		if($condition_array['refund_state'] == 'able_evaluate') {
			$condition_sql	.= ' and order.refund_state<2';
		}
        if($condition_array['order_state'] == 'order_refer') {
            $condition_sql .= ' and order.order_state=50';
        }
        if($condition_array['order_state'] == 'order_confirm') {
            $condition_sql .= ' and order.order_state=60';
        }
        if($condition_array['order_state']=='order_pay_finish'){
            $condition_sql .= ' and order.order_state>=20';
        }
		if($condition_array['`order`.store_id'] > 0) {
			$condition_sql	.= " and `order`.store_id='{$condition_array['`order`.store_id']}'";
		}
		if($condition_array['`order`.evaluation_status'] > 0) {
			$condition_sql	.= " and `order`.evaluation_status='{$condition_array['`order`.evaluation_status']}'";
		}
		if($condition_array['`order_goods`.evaluation'] > 0) {
			$condition_sql	.= " and `order_goods`.evaluation='{$condition_array['`order_goods`.evaluation']}'";
		}
		if($condition_array['`order_goods`.goods_id'] > 0) {
			$condition_sql	.= " and `order_goods`.goods_id='{$condition_array['`order_goods`.goods_id']}'";
		}
		if($condition_array['ordergoods_goodsnamelike'] !='') {
			$condition_sql	.= " and `order_goods`.goods_name like '%{$condition_array['ordergoods_goodsnamelike']}%'";
		}
		if($condition_array['ordergoods_evaluationstate'] != '') {
			$condition_sql	.= " and `order_goods`.evaluation_state = '{$condition_array['ordergoods_evaluationstate']}' ";
		}
		if(isset($condition_array['ordergoods_evaluationstate_in'])) {
			if($condition_array['ordergoods_evaluationstate_in'] == '') {
				$condition_sql	.= " and `order_goods`.evaluation_state in ('') ";
			}else {
				$condition_sql	.= " and `order_goods`.evaluation_state in ({$condition_array['ordergoods_evaluationstate_in']})";
			}
		}
		if($condition_array['ordergoods_rec_id'] != '') {
			$condition_sql	.= " and `order_goods`.rec_id = '{$condition_array['ordergoods_rec_id']}' ";
		}
		if($condition_array['order_evalseller_able'] != '') {
			$condition_sql	.= " and order.order_state=40 and order.refund_state<2 and order.evalseller_status = 0 and (((order.finnshed_time+60*60*24*15)>".time().") or ((order.evaluation_time+60*60*24*15)>".time().")) ";
		}
		if($condition_array['order_progressing'] != '') {
			$condition_sql	.= " and order.order_state>0 and order.order_state<>40";
		}
		return $condition_sql;
	}
	
	
	public function order_record($order_id)
	{
		$order	= Model('order');
		$address = Model('address');
		$order_goods = Model('order_goods');
		$list = $order->OrderGoodsList(array('order_id'=>$order_id));
		foreach($list as $k=>$v)
		{
			//收件人信息
			$param['table'] = 'address';
			$param['field'] = 'member_id';
			$param['value'] = $v['buyer_id'];
			$address = Db::getRow( $param, "*");
			$list[$k]['card'] = $address['card'];
			$list[$k]['mob_phone'] = $address['mob_phone'];
			
			//发货人信息
			$param['table'] = 'member';
			$param['field'] = 'member_id';
			$param['value'] = $v['buyer_id'];
			$member = Db::getRow( $param, "*");
			$list[$k]['member_id_card'] = $member['member_id_card'];
			$list[$k]['member_mob_phone'] = $member['member_mob_phone'];
			$list[$k]['member_truename'] = $member['member_truename'];
			
			//订单商品信息
			$param['table'] = 'order_goods';
			$param['field'] = 'goods_id';
			$param['value'] = $v['goods_id'];
			$order_goods = Db::getRow($param, "*");
			$v['product_num'] = $order_goods['product_num'];
			$v['goods_price'] = $order_goods['goods_price'];
			$v['goods_num'] = $order_goods['goods_num'];
			$v['goods_total'] = $order_goods['goods_price']*$order_goods['goods_num'];
		}
		
		return $list;

	}	
	
	public function entry_record($order_id)
	{
		$order	= Model('order');
		$address = Model('address');
		$order_goods = Model('order_goods');
		$list = $order->OrderGoodsList(array('order_id'=>$order_id));
		foreach($list as $k=>$v)
		{
			//收件人信息
			$param['table'] = 'address';
			$param['field'] = 'member_id';
			$param['value'] = $v['buyer_id'];
			$address = Db::getRow( $param, "*");
			$list[$k]['card'] = $address['card'];
			$v['tel_phone'] = $address['tel_phone'];

			//发货人信息
			$param['table'] = 'member';
			$param['field'] = 'member_id';
			$param['value'] = $v['buyer_id'];
			$member = Db::getRow( $param, "*");
			$list[$k]['member_id_card'] = $member['member_id_card'];
			$list[$k]['member_mob_phone'] = $member['member_mob_phone'];
			$list[$k]['member_truename'] = $member['member_truename'];
			
			//订单商品信息
			$param['table'] = 'order_goods';
			$param['field'] = 'goods_id';
			$param['value'] = $v['goods_id'];
			$order_goods = Db::getRow($param, "*");
			$v['goods_price'] = $order_goods['goods_price'];
			$v['ieflag'] = $order_goods['ieflag'];
			$v['goods_num'] = $order_goods['goods_num'];
			$v['goods_apply_num'] = $order_goods['goods_apply_num'];
			$v['goods_total'] = $order_goods['goods_price']*$order_goods['goods_num'];
			
		}
		return $list;

	}
    
   //新版进口岸
      public  function get_xlsbcnew($order_list){
		
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
         <tr height=25 style="height:18.75pt">
          <td colspan=12 height=25 class=xl70 width=1305 style="height:18.75pt;
          width:982pt">订单信息表</td>
         </tr>
         <tr height=19 style="height:14.25pt">
          <td height=19 class=xl69 style="height:14.25pt">NO</td>
          <td class=xl69 style="border-left:none">报关单号</td>
          <td class=xl69 style="border-left:none">总运单号</td>
          <td class=xl69 style="border-left:none">袋号</td>
          <td class=xl69 style="border-left:none">订单编号</td>
          <td class=xl69 style="border-left:none">快件单号</td>
          <td class=xl69 style="border-left:none">发件人</td>
          <td class=xl69 style="border-left:none">发件人地址</td>
          <td class=xl69 style="border-left:none">发件人电话</td>
          <td class=xl69 style="border-left:none">收件人</td>
          <td class=xl69 style="border-left:none">收件人电话</td>
          <td class=xl69 style="border-left:none">城市</td>
          <td class=xl69 style="border-left:none">邮编</td>
          <td class=xl69 style="border-left:none">收件人地址</td>
          <td class=xl69 style="border-left:none">内件名称</td>
          <td class=xl69 style="border-left:none">数量</td>
          <td class=xl69 style="border-left:none">价值</td>
          <td class=xl69 style="border-left:none">净重</td>
          <td class=xl69 style="border-left:none">毛重(KG)</td>
          <td class=xl69 style="border-left:none">行邮税号</td>
          <td class=xl69 style="border-left:none">物品名称</td>
          <td class=xl69 style="border-left:none">品牌</td>
          <td class=xl69 style="border-left:none">规格型号</td>
          <td class=xl69 style="border-left:none">数量</td>
          <td class=xl69 style="border-left:none">单位</td>
          <td class=xl69 style="border-left:none">单价</td>
          <td class=xl69 style="border-left:none">币别</td>
          <td class=xl69 style="border-left:none">身份证件号码</td>
          <td class=xl69 style="border-left:none">运费</td>
          <td class=xl69 style="border-left:none">税金（数量*单位*税率）</td>
          <td class=xl69 style="border-left:none">发货人国别</td>
          
         </tr>
          ';
    
                $order_sn='';
                $number = 0;
                $model = Model();
                foreach($order_list as $key=>$val){ 
                    
                  $daddress = $model->table('daddress')->where(array('address_id'=>$val['daddress_id']))->find();       
                    $areainfo=explode("	",$val['area_info']);
                  echo'<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                  
                           <td height=121 class=xl26 style=\'height:90.75pt\' x:num>'.$key.'</td>
                           <td class=xl66 style="border-top:none;border-left:none"></td>
                           <td class=xl66 style="border-top:none;border-left:none"></td>
                           <td class=xl66 style="border-top:none;border-left:none"></td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["order_sn"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none"></td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$daddress['seller_name'].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$daddress['address'].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$daddress["mob_phone"].'</td>
                           
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["true_name"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["mob_phone"].'</td>
                           
                           <td class=xl66 style="border-top:none;border-left:none">'.$areainfo[1].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['zip_code'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["address"].'</td>
                           <td class=xl65 style="border-top:none;border-left:none">'.$val['goods_name'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_num"].'</td>
                           
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_price"]*$val["goods_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["net_weight"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["gross_weight"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["tax_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_name"].'</td>
                            <td class=xl66 style="border-top:none;border-left:none"></td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_format"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["declaration_unit"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_price"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">RMB</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["consignee_id_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["shipping_fee"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["ems"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_country"].'</td>
                           
                 </tr>';
           }  
        echo '
        </table>
    
        </body>
    
        </html>';
    		
} 
    
    
    
    //新版进口岸
      public  function get_xlsbc($order_list){
		
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
         <tr height=25 style="height:18.75pt">
          <td colspan=12 height=25 class=xl70 width=1305 style="height:18.75pt;
          width:982pt">订单信息表</td>
         </tr>
         <tr height=19 style="height:14.25pt">
          <td height=19 class=xl69 style="height:14.25pt">订单编号(必录)</td>
          <td class=xl69 style="border-left:none">进出口标识（必录，I:进口；E:出口）</td>
          <td class=xl69 style="border-left:none">订单状态（必录）</td>
          <td class=xl69 style="border-left:none">电商平台企业备案号（代码）（必录）</td>
          <td class=xl69 style="border-left:none">电商平台企业名称（必填）</td>
          <td class=xl69 style="border-left:none">订单人姓名（必录）</td>
          <td class=xl69 style="border-left:none">订单人证件类型</td>
          <td class=xl69 style="border-left:none">订单人证件号</td>
          <td class=xl69 style="border-left:none">订单人电话</td>
          <td class=xl69 style="border-left:none">订单商品总额（必录）</td>
          <td class=xl69 style="border-left:none">订单商品总额币制（必录，币制代码表(CURR)）</td>
          <td class=xl69 style="border-left:none">运费（必录）</td>
          <td class=xl69 style="border-left:none">运费币制（必录，币制代码表(CURR)）</td>
          <td class=xl69 style="border-left:none">税款（必录）</td>
          <td class=xl69 style="border-left:none">税款币制（必录，币制代码表(CURR)）</td>
          <td class=xl69 style="border-left:none">备注</td>
          <td class=xl69 style="border-left:none">订单日期（必录）</td>
          
         </tr>
          ';
    
                $order_sn='';
                $number = 0;
                foreach($order_list as $key=>$val){ 
                if($key>0 && $order_list[$key-1]['order_sn']==$val['order_sn']){
                    continue;
                }
                       
    
                  echo'<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                           <td height=121 class=xl26 style=\'height:90.75pt\' x:num>'.$val['order_sn'].'</td>
                            <td class=xl66 style="border-top:none;border-left:none">I</td>
                             <td class=xl66 style="border-top:none;border-left:none">S</td>
                              <td class=xl66 style="border-top:none;border-left:none">'.$val["company_num"].'</td>
                               <td class=xl66 style="border-top:none;border-left:none">'.$val["company_name"].'</td>
                                <td class=xl66 style="border-top:none;border-left:none">'.$val['member_truename'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">01</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["consignee_id_num"].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["member_mob_phone"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["order_amount"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">142</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["shipping_fee"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">142</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.($val["order_amount"]-$val["goods_amount"]-$val["shipping_fee"]).'</td>
                           <td class=xl65 style="border-top:none;border-left:none">142</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["deliver_explain"].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.date("Y-m-d h:i:s",$val["add_time"]).'</td>
                 </tr>';
           }  
        echo '
        </table>
    
        </body>
    
        </html>';
    		
} 
    //新版进口岸
      public  function get_xlsbbc($order_list){
		
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
          <col width=78 style="mso-width-source:userset;mso-width-alt:2496;width:59pt">
           <col width=78 style="mso-width-source:userset;mso-width-alt:2496;width:59pt">
         <tr height=25 style="height:18.75pt">
          <td colspan=12 height=25 class=xl70 width=1305 style="height:18.75pt;
          width:982pt">订单信息表</td>
         </tr>
         <tr height=19 style="height:14.25pt">
          <td height=19 class=xl69 style="height:14.25pt">客户代码</td>
          <td class=xl69 style="border-left:none">订单编号</td>
          <td class=xl69 style="border-left:none">订单人姓名</td>
          <td class=xl69 style="border-left:none">订单人证件类型</td>
          <td class=xl69 style="border-left:none">订单人证件号</td>
          <td class=xl69 style="border-left:none">收件人姓名</td>
          <td class=xl69 style="border-left:none">收件人电话</td>
          <td class=xl69 style="border-left:none">收件人市</td>
          <td class=xl69 style="border-left:none">收件人地址</td>
          <td class=xl69 style="border-left:none">订单商品总额(RMB)</td>
          <td class=xl69 style="border-left:none">运费(RMB)</td>
          <td class=xl69 style="border-left:none">税款(RMB)</td>
          <td class=xl69 style="border-left:none">备注</td>
          <td class=xl69 style="border-left:none">订单时间</td>
          <td class=xl69 style="border-left:none">商品海关备案号</td>
          <td class=xl69 style="border-left:none">销售单价(RMB)</td>
          <td class=xl69 style="border-left:none">数量</td>
          <td class=xl69 style="border-left:none">计量单位</td>
          <td class=xl69 style="border-left:none">支付交易号</td>
         </tr>
          ';
    
      
          
                $number = 0;
                foreach($order_list as $key=>$val){ 
    
                        
        
                  $areainfo=explode("	",$val['area_info']);
                  echo'<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                           <td height=121 class=xl26 style=\'height:90.75pt\' x:num></td>
                            <td class=xl66 style="border-top:none;border-left:none">'.$val['order_sn'].'</td>
                             <td class=xl66 style="border-top:none;border-left:none">'.$val['member_truename'].'</td>
                              <td class=xl66 style="border-top:none;border-left:none">身份证</td>
                               <td class=xl66 style="border-top:none;border-left:none">'.$val['consignee_id_num'].'</td>
                                <td class=xl66 style="border-top:none;border-left:none">'.$val['true_name'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['mob_phone'].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$areainfo[1].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["area_info"].$val["address"].'</td>
                           
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_amount"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["shipping_fee"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$order_list[0]["total_ems"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["deliver_explain"].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.date("Y-m-d h:i:s",$val["add_time"]).'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_custom_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_price"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["goods_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["declaration_unit"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["payno"].'</td>
                 </tr>';
           }  
        echo '
        </table>
    
        </body>
    
        </html>';
    		
} 

 public  function get_xlsgoods($order_list){
		
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
         <tr height=25 style="height:18.75pt">
          <td colspan=12 height=25 class=xl70 width=1305 style="height:18.75pt;
          width:982pt">订单信息表</td>
         </tr>
         <tr height=19 style="height:14.25pt">
          <td height=19 class=xl69 style="height:14.25pt">订单编号(必录)</td>
          <td class=xl69 style="border-left:none">商品序号（必录）</td>
          <td class=xl69 style="border-left:none">子订单编号</td>
          <td class=xl69 style="border-left:none">电商商户企业备案号（必录）</td>
          <td class=xl69 style="border-left:none">电商商户企业名称（必填）</td>
          <td class=xl69 style="border-left:none">商品海关备案号（必录）</td>
          <td class=xl69 style="border-left:none">商品单价（必录，RMB金额（元））</td>
          <td class=xl69 style="border-left:none">计量单位（必录）</td>
          <td class=xl69 style="border-left:none">商品数量（必录）</td>
          <td class=xl69 style="border-left:none">商品总价（必录）</td>
          <td class=xl69 style="border-left:none">备注</td>
         
         </tr>
          ';
    
                $order_sn='';
                $number = 0;
                foreach($order_list as $key=>$val){ 
               
                   echo'<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                           <td height=121 class=xl26 style=\'height:90.75pt\' x:num>'.$val['order_sn'].'</td>
                            <td class=xl66 style="border-top:none;border-left:none">'.$val['product_num'].'</td>
                             <td class=xl66 style="border-top:none;border-left:none">'.$val['order_sn'].'</td>
                              <td class=xl66 style="border-top:none;border-left:none">'.$val['company_num'].'</td>
                               <td class=xl66 style="border-top:none;border-left:none">'.$val['company_name'].'</td>
                                <td class=xl66 style="border-top:none;border-left:none">'.$val['goods_custom_num'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['goods_price'].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["declaration_unit"].'</td>
                           <td class=xl67 style="border-top:none;border-left:none">'.$val["goods_num"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["order_amount"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["deliver_explain"].'</td>
                 </tr>';
           }  
        echo '
        </table>
    
        </body>
    
        </html>';
    		
} 
    
	
	public function get_xml($list,$n=0)
	{
		$item = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$item.="<Manifest>\n";
			  $item.="<Head>\n";
			    /*报文编号*/
				$item.="<MessageID>880020".date('YmdHis').rand(0000,9999)."</MessageID>\n";
				/*业务类型*/
				$item.="<FunctionCode>0</FunctionCode>\n";
				/*报文类型*/
				$item.="<MessageType>880020</MessageType>\n";
				/*接入企业备案号*/
				$item.="<SenderID>".$list[0]['company_num']."</SenderID>\n";
				/*报文日期*/
				$item.="<SendTime>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</SendTime>\n";
				/*版本 默认为1.0*/
				$item.="<Version>1.0 </Version>\n";
			  $item.="</Head>\n";
			  $item.="<Declaration>\n";
				$item.="<EOrder>\n";
					/*订单编号*/
					$item.="<OrderId>".$list[0]['order_sn']."</OrderId>\n";
					/*进出口标识*/
					$item.="<IEFlag>".$list[0]['ieflag']."</IEFlag>\n";
					/*订单状态 S-订单新增，C-订单取消*/
					if($list[0]['order_state '] == 20)
					{
						$item.="<OrderStatus>S</OrderStatus>\n";
					}else if($list[0]['order_state '] == 0)
					{
						$item.="<OrderStatus>S</OrderStatus>\n";
					}
					/*电商平台企业备案号（代码）*/
					$item.="<EntRecordNo>".$list[0]['company_num']."</EntRecordNo>\n";
					/*电商平台企业名称*/
					$item.="<EntRecordName>".$list[0]['company_name']."</EntRecordName>\n";
					/*订单人姓名*/
					//$item.="<OrderName>".$list[0]['buyer_name']."</OrderName>\n";
					$item.="<OrderName>".$list[0]['member_truename']."</OrderName>\n";
					/*订单人证件类型*/
					$item.="<OrderDocType>01</OrderDocType>\n";
					/*订单人证件号*/
					$item.="<OrderDocId>".$list[0]['member_id_card']."</OrderDocId>\n";
					/*订单人电话*/
					$item.="<OrderPhone>".$list[0]['member_mob_phone']."</OrderPhone>\n";
					/*订单商品总额*/
					$item.="<OrderGoodTotal>".$list[0]['order_amount']."</OrderGoodTotal>\n";
					/*订单商品总额币制*/
					$item.="<OrderGoodTotalCurr>142</OrderGoodTotalCurr>\n";
					/*运费*/
					$item.="<Freight>".$list[0]['shipping_fee']."</Freight>\n";
					/*运费币制*/
					$item.="<FreightCurr>142</FreightCurr>\n";
					/*税款*/
					$tax=0;
					foreach($list as $k=>$v)
					{
						$tax +=$v['ems'];
					}
					$item.="<Tax>".$tax."</Tax>\n";
					/*税款币制*/
					$item.="<TaxCurr>142</TaxCurr>\n";
					/*备注*/
					$item.="<Note>".$list[0]['deliver_explain']."</Note>\n";	
					/*订单日期，精确到秒*/
					$item.="<OrderDate>".date("Y-m-d H:i:s",$list[0]['add_time'])."</OrderDate>\n";
				$item.="</EOrder>\n";
				$item.="<EOrderGoods>\n";
				foreach($list as $k=>$v)
				{
					$item.="<EOrderGood>\n";
						/*商品序号*/
						$item.="<GNo>".$v['product_num']."</GNo>\n";
						/*子订单编号*/
						$item.="<ChildOrderNo></ChildOrderNo>\n";
						/*电商商户企业备案号*/
						$item.="<StoreRecordNo>".$v['company_num']."</StoreRecordNo>\n";
						/*电商商户企业名称*/
						$item.="<StoreRecordName>".$v['company_name']."</StoreRecordName>\n";
						/*商品海关备案号*/
						$item.="<CustomsListNO >".$v['goods_custom_num']."</CustomsListNO > \n";
						/*商品单价*/
						$item.="<DecPrice>".$v['goods_price']."</DecPrice>\n";
						/*计量单位*/
						$item.="<Unit>".$v['declaration_unit']."</Unit>\n";
						/*商品数量*/
						$item.="<GQty>".($v['goods_num']*$v['store_base'])."</GQty>\n";
						/*商品总价*/
						if($list[0]['total_ems'] > 50)
						{
						
						$item.="<DeclTotal>".((floatval($v['goods_price'])*$v['goods_num']*$v['store_base'])+ floatval($v['ems']))."</DeclTotal>\n";
					
						}else{

						$item.="<DeclTotal>".(floatval($v['goods_price'])*$v['goods_num']*$v['store_base'])."</DeclTotal>\n";
					
						}
						/*备注*/
						$item.="<Notes>".$v['deliver_explain']."</Notes>\n";
					$item.="</EOrderGood>\n";							
				}				
	

				$item.="</EOrderGoods>\n";
			 $item.="</Declaration>\n";
			$item.="</Manifest>";
			
			return $item;
	}
	
	
	public function get_entry_xml($row,$n=0)
	{
		$item = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

		$item.="<Manifest>\n";

		  $item.="<Head>\n";
            /*报文编号*/
			$item.="<MessageID>881104".date('YmdHis').rand(0000,9999)."</MessageID>\n";
            /*业务类型（默认为0）*/
			$item.="<FunctionCode>0</FunctionCode>\n";
			/*报文类型 （默认为881104）*/
			$item.="<MessageType>881104</MessageType>\n";
			/*平台企业备案号*/
			$item.="<SenderID>".$row[0]['company_num']."</SenderID>\n";
			/*平台企业备案号*/
			$item.="<ReceiverID>".$row[0]['company_num']."</ReceiverID>\n";
			/*发送时间*/
			$item.="<SendTime>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</SendTime>\n";
			/*版本 默认为1.0*/
			$item.="<Version>1.0</Version>\n";

		  $item.="</Head>\n";

		  $item.="<Declaration>\n";

			$item.="<EBill>\n";
				/*企业内部编号*/
				$item.="<EntInsideNo>".$row[0]['company_inside_num']."</EntInsideNo>\n";
				/*申报地海关*/
				$item.="<MasterCustoms>".$row[0]['apply_custom']."</MasterCustoms>\n";
				/*账册编号*/
				$item.="<EmsNo>".$row[0]['zhangce_num']."</EmsNo>\n";		
				/*收件人姓名*/
				$item.="<ReceiveName>".$row[0]['true_name']."</ReceiveName>\n";		
				/*收件人省市区代码*/
				$item.="<RecipientProvincesCode>".$row[0]['recieve_procity_code']."</RecipientProvincesCode>\n";		
				/*收件人地址*/
				$item.="<ReceiveAddr>".$row[0]['area_info'].$row[0]['address']."</ReceiveAddr>\n";		
				/*收件人证件号*/
				$item.="<ReceiveNo>".$row[0]['card']."</ReceiveNo>\n";		
				/*订单人姓名*/
				//$item.="<OrderName>".$row[0]['buyer_name']."</OrderName>\n";		
				$item.="<OrderName>".$row[0]['member_truename']."</OrderName>\n";		
				/*订单人证件类型*/
				$item.="<OrderDocType>01</OrderDocType>\n";		
				/*订单人证件号*/
				$item.="<OrderDocId>".$row[0]['member_id_card']."</OrderDocId>\n";		
				/*订单编号*/
				$item.="<OrderId>".$row[0]['order_sn']."</OrderId>\n";	
				/*订单接入企业备案号*/
				$item.="<OrderEntRecordNo>".$row[0]['company_num']."</OrderEntRecordNo>\n";
				/*运单编号*/
				$item.="<EWayBillId>".$row[0]['shipping_code']."</EWayBillId>\n";
				/*运单接入企业备案号*/
				$item.="<EWayBEntRecordNo>".$row[0]['company_num']."</EWayBEntRecordNo >\n";			
				/*运费*/
				$item.="<Freight>".$row[0]['shipping_fee']."</Freight>\n";		
				/*运费币制*/
				$item.="<FreightCurr>142</FreightCurr>\n";
				/*进出仓日期*/
				$item.="<DrDate>".$row[0]['apply_date']."</DrDate>\n";
				/*录入日期*/
				$item.="<InputDate>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</InputDate>\n";
				/*备注*/
				$item.="<Nots></Nots>\n";		
				/*发货人所在国家(地区）代码*/
				$item.="<ShipperCountryCode>".$row[0]['country_code']."</ShipperCountryCode>\n";

			$item.="</EBill>\n";

			$item.="<EBillLists>\n";
			foreach($row as $k=>$v)
			{

				$item.="<EBillList>\n";
					/*商品序号*/
					$item.="<GNo>".$v['product_num']."</GNo>\n";		
					/*商品海关备案号*/
					$item.="<CustomsListNO>".$v['goods_custom_num']."</CustomsListNO>\n";	
					/*申报数量*/
					$item.="<Qty>".$v['goods_num']."</Qty>\n";			
					/*申报单价*/
					$item.="<DecPrice>".$v['goods_price']."</DecPrice>\n";			
					/*申报总价*/
					$item.="<DecTotal>".$v['order_amount']."</DecTotal>\n";			
					/*币制*/
					$item.="<Curr>142</Curr>\n";
					/*毛重*/
					$item.="<GrossWt>".$v['gross_weight']."</GrossWt>\n";	
					/*净重*/
					$item.="<NetWt>".$v['net_weight']."</NetWt>\n";	
					/*备注*/
					$item.="<Nots></Nots>\n";

				$item.="</EBillList>\n";			
			}
			
			$item.="</EBillLists>\n";
		  
		  $item.="</Declaration>\n";
		
		$item.="</Manifest>\n";
		
		return $item;
	}
	
	public function get_send_xml($row,$store_info)
	{
		/*报文编号*/
		$item="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		/*业务类型*/
		$item.="<Manifest>\n";
		 /*报文编号*/
		  $item.="<Head>\n";
		 	 /*接入企业备案号*/
			$item.="<MessageID>".$row[0]['company_num']."</MessageID>\n";
			/*报文日期*/	
			$item.="<MessageType>880022</MessageType>\n";
			/*版本 默认为1.0*/
			$item.="<SenderID>".$row[0]['company_num']."</SenderID>\n";
			/*订单编号*/
			$item.="<SendTime>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</SendTime>\n";
			/*进出口标识*/
			$item.="<Version>1.0</Version>\n";

		  $item.="</Head>\n";

		  $item.="<Declaration>\n";

		  $item.="<EWayBills>\n";

				$item.="<EWayBill>\n";
					/*订单编号*/
					$item.="<OrderId>".$row[0]['order_sn']."</OrderId>\n";
					/*进出口标识*/
					$item.="<IEFlag>".$row[0]['ieflag']."</IEFlag>\n";
					/*物流企业代码*/
					$item.="<EntRecordNo>".$row[0]['flow_code']."</EntRecordNo>\n";
					/*物流企业名称*/
					$item.="<EntRecordName>".$row[0]['flow_name']."</EntRecordName>\n";
					/*企业运单编号*/
					$item.="<WayBillNo>".$row[0]['shipping_code']."</WayBillNo>\n";
					/*申报类型*/
					$item.="<DeclareType>".$row[0]['declare_type']."</DeclareType>\n";
					/*物流状态*/
					$item.="<LogisticsStatus>".$row[0]['flow_type']."</LogisticsStatus>\n";
					/*运费*/
					$item.="<Freight>".$row[0]['shipping_fee']."</Freight>\n";
					/*运费币制*/
					$item.="<FreightCurr>142</FreightCurr>\n";	
					/*保价费*/
					$item.="<ValuationFee>0</ValuationFee>\n";
					/*保价费币制*/
					$item.="<ValuationFeeCurr>142</ValuationFeeCurr>\n";
					/*净重*/
					$item.="<NetWt>".$row[0]['total_net_weight']."</NetWt>\n";
					/*毛重*/
					$item.="<GrossWt>".$row[0]['total_gross_weight']."</GrossWt>\n";
					/*件数*/
					$item.="<Num>".$row[0]['total_goods_num']."</Num>\n";
					/*商品信息*/
					$item.="<GoodInfo>".$row[0]['goods_name']."</GoodInfo>\n";
					/*收件人姓名*/
					$item.="<RecipientName>".$row[0]['true_name']."</RecipientName>\n";
					/*收件人所在国家(地区）代码*/
					$item.="<RecipientCountryCode>".$row[0]['recieve_country_code']."</RecipientCountryCode>\n";
					/*收件人省市区代码*/
					$item.="<RecipientProvincesCode>".$row[0]['recieve_procity_code']."</RecipientProvincesCode>\n";
					/*收件人地址*/	
					$item.="<RecipientDetailedAddress>".$row[0]['area_info'].$row[0]['address']."</RecipientDetailedAddress>\n";
					/*收件人电话*/	
					$item.="<RecipientPhone>".$row[0]['mob_phone']."</RecipientPhone>\n";
					/*发货人姓名*/	
					//$item.="<ShipperName>".$row[0]['buyer_name']."</ShipperName>\n";
					$item.="<ShipperName>".$row[0]['member_truename']."</ShipperName>\n";
					/*发货人所在国家(地区）代码*/	
					$item.="<ShipperCountryCode>".$row[0]['country_code']."</ShipperCountryCode>\n";
					/*发货人省市区代码*/
					$item.="<ShipperProvincesCode>".$row[0]['procity_code']."</ShipperProvincesCode>\n";
					/*发货人地址*/	
					$item.="<ShipperDetailedAddress>".$row[0]['area_info'].$row[0]['store_address']."</ShipperDetailedAddress>\n";
					/*发货人电话*/	
					$item.="<ShipperPhone>".$row[0]['member_mob_phone']."</ShipperPhone>\n";
					/*包裹单号*/	
					$item.="<NoticeNo>".$row[0]['parcel']."</NoticeNo>\n";
					/*备注*/	
					$item.="<Note></Note>\n";

				$item.="</EWayBill>\n";
			
			$item.="</EWayBills>\n";
		 
		  $item.="</Declaration>\n";
		
		$item.="</Manifest>\n";
		
		return $item;
	}
	
	public function get_load_xml($row,$n=0)
	{
		
		$item="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

		$item.="<Manifest>\n";
		 
		  $item.="<Head>\n";
			/*报文编号*/
			$item.="<MessageID>881105".date('YmdHis').rand(0000,9999)."</MessageID>\n";
			/*业务类型（默认为0）*/
			$item.="<FunctionCode>0</FunctionCode>\n";
			/*报文类型 （默认为881105）*/
			$item.="<MessageType>881105</MessageType>\n";
			/*平台企业备案号*/
			$item.="<SenderID>".$row[0]['company_num']."</SenderID>\n";
			/*平台企业备案号*/
			$item.="<ReceiverID>".$row[0]['company_num']."</ReceiverID>\n";
			/*发送时间*/
			$item.="<SendTime>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</SendTime>\n";
			/*版本 默认为1.0*/
			$item.="<Version>1.0</Version>\n";
		 
		  $item.="</Head>\n";
		  
		  $item.="<Declaration>\n";
			
			$item.="<EBill>\n";
				/*企业内部编号*/
				$item.="<EntInsideNo>".$row[0]['company_inside_num']."</EntInsideNo>\n";
				/*申报地海关*/
				$item.="<MasterCustoms>".$row[0]['apply_custom']."</MasterCustoms>\n";
				/*车牌号*/
				$item.="<VeName>".$row[0]['car_num']."</VeName>\n";
				/*装载日期*/
				$item.="<LoadingDate>".$row[0]['load_date']."</LoadingDate>\n";
				/*备注*/
				$item.="<Note>".$row[0]['order_not']."</Note>\n";
				/*录入日期*/
				$item.="<InputDate>".gmdate('Y-m-d H:i:s', time() + 3600 * 8)."</InputDate>\n";
				/*总毛重*/
				$item.="<GrossWt>".$row[0]['total_gross_weight']."</GrossWt>\n";
				
			$item.="</EBill>\n";
			
			$item.="<EBillLists>\n";
			foreach($row as $k=>$v)
			{
				$item.="<EBillList>\n";
					/*序号*/
					$item.="<ListGNo>".++$n."</ListGNo>\n";
					/*清单企业内部编号*/
					$item.="<ListEntNo>".$v['company_inside_num']."</ListEntNo>\n";
					/*收件人姓名*/
					$item.="<ReceiveName>".$v['true_name']."</ReceiveName>\n";
					/*清单商品序号*/
					$item.="<GNo>".$v['product_num']."</GNo>\n";	
					/*商品海关备案号*/
					$item.="<CustomsListNO >".$v['goods_custom_num']."</CustomsListNO>\n";
					/*商品名称*/
					$item.="<GName>".$v['goods_name']."</GName>\n";
					/*申报数量*/
					$item.="<Qty>".$v['goods_num']."</Qty>\n";
				
				$item.="</EBillList>\n";
			}
			
			$item.="</EBillLists>\n";
		
		 $item.="</Declaration>\n";
		
		 $item.="</Manifest>\n";
		 
		 return $item;
	}
	
	/*
	public function order_package($item,$name='')
	{
		
		$dir = 'save/';
		$newName = $name.date('YmdHis',time()).rand(0000,9999);
		if(!file_exists($dir))
		{
			mkdir($dir,'0777',true);
		}
		file_put_contents($dir.$newName.".xml",$item);
		file_put_contents($newName.".xml",$item);
		/*
		$zip=new ZipArchive();
		$zipname=date('YmdHis',time()).'.zip';
/*
		if (!file_exists($zipname))
		{
			$zip->open($zipname,ZipArchive::OVERWRITE);
			$zip->addFile($dir.$newName.".xml");
			$content=file_get_contents($dir.$newName.".xml");
			if( $content !== false ) {
				$zip->addFromString($dir.$newName.".xml",$content);
			}
			$zip->close();
			$dw= new download($zipname); 
			$dw->getfiles();
			unlink($zipname); //下载完成后要主动删除 
		}	
		//$zipname=$newName.".xml";
		$dw= new download($newName.".xml"); //下载文件
		$dw->getfiles();		
	}	
	*/
	/*
	*生成压缩包
	*/
	public function order_package($item,$name='')
	{
		$dir = 'save/';
		
		$newName = $name.date('YmdHis',time()).rand(1000,9999);
		
		if(!file_exists($dir))
		{
			mkdir($dir,'0777',true);
		}
		file_put_contents($dir.$newName.".xml",$item);
		file_put_contents($newName.".xml",$item);
		$dw= new download($newName.".xml"); 
		$dw->getfiles();
	}
	
	
	public function goods_receipt()
	{
		if(!empty($_FILES['file']['name']))
		{
			$file = $_FILES['file'];
			if($file['size'] > 2*1024*1024)
			{
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>alert('上传文件超过2M')</script>";
			}
			
			
			if($file['type']!=='text/xml')
			{
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>alert('上传文件类型不对')</script>";
			}
			
			
			if(!file_exists(dirname(dirname(__FILE__)).'/tmp/warehouse_list'))
			{
				mkdir(dirname(dirname(__FILE__)).'/tmp/warehouse_list/','0777',true);
			}
				
			
			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],dirname(dirname(__FILE__)).'/tmp/warehouse_list/'.$dir.$newName))
			{
				//$arr = dirname(dirname(__FILE__)).'/warehouse_list/'.$dir.$newName;
				$arr = 'tmp/warehouse_list/'.$dir.$newName;
				return $arr;
				
			}else
			{
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>alert('上传失败');</script>";
			}
		}
	}
	
	/*
	 * 导出支付回执
	 */
	public  function get_xlspay($order_list){
	
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$order_list[0]['order_sn'].".xls");
		
		$data = "";
		 
		$data .= '<html xmlns:o="urn:schemas-microsoft-com:office:office"
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
          <td class=xl69 style="border-left:none">支付交易号</td>
          <td class=xl69 style="border-left:none">支付企业名称</td>
          <td class=xl69 style="border-left:none">付款时间</td>
          <td class=xl69 style="border-left:none">订单编号</td>
          <td class=xl69 style="border-left:none">电商平台域名</td>
          <td class=xl69 style="border-left:none">电商商户备案号</td>
          <td class=xl69 style="border-left:none">电商商户名称</td>
          <td class=xl69 style="border-left:none">支付金额</td>
          <td class=xl69 style="border-left:none">支付币制代码</td>
          <td class=xl69 style="border-left:none">人民币金额</td>
         </tr>
          ';
		
		foreach($order_list as $key=>$val){
			 
			$data .= '<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                           <td class=xl66 style="border-top:none;border-left:none">'. ($val['out_payment_code'] ? $val['out_payment_code'] : $val['payno']).'</td>
                            <td class=xl66 style="border-top:none;border-left:none">'.$val['pay_ent_name'].'</td>
                             <td class=xl66 style="border-top:none;border-left:none">'.date('Y/m/d H:i:s',$val['payment_time']).'</td>
                              <td class=xl66 style="border-top:none;border-left:none">'.$val['order_sn'].'</td>
                                <td class=xl66 style="border-top:none;border-left:none">'.$val['company_web'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val['company_hscode'].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["company_name"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["order_amount"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["pay_fcode"].'</td>
                           <td class=xl66 style="border-top:none;border-left:none">'.$val["order_amount"].'</td>
                 </tr>';
		}
		$data .=  '
        </table>
	
        </body>
	
        </html>';
		
		return $data;
	}
	
}
?>
