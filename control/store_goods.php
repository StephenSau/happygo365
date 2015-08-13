<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_goodsControl extends BaseMemberStoreControl {
	public function __construct() {

		parent::__construct();
		Language::read('member_store_goods_index');
		$lang	= Language::getLangContent();
		
		$model_grade	= Model('store_grade');
		$store_grade	= $model_grade->getGradeShopList(array('store_id'=>"{$_SESSION['store_id']}"));
		if(intval($store_grade[0]['store_state']) == 2) {
			showMessage($lang['store_auditing_tip'],'index.php?act=store','html','error');
		}
	}
	public function indexOp(){
		$this->goods_listOp();
	}
	
	public function goods_listOp() {
		
		$model_store_goods	= Model('goods');
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');

		$list_goods	= array();
		$search_array	= array();
		$search_array['goods_show']		= '1';
		$search_array['store_id']		= $_SESSION['store_id'];
		$search_array['keyword']		= addslashes(trim($_GET['keyword']));
		
		$stc_id = intval($_GET['stc_id']);
		if ($stc_id){
			$model_store_class = Model('my_goods_class');
			$stc_id_arr = $model_store_class->getChildAndSelfClass($stc_id,'1');
			if (is_array($stc_id_arr) && count($stc_id_arr)>0){
				$search_array['stc_id_in'] = implode(',',$stc_id_arr);
			}else{
				$search_array['stc_id'] = $stc_id_arr;
			}
		}
		$search_array['order']		.= 'goods.goods_id desc';

        $gc_parent_id = intval($_GET['gc_parent_id']);
        if($gc_parent_id) {
            $gc_first_id = $_GET['gc_first_id'];
            $gc_second_id= $_GET['gc_second_id'];
            $gc_third_id = $_GET['gc_third_id'];
            $store_model = Model('store');
            $store_cate = $store_model->getClass(array('gc_parent_id'=>$gc_parent_id));
            if(!empty($store_cate) && is_array($store_cate)) {
                $tmp_array = array();
                foreach ($store_cate as $v2) {
                    $tmp_array[] = $v2['gc_id'];
                }
                $search_array['gc_id_in'] = implode(',',$tmp_array);
                //$search_array['gc_id_in'] = implode(',',array_column($store_cate,'gc_id'));
            } else {
                $search_array['gc_id_in'] = $gc_parent_id;
            }
        }
		$list_goods	= $model_store_goods->getGoods($search_array,$page,'*','stc');
		if(is_array($list_goods) and !empty($list_goods)) {
			$goods_id_str = '';
			foreach ($list_goods as $key => $val) {
				$goods_id_str .= "'".$val['goods_id']."',";
			}
			$goods_id_str	= rtrim($goods_id_str, ',');
			$goods_storage	= $model_store_goods->countStorageByGoodsSpec(array('in_spec_goods_id'=>$goods_id_str), 'sum(spec_goods_storage) as sum,goods_id');
			$storage_array	= array();
			foreach ($goods_storage as $val) {
				$storage_array[$val['goods_id']]	= $val['sum'];
			}
		}
		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出

		Tpl::output('show_page',$page->show());
		Tpl::output('list_goods',$list_goods);
		Tpl::output('storage_array', $storage_array);


		$model_store_class	= Model('my_goods_class');
		$store_goods_class	= $model_store_class->getClassTree(array('store_id'=>$_SESSION['store_id'],'stc_state'=>'1'));
        //print_r($store_goods_class);die;
		Tpl::output('store_goods_class',$store_goods_class);
        //获取商店的顶级分类
        $store_third_class = array();
        $store_model = Model('store');

        $store_class = $store_model->getStoreClass($_SESSION['store_id']);
        if (!empty($gc_first_id)) {
            $store_sec_class = $store_model->getStoreClass($_SESSION['store_id'],2,['gc_parent_id'=>$gc_first_id]);
            Tpl::output("store_second_class",$store_sec_class);
        }
        if (!empty($gc_second_id)) {
            $store_third_class = $store_model->getClass(array('gc_parent_id'=>$gc_second_id));
            TPl::output('store_third_class',$store_third_class);
        }


        //$store_class
        Tpl::output("gc_parent_id",$gc_parent_id);
        Tpl::output('store_class',$store_class);
        Tpl::output('gc_first_id',$gc_first_id);
        Tpl::output('gc_second_id',$gc_second_id);
        Tpl::output('gc_third_id',$gc_third_id);
		self::profile_menu('goods','goods_list');
		Tpl::output('menu_sign','goods_selling');
		Tpl::showpage('store_goods_list');
	}
	
	public function goods_storageOp() {
	
		$model_store_goods	= Model('goods');
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');

		$list_goods	= array();
		$search_array	= array();
		if($_GET['type'] == 'state'){
			$search_array['goods_state']	= '1';
			self::profile_menu('goods_storage','goods_state');
		}else{
			$search_array['goods_state']	= '0';
			$search_array['goods_show']		= '0';
			self::profile_menu('goods_storage','goods_storage');
		}
		$search_array['store_id']		= $_SESSION['store_id'];
		$search_array['keyword']		= trim($_GET['keyword']);
		
		$stc_id = intval($_GET['stc_id']);
		if ($stc_id){
			$model_store_class = Model('my_goods_class');
			$stc_id_arr = $model_store_class->getChildAndSelfClass($stc_id,'1');
			if (is_array($stc_id_arr) && count($stc_id_arr)>0){
				$search_array['stc_id_in'] = implode(',',$stc_id_arr);
			}else{
				$search_array['stc_id'] = $stc_id_arr;
			}
		}
		$search_array['order']		.= 'goods.goods_id desc';
		$list_goods	= $model_store_goods->getGoods($search_array,$page,'*','stc');
		if(is_array($list_goods) and !empty($list_goods)) {
			$goods_id_str = '';
			foreach ($list_goods as $key => $val) {
				$goods_id_str .= "'".$val['goods_id']."',";
			}
			$goods_id_str	= rtrim($goods_id_str, ',');
			$goods_storage	= $model_store_goods->countStorageByGoodsSpec(array('in_spec_goods_id'=>$goods_id_str), 'sum(spec_goods_storage) as sum,goods_id');
			$storage_array	= array();
			foreach ($goods_storage as $val) {
				$storage_array[$val['goods_id']]	= $val['sum'];
			}
		}
		
		Tpl::output('show_page',$page->show());
		Tpl::output('list_goods',$list_goods);
		Tpl::output('storage_array', $storage_array);

	
		$model_store_class	= Model('my_goods_class');
		$store_goods_class	= $model_store_class->getClassTree(array('store_id'=>$_SESSION['store_id'],'stc_state'=>'1'));
		Tpl::output('store_goods_class',$store_goods_class);

		if($_GET['type'] == 'state'){
			Tpl::output('menu_sign', 'goods_state');
		}else{
			Tpl::output('menu_sign','goods_storage');
		}
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		Tpl::showpage('store_goods_storage');
	}
	
	public function goods_stock_listOp() {
		$lang	= Language::getLangContent();
		$model_goods	= Model('goods');
		
		$goods_id	= intval($_GET['goods_id']);
		$stock_sum	= intval($_GET['stock_sum']);
		if($goods_id <1){
			return false;
		}
		$goods_info		= $model_goods->getGoods(array('goods_id'=>$goods_id, 'store_id'=>$_SESSION['store_id']), '', '*', 'goods');
		if(!is_array($goods_info) || empty($goods_info)){
			return false;
		}
		$stock_count= $model_goods->getGoods(array('goods_spec.goods_id'=>$goods_id, 'order'=>'spec_id asc'), '', 'count(spec_id) as count', 'goods_spec');
		$stock_list	= $model_goods->getGoods(array('goods_spec.goods_id'=>$goods_id, 'order'=>'spec_goods_storage asc', 'limit'=>'10'), '', 'spec_id,spec_goods_spec,spec_goods_storage', 'goods_spec');
		$stock_array	= array();
		$stock_part_sum	= 0;
		foreach ($stock_list as $k => $val){
			$stock_array[$k]['spec_id']				= $val['spec_id'];
			$stock_array[$k]['spec_goods_storage']	= $val['spec_goods_storage'];
			$stock_array[$k]['spec_goods_spec']		= '';
			foreach (unserialize($val['spec_goods_spec']) as $v){
				$stock_array[$k]['spec_goods_spec']	.= $v.'/';
			}
			$stock_array[$k]['spec_goods_spec']		= rtrim($stock_array[$k]['spec_goods_spec'], '/');
			$stock_part_sum							+= intval($val['spec_goods_storage']);
		}
		Tpl::output('goods_id', $goods_id);
		Tpl::output('stock_sum', $stock_sum);
		Tpl::output('surplus_sum', $stock_sum-$stock_part_sum);
		Tpl::output('stock_count', $stock_count['0']['count']);
		Tpl::output('stock_array', $stock_array);
		Tpl::showpage('store_goods_stock_list','null_layout');
	}
	
	public function goods_stock_ajax_saveOp() {
		$goods_id	= intval($_POST['goods_id']);
		$arr		= rtrim($_POST['name'], ';');
		$arr		= explode(';', $arr);
		
		$model_goods = Model('goods');
		$stock_array= array();		
		if(is_array($arr) && !empty($arr)){
			foreach ($arr as $val){
				list($spec_id, $stock) = explode(':', $val);
				$model_goods->updateSpecGoods(array('spec_goods_id'=>$goods_id,'spec_id'=>$spec_id), array('spec_goods_storage'=>$stock));
			}
		}
	}
	
	public function goods_price_listOp() {
		$lang	= Language::getLangContent();
		$model_goods	= Model('goods');
		
		$goods_id	= intval($_GET['goods_id']);
		if($goods_id <1){
			return false;
		}
		$goods_info		= $model_goods->getGoods(array('goods_id'=>$goods_id, 'store_id'=>$_SESSION['store_id']), '', '*', 'goods');
		if(!is_array($goods_info) || empty($goods_info)){
			return false;
		}
		$price_count= $model_goods->getGoods(array('goods_spec.goods_id'=>$goods_id, 'order'=>'spec_id asc'), '', 'count(spec_id) as count', 'goods_spec');
		$price_list	= $model_goods->getGoods(array('goods_spec.goods_id'=>$goods_id, 'order'=>'spec_id asc', 'limit'=>'10'), '', 'spec_id,spec_goods_spec,spec_goods_price', 'goods_spec');
		$price_array	= array();
		foreach ($price_list as $k => $val){
			$price_array[$k]['spec_id']				= $val['spec_id'];
			$price_array[$k]['spec_goods_price']	= $val['spec_goods_price'];
			$price_array[$k]['spec_goods_spec']		= '';
			foreach (unserialize($val['spec_goods_spec']) as $v){
				$price_array[$k]['spec_goods_spec']	.= $v.'/';
			}
			$price_array[$k]['spec_goods_spec']		= rtrim($price_array[$k]['spec_goods_spec'], '/');
		}
		Tpl::output('goods_id', $goods_id);
		Tpl::output('price_count', $price_count['0']['count']);
		Tpl::output('price_array', $price_array);
		Tpl::showpage('store_goods_price_list','null_layout');
	}
	
	public function goods_price_ajax_saveOp() {
		$goods_id	= intval($_POST['goods_id']);
		$arr		= rtrim($_POST['name'], ';');
		$arr		= explode(';', $arr);
	
		$model_goods = Model('goods');
		$stock_array= array();
		if(is_array($arr) && !empty($arr)){
			foreach ($arr as $val){
				list($spec_id, $price) = explode(':', $val);
				$model_goods->updateSpecGoods(array('spec_goods_id'=>$goods_id,'spec_id'=>$spec_id), array('spec_goods_price'=>$price));
			}
			
			$price_list	= $model_goods->getGoods(array('goods_spec.goods_id'=>$goods_id, 'order'=>'spec_id asc'), '', 'spec_goods_price', 'goods_spec');
			$min = 10000000;$max = 0;
			if(is_array($price_list) && !empty($price_list)){
				foreach ($price_list as $val){
					if(intval($min) > intval($val['spec_goods_price']))	$min = $val['spec_goods_price'];
					if(intval($max) < intval($val['spec_goods_price']))	$max = $val['spec_goods_price'];
				}
			}
			$model_goods->updateGoods(array('goods_store_price_interval'=> $min .' - '. $max, 'goods_store_price'=>$min), $goods_id);
		}
	}
	
	public function add_goodsOp(){
         
		
		$lang	= Language::getLangContent();
		
		Tpl::setLayout('member_goods_marketing_layout');

		if (!C('payment')){
			
			$model_payment = Model('gold_payment');
			$condition = array();
			$condition['payment_state'] = '1';
			$payment_list = $model_payment->getList($condition);
			if (!$payment_list){
				showMessage($lang['store_goods_index_no_pay_type'],'index.php?act=store_goods&op=goods_list','html','error');
			}
		}else{
		
			$model_payment 	= Model('payment');
			$check_payment	= $model_payment->checkStorePayment();
			
			if(!$check_payment) {
				redirect('index.php?act=store&op=payment');
			}
		}

		
		$model_store_goods	= Model('goods');
		$goods_num=$model_store_goods->countGoods(array('store_id'=>$_SESSION['store_id']));
		
		$model_store	= Model('store');
		$store_info		= $model_store->shopStore(array('store_id'=>$_SESSION['store_id']));
		$model_store_grade	= Model('store_grade');
		$store_grade	= $model_store_grade->getOneGrade($store_info['grade_id']);
		$editor_multimedia = false;
		$sg_fun = @explode('|',$store_grade['sg_function']);
		if(!empty($sg_fun) && is_array($sg_fun)){
			foreach($sg_fun as $fun){
				if ($fun == 'editor_multimedia'){
					$editor_multimedia = true;
				}
			}
		}
		Tpl::output('editor_multimedia',$editor_multimedia);
		if(intval($store_grade['sg_goods_limit']) != 0) {
			if($goods_num >= $store_grade['sg_goods_limit']) {
				showMessage($lang['store_goods_index_goods_limit'].$store_grade['sg_goods_limit'].$lang['store_goods_index_goods_limit1'],'index.php?act=store_goods&op=goods_list','html','error');
			}
		}
		if(intval($store_info['store_end_time']) != 0) {
			if(time() >= $store_info['store_end_time']) {
				showMessage($lang['store_goods_index_time_limit'],'index.php?act=store_goods&op=goods_list','html','error');
			}
		}
	
		if($_GET['step'] == 'first')
		{
                    
                    //汽车馆直接发布商品
                    if($_SESSION['store_id'] == 20){
                       header("Location: ".$GLOBALS['config']['site_url']."/index.php?act=store_goods&op=add_goods&step=two"); 
                    
                    }
                                
                         //获取店铺已发布的商品
                        $model = Model();
                        $checkarr=array();
                        $checkserialstr='';
                        $whereserial='';
                        $store_goods_list = $model->table('goods')->field('goods_id')->where("store_id = '".$_SESSION['store_id']."'")->select();   
                        if($store_goods_list){
                            foreach($store_goods_list as $val){
                               $store_goods_spec_list = $model->table('goods_spec')->field('spec_goods_serial')->where("goods_id = '".$val['goods_id']."'")->select();   //获取对应规格的商品
                                if($store_goods_spec_list){
                                    foreach($store_goods_spec_list as $vaspec){
                                        $checkarr[]=$vaspec['spec_goods_serial'];
                                     }
                                }
                            }
                        }
                        
                        if(is_array($checkarr)){
                            $checkserialstr=  implode(',', $checkarr);
                               foreach($checkarr as $val){
                                 $whereserial.="  AND goods_article_num <>'$val'";
                               }
                          
                        }else{
                                 $whereserial='';
                        }
	
			$model = Model('goods_records');
			$store_model = Model('store');
			$goods_class = Model('goods_class');
			$page = new page(); 
                        
                        
                        
                        

			if(!empty($_GET['goods_name']) and $_GET['goods_name']!='请输入商品名称')
			{
				if($_SESSION['store_id'] == 2)
				{
					$goods_records_done_list = $model->where('examine = 1 and goods_name like "%'.$_GET['goods_name'].'%" ')->order('id desc')->page(10)->select();																							
				
				}else{
				
					$store_info = $store_model->getOne($_SESSION['store_id']);
		
					//获取对应顶级分类的所有子分类id(便于获取对应分类产品)
					$father_class = explode(",",$store_info['store_top_category']);				
				 $sec_class = explode(",",$store_info['store_second_category']);         
					foreach($father_class as $key=>$value)
					{
                                             
						$brr = $goods_class->getChildClass($value);
                                              
						foreach($brr as $k=>$v)
						{
						      
                              if(in_array($v['gc_id'],$sec_class)){
                            	$arr[$value][] = $v['gc_id'];
                                }
						}
					}
                    $crr = [];//获取二级分类
					foreach($arr as $key=>$val)
					{
						$drr.="'".$key."',";
						foreach($val as $k=>$v)
						{
                            $crr[] = $v;
						}
					}
					$drr = substr($drr,0,-1);	
					$crr = implode(',',$crr);
					if(!empty($crr) && !empty($drr))
					{
                                         
						$goods_records_done_list = $model->where(" `goods_provider`='".$store_info['store_goods_provider']."' and `examine` = 1 and `top_category` in (".$drr.") and `second_category` in (".$crr.") and goods_name like '%".$_GET['goods_name']."%' ".$whereserial."")->page(10)->order('id desc')->select();											
					}
				
				}

			}else
			{
                           
				if($_SESSION['store_id'] == 2)
				{
					
					$goods_records_done_list = $model->where(array('examine'=>1))->order('id desc')->page(10)->select();			
				}else
				{
					$store_info = $store_model->getOne($_SESSION['store_id']);
	
					//获取对应顶级分类的所有子分类id(便于获取对应分类产品)
					$father_class = explode(",",$store_info['store_top_category']);	
                    $sec_class = explode(",",$store_info['store_second_category']);   
					foreach($father_class as $key=>$value)
					{
                                             
						$brr = $goods_class->getChildClass($value);
                                              
						foreach($brr as $k=>$v)
						{
						      
                              if(in_array($v['gc_id'],$sec_class)){
                            	$arr[$value][] = $v['gc_id'];
                                }
						}
					}


                                      
					foreach($arr as $key=>$val)
					{
						$drr.="'".$key."',";
						foreach($val as $k=>$v)
						{
							$crr .= "'".$v."',";						
						}
					
					}
                    $crr = substr($crr,0,-1);	
					$drr = substr($drr,0,-1);

	
					if(!empty($crr) && !empty($drr))
					{
                                      
						$goods_records_done_list = $model->where(" `goods_provider`='".$store_info['store_goods_provider']."' and `examine` = 1 and `top_category` in (".$drr.") and `second_category` in (".$crr.") ".$whereserial." ")->page(10)->order('id desc')->select();	
                                                
                                               
                                                
                                               
					}

				}
								
			}	
                        
                       
                        
			
			$page = $model->showpage();
		
			//S脚部文章输出
			$list=$this->_article();
			//E脚部文章输出
			
			//Tpl::goods_sell('menu_sign', 'first');
			Tpl::output('menu_sign','goods_sell');
			Tpl::output('goods_records_done_list', $goods_records_done_list);
                     
			Tpl::output('page',$page);
			Tpl::showpage('store_goods_add_first');			
						
		}elseif( $_GET['step'] == 'one' ){
			
			//商品发布
			$model_class	= Model('goods_class');
			$param_array	= array();
            if($_SESSION['store_id']=='20'){
               $param_array['gc_parent_id']	= '388'; 
            }else{
			$param_array['gc_parent_id']	= '0';
            }
			$param_array['gc_show']	= '1';
		
			$goods_class	= $model_class->getClassList($param_array);
			
			
			$model_staple	= Model('goods_class_staple');
			$param_array	= array();
			$param_array['store_id']	= $_SESSION['store_id'];
			$staple_array	= $model_staple->getStapleList($param_array);
		
			//S脚部文章输出
			$list=$this->_article();
			//E脚部文章输出
			
			$model = Model('goods_records');
			$row = $model->where(array('store_id'=>$_SESSION['store_id'],'id'=>$_GET['id']))->find();
			
			
			Tpl::output('staple_array', $staple_array);
			Tpl::output('row', $row);
			Tpl::output('goods_class', $goods_class);
			Tpl::output('menu_sign', 'add_goods_step1');
			Tpl::showpage('store_goods_add_step1');
			
		}else if ($_GET['step'] == 'two'){
                    
                    
               
                    //判断是否汽车馆
                  
			$model_class		= Model('goods_class');
			$model_class_tag	= Model('goods_class_tag');

			$gc_child_list		= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['class_id'])));

			if($gc_child_list && $_SESSION['store_id'] != 20){
			//	showMessage($lang['store_goods_index_again_choose_category'], '', '', 'error');
			}
			$goods_class		= $model_class->getGoodsClassLineForTag(intval($_GET['class_id']));
                       
			if(!$goods_class && $_SESSION['store_id'] != 20){
				showMessage($lang['store_goods_index_again_choose_category'], '', '', 'error');
			}
			Tpl::output('goods_class',$goods_class);
			
                           

			$model_staple = Model('goods_class_staple');
			$param_array = array();
			$param_array['staple_name']	= $goods_class['gc_tag_name'];
			$param_array['gc_id']		= $goods_class['gc_id'];
			$param_array['type_id']		= $goods_class['type_id'];
			$param_array['store_id']	= $_SESSION['store_id'];
			$param_array['staple_id']	= $model_staple->getStapleOne($param_array, 'staple_id');
	
			if(!$param_array['staple_id']){
				if(intval($model_staple->countStaple($_SESSION['store_id'])) < 20){
					unset($param_array['staple_id']);
					$param_array['staple_id']	= $model_staple->addStaple($param_array);
				}
			}
	
			if($goods_class['type_id'] != '0'){
		
				$model_type	= Model('type');
				
				$spec_list 	= $model_type->typeRelatedJoinList(array('type_id'=>$goods_class['type_id']), 'spec', 'spec.sp_id as sp_id, spec.sp_name as sp_name, spec.sp_format as sp_format, spec_value.sp_value_id as sp_value_id, spec_value.sp_value_name as sp_value_name, spec_value.sp_value_image as sp_value_image');
				$attr_list	= $model_type->typeRelatedJoinList(array('type_id'=>$goods_class['type_id']), 'attr', 'attribute.attr_id as attr_id, attribute.attr_name as attr_name, attribute_value.attr_value_id as attr_value_id, attribute_value.attr_value_name as attr_value_name');
				$brand_list	= $model_type->typeRelatedJoinList(array('type_id'=>$goods_class['type_id'],'brand_apply'=>1), 'brand', 'brand.brand_id as brand_id,brand.brand_name as brand_name');
				
				$spec_json	= array();
                                
                              
				if(is_array($spec_list) && !empty($spec_list)){
					$array		= array();
					foreach ($spec_list as $val){
						$a	= array();
						$a['sp_value_id']	= $val['sp_value_id'];
						$a['sp_value_name']	= $val['sp_value_name'];
						$a['sp_value_image']= $val['sp_value_image'];
						
						$array[$val['sp_id']]['sp_name']	= $val['sp_name'];
						$array[$val['sp_id']]['sp_format']	= $val['sp_format'];
						$array[$val['sp_id']]['value'][]	= $a;
                        
						
						
						$spec_json[$val['sp_id']][$val['sp_value_id']]['sp_value_name']	= $val['sp_value_name'];
						$spec_json[$val['sp_id']][$val['sp_value_id']]['sp_value_image']= $val['sp_value_image'];
						$spec_json[$val['sp_id']][$val['sp_value_id']]['sp_format']		= $val['sp_format'];
					}
					$spec_list = $array;
				}
				
				if(is_array($attr_list) && !empty($attr_list)){
					$array = array();
						foreach ($attr_list as $val){
						$a	= array();
						$a['attr_value_id']		= $val['attr_value_id'];
						$a['attr_value_name']	= $val['attr_value_name'];
						
						$array[$val['attr_id']]['attr_name']	= $val['attr_name'];
						$array[$val['attr_id']]['value'][]		= $a;
					}
					$attr_list = $array;
				}
				
				Tpl::output('sign_i', count($spec_json));

				Tpl::output('spec_list', $spec_list);
				Tpl::output('attr_list', $attr_list);
				Tpl::output('brand_list',$brand_list);
			}
			
			if($brand_list == ''){
				$model_brand	= Model('brand');
				$condition		= array('brand_apply'=>1);
				$brand_list		= $model_brand->getBrandList($condition);
				Tpl::output('brand_list',$brand_list);
			}
			
		
			$model_store_class	= Model('my_goods_class');

			$store_goods_class	= $model_store_class->getClassTree(array('store_id'=>$_SESSION['store_id'],'stc_state'=>'1'));
			Tpl::output('store_goods_class',$store_goods_class);
			

			
			$hour_array		= array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
			Tpl::output('hour_array',$hour_array);
			$minute_array	= array('05','10','15','20','25','30','35','40','45','50','55');
			Tpl::output('minute_array',$minute_array);
	
			if(!empty($_GET) && $_SESSION['store_id'] != 20)
			{
				$goods_record = Model('goods_records');
				$attr = $goods_record->where("id='".$_GET['id']."'")->find();
                          
                               
				Tpl::output('goods_name',$attr['goods_name']);
                                Tpl::output('top_category',$attr['top_category']);//顶级分类
                                Tpl::output('second_category',$attr['second_category']);//二级分类
				Tpl::output('declaration_price',$attr['declaration_price']);
				Tpl::output('goods_number',$attr['goods_number']);
				Tpl::output('product_num',$attr['product_num']);
				Tpl::output('ieflag',$attr['ieflag']);
				Tpl::output('goods_custom_num',$attr['goods_custom_num']);
				Tpl::output('goods_article_num',$attr['goods_article_num']);//货号
				Tpl::output('gross_weight',$attr['gross_weight']);
				Tpl::output('net_weight',$attr['net_weight']);
				Tpl::output('declaration_unit',$attr['declaration_unit']);
				Tpl::output('goods_country',$attr['goods_country']);//国家
				Tpl::output('goods_provider',$attr['goods_provider']);//供应商
                                Tpl::output('tax_num',$attr['tax_num']);//行邮税
                                Tpl::output('foreign_language',$attr['foreign_language']);//商品外文
                		
                                if($attr['tax_num']){
                                //获取并匹配行邮税
                                $param['table'] = 'taxcontrast';
                                $param['field'] = 'taxnum';
                                $param['value'] = $attr['tax_num'];
                                $result = Db::getRow($param);
                                Tpl::output('tax_money',$result['tanrate']);//行邮税率
                               }
                                
				Tpl::output('id',$attr['id']);				
			}
                   
			//S脚部文章输出
			$list=$this->_article();
			//E脚部文章输出
			
			Tpl::output('item_id','');
			Tpl::output('menu_sign','add_goods_stpe2');
                    
                    
                    
			Tpl::showpage('store_goods_add_step2');
		}
	}
        
        
        public function goods_records_listOp(){

            $goods_records_id = $_GET['goods_records_id'];
           
             $gc_id = $_GET['gc_id'];
            
             $model = Model(); 
             if (!empty($goods_records_id)){                   
                   $is_release  = 0;
                   $str =  'id ="'.$goods_records_id.'"';
                 
                   
                   //$goods_records_one = $model->table('goods_records')->where(array('id'=>$goods_records_id,'is_release'=>$is_release))->select();  
                  $goods_records_one = $model->table('goods_records')->where($str)->select(); 
                 
                 
                    //商品顶级分类
                  // $top_category = $goods_records_one[0]['top_category'];

                    //商品二级分类
                   $second_category = $goods_records_one[0]['second_category']; 
             } else{
                 
                 $second_category = $gc_id;
             }
             
             
           //获取店铺已发布的商品
           $checkarr=array();
           $checkserialstr='';
           $whereserial='';
           $store_goods_list = $model->table('goods')->field('goods_id')->where("store_id = '".$_SESSION['store_id']."'")->select();   
           if($store_goods_list){
               foreach($store_goods_list as $val){
                  $store_goods_spec_list = $model->table('goods_spec')->field('spec_goods_serial')->where("goods_id = '".$val['goods_id']."'")->select();   //获取对应规格的商品
                   if($store_goods_spec_list){
                       foreach($store_goods_spec_list as $vaspec){
                           $checkarr[]=$vaspec['spec_goods_serial'];
                        }
                   }
               }
           }
           
          
//           if(is_array($checkarr)){
//            //$checkserialstr=  implode(',', $checkarr);
//               foreach($checkarr as $val){
//                $whereserial.=$whereserial?"  AND goods_article_num ='$val'":" goods_article_num ='$val'";
//                              $whereserial.="  AND goods_article_num ='$val'";
//               }
//               $whereserial=" AND (".$whereserial.")";
//           }else{
//               $whereserial='';
//           }
          
           //if($checkserialstr){
             // $whereserial=" AND goods_article_num in ($checkserialstr)";
          // }else{
           //    $whereserial='';
          // }

           //获取同一等级的备案商品信息
            $goods_records_list = $model->table('goods_records')->where("second_category = '".$second_category."'")->select(); 
            
            if($goods_records_list){
                foreach($goods_records_list as $key=>$val){
                    if(is_array($checkarr)){
                        if(!in_array($val['goods_article_num'],$checkarr)){
                             $goods_records_list[$key]=$val;
                           // echo $val['goods_article_num'].',';
                        }else{
                            unset($goods_records_list[$key]);
                        }
                    }
                }
            }
           
                Tpl::output('goods_records_list',$goods_records_list);
                
                Tpl::output('name',$_GET['name']);
           
           
		Tpl::showpage('store_goods_records_list','null_layout');

	}
    
    
    //获取汽配明细
    public function get_car_detail($list,$suffix=''){
        
      
        foreach($list as $ey=>$al){
            $content=unserialize($al['spec_d_specvalue']);
            $spec_goods_spec=unserialize($al['spec_goods_spec']);
            $idstr='';
            $specstr='';
            if($spec_goods_spec){
           foreach($spec_goods_spec as $keyaa=>$valaa){
                $idstr.=$keyaa;
                $specstr.=$specstr?'-'.$valaa:$valaa;
            }}
            if($content){
            foreach($content as $key=>$val){
                
                $i=0;
                foreach($val['c_detail'] as $keys=>$vals){
                    if($i==0){
                         $i++;
                        continue;
                    }
                   if($suffix){
                    $carlist["i_".$idstr][$keys]['specstr']=$specstr;
                    $carlist["i_".$idstr][$keys]['c_type']=$val['c_type'][0];
                    $carlist["i_".$idstr][$keys]['c_detail']=$val['c_detail'][$keys];
                    $carlist["i_".$idstr][$keys]['c_num']=$val['c_num'][$keys];
                    $carlist["i_".$idstr][$keys]['c_price']=$val['c_price'][$keys];
                    $carlist["i_".$idstr][$keys]['c_omodel']=$val['c_omodel'][$keys];
                    $carlist["i_".$idstr][$keys]['c_rbrand']=$val['c_rbrand'][$keys];
                    $carlist["i_".$idstr][$keys]['c_rmodel']=$val['c_rmodel'][$keys];
                    $carlist["i_".$idstr][$keys]['c_cost']=$val['c_cost'][$keys];
                   }else{
                    
                    $carlist[$keys]['c_type']=$val['c_type'][0];
                    $carlist[$keys]['c_detail']=$val['c_detail'][$keys];
                    $carlist[$keys]['c_num']=$val['c_num'][$keys];
                    $carlist[$keys]['c_price']=$val['c_price'][$keys];
                    $carlist[$keys]['c_omodel']=$val['c_omodel'][$keys];
                    $carlist[$keys]['c_rbrand']=$val['c_rbrand'][$keys];
                    $carlist[$keys]['c_rmodel']=$val['c_rmodel'][$keys];
                    $carlist[$keys]['c_cost']=$val['c_cost'][$keys];
                    
                   }
                   
                  
                   
                   
                   
                }
            }
           }
        }
    
        return $carlist;
    }
	
	public function edit_goodsOp() {
		$model_class		= Model('goods_class');
		if(intval($_GET['class_id']) >0 && intval($_GET['t_id']) >=0){
			$goods_class		= $model_class->getGoodsClassLineForTag(intval($_GET['class_id']));
			if(!$goods_class){
				showMessage($lang['store_goods_index_again_choose_category'], '', '', 'error');
			}
			Tpl::output('goods_class',$goods_class);
                       
		}
		
		Tpl::setLayout('member_goods_marketing_layout');
		$lang	= Language::getLangContent();
		
		$model_store_goods	= Model('goods');
		$goods_array		= $model_store_goods->getGoods(array('goods_id'=>intval($_GET['goods_id'])),'','goods.*');
                
                
                
        if(intval($goods_array[0]['store_id']) !== intval($_SESSION['store_id'])) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $goods_array[0]['goods_col_img']	= unserialize($goods_array[0]['goods_col_img']);

		if ($goods_array[0]['transport_id']>0){
			$model_transport = Model('transport');
			$transport = $model_transport->getRow($goods_array[0]['transport_id']);
			$goods_array[0]['transport_title'] = $transport['title'];	
		}
		
		if($goods_array[0]['goods_image_more'] == ''){
			$goods_image		= $model_store_goods->getListImageGoods(array(
					'image_store_id'=>$_SESSION['store_id'],
					'item_id'=>$goods_array[0]['goods_id'],
					'image_type'=>2
			));
			if(is_array($goods_image) and !empty($goods_image)) {
				$goods_image_more = array();
				foreach ($goods_image as $key => $val) {
					$goods_image_more[]	= str_replace('_small', '_tiny', $val['file_thumb']);
				}
				$goods_array[0]['goods_image_more']	= $goods_image_more;
			}
		}else{
			$goods_array[0]['goods_image_more']	= explode(',', $goods_array[0]['goods_image_more']);
		}
  
        
        Tpl::output('car_detail',$car_detail);     
		Tpl::output('goods',$goods_array[0]);
                
		Tpl::output('goods_id',$goods_array[0]['goods_id']);
		
	
		$model_class_tag	= Model('goods_class_tag');

		if(empty($goods_class) || !is_array($goods_class)){
			$goods_class		= $model_class->getGoodsClassLineForTag($goods_array[0]['gc_id']);
			if(!$goods_class){
				showMessage($lang['store_goods_index_again_choose_category'], '', '', 'error');
			}
			Tpl::output('goods_class',$goods_class);
		}
		
		if($goods_class['type_id'] != '0'){
			$model_type	= Model('type');
			$spec_list 	= $model_type->typeRelatedJoinList(array('type_id'=>$goods_class['type_id']), 'spec', 'spec.sp_id as sp_id, spec.sp_name as sp_name, spec.sp_format as sp_format, spec_value.sp_value_id as sp_value_id, spec_value.sp_value_name as sp_value_name, spec_value.sp_value_image as sp_value_image');
			$attr_list	= $model_type->typeRelatedJoinList(array('type_id'=>$goods_class['type_id']), 'attr', 'attribute.attr_id as attr_id, attribute.attr_name as attr_name, attribute_value.attr_value_id as attr_value_id, attribute_value.attr_value_name as attr_value_name');
			$brand_list	= $model_type->typeRelatedJoinList(array('type_id'=>$goods_class['type_id'],'brand_apply'=>1), 'brand', 'brand.brand_id as brand_id,brand.brand_name as brand_name');
			Tpl::output('brand_list',$brand_list);
		
			$spec_json	= array();
			if(is_array($spec_list) && !empty($spec_list)){
				$array		= array();
				foreach ($spec_list as $val){
					$a	= array();
					$a['sp_value_id']	= $val['sp_value_id'];
					$a['sp_value_name']	= $val['sp_value_name'];
					$a['sp_value_image']= $val['sp_value_image'];
					
					$array[$val['sp_id']]['sp_name']	= $val['sp_name'];
					$array[$val['sp_id']]['sp_format']	= $val['sp_format'];
					$array[$val['sp_id']]['value'][]	= $a;
					
					
					$spec_json[$val['sp_id']][$val['sp_value_id']]['sp_value_name']	= $val['sp_value_name'];
					$spec_json[$val['sp_id']][$val['sp_value_id']]['sp_value_image']= $val['sp_value_image'];
					$spec_json[$val['sp_id']][$val['sp_value_id']]['sp_format']		= $val['sp_format'];
				}
				$spec_list = $array;
			}
			if(is_array($attr_list) && !empty($attr_list)){
				$array = array();
					foreach ($attr_list as $val){
					$a	= array();
					$a['attr_value_id']		= $val['attr_value_id'];
					$a['attr_value_name']	= $val['attr_value_name'];
					
					$array[$val['attr_id']]['attr_name']	= $val['attr_name'];
					$array[$val['attr_id']]['value'][]		= $a;
				}
				$attr_list = $array;
			}
			
			if(is_array($spec_json) && !empty($spec_json)){
				$i = '0';
				foreach ($spec_json as $val){
					if (strtoupper(CHARSET) == 'GBK'){
						$val = Language::getUTF8($val);
					}
					Tpl::output('spec_json_'.$i, json_encode($val));
					$i++;
				}
				Tpl::output('sign_i', $i);
			}
			
			Tpl::output('spec_list', $spec_list);
                        
			Tpl::output('attr_list', $attr_list);
		}
		
		if($brand_list == ''){
			$model_brand	= Model('brand');
			$condition		= array('brand_apply'=>1);
			$brand_list		= $model_brand->getBrandList($condition);
			Tpl::output('brand_list',$brand_list);
		}
		
	
		$model_type		= Model('type');
		$spec_checked	= $model_type->typeRelatedList('goods_spec_index', array('goods_id'=>intval($goods_array[0]['goods_id'])), 'sp_value_id,sp_value_name');
		$attr_checked_l	= $model_type->typeRelatedList('goods_attr_index', array('goods_id'=>intval($goods_array[0]['goods_id'])), 'attr_value_id');
		if(is_array($attr_checked_l) && !empty($attr_checked_l)){
			$attr_checked = array();
			foreach ($attr_checked_l as $val){
				$attr_checked[] = $val['attr_value_id'];
			}
		}
		Tpl::output('attr_checked', $attr_checked);
		Tpl::output('spec_checked', $spec_checked);

	
		$spec_value	= $model_type->typeRelatedList('goods_spec', array('goods_id'=>intval($goods_array[0]['goods_id'])));
        
        $suffix=(count($spec_value)>1)?'s':'';
        $car_detail=$this->get_car_detail($spec_value,$suffix);
         
        Tpl::output('car_detail', $car_detail);
      
        Tpl::output('spec_value', $spec_value);
        
		$sp_value = array();
		if(is_array($spec_value) && !empty($spec_value)){
		foreach ($spec_value as $k=>$v) {
			preg_match_all("/i:(\d+)/s",$v['spec_goods_spec'],$matchs);
			$id = str_replace(',','',implode(',',$matchs[1]));
			$sp_value['i_'.$id.'|price'] = $v['spec_goods_price'];
			$sp_value['i_'.$id.'|stock'] = $v['spec_goods_storage'];
			$sp_value['i_'.$id.'|sku'] = $v['spec_goods_serial'];
            $sp_value['i_'.$id.'|market_price'] = $v['spec_market_price'];
           
		}
		}
       //print_r($sp_value);
		Tpl::output('sp_value', $sp_value);

		
		$model_store_class	= Model('my_goods_class');
		$store_goods_class	= $model_store_class->getClassTree(array('store_id'=>$_SESSION['store_id'],'stc_state'=>'1'));
		Tpl::output('store_goods_class',$store_goods_class);
		$store_class_goods	= $model_store_goods->getStoreClassGoods($goods_array[0]['goods_id']);
		Tpl::output('store_class_goods',$store_class_goods);

		$model_store_grade	= Model('store_grade');
		$store_grade	= $model_store_grade->getOneGrade($_SESSION['grade_id']);
		$editor_multimedia = false;
		$sg_fun = @explode('|',$store_grade['sg_function']);
		if(!empty($sg_fun) && is_array($sg_fun)){
			foreach($sg_fun as $fun){
				if ($fun == 'editor_multimedia'){
					$editor_multimedia = true;
				}
			}
		}
		Tpl::output('editor_multimedia',$editor_multimedia);

	

		$spec_array		= $model_store_goods->getSpecGoods($goods_array[0]['goods_id']);
		if(is_array($spec_array) and $goods_array[0]['spec_open'] == 1) {
			$spec	= array();
			foreach ($spec_array as $key => $val) {
				$spec[$key]['spec_id'] = $val['spec_id'];
				$spec[$key]['goods_id'] = $val['goods_id'];
				$spec[$key]['spec_1'] = $val['spec_name_1'];
				$spec[$key]['spec_2'] = $val['spec_name_2'];
				$spec[$key]['color_rgb'] = $val['spec_goods_color'];
				$spec[$key]['price'] = $val['spec_goods_price'];
				$spec[$key]['stock'] = $val['spec_goods_storage'];
				$spec[$key]['sku'] = $val['spec_goods_serial'];
			}
		}
		$spec_num	= 0;
		if(($goods_array[0]['spec_name_1'] !='' or $goods_array[0]['spec_name_2'] !='') and $goods_array[0]['spec_open'] == 1) {
			$spec_num = 1;
		} elseif ($goods_array[0]['spec_name_1'] !='' and $goods_array[0]['spec_name_2'] !=''  and $goods_array[0]['spec_open'] == 1) {
			$spec_num = 2;
		} else {
			$spec_num = 0;
			Tpl::output('goods_storage',$spec_array[0]['spec_goods_storage']);
			Tpl::output('store_base',$spec_array[0]['store_base']);
		}
		Tpl::output('spec_num',$spec_num);
		$spec_json	= array('spec_qty'=>$spec_num,'spec_name_1'=>($goods_array[0]['spec_name_1']=='' ? $lang['store_goods_index_color'] : $goods_array[0]['spec_name_1']),
								'spec_name_2'=>($goods_array[0]['spec_name_2']=='' ? 		$lang['store_goods_index_spec'] : $goods_array[0]['spec_name_2']),
								'specs'=>$spec);
		
		if (strtoupper(CHARSET) == 'GBK'){
			$spec_json = Language::getUTF8($spec_json);
		}
		
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		
		$spec_json	= json_encode($spec_json);
		Tpl::output('spec_json',$spec_json);
		
		
		$hour_array		= array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
		Tpl::output('hour_array',$hour_array);
		$minute_array	= array('05','10','15','20','25','30','35','40','45','50','55');
		Tpl::output('minute_array',$minute_array);

		//编辑商品
		$goods = Model('goods');
		$goods_records = Model('goods_records');
		//$good_info = $goods->where('goods_id ='.$_GET['goods_id'])->find();
		$good_info = $goods->getGoods(array('goods_id'=>$_GET['goods_id']));
              
             
		$goods_records_info = $goods_records->where('goods_article_num = "'.$good_info[0]['goods_serial'].'"')->find();
       // if(!$goods_records_info){
        //    showMessage($lang['store_goods_no_tax'], '', '', 'error'); 
       // }
        Tpl::output('tax_num',$goods_records_info['tax_num']);
        Tpl::output('tax_money',$goods_records_info['tax_money']);
       
        Tpl::output('good_info',$good_info[0]);        
                
		/*
		Tpl::output('goods_name',$good_info[0]['goods_name']);
        Tpl::output('foreign_language',$good_info[0]['foreign_language']);
		Tpl::output('declaration_price',$good_info[0]['declaration_price']);
		Tpl::output('goods_number',$good_info[0]['goods_number']);
		Tpl::output('gross_weight',$good_info[0]['gross_weight']);
		Tpl::output('net_weight',$good_info[0]['net_weight']);
		Tpl::output('ieflag',$good_info[0]['ieflag']);
		Tpl::output('declaration_unit',$good_info[0]['declaration_unit']);//商品外文
		Tpl::output('product_num',$good_info[0]['product_num']);
		Tpl::output('goods_custom_num',$good_info[0]['goods_custom_num']);
		Tpl::output('goods_article_num',$good_info[0]['goods_article_num']);
        Tpl::output('goods_country',$good_info[0]['goods_country']);//国家
		Tpl::output('goods_provider',$good_info[0]['goods_provider']);//供应商*/
		
               

      
      
	
		
		Tpl::output('item_id',$goods_array[0]['goods_id']);
		Tpl::output('menu_sign','add_goods_stpe2');
		Tpl::showpage('store_goods_add_step2');
	}
	
    
    //行邮税率对照表
    private function taxcontrastOp(){
        $f = fopen ("./xingyoushui.txt", "r");
        $ln= 0;
        while (! feof ($f)) {
            $line= fgets ($f);
            ++$ln;
            //printf ("%2d: ", $ln);
            if ($line===FALSE) print ("FALSE\n");
            else $str.=$line;
        }
        fclose ($f);
       // echo $str;
        $qian=array(" ","　","\t","\r");$hou=array("","","","");
        $str= str_replace($qian,$hou,$str);    
        
        $arr=explode("\n",$str);
        //print_r($arr);
        
        foreach($arr as $val){
           $tanrate = substr($val,8,2);
           $tanrate=$tanrate/100;
           $taxnum = substr($val,0,8);
           if($tanrate){
                $array['taxnum']=$taxnum;
                $array['tanrate']=$tanrate;
               // DB::insert("taxcontrast",$array);
           }
        }
    }
    
    
	public function save_goodsOp() {
           
          
		$lang	= Language::getLangContent();
		if (chksubmit()){		
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["goods_name"],"require"=>"true","message"=>$lang['store_goods_index_goods_name_null']),
			//array("input"=>$_POST["goods_price"],"require"=>"true","validator"=>"Double","message"=>$lang['store_goods_index_goods_price_null']),
                       // array("option"=>$_POST["goods_mail"],"require"=>"true","message"=>$lang['store_goods_mail_error']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($lang['error'].$error,'','html','error');
			}
			
			$model_store_goods	= Model('goods');
                      
                     
			$goods_array	= array();
			$goods_array['goods_name']				= $_POST['goods_name'];
            $goods_array['foreign_language']     = $_POST['foreign_language'];//商品外文
			$goods_array['gc_id']					= $_POST['cate_id'];
			$goods_array['gc_name']					= $_POST['cate_name'];
			$goods_array['brand_id']				= $_POST['brand_id'];
			$goods_array['spec_open']				= '1';
			$goods_array['goods_store_price']		= $_POST['goods_store_price'];
			$goods_array['goods_store_price_interval']= $_POST['goods_store_price_interval'];		
			$goods_array['goods_serial']			= $_POST['goods_serial'];
			$goods_array['goods_commend']			= $_POST['goods_commend'];
			$goods_array['goods_body']				= $_POST['goods_body'];
			$goods_array['goods_keywords']			= addslashes($_POST['seo_keywords']);
			$goods_array['goods_description']		= $_POST['seo_description'];
			$goods_array['product_num']		= $_POST['product_num'];//商品序号
			$goods_array['gross_weight']		= $_POST['gross_weight'];//毛重
			$goods_array['net_weight']		= $_POST['net_weight'];//净重
			$goods_array['ieflag']		= $_POST['ieflag'];//进出口标志
			$goods_array['declaration_unit']		= $_POST['declaration_unit'];//申报计量单位
			$goods_array['goods_custom_num']		= $_POST['goods_custom_num'];//商品海关备案号
			$goods_array['goods_num']		= $_POST['goods_num'];
			$goods_array['country']		= $_POST['country'];//国家
			$goods_array['provider']		= $_POST['provider'];//供应商
			$goods_array['tax_num']		= $_POST['tax_num'];//行邮税编号
            $goods_array['tax_money']   = $_POST['tax_money'];//行邮税率
            $goods_array['goods_mail']		= $_POST['goods_mail'];//邮寄方式
            $goods_array['car_accessory']		= $_POST['car_accessory'];//车配
            $goods_array['four_s_price']		= $_POST['four_s_price'];//4s店价格
            $goods_array['market_price']		= $_POST['market_price'];//4s店价格
            
            
            
           
                    
                    
            
         /*  $d_specid_array		= $_POST['d_specid'];
           $arr = explode("\n",$d_specid_array);
           $dspecarr['type']=$arr[0]; 
           unset($arr[0]);
           foreach($arr as $key=>$val){
                $arr=array();
                $arr = explode(":",$val[$key]);
                $dspecarr['detail']=$arr[0];
               
                $arrs = explode("|",$arr[1]);
                
                $dspecarr['d_num']=$arrs[0];
                $dspecarr['d_price']=$arr[1];
                $dspecarr['d_omodel']=$arr[2];
                $dspecarr['d_rbrand']=$arr[3];
                $dspecarr['d_rmodel']=$arr[4];
                $dspecarr['d_cost']=$arr[5];
                $dspecarr['d_specid']=$arr[0];
                $dspecarr['d_addtime']=$arr[0];                
                
           }
           
            */
           
            
			
			$goods_array['transport_id']			= ($_POST['isApplyPostage'] == '0') ? '0' : intval($_POST['transport_id']);	//运费模板
			$goods_array['py_price']				= intval($_POST['py_price']);
			$goods_array['kd_price']				= intval($_POST['kd_price']);
			$goods_array['es_price']				= intval($_POST['es_price']);
			
			$goods_array['city_id']					= intval($_POST['city_id']);
			$goods_array['province_id']				= intval($_POST['province_id']);
			
			//$goods_array['goods_image']				= str_replace('_tiny', '_small', $_POST['image_path'][0]);
			$goods_array['goods_image']				= str_replace('_tiny', '_mid', $_POST['image_path'][0]);
			$goods_array['goods_image_more']		= implode(',',$_POST['image_path']);
			
			$goods_array['goods_transfee_charge']	= $_POST['goods_transfee_charge'];
			$goods_array['type_id']					= $_POST['type_id'];
			if (is_array($_POST['spec'])) {
				$goods_array['goods_spec']			= $_POST['sp_val'];
			}else{
				$goods_array['goods_spec']			= null;
			}
			$goods_array['goods_attr']				= $_POST['attr'];
			$goods_array['spec_name']				= $_POST['sp_name'];
			$goods_array['goods_form']				= $_POST['goods_form'];
			/*
			switch ($_POST['goods_show']){
				case 0:
					$goods_array['goods_show']		= 0;
					$goods_array['goods_starttime']	= time()-14*86400;
					$goods_array['goods_endtime']	= time()-7*86400;
					break;
				case 1:
					$goods_array['goods_show']		= 1;
					$goods_array['goods_starttime']	= time();
					$goods_array['goods_endtime']	= time()+C('product_indate')*86400;
					break;
				case 2:
					$goods_array['goods_show']		= 0;
					$goods_array['goods_starttime']	= strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
					$goods_array['goods_endtime']	= strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60 + C('product_indate')*86400;
					if($goods_array['goods_starttime'] <= time() && time() < $goods_array['goods_endtime'] ){
						$goods_array['goods_show']		= 1;
					}
					break;
			}*/				
			switch ($_POST['goods_show']){
				case 0:
					$goods_array['goods_show']		= 0;
					$goods_array['goods_starttime']	= time()-14*86400;
					$goods_array['goods_endtime']	= time()-7*86400;
					break;
				case 1:
					$goods_array['goods_show']		= 1;
					$goods_array['goods_starttime']	= time();
					$goods_array['goods_endtime']	= time()+C('product_indate')*86400;
					break;
				case 2:
					$goods_array['goods_show']		= 0;
					$goods_array['goods_starttime']	= strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
					$goods_array['goods_endtime']	= strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60 + C('product_indate')*86400;
					if($goods_array['goods_starttime'] <= time() && time() < $goods_array['goods_endtime'] ){
						$goods_array['goods_show']		= 1;
					}
					break;
			}
			$model_store = Model('store');
			$store_info = $model_store->shopStore(array(
				'store_id'=>$_SESSION['store_id']
			));
			if ($store_info['store_state'] == 0){
				$goods_array['goods_store_state'] = 1;
			}
                     
			unset($store_info);
            //添加成功返回goods_id
			$state = $model_store_goods->saveGoods($goods_array);
			
			if($state) {
                            
				$update_array = array();
                               
//                                if(empty($_POST['spec'])){
//                                    
//                                   showMessage($lang['store_goods_index_spec_error'], '', '', 'error');//没有填写规格提示错误
//                                   exit();
//                                }
                
                 //$d_specid_array		= $_POST['d_specid'];//明细
                 $d_specid_open=0;
                       
				if (is_array($_POST['spec'])) {
                                    $spec_array	= $_POST['spec'];
                                    //$d_specid_array	= $_POST['d_specids'];
                                    
                                   
                                    
                                    $d_specid_open=1;
                                    $i=1;
                                    foreach ($spec_array as $key=> $val){
                                    $d_specid=array();
                                         if($val['sku']){

                                             //根据商品货号查找行邮税编号
                                            $model = Model();
                                            $records_date = $model->table('goods_records')->where(array('goods_article_num'=>$val['sku']))->select();  

                                            foreach($records_date as $k => $v){
                                             //获取并匹配行邮税
                                             $param['table'] = 'taxcontrast';
                                             $param['field'] = 'taxnum';
                                             $param['value'] = $v['tax_num'];
                                             $result = Db::getRow($param);
                                             $spec_array[$key]['goods_tax'] =$result['tanrate'];
                                             $spec_array[$key]['goods_tax_num'] = $result['taxnum'];
                                            }
                                        }   
                                         $spec_array[$key]['market_price'] =$val['market_price']; 
                                         
                                          //车配明细记录
                                        if($_POST['c_types'][$key]){
                                           $d_specid[$key]['c_type']=$_POST['c_types'][$key];  
                                           $d_specid[$key]['c_detail']=$_POST['c_details'][$key];  
                                           $d_specid[$key]['c_num']=$_POST['c_nums'][$key];
                                           $d_specid[$key]['c_price']=$_POST['c_prices'][$key];
                                           $d_specid[$key]['c_omodel']=$_POST['c_omodels'][$key];
                                           $d_specid[$key]['c_rbrand']=$_POST['c_rbrands'][$key];
                                           $d_specid[$key]['c_rmodel']=$_POST['c_rmodels'][$key];
                                           $d_specid[$key]['c_cost']=$_POST['c_costs'][$key];  
                                           // print_r($_POST['c_detail']);
                                            
                                          //  $d_specid_array	= serialize($d_specid);//明细
                                            $spec_array[$key]['spec_d_specvalue'] =serialize($d_specid);  
                                        }
                                         $i++;   
                                    }
                                    
				} else {
                    //单件规格
                    
                    
                    
                    //车配明细记录
                    if($_POST['c_type']){
                       $d_specid[0]['c_type']=$_POST['c_type'];  
                       $d_specid[0]['c_detail']=$_POST['c_detail'];  
                       $d_specid[0]['c_num']=$_POST['c_num'];
                       $d_specid[0]['c_price']=$_POST['c_price'];
                       $d_specid[0]['c_omodel']=$_POST['c_omodel'];
                       $d_specid[0]['c_rbrand']=$_POST['c_rbrand'];
                       $d_specid[0]['c_rmodel']=$_POST['c_rmodel'];
                       $d_specid[0]['c_cost']=$_POST['c_cost'];  
                       // print_r($_POST['c_detail']);
                        
                        $d_specid_array	= serialize($d_specid);//明细
                    }
                    
                    
                    
                    $model = Model();
                    $records_date = $model->table('goods_records')->where(array('goods_article_num'=>$goods_array['goods_serial']))->find();
                    //获取并匹配行邮税
                             $param['table'] = 'taxcontrast';
                             $param['field'] = 'taxnum';
                             $param['value'] = $records_date['tax_num'];
                             $result = Db::getRow($param);  
                             $goods_tax['goods_tax'] =$result['tanrate'];
                             $goods_tax_num['goods_tax_num'] = $result['taxnum'];
                    
                                        
					$spec_array[0]['price']	= $goods_array['goods_store_price'];//价钱
					$spec_array[0]['stock']	= $_POST['goods_storage'];//库存
					$spec_array[0]['store_base']	= $_POST['store_base'];//库存基数
					$spec_array[0]['sku']	= $goods_array['goods_serial'];//货号
                    $spec_array[0]['goods_tax']			= trim( $goods_tax['goods_tax']);
                    $spec_array[0]['goods_tax_num']		= trim($goods_tax_num['goods_tax_num']);
                    $spec_array[0]['market_price']	= $goods_array['market_price'];
                    $spec_array[0]['spec_d_specvalue']	= $d_specid_array;
                    
                    
					$spec_array[0]['goods_tax'] = $_POST['tax_money'];//行邮税
                    $spec_array[0]['goods_tax_num'] = $_POST['tax_num'];//行邮税编号
                                     
					$update_array['spec_open'] = '0';
				}
				
				$spec_id = $model_store_goods->saveSpecGoods($spec_array,$state,$_POST['sp_name']);
				$update_array['spec_id']	= $spec_id;

				$model_store_goods->updateGoods($update_array, $state);

				$model_type = Model('type');
				
				$sa_array = array();
				$sa_array['goods_id'] 	= $state;
				$sa_array['gc_id']		= $_POST['cate_id'];
				$sa_array['type_id']	= $_POST['type_id'];
				if(is_array($_POST['sp_val'])){
					$sa_array['value']		= $_POST['sp_val'];
					$model_type->typeGoodsRelatedAdd($sa_array, 'goods_spec_index', 'spec');
				}
				
			
				if(is_array($_POST['attr'])){
					$sa_array['value']		= $_POST['attr'];
					$model_type->typeGoodsRelatedAdd($sa_array, 'goods_attr_index');
				}
				
				
				if(is_array($_POST['sgcate_id'])) {
					$new_sgcate_id = $_POST['sgcate_id'];
					$new_sgcate_id = array_unique($new_sgcate_id);
					$model_store_goods->saveStoreClassGoods($new_sgcate_id,$state);
				}
				
				
				if(is_array($_POST['sp_val']['1'])){
					$col_img_array	= array();
					foreach($_POST['sp_val']['1'] as $k=>$v){
						if(!empty($_FILES[$v]['name'])){
							$upload = new UploadFile();
							$upload->set('ifremove', true);
							$upload->set('default_dir',ATTACH_SPEC.DS.$_SESSION['store_id'].DS.$upload->getSysSetPath());
							$upload->set('max_size',C('image_max_filesize'));
							$thumb_width	= '30,'.C('thumb_mid_width');
							$thumb_height	= '30,'.C('thumb_mid_height');
					
							$upload->set('thumb_width',	$thumb_width);
							$upload->set('thumb_height',$thumb_height);
							$upload->set('thumb_ext',	'_tiny,_mid');	
							
							$result = $upload->upfile($v);
							if($result){
								$img_path	= $upload->getSysSetPath().$upload->thumb_image;
								$col_img_array[$v]  =  $col_img_array[$k]  =  $img_path;
							}
						}
					}
					if(!empty($col_img_array)){
						$model_store_goods->updateGoods(array('goods_col_img'=>serialize($col_img_array)),$state);
					}
				}

			
				$data_array = array();
				$data_array['goods_id']				= $state;
				$data_array['store_id']				= $_SESSION['store_id'];
				$data_array['goods_name']			= $goods_array['goods_name'];
				$data_array['goods_image']			= $goods_array['goods_image'];
				$data_array['goods_store_price']	= $goods_array['goods_store_price'];
				$data_array['goods_transfee_charge']= $goods_array['goods_transfee_charge'];
				$data_array['py_price']				= $goods_array['py_price'];
				$this->storeAutoShare($data_array, 'new');
                                  
                                //更改已备案的状态          
                             if(!empty($spec_array)){
                                 $model = Model();
                                 $records_is_release = 1;//备案商品已发布 
                                 foreach ($spec_array as $key => $val){
                                     
                                 $model->table('goods_records')->where(array('goods_article_num'=>$val['sku']))->update(array('is_release'=>$records_is_release));
                                 
                                 }
                             }
				@header("Location: index.php?act=store_goods&op=add_goods_step3&gid=".$state."&id=".$_POST['id']);
				exit;
			} else {
				showMessage($lang['store_goods_index_goods_add_fail'],'index.php?act=store_goods&op=goods_list','html','error');
			}
		}
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
	
	public function add_goods_step3Op(){
		
		if(!empty($_GET['id']))
		{
			$update_array['is_show'] = 1;
			Db::update('goods_records', $update_array,"id = ".$_GET['id']."");
		}
	
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
					
		Tpl::setLayout('member_goods_marketing_layout');
		Tpl::output('goods_id',$_GET['gid']);
		Tpl::output('menu_sign', 'add_goods_step3');
		Tpl::showpage('store_goods_add_step3');
	}
	
	public function edit_save_goodsOp() {
        $lang	= Language::getLangContent();
		$goods_id	= intval($_POST['goods_id']);
		if (chksubmit() &&  $goods_id!= 0){
			
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["goods_name"],"require"=>"true","message"=>$lang['store_goods_index_goods_name_null']),
				array("input"=>$_POST["goods_price"],"require"=>"true","validator"=>"Double","message"=>$lang['store_goods_index_goods_price_null']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($lang['error'].$error,'','html','error');
			}

			$model_store_goods	= Model('goods');
            $para = array();
            $para['store_id'] = $_SESSION['store_id'];
            $para['goods_id'] = $goods_id;
            $verify_count = $model_store_goods->countGoods($para);
            if(intval($verify_count) !== 1) {
                showMessage(Language::get('para_error'),'','html','error');
            }
			$goods_array			= array();
			$goods_array['goods_name']				= addslashes($_POST['goods_name']);
			$goods_array['brand_id']				= $_POST['brand_id'];
			$goods_array['spec_open']				= 1;
			$goods_array['goods_store_price']		= $_POST['goods_store_price'];
			$goods_array['goods_store_price_interval']= $_POST['goods_store_price_interval'];
			$goods_array['goods_serial']			= $_POST['goods_serial'];
			$goods_array['goods_state']				= '0';
			$goods_array['goods_commend']			= $_POST['goods_commend'];
			$goods_array['goods_add_time']			= time();
			$goods_array['goods_body']				= $_POST['goods_body'];
			$goods_array['goods_keywords']			= addslashes($_POST['seo_keywords']);
			$goods_array['goods_description']		= $_POST['seo_description'];

			$goods_array['city_id']					= $_POST['city_id'];
			$goods_array['province_id']				= $_POST['province_id'];			

			$goods_array['goods_image']				= str_replace('_tiny', '_small', $_POST['image_path'][0]);
			$goods_array['goods_image_more']		= implode(',',$_POST['image_path']);
			
			$goods_array['goods_transfee_charge']	= $_POST['goods_transfee_charge'];
			$goods_array['type_id']					= $_POST['type_id'];
			$goods_array['tax_num']		= $_POST['tax_num'];//行邮税编号
            $goods_array['goods_mail']		= $_POST['goods_mail'];//行邮税编号
                        
            $goods_array['car_accessory']		= $_POST['car_accessory'];//车配
            $goods_array['four_s_price']		= $_POST['four_s_price'];//4s店价格
            $goods_array['market_price']		= $_POST['market_price'];//市场价格
            
            $goods_array['d_specvalue']		= serialize($_POST['d_specid']);//明细
                       
            if($goods_array['tax_num']){
                //获取并匹配行邮税
                $param['table'] = 'taxcontrast';
                
                $param['field'] = 'taxnum';
                $param['value'] = $goods_array['tax_num'];
                $result = Db::getRow($param);
                $goods_array['tax_money']=$result['tanrate'];
            }
            
            
            
			//海关属性
			$goods_array['ieflag']					= $_POST['ieflag'];
			$goods_array['product_num']				= $_POST['product_num'];
			$goods_array['gross_weight']			= $_POST['gross_weight'];
			$goods_array['net_weight']				= $_POST['net_weight'];
			$goods_array['goods_custom_num']		= $_POST['goods_custom_num'];
			$goods_array['declaration_unit']		= $_POST['declaration_unit'];
			$goods_array['country']					= $_POST['country'];
			$goods_array['provider']				= $_POST['provider'];
			$goods_array['foreign_language']		= $_POST['foreign_language'];
			
			if (is_array($_POST['spec'])) {
				$goods_array['goods_spec']			= serialize($_POST['sp_val']);			
			}else{
				$goods_array['goods_spec']			= serialize(null);						
			}
			$goods_array['goods_attr']				= serialize($_POST['attr']);
			$goods_array['spec_name']				= serialize($_POST['sp_name']);
			

			if ($_POST['goods_transfee_charge']==1){
				$goods_array['transport_id'] = $goods_array['py_price'] = $goods_array['kd_price'] = $goods_array['es_price'] = '0';
			}elseif($_POST['isApplyPostage'] == '0'){
				$goods_array['transport_id']			= '0';
				$goods_array['py_price']				= intval($_POST['py_price']);
				$goods_array['kd_price']				= intval($_POST['kd_price']);
				$goods_array['es_price']				= intval($_POST['es_price']);
			}else{
				$goods_array['transport_id']			= intval($_POST['transport_id']);
				$model_transport = Model('transport');
				$condition = array();
				$condition['transport_id'] 	= array(intval($_POST['transport_id']));
				$condition['is_default'] 	= 1; 
				$trans_list = $model_transport->getExtendList($condition);
				if (!empty($trans_list) && is_array($trans_list)){
					foreach ($trans_list as $k=>$v) {
						if ($v['type'] == 'py') $goods_array['py_price'] 	= $v['sprice']; 
						if ($v['type'] == 'kd') $goods_array['kd_price'] 	= $v['sprice']; 
						if ($v['type'] == 'es') $goods_array['es_price'] 	= $v['sprice']; 
					}
					if(!isset($goods_array['py_price'])) $goods_array['py_price'] = 0;
					if(!isset($goods_array['kd_price'])) $goods_array['kd_price'] = 0;
					if(!isset($goods_array['es_price'])) $goods_array['es_price'] = 0;
				}
			}

			if (intval($_POST['cate_id']) != 0) {
				$goods_array['gc_id']			= $_POST['cate_id'];
				$goods_array['gc_name']			= addslashes($_POST['cate_name']);
			}

			$goods_array['goods_form']				= $_POST['goods_form'];
			switch ($_POST['goods_show']){
				case 0:
					$goods_array['goods_show']		= 0;
					$goods_array['goods_starttime']	= time()-14*86400;
					$goods_array['goods_endtime']	= time()-7*86400;
					$goods_array['goods_commend']	= 0;
					break;
				case 1:
					$goods_array['goods_show']		= 1;
					$goods_array['goods_starttime']	= time();
					$goods_array['goods_endtime']	= time()+C('product_indate')*86400;
					break;
				case 2:
					$goods_array['goods_show']		= 0;
					$goods_array['goods_starttime']	= strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60;
					$goods_array['goods_endtime']	= strtotime($_POST['starttime']) + intval($_POST['starttime_H'])*3600 + intval($_POST['starttime_i'])*60 + C('product_indate')*86400;
					if($goods_array['goods_starttime'] <= time() && time()  < $goods_array['goods_endtime'] ){
						$goods_array['goods_show']		= 1;
					}else{
						$goods_array['goods_commend']	= 0;
					}
					break;
			}
			$state = $model_store_goods->updateGoods($goods_array,$goods_id);
			if($state) {
				
				$model_type = Model('type');
				$model_type->delType('goods_spec_index',array('goods_id'=>$goods_id));
				$model_type->delType('goods_attr_index',array('goods_id'=>$goods_id));
                               
                          
				$update_array = array();
                  $i=0;
				if (is_array($_POST['spec'])) {
					$spec_id_array		= array();
                    $d_specid_array		= $_POST['d_specids'];//明细
					foreach ($_POST['spec'] as $key=>$val){
                             $d_specid=array();
					         //车配明细记录
                            if($_POST['c_types'][$key]){
                               $d_specid[$key]['c_type']=$_POST['c_types'][$key];  
                               $d_specid[$key]['c_detail']=$_POST['c_details'][$key];  
                               $d_specid[$key]['c_num']=$_POST['c_nums'][$key];
                               $d_specid[$key]['c_price']=$_POST['c_prices'][$key];
                               $d_specid[$key]['c_omodel']=$_POST['c_omodels'][$key];
                               $d_specid[$key]['c_rbrand']=$_POST['c_rbrands'][$key];
                               $d_specid[$key]['c_rmodel']=$_POST['c_rmodels'][$key];
                               $d_specid[$key]['c_cost']=$_POST['c_costs'][$key];  
                            
                              
                            }
           
                        
                       
                       
                          if($val['sku']){

                             //根据商品货号查找行邮税编号
                            $model = Model();
                            $records_date = $model->table('goods_records')->where(array('goods_article_num'=>$val['sku']))->select();  

                            foreach($records_date as $k => $v){
                               
                             //获取并匹配行邮税
                             $param['table'] = 'taxcontrast';
                             $param['field'] = 'taxnum';
                             $param['value'] = $v['tax_num'];
                             $result = Db::getRow($param);  
                             $goods_tax['goods_tax'] =$result['tanrate'];
                             $goods_tax_num['goods_tax_num'] = $result['taxnum'];
                            }
                          }          
                           $spec_id = $model_store_goods->getSpecGoodsWhere(array('spec_goods_spec'=>serialize($val['sp_value']), 'spec_goods_id'=>$goods_id));
                         
                                      
						if($spec_id){
							$param = array();
							$param['goods_id']				= $goods_id;
							$param['spec_name']				= serialize($_POST['sp_name']);
							$param['spec_goods_spec']		= serialize($val['sp_value']);
							$param['spec_goods_price']		= ncPriceFormat($val['price']);
							$param['spec_goods_storage']	= intval($val['stock']);
							$param['store_base']	= intval($_POST['store_base']);//商品库存基数
							$param['spec_goods_serial']		= trim($val['sku']);
                            $param['goods_tax']		= trim($goods_tax['goods_tax']);
                            $param['goods_tax_num']		= trim($goods_tax_num['goods_tax_num']);
                            $param['spec_d_specvalue']	 = serialize($d_specid);    
                            
                            $param['spec_market_price']		= ncPriceFormat($val['market_price']);
							$model_store_goods->updateSpecGoods(array('spec_id'=>$spec_id['spec_id'], 'spec_goods_id'=>$goods_id),$param);
							$spec_id_array[]	= $spec_id['spec_id'];
						}else{
                                                   
							$insert_array						= array();
							$insert_array[0]['spec_name']		= serialize($_POST['sp_name']);
							$insert_array[0]['sp_value']		= $val['sp_value'];
							$insert_array[0]['price']			= ncPriceFormat($val['price']);
							$insert_array[0]['stock']			= intval($val['stock']);
							$insert_array[0]['store_base']	= intval($_POST['store_base']);//商品库存基数
							$insert_array[0]['sku']				= trim($val['sku']);
                            $insert_array[0]['goods_tax']			= trim( $goods_tax['goods_tax']);
                            $insert_array[0]['goods_tax_num']		= trim($goods_tax_num['goods_tax_num']);
                            $insert_array[0]['spec_market_price']		= ncPriceFormat($val['market_price']);
                            $insert_array[0]['spec_d_specvalue']	 = serialize($d_specid);     
                                                      
							$insert_id = $model_store_goods->saveSpecGoods($insert_array,$goods_id,$_POST['sp_name']);
							$spec_id_array[]	= strval($insert_id);
						}
                        $i++;
					}
					$model_store_goods->dropSpecGoodsWhere($spec_id_array, $goods_id);			
					$update_array['spec_id']	= $spec_id_array[0];
					$model_store_goods->updateGoods($update_array, $goods_id);
				} else {
					
                    $model = Model();
                    $records_date = $model->table('goods_records')->where(array('goods_article_num'=>$goods_array['goods_serial']))->find();
                    
                    //车配明细记录
                    if($_POST['c_type']){
                       $d_specid[0]['c_type']=$_POST['c_type'];  
                       $d_specid[0]['c_detail']=$_POST['c_detail'];  
                       $d_specid[0]['c_num']=$_POST['c_num'];
                       $d_specid[0]['c_price']=$_POST['c_price'];
                       $d_specid[0]['c_omodel']=$_POST['c_omodel'];
                       $d_specid[0]['c_rbrand']=$_POST['c_rbrand'];
                       $d_specid[0]['c_rmodel']=$_POST['c_rmodel'];
                       $d_specid[0]['c_cost']=$_POST['c_cost'];  
                       // print_r($_POST['c_detail']);
                        
                        $d_specid_array	= serialize($d_specid);//明细
                    }
                    
                    
                    //获取并匹配行邮税
                     $param['table'] = 'taxcontrast';
                     $param['field'] = 'taxnum';
                     $param['value'] = $records_date['tax_num'];
                     $result = Db::getRow($param);  
                     $goods_tax['goods_tax'] =$result['tanrate'];
                     $goods_tax_num['goods_tax_num'] = $result['taxnum'];
                    
					$goods_list = $model_store_goods->getGoods(array('goods_id'=>$goods_id), '', 'spec_id', 'goods');
					$spec_array['goods_id']				= $goods_id;
					$spec_array['spec_name']			= '';
					$spec_array['spec_goods_price']		= $goods_array['goods_store_price'];
					$spec_array['spec_goods_storage']	= $_POST['goods_storage'];//商品库存
					$spec_array['store_base']	= $_POST['store_base'];//商品库存，基数
					$spec_array['spec_goods_serial']	= $goods_array['goods_serial'];
                    $spec_array['goods_tax']			= trim( $goods_tax['goods_tax']);
                    $spec_array['goods_tax_num']		= trim($goods_tax_num['goods_tax_num']);
                    $spec_array['spec_market_price']	= $goods_array['market_price'];
                    $spec_array['spec_d_specvalue']	= $d_specid_array;
                    
                    
                    $model = Model();
                    $records_date = $model->table('goods_records')->where(array('goods_article_num'=>$goods_array['goods_serial']))->find();  
                     $paramdan['table'] = 'taxcontrast';
                     $paramdan['field'] = 'taxnum';
                     $paramdan['value'] = $records_date['tax_num'];
                     $resultdan = Db::getRow($paramdan);
                     $tax_moneydan =$resultdan['tanrate'];
                     $tax_numdan = $resultdan['taxnum'];
                     
                    $spec_array['goods_tax']	= $tax_moneydan;
                    $spec_array['goods_tax_num']	= $tax_numdan;                  
					
					$update_array['spec_open'] = '0';
					
					$model_store_goods->updateSpecStorageGoods($spec_array,$goods_list[0]['spec_id']);
					$model_store_goods->dropSpecGoodsWhere($goods_list[0]['spec_id'], $goods_id);			
					$model_store_goods->updateGoods($update_array, $goods_id);
				}
				
				$model_type = Model('type');
			
				$sa_array = array();
				$sa_array['goods_id'] 	= $goods_id;
				$sa_array['gc_id']		= $_POST['cate_id'];
				$sa_array['type_id']	= $_POST['type_id'];
				if(is_array($_POST['sp_val'])){
					$sa_array['value']		= $_POST['sp_val'];
					$model_type->typeGoodsRelatedAdd($sa_array, 'goods_spec_index', 'spec');
				}
				
				
				if(is_array($_POST['attr'])){
					$sa_array['value']		= $_POST['attr'];
					$model_type->typeGoodsRelatedAdd($sa_array, 'goods_attr_index');
				}
				
				
				if(is_array($_POST['sgcate_id']) and !empty($_POST['sgcate_id'])) {
					$model_store_goods->dropStoreClassGoods($goods_id);
					$new_sgcate_id = $_POST['sgcate_id'];
					$new_sgcate_id = array_unique($new_sgcate_id);
					$model_store_goods->saveStoreClassGoods($new_sgcate_id,$goods_id);
				}
				
				if(!empty($_POST['goods_file_id'][0])) {
					$image_info	= $model_store_goods->getListImageGoods(array('upload_id'=>intval($_POST['goods_file_id'][0])));
					$goods_image	= $image_info[0]['file_thumb'];
					$model_store_goods->updateGoods(array('goods_image'=>$goods_image),$goods_id);
				}

				
				if(is_array($_POST['sp_val']['1'])){
					$col_img_array	= array();
					foreach($_POST['sp_val']['1'] as $k=>$v){
						if(!empty($_FILES[$v]['name'])){	
							@unlink(ATTACH_SPEC.DS.$_SESSION['store_id'].DS.$_POST['goods_col_img'][$k]);
							@unlink(ATTACH_SPEC.DS.$_SESSION['store_id'].DS.str_replace('_tiny', '_mid', $_POST['goods_col_img'][$k]));
							
							$upload = new UploadFile();
							$upload->set('ifremove', true);
							$upload->set('default_dir',ATTACH_SPEC.DS.$_SESSION['store_id'].DS.$upload->getSysSetPath());
							$upload->set('max_size',C('image_max_filesize'));
							$thumb_width	= '30,'.C('thumb_mid_width');
							$thumb_height	= '30,'.C('thumb_mid_height');
					
							$upload->set('thumb_width',	$thumb_width);
							$upload->set('thumb_height',$thumb_height);
							$upload->set('thumb_ext',	'_tiny,_mid');	
								
							$result = $upload->upfile($v);
							if($result){
								$img_path	= $upload->getSysSetPath().$upload->thumb_image;
								$col_img_array[$v]  =  $col_img_array[$k]  =  $img_path;
							}
						}else if (isset($_POST['goods_col_img'][$k])){	
							$col_img_array[$v]  =  $col_img_array[$k]  =  $_POST['goods_col_img'][$k];
						}
					}
					
					$col_keys	= array_keys($_POST['sp_val']['1']);
					if(is_array($_POST['goods_col_img'])){
						foreach($_POST['goods_col_img'] as $k=>$v){
							if(!in_array($k, $col_keys) && $v != ''){
								@unlink(ATTACH_SPEC.DS.$_SESSION['store_id'].DS.$v);
								@unlink(ATTACH_SPEC.DS.$_SESSION['store_id'].DS.str_replace('_tiny', '_mid', $v));
							}
						}
					}
					
					
					if(!empty($col_img_array)){
						$model_store_goods->updateGoods(array('goods_col_img'=>serialize($col_img_array)),$goods_id);
					}
				}
				$evaluate_model = Model('evaluate');
				$evaluate_model->updateGoodsStat($goods_id);
				//redirect(ncUrl(array('act'=>'goods','goods_id'=>$goods_id), 'goods'));
				showdialog('修改成功！','index.php?act=goods&goods_id='.$goods_id);
				//header('location:index.php?act=goods&goods_id='.$goods_id);
			} else {
				showMessage($lang['store_goods_index_goods_edit_fail'],'','html','error');
			}
		}
	}
        
        
        
        

	public function drop_goodsOp() {
          
		$lang	= Language::getLangContent();
	
		$model_store_goods	= Model('goods');

	
        $goods_id = trim($_GET['goods_id']);
        if(empty($goods_id)) {
            showdialog(Language::get('para_error'),'','error');
        }
        $goods_id_array = explode(',',$goods_id);
        $input_goods_count = count($goods_id_array);
        $para = array();
        $para['store_id'] = $_SESSION['store_id'];
        $para['goods_id_in'] = $goods_id;
        $verify_count = intval($model_store_goods->countGoods($para));
        if($input_goods_count !== $verify_count) {
            showMessage(Language::get('para_error'),'','html','error');
        }

		$state	= $model_store_goods->dropGoods($goods_id);
		if($state) {
			showDialog($lang['store_goods_index_goods_del_success'],'reload','succ');
		} else {
			showDialog($lang['store_goods_index_goods_del_fail'],'','error');
		}
	}
	
	public function goods_unshowOp() {

		$lang	= Language::getLangContent();
		
		$model_store_goods	= Model('goods');

		
        $goods_id = trim($_GET['goods_id']);
        if(empty($goods_id)) {
            showDialog(Language::get('para_error'),'','error');
        }

        $goods_id_array = explode(',',$goods_id);
     
        $input_goods_count = count($goods_id_array);
        $para = array();
        $para['store_id'] = $_SESSION['store_id'];
        $para['goods_id_in'] = $goods_id;
        //统计共有多少条数据
        $verify_count = intval($model_store_goods->countGoods($para));

        if($input_goods_count !== $verify_count) {
            showDialog(Language::get('para_error'),'','html','error');
        }

		$state	= $model_store_goods->updateGoods(array('goods_show'=>'0','goods_starttime'=>(time()-2*86400),'goods_endtime'=>(time()-86400)), $goods_id);
	
		if($state) {
			showdialog($lang['store_goods_index_goods_unshow_success'],'index.php?act=store_goods&op=goods_list','succ');
		} else {
			showdialog($lang['store_goods_index_goods_unshow_fail'],'','error');
		}
	}
	
	public function goods_showOp(){
		$lang	= Language::getLangContent();
		
		$model = Model();
		
		$goods_id = intval($_GET['goods_id']);
		if($goods_id <= 0) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $model->table('goods')->where(array('goods_id'=>$goods_id,'store_id'=>$_SESSION['store_id']));
        $state =$model->update(array('goods_show'=>'1','goods_state'=>0,'goods_starttime'=>time(),'goods_endtime'=>(time()+86400*C('product_indate'))));
		if($state) {
			showdialog($lang['store_goods_index_goods_show_success'],'reload','succ');
		} else {
			showdialog($lang['store_goods_index_goods_show_fail'],'','','error');
		}
	}
	
	public function goods_show_batchOp(){

		$lang	= Language::getLangContent();
		$goods_id = explode(',', $_GET['goods_id']);
		if(empty($goods_id)) {
            showMessage(Language::get('para_error'),'','html','error');
        }
		$model = Model();
		$update = array();
		$update['goods_show']		= 1;
		$update['goods_starttime']	= time();
		$update['goods_endtime']	= time()+86400*C('product_indate');
		$where	= array();
		$where['goods_id']	= array('in',$goods_id);
		//$where['member_id']	= $_SESSION['member_id'];
		$where['store_id']	= $_SESSION['store_id'];
		$state = $model->table('goods')->where($where)->update($update);
		if($state) {
			showdialog($lang['store_goods_index_goods_show_success'],'reload','succ');
		} else {
			showdialog($lang['store_goods_index_goods_show_fail'],'','','error');
		}
	}
	
	public function brand_listOp() {
		
		$model_brand	= Model('brand');
		$condition['like_brand_name']	= $_GET['brand_name'];
		$condition['storeid_equal']			= "{$_SESSION['store_id']}";
		
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');

		$brand_list		= $model_brand->getBrandList($condition,$page);
		Tpl::output('brand_list',$brand_list);
		Tpl::output('show_page',$page->show());
		//脚部文章输出
		$article = $this->_article();

		self::profile_menu('brand_list','brand_list');
		Tpl::output('menu_sign','brand_list');
		Tpl::showpage('store_brand_list');
	}
	
	public function brand_addOp() {
		$lang	= Language::getLangContent();
		$model_brand = Model('brand');
		if($_GET['brand_id'] != '') {
			$brand_array = $model_brand->getOneBrand($_GET['brand_id']);
			if (empty($brand_array) || $brand_array['store_id'] != $_SESSION['store_id']){
				showMessage($lang['wrong_argument'],'','html','error');
			}
			Tpl::output('brand_array',$brand_array);
		}
		//脚部文章输出
		$article = $this->_article();

		Tpl::showpage('store_brand_add','null_layout');
	}
	
	public function brand_saveOp() {
		$lang	= Language::getLangContent();
		$model_brand = Model('brand');
		if (chksubmit()) {
			
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["brand_name"], "require"=>"true", "message"=>$lang['store_goods_brand_name_null'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showValidateError($error);
			}else {
				
				if (!empty($_FILES['brand_pic']['name'])){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_BRAND);
					$upload->set('thumb_width',	150);
					$upload->set('thumb_height',50);
					$upload->set('thumb_ext',	'_small');
					$upload->set('ifremove',	true);
					$result = $upload->upfile('brand_pic');				
					if ($result){
						$_POST['brand_pic'] = $upload->thumb_image;
					}else {
						showDialog($upload->error);
					}
				}
				$insert_array = array();
				$insert_array['brand_name']		= $_POST['brand_name'];
				$insert_array['brand_class']	= $_POST['brand_class'];
				$insert_array['brand_pic']		= $_POST['brand_pic'];
				$insert_array['brand_apply']	= 0;
				$insert_array['store_id']		= $_SESSION['store_id'];

				$result = $model_brand->add($insert_array);
				if ($result){
					showDialog($lang['store_goods_brand_apply_success'],'index.php?act=store_goods&op=brand_list','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
				}else {
					showDialog($lang['nc_common_save_fail']);
				}
			}
		}
	}
	
	public function brand_editOp() {
		$lang	= Language::getLangContent();
		$model_brand = Model('brand');
		if ($_POST['form_submit'] == 'ok' and intval($_POST['brand_id']) != 0) {
		
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["brand_name"], "require"=>"true", "message"=>$lang['store_goods_brand_name_null'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showValidateError($error);
			}else {
				
				if (!empty($_FILES['brand_pic']['name'])){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_BRAND);
					$upload->set('thumb_width',	150);
					$upload->set('thumb_height',50);
					$upload->set('thumb_ext',	'_small');
					$upload->set('ifremove',	true);
					$result = $upload->upfile('brand_pic');

					if ($result){
						$_POST['brand_pic'] = $upload->thumb_image;
					}else {
						showDialog($upload->error);
					}
				}

				$update_array = array();
				$update_array['brand_id'] = $_POST['brand_id'];
				$update_array['brand_name'] = $_POST['brand_name'];
				$update_array['brand_class'] = $_POST['brand_class'];
				if (!empty($_POST['brand_pic'])){
					$update_array['brand_pic'] = $_POST['brand_pic'];
				}

				$result = $model_brand->update($update_array);
				if ($result){
					
					if (!empty($_POST['brand_pic'])){
						@unlink(BasePath.DS.ATTACH_BRAND.DS.$_POST['old_brand_pic']);
					}
					showDialog($lang['nc_common_save_succ'],'index.php?act=store_goods&op=brand_list','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
				}else {
					showDialog($lang['nc_common_save_fail']);
				}
			}
		} else {
			showDialog($lang['nc_common_save_fail']);
		}
	}
	
	public function drop_brandOp() {
		$model_brand	= Model('brand');
		$brand_id		= intval($_GET['brand_id']);
		if ($brand_id > 0){
			$brand_list = $model_brand->getBrandList(array('brand_id'=>$brand_id,'brand_apply'=>'0'));
			$brand_array = $brand_list[0];
			if (empty($brand_array) || $brand_array['store_id'] != $_SESSION['store_id']){
				showDialog(Language::get('nc_common_del_fail'));
			}
			if (!empty($brand_array['brand_pic'])){
				@unlink(BasePath.DS.ATTACH_BRAND.DS.$brand_array['brand_pic']);
			}
			
			$model_brand->del($brand_id);
			unset($brand_array);
			showDialog(Language::get('nc_common_del_succ'),'index.php?act=store_goods&op=brand_list','succ');
		}else {
			showDialog(Language::get('nc_common_del_fail'));
		}
	}

	public function check_classOp() {
		if($_GET['required'] == 'false' and $_GET['cate_id'] == '0'){
			echo 'true';
			exit;
		}
		
		$model_class		= Model('goods_class');;
		$sub_class			= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['cate_id']),'gc_show'=>1));
		if(is_array($sub_class)) {
			echo 'false';
		} else {
			echo 'true';
		}
	}
	
	public function image_uploadOp() {
		$model = Model();
		$store_grade = ($setting = F('store_grade')) ? $setting : H('store_grade',true,'file');
		$grade_id = $model->table('store')->getfby_store_id($_SESSION['store_id'],'grade_id');
		$album_limit = $store_grade[$grade_id]['sg_album_limit'];
		$album_count = $model->table('album_pic')->where(array('store_id'=>$_SESSION['store_id']))->count();
		if ($album_count >= $album_limit){
			$error = Language::get('store_goods_album_climit');
			if (strtoupper(CHARSET) == 'GBK') $error = Language::getUTF8($error);
			exit(json_encode(array('error'=>$error)));
		}

		$lang	= Language::getLangContent();

		$class_info = Model('album_class')->where(array('store_id'=>$_SESSION['store_id'],'is_default'=>1))->find();
		
		$upload = new UploadFile();
		
		$upload->set('default_dir',ATTACH_GOODS.DS.$_SESSION['store_id'].DS.$upload->getSysSetPath());
		$upload->set('max_size',C('image_max_filesize'));
		$thumb_width	= C('thumb_tiny_width').','.C('thumb_small_width').','.C('thumb_mid_width').','.C('thumb_max_width').',240';
		$thumb_height	= C('thumb_tiny_height').','.C('thumb_small_height').','.C('thumb_mid_height').','.C('thumb_max_height').',1024';

		$upload->set('thumb_width',	$thumb_width);
		$upload->set('thumb_height',$thumb_height);
		$upload->set('thumb_ext',	'_tiny,_small,_mid,_max,_240x240');	
		$upload->set('fprefix',$_SESSION['store_id']);
		$upload->set('allow_type', array('gif','jpg','jpeg','png'));
		$result = $upload->upfile($_POST['id']);
		if (!$result){
			if (strtoupper(CHARSET) == 'GBK'){
				$upload->error = Language::getUTF8($upload->error);
			}
			$output	= array();
			$output['error']	= $upload->error;
			$output = json_encode($output);
			exit($output);
		}

		$img_path = $upload->getSysSetPath().$upload->file_name;
	
		
		list($width, $height, $type, $attr) = getimagesize(BasePath.'/upload/store/goods/'.$_SESSION['store_id'].DS.$img_path);

		
		$model_upload_album = Model('upload_album');
		$image = explode('.', $_FILES[$_POST['id']]["name"]);
		$insert_array = array();
		$insert_array['apic_name']	= $image['0'];
		$insert_array['apic_tag']	= '';
		$insert_array['aclass_id']	= $class_info['aclass_id'];
		$insert_array['apic_cover']	= $img_path;
		$insert_array['apic_size']	= intval($_FILES[$_POST['id']]['size']);
		$insert_array['apic_spec']	= $width.'x'.$height;
		$insert_array['upload_time']= time();
		$insert_array['store_id']	= $_SESSION['store_id'];
		$result1 = $model_upload_album->add($insert_array);

		$data = array();
		$data['file_name']	= $upload->getSysSetPath().$upload->thumb_image;
		$data['image_cover']= SiteUrl.DS.ATTACH_GOODS.DS.$_SESSION['store_id'].'/';

		if (C('ftp_open') && C('thumb.save_type')==3){
			import('function.ftp');
			if ($_url = remote_ftp(ATTACH_GOODS.DS.$_SESSION['store_id'],$img_path)){
				$data['image_cover'] = $_url.'/';
			}
		}

		
		$output = json_encode($data);
		echo  $output;die;
	}
	
	public function drop_imageOp() {
		$lang	= Language::getLangContent();
		if(empty($_GET['id'])) {
			echo 'false';
		}
		
		$model_store_goods	= Model('goods');
		$drop_stata			= $model_store_goods->dropImageGoods(array('upload_id'=>intval($_GET['id'])));
		if($drop_stata) {
			echo json_encode(array('done'=>'true'));
		} else {
			echo json_encode(array('done'=>'false','msg'=>$lang['store_goods_upload_del_fail']));
		}
	}
	
	public function image_swuploadOp() {
		Tpl::output('act',		trim($_GET['upload_type']));
		Tpl::output('instance',	$_GET['instance']);
		Tpl::output('id',		$_GET['id']);
		Tpl::output('belong',	$_GET['belong']);
		//脚部文章输出
		$article = $this->_article();

		Tpl::showpage('image','null_layout');
	}
	
	public function edit_goods_ajaxOp(){
		$lang	= Language::getLangContent();
		$rzt	= array();
		$rzt['done']	= true;
		if(!is_numeric($_GET['id'])){
			$rzt['done']	= false;
			$rzt['msg']		= $lang['miss_argument'];
		} elseif($_GET['value']	== ''){
			$rzt['done']	= false;
			$rzt['msg']		= $lang['miss_argument'];
		} elseif($_GET['column'] == ''){
			$rzt['done']	= false;
			$rzt['msg']		= $lang['miss_argument'];
		} else {
			$input	= array();
			switch($_GET['column']){
				case 'goods_name':
					break;
				case 'goods_store_price':
					if(!preg_match("/^\d+(\.\d{0,2})?$/",$_GET['value'])){
						$rzt['done']	= false;
						$rzt['msg']		= $lang['wrong_argument'];
					}
					break;
				case 'spec_goods_storage':
					if(!preg_match("/^\d+$/",$_GET['value'])){
						$rzt['done']	= false;
						$rzt['msg']		= $lang['wrong_argument'];
					}
					break;
				case 'goods_show':
					if(!in_array($_GET['value'],array('0','1'))){
						$rzt['done']	= false;
						$rzt['msg']		= "<script>history.go(0);</script>";
					}
					break;
				case 'goods_commend':
					if(!in_array($_GET['value'],array('0','1'))){
						$rzt['done']	= false;
						$rzt['msg']		= $lang['invalid_request'];
					}
					break;
				default:
					$rzt['done']	= false;
					$rzt['msg']		= $lang['wrong_argument'];
			}
			if($rzt['done']){
				$input	= array();
				$input[$_GET['column']]	= $_GET['value'];
				$model_class	= Model('goods');
				$result1	= $result	= true;
				if($_GET['column']!='spec_goods_storage'){
					$result	= $model_class->updateGoods($input,$_GET['id']);
				}
				if(in_array($_GET['column'],array('spec_goods_storage','goods_store_price'))){
					$goods_spec	= $model_class->getSpecGoods($_GET['id']);
					if(!empty($goods_spec) && is_array($goods_spec)){
						$goods_spec	= $goods_spec[0];
						$input	= array();
						switch($_GET['column']){
							case 'spec_goods_storage':
								$input['spec_goods_storage']	= $_GET['value'];
								break;
							case 'goods_store_price':
								$input['spec_goods_price']	= $_GET['value'];
								break;
						}
						$result1 = $model_class->updateSpecStorageGoods($input,$goods_spec['spec_id']);
					}else{
						$rzt['done']	= false;
						$rzt['msg']		= $lang['store_goods_ajax_find_none_spec'];
						$result1	= false;
					}
				}
				if($result and $result1){
					$goods_info	= $model_class->getGoods(array('goods_id'=>$_GET['id']));
					switch($_GET['column']){
						case 'goods_name':
							$rzt['retval']	= $goods_info[0]['goods_name'];
							break;
						case 'goods_store_price':
							$rzt['retval']	= $goods_info[0]['goods_store_price'];
							break;
						case 'spec_goods_storage':
							$rzt['retval']	= $goods_info[0]['spec_goods_storage'];
							break;
					}
				}else{
					$rzt['done']	= false;
					$rzt['msg']	= $lang['store_goods_ajax_update_fail'];
				}
			}
		}
		
		if (strtoupper(CHARSET) == 'GBK'){
			$rzt['msg']		= Language::getUTF8($rzt['msg']);
			$rzt['retval']	= Language::getUTF8($rzt['retval']);
		}
		echo json_encode($rzt);
	}
	
	public function ajax_goods_classOp(){
		if(empty($_GET['gc_id']) or $_GET['gc_id'] == '0')exit;
		$gc	= Model('goods_class');
		$list = $gc->getClassList(array('gc_parent_id'=>intval($_GET['gc_id']),'gc_show'=>1));
		
		if (strtoupper(CHARSET) == 'GBK'){
			$list = Language::getUTF8($list);
		}
		echo json_encode($list);
	}
	
	public function ajax_class_searchOp() {
		
		$class_tag_array = ($tag = F('class_tag')) ? $tag : H('class_tag',true,'file');

		if(!isset($_GET['value'])){
			echo 'false';die;
		}

		$json_array = array();
		if(is_array($class_tag_array)){
			foreach ($class_tag_array as $v){
				$tag_name_array	= explode(',', $v['gc_tag_value']);
				$rs = preg_match('/'.trim($_GET['value']).'/i',$v['gc_tag_value']);
				if($rs != ''){
					if(CHARSET == 'GBK') $v = Language::getUTF8($v);
					$json_array[] = $v;
				}
			}
		}
		
		if(!empty($json_array)){
			echo json_encode($json_array);die;
		}
		echo 'false';die;
	}
	
	public function ajax_staple_controlOp() {
		Language::read('member_store_goods_index');
		$class_id = intval($_GET['class_id']);
		if($class_id < 1){
			echo json_encode(array('done'=>false,'msg'=>Language::get('wrong_argument')));die;
		}
		
		$model_staple = Model('goods_class_staple');
		
		if($_GET['column'] == 'add') {
			$count = $model_staple->countStaple($_SESSION['store_id']);
			if($count >= '20'){
				echo json_encode(array('done'=>false,'msg'=>Language::get('store_goods_step1_max_20')));die;
			}
			$staple_array = $this->getTagByCache($class_id);
			$staple_array['done'] = true;
			if (strtoupper(CHARSET) == 'GBK'){
				$staple_array = Language::getUTF8($staple_array);
			}
			$staple_array['msg'] = Language::get('store_goods_step1_ajax_add_class');
			echo json_encode($staple_array);die;
		}else if($_GET['column'] == 'del') {
			$result = $model_staple->delStaple($class_id, $_SESSION['store_id']);
			if($result){
				echo json_encode(array('done'=>true));die;
			}else{
				echo json_encode(array('done'=>false, 'msg'=>''));die;
			}
		}
	}
	
	public function ajax_show_commOp(){
		list($gc_id, $staple_id, $type_id) = explode('|', $_GET['name']);
		
		$model_tag	= Model('goods_class_tag');
		$tag_list	= $model_tag->getTagList(array('gc_id'=>intval($gc_id), 'type_id'=>intval($type_id)), '', 'gc_id_1,gc_id_2,gc_id_3');
		if(empty($tag_list) || !is_array($tag_list)){
			echo json_encode(array('done'=>false, 'msg'=>''));die;
		}
		
		$list_array				= array();
		$list_array['done']		= true;
		$list_array['one']		= '';
		$list_array['two']		= '';
		$list_array['three']	= '';
		
		$gc_id_1	= intval($tag_list['0']['gc_id_1']);
		$gc_id_2	= intval($tag_list['0']['gc_id_2']);
		$gc_id_3	= intval($tag_list['0']['gc_id_3']);
		
		 
		$model_goods_class	= Model('goods_class');
		
		if($gc_id_1 >0){
			$class_list			= $model_goods_class->getClassList(array('gc_parent_id'=>'0'), 'gc_id,gc_name,type_id');
			if(empty($class_list) || !is_array($class_list)){
				echo json_encode(array('done'=>false, 'msg'=>''));die;
			}
			foreach ($class_list as $val){
				if($val['gc_id'] == $gc_id_1){
					$list_array['one']	.= '<li class="" onclick="selClass(this);" id="'.$val['gc_id'].'|1|'.$val['type_id'].'"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['gc_name'].'</span></a> </li>';
				}else{
					$list_array['one']	.= '<li class="" onclick="selClass(this);" id="'.$val['gc_id'].'|1|'.$val['type_id'].'"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['gc_name'].'</span></a> </li>';
				}
			}
		}
		
		if($gc_id_2 >0){
			$class_list			= $model_goods_class->getClassList(array('gc_parent_id'=>$gc_id_1), 'gc_id,gc_name,type_id');
			if(empty($class_list) || !is_array($class_list)){
				echo json_encode(array('done'=>false, 'msg'=>''));die;
			}
			foreach ($class_list as $val){
				if($val['gc_id'] == $gc_id_2){
					$list_array['two']	.= '<li class="" onclick="selClass(this);" id="'.$val['gc_id'].'|2|'.$val['type_id'].'"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['gc_name'].'</span></a> </li>';
				}else{
					$list_array['two']	.= '<li class="" onclick="selClass(this);" id="'.$val['gc_id'].'|2|'.$val['type_id'].'"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['gc_name'].'</span></a> </li>';
				}
			}
		}
		
		if($gc_id_3 >0){
			$class_list			= $model_goods_class->getClassList(array('gc_parent_id'=>$gc_id_2), 'gc_id,gc_name,type_id');
			if(empty($class_list) || !is_array($class_list)){
				echo json_encode(array('done'=>false, 'msg'=>''));die;
			}
			foreach ($class_list as $val){
				if($val['gc_id'] == $gc_id_3){
					$list_array['three']	.= '<li class="" onclick="selClass(this);" id="'.$val['gc_id'].'|3|'.$val['type_id'].'"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['gc_name'].'</span></a> </li>';
				}else{
					$list_array['three']	.= '<li class="" onclick="selClass(this);" id="'.$val['gc_id'].'|3|'.$val['type_id'].'"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['gc_name'].'</span></a> </li>';
				}
			}
		}
		
		if (strtoupper(CHARSET) == 'GBK'){
			$list_array = Language::getUTF8($list_array);
		}
		echo json_encode($list_array);die;
	}
	
	private function profile_menu($menu_type,$menu_key='',$array=array()) {
		Language::read('member_layout');
		$lang	= Language::getLangContent();
		$menu_array		= array();
		switch ($menu_type) {
			case 'goods':
				$menu_array	= array(
				1=>array('menu_key'=>'goods_list',	'menu_name'=>$lang['nc_member_path_goods_selling'],			'menu_url'=>'index.php?act=store_goods&op=goods_list'));
				break;
			case 'goods_storage':
				$menu_array	= array(
				1=>array('menu_key'=>'goods_storage',	'menu_name'=>$lang['nc_member_path_goods_storage'],		'menu_url'=>'index.php?act=store_goods&op=goods_storage'),
				2=>array('menu_key'=>'goods_state',	'menu_name'=>$lang['nc_member_path_goods_state'],			'menu_url'=>'index.php?act=store_goods&op=goods_storage&type=state'));
				break;
			case 'import_taobao':
				$menu_array = array(
				1=>array('menu_key'=>'import_taobao','menu_name'=>$lang['nc_member_path_taobao_import'],		'menu_url'=>'index.php?act=store_goods&op=taobao_import'));
				break;
			case 'brand_list':
				$menu_array = array(
				1=>array('menu_key'=>'brand_list',	'menu_name'=>$lang['nc_member_path_brand_list'],			'menu_url'=>'index.php?act=store_goods&op=brand_list'));
				break;
		}
		if(!empty($array)) {
			$menu_array[] = $array;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
	
	public function taobao_importOp(){
		$lang 	= Language::getLangContent();
		if(!$_POST){
			
			$gc	= Model('goods_class');
			$gc_list	= $gc->getClassList(array('gc_parent_id'=>'0','gc_show'=>1));
			Tpl::output('gc_list',$gc_list);
			
			$model_store_class	= Model('my_goods_class');
			$store_goods_class	= $model_store_class->getClassTree(array('store_id'=>$_SESSION['store_id'],'stc_state'=>'1'));
			Tpl::output('store_goods_class',$store_goods_class);
			
			if($_GET['step'] != ''){
				Tpl::output('step',$_GET['step']);
			}else{
				Tpl::output('step','1');
			}
		}else{
			$file	= $_FILES['csv'];
			
			if(empty($file['name'])){
				showMessage($lang['store_goods_import_choose_file'],'','html','error');
			}
			
			if(!is_uploaded_file($file['tmp_name'])){
				showMessage($lang['store_goods_import_unknown_file'],'','html','error');
			}
			
			$file_name_array	= explode('.',$file['name']);
			if($file_name_array[count($file_name_array)-1] != 'csv'){
				showMessage($lang['store_goods_import_wrong_type'].$file_name_array[count($file_name_array)-1],'','html','error');
			}
			
			if($file['size'] > intval(ini_get('upload_max_filesize'))*1024*1024){
				showMessage($lang['store_goods_import_size_limit'],'','html','error');
			}
			
			if(empty($_POST['gc_id'])){
				showMessage($lang['store_goods_import_wrong_class'],'','html','error');
			}
			$gc	= Model('goods_class');
			$gc_row	= $gc->getGoodsClassLineForTag($_POST['gc_id']);
			if(!is_array($gc_row) or count($gc_row) == 0){
				showMessage($lang['store_goods_import_wrong_class1'],'','html','error');
			}
			$gc_sub_list	=	$gc->getClassList(array('gc_parent_id'=>intval($_POST['gc_id']),'gc_show'=>1));
			if(is_array($gc_sub_list) and count($gc_sub_list) > 0){
				showMessage($lang['store_goods_import_wrong_class2'],'','html','error');
			}
			
			$stc_ids	= array();
			$stc	= Model('store_goods_class');
			if(is_array($_POST['stc_id']) and count($_POST['stc_id']) > 0){
				foreach ($_POST['stc_id'] as $stc_id) {
					if(!in_array($stc_id,$stc_ids)){
						$stc_row	= $stc->getOneById($stc_id);
						if(is_array($stc_row) and count($stc_row) > 0){
							$stc_ids[]	= $stc_id;
						}
					}
				}
			}
			
			$csv_string	= unicodeToUtf8(file_get_contents($file['tmp_name']));
			
			$csv_array = explode("\tsyncStatus", $csv_string, 2);
			if(count($csv_array) == 2){
				$csv_string	= $csv_array[1];
			}
			
			$records	= $this->parse_taobao_csv($csv_string);
			if($records === false){
			showMessage($lang['store_goods_import_wrong_column'],'','html','error');
			}
			
			
			if (strtoupper(CHARSET) == 'GBK'){
				$records = Language::getGBK($records);
			}
			$model_store_goods	= Model('goods');
			$goods_num=$model_store_goods->countGoods(array('store_id'=>$_SESSION['store_id']));
			
			$model_store	= Model('store');
			$store_info		= $model_store->shopStore(array('store_id'=>$_SESSION['store_id']));
			$model_store_grade	= Model('store_grade');
			$store_grade	= $model_store_grade->getOneGrade($store_info['grade_id']);
			$remain_num	= -1;
			if(intval($store_grade['sg_goods_limit']) != 0) {
				if($goods_num >= $store_grade['sg_goods_limit']) {
					showMessage($lang['store_goods_index_goods_limit'].$store_grade['sg_goods_limit'].$lang['store_goods_index_goods_limit1'],'index.php?act=store_goods&op=goods_list','html','error');
				}
				$remain_num	= $store_grade['sg_goods_limit']-$goods_num;
			}
			if(intval($store_info['store_end_time']) != 0) {
				if(time() >= $store_info['store_end_time']) {
					showMessage($lang['store_goods_index_time_limit'],'index.php?act=store_goods&op=goods_list','html','error');
				}
			}
			
			if(is_array($records) and count($records) > 0){
				foreach($records as $k=>$record){
					if($remain_num>0 and $k>=$remain_num){
						showMessage($lang['store_goods_index_goods_limit'].$store_grade['sg_goods_limit'].$lang['store_goods_index_goods_limit1'].$lang['store_goods_import_end'].(count($records)-$remain_num).$lang['store_goods_import_products_no_import'],'index.php?act=store_goods&op=taobao_import&step=2','html','error');
					}
					$pic_array	= $this->get_goods_image($record['goods_image']);
					if(empty($record['goods_name']))continue;
					$param	= array();
					$param['goods_name']			= $record['goods_name'];
					$param['gc_id']					= intval($_POST['gc_id']);
					$param['gc_name']				= $gc_row['gc_tag_name'];
					$param['store_id']				= $_SESSION['store_id'];
					$param['goods_image']			= $pic_array['goods_image'][0];
					$param['goods_store_price']		= $record['goods_store_price'];
					$param['goods_show']			= '1';
					$param['goods_commend']			= $record['goods_commend'];
					$param['goods_add_time']		= time();
					$param['goods_body']			= $record['goods_body'];
					$param['py_price']				= $record['py_price'];
					$param['es_price']				= $record['es_price'];
					$param['kd_price']				= $record['kd_price'];
					$param['goods_form']			= '1';
					$param['goods_starttime']		= time();
					$param['goods_endtime']			= time()+C('product_indate')*86400;
					$param['goods_transfee_charge']	= $record['goods_transfee_charge'];
					$param['city_id']				= intval($_POST['city_id']);
					$param['province_id']			= intval($_POST['province_id']);
					
					$goods_id	= $model_store_goods->saveGoods($param);
					$goods_id_str.=",".$goods_id;
					if($goods_id){
						
						if(!empty($stc_ids)){
							$model_store_goods->saveStoreClassGoods($stc_ids,$goods_id);
						}
						
						if(true){
							$spec_array		= array();
							$spec_array[0]['price']	= $record['goods_store_price'];
							$spec_array[0]['stock']	= $record['spec_goods_storage'];
							$spec_id = $model_store_goods->saveSpecGoods($spec_array,$goods_id);
						
							$model_store_goods->updateGoods(array('spec_id'=>$spec_id),$goods_id);
						}
						
						if(!empty($pic_array['goods_image']) && is_array($pic_array['goods_image'])){
							$model_upload	= Model('upload');
							foreach ($pic_array['goods_image'] as $pic) {
								if($pic	== '')continue;
								$param	= array();
								$param['file_name']	= $pic;
								$param['file_thumb']= $pic;
								$param['store_id']	= $_SESSION['store_id'];
								$param['upload_type']	= '2';
								$param['upload_time']	= time();
								$param['item_id']	= $goods_id;
								$model_upload->add($param);
							}
						}
					}else{
					}
				}
				if($goods_id_str!=""){
					Tpl::output('goods_id_str',substr($goods_id_str,1,strlen($goods_id_str)));
				}
			}
			Tpl::output('step','2');
		}
		
	
		$model_album = Model('album');
		$param = array();
		$param['album_aclass.store_id']	= $_SESSION['store_id'];
		$aclass_info = $model_album->getClassList($param);
		Tpl::output('aclass_info',$aclass_info);
		
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::output('PHPSESSID',session_id());
		self::profile_menu('import_taobao','import_taobao');
		Tpl::output('menu_sign','taobao_import');
		Tpl::showpage('store_goods_import');
	}
	
	private function get_goods_image($pic_string){
		if($pic_string == ''){
			return false;
		}
		$pic_array = explode(';',$pic_string);
		if(!empty($pic_array) && is_array($pic_array)){
			$array	= array();
			$goods_image	= array();
			$multi_image	= array();
			$i=0;
			foreach($pic_array as $v){
				if($v != ''){
					$line = explode(':',$v);
					$goods_image[] = $line[0];
				}
			}
			$array['goods_image']	= $goods_image;
			return $array;
		}else{
			return false;
		}
	}
	
	private function taobao_fields()
	{
		return array(
		'goods_name'		=> '宝贝名称',
		'cid'				=> '宝贝类目',
		'goods_form'		=> '新旧程度',
		'goods_store_price'	=> '宝贝价格',
		'spec_goods_storage'=> '宝贝数量',
		'goods_indate'		=> '有效期',
		'goods_transfee_charge'=>'运费承担',
		'py_price'			=>'平邮',
		'es_price'			=>'EMS',
		'kd_price'			=>'快递',
		'goods_commend'		=> '橱窗推荐',
		'goods_body'		=> '宝贝描述',
		'goods_image'		=> '新图片'
		);
		
	}

	
	private function taobao_fields_cols($title_arr, $import_fields)
	{
		$fields_cols = array();
		foreach ($import_fields as $k => $field)
		{
			$pos = array_search($field, $title_arr);
			if ($pos !== false)
			{
				$fields_cols[$k] = $pos;
			}
		}
		return $fields_cols;
	}

	
	private function parse_taobao_csv($csv_string)
	{
		define('ORD_SPACE', 32); 
		define('ORD_QUOTE', 34); 
		define('ORD_TAB',    9); 
		define('ORD_N',     10);
		define('ORD_R',     13);

		$import_fields = $this->taobao_fields(); 
		$fields_cols = array(); 
		$csv_col_num = 0; 

		$pos = 0;
		$status = 0; 
		$title_pos = 0; 
		$records = array(); 
		$field = 0; 
		$start_pos = 0; 
		$field_status = 0; 
		$line =0; 
		while($pos < strlen($csv_string))
		{
			$t = ord($csv_string[$pos]); 
			$next = ord($csv_string[$pos + 1]);
			$next2 = ord($csv_string[$pos + 2]);
			$next3 = ord($csv_string[$pos + 3]);

			if ($status == 0 && !in_array($t, array(ORD_SPACE, ORD_TAB, ORD_N, ORD_R)))
			{
				$status = 1;
				$title_pos = $pos;
			}
			
			if ($status == 1)
			{
				if ($field_status == 0 && $t== ORD_N)
				{
					static $flag = null;
					if ($flag === null)
					{
						$title_str = substr($csv_string, $title_pos, $pos - $title_pos);
						$title_arr = explode("\t", trim($title_str));
						$fields_cols = $this->taobao_fields_cols($title_arr, $import_fields);
						
						if (count($fields_cols) != count($import_fields))
						{
							return false;
						}
						$csv_col_num = count($title_arr); 
						$flag = 1;
					}

					if ($next == ORD_QUOTE)
					{
						$field_status = 1; 
						$start_pos = $pos = $pos + 2; 
					}
					else
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 1; 
					}
					continue;
				}

				if($field_status == 1 && $t == ORD_QUOTE && in_array($next, array(ORD_N, ORD_R, ORD_TAB)))
				{
					$records[$line][$field] = addslashes(substr($csv_string, $start_pos, $pos - $start_pos));
					$field++;
					if ($field == $csv_col_num)
					{
						$line++;
						$field = 0;
						$field_status = 0;
						continue;
					}
					if (($next == ORD_N && $next2 == ORD_QUOTE) || ($next == ORD_TAB && $next2 == ORD_QUOTE) || ($next == ORD_R && $next2 == ORD_QUOTE))
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 3;
						continue;
					}
					if (($next == ORD_N && $next2 != ORD_QUOTE) || ($next == ORD_TAB && $next2 != ORD_QUOTE) || ($next == ORD_R && $next2 != ORD_QUOTE))
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 2;
						continue;
					}
					if ($next == ORD_R && $next2 == ORD_N && $next3 == ORD_QUOTE)
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 4;
						continue;
					}
					if ($next == ORD_R && $next2 == ORD_N && $next3 != ORD_QUOTE)
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 3;
						continue;
					}
				}

				if($field_status == 2 && in_array($t, array(ORD_N, ORD_R, ORD_TAB))) 
				{
					$records[$line][$field] = addslashes(substr($csv_string, $start_pos, $pos - $start_pos));
					$field++;
					if ($field == $csv_col_num)
					{
						$line++;
						$field = 0;
						$field_status = 0;
						continue;
					}
					if (($t == ORD_N && $next == ORD_QUOTE) || ($t == ORD_TAB && $next == ORD_QUOTE) || ($t == ORD_R && $next == ORD_QUOTE))
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 2;
						continue;
					}
					if (($t == ORD_N && $next != ORD_QUOTE) || ($t == ORD_TAB && $next != ORD_QUOTE) || ($t == ORD_R && $next != ORD_QUOTE))
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 1;
						continue;
					}
					if ($t == ORD_R && $next == ORD_N && $next2 == ORD_QUOTE)
					{
						$field_status = 1;
						$start_pos = $pos = $pos + 3;
						continue;
					}
					if ($t == ORD_R && $next == ORD_N && $next2 != ORD_QUOTE)
					{
						$field_status = 2;
						$start_pos = $pos = $pos + 2;
						continue;
					}
				}
			}

			if($t > 0 && $t <= 127) {
				$pos++;
			} elseif(192 <= $t && $t <= 223) {
				$pos += 2;
			} elseif(224 <= $t && $t <= 239) {
				$pos += 3;
			} elseif(240 <= $t && $t <= 247) {
				$pos += 4;
			} elseif(248 <= $t && $t <= 251) {
				$pos += 5;
			} elseif($t == 252 || $t == 253) {
				$pos += 6;
			} else {
				$pos++;
			}	
		}
		$return = array();
		foreach ($records as $key => $record)
		{
			foreach ($record as $k => $col)
			{
				$col = trim($col); 
				switch ($k)
				{
					case $fields_cols['goods_body']		: $return[$key]['goods_body'] = str_replace(array("\\\"\\\"", "\"\""), array("\\\"", "\""), $col); break;
					case $fields_cols['goods_image']	: $return[$key]['goods_image'] = trim($col,'"');break;
					case $fields_cols['goods_name']		: $return[$key]['goods_name'] = $col; break;
					case $fields_cols['spec_goods_storage']	: $return[$key]['spec_goods_storage'] = $col; break;
					case $fields_cols['goods_store_price']: $return[$key]['goods_store_price'] = $col; break;
					case $fields_cols['goods_commend']	: $return[$key]['goods_commend'] = $col; break;
					case $fields_cols['sale_attr']		: $return[$key]['sale_attr'] = $col; break;
					case $fields_cols['goods_form']	: $return[$key]['goods_form'] = $col; break;
					case $fields_cols['goods_transfee_charge']		: $return[$key]['goods_transfee_charge'] = $col; break;
					case $fields_cols['py_price']	: $return[$key]['py_price'] = $col; break;
					case $fields_cols['es_price']		: $return[$key]['es_price'] = $col; break;
					case $fields_cols['kd_price']		: $return[$key]['kd_price'] = $col; break;
					case $fields_cols['kd_price']		: $return[$key]['kd_price'] = $col; break;
				}
			}
		}
		return $return;
	}
	
	public function date_packOp(){
		Language::read('member_store_goods_index');
		$lang	= Language::getLangContent();
		if(trim($_GET['goods_id_str'])==''){
			showMessage($lang['store_goods_pack_wrong1'],'','','error');
		}else{
			$upload_model=Model('upload');
			$gid_arr=explode(',',trim($_GET['goods_id_str']));
			if(is_array($gid_arr) && !empty($gid_arr)){
				$path=ATTACH_GOODS.DS.$_SESSION['store_id'].DS;
				foreach($gid_arr as $v1){
					$upload_list=$upload_model->getUploadList(array('item_id'=>$v1),'upload_id,file_name,file_thumb');
					
					$goods_image_more	= array();		
					$goods_image		= '';			
					foreach($upload_list as $v2){
						
						if(count($goods_image_more) == 5) break;
						if(file_exists($path.str_replace('_small', '_tiny', $v2['file_thumb']))){
							$goods_image_more[] = str_replace('_small', '_tiny', $v2['file_thumb']);
							if($goods_image == '') $goods_image = $v2['file_thumb'];
						}else{
							$upload_model->dropUploadById($v2['upload_id']);
						}
					}
					$goods_image_more = implode(',', $goods_image_more);
					
					$goods_model=Model('goods');
					$goods_model->updateGoods(array('goods_image'=>$goods_image,'goods_image_more'=>$goods_image_more),$v1);
					$upload_model->delByWhere(array('item_id'=>$v1,'upload_type'=>$_SESSION['store_id']));
				}
				showMessage($lang['store_goods_pack_success'],'index.php?act=store_goods');
			}else{
				showMessage($lang['store_goods_pack_wrong2'],'','','error');
			}
		}
	}
	
	private function getTagByCache($class_id){
		
		$model_staple = Model('goods_class_staple');
		
		$class_tag_array = ($tag = F('class_tag')) ? $tag : H('class_tag',true,'file');
		if(!empty($class_tag_array) && is_array($class_tag_array)){
			foreach ($class_tag_array as $v){
				if($v['gc_id'] == $class_id){
					$param_array = array();
					$param_array['staple_name']	= $v['gc_tag_name'];
					$param_array['gc_id']		= $v['gc_id'];
					$param_array['type_id']		= $v['type_id'];
					$param_array['store_id']	= $_SESSION['store_id'];
					$param_array['staple_id']	= $model_staple->addStaple($param_array);
					return $param_array;
				}
			}
		}
		
		
		$model_class		= Model('goods_class');
		$model_class_tag	= Model('goods_class_tag');
		$gc_list = $model_class->getGoodsClassLineForTag($class_id);
		$return = $model_class_tag->addOneTag($gc_list);
		
		$param_array = array();
		$param_array['staple_name']	= $gc_list['gc_tag_name'];
		$param_array['gc_id']		= $gc_list['gc_id'];
		$param_array['type_id']		= $gc_list['type_id'];
		$param_array['store_id']	= $_SESSION['store_id'];
		$param_array['staple_id']	= $model_staple->addStaple($param_array);
		return $param_array;
	}
}
