<?php

defined('haipinlegou') or exit('Access Invalid!');
define('MYSQL_RESULT_TYPE',1);
class indexControl extends BaseHomeControl{
    
    //团购用到
	const TEMPLATE_STATE_ACTIVE = 1;
	public function indexOp(){

		Language::read('home_index_index');
		Tpl::output('index_sign','index');

        $model_store = Model('store');
		$r_store = $model_store->getRecommendStore(3);
		Tpl::output('show_recommend_store',$r_store);

		$f_store = $model_store->getFavoritesStore(3);
		Tpl::output('show_favorites_store',$f_store);
        
		$n_store = $model_store->getNewStore(3);
		Tpl::output('show_new_store',$n_store);

		$xianshi_item = $this->_promotion();
		Tpl::output('xianshi_item',$xianshi_item);

		$list = $this->_product();
		Tpl::output('recommend_best_item',$list);
		//首页产品列表
		$goods_list = $this->get_category_goods();
		Tpl::output('goods_list',$goods_list);
		Tpl::output('show_flink',($link = F('link')) ? $link : H('link',true,'file'));

		//获取顶级分类广告图
		$goods_class = Model('goods_class');
		$param['gc_parent_id'] = 0;
       // $param['goods_show'] = 1;
		$father_class = $goods_class->getClassList($param ,$field='*');
        Tpl::output('father_class',$father_class);
        
        
        $paramall['gc_show'] = 1;
		$all_class = $goods_class->getClassList($paramall,$field='*');
        Tpl::output('all_class',$all_class);
		/*
		Language::read('member_groupbuy');
        $param = array();
        $param['recommended'] = 1;
        $param['state'] = 3;
        $param['in_progress'] = time();
        $param['limit'] = 1;
		$model_group = Model('goods_group');
        $group_list = $model_group->getList($param);
		Tpl::output('group_list',$group_list[0]);
        Tpl::output('count_down',$group_list[0]['end_time'] - time());
		Tpl::output('father_class',$father_class);
		
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}
		*/
		//文章输出
		$list = $this->_article();
	
		//品牌推荐
		
		Language::read('home_brand_index');
		$nav_link = array(
			0=>array(
				'title'=>Language::get('homepage'),
				'link'=>'index.php'
			),
			1=>array(
				'title'=>Language::get('brand_index_all_brand')
			)
		);
		Tpl::output('nav_link_list',$nav_link);
		$model_store = Model('store');
		$store_r_list = $model_store->getRecommendStore(5);
		Tpl::output('store_r',$store_r_list);
		
		$model = Model();
		$brand_c_list = $model->table('brand')->where(array('brand_apply'=>'1'))->order('brand_sort')->select();
		
		// $brand_listnew = array();
		// $brand_listother = array();
		// if (!empty($brand_c_list) && is_array($brand_c_list)){
			// foreach ($brand_c_list as $key=>$brand_c){
				// if (!empty($brand_c['brand_class'])){
					// $brand_listnew[$brand_c['brand_class']]['brand'][] = $brand_c;
					// $brand_listnew[$brand_c['brand_class']]['brand_class'] += 1; 
				// }else {
					// $brand_listother['brand'][] = $brand_c;
					// $brand_listother['brand_class'] += 1;
				// }
				// if ($brand_c['brand_recommend'] == 1){
					// $brand_r_list[] = $brand_c;
				// }
			// }
			// if (!empty($brand_listother)){
				// $brand_listnew['other'] = $brand_listother;
			// }
		// }
		
		//Tpl::output('brand_c',$brand_listnew);
		Tpl::output('brand_c_list',$brand_c_list);
		//Tpl::output('brand_r',$brand_r_list);
		//Tpl::output('html_title',Language::get('brand_index_brand_list'));
		//Tpl::output('index_sign','brand');
		
		//商品推荐
			$recommend_limit	= 6;//显示个数
			$model_recommend	= Model('recommend');
			$condition	= array(
				'goods_show'=>'1',
				'recommend_id'=>'1',
				'limit'=>$recommend_limit,
				'field'	=> 'recommend_goods.recommend_id,goods.goods_id,goods.store_id,goods.goods_name,goods.goods_image,goods.goods_store_price'
			);
			$product_list	= $model_recommend->getGoodsList($condition);
		Tpl::output('product_list',$product_list);
		
			
		//团购
		//$g_cache = ($cache = F('groupbuy'))? $cache : H('groupbuy',true,'file');
      
        //$template_in_progress = $this->get_groupbuy_template_list('in_progress');
        //Tpl::output('groupbuy_template',$template_in_progress[0]);

      
        //$this->output_count_down($template_in_progress[0]['end_time']);

		/*
        $page = new Page();
        $page->setEachNum(9) ;
        $page->setStyle('admin') ;

       
        $param = array();
        $param['area_id'] = intval($_GET['groupbuy_area']);
        if(empty($param['area_id'])) {
            if(cookie('groupbuy_area')) {
                $area_array = explode(',',cookie('groupbuy_area'));
                $param['area_id'] = intval($area_array[0]);
            }
        }
        $param['class_id'] = intval($_GET['groupbuy_class']);
        if(intval($_GET['groupbuy_price']) !== 0) {
            $price_range_list = $g_cache['price'];
            foreach($price_range_list as $price_range) {
                if($price_range['range_id'] == $_GET['groupbuy_price']) {
                    $param['greater_than_groupbuy_price'] = $price_range['range_end'];
                    $param['less_than_groupbuy_price'] = $price_range['range_start'];
                } 
            }
        }
        $groupbuy_order_key = trim($_GET['groupbuy_order_key']);
        $groupbuy_order = empty($_GET['groupbuy_order'])?'desc':trim($_GET['groupbuy_order']);
        if(!empty($groupbuy_order_key)) {
            switch ($groupbuy_order_key) {
                case 'price':
                    $param['order'] = 'state asc,groupbuy_price '.$groupbuy_order;
                    break;
                case 'rebate':
                    $param['order'] = 'state asc,rebate '.$groupbuy_order;
                    break;
                case 'sale':
                    $param['order'] = 'state asc,buyer_count '.$groupbuy_order;
                    break;
            }
        }
        $groupbuy_list = $this->get_groupbuy_list('in_progress',$template_in_progress[0]['template_id'],$page,$param);
        Tpl::output('groupbuy_list',$groupbuy_list);
        Tpl::output('show_page',$page->show());
		
		//检测身份验证
		if(!empty($_SESSION['member_id']))
		{
			$param['table'] = "member";
			$param['field'] = 'member_id';
			$param['value'] = $_SESSION['member_id'];
			$row = Db::getRow( $param,"*" );
			
			Tpl::output('row',$row);
		}
		//提交身份信息
		if(!empty($_POST))
		{
			if(Db::update('member',$_POST,"member_id=".$_SESSION['member_id']))
			{
				echo "<script>alert('提交成功！等待管理员审核！')</script>";
			}
		}
		*/

		
        Tpl::output('class_list',$g_cache['category']);
        Tpl::output('area_list',$g_cache['area']);
        Tpl::output('price_list',$g_cache['price']);
		//Tpl::output('index_sign','groupbuy');
		Tpl::output('html_title',Language::get('text_groupbuy_list'));

        $model = Model("tpl_position");
        $data = $model->getTplData([
            "left_index"=>"home",
            "position_id_isset"=>"1",
            "block_is_show"=>"1",
            "is_show"=>"1"
        ]);
        Tpl::output("tpl",$data);
		Model('seo')->type('group')->show();
		
		Model('seo')->type('brand')->show();
		
		Model('seo')->type('index')->show();

        Tpl::setLayout("new_home_layout");
        Tpl::showpage('newindex');


		//Tpl::showpage('index');

	}
	
	
	private function get_category_goods()
	{
		$goods_class = Model('goods_class');
		$goods = Model('goods');
		
		//获取父级分类
		$param['gc_parent_id'] = 0;
        $param['goods_show'] = 1;
		$father_class = $goods_class->getClassList($param ,$field='*');

		//获取对应顶级分类的所有子分类id(便于获取对应分类产品)
		foreach($father_class as $key=>$value)
		{
			$father_class[$key]['child'] = $goods_class->getChildClass($value['gc_id']);
			foreach($father_class[$key]['child'] as $k=>$v)
			{
				$arr[$value['gc_id']][] = $v['gc_id'];
			}
		}
		//获取对应分类的产品
		foreach($arr as $k=>$v)
		{
			$param['gc_id'] = implode(",",$v);
			$param['order'] = 'goods_starttime desc,salenum';
            $tmp = $goods->getGoods($param);
            //获取商品父id
            if (!empty($tmp) && is_array($tmp)) {
                foreach($tmp as $gck=>$gcv) {
                    $class = $goods_class->getOneGoodsClass($gcv['gc_id']);
                    $tmp[$gck]['gc_parent_id'] = $class['gc_parent_id'];
                }
            }
			$goods_list[$k] = $tmp;
            unset($tmp);
		}
        //var_dump($goods_list);die;
		return $goods_list;
	
	}
	
	//团购用到的方法
	private function get_groupbuy_template_list($type,$page='',$param = array()) {

        $model_groupbuy_template = Model('groupbuy_template');
        $param['state'] = self::TEMPLATE_STATE_ACTIVE;
        switch ($type) {
            case 'in_progress':
                $param['in_progress'] = time();
                break;
            case 'soon':
                $param['less_than_start_time'] = time();
                $param['order'] = 'start_time asc';
                break;
            case 'history':
                $param['greater_than_end_time'] = time();
                break;
            default:
                $param['in_progress'] = time();
                break;
        }
        $template_list = $model_groupbuy_template->getList($param,$page);
        return $template_list;
    }
	private function output_count_down($time) {
        $count_down = intval($time) - time();
        Tpl::output('count_down',$count_down);
    }
	private function get_groupbuy_list($type,$template_id,$page = '',$param = array()) {

        $model_groupbuy = Model('goods_group');
        $param['in_template_id'] = $template_id;
        switch ($type) {
            default:
                $param['state_progress_and_close'] = true; 
                break;
        }
        $groupbuy_list = $model_groupbuy->getList($param,$page);
        return $groupbuy_list;
    }
	//end
	
	public function uploadimgOp()
	{
		if(!empty($_FILES['file']['name']))
		{
			$file = $_FILES['file'];
			
			if($file['size'] > 2*1024*1024)
			{
				$msg = '图片大小超过2m';
				echo "<script>parent.error('".$msg."');</script>";
			}
			
			$arr = explode('|','image/jpeg|image/png|image/gif');
			if(!in_array($file['type'],$arr))
			{
				$msg = '上传文件类型不对';
				echo "<script>parent.error('".$msg."');</script>";
			}
			
			
			if(!file_exists('upload/member/'))
				mkdir('upload/member/','0777',true);
			
			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'upload/member/'.$dir.$newName))
			{
				
				$str = 'upload/member/'.$dir.$newName;
				$where = "member_id = ".$_SESSION['member_id']."";
				$update_array['member_idcard'] = $str;
				if(Db::update('member',$update_array, $where))
				{
					echo "<script>parent.stopSend('".$str."');</script>";
				}
				
			}else
			{
				$msg = '上传出错';
				echo "<script>parent.error('".$msg."');</script>";
			}
		}
		Tpl::showpage('index_upload');
	}	
	public function uploadimg2Op()
	{
		if(!empty($_FILES['file']['name']))
		{
			$file = $_FILES['file'];
			
			if($file['size'] > 2*1024*1024)
			{
				$msg = '图片大小超过2m';
				echo "<script>parent.error('".$msg."');</script>";
			}
			
			$arr = explode('|','image/jpeg|image/png|image/gif');
			if(!in_array($file['type'],$arr))
			{
				$msg = '上传文件类型不对';
				echo "<script>parent.error('".$msg."');</script>";
			}
			
		
			if(!file_exists('upload/member/'))
				mkdir('upload/member/','0777',true);
			
			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'upload/member/'.$dir.$newName))
			{
				$str = 'upload/member/'.$dir.$newName;
				$where = "member_id = ".$_SESSION['member_id']."";
				$update_array['member_idcard2'] = $str;
				if(Db::update('member',$update_array, $where))
				{
					echo "<script>parent.stop('".$str."');</script>";
				}

			}else
			{
				$msg = '上传出错';
				echo "<script>parent.error('".$msg."');</script>";
			}
		}
		Tpl::showpage('index_upload2');
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
	
	private function _promotion(){
		$cache_file = BasePath.DS.'cache'.DS.'index'.DS.'promotion.php';
		if (!file_exists($cache_file) || filemtime($cache_file) <= (time()-SESSION_EXPIRE)){
			$limit	= 5;
			$field = 'p_xianshi.xianshi_name,p_xianshi.store_id,p_xianshi.store_name,p_xianshi.start_time,p_xianshi.end_time,'.
			'p_xianshi_goods.goods_id,p_xianshi_goods.goods_name,p_xianshi_goods.goods_image,p_xianshi_goods.goods_store_price,p_xianshi_goods.discount,p_xianshi_goods.xianshi_discount,p_xianshi_goods.xianshi_price';
			$promotion_time = time();
			$condition	= array(
				'start_time'=>array('lt',$promotion_time),
				'end_time'=>array('gt',$promotion_time),
				'p_xianshi.state'=>'2',
				'p_xianshi_goods.state'=>'1'
			);
			$model = Model();
			$list = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where($condition)->order(rand(1,5))->limit($limit)->select();//防止出现全是一个店铺的使用随机排序
			F('promotion',$list,'cache/index');
		} else {
			$list = F('promotion','','cache/index');
		}
		return $list;
	}

	private function _product(){
		//if (!$list = F('product','','cache/index')){
			$recommend_limit	= 6;
			$model_recommend	= Model('recommend');
			$condition	= array(
				'goods_show'=>'1',
				'recommend_id'=>'1',
				'limit'=>$recommend_limit,
				'field'	=> 'recommend_goods.recommend_id,goods.goods_id,goods.store_id,goods.goods_name,goods.goods_image,goods.goods_store_price'
			);
			$list	= $model_recommend->getGoodsList($condition);
		//}
		//F('product',$list,'cache/index');
		return $list;
	}

	public function josn_classOp() {
		
		$model_class		= Model('goods_class');
		$goods_class		= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['gc_id']),'gc_show'=>1,'order'=>'gc_parent_id asc,gc_sort asc,gc_id asc'));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'gc_sort'=>$val['gc_sort']);
			}
		}
		
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));
		} else {
			$array = array_values($array);
		}
		echo json_encode($array);
	}

	public function flea_areaOp() {
		if(intval($_GET['check']) > 0) {
			$_GET['area_id'] = $_GET['region_id'];
		}
		if(intval($_GET['area_id']) == 0) {
			return ;
		}
		$model_area	= Model('flea_area');
		$area_array			= $model_area->getListArea(array('flea_area_parent_id'=>intval($_GET['area_id'])),'flea_area_sort desc');
		$array	= array();
		if(is_array($area_array) and count($area_array)>0) {
			foreach ($area_array as $val) {
				$array[$val['flea_area_id']] = array('flea_area_id'=>$val['flea_area_id'],'flea_area_name'=>htmlspecialchars($val['flea_area_name']),'flea_area_parent_id'=>$val['flea_area_parent_id'],'flea_area_sort'=>$val['flea_area_sort']);
			}
			
			if (strtoupper(CHARSET) == 'GBK'){
				$array = Language::getUTF8(array_values($array));
			} else {
				$array = array_values($array);
			}
		}
		if(intval($_GET['check']) > 0) {
			if(!empty($array) && is_array($array)) {
				echo 'false';
			} else {
				echo 'true';
			}
		} else {
			echo json_encode($array);
		}
	}

	public function josn_flea_classOp() {
		
		$model_class		= Model('flea_class');
		$goods_class		= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['gc_id'])));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'gc_sort'=>$val['gc_sort']);
			}
		}
		
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));
		} else {
			$array = array_values($array);
		}
		echo json_encode($array);
	}

	public function loginOp(){
		echo ($_SESSION['is_login'] == '1')? '1':'0';
	}
}
