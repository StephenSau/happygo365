<?php

defined('haipinlegou') or exit('Access Invalid!');
class member_complainControl extends BaseMemberControl{

    const STATE_NEW = 10;
    const STATE_APPEAL = 20;
    const STATE_TALK = 30;
    const STATE_HANDLE = 40;
    const STATE_FINISH = 99;
    const STATE_UNACTIVE = 1;
    const STATE_ACTIVE = 2;
    const COMPLAIN_TYPE_BUYER = 1;
    const COMPLAIN_TYPE_SELLER = 2;

    public function __construct() {

        parent::__construct() ;
        Language::read('member_layout,member_complain');

    }

    
    public function indexOp() {

        $this->complain_accuser_listOp();
    }

    //S脚部内容显示
    private function _article() {

        if (file_exists(BasePath.'/cache/index/article.php')){
            include(BasePath.'/cache/index/article.php');
            Tpl::output('show_article',$show_article);
            Tpl::output('article_list',$article_list);
            return ;        
        }
        $model_article_class    = Model('article_class');
        $model_article  = Model('article');
        $show_article = array();
        $article_list   = array();
        $notice_class   = array('notice','store','about');
        $code_array = array('member','store','payment','sold','service','about');
        $notice_limit   = 5;
        $faq_limit  = 5;

        $class_condition    = array();
        $class_condition['home_index'] = 'home_index';
        $class_condition['order'] = 'ac_sort asc';
        $article_class  = $model_article_class->getClassList($class_condition);
        $class_list = array();
        if(!empty($article_class) && is_array($article_class)){
            foreach ($article_class as $key => $val){
                $ac_code = $val['ac_code'];
                $ac_id = $val['ac_id'];
                $val['list']    = array();
                $class_list[$ac_id] = $val;
            }
        }
        
        $condition  = array();
        $condition['article_show'] = '1';
        $condition['home_index'] = 'home_index';
        $condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article_class.ac_name,article_class.ac_parent_id';
        $condition['order'] = 'article_sort desc,article_time desc';
        $condition['limit'] = '300';
        $article_array  = $model_article->getJoinList($condition);
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
                    $val['class']['ac_name']    = $val['ac_name'];
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

    
    public function complain_accuser_listOp() {

        $keyword = array();
        $keyword['condition'] = 'accuser_id';
        $keyword['op'] = 'complain_accuser_list';
        $keyword['flag'] = 'member_complain';

        switch(intval($_GET['select_complain_state'])) {
        case 1:
            $keyword['state'] = 'progressing';
            break;
        case 2:
            $keyword['state'] = 'finish';
            break;
        default :
            $keyword['state'] = '';
        }

        $this->complain_listOp($keyword);
    }

   
    public function complain_accused_listOp() {

        $keyword = array();
        $keyword['condition'] = 'accused_id';
        $keyword['op'] = 'complain_accused_list';
        $keyword['flag'] = 'store_complain';

        switch(intval($_GET['select_complain_state'])) {
        case 1:
            $keyword['state'] = 'accused_progressing';
            break;
        case 2:
            $keyword['state'] = 'accused_finish';
            break;
        default :
            $keyword['state'] = 'accused_all';
        }

        $this->complain_listOp($keyword);
    }
   
    private function complain_listOp($keyword) {

        
        $page = new Page() ;
        $page->setEachNum(10);
        $page->setStyle('admin') ;	

       	
        $model_complain = Model('complain') ;
        $condition = array();
        $condition['order']        = 'complain_state asc,complain_id desc';
        $condition[$keyword['condition']] = $_SESSION['member_id'];
        if(!empty($keyword['state'])) {
            $condition[$keyword['state']] = 'true';
        }
        $port_list = $model_complain->getComplain($condition, $page) ;
				$this->get_member_info();
        $this->profile_menu($keyword['op']);
        //S脚部文章输出
        $list=$this->_article();
        //E脚部文章输出
        Tpl::output('port_list', $port_list) ;
        Tpl::output('op', $keyword['op']) ;
        Tpl::output('show_page', $page->show()) ;
        Tpl::output('menu_sign','complain');
        Tpl::output('menu_sign_url','index.php?act=member_complain');
        Tpl::output('menu_sign1',$keyword['flag']);
        Tpl::showpage('complain.list');
    }

    
    public function complain_submitOp() {

        if(empty($_GET['complain_id'])) {
            $this->complain_submit_new();
        }else {
            $this->complain_submit_process();
        }		

    }

    
    private function complain_submit_new() {

        $order_id = intval($_GET['order_id']);
        $this->check_complain_exist($order_id,$_SESSION['member_id']);

        $order_info = $this->get_order_info($order_id);
        $complain_time_limit = intval($GLOBALS['setting_config']['complain_time_limit']);

        if(!empty($order_info['finnshed_time'])) {
            if((intval($order_info['finnshed_time'])+$complain_time_limit) < time()) {
                showMessage(Language::get('complain_time_limit'),'','html','error');
            }
        }

        
        $order_goods_list = $this->get_goods_list($order_id);

        $complain_info = array();
        $complain_info['complain_state_text'] = $this->get_complain_state_text(self::STATE_NEW);
        if($_SESSION['member_id'] == $order_info['buyer_id']) {
            $complain_info['complain_subject_type'] = self::COMPLAIN_TYPE_BUYER;
            $complain_info['complain_subject_type_text'] = $this->get_complain_subject_type_text(self::COMPLAIN_TYPE_BUYER); 
            $complain_info['complain_accuser_name'] = $_SESSION['member_name'];
            $complain_info['member_status'] = 'accuser';
            $complain_info['complain_type'] = 1;
            if(intval($order_info['order_state']) < 20) {
                showMessage(Language::get('para_error'),'','html','error');
            }
        }
        else {
            $complain_info['complain_subject_type'] = self::COMPLAIN_TYPE_SELLER;
            $complain_info['complain_subject_type_text'] = $this->get_complain_subject_type_text(self::COMPLAIN_TYPE_SELLER); 
            $complain_info['complain_accuser_name'] = $order_info['store_name'];
            $complain_info['member_status'] = 'accuser';
            $complain_info['complain_type'] = 2; 
            if(intval($order_info['order_state']) < 30) {
                showMessage(Language::get('para_error'),'','html','error');
            }
        }


        $model_complain_subject = Model('complain_subject');
        $param = array();
        $param['complain_subject_type'] = $complain_info['complain_subject_type'];
        $complain_subject_list = $model_complain_subject->getActiveComplainSubject($param);
        if(empty($complain_subject_list)) {
            showMessage(Language::get('complain_subject_error'),'','html','error');
        }
		$this->get_member_info();				//脚部文章输出		$list = $this->_article();
        Tpl::output('order_info',$order_info);
        Tpl::output('order_goods_list',$order_goods_list);
        Tpl::output('complain_info',$complain_info);
        Tpl::output('subject_list',$complain_subject_list);
		Tpl::output('left_show','order_view');
        Tpl::showpage('complain.submit');

    }

   
    private function complain_submit_process() {
        $complain_id = intval($_GET['complain_id']);

        $complain_info = $this->get_complain_info($complain_id);

        $order_info = $this->get_order_info($complain_info['order_id']);

        $complain_goods_list = $this->get_complain_goods_list($complain_id);

        $page_name = '';

        switch(intval($complain_info['complain_state'])) {
        case self::STATE_NEW:
            $page_name = 'complain.info'; 
            break;
        case self::STATE_APPEAL:
            $page_name = 'complain.appeal'; 
            break;
        case self::STATE_TALK: 
            $page_name = 'complain.talk'; 
            break;
        case self::STATE_HANDLE: 
            $page_name = 'complain.talk'; 
            break;
        case self::STATE_FINISH: 
            $page_name = 'complain.info'; 
            break;
        default:
            showMessage(Language::get('para_error'),'','html','error');
        }
		

		//S脚部内容显示
		$list = $this->_article();
		//E脚部内容显示
		
		
		$this->get_member_info();
        Tpl::output('order_info',$order_info);
        Tpl::output('complain_info',$complain_info);
        Tpl::output('complain_goods_list',$complain_goods_list);
        Tpl::output('left_show','order_view');
        Tpl::showpage($page_name);
    }

    
    public function complain_saveOp() {

        $input = array();
        $input['order_id'] = intval($_POST['input_order_id']);

        $this->check_complain_exist($input['order_id'],$_SESSION['member_id']);

        list($input['complain_subject_id'],$input['complain_subject_content']) = explode(',',trim($_POST['input_complain_subject']));
        $input['complain_content'] = trim($_POST['input_complain_content']);

        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input['complain_content'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('complain_content_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
        	showValidateError($error);
        }
        $checked_goods = $_POST['input_goods_check'];
        $checked_goods_problem = $_POST['input_goods_problem'];
        $checked_goods_list = $this->get_checked_goods_list($checked_goods,$checked_goods_problem);

        $input['order_goods_count'] = count($checked_goods_problem);
        $input['complain_goods_count'] = count($checked_goods);

        $order_info = $this->get_order_info($input['order_id']);

        if($_SESSION['member_id'] == $order_info['buyer_id']) {
            $input['accuser_id'] = $order_info['buyer_id'];
            $input['accuser_name'] = $order_info['buyer_name'];
            $input['accused_id'] = $order_info['seller_id'];
            $input['accused_name'] = $order_info['store_name'];
            $input['complain_type'] = self::COMPLAIN_TYPE_BUYER;
        }
        else {
            $input['accuser_id'] = $order_info['seller_id'];
            $input['accuser_name'] = $order_info['store_name'];
            $input['accused_id'] = $order_info['buyer_id'];
            $input['accused_name'] = $order_info['buyer_name'];
            $input['complain_type'] = self::COMPLAIN_TYPE_SELLER;
        }


        $complain_pic = array();
        $complain_pic[1] = 'input_complain_pic1';
        $complain_pic[2] = 'input_complain_pic2';
        $complain_pic[3] = 'input_complain_pic3';
        $pic_name = $this->upload_pic($complain_pic);
        $input['complain_pic1'] = $pic_name[1]; 
        $input['complain_pic2'] = $pic_name[2]; 
        $input['complain_pic3'] = $pic_name[3]; 

        $input['complain_datetime'] = time();
        $input['complain_state'] = self::STATE_NEW;
        $input['complain_active'] = self::STATE_UNACTIVE;

        $model_complain = Model('complain');
        $complain_id = $model_complain->saveComplain($input);

        $model_complain_goods = Model('complain_goods');
        foreach($checked_goods_list as $checked_goods) {

            $checked_goods_info = $this->get_order_goods_info($checked_goods['rec_id']);
            $input_checked_goods['complain_id'] = $complain_id;
            $input_checked_goods['goods_id'] = $checked_goods_info['goods_id'];
            $input_checked_goods['goods_name'] = $checked_goods_info['goods_name'];
            $input_checked_goods['spec_id'] = $checked_goods_info['spec_id'];
            $input_checked_goods['spec_info'] = $checked_goods_info['spec_info'];
            $input_checked_goods['goods_price'] = $checked_goods_info['goods_price'];
            $input_checked_goods['goods_num'] = $checked_goods_info['goods_num'];
            $input_checked_goods['goods_image'] = $checked_goods_info['goods_image'];
            $input_checked_goods['evaluation'] = $checked_goods_info['evaluation'];
            $input_checked_goods['comment'] = $checked_goods_info['comment'];
            $input_checked_goods['complain_message'] = $checked_goods['complain_message'];

            $model_complain_goods->saveComplainGoods($input_checked_goods);
        }
		showDialog(Language::get('complain_submit_success'),'index.php?act=member_complain','succ');

    }

    
    public function complain_add_picOp() {
    	$complain_id = intval($_GET['complain_id']);
      $complain_info = $this->get_complain_info($complain_id);
    	if (chksubmit()){
        $where_array = array();
        $where_array['complain_id'] = $complain_id;
        
        $input = array();
        $complain_pic = array();
        $complain_pic[1] = 'input_complain_pic1';
        $complain_pic[2] = 'input_complain_pic2';
        $complain_pic[3] = 'input_complain_pic3';
        $pic_name = $this->upload_pic($complain_pic);
        $input['complain_pic1'] = $pic_name[1]; 
        $input['complain_pic2'] = $pic_name[2]; 
        $input['complain_pic3'] = $pic_name[3]; 
        $model_complain = Model('complain');
        $model_complain->updateComplain($input,$where_array);
        showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
    	}
    	Tpl::output('complain_info',$complain_info);
      Tpl::showpage('complain_add_pic','null_layout');
    }

   
    public function appeal_saveOp() {

        $complain_id = intval($_POST['input_complain_id']);

        $complain_info = $this->get_complain_info($complain_id);

        if($complain_info['member_status'] !== 'accused') {
            showMessage(Language::get('para_error'),'','html','error');
        }

        if(intval($complain_info['complain_state']) !== self::STATE_APPEAL) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $input = array();
        $input['appeal_message'] = trim($_POST['input_appeal_message']);

        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input['appeal_message'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('appeal_message_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
        	showValidateError($error);
        }

        $appeal_pic = array();
        $appeal_pic[1] = 'input_appeal_pic1';
        $appeal_pic[2] = 'input_appeal_pic2';
        $appeal_pic[3] = 'input_appeal_pic3';
        $pic_name = $this->upload_pic($appeal_pic);
        $input['appeal_pic1'] = $pic_name[1]; 
        $input['appeal_pic2'] = $pic_name[2]; 
        $input['appeal_pic3'] = $pic_name[3]; 

        $input['appeal_datetime'] = time();
        $input['complain_state'] = self::STATE_TALK;

        $where_array = array();
        $where_array['complain_id'] = $complain_id;

        $model_complain = Model('complain');
        $complain_id = $model_complain->updateComplain($input,$where_array);

        $this->send_message('appeal',$complain_info);

        showDialog(Language::get('appeal_submit_success'),'index.php?act=member_complain','succ');

    }

  
    public function complain_cancelOp() {

        $complain_id = intval($_GET['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);

        if($complain_info['member_status'] !== 'accuser') {
            showMessage(Language::get('para_error'),'','html','error');
        }

        if(intval($complain_info['complain_state']) === 10) {
            $pics = array();
            if(!empty($complain_info['complain_pic1'])) $pics[] = $complain_info['complain_pic1'];
            if(!empty($complain_info['complain_pic2'])) $pics[] = $complain_info['complain_pic2'];
            if(!empty($complain_info['complain_pic3'])) $pics[] = $complain_info['complain_pic3'];
            $this->drop_complain($complain_id,$pics);
            showDialog(Language::get('complain_cancel_success'),'reload','succ');
        }
        else {
        	showDialog(Language::get('complain_cancel_fail'),'','error');
        }
    }

   
    private function drop_complain($complain_id,$complain_pics) {

        if(!empty($complain_pics)) {
            foreach($complain_pics as $pic) {
                $this->complain_delete_pic($pic);
            }
        }
        $model_complain = Model('complain');
        $model_complain->dropComplain(array('complain_id' => $complain_id));
    }

   
    public function apply_handleOp() {

        $complain_id = intval($_POST['input_complain_id']);

       
        $complain_info = $this->get_complain_info($complain_id);

        $complain_state = intval($complain_info['complain_state']);

        if($complain_state < self::STATE_TALK || $complain_state === 99) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $update_array = array();
        $update_array['complain_state'] = self::STATE_HANDLE;

        $where_array = array();
        $where_array['complain_id'] = $complain_id;

        $model_complain = Model('complain');
        $complain_id = $model_complain->updateComplain($update_array,$where_array);

        showMessage(Language::get('handle_submit_success'),'index.php?act=member_complain');

    }

    
    public function get_complain_talkOp() {

        $complain_id = intval($_POST['complain_id']);
        $complain_info = $this->get_complain_info($complain_id);
        $complain_talk_list = $this->get_talk_list($complain_id);
        $talk_list = array();
        $i=0;
        foreach($complain_talk_list as $talk) {
            $talk_list[$i]['css'] = $talk['talk_member_type'];
            $talk_list[$i]['talk'] = date("Y-m-d H:i:s",$talk['talk_datetime']);
            switch($talk['talk_member_type']){
            case 'accuser':
                $talk_list[$i]['talk'] .= Language::get('complain_accuser');
                break;
            case 'accused':
                $talk_list[$i]['talk'] .= Language::get('complain_accused');
                break;
            case 'admin':
                $talk_list[$i]['talk'] .= Language::get('complain_admin');
                break;
            default:
                $talk_list[$i]['talk'] .= Language::get('complain_unknow');
            }
            if(intval($talk['talk_state']) === 2) {
                $talk['talk_content'] = Language::get('talk_forbit_message');
            }
            $talk_list[$i]['talk'].= '('.$talk['talk_member_name'].')'.Language::get('complain_text_say').':'.$talk['talk_content'];
            $i++;
        }
        if (strtoupper(CHARSET) == 'GBK') {
            $talk_list = Language::getUTF8($talk_list);
        }
        echo json_encode($talk_list);
    }

    
    public function publish_complain_talkOp() {

        $complain_id = intval($_POST['complain_id']);
        $complain_talk = trim($_POST['complain_talk']);
        $talk_len = strlen($complain_talk);
        if($talk_len > 0 && $talk_len < 255) {
            $complain_info = $this->get_complain_info($complain_id);
            $complain_state = intval($complain_info['complain_state']);
            if($complain_state > self::STATE_APPEAL && $complain_state < self::STATE_FINISH) {
                $model_complain_talk = Model('complain_talk');
                $param = array();
                $param['complain_id'] = $complain_id;
                if($complain_info['member_status'] === 'accuser') {
                    $param['talk_member_id'] = $complain_info['accuser_id'];
                    $param['talk_member_name'] = $complain_info['accuser_name'];
                }
                else {
                    $param['talk_member_id'] = $complain_info['accused_id'];
                    $param['talk_member_name'] = $complain_info['accused_name'];
                }
                $param['talk_member_type'] = $complain_info['member_status'];
                if (strtoupper(CHARSET) == 'GBK') {
                    $complain_talk = Language::getGBK($complain_talk);
                }
                $param['talk_content'] = $complain_talk;
                $param['talk_state'] =1;
                $param['talk_admin'] = 0;
                $param['talk_datetime'] = time(); 
                if($model_complain_talk->saveComplainTalk($param)) {
                    echo json_encode('success');
                }
                else {
                    echo json_encode('error2');
                }
            }
            else {
                echo json_encode('error');
            }
        }
        else {
            echo json_encode('error1');
        }
    }

    
    private function get_order_info($order_id) {

        if(empty($order_id)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $model_order = Model('order');
        $order_info = $model_order->getOrderById($order_id,$type='simple');

        if(empty($order_info)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        if($order_info['buyer_id'] != $_SESSION['member_id'] && $order_info['seller_id'] != $_SESSION['member_id']) {
            showMessage(Language::get('para_error'),'','html','error');
        }
        $order_info['order_state_text'] = $this->get_order_state_text($order_info['order_state']);
        return $order_info;
    }

    
    private function get_complain_info($complain_id) {

        if(empty($complain_id)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $model_complain = Model('complain');
        $complain_info = $model_complain->getoneComplain($complain_id);

        if(empty($complain_info)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        if($complain_info['accuser_id'] != $_SESSION['member_id'] && $complain_info['accused_id'] != $_SESSION['member_id']) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        if($complain_info['accuser_id'] == $_SESSION['member_id']) {
            $complain_info['member_status'] = 'accuser';
        }
        else {
            $complain_info['member_status'] = 'accused';
        }
        $complain_info['complain_state_text'] = $this->get_complain_state_text($complain_info['complain_state']);
        if(intval($complain_info['complain_type']) === 1) {
            $complain_info['complain_type_text'] = Language::get('complain_type_buyer');
        }
        else {
            $complain_info['complain_type_text'] = Language::get('complain_type_seller');
        }
        return $complain_info;
    }


    
    private function get_goods_list($order_id) {

        $model_order_goods = Model('order_goods');
        $param = array();
        $param['order_id'] = $order_id;
        $order_goods_list = $model_order_goods->getOrderGoodsList($param);
        return $order_goods_list;
    }

    
    private function get_talk_list($complain_id) {

        $model_complain_talk = Model('complain_talk');
        $param = array();
        $param['complain_id'] = $complain_id;
        $talk_list = $model_complain_talk->getComplainTalk($param);
        return $talk_list;
    }


    
    private function get_complain_goods_list($complain_id) {

        $model_complain_goods = Model('complain_goods');
        $param = array();
        $param['complain_id'] = $complain_id;
        $complain_goods_list = $model_complain_goods->getComplainGoods($param);
        return $complain_goods_list;
    }



    
    private function get_checked_goods_list($checked_goods,$checked_goods_problem) {

        if(empty($checked_goods)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $checked_goods_list = array();
        $i=0;
        foreach($checked_goods as $k => $v) {
            $checked_goods_list[$i]['rec_id'] = trim($v);
            $checked_goods_list[$i]['complain_message'] = trim($checked_goods_problem[$k]);
            $i++;
        }
        return $checked_goods_list;
    }


    
    private function get_order_goods_info($rec_id) {

        $model_order_goods = Model('order_goods');
        return $model_order_goods->getoneOrderGoods($rec_id);
    }


   
    private function check_complain_exist($order_id,$accuser_id) {

        $model_complain = Model('complain');
        $param = array();
        $param['order_id'] = $order_id;
        $param['accuser_id'] = $accuser_id;
        $param['progressing'] = 'ture';
        if($model_complain->isExist($param)) {
            showMessage(Language::get('complain_repeat'),'','html','error');
        }

    }


    
    private function get_complain_state_text($complain_state) {

        switch(intval($complain_state)) {
        case self::STATE_NEW:
            return Language::get('complain_state_new');
            break;
        case self::STATE_APPEAL:
            return Language::get('complain_state_appeal');
            break;
        case self::STATE_TALK: 
            return Language::get('complain_state_talk');
            break;
        case self::STATE_HANDLE: 
            return Language::get('complain_state_handle');
            break;
        case self::STATE_FINISH: 
            return Language::get('complain_state_finish');
            break;
        default:
            showMessage(Language::get('para_error'),'','html','error');
        }

    }

    
    private function get_complain_subject_type_text($complain_subject_type) {

        switch(intval($complain_subject_type)) {
        case self::COMPLAIN_TYPE_BUYER:
            return Language::get('complain_type_buyer'); 
            break;
        case self::COMPLAIN_TYPE_SELLER:
            return Language::get('complain_type_seller'); 
            break;
        default:
            showMessage(Language::get('para_error'),'','html','error');
        }

    }


    
    private function get_order_state_text($order_state) {

        switch(intval($order_state)) {
        case 0:
            return Language::get('order_state_cancel');
            break;
        case 10:
            return Language::get('order_state_unpay');
            break;
        case 20:
            return Language::get('order_state_payed');
            break;
        case 30: 
            return Language::get('order_state_send');
            break;
        case 40: 
            return Language::get('order_state_receive');
            break;
        case 50: 
            return Language::get('order_state_commit');
            break;
        case 60: 
            return Language::get('order_state_verify');
            break;
        default:
            showMessage(Language::get('para_error'),'','html','error');
        }
    }


    private function upload_pic($complain_pic) {

        $pic_name = array();
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'complain'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        $count = 1;
        foreach($complain_pic as $pic) {
            if (!empty($_FILES[$pic]['name'])){
                $result = $upload->upfile($pic);
                if ($result){
                    $pic_name[$count] = $upload->file_name;
                    $upload->file_name = '';
                }
                else {
                    $pic_name[$count] = '';
                }
            }
            $count++;
        }
        return $pic_name;

    }

  
    private function complain_delete_pic($pic_name) {

        $pic = ATTACH_PATH.DS.'complain'.DS.$pic_name;
        if(file_exists($pic)) {
            @unlink($pic);
        }

    }


   
    private function get_pic_filename() {
        return date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
    }

   
    private function send_message($type,$complain_info) {

        $param = array();
        $param['from_member_id'] = 0;
        switch($type) {
        case 'appeal':            
            $param['member_id'] = $complain_info['accuser_id'];
            $param['to_member_name'] = $complain_info['accuser_name'];
            $param['message_type'] = '1';
            break;
        default:
            return false;
        }
        $param['msg_content'] .= Language::get('send_appeal_message').'<a href=index.php?act=store_complain&op=complain_submit&complain_id='.$complain_info['complain_id'].'>'.Language::get('click_to_see').'</a>';
        $model_message = Model('message');
        return $model_message->saveMessage($param);
    }

   
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'complain_accuser_list','menu_name'=>Language::get('complain_accuser_title'),'menu_url'=>'index.php?act=member_complain&op=complain_accuser_list'),
            2=>array('menu_key'=>'complain_accused_list','menu_name'=>Language::get('complain_accused_title'),'menu_url'=>'index.php?act=member_complain&op=complain_accused_list'),
            3=>array('menu_key'=>'complain_submit','menu_name'=>Language::get('complain_submit'),'menu_url'=>'#'),
        );
        if($menu_key!=='complain_submit') {
            unset($menu_array[3]);
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }	

}
