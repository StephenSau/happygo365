<?php



defined('haipinlegou') or exit('Access Invalid!');



class show_groupbuyControl extends BaseHomeControl {



  

    const STATE_VERIFY =  1;

    const STATE_CANCEL =  2;

    const STATE_PROGRESS =  3;

    const STATE_VERIFY_FAIL =  4;

    const STATE_CLOSE =  5;

    const TEMPLATE_STATE_ACTIVE = 1;

    const TEMPLATE_STATE_UNACTIVE = 2;



    public function __construct() {

        parent::__construct();

		

     

        Language::read('member_groupbuy,home_cart_index');

	

        if (intval($GLOBALS['setting_config']['groupbuy_allow']) !== 1){

            showMessage(Language::get('groupbuy_unavailable'),'index.php','','error');

        }

    }



	

    public function indexOp() {

        $this->groupbuy_listOp();

		

	}



   

    public function groupbuy_listOp() {

		$g_cache = ($cache = F('groupbuy'))? $cache : H('groupbuy',true,'file');

		

        $template_in_progress = $this->get_groupbuy_template_list('in_progress');

        Tpl::output('groupbuy_template',$template_in_progress[0]);



      

        $this->output_count_down($template_in_progress[0]['end_time']);



      

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

		//S脚部内容显示



		$list = $this->_article();



        //E脚部内容显示

        $groupbuy_list = $this->get_groupbuy_list('in_progress',$template_in_progress[0]['template_id'],$page,$param);

        Tpl::output('groupbuy_list',$groupbuy_list);

        Tpl::output('show_page',$page->show());



	



        Tpl::output('class_list',$g_cache['category']);

        Tpl::output('area_list',$g_cache['area']);

        Tpl::output('price_list',$g_cache['price']);

		Tpl::output('index_sign','groupbuy');

		Tpl::output('html_title',Language::get('text_groupbuy_list'));



		Model('seo')->type('group')->show();



		Tpl::showpage('groupbuy_list');

    }

    

	//S脚部内容显示

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

    //E脚部内容显示

   

    public function groupbuy_soonOp() {



        $param = array();

        $param['order'] = 'template_id asc';

        $param['limit'] = 1;

        $template_soon = $this->get_groupbuy_template_list('soon','',$param);

        Tpl::output('groupbuy_template',$template_soon[0]);



     

        if(!empty($template_soon) && is_array($template_soon)) {

            $groupbuy_list = $this->get_groupbuy_list('soon',$template_soon[0]['template_id']);

            Tpl::output('groupbuy_list',$groupbuy_list);

        }



		Tpl::output('index_sign','groupbuy');

		Model('seo')->type('group')->show();

		Tpl::showpage('groupbuy_soon');

    }



  

    public function groupbuy_historyOp() {

        

      



        

        $page = new Page();

        $page->setEachNum(3);

        $page->setStyle('admin');



        $template_history = $this->get_groupbuy_template_list('history',$page);

        Tpl::output('groupbuy_template',$template_history);

        Tpl::output('show_page',$page->show());



        $template_id_array = array();

        if(!empty($template_history) && is_array($template_history)) {

            foreach($template_history as $template) {

                $template_id_array[] = $template['template_id'];

            }

        }



     

        $groupbuy_list = $this->get_groupbuy_list('history',implode(',',$template_id_array));

        Tpl::output('groupbuy_list',$groupbuy_list);



      

        $this->get_hot_groupbuy_list();



		Tpl::output('index_sign','groupbuy');

		Model('seo')->type('group')->show();

		Tpl::showpage('groupbuy_history');

    }



  

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



  

    private function get_hot_groupbuy_list() {



        $model_groupbuy = Model('goods_group');

        $param = array();

        $param['state'] = self::STATE_CLOSE;

        $param['expire'] = time();

        $param['order'] = 'buyer_count desc';

        $param['limit'] = 10;

        $groupbuy_list = $model_groupbuy->getList($param,$page);

        Tpl::output('hot_groupbuy_list',$groupbuy_list);

    }





   

    public function groupbuy_detailOp() {



        $group_id = intval($_GET['group_id']);

        if($group_id === 0) {

            showMessage(Language::get('param_error'),'index.php?act=show_groupbuy','','error');

        }



       

        $model_group = Model('goods_group');

        $groupbuy_info = $model_group->getOne($group_id);

        if(empty($groupbuy_info)) {

            showMessage(Language::get('param_error'),'index.php?act=show_groupbuy','','error');

        }



       

        if(intval($groupbuy_info['state']) === self::STATE_PROGRESS) {

            if(intval($groupbuy_info['end_time']) < time() || $groupbuy_info['def_quantity'] >= $groupbuy_info['max_num']) {

                $update_array = array();

                $update_array['state'] = self::STATE_CLOSE;

                $model_group->update($update_array,array('group_id'=>$group_id));

            }

        }



      

        $update_array = array();

        $update_array['views'] = array('sign'=>'increase','value'=>1);

        $model_group->update($update_array,array('group_id'=>$group_id));



        Tpl::output('groupbuy_info',$groupbuy_info);



     

        $groupbuy_state = intval($groupbuy_info['state']); 

    

        $this->output_groupbuy_state_message($groupbuy_info);





      

        $param = array();

        $param['template_id'] = $groupbuy_info['template_id'];

        $param['state'] = self::STATE_PROGRESS;

        $param['order'] = 'buyer_count desc';

        $param['limit'] = 10;

        $groupbuy_list_in_progress = $model_group->getList($param,$page);

        Tpl::output('hot_groupbuy_list_in_progress',$groupbuy_list_in_progress);



   

        $model_order = Model('order');

        $param = array();

        $param['group_id'] = $group_id;

        $param['limit'] = 20;

        $order_list = $model_order->getOrderList($param);

        Tpl::output('order_list',$order_list);



        

        $model_goods = Model('goods');

        $param = array();

        $param['store_id'] = $groupbuy_info['store_id'];

        $param['limit'] = 10;

        Tpl::output('commend_goods_list',$model_goods->getCommenGoods($param));



       

        $this->get_hot_groupbuy_list();



		Tpl::output('index_sign','groupbuy');



		Model('seo')->type('group_content')->param(array('name'=>$groupbuy_info['group_name']))->show();

		Tpl::showpage('groupbuy_detail');

    }



    private function output_groupbuy_state_message($groupbuy_info) {



        $state_message = array();

        $start_time = intval($groupbuy_info['start_time']);

        $end_time = intval($groupbuy_info['end_time']);

        $current = time();



        switch (intval($groupbuy_info['state'])) {

        case self::STATE_VERIFY:

            $state_message = $this->get_state_array_verify();

            break;

        case self::STATE_PROGRESS:

            if($current < $start_time) {

         

                $state_message['class'] = 'not-start';

                $state_message['text'] = Language::get('groupbuy_message_not_start');

                $state_message['count_down'] = TRUE;

                $state_message['count_down_text'] = Language::get('text_start_time');

                $state_message['hide_virtual_quantity'] = TRUE;

         

                $this->output_count_down($groupbuy_info['start_time']);

            } else {

                if($current > $end_time) {

                

                    $state_message = $this->get_state_array_close();

                } else {

                    if($groupbuy_info['def_quantity'] >= $groupbuy_info['max_num']) {

                        $state_message = $this->get_state_array_close();

                    }

                    else {

                

                        $state_message['class'] = 'buy-now';

                        $state_message['text'] = Language::get('groupbuy_message_start');

                        $state_message['count_down'] = TRUE;

                        $state_message['count_down_text'] = Language::get('text_end_time');

                    

                        $this->output_count_down($groupbuy_info['end_time']);

                    }

                }

            }

            break;

        case self::STATE_VERIFY_FAIL:

            $state_message = $this->get_state_array_verify();

            break;

        case self::STATE_CLOSE:

            $state_message = $this->get_state_array_close();

            break;

        default:

            showMessage(Language::get('param_error'),'','','error');

            break;

        }

        Tpl::output('groupbuy_message',$state_message);

    }



    private function get_state_array_verify() {

        $state_message = array();

        $state_message['class'] = 'not-verify';

        $state_message['text'] = '';

        $state_message['count_down'] = FALSE;

        $state_message['count_down_text'] = '';

        $state_message['hide_virtual_quantity'] = TRUE;

        return $state_message;

    }

    private function get_state_array_close() {

        $state_message = array();

        $state_message['class'] = 'close';

        $state_message['text'] = Language::get('groupbuy_message_close');

        $state_message['count_down'] = FALSE;

        $state_message['count_down_text'] = '';

        return $state_message;

    }



  

    private function output_count_down($time) {

        $count_down = intval($time) - time();

        Tpl::output('count_down',$count_down);

    }





	public function groupbuy_buyOp(){



     

        $group_id = intval($_GET['group_id']);

        $groupbuy_info = $this->get_groupbuy_info($group_id);



     

        if ($_SESSION['is_login'] !== '1'){

			$ref_url = request_uri();

            @header("location: index.php?act=login&ref_url=".urlencode($ref_url));

            exit;

        }

        Tpl::output('groupbuy_info',$groupbuy_info);





        if(intval($groupbuy_info['state']) !== self::STATE_PROGRESS) {

            showMessage(Language::get('param_error'),'','','error');

        }

        $current_time = time();

        if(intval($groupbuy_info['start_time']) > $current_time) { 

            showMessage(Language::get('param_error'),'','','error');

        }

        if(intval($groupbuy_info['end_time']) < $current_time) { 

            showMessage(Language::get('param_error'),'','','error');

        }



        if(intval($groupbuy_info['store_id']) === intval($_SESSION['store_id'])) {

            showMessage(Language::get('can_not_buy'),'','','error');

        }





       

        $spec_id = intval($_GET['groupbuy_spec_id']);

        $spec_info = $this->get_goods_spec($spec_id);
        
       

        $quantity = intval($_GET['groupbuy_quantity']);
        $total_ems=0;
         $total_ems=$groupbuy_info['groupbuy_price']*$spec_info['goods_tax']*$quantity;
        if($total_ems < 50){
           $total_ems =0;
        }
        
        Tpl::output('total_ems',$total_ems);

       

        if(intval($spec_info['spec_goods_storage']) < $quantity) {

            showMessage(Language::get('goods_not_enough'),'index.php?act=show_groupbuy&op=groupbuy_detail&group_id='.$group_id,'','error');

        }



      

        if((intval($groupbuy_info['max_num']) - intval($groupbuy_info['def_quantity'])) < $quantity) {

            showMessage(Language::get('groupbuy_not_enough'),'index.php?act=show_groupbuy&op=groupbuy_detail&group_id='.$group_id,'','error');

        }



        if($spec_info['goods_id'] != $groupbuy_info['goods_id']) {

            showMessage(Language::get('param_error'),'','','error');

        }

        Tpl::output('spec_id',$spec_id);

        $spec_text = $this->get_goods_spec_text($spec_info);
        

        Tpl::output('spec_text',$spec_text);

        Tpl::output('quantity',$quantity);



        

       

		$mode_address	= Model('address');

		$address_list	= $mode_address->getAddressList(array('member_id'=>$_SESSION['member_id'],'order'=>'address_id desc'));

		Tpl::output('address_list',$address_list);



		Tpl::showpage('groupbuy_buy');

	}

   

    private function get_groupbuy_info($group_id) {

        $model_group = Model('goods_group');

        $groupbuy_info = $model_group->getOne($group_id);

        if(empty($groupbuy_info)) {

            showMessage(Language::get('param_error'),'index.php?act=show_groupbuy','','error');

        }

        return $groupbuy_info;

    }

    

    private function get_goods_spec($spec_id) {

        $model_goods_spec = Model('goods_spec');

        $spec_info = $model_goods_spec->getOne($spec_id);

        if(empty($spec_info)) {

            showMessage(Language::get('param_error'),'','','error');

        }

        return $spec_info;

    }

  

    private function get_goods_spec_text($spec_info) {

        $spec_name = unserialize($spec_info['spec_name']);

        $spec_goods = unserialize($spec_info['spec_goods_spec']);



        $spec_text = '';

        if(!empty($spec_name) && is_array($spec_name)) {

            $spec_name = array_values($spec_name);

            $i = 0;

            if(is_array($spec_name) && is_array($spec_goods)) {

                foreach ($spec_goods as $val) {

                    $spec_text .= $spec_name[$i].':'.$val.' ';

                    $i++;

                }

            }

        }

        return $spec_text;

    }



  

    private function change_groupbuy_state($condition,$state) {



        $model = Model('goods_group');

        return $model->update(array('state'=>$state),$condition);

    }



	

	public function groupbuy_orderOp() {



    

        if ($_SESSION['is_login'] !== '1'){

			$ref_url = 'index.php?act=show_groupbuy'; 

            @header("location: index.php?act=login&ref_url=".urlencode($ref_url));

            exit;

        }



      

        $spec_id = intval($_POST['spec_id']);

        $quantity = intval($_POST['quantity']);

        if(empty($spec_id) || empty($quantity)) {

            showMessage(Language::get('param_error'),'','','error');

        }



     

        $group_id = intval($_POST['group_id']);

        $groupbuy_info = $this->get_groupbuy_info($group_id);

        $store_id = $groupbuy_info['store_id'];

       


        if(intval($groupbuy_info['store_id']) === intval($_SESSION['store_id'])) {

            showMessage(Language::get('can_not_buy'),'','','error');

        }



        

        if((intval($groupbuy_info['max_num']) - intval($groupbuy_info['def_quantity'])) <= 0) {

            

            

            $this->change_groupbuy_state(array('group_id'=>$group_id),self::STATE_CLOSE);

            showMessage(Language::get('groupbuy_closed'),'','','error');

        }

       

        $current_time = time();

        if(intval($groupbuy_info['start_time']) > $current_time) {

            showMessage(Language::get('groupbuy_not_state'),'','','error');

        }

        if(intval($groupbuy_info['end_time']) < $current_time) {

            showMessage(Language::get('groupbuy_closed'),'','','error');

        }

     

        if(intval($groupbuy_info['state']) !== self::STATE_PROGRESS) {

            showMessage(Language::get('groupbuy_closed'),'','','error');

        }

      

        $history_buy_count = 0;

        $model_order = Model('order');

        $history_order = $model_order->getOrderList(array('group_id'=>$group_id,'buyer_id'=>$_SESSION['member_id']),'','order_id');

        if(!empty($history_order) && is_array($history_order)) {

            $history_order_id = '';

            foreach($history_order as $order) {

                $history_order_id .= $order['order_id'].',';

            }

            $history_order_id = rtrim($history_order_id,',');

            $model_order_goods = Model('order_goods');

            $order_goods_list = $model_order_goods->getOrderGoodsList(array('in_order_id'=>$history_order_id),' sum(goods_num) ');

            $history_buy_count = intval($order_goods_list[0][0]);

        }

        if(intval($groupbuy_info['sale_quantity']) > 0) {

            if(intval($groupbuy_info['sale_quantity']) < ($quantity+$history_buy_count)) {

                showMessage(Language::get('groupbuy_sale_quantity').$groupbuy_info['sale_quantity'].Language::get('groupbuy_index_jian'),'','','error');

            } 



        }



      

        $spec_info = $this->get_goods_spec($spec_id);

         $total_ems=0;
        $total_ems=$groupbuy_info['groupbuy_price']*$spec_info['goods_tax']*$quantity;
        if($total_ems < 50){
           $total_ems =0;
        }

    

        if(intval($spec_info['spec_goods_storage']) < $quantity) {

            showMessage(Language::get('goods_not_enough'),'index.php?act=show_groupbuy&op=groupbuy_detail&group_id='.$group_id,'','error');

        }



       

        if((intval($groupbuy_info['max_num']) - intval($groupbuy_info['def_quantity'])) < $quantity) {

            showMessage(Language::get('groupbuy_not_enough'),'index.php?act=show_groupbuy&op=groupbuy_detail&group_id='.$group_id,'','error');

        }



       

        $model_goods = Model('goods');

        $goods_info = $model_goods->getGoods(array('goods_id'=>$groupbuy_info['goods_id']),'','','goods'); 



      

        if(intval($goods_info[0]['goods_show']) !== 1 || intval($goods_info[0]['goods_state']) !== 0) {

            showMessage(Language::get('param_error'),'','','error');

        }



     

        if(intval($spec_info['spec_goods_storage']) < $quantity) {

            showMessage(Language::get('goods_not_enough'),'index.php?act=show_groupbuy&op=groupbuy_detail&group_id='.$group_id,'','error');

        }



        $model_store = Model('store');

        $store_info = $model_store->getOne($groupbuy_info['store_id']);



		

		$order_array		= array();

		$order_array['order_sn']		= $model_order->snOrder();

		$order_array['seller_id']		= $store_info['member_id'];

		$order_array['store_id']		= $groupbuy_info['store_id'];

		$order_array['store_name']		= $groupbuy_info['store_name'];

		$order_array['buyer_id']		= $_SESSION['member_id'];

		$order_array['buyer_name']		= $_SESSION['member_name'];

		$order_array['buyer_email']		= $_SESSION['member_email'];

		$order_array['add_time']		= time();

		$order_array['out_sn']			= $model_order->outSnOrder();

		$order_array['invoice']			= '';	

		$order_array['evaluation_status']= 0;

		$order_array['order_type'] = 0;

		$order_array['order_message']	= trim($_POST['order_message']);

        $order_array['group_id'] = $group_id;

        $order_array['group_count'] = $quantity;

        $order_array['shipping_fee']	= 0; 

		$order_id	= $model_order->addOrder($order_array);



		

        $order_goods_array	= array();

        $order_goods_array['order_id']		= $order_id;

        $order_goods_array['goods_id']		= $groupbuy_info['goods_id'];

				$order_goods_array['stores_id']		= $groupbuy_info['store_id'];

        $order_goods_array['goods_name']	= $groupbuy_info['goods_name'];

        $order_goods_array['spec_id']		= $spec_id;

        $order_goods_array['spec_info']		= $this->get_goods_spec_text($spec_info);

        $order_goods_array['goods_price']	= $groupbuy_info['groupbuy_price'];

        $order_goods_array['goods_num']		= $quantity;

        $order_goods_array['goods_image']	= $goods_info[0]['goods_image'];

        $model_order->addGoodsOrder($order_goods_array);

		

        Tpl::output('goods_name',$groupbuy_info['goods_name']);

        

     

        $model_goods->updateSpecStorageGoods(array('spec_goods_storage'=>array('value'=>$quantity,'sign'=>'decrease'),'spec_salenum'=>array('value'=>$quantity,'sign'=>'increase')),$spec_id);

        $model_goods->updateGoods(array('salenum'=>array('value'=>$quantity,'sign'=>'increase')),$groupbuy_info['goods_id']);



       

        $model_group = Model('goods_group');

        $model_group->update(array('def_quantity'=>array('value'=>$quantity,'sign'=>'increase'),'buyer_count'=>array('value'=>1,'sign'=>'increase')),array('group_id'=>$group_id));



		

		$order_amount = $groupbuy_info['groupbuy_price'] * $quantity;

        

      

		$address_options = intval($_POST['address_options']);

		if ($address_options <= 0){

			showMessage(Language::get('cart_step1_chooseaddress_error'),'index.php?act=show_groupbuy&op=groupbuy_detail&group_id='.$group_id,'','error');

		}

		$mode_address	= Model('address');

		$address_info	= $mode_address->getOneAddress($address_options);



		

        $address_array		= array();

        $address_array['order_id']		= $order_id;

        $address_array['true_name']		= $address_info['true_name'];

        $address_array['area_id']		= $address_info['area_id'];

        $address_array['area_info']		= $address_info['area_info'];

        $address_array['address']		= $address_info['address'];

        $address_array['zip_code']		= $address_info['zip_code'];

        $address_array['tel_phone']		= $address_info['tel_phone'];

        $address_array['mob_phone']		= $address_info['mob_phone'];

        $model_order->addAddressOrder($address_array);



	

		$order_sn		= $order_array['order_sn'];

		$order_array	= array();

		$order_array['goods_amount']	= $order_amount;

		$order_array['discount']		= 0;



        $order_array['voucher_id'] = 0;

        $order_array['voucher_price'] = 0;     

        $order_array['voucher_code'] ='';



	    $order_array['order_amount'] = ($order_amount+$total_ems); 

		$model_order->updateOrder($order_array,$order_id);



		

		$model_order->addLogOrder(10,$order_id);

		

		@header("Location: index.php?act=cart&op=order_pay&order_id=".$order_id);

		exit;

    }

}

