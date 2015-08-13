<?php



defined('haipinlegou') or exit('Access Invalid!');



class deliverControl extends BaseMemberStoreControl {

	public function __construct() {

		parent::__construct();

		Language::read('member_store_index,deliver');

	}



	function indexOp(){

		$model = Model('trade');

                //判断状态是那个表的数据

		if (!in_array($_GET['state'],array('deliverno','delivering','delivered'))) $_GET['state'] = 'deliverno';

		$state = str_replace(array('deliverno','delivering','delivered'),array('20,60',30,40),$_GET['state']);

		$condition = array();
        
        $order_sn	= trim($_GET['order_sn']);
        if($order_sn){
            
            	$condition['order.order_sn'] = array('like',"%$order_sn%");
        }

		$condition['order.seller_id'] = $_SESSION['member_id'];

		$condition['order.order_state'] = array('in',$state);

		$order_list = $model->order_list($condition,5);

              //根据时间筛选记录  

                if($_GET['add_time_from']!= '' && $_GET['add_time_to'] != ''){

                    $condition = array();

                    $condition['seller_id'] = $_SESSION['member_id'];

                    $condition['order_state'] = 30;

                    $condition['add_time_from'] = strtotime($_GET['add_time_from']);

                    $condition['add_time_to'] = strtotime($_GET['add_time_to']);         

                    if($condition['add_time_to'] > 0) {

                        $condition['add_time_to'] +=86400;



                    }

                    $order_list = $model->order_liste_time_screen($condition);                 

                }

              

		if (is_array($order_list)){

			$arr_buyer_id = array();

			$arr_order_id = array();

			foreach ($order_list as $v) {

				$arr_buyer_id[] = $v['buyer_id'];	

				$arr_order_id[] = $v['order_id'];

                              

			}

			$goods_list = $model->table('order_goods')->where(array('order_id'=>array('in',$arr_order_id)))->select();
                       // $store_info = $model->table('store')->where(array('store_id'=>))->select();

			foreach ($order_list as $key=>$value) {

				foreach ($goods_list as $k=>$v) {

					if ($v['order_id'] == $value['order_id']) {

						$order_list[$key]['goods'][] = $v;
                        unset($goods_list[$k]);

					}
				}
                
                if($_GET['state']=='delivering' && $value['shipping_express_id']=='29'){
                    $order_list[$key]['print']=1;
                }
				
			}

            //excel表导出

            if($_GET['excel'] == 1){

              $this->export_excel($order_list);

              exit();

            }

                  

			$arr_buyer_id = array_unique($arr_buyer_id);

			$member_list = $model->table('member')->where(array('member_id'=>array('in',$arr_buyer_id)))->select();

			$member_list = array_under_reset($member_list,'member_id');

			Tpl::output('member_array',$member_list);

			Tpl::output('order_array',$order_list);

			Tpl::output('show_page',$model->showpage());

		}



		$list = $this->_article();

		self::profile_menu('deliver',$_GET['state']);

		Tpl::output('menu_sign','deliver');

		Tpl::output('menu_sign_url','index.php?act=deliver&op=index');

		Tpl::output('menu_sign1',$_GET['state']);// 订单状态
               

		Tpl::showpage('store_order_deliver');

	}

   private  function export_excel($order_list){
		
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
      <td height=19 class=xl69 style="height:14.25pt">序号</td>
      <td class=xl69 style="border-left:none">订单号</td>
      <td class=xl69 style="border-left:none">订单时间</td>
      <td class=xl69 style="border-left:none">发货人</td>
      <td class=xl69 style="border-left:none">发货人电话</td>
      <td class=xl69 style="border-left:none">收货人姓名</td>
      <td class=xl69 style="border-left:none">身份证号</td>
      <td class=xl69 style="border-left:none">收货地址</td>
      <td class=xl69 style="border-left:none">收货人电话号码</td>
      <td class=xl69 style="border-left:none">商品编号</td>
      <td class=xl69 style="border-left:none">商品名称</td>
      <td class=xl69 style="border-left:none">商品外文名称</td>
      <td class=xl69 style="border-left:none">数量</td>
      <td class=xl69 style="border-left:none">商品单价</td>
      <td class=xl69 style="border-left:none">总价</td>
      <td class=xl69 style="border-left:none">运费</td>
     </tr>
      ';

     
      
            $number = 0;
            foreach($order_list as $key=>$val){ 

                    $model = Model();
                    $number ++;//序号
                    $order_id  = intval($val['order_id']);
                    
                    $model_store_order = Model('store_order');
                    $order_goods_list= $model_store_order->storeOrderGoodsList(array('order_id'=>$order_id));
              
                    $datalanguage='';//商品外文
                    $datagoodsno='';//商品编号
                    foreach($order_goods_list as $valgoods){
                        $goods_spec_info = $model->table('goods_spec')->where(array('spec_id'=>$valgoods['spec_id']))->find();                      
                        $goods_article_num = $goods_spec_info['spec_goods_serial'];
                        $goods_records_info = $model->table('goods_records')->where(array('goods_article_num'=>$goods_article_num))->find();      
                        $goods_foreign_language= $goods_records_info["foreign_language"];  
                        $datalanguage .= $goods_foreign_language. "<br/>"; 
                        $datagoodsno .= "".$goods_spec_info["spec_goods_serial"] . "<br/>"; //商品编号
                        
                    }

                    

                        $datagoods='';
                        foreach($order_goods_list as $valgoods){
                        $goods_name=$valgoods["goods_name"];  
                        $datagoods .= $goods_name."<br/>"; 
                        }

                        
                        $datanum='';
                        foreach($order_goods_list as $valgoods){
                        $datanum .= "".$valgoods["goods_num"]."<br/>"; 
                        }

                        $dataprice='';
                        foreach($order_goods_list as $valgoods){
                            $dataprice .= "".$valgoods['goods_price']."<br/>";
                        }

                        $datashipping='';
                        foreach($order_goods_list as $valgoods){
                            $datashipping .= "".$valgoods['shipping_name ']."<br/>";
                        }   

    //		$datagoodsno='';
    //		foreach($order_goods_list as $valgoods){
    //		$datagoodsno .= "".$valgoods["goods_item_no"] . "<br/>"; 
    //		}
    //                
    //		$datagoods='';
    //		foreach($order_goods_list as $valgoods){
    //		$goods_name=$valgoods["goods_name"];  
    //		$datagoods .= $goods_name."<br/>"; 
    //		}
    //                
    //                $datalanguage='';
    //                foreach($order_goods_list as $valgoods){
    //                $goods_foreign_language=$valgoods["foreign_language"];  
    //                $datalanguage .= $goods_foreign_language. "<br/>"; 
    //                }
    //                
    //		$datanum='';
    //		foreach($order_goods_list as $valgoods){
    //		$datanum .= "".$valgoods["goods_num"]."<br/>"; 
    //		}
    //                
    //                $dataprice='';
    //                foreach($order_goods_list as $valgoods){
    //                    $dataprice .= "".$valgoods['goods_price']."<br/>";
    //                }
    //                
    //                $datashipping='';
    //                foreach($order_goods_list as $valgoods){
    //                    $datashipping .= "".$valgoods['shipping_name ']."<br/>";
    //                }
              echo'<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                <td height=121 class=xl26 style=\'height:90.75pt\' x:num>'.$number.'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$val["order_sn"].'</td>
              <td class=xl67 style="border-top:none;border-left:none">'.date("Y-m-d",$val["add_time"]).'</td>
                    <td class=xl67 style="border-top:none;border-left:none">'.$val["store_name"].'</td>
                    <td class=xl67 style="border-top:none;border-left:none">'.$val["store_tel"].'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$val["buyer_name"].'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$val["consignee_id_num"].'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$val["address"].'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$val["tel_phone"].'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$datagoodsno.'</td>
              <td class=xl65 style="border-top:none;border-left:none">'.$datagoods.'</td>
              <td class=xl66 style="border-top:none;border-left:none">'.$datalanguage.'</td>
              <td class=xl65 style="border-top:none;border-left:none">'.$datanum.'</td>
              <td class=xl65 style="border-top:none;border-left:none">'.$dataprice.'</td>
                  <td class=xl65 style="border-top:none;border-left:none">'.$val['goods_amount'].'</td>
                      <td class=xl65 style="border-top:none;border-left:none">'.$datashipping.'</td>
             </tr>';
       }  
    echo '
    </table>

    </body>

    </html>';
		
} 

        

        /**

         *  excel表格导出

         */        

    private function export_excel2($order_list){

      

    $filename = !empty($_REQUEST['filename']) ? trim($_REQUEST['filename']) : '订单信息';



        $data = "";

        $data .= '订单号' . "\t";

        $data .= '订单时间' . "\t";
        
        $data .= '发货人' . "\t";
         
        $data .= '发货人联系电话' . "\t";

        $data .= '收货人' . "\t";

        $data .= '身份证号码' . "\t";

        $data .= '收货地址' . "\t";

        $data .= '收货人电话' . "\t";

        $data .= '商品货号' . "\t";

        $data .= '商品名称' . "\t";

        $data .= '外文' . "\t";

        $data .= '数量' . "\t";
        
        $data .= '商品单价' . "\t";
         
        $data .= '总价' . "\t";

        $data .= '运费' . "\t\n";
         
        
        $model_order	= Model('order');

       

        foreach($order_list as $key=>$val){

             

                 $order_id  = intval($val['order_id']);

		 $model_order	= Model('order');

         

//		 $condition = array();
//
//		 $condition['store_id'] = $_SESSION['store_id'];
//
//		 $condition['order_id'] = $order_id;
//
//                 
//
//		 $order_info = $model_order->getOrderById($order_id,'all',$condition);
              
             
                 
         

                $model_store_order = Model('store_order');

                $order_goods_list= $model_store_order->storeOrderGoodsList(array('order_id'=>$order_id));
              
                       

                $data .= $val['order_sn'] . "\t";  //订单号码

                $data .= date('Y-m-d',$val['add_time']) . "\t"; //订单时间
                
                $data .= $val['store_name'] . "\t"; //发货人
                  
                $data .= $val['store_tel'] . "\t"; //发货人联系电话

                $data .= $val['buyer_name'] . "\t"; //收货人

                $data .= $val['consignee_id_num'] . "\t"; //身份证号码

                $data .= $val['address'] . "\t"; //收货地址

                $data .= $val['tel_phone'] . "\t";  //收货人电话

                        

                //商品货号  
                  $data .="\"";

                  foreach($order_goods_list as $valgoods){

                      $data .= "".$valgoods['goods_item_no'] . "\r\n"; 

                  }

                  $data .= "\"\t";


                //商品名称
                  $data .="\"";
                  
                  foreach($order_goods_list as $valgoods){

                     $goods_name=$valgoods['goods_name'];  

                     $data .= "$goods_name\r\n";

                 }

                  $data .= "\"\t";


                    //商品外文   韩文问题！ 
                  $data .="\"";
                  
                  foreach($order_goods_list as $valgoods){

                     $goods_foreign_language=$valgoods['goods_name'];  

                     $data .= "$goods_foreign_language\r\n";

                 }

                  $data .= "\"\t";
                  
  

//                //商品外文  
//                 $data .="\"";
//
//                  foreach($order_goods_list as $valgoods){
//
//                     $goods_foreign_language_=$valgoods['foreign_language'];
//
//                     $data .= "$goods_foreign_language\r\n";
//
//                 }
//
//                  $data .= "\"\t";


                //商品数量
                $data .="\"";
                 foreach($order_goods_list as $valgoods){

                      $data .= "".$valgoods['goods_num'] . "\r\n";

                 }

                  $data .= "\"\t";
                  
                //商品单价
                $data .="\"";
                 foreach($order_goods_list as $valgoods){

                      $data .= "".$valgoods['goods_price'] . "\r\n";

                 }

                $data .= "\"\t";
                  
                  
                //总价  
                $data .= $val['goods_amount'] . "\t";  
                
                
                
                $data .="\"";

               //运费   

                  foreach($order_goods_list as $valgoods){

                      $data .= "".$valgoods['shipping_name '] . "\r\n";

                  }

                $data .= "\"\t\n";



              }

              if($data){
                  
                 echo iconv('utf-8', 'gbk', $data) . "\t";

              }

              

                header("Content-type: application/vnd.ms-excel; charset=utf-8");

                header("Content-Disposition: attachment; filename=$filename.xls");

    }

        

        



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

    //保存ajax地址
    public function saveaddressajaxOp(){
        $model = Model('order_address');

			$data = array(

				'true_name'=>$_POST['strue_name'],

				'area_info'=>$_POST['sarea_info'],

				'address'=>$_POST['saddress'],

				'zip_code'=>$_POST['szip_code'],

				'tel_phone'=>$_POST['stel_phone'],

				'mob_phone'=>$_POST['smob_phone'],
                
                'consignee_id_num'=>$_POST['scard'],

				'order_id'=>intval($_POST['order_id'])

			);

			$update = $model->update($data);
            if($update){
                echo 's';
            }else{
                echo 'f';
            }
            

    }

    //生成快递单号
    public function genExpress(){

    }

    //发货
	public function sendOp(){

		$model = Model();

		if (chksubmit()){

			if (!is_numeric($_POST['order_id'])){

				showDialog(Language::get('nc_common_save_fail'),'index.php?act=deliver&op=index&state=delivering','succ');

			}

			$model = Model('order_address');

			$data = array(

				'true_name'=>$_POST['strue_name'],

				'area_info'=>$_POST['sarea_info'],

				'address'=>$_POST['saddress'],

				'zip_code'=>$_POST['szip_code'],

				'tel_phone'=>$_POST['stel_phone'],

				'mob_phone'=>$_POST['smob_phone'],
                
                'consignee_id_num'=>$_POST['scard'],

				'order_id'=>intval($_POST['order_id'])

			);

			$update = $model->update($data);

			$model_order = Model('order');

			$order	= $model_order->getOrderById(intval($_POST['order_id']),'all');
           
            $model_daddress = Model('daddress');
            $daddressinfo=$model_daddress->where("address_id='".$_POST['daddress_id']."'")->find();
            $j_daddressinfo_arr=explode("	",$daddressinfo['area_info']);
            $d_orderaddressinfo_arr=explode("	",$order['area_info']);
            
            $model_order_goods = Model();
            $things='';
            $things_num=0;
            $order_goodslist=$model_order_goods->table('order_goods')->where("order_id='".$order['order_id']."'")->select();
            foreach($order_goodslist as $val){
                $things.=$things?','.$val['goods_name']:$val['goods_name'];
                $things_num+=$val['goods_num'];
            }


            if($order['order_state']=='20' && $_POST['shipping_express_id']=='29'){   
                // 顺丰快递生成订单
                
                $pay_method=1;
                $post_data=array();
                $post_data['custid']='0201356453';
                $post_data['d_address']=$order['address'];
                $post_data['d_city']=$d_orderaddressinfo_arr['1'];
                $post_data['d_company']='';
                $post_data['d_contact']=$order['true_name'];
                $post_data['d_province']=$d_orderaddressinfo_arr['0'];
                $post_data['d_qu']=$d_orderaddressinfo_arr['2'];
                $post_data['d_tel']=$order['mob_phone'];
                 $post_data['daishou']=0; 
                $post_data['express_type']=7;
                //<option selected="true" value="1">标准快递 </option><option value="2">顺丰特惠</option><option value="3">电商特惠</option><option value="7">电商速配</option>
                $post_data['j_address']=$daddressinfo['address'];
                $post_data['j_city']=$j_daddressinfo_arr['1'];
                $post_data['j_company']=$daddressinfo['company'];
                $post_data['j_contact']=$daddressinfo['seller_name'];
                $post_data['j_province']=$j_daddressinfo_arr['0'];
                $post_data['j_qu']=$j_daddressinfo_arr['2'];
                $post_data['j_tel']=$daddressinfo['mob_phone'];
                $post_data['orderid']=$order['order_sn'];
                $post_data['pay_method']=$pay_method;
                $post_data['remark']=$_POST['deliver_explain'];
                $post_data['things']=$things;
                $post_data['things_num']=$things_num;
                //增值服务保价
                $post_data['is_insure']=0; 
                $post_data['insure']=$order['order_amount'];  
                
                
               
                
                define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
                require_once (_ROOT . "/../vendor/shunfeng/class/SFforHttpPost.class.php");
                 $SF = new SFapi();
                 $datajosn = $SF->OrderService($post_data)->Send()->readJSON();
              
                @preg_match ("/\"mailno\":\".*?\"/is", $datajosn, $m) ;
                $str=@str_replace("\"", "",$m[0]);
                $strarr=@explode(":",$str);
                $_POST['shipping_code']= $strarr[1];
                
                if(!$strarr[1]){
                    $search_orderid = $order['order_sn'];
                    $datasearch = $SF->OrderSearchService($search_orderid)->Send()->readJSON();
                    @preg_match ("/\"mailno\":\".*?\"/is", $datasearch, $m) ;
                    $str=@str_replace("\"", "",$m[0]);
                    $strarr=@explode(":",$str);
                    $_POST['shipping_code']= $strarr[1];
                }
            }
            
           
             if($order['order_state']=='20' && $_POST['shipping_express_id']=='41'){   
                // 韵达快递生成订单
                $orderinfowuliu="<orders>
            	<order>
            		<order_serial_no>".time()."</order_serial_no>
            		<khddh>".$order['order_sn']."</khddh>
            		<nbckh>".$order['buyer_id']."</nbckh>
            		<order_type>common</order_type>
            		<sender>
            			<name>".$order['true_name']."</name>
            			<company></company>
            			<city>".$d_orderaddressinfo_arr['0'].",".$d_orderaddressinfo_arr['1'].",".$d_orderaddressinfo_arr['2']."</city>
            			<address>".$order['address']."</address>
            			<postcode>".$order['zip_code']."</postcode>
            			<phone></phone>
            			<mobile>".$order['mob_phone']."</mobile>
            			<branch></branch>
            		</sender>
            		<receiver>
            			<name>".$order['true_name']."</name>
            				<company></company>
            				<city>".$d_orderaddressinfo_arr['0'].",".$d_orderaddressinfo_arr['1'].",".$d_orderaddressinfo_arr['2']."</city>
            				<address>".$order['address']."</address>
            				<postcode>".$order['zip_code']."</postcode>
            				<phone></phone>
            				<mobile>".$order['mob_phone']."</mobile>
            				<branch></branch>
            			</receiver>
            			<weight></weight>
            			<size></size>
            			<value></value>
            			<freight></freight>
            			<premium></premium>
            			<other_charges></other_charges>
            			<collection_currency></collection_currency>
            			<collection_value></collection_value>
            			<special></special>
            			<items>
            				<item>
            					<name>".$things."</name>
            					<number>".$things_num."</number>
            					<remark></remark>
            				</item>
            			 </items>
            			<remark>".$_POST['deliver_explain']."</remark>
            			<cus_area1></cus_area1>
            			<cus_area2></cus_area2>
            		  </order>
            		</orders>";
                    
                    
                    $str=$orderinfowuliu;
            		if($_REQUEST['interface']==104){
            			$url="http://orderdev.yundasys.com:10110/cus_order/pub_crontab/interface_receive_order__mailno.php";
            		}else{
            			$url="http://orderdev.yundasys.com:10110/cus_order/order_interface/interface_receive_order__mailno.php";
            		}
            		
            		$data=$this->vfunction($str,1);
            		
            		$ch = curl_init();
            		curl_setopt($ch, CURLOPT_URL,$url);	  // set url to post to
            		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
            		curl_setopt($ch, CURLOPT_TIMEOUT, 60);	  // times out 
            		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	 // add POST fields
            		$result = curl_exec($ch); 
            		if($error = curl_error($ch)) 
            		{   
            			$result= $error;
            			return -1;   
            		}
                    
                 
                	@preg_match ("/<mail_no>.*?<\/mail_no>/is", $result, $m) ;print_r($m);
                    $stryunda=@str_replace("<mail_no>", "",$m[0]);
                    $stryunda=@str_replace("</mail_no>", "",$stryunda);
                    
                    $_POST['shipping_code']= $stryunda;
            }
            
           

			if ($order['payment_code'] == 'alipay' && $order['payment_direct'] == '2' && $order['order_state'] == 20) {

				$express_list  = ($h = F('express')) ? $h : H('express',true,'file');

				

				$data = array();

				$data['daddress_id'] 		= intval($_POST['daddress_id']);

				$data['deliver_explain'] 	= trim($_POST['deliver_explain']);

				$data['shipping_express_id'] = intval($_POST['shipping_express_id']);

				$data['shipping_code'] 		= trim($_POST['shipping_code']);

				$model->table('order')->where(array('order_id'=>intval($_POST['order_id'])))->update($data);

				

				$data['express_name'] = $express_list[intval($_POST['shipping_express_id'])]['e_name'];

				$payment_model	= Model('payment');

				$payment_info	= $payment_model->getPaymentById($order['payment_id']);

				$payment_info['payment_config']	= unserialize($payment_info['payment_config']);

				$inc_file = BasePath.DS.'api'.DS.'payment'.DS.$order['payment_code'].DS.$order['payment_code'].'.php';

				require_once($inc_file);

				$payment_api	= new $order['payment_code']($payment_info,$order);

				$doc = $payment_api->sendPostInfo($data);

				$is_success = $doc->getElementsByTagName( "is_success" )->item(0)->nodeValue;

			}else{

				$data = array();

				$data['daddress_id'] 		= intval($_POST['daddress_id']);

				$data['deliver_explain'] 	= trim($_POST['deliver_explain']);

				$data['shipping_express_id'] = intval($_POST['shipping_express_id']);

				$data['shipping_code'] 		= trim($_POST['shipping_code']);

				$data['order_state'] 		= 30;

				$data['shipping_time'] 		= time();

				$model->table('order')->where(array('order_id'=>intval($_POST['order_id'])))->update($data);

				

				$data = array();

				$data['order_id'] = intval($_POST['order_id']);

				$data['order_state'] = Language::get('store_deliver_order_state_send');

				$data['change_state'] = Language::get('store_deliver_order_state_receive');

				$data['state_info'] = trim($_POST['deliver_explain']);

				$data['log_time'] = time();

				$data['operator'] = $_SESSION['member_name'];

				$model->table('order_log')->insert($data);

				

				

				$param	= array(

					'site_url'	=> SiteUrl,

					'site_name'	=> $GLOBALS['setting_config']['site_name'],

					'store_name'	=> $order['store_name'],

					'buyer_name'	=> $order['buyer_name'],

					'order_sn'	=> $order['order_sn'],

					'order_id'	=> $order['order_id'],

					'invoice_no'=> $order['shipping_code']

				);

				$this->send_notice($order['buyer_id'],'email_tobuyer_shipped_notify',$param);

			}

			showDialog(Language::get('nc_common_save_succ'),'index.php?act=deliver&op=index&state=delivering','succ');

		}



		if (!is_numeric($_GET['order_id'])){

			showMessage(Language::get('wrong_argument'),'','html','error');

		}



		$condition = array();

		$condition['order.seller_id'] = $_SESSION['member_id'];

		$condition['order.order_id'] = $_GET['order_id'];

		$condition['order.order_state'] = array('in','20,30,60');

		$on = 'order.order_id=order_address.order_id';

		$order_info = $model->table('order,order_address')->on($on)->where($condition)->find();

		if (is_numeric($order_info['order_id'])){

			$goods_list = $model->table('order_goods')->where(array('order_id'=>$order_info['order_id']))->select();

			$member_info = $model->table('member')->where(array('member_id'=>$order_info['buyer_id']))->find();

			Tpl::output('goods_array',$goods_list);

			Tpl::output('member_array',$member_info);

			Tpl::output('order_array',$order_info);

		}

		if ($order_info['daddress_id'] > 0 ){

			$daddess_info = $model->table('daddress')->find($order_info['daddress_id']);

		}else{

			$daddess_info = $model->table('daddress')->where(array('store_id'=>$_SESSION['store_id']))->order('is_default desc')->find();

		}

		Tpl::output('daddress_info',$daddess_info);



		$my_express_list = $model->table('store_extend')->getfby_store_id($_SESSION['store_id'],'express');

		if (!is_null($my_express_list)){

			$my_express_list = explode(',',$my_express_list);

		}

		//$express_list  = ($h = F('express')) ? $h : H('express',true,'file');

		

		$express = Model('express');
        $options['limit']=100;
		$express_list = $express->select($options);

		//S脚部文章输出

		$list = $this->_article();

		//E脚部文章输出



		Tpl::output('my_express_list',$my_express_list);

		Tpl::output('express_list',$express_list);

		Tpl::output('menu_sign','deliver');

		Tpl::showpage('store_order_deliver2');

	}


//韵达 *************************************
  private  function vfunction($str,$form_interface,$act){
	$form_interface=(int) $form_interface;
	switch ($form_interface){
		case 3:
			$rt=$this->make_send_data_in($str,'cancel_order',$_REQUEST['user'],$_REQUEST['pass']);
			return $rt;
			break;
		default:
			$rt=$this->make_send_data_in($str,'data','YUNDA','123456');
			return $rt;
			break;
	}
}
    /*生成指定格式的发送数据	外部系统接入*/
 private   function make_send_data_in($xmldata,$request='data',$user='YUNDA',$pass='123456')
    {
    	return ("partnerid=".$user."&version=1.0&request={$request}&xmldata=".urlencode(base64_encode($xmldata))."&validation=".urlencode(strtolower(md5(base64_encode($xmldata).$user.$pass))));
    }

	public function buyer_addressOp(){

		$order_id = decrypt($_GET['order_id'],md5(1));

		if (!is_numeric($order_id)) return false;





		Tpl::showpage('deliver_buyer_address_edit','null_layout');

	}



	/****发货设置****/

	public function daddressOp() {

		

		Language::read('member_member_index');

		$lang	= Language::getLangContent();

		

		$model = Model('daddress');

		

		if (!empty($_GET['type'])){

			

			if (intval($_GET['id']) > 0){



				$address_info = $model->getby_address_id(intval($_GET['id']));

				if (empty($address_info) && !is_array($address_info)){

					showMessage($lang['store_daddress_wrong_argument'],'index.php?act=member&op=address','html','error');

				}



				Tpl::output('address_info',$address_info);

			}

			/**

			 * 增加/修改页面输出

			 */

			Tpl::output('type',$_GET['type']);

			Tpl::showpage('store_deliver_daddress_form','null_layout');

			exit();

		}



		if (chksubmit()){



			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

				array("input"=>$_POST["seller_name"],"require"=>"true","message"=>$lang['store_daddress_receiver_null']),

				array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>$lang['store_daddress_wrong_area']),

				array("input"=>$_POST["city_id"],"require"=>"true","validator"=>"Number","message"=>$lang['store_daddress_wrong_area']),

				array("input"=>$_POST["area_info"],"require"=>"true","message"=>$lang['store_daddress_area_null']),

				array("input"=>$_POST["address"],"require"=>"true","message"=>$lang['store_daddress_address_null']),

				array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>$lang['store_daddress_phone_and_mobile'])

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}

			$data = array(

				'store_id'=>$_SESSION['store_id'],

				'seller_name'=>$_POST['seller_name'],

				'area_id'=>$_POST['area_id'],

				'city_id'=>$_POST['city_id'],

				'area_info'=>$_POST['area_info'],

				'address'=>$_POST['address'],

				'zip_code'=>$_POST['zip_code'],

				'tel_phone'=>$_POST['tel_phone'],

				'mob_phone'=>$_POST['mob_phone'],

				'company'=>$_POST['company'],

				'content'=>$_POST['content']

			);			

			if (intval($_POST['id']) > 0){

				$update = $model->where(array('address_id'=>intval($_POST['id'])))->update($data);

				if (!$update){

					showDialog($lang['store_daddress_modify_fail'],'','error');

				}

			}else {

				$insert = $model->insert($data);

				if (!$insert){

					showDialog($lang['store_daddress_add_fail'],'','error');

				}

			}

			showDialog($lang['nc_common_op_succ'],'reload','succ','CUR_DIALOG.close()');

		}

		$del_id = isset($_GET['id']) ? intval(trim($_GET['id'])) : 0 ;

		if ($del_id > 0){

			$del = $model->delete($del_id);

			if ($del){

				showDialog(Language::get('store_daddress_del_succ'),'index.php?act=deliver&op=daddress','succ');

			}else {

				showDialog(Language::get('store_daddress_del_fail'),'','error');

			}

		}

		$address_list = $model->where(array('store_id'=>$_SESSION['store_id']))->select();



		$list = $this->_article();

		self::profile_menu('daddress','daddress');

		Tpl::output('menu_sign','daddress');

		Tpl::output('address_list',$address_list);

		Tpl::output('menu_sign_url','index.php?act=deliver&op=daddress');

		Tpl::output('menu_sign1','daddress_list');

		Tpl::showpage('store_deliver_daddress');

	}



	public function expressOp(){	

		$model = Model('store_extend');



		if (chksubmit()){

			$data['store_id'] = $_SESSION['store_id'];

			if(is_array($_POST['cexpress']) && !empty($_POST['cexpress'])){

				$data['express'] = implode(',',$_POST['cexpress']);

			}else{				

				$data['express'] = '';				

			}

			if (is_null($model->getby_store_id($_SESSION['store_id']))){

				$result = $model->insert($data);

			}else{

				$result = $model->update($data);

			}

			if ($result){

				showDialog(Language::get('nc_common_save_succ'),'reload','succ');

			}else{

				showDialog(Language::get('nc_common_save_fail'),'reload','error');

			}

		}

		if (!$express_list = F('express')){

			$express_list = H('express',true,'file');

		}

		

		$express_select = $model->getfby_store_id($_SESSION['store_id'],'express');

		if (!is_null($express_select)){

			$express_select = explode(',',$express_select);

		}else{

			$express_select = array();

		}

		Tpl::output('express_select',$express_select);

		self::profile_menu('daddress','express');

		Tpl::output('menu_sign','daddress');

		Tpl::output('express_list',$express_list);

		Tpl::output('menu_sign_url','index.php?act=deliver&op=express');

		Tpl::output('menu_sign1','default_express');

		Tpl::showpage('store_deliver_express');

	}



	public function pop_addressOp(){

		if (chksubmit()){



			$obj_validate = new Validate();

			$obj_validate->validateparam = array(

				array("input"=>$_POST["seller_name"],"require"=>"true","message"=>$lang['store_daddress_receiver_null']),

				array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>$lang['store_daddress_wrong_area']),

				array("input"=>$_POST["city_id"],"require"=>"true","validator"=>"Number","message"=>$lang['store_daddress_wrong_area']),

				array("input"=>$_POST["area_info"],"require"=>"true","message"=>$lang['store_daddress_area_null']),

				array("input"=>$_POST["address"],"require"=>"true","message"=>$lang['store_daddress_address_null']),

				array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>$lang['store_daddress_phone_and_mobile'])

			);

			$error = $obj_validate->validate();

			if ($error != ''){

				showValidateError($error);

			}

			$data = array(

				'store_id'=>$_SESSION['store_id'],

				'seller_name'=>$_POST['seller_name'],

				'area_id'=>$_POST['area_id'],

				'city_id'=>$_POST['city_id'],

				'area_info'=>$_POST['area_info'],

				'address'=>$_POST['address'],

				'zip_code'=>$_POST['zip_code'],

				'tel_phone'=>$_POST['tel_phone'],

				'mob_phone'=>$_POST['mob_phone'],

				'company'=>$_POST['company'],

				'content'=>$_POST['content']

			);

			$model = Model('daddress');

			$insert = $model->insert($data);

			if (!$insert){

				showDialog(Language::get('nc_common_op_fail'),'','error');

			}

			$extend_js = array($_POST['area_info'].$_POST['address'],$_POST['zip_code'],$_POST['seller_name'],$_POST['tel_phone'],$_POST['mob_phone']);

			$extend_js = implode('&nbsp;',$extend_js);

			$extend_js .= "<a href=\"javascript:void(0);\" onclick=\"ajax_form(\'modfiy_daddress\', \'".Language::get('store_deliver_select_daddress')."\', \'index.php?act=deliver&op=pop_address&type=select\', 550,0);\" class=\"fr\">".Language::get('store_deliver_select_ather_daddress')."</a>";

			$extend_js = "$('#daddress').html('".$extend_js."');";

			showDialog(Language::get('nc_common_op_succ'),'','succ','CUR_DIALOG.close();$("#dadress_id").val('.$insert.');'.$extend_js);

		}

		if ($_GET['type'] == 'select'){

			$model = Model('daddress');

			$daddress_list = $model->where(array('store_id'=>$_SESSION['store_id']))->limit(10)->select();

			Tpl::output('daddress_list',$daddress_list);



			Tpl::showpage('store_deliver_daddress_select','null_layout');

		}else{

			Tpl::showpage('store_deliver_daddress_add','null_layout');

		}

	}
	public function print_deliverOp(){

		Language::read('member_member_index');

		$lang	= Language::getLangContent();
        $order_sn	= $_GET['order_sn'];
        $model	= Model();

		$condition['order.store_id'] = $_SESSION['store_id'];

		$condition['order.order_sn'] = $order_sn;

		$on = 'order.order_id=order_address.order_id';

		$order_info = $model->table('order,order_address')->on($on)->join('inner')->where($condition)->find();

		$order_id	= intval($order_info['order_id']);


         
        

		$order_info['state_info'] = orderStateInfo($order_info['order_state']);

		Tpl::output('order_info',$order_info);
        
        
        define('_ROOT', str_replace("\\", '/', dirname(__FILE__)));
        require_once (_ROOT . "/../vendor/shunfeng/class/SFforHttpPost.class.php");
        $SF = new SFapi();
        $search_orderid = $order_info['order_sn'];
        $datasearch = $SF->OrderSearchService($search_orderid)->Send()->readJSON();
        @preg_match ("/\"destcode\":\".*?\"/is", $datasearch, $m) ;
        $deststr=@str_replace("\"", "",$m[0]);
        $destcodearr=@explode(":",$deststr);
        
        @preg_match ("/\"origincode\":\".*?\"/is", $datasearch, $m) ;
        $str=@str_replace("\"", "",$m[0]);
        $origincodecodearr=@explode(":",$str);
       
        Tpl::output('d_number',$destcodearr[1]);
        
        Tpl::output('j_number',$origincodecodearr[1]);
      //  print_r($order_info);

		$model_store	= Model('store');
        $store_info		= $model_store->shopStore(array('store_id'=>$order_info['store_id']));

		Tpl::output('store_info',$store_info);
//print_r($store_info);
		

		$model_store_order = Model('store_order');



		if ($order_id > 0){

			$order_goods_list= $model_store_order->storeOrderGoodsList(array('order_id'=>$order_id));

		}

		Tpl::output('order_goods_list',$order_goods_list);

	

		$daddress_info = Model('daddress')->find($order_info['daddress_id']);

        $j_daddressinfo_arr=explode("	",$daddress_info['area_info']);
        $d_orderaddressinfo_arr=explode("	",$order_info['area_info']);
		
        Tpl::output('j_daddressinfo_arr',$j_daddressinfo_arr);
        Tpl::output('d_orderaddressinfo_arr',$d_orderaddressinfo_arr);
		Tpl::output('daddress_info',$daddress_info);

//print_r($daddress_info);

        $model_order_goods = Model();
        $things='';
        $things_num=0;
        $order_goodslist=$model_order_goods->table('order_goods')->where("order_id='".$order_info['order_id']."'")->select();
        foreach($order_goodslist as $val){
            $bar_code=$model_order_goods->table('goods_records')->where("goods_article_num='".$val['goods_item_no']."'")->find();
            $things.=$things?','.$val['goods_name'].'*'.$val['goods_num'].'('.$bar_code['bar_code'].')':$val['goods_name'].'*'.$val['goods_num'].'('.$bar_code['bar_code'].')';
           // $things_num+=$val['goods_num'];
        }
        
        Tpl::output('things_num',$things_num);
        Tpl::output('things',$things);
        
        
        
		$express = ($express = F('express'))? $express :H('express',true,'file');

		Tpl::output('e_code',$express[$order_info['shipping_express_id']]['e_code']);

		Tpl::output('e_name',$express[$order_info['shipping_express_id']]['e_name']);

		Tpl::output('e_url',$express[$order_info['shipping_express_id']]['e_url']);

		Tpl::output('shipping_code',$order_info['shipping_code']);

		//S脚部文章输出

		$list = $this->_article();

		//E脚部文章输出



		self::profile_menu('search','search');

		Tpl::output('menu_sign','deliver');

		Tpl::output('menu_sign_url','index.php?act=deliver&op=index');

		Tpl::output('menu_sign1','deliver_info');	

		Tpl::showpage('store_order_deliver_print');

	}


	public function search_deliverOp(){

		Language::read('member_member_index');

		$lang	= Language::getLangContent();







		$order_sn	= $_GET['order_sn'];

		

		$model	= Model();

		$condition['order.store_id'] = $_SESSION['store_id'];

		$condition['order.order_sn'] = $order_sn;

		$on = 'order.order_id=order_address.order_id';

		$order_info = $model->table('order,order_address')->on($on)->join('inner')->where($condition)->find();

		$order_id	= intval($order_info['order_id']);



		$order_info['state_info'] = orderStateInfo($order_info['order_state']);

		Tpl::output('order_info',$order_info);

		

		$model_store	= Model('store');

		$store_info		= $model_store->shopStore(array('store_id'=>$order_info['store_id']));

		Tpl::output('store_info',$store_info);

		

		$model_store_order = Model('store_order');

	

		if ($order_id > 0){

			$order_goods_list= $model_store_order->storeOrderGoodsList(array('order_id'=>$order_id));

		}

		Tpl::output('order_goods_list',$order_goods_list);

		

		$daddress_info = Model('daddress')->find($order_info['daddress_id']);

		

		Tpl::output('daddress_info',$daddress_info);



		$express = ($express = F('express'))? $express :H('express',true,'file');

		Tpl::output('e_code',$express[$order_info['shipping_express_id']]['e_code']);

		Tpl::output('e_name',$express[$order_info['shipping_express_id']]['e_name']);

		Tpl::output('e_url',$express[$order_info['shipping_express_id']]['e_url']);

		Tpl::output('shipping_code',$order_info['shipping_code']);

		//S脚部文章输出

		$list = $this->_article();

		//E脚部文章输出



		self::profile_menu('search','search');

		Tpl::output('menu_sign','deliver');

		Tpl::output('menu_sign_url','index.php?act=deliver&op=index');

		Tpl::output('menu_sign1','deliver_info');	

		Tpl::showpage('store_order_deliver_detail');

	}



	public function ajaxOp(){

		switch ($_GET['type']) {

			case 'daddress':

				if (!is_numeric($_GET['id'])) return false;

				$model = Model('daddress');

				$model->where(array('address_id'=>$_GET['id']))->update(array('is_default'=>1));

				$model->where(array('address_id'=>array('neq',$_GET['id'])))->update(array('is_default'=>0));

			break;

		}

	}



	

	private function profile_menu($menu_type,$menu_key='') {

		Language::read('member_layout');

		$menu_array		= array();

		switch ($menu_type) {

			case 'deliver':

				$menu_array = array(

				1=>array('menu_key'=>'deliverno',			'menu_name'=>Language::get('nc_member_path_deliverno'),	'menu_url'=>'index.php?act=deliver&op=index&state=deliverno'),

				2=>array('menu_key'=>'delivering',			'menu_name'=>Language::get('nc_member_path_delivering'),	'menu_url'=>'index.php?act=deliver&op=index&state=delivering'),

				3=>array('menu_key'=>'delivered',		'menu_name'=>Language::get('nc_member_path_delivered'),	'menu_url'=>'index.php?act=deliver&op=index&state=delivered'),

                                

				);

				break;

			case 'search':

				$menu_array = array(

				1=>array('menu_key'=>'nodeliver',			'menu_name'=>Language::get('nc_member_path_deliverno'),	'menu_url'=>'index.php?act=deliver&op=index&state=nodeliver'),

				2=>array('menu_key'=>'delivering',			'menu_name'=>Language::get('nc_member_path_delivering'),	'menu_url'=>'index.php?act=deliver&op=index&state=delivering'),

				3=>array('menu_key'=>'delivered',		'menu_name'=>Language::get('nc_member_path_delivered'),	'menu_url'=>'index.php?act=deliver&op=index&state=delivered'),

				4=>array('menu_key'=>'search',		'menu_name'=>Language::get('nc_member_path_deliver_info'),	'menu_url'=>'###'),

				);

				break;				

			case 'daddress':

				$menu_array = array(

				1=>array('menu_key'=>'daddress',			'menu_name'=>Language::get('store_deliver_daddress_list'),	'menu_url'=>'index.php?act=deliver&op=daddress'),

				2=>array('menu_key'=>'express',				'menu_name'=>Language::get('store_deliver_default_express'),	'menu_url'=>'index.php?act=deliver&op=express')

				);

				break;

		}

		Tpl::output('member_menu',$menu_array);

		Tpl::output('menu_key',$menu_key);

	}

    public function changestateOp()
    {
        $order_id = intval($_GET['order_id']);
        $state    = intval($_GET['state']);

        if(empty($order_id) || empty($state)) {die('123');
            showMessage('修改订单显示状态失败','','html','error');
        }

        $update['show_state'] = $state;
        $where = " order_id=".$order_id;

        $res = Db::update('order',$update,$where);

        if($res) {
            echo json_encode(['status'=>'succ']);
            die;

        }else {
            echo json_encode(['status'=>'fail']);
            die;
        }
    }

}