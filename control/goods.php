<?php



defined('haipinlegou') or exit('Access Invalid!');



class goodsControl extends BaseStoreControl {



    const XIANSHI_STATE_PUBLISHED = 2;

    const XIANSHI_STATE_END = 5;

    const XIANSHI_GOODS_STATE_NORMAL = 1;

    const MANSONG_STATE_PUBLISHED = 2;

	

	public function __construct() {

		parent::__construct();

		Language::read('store_goods_index');

	}



	

	public function indexOp() {

		if(empty($_GET['goods_id']))showMessage(Language::get('miss_argument'),'','html','error');

		$goods_id	= intval($_GET['goods_id']);

		$model_store_goods	= Model('goods');

		$goods_array = $model_store_goods->where(array('goods_id'=>intval($_GET['goods_id'])))->find();

		if(empty($goods_array) || $goods_array['goods_store_state'] == 1 )showMessage(Language::get('goods_index_no_goods'),'','html','error');

        //店铺信息
		$store_info = $this->getStoreInfo($goods_array['store_id']);



		$_GET['id']	= $goods_array['store_id'];

		$spec_array	= $model_store_goods->getSpecGoods($goods_array['goods_id']);

        //商品分类
        $modelClass=Model('goods_class');
        $goodsClass=$modelClass->getOneGoodsClass($goods_array['gc_id']);
        if(!empty($goods_array['gc_name'])) {
            $goodsClassName = explode('>', $goods_array['gc_name']);
            Tpl::output('goods_class_name', $goodsClassName);
        }
        Tpl::output('goods_class',$goodsClass);


		if(empty($spec_array)) {

			showMessage(Language::get('goods_index_no_goods'),'','html','error');

		}

		if(!empty($spec_array) && is_array($spec_array)){

			foreach ($spec_array as $key => $val){
			 
                $val['goods_tax_money']=number_format($val['goods_tax']*$val['spec_goods_price'],2);

				$s_array	= unserialize($val['spec_goods_spec']);

				$val['spec_goods_spec']	= '';

				if(!empty($s_array) && is_array($s_array)){

					foreach ($s_array as $k=>$v){

						$val['spec_goods_spec'] .= "'".$k."',";

					}

				}

				$val['spec_goods_spec']	= rtrim($val['spec_goods_spec'],',');

				if ($val['spec_id'] == $goods_array['spec_id']){

					$goods_array['spec_goods_storage'] = $val['spec_goods_storage'];

				}

				$spec_array[$key]	= $val;

			}

		}

		$goods_array['goods_spec'] = unserialize($goods_array['goods_spec']);

		$goods_array['spec_name'] = unserialize($goods_array['spec_name']);

		$goods_array['goods_attr'] = unserialize($goods_array['goods_attr']);

		$goods_array['goods_col_img'] = unserialize($goods_array['goods_col_img']);

		

		$model = Model();

		$_times = cookie('tm_visit_product');

		if (empty($_times)){

			$model->table('goods')->where(array('goods_id' => $goods_id))->attr('LOW_PRIORITY')->update(array('goods_click' =>array('exp','goods_click+1')));

			setNcCookie('tm_visit_product',1);

			$goods_array['goods_click'] = intval($goods_array['goods_click'])+1;

		}

		Tpl::output('spec_array',$spec_array);

		Tpl::output('spec_count',count($goods_array['goods_spec']));

		Tpl::output('goods_spec', $goods_array['goods_spec']);

		Tpl::output('goods_col_img', $goods_array['goods_col_img']);

		

		$hash_key = $goods_array['goods_id'];

		$cachekey_arr = array('likenum','sharenum','brand_name');

		if ($_cache = rcache($hash_key,'product')){

			foreach ($_cache as $k=>$v){

				$goods_array[$k] = $v;

			}

		}else {

			$snsgoodsinfo = $model->table('sns_goods')->where(array('snsgoods_goodsid'=>$goods_array['goods_id']))->find();

			$goods_array['likenum'] = $snsgoodsinfo['snsgoods_likenum'];

			$goods_array['sharenum'] = $snsgoodsinfo['snsgoods_sharenum'];

			$brand_array = $model->table('brand')->where(array('brand_id'=>$goods_array['brand_id'],'brand_apply'=>'1'))->find();

			$goods_array['brand_name'] = $brand_array['brand_name'];



			$data = array();

			if (!empty($goods_array)){

				foreach ($goods_array as $k=>$v){

					if (in_array($k,$cachekey_arr)){

						$data[$k] = $v;

					}

				}

			}

			wcache($hash_key,$data,'product');

		}

		

        $store_self = false;

        if(!empty($_SESSION['store_id'])) {

            if ($goods_array['store_id'] == $_SESSION['store_id']) {

                $store_self = true;

            }

        }

        Tpl::output('store_self',$store_self);

        

        

		$desc_image			= explode(',', $goods_array['goods_image_more']);

		if($goods_array['goods_image_more'] == ''){

			$desc_image			= $model_store_goods->getListImageGoods(array('image_store_id'=>$goods_array['store_id'],'item_id'=>$goods_array['goods_id'],'image_type'=>2));

			

			$image_key = 0;

			if(!empty($desc_image) && is_array($desc_image)) {

				$goods_image_1	= $goods_array['goods_image'];

				foreach ($desc_image as $key => $val) {

					if($goods_image_1 == $val['thumb_small']){

						$image_key = $key;break;

					}

				}

				if($image_key > 0) {

					$desc_image_0	= $desc_image[0];

					$desc_image[0]	= $desc_image[$image_key];

					$desc_image[$image_key]	= $desc_image_0;

				}

				$image_array = array();

				foreach ($desc_image as $key => $val) {

					$image_array[] = $val['file_thumb'];

				}

				$desc_image	= $image_array;

			}

		}

		if ($goods_array['transport_id'] > 0){

			$model_transport = Model('transport');

			$transport = $model_transport->getExtendList(array('transport_id'=>$goods_array['transport_id'],'is_default'=>1));

			if (!empty($transport) && is_array($transport)){

				foreach ($transport as $v) {

					$goods_array[$v['type']."_price"] = $v['sprice'];

				}

			}

		}
//print_r($goods_array);
		/*
		 * 检测区间价格
		 * 林涌辉
		 */
		$gprices = explode('-',$goods_array['goods_store_price_interval']);
		($gprices[0] - $gprices[1]) ? '' : $goods_array['goods_store_price_interval']=$gprices[0];
		
		Tpl::output('goods',$goods_array);
		Tpl::output('goods_image',$desc_image[0]);

		Tpl::output('desc_image',$desc_image);



		$area_list = array (1 => '����',2 => '���',3 => '�ӱ�',4 => 'ɽ��',5 => '���ɹ�',6 => '����',7 => '����',8 => '����',9 => '�Ϻ�',10 => '����',11 => '�㽭',12 => '����',13 => '����',14 => '����',15 => 'ɽ��',16 => '����',17 => '����',18 => '����',19 => '�㶫',20 => '����',21 => '����',22 => '����',23 => '�Ĵ�',24 => '����',25 => '����',26 => '����',27 => '����',28 => '����',29 => '�ຣ',30 => '����',31 => '�½�',32 => '̨��',33 => '���',34 => '����',35 => '����');

		if (strtoupper(CHARSET) == 'GBK'){

			$area_list = Language::getGBK($area_list);

		}

		Tpl::output('area_list',$area_list);



		

		

		$goods_commend_list = $model_store_goods->getGoods(array(

			'store_id'=>$goods_array['store_id'],

			'goods_id_diff'=>intval($_GET['goods_id']),

			'goods_show'=>1,

			'goods_commend'=>1,

			'order'=>'goods_commend desc',

			'limit'=>'0,4'

		),'','goods.goods_id,goods.goods_name,goods.goods_image,goods.store_id,goods.goods_store_price','goods');



		Tpl::output('goods_commend',$goods_commend_list);





		

		$cookievalue = $goods_id.'-'.$goods_array['store_id'];

		if(cookie('viewed_goods')){

			$string_viewed_goods = cookie('viewed_goods');

			if (get_magic_quotes_gpc()) $string_viewed_goods = stripslashes($string_viewed_goods);

			$vg_ca = @unserialize($string_viewed_goods);

			$sign = true;

			if(!empty($vg_ca) && is_array($vg_ca)){

				foreach ($vg_ca as $vk=>$vv){

					if($vv == $cookievalue){

						$sign = false;

					}

				}

			}else{

				$vg_ca = array();

			}

				

			if($sign){

				if(count($vg_ca) >= 4){

					$vg_ca[]  = $cookievalue;

					array_shift($vg_ca);

				}else{

					$vg_ca[]  = $cookievalue;

				}				

			}

		}else{

			$vg_ca[]  = $cookievalue;

		}

		$vg_ca = serialize($vg_ca);	

        setNcCookie('viewed_goods',$vg_ca);



        $group_flag = FALSE;

        if(intval($goods_array['group_flag']) === 1) {

            $group_list = $this->get_group_list($goods_id);

            if(!empty($group_list) && is_array($group_list)) {

                $current_time = time();

                foreach($group_list as $group) {

                    if(intval($group['start_time']) < $current_time && intval($group['end_time']) > $current_time) {

                        if(intval($group['state']) === 3) {

                            if($group['def_quantity'] < $group['max_num']) {

                                $group_flag = TRUE;

                                Tpl::output('group_info',$group);

                                break;

                            }

                        }

                    }

                }

            } else {

                $model_store_goods->updateGoods(array('group_flag'=>0),$goods_id); 

            }

        }

        Tpl::output('group_flag',$group_flag);



        $xianshi_flag = FALSE;
        $start_flag = FALSE;
        $goods_xianshi_flag = FALSE;
        if(intval($GLOBALS['setting_config']['promotion_allow']) === 1){
            if(!$group_flag && intval($goods_array['xianshi_flag']) === 1) {

                $xianshi_goods = $this->get_xianshi_goods($goods_id);
                if(!empty($xianshi_goods) && intval($xianshi_goods['state']) === self::XIANSHI_GOODS_STATE_NORMAL ) {

                    $xianshi_id = $xianshi_goods['xianshi_id'];

                    $model_xianshi = Model('p_xianshi');

                    $xianshi_info = $model_xianshi->getOne($xianshi_id);

                    if(!empty($xianshi_info)) {

                        if(intval($xianshi_info['state']) === self::XIANSHI_STATE_PUBLISHED) {

                            $current_time = time();

                            if(intval($xianshi_info['end_time']) > $current_time) {

                                $xianshi_flag = TRUE;

                                if(intval($xianshi_info['start_time']) < $current_time) {

                                    $start_flag = TRUE;

                                }

                                Tpl::output('xianshi_info',$xianshi_info);
                                $xianshi_goods['discount'] /= 10;

                                Tpl::output('xianshi_goods',$xianshi_goods);
                            }

                            else {

                                $model_xianshi->update(array('state'=>self::XIANSHI_STATE_END),array('xianshi_id'=>$xianshi_id));

                                $goods_xianshi_flag = TRUE;

                            }

                        }

                        else {

                            $goods_xianshi_flag = TRUE;

                        }

                    }

                } else {

                    $goods_xianshi_flag = TRUE;

                }

            }

        } else {

            if(intval($goods_array['xianshi_flag']) === 1) {

                $goods_xianshi_flag = TRUE;

            }

        }



        if($goods_xianshi_flag) { 

            $model_store_goods->updateGoods(array('xianshi_flag'=>0),$goods_id); 

        }



        Tpl::output('xianshi_flag',$xianshi_flag);

        Tpl::output('start_flag',$start_flag);



        if(intval($GLOBALS['setting_config']['promotion_allow']) === 1){

            if($group_flag || $xianshi_flag) {

                Tpl::output('mansong_flag',FALSE);

            }

            else {

                $this->output_mansong($goods_array['store_id']);

            }

        } else {

            Tpl::output('mansong_flag',FALSE);

        }

		

		//�̵�ͷ���ж��Ƿ�����������ĵ�����

		if($_SESSION['is_login'] == '1'){

			$member_model	= Model('member');

			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');

			Tpl::output('member_info',$member_info);

		}

		

		

        Tpl::output('page','goods');

        

		//S�Ų�������ʾ

		$list = $this->_article();

		//E�Ų�������ʾ


 		$seo_param = array();

		$seo_param['name'] = $goods_array['goods_name'];

		$seo_param['key']  = $goods_array['goods_keywords'];

		$seo_param['description'] = $goods_array['goods_description'];

		Model('seo')->type('product')->param($seo_param)->show();   

		

		//�ж��Ƿ���Ӫ��Ʒ

		Tpl::output('store_id',$goods_array['store_id']);
    
        Tpl::showpage('goods');

	}

	

	//S�Ų�������ʾ

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

		if(!file_exists(BasePath.'/cache/index')){
			mkdir(BasePath.'/cache/index','0777', true);
		}

		file_put_contents(BasePath.'/cache/index/article.php',compress_code($string));



		Tpl::output('show_article',$show_article);

		Tpl::output('article_list',$article_list);

	}

    //E�Ų�������ʾ

   

    private function get_group_list($goods_id) {



        $current_time = time();

        $model_group = Model('goods_group');

        $param = array();

        $param['goods_id'] = $goods_id;

        $param['state'] = 3;

        $param['less_than_end_time'] = $current_time;

        return $model_group->getList($param);

    }

   

    private function get_xianshi_goods($goods_id) {



        $model_xianshi_goods = Model('p_xianshi_goods');

        $param = array();

        $param['goods_id'] = $goods_id;

        $param['order'] = 'xianshi_goods_id desc';

        $param['limit'] = 1;

        $xianshi_goods = $model_xianshi_goods->getList($param);

        return $xianshi_goods[0];

    }

   

    private function output_mansong($store_id) {



        $model_mansong = Model('p_mansong');

        $param = array();

        $param['state'] = self::MANSONG_STATE_PUBLISHED;

        $current_time = time();

        $param['greater_than_start_time'] = $current_time;

        $param['less_than_end_time'] = $current_time;

        $param['store_id'] = $store_id;

        $param['limit'] = 1;

        $mansong_list = $model_mansong->getList($param);

        $mansong = $mansong_list[0];

        $mansong_flag = FALSE;

        if(!empty($mansong)) {

            $model_mansong_rule = Model('p_mansong_rule');

            $mansong_rule = $model_mansong_rule->getList(array('mansong_id'=>$mansong['mansong_id'],'order'=>'level asc'));

            if(!empty($mansong_rule)) {

                $mansong_flag = TRUE;

                Tpl::output('mansong_info',$mansong);

                Tpl::output('mansong_rule',$mansong_rule);

            }

        }

        Tpl::output('mansong_flag',$mansong_flag);

    }



   

	public function commentsOp() {

		$goods_id = intval($_GET['goods_id']);

		$result = true;

		if ($goods_id <= 0){

			$result = false;

		}

		if ($result){

			$goods_model = Model('goods');

			$goods_info = $goods_model->getOne($goods_id);

			if (empty($goods_info)){

				$result = false;

			}

		}

		if ($result){

			$evaluate_defaulttext = array('1'=>Language::get('nc_credit_defaultcontent_good'),'0'=>Language::get('nc_credit_defaultcontent_normal'),'-1'=>Language::get('nc_credit_defaultcontent_bad'));

			$condition = array();

			if($_GET['evalscore']){

				switch($_GET['evalscore']){

					case 1:

						$condition['geval_scores'] = '1';

						break;

					case 2:

						$condition['geval_scores'] = '0';

						break;

					case 3:

						$condition['geval_scores'] = '-1';

						break;

				}

			}

			if(intval($_GET['havecontent']) == 1){

				$condition['havecontent'] = 'yes';

			}

			$condition['geval_goodsid'] = "{$goods_id}";

			$condition['geval_storeid'] = "{$goods_info['store_id']}";

			$condition['geval_type'] = "1";

			$condition['geval_showtime_lt'] = time();

			$page	= new Page();

			$page->setEachNum(10);

			$page->setStyle('admin');

			$evaluate_model = Model("evaluate");

			$goodsevallist = $evaluate_model->getGoodsEvalList($condition,$page,'*','member');
			if (!empty($goodsevallist)){

				foreach ($goodsevallist as $k=>$v){					

					if ($v['geval_content'] == '' || $v['geval_state'] == '1'){

						$v['geval_content'] = $evaluate_defaulttext[$v['geval_scores']];

					}

					switch ($v['geval_scores']){

						case 1:

							$v['geval_scoressign'] = 'good';

							break;

						case 0:

							$v['geval_scoressign'] = 'normal';

							break;

						case -1:

							$v['geval_scoressign'] = 'bad';

							break;

					}

					$v['credit_arr'] = getCreditArr(intval($v['member_credit']));

					$v['geval_frommembername'] = $v['geval_isanonymous'] == 1?str_cut($v['geval_frommembername'],2).'***':$v['geval_frommembername'];

					$goodsevallist[$k] = $v;

				}

			}

			Tpl::output('goods_info',$goods_info);

			Tpl::output('goodsevallist',$goodsevallist);

			Tpl::output('show_page',$page->show());

		}

		Tpl::showpage('comments','null_layout');

	}

	



	public function salelogOp() {		

		$goods_id	 = intval($_GET['goods_id']);

		$order_class = Model('order');

		

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

		

		$sales = $order_class->getOrderGoodsList(array(

		'`order_goods`.goods_id'=>$goods_id,

		'status_no'=>'0',

		'order'=>'`order`.add_time desc'

		),$page);

		

		Tpl::output('show_page',$page->show());

		

		Tpl::output('sales',$sales);

		Tpl::showpage('salelog','null_layout');

	}

	

	public function cosultingOp() {

		$goods_id	 = intval($_GET['goods_id']);

		if($goods_id <= 0){

			showMessage(Language::get('wrong_argument'),'','html','error');

		}

		$page	= new Page();

		$page->setEachNum(10);

		$page->setStyle('admin');

				

		$consult		= Model('consult');

		$consult_list	= $consult->getConsultList(array('goods_id'=>$goods_id),$page,'simple');

		Tpl::output('consult_list',$consult_list);

		

		Tpl::output('show_page', $page->show());		

		

		$store_self = false;

        if(!empty($_SESSION['store_id'])) {

            if (intval($_GET['id']) == $_SESSION['store_id']) {

                $store_self = true;

            }

        }

        $member_info	= array();

        $member_model = Model('member');

        if(!empty($_SESSION['member_id'])) $member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}"));

        $consult_able = true;

        if((!$GLOBALS['setting_config']['guest_comment'] && !$_SESSION['member_id'] ) || $store_self == true || ($_SESSION['member_id']>0 && $member_info['is_allowtalk'] == 0)){

        	$consult_able = false;

        }

        Tpl::output('nchash',substr(md5(SiteUrl.$_GET['act'].$_GET['op']),0,8));

        Tpl::output('consult_able',$consult_able);

		Tpl::showpage('cosulting');

	}

	

	public function save_consultajaxOp(){

        if(!C('guest_comment') && !$_SESSION['member_id']){

        	echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_goods_noallow')));

        	die;

        }

		$goods_id	 = intval($_GET['goods_id']);

		if($goods_id <= 0){

			echo json_encode(array('done'=>'false','msg'=>Language::get('wrong_argument')));

        	die;

		}

		if(trim($_GET['goods_content'])== ""){

			echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_input_consult')));

        	die;

		}

		if(C('captcha_status_goodsqa') == '1' && !checkSeccode($_GET['nchash'],$_GET['captcha'])){

			echo json_encode(array('done'=>'false','msg'=>Language::get('wrong_checkcode')));

        	die;

		}

        if (check_repeat('comment')){

        	echo json_encode(array('done'=>'false','msg'=>Language::get('nc_common_op_repeat')));

        	die;

        }		

        if($_SESSION['member_id']){

	        $member_model = Model('member');

	        $member_info = $member_model->infoMember(array('member_id'=>"{$_SESSION['member_id']}"));

			if(empty($member_info) || $member_info['is_allowtalk'] == 0){

	        	echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_goods_noallow')));

        		die;

	        }

        }

		$goods	= Model('goods');

		$goods_info	= array();

		$goods_info	= $goods->checkGoods(array('goods_id'=>"{$goods_id}"));

		if(empty($goods_info)){

			echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_goods_not_exists')));

        	die;

		}

        if($_SESSION['store_id'] && $goods_info['store_id'] == $_SESSION['store_id']) {

            echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_consult_store_error')));

        	die;

        }

		$store_model = Model('store');

		$store_info	= $store_model->shopStore(array('store_id'=>"{$goods_info['store_id']}"));

		if($store_info['store_state'] == '0' || intval($store_info['store_state']) == '2' || (intval($store_info['store_end_time']) != 0 && $store_info['store_end_time'] <= time())){

			echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_goods_store_closed')));

        	die;

		}

		$input	= array();

		$input['goods_id']			= $goods_id;

		$input['cgoods_name']		= $goods_info['goods_name'];

		$input['member_id']			= intval($_SESSION['member_id']) > 0?$_SESSION['member_id']:0;

		$input['cmember_name']		= $_SESSION['member_name']?$_SESSION['member_name']:'';

		$input['seller_id']			= $store_info['member_id'];

		$input['email']				= $_GET['email'];

		if (strtoupper(CHARSET) == 'GBK') {

			$input['consult_content']	= Language::getGBK($_GET['goods_content']);

		}else{

			$input['consult_content']	= $_GET['goods_content'];

		}

		$input['isanonymous']		= $_GET['hide_name']=='hide'?1:0;

		$consult_model	= Model('consult');

		if($consult_model->addConsult($input)){

			echo json_encode(array('done'=>'true'));

        	die; 

		}else{

			echo json_encode(array('done'=>'false','msg'=>Language::get('goods_index_consult_fail')));

        	die; 

		}

	}

	

	public function get_bundlingOp(){

		$goods_id = intval($_GET['goods_id']);

		$store_id = intval($_GET['id']);

		if($goods_id <= 0 || $store_id <= 0){

			exit;

		}

		$model = Model();

		

		$update = array();

		$update['bl_quota_state']	= '0';

		$model->table('p_bundling_quota')->where('store_id='.$store_id.' and bl_quota_endtime<'.time())->update($update);

		

		$quota_list = $model->table('p_bundling_quota')->where('store_id='.$store_id.' and bl_quota_state=1')->select();

		if(!empty($quota_list)){

			$b_g_list = $model->table('p_bundling_goods')->field('bl_id')->where('goods_id='.$goods_id)->select();

	

			if(!empty($b_g_list) && is_array($b_g_list)){

				$b_id_array = array();

				foreach ($b_g_list as $val){

					$b_id_array[] = $val['bl_id'];

				}

				

				$bundling_list	= $model->table('p_bundling')->where('bl_id in ('.implode(',', $b_id_array).') and bl_state=1')->select();

				if(!empty($bundling_list) && is_array($bundling_list)){

					$bundling_array = array();

					foreach ($bundling_list as $val){

						$bundling_array[$val['bl_id']]['id']		= $val['bl_id'];

						$bundling_array[$val['bl_id']]['name']		= $val['bl_name'];

						$bundling_array[$val['bl_id']]['cost_price']= 0;

						$bundling_array[$val['bl_id']]['price']		= $val['bl_discount_price'];

					}

				}

	

				$b_goods_list	= $model->table('p_bundling_goods,goods')

										->field('p_bundling_goods.bl_goods_id, p_bundling_goods.bl_id, p_bundling_goods.goods_id, p_bundling_goods.goods_name, goods.goods_image, goods.goods_store_price, goods.store_id')

										->join('inner')->on('p_bundling_goods.goods_id=goods.goods_id')

										->where('bl_id in ('.implode(',', $b_id_array).') and goods_show=1')->select();

				if(!empty($b_goods_list) && is_array($b_goods_list)){

					$b_goods_array	= array();

					foreach($b_goods_list as $val){

						$k = intval($val['goods_id']) == $goods_id?0:$val['goods_id'];

						$b_goods_array[$val['bl_id']][$k]['id']		= $val['goods_id'];

						$b_goods_array[$val['bl_id']][$k]['image']	= $val['goods_image'];

						$b_goods_array[$val['bl_id']][$k]['price']	= $val['goods_store_price'];

						$b_goods_array[$val['bl_id']][$k]['name']		= $val['goods_name'];

						$b_goods_array[$val['bl_id']][$k]['store_id']	= $val['store_id'];

						$bundling_array[$val['bl_id']]['cost_price']	+= intval($val['goods_store_price']);

					}

				}



				Tpl::output('bundling_array', $bundling_array);

				Tpl::output('b_goods_array', $b_goods_array);

			}

		}

		Tpl::showpage('goods_bundling','null_layout');

	}

	

	function calcOp(){

		if (!is_numeric($_GET['id']) || !is_numeric($_GET['tid'])) return false;



		$model_transport = Model('transport');

		$extend = $model_transport->getExtendList(array('transport_id'=>array(intval($_GET['tid']))));

		if (!empty($extend) && is_array($extend)){

			$calc = array();

			$calc_default = array();

			foreach ($extend as $v) {

				if (strpos($v['top_area_id'],",".intval($_GET['id']).",") !== false){

					$calc[$v['type']] = $v['sprice'];

				}

				if ($v['is_default']==1){

					$calc_default[$v['type']] = $v['sprice'];

				}

			}

			foreach (array('py','kd','es') as $v){

				if (!isset($calc[$v]) && isset($calc_default[$v])){

					$calc[$v] = $calc_default[$v];

				}

			}

		}

		echo json_encode($calc);

	}

	

	

	function get_s_aOp(){

		if(!is_numeric($_GET['goods_id']) || !is_numeric($_GET['id'])) die('null');



		$model	= Model();

		$goods_info		= $model->table('goods')->field('spec_name, goods_spec, goods_attr, goods_col_img')->find($_GET['goods_id']);

		

		if(C('spec_model') == 1){

			$spec_name	= unserialize($goods_info['spec_name']);

			if(!empty($spec_name) && is_array($spec_name)){

				$k = array();

				foreach($spec_name as $key=>$val){

					$k[] = $key;

				}

				if (!empty($k)) $return = $model->table('spec')->field('sp_id, sp_name')->select(implode(',', $k));

				if(!empty($return) && is_array($return)){

					foreach ($return as $val){

						if(isset($spec_name[$val['sp_id']])) $spec_name[$val['sp_id']] = $val['sp_name'];

					}

				}

			}

			

			$goods_spec = unserialize($goods_info['goods_spec']);

			if(!empty($goods_spec) && is_array($goods_spec)){

				$k = array();

				foreach ($goods_spec as $value){

					if(!empty($value) && is_array($value)){

						foreach ($value as $key=>$val){

							$k[] = $key;

						}

					}

				}

				if (!empty($k)) $return = $model->table('spec_value')->field('sp_value_id, sp_value_name, sp_id')->select(implode(',', $k));

				if(!empty($return) && is_array($return)){

					foreach ($return as $val){

						if(isset($goods_spec[$val['sp_id']][$val['sp_value_id']])) $goods_spec[$val['sp_id']][$val['sp_value_id']] = array('name'=>$val['sp_value_name'],'id'=>$val['sp_value_id']);

					}

				}

			}

			

			$goods_col_img = unserialize($goods_info['goods_col_img']);

		}else{

			$spec_name		= 'null';

			$goods_spec		= 'null';

			$goods_col_img	= 'null';

		}

		

		$goods_attr	= unserialize($goods_info['goods_attr']);

		if(!empty($goods_attr) && is_array($goods_attr)){

			$k = array();

			foreach ($goods_attr as &$value){

				if(!empty($value) && is_array($value)){

					foreach ($value as $key=>$val){

						if(is_numeric($key)) $k[] = $key;

					}

				}

			}

			if (!empty($k)) $return = $model->table('attribute_value,attribute')->join('inner')->on('attribute_value.attr_id=attribute.attr_id')->field('attr_value_id, attr_value_name,attribute_value.attr_id, attr_name')->where('attr_value_id in ('.implode(',', $k).')')->select();

			if(!empty($return) && is_array($return)){

				foreach ($return as $key=>$val){

					if(isset($goods_attr[$val['attr_id']]))$goods_attrs[$val['attr_id']] = array('name'=>$val['attr_name'],'value'=>$val['attr_value_name']);

				}

			}else{

				$goods_attrs = 'null';

			}

		}

		

		$data['spec_name']		= $spec_name;

		$data['goods_spec']		= $goods_spec;

		$data['goods_attr']		= $goods_attrs;

		$data['goods_col_img']	= $goods_col_img?$goods_col_img:'null';

		

		

		

		if (strtoupper(CHARSET) == 'GBK'){

			$data = Language::getUTF8($data);

		}

		echo json_encode($data);

	}

}

