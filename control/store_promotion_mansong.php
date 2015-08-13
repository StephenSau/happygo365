<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_promotion_mansongControl extends BaseMemberStoreControl {

    const APPLY_STATE_NEW = 1;
    const APPLY_STATE_VERIFY = 2;
    const APPLY_STATE_CANCEL = 3;
    const APPLY_STATE_FAIL = 4;
    const QUOTA_STATE_ACTIVITY = 1;
    const QUOTA_STATE_CANCEL = 2;
    const QUOTA_STATE_EXPIRE = 3;
    const MANSONG_STATE_UNPUBLISHED = 1;
    const MANSONG_STATE_PUBLISHED = 2;
    const MANSONG_STATE_CANCEL = 3;
    const MANSONG_STATE_INVADITATION = 4;
    const MANSONG_STATE_END = 5;
    const SECONDS_OF_DAY = 86400;
    const SECONDS_OF_30DAY = 2592000;
    const LINK_APPLY_LIST = 'index.php?act=store_promotion_mansong&op=mansong_apply_list';
    const LINK_APPLY_ADD = 'index.php?act=store_promotion_mansong&op=mansong_quota_add';
    const LINK_MANSONG_LIST = 'index.php?act=store_promotion_mansong&op=mansong_list';
    const LINK_MANSONG_MANAGE = 'index.php?act=store_promotion_mansong&op=mansong_manage&mansong_id=';

    public function __construct() {

        parent::__construct() ;

        
        Language::read('member_layout,promotion_mansong');
        if (intval(C('gold_isuse')) !== 1 || intval(C('promotion_allow')) !== 1){
            showMessage(Language::get('promotion_unavailable'),'index.php?act=store','','error');
        }

    }

    public function indexOp() {
        $this->mansong_listOp();
    }

   
    public function mansong_listOp() {

        $model_mansong_quota = Model('p_mansong_quota');
        $current_mansong_quota = $model_mansong_quota->getCurrent($_SESSION['store_id']);
        $result = $this->check_current_mansong_quota($current_mansong_quota);
        Tpl::output('mansong_quota_flag',$result);

        if(!$result) {

            $apply_flag = FALSE;
            $model_mansong_apply = Model('p_mansong_apply');
            $apply_list = $model_mansong_apply->getList(array('store_id'=>$_SESSION['store_id'],'state'=>self::APPLY_STATE_NEW));
            if(!empty($apply_list)) {
                $apply_flag = TRUE;
                Tpl::output('mansong_apply',$apply_list[0]);
            }
            Tpl::output('mansong_apply_flag',$apply_flag);
        } else {
            Tpl::output('current_mansong_quota',$current_mansong_quota);

            $model_mansong = Model('p_mansong');

            $where['greater_than_end_time'] = time();
            $where['state'] = self::MANSONG_STATE_PUBLISHED;
            $where['store_id'] = $_SESSION['store_id'];
            $model_mansong->update(array('state'=>self::MANSONG_STATE_END),$where);

            $page = new Page();
            $page->setEachNum(10) ;
            $page->setStyle('admin'); 

            $param = array();
            $param['store_id'] = $_SESSION['store_id'];
            $param['mansong_name'] = trim($_GET['mansong_name']);
            $param['state'] = trim($_GET['state']);
            $param['order'] = 'state asc,mansong_id asc';
            $mansong_list = $model_mansong->getList($param,$page);
            Tpl::output('list',$mansong_list);

            $this->output_mansong_state_list();
            Tpl::output('state_cancel',self::MANSONG_STATE_CANCEL);

            Tpl::output('show_page',$page->show());
        }

        self::profile_menu('mansong_list');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_mansong.list');

    }

    
    public function mansong_addOp() {

        $model_mansong_quota = Model('p_mansong_quota');
        $current_mansong_quota = $model_mansong_quota->getCurrent($_SESSION['store_id']);
        $result = $this->check_current_mansong_quota($current_mansong_quota);
        if(!$result) {
            showMessage(Language::get('mansong_quota_current_error'),'','','error');
        }
        $start_time = $current_mansong_quota['start_time'];
        $end_time = $current_mansong_quota['end_time'];

        $model_mansong = Model('p_mansong');
        $mansong_last = $model_mansong->getLast(array('store_id'=>$_SESSION['store_id']),self::MANSONG_STATE_PUBLISHED);
        if(!empty($mansong_last)) {
            if(intval($mansong_last['end_time']) > intval($start_time)) {
                $start_time = $mansong_last['end_time'];
            }
        }
        Tpl::output('start_time',$start_time);
        Tpl::output('end_time',$end_time);

        self::profile_menu('mansong_add');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_mansong.add');

    }

    
    public function mansong_saveOp() {

        $model_mansong_quota = Model('p_mansong_quota');
        $current_mansong_quota = $model_mansong_quota->getCurrent($_SESSION['store_id']);
        $result = $this->check_current_mansong_quota($current_mansong_quota);
        if(!$result) {
            showDialog(Language::get('mansong_quota_current_error'));
        }

        $mansong_name = trim($_POST['mansong_name']);
        $start_time = strtotime($_POST['start_time']);
        $end_time = strtotime($_POST['end_time']) + self::SECONDS_OF_DAY - 1;
        $quota_start_time = intval($current_mansong_quota['start_time']);
        $quota_end_time = intval($current_mansong_quota['end_time']);
        if(empty($mansong_name)) {
            showDialog(Language::get('mansong_name_error'));
        }
        if($start_time >= $end_time) {
            showDialog(Language::get('greater_than_start_time'));
        }
        $model_mansong = Model('p_mansong');
        $mansong_last = $model_mansong->getLast(array('store_id'=>$_SESSION['store_id']),self::MANSONG_STATE_PUBLISHED);
        if(!empty($mansong_last)) {
            if(intval($mansong_last['end_time']) > $start_time) {
                $quota_start_time = intval($mansong_last['end_time']);
            }
        }
        if($start_time < $quota_start_time) {
            showDialog(sprintf(Language::get('mansong_add_start_time_explain'),date('Y-m-d',$current_mansong_quota['start_time'])));
        }
        if($end_time > $quota_end_time) {
            showDialog(sprintf(Language::get('mansong_add_end_time_explain'),date('Y-m-d',$current_mansong_quota['end_time'])));
        }

        $level_array = array();
        for($i=1,$j=0;$i<=3;$i++,$j++) {
            $price = intval($_POST['level'.$i.'_price']);
            if($price <= 0) {
                break;
            }
            $level_array[$j]['level'] = $i;
            $level_array[$j]['price'] = $price;
            if(!empty($_POST['level'.$i.'_shipping_free_flag'])) {
                $level_array[$j]['shipping_free'] = 1;
            }
            else {
                $level_array[$j]['shipping_free'] = 0;
            }
            if(!empty($_POST['level'.$i.'_discount_flag'])) {
                $level_array[$j]['discount'] = intval($_POST['level'.$i.'_discount']);
            }
            else {
                $level_array[$j]['discount'] = 0;
            }
            if(!empty($_POST['level'.$i.'_gift_flag'])) {
                $level_array[$j]['gift_name'] = trim($_POST['level'.$i.'_gift_name']);
                $level_array[$j]['gift_link'] = trim($_POST['level'.$i.'_gift_link']);
            }
            else {
                $level_array[$j]['gift_name'] = ''; 
                $level_array[$j]['gift_link'] = ''; 
            }
            if(empty($level_array[$j]['shipping_free']) && empty($level_array[$j]['discount']) && empty($level_array[$j]['gift_name'])) {
                showDialog(Language::get('param_error'));
            }

        }
        if(empty($level_array)) {
            showDialog(Language::get('param_error'));
        }

        $model_mansong = Model('p_mansong');
        $param = array();
        $param['mansong_name'] = $mansong_name;
        $param['start_time'] = $start_time;
        $param['end_time'] = $end_time;
        $param['store_id'] = $current_mansong_quota['store_id'];
        $param['store_name'] = $current_mansong_quota['store_name'];
        $param['member_id'] = $current_mansong_quota['member_id'];
        $param['member_name'] = $current_mansong_quota['member_name'];
        $param['quota_id'] = $current_mansong_quota['quota_id'];
        $param['state'] = self::MANSONG_STATE_PUBLISHED;
        $param['remark'] = trim($_POST['remark']);
        $result = $model_mansong->save($param);
        if($result) {
            $count = count($level_array);
            for($i=0;$i<$count;$i++) {
                $level_array[$i]['mansong_id'] = $result;
            }
            $model_mansong_rule = Model('p_mansong_rule');
            $result = $model_mansong_rule->save_array($level_array);
            
			
			$data_array = array();
			$data_array['mansong_name']		= $param['mansong_name'];
			$data_array['start_time']		= $param['start_time'];
			$data_array['end_time']			= $param['end_time'];
			$data_array['store_id']			= $_SESSION['store_id'];
			$this->storeAutoShare($data_array, 'mansong');
        }
        if($result) {
            showDialog(Language::get('mansong_add_success'),self::LINK_MANSONG_LIST,'succ');
        }
        else {
            showDialog(Language::get('mansong_add_fail'));
        }
    } 

  
    public function mansong_detailOp() {

        $mansong_id = intval($_GET['mansong_id']);
        $mansong_info = $this->get_mansong_info($mansong_id);
        Tpl::output('mansong_info',$mansong_info);

        $this->output_mansong_state_list();

        $model_mansong_rule = Model('p_mansong_rule');
        $param = array();
        $param['mansong_id'] = $mansong_id;
        $param['order'] = 'level asc';
        $rule_list = $model_mansong_rule->getList($param);
        Tpl::output('list',$rule_list);

        self::profile_menu('mansong_detail');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_mansong.detail');
    }

    
    public function mansong_cancelOp() {

        $mansong_id = intval($_GET['mansong_id']);
        $mansong_info = $this->get_mansong_info($mansong_id);

        $model_mansong= Model('p_mansong');
        $update = array();
        $update['state'] = self::MANSONG_STATE_CANCEL;
        $where = array();
        $where['mansong_id'] = $mansong_id;
        $model_mansong->update($update,$where);

        if($model_mansong->update($update,$where)) {
            showDialog(Language::get('mansong_cancel_success'),'index.php?act=store_promotion_mansong&op=mansong_list','succ');
        } else {
            showDialog(Language::get('mansong_cancel_fail'));
        }
    }
   
    private function check_current_mansong_quota($current_mansong_quota) {
        $result = TRUE;
        if(empty($current_mansong_quota)) {
            $result = FALSE;
        }
        return $result;
    }


    public function mansong_quota_listOp() {

        $model_mansong = Model('p_mansong_quota');

        $page = new Page();
        $page->setEachNum(10) ;
        $page->setStyle('admin'); 

        $where['greater_than_end_time'] = time();
        $where['state'] = self::QUOTA_STATE_ACTIVITY;
        $where['store_id'] = $_SESSION['store_id'];
        $model_mansong->update(array('state'=>self::QUOTA_STATE_EXPIRE),$where);
        
        $param = array();
        $param['store_id'] = $_SESSION['store_id'];
        $param['order'] = 'state asc,start_time asc';
        $mansong_list = $model_mansong->getList($param,$page);
        Tpl::output('list',$mansong_list);
        $this->output_mansong_quota_state_list();

        Tpl::output('show_page',$page->show());

        self::profile_menu('mansong_quota_list');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_mansong_quota.list');

    }


    public function mansong_apply_listOp() {

        $model_mansong = Model('p_mansong_apply');

        $page = new Page();
        $page->setEachNum(10) ;
        $page->setStyle('admin'); 
        
        $param = array();
        $param['store_id'] = $_SESSION['store_id'];
        $param['order'] = 'state asc,apply_date asc';
        $list = $model_mansong->getList($param,$page);
        Tpl::output('list',$list);

        $this->output_mansong_apply_state_list();

        Tpl::output('show_page',$page->show());

        self::profile_menu('mansong_list');				//脚部文章输出		$article = $this->_article();
        Tpl::showpage('store_promotion_mansong_apply.list');

    }

  
    public function mansong_quota_addOp() {

        self::profile_menu('mansong_quota_add');
        Tpl::showpage('store_promotion_mansong_quota.add');
    }

   
    public function mansong_quota_add_saveOp() {

        $mansong_quota_quantity = intval($_POST['mansong_quota_quantity']);

        if(empty($mansong_quota_quantity)) {
            showDialog(Language::get('mansong_quota_quantity_error'));
        }
        if($mansong_quota_quantity <= 0 || $mansong_quota_quantity > 12) {
            showDialog(Language::get('mansong_quota_quantity_error'));
        }

        $current_price = intval($GLOBALS['setting_config']['promotion_mansong_price']);

        $flag = TRUE;
        $model_member = Model('member');
        $member_info = $model_member->infoMember(array('member_id'=>$_SESSION['member_id']));
        if(empty($member_info)) {
            $flag = FALSE;
        }

        $apply_price = $mansong_quota_quantity * $current_price;
        if($apply_price > intval($member_info['member_goldnum'])) {
            $flag = FALSE;
        }

        if($flag) {

            $model_quota_apply = Model('p_mansong_apply');
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['apply_quantity'] = $mansong_quota_quantity;
            $param['apply_date'] = time();
            $param['state'] = 2;
            $result = $model_quota_apply->save($param);
            if($result) {
                $update_array = array();
                $update_array['member_goldnum'] = array('sign'=>'decrease','value'=>$apply_price);
                $update_array['member_goldnumminus'] = array('sign'=>'increase','value'=>$apply_price);
                $result = $model_member->updateMember($update_array,$_SESSION['member_id']);

                $model_gold_log = Model('gold_log');
                $param = array();
                $param['glog_memberid'] = $_SESSION['member_id'];
                $param['glog_membername'] = $_SESSION['member_name'];
                $param['glog_storeid'] = $_SESSION['store_id'];
                $param['glog_storename'] = $_SESSION['store_name'];
                $param['glog_goldnum'] = $apply_price;
                $param['glog_method'] = 2;
                $param['glog_addtime'] = time();
                $param['glog_desc'] = sprintf(Language::get('mansong_apply_verify_success_glog_desc'),$mansong_quota_quantity,$apply_price,$apply_price);
                $param['glog_stage'] = 'mansong';
                $model_gold_log->add($param);

                $model_mansong_quota = Model('p_mansong_quota');

                $param = array();
                $param['store_id'] = $_SESSION['store_id'];
                $param['order'] = 'end_time desc';
                $param['state'] = self::QUOTA_STATE_ACTIVITY;
                $param['limit'] = 1;
                $last_mansong_quota = $model_mansong_quota->getList($param);

                $start_time = time(); 
                if(!empty($last_mansong_quota)) {
                    $last_end_time = intval($last_mansong_quota[0]['end_time']);
                    if($last_end_time > $start_time) {
                        $start_time = $last_end_time + 1;
                    }
                }

                $param = array();
                $param['member_id'] = $_SESSION['member_id'];
                $param['member_name'] = $_SESSION['member_name'];
                $param['store_id'] = $_SESSION['store_id'];
                $param['store_name'] = $_SESSION['store_name'];
                $param['state'] = self::QUOTA_STATE_ACTIVITY;
                $param['apply_id'] = $mansong_apply['apply_id'];
                $apply_quantity = $mansong_quota_quantity;
                $param['start_time'] = $start_time;
                $param['end_time'] = $start_time + (self::SECONDS_OF_30DAY * $apply_quantity) - 1;
                $model_mansong_quota->save($param);

                showDialog(Language::get('mansong_quota_add_success'),self::LINK_MANSONG_LIST,'succ');
            }
            else {
                showDialog(Language::get('mansong_quota_add_fail'),self::LINK_MANSONG_LIST);
            }
        }
        else {
            showDialog(Language::get('mansong_quota_add_fail_nogold'),self::LINK_MANSONG_LIST);
        }

    }

    
    private function get_mansong_info($mansong_id) {
        if($mansong_id === 0) {
            showMessage(Language::get('param_error'),'','','error');
        }

        $model_mansong = Model('p_mansong');
        $mansong_info = $model_mansong->getOne($mansong_id);
        if(empty($mansong_info)) {
            showMessage(Language::get('param_error'),'','','error');
        }
        if(intval($mansong_info['store_id']) !== intval($_SESSION['store_id'])) {
            showMessage(Language::get('param_error'),'','','error');
        }
        return $mansong_info;
    }

   
    private function output_mansong_apply_state_list() {
        $state_list = array(
            0 => Language::get('all_state'),
            self::APPLY_STATE_NEW=> Language::get('state_new'),
            self::APPLY_STATE_VERIFY=> Language::get('state_verify'),
            self::APPLY_STATE_CANCEL=> Language::get('state_cancel'),
            self::APPLY_STATE_FAIL=> Language::get('state_verify_fail')
    );
        Tpl::output('apply_state_list',$state_list);
    }

    
    private function output_mansong_quota_state_list() {
        $state_list = array(
            0 => Language::get('all_state'),
            self::QUOTA_STATE_ACTIVITY=> Language::get('nc_normal'),
            self::QUOTA_STATE_CANCEL=> Language::get('nc_cancel'),
            self::QUOTA_STATE_EXPIRE=> Language::get('nc_end')
    );
        Tpl::output('mansong_quota_state_list',$state_list);
    }

   
    private function output_mansong_state_list() {
        $state_list = array(
            0 => Language::get('all_state'),
            self::MANSONG_STATE_PUBLISHED => Language::get('mansong_state_published'),
            self::MANSONG_STATE_CANCEL => Language::get('nc_cancel'),
            self::MANSONG_STATE_INVADITATION => Language::get('nc_invalidation'),
            self::MANSONG_STATE_END => Language::get('nc_end')
    );
        Tpl::output('state_list',$state_list);
    }

    
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'mansong_list','menu_name'=>Language::get('promotion_active_list'),'menu_url'=>'index.php?act=store_promotion_mansong&op=mansong_list'),
            2=>array('menu_key'=>'mansong_quota_list','menu_name'=>Language::get('promotion_quota_list'),'menu_url'=>'index.php?act=store_promotion_mansong&op=mansong_quota_list'),
            3=>array('menu_key'=>'mansong_add','menu_name'=>Language::get('promotion_join_active'),'menu_url'=>'index.php?act=store_promotion_mansong&op=mansong_add'),
            4=>array('menu_key'=>'mansong_quota_add','menu_name'=>Language::get('promotion_buy_product'),'menu_url'=>'index.php?act=store_promotion_mansong&op=mansong_quota_add'),
            5=>array('menu_key'=>'mansong_detail','menu_name'=>Language::get('mansong_active_content'),'menu_url'=>'index.php?act=store_promotion_mansong&op=mansong_detail&mansong_id='.$_GET['mansong_id']),
            6=>array('menu_key'=>'choose_goods','menu_name'=>Language::get('promotion_add_goods'),'menu_url'=>'index.php?act=store_promotion_xianshi&op=choose_goods&xianshi_id=='.$_GET['xianshi_id']),
        );
        switch (strtolower($_GET['op'])){
        	case 'mansong_list':
        	case 'mansong_quota_list':
        		unset($menu_array[3]);
        		unset($menu_array[4]);
        		unset($menu_array[5]);
        		unset($menu_array[6]);
        		break; 
        	case 'mansong_add':
        		unset($menu_array[4]);
        		unset($menu_array[5]);
        		unset($menu_array[6]);
        		break;  
        	case 'mansong_quota_add':
        		unset($menu_array[3]);
        		unset($menu_array[5]);
        		unset($menu_array[6]);
        		break;
        	case 'mansong_detail':
        		unset($menu_array[3]);
        		unset($menu_array[4]);       		
        		unset($menu_array[6]);       		
        		break;
        	case 'choose_goods':
        		unset($menu_array[3]);
        		unset($menu_array[4]);       		
        		unset($menu_array[5]);       		
        		break;        		
        	default:
        		unset($menu_array[3]);
        		unset($menu_array[4]);
        		unset($menu_array[5]);
        		unset($menu_array[6]);
        		break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
        Tpl::output('menu_sign','mansong');
		Tpl::output('menu_sign_url','index.php?act=store_promotion_mansong&op=mansong_list');
        Tpl::output('menu_sign1','mansong');
    }		//S脚部文章输出	private function _article() {		if (file_exists(BasePath.'/cache/index/article.php')){			include(BasePath.'/cache/index/article.php');			Tpl::output('show_article',$show_article);			Tpl::output('article_list',$article_list);			return ;				}		$model_article_class	= Model('article_class');		$model_article	= Model('article');		$show_article = array();		$article_list	= array();		$notice_class	= array('notice','store','about');		$code_array	= array('member','store','payment','sold','service','about');		$notice_limit	= 5;		$faq_limit	= 5;		$class_condition	= array();		$class_condition['home_index'] = 'home_index';		$class_condition['order'] = 'ac_sort asc';		$article_class	= $model_article_class->getClassList($class_condition);		$class_list	= array();		if(!empty($article_class) && is_array($article_class)){			foreach ($article_class as $key => $val){				$ac_code = $val['ac_code'];				$ac_id = $val['ac_id'];				$val['list']	= array();				$class_list[$ac_id]	= $val;			}		}				$condition	= array();		$condition['article_show'] = '1';		$condition['home_index'] = 'home_index';		$condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article.article_content,article_class.ac_name,article_class.ac_parent_id';		$condition['order'] = 'article_sort desc,article_time desc';		$condition['limit'] = '300';		$article_array	= $model_article->getJoinList($condition);		if(!empty($article_array) && is_array($article_array)){			foreach ($article_array as $key => $val){				$ac_id = $val['ac_id'];				$ac_parent_id = $val['ac_parent_id'];				if($ac_parent_id == 0) {					$class_list[$ac_id]['list'][] = $val;				} else {					$class_list[$ac_parent_id]['list'][] = $val;				}			}		}		if(!empty($class_list) && is_array($class_list)){			foreach ($class_list as $key => $val){				$ac_code = $val['ac_code'];				if(in_array($ac_code,$notice_class)) {					$list = $val['list'];					array_splice($list, $notice_limit);					$val['list'] = $list;					$show_article[$ac_code] = $val;				}				if (in_array($ac_code,$code_array)){					$list = $val['list'];					$val['class']['ac_name']	= $val['ac_name'];					array_splice($list, $faq_limit);					$val['list'] = $list;					$article_list[] = $val;				}			}		}		$string = "<?php\n\$show_article=".var_export($show_article,true).";\n";		$string .= "\$article_list=".var_export($article_list,true).";\n?>";		file_put_contents(BasePath.'/cache/index/article.php',compress_code($string));		Tpl::output('show_article',$show_article);		Tpl::output('article_list',$article_list);	}

}
