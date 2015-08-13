<?php

defined('haipinlegou') or exit('Access Invalid!');
class member_informControl extends BaseMemberControl{
	
	public function __construct() {
	
		parent::__construct() ;

		
		Language::read('member_layout,member_inform');

       
	}
    	
	
	public function indexOp() {

        $this->inform_listOp() ;
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

	
    public function inform_listOp() {

		
		$page = new Page() ;
		$page->setEachNum(10);
		$page->setStyle('admin') ;	
        
        	
		$model_inform = Model('inform') ;
        $condition = array();
        $condition['inform_state'] = intval($_GET['select_inform_state']);
        $condition['inform_member_id'] = $_SESSION['member_id'];
        $condition['order']        = 'inform_id desc';
		$report_list = $model_inform->getInform($condition, $page) ;
		//S脚部文章输出
        $list=$this->_article();
        //E脚部文章输出
		$this->get_member_info();
        $this->profile_menu('inform_list');
        Tpl::output('report_list', $report_list) ;
        Tpl::output('show_page', $page->show()) ;
		Tpl::output('menu_sign','myinform');
		Tpl::output('menu_sign_url','index.php?act=member_inform');
		Tpl::output('menu_sign1','member_inform');
        Tpl::showpage('member_inform.list');
    }

   
    public function inform_submitOp() {

        $this->check_member_allow_inform();

        $goods_id = intval($_GET['goods_id']);

        $goods_info = $this->get_goods_info_byid($goods_id);

        if(!empty($_SESSION['store_id'])) {
            if ($goods_info['store_id'] == $_SESSION['store_id']) {
                showMessage(Language::get('para_error'),'','html','error');
            }
        }


        $model_inform = Model('inform');
        if($model_inform->isProcessOfInform($goods_id)) {
           showMessage(Language::get('inform_handling'),'','html','error'); 
        }
        
        $model_inform_subject_type = Model('inform_subject_type');
        $inform_subject_type_list = $model_inform_subject_type->getActiveInformSubjectType();
        if(empty($inform_subject_type_list)) {
            showMessage(Language::get('inform_type_null'),'','html','error');
        }		//脚部文章输出		$article = $this->_article();

        Tpl::output('goods_info',$goods_info);
        Tpl::output('type_list',$inform_subject_type_list);
        Tpl::output('menu_sign','myinform');
        Tpl::output('menu_sign_url','index.php?act=member_inform');
        Tpl::output('menu_sign1','member_inform');
        Tpl::showpage('member_inform.submit');
    }

  
    public function inform_saveOp() {

        $this->check_member_allow_inform();

        $goods_id = intval($_POST['inform_goods_id']);

        $goods_info = $this->get_goods_info_byid($goods_id);

        if(!empty($_SESSION['store_id'])) {
            if ($goods_info['store_id'] == $_SESSION['store_id']) {
            	showDialog(Language::get('para_error'));
            }
        }

        $model_inform = Model('inform');
        if($model_inform->isProcessOfInform($goods_id)) {
           showDialog(Language::get('inform_handling')); 
        }
        $input = array();
        $input['inform_member_id'] = $_SESSION['member_id'];
        $input['inform_member_name'] = $_SESSION['member_name'];
        $input['inform_goods_id'] = $goods_id;
        $input['inform_goods_name'] = $goods_info['goods_name'];
        list($input['inform_subject_id'],$input['inform_subject_content']) = explode(",",trim($_POST['inform_subject']));
        $input['inform_content'] = trim($_POST['inform_content']);

        $inform_pic = array();
        $inform_pic[1] = 'inform_pic1';
        $inform_pic[2] = 'inform_pic2';
        $inform_pic[3] = 'inform_pic3';
        $pic_name = $this->inform_upload_pic($inform_pic);
        $input['inform_pic1'] = $pic_name[1]; 
        $input['inform_pic2'] = $pic_name[2]; 
        $input['inform_pic3'] = $pic_name[3]; 

        $input['inform_datetime'] = time();
        $input['inform_store_id'] = $goods_info['store_id'];
        $input['inform_state'] = 1;
        $input['inform_handle_message'] = '';
        $input['inform_handle_member_id'] = 0;
        $input['inform_handle_datetime'] = 1;
        
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input["inform_content"], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"100","message"=>Language::get('inform_content_null')),
            array("input"=>$input["inform_subject_content"], "require"=>"true", "validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('para_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
        	showValidateError($error);
        }

        if($model_inform->saveInform($input)) {
            showDialog(Language::get('inform_success'),'index.php?act=member_inform&op=inform_list','succ');
        }
        else {
            showDialog(Language::get('inform_fail'),'index.php?act=member_inform&op=inform_list','error');
        }
    }


   
    public function inform_cancelOp() {

        $inform_id = intval($_GET['inform_id']);
        $inform_info = $this->get_inform_info($inform_id);

        if(intval($inform_info['inform_state']) === 1) {
            $pics = array();
            if(!empty($inform_info['inform_pic1'])) $pics[] = $inform_info['inform_pic1'];
            if(!empty($inform_info['inform_pic2'])) $pics[] = $inform_info['inform_pic2'];
            if(!empty($inform_info['inform_pic3'])) $pics[] = $inform_info['inform_pic3'];
            $this->drop_inform($inform_id,$pics);
            showDialog(Language::get('inform_cancel_success'),'reload','succ');
        }
        else {
            showDialog(Language::get('inform_cancel_fail'),'','error');
        }
    }



    
    private function get_inform_info($inform_id) {

        if (empty($inform_id)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $model_inform = Model('inform');
        $inform_info = $model_inform->getoneInform($inform_id);

        if(empty($inform_info)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        if(intval($inform_info['inform_member_id']) !== intval($_SESSION['member_id'])) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        return $inform_info;
    
    }


   
    private function drop_inform($inform_id,$inform_pics) {
        
        $model_inform = Model('inform');
        if(!empty($inform_pics)) {
            foreach($inform_pics as $pic) {
                $this->inform_delete_pic($pic);
            }
        }
        $model_inform->dropInform(array('inform_id' => $inform_id));
    }

   
    private function get_goods_info_byid($goods_id) {
        
        if(empty($goods_id)) {
            showMessage(Language::get('para_error'),'','html','error');
        }

        $model_goods = Model('goods');
        $goods_condition = array();
        $goods_condition['goods_id'] = $goods_id;
        $goods_condition['goods_state'] = 0;
        $goods_info = $model_goods->getGoods($goods_condition);

        if(count($goods_info) !== 1) {
            showMessage(Language::get('goods_null'),'','html','error');
        }
        return $goods_info[0];
    }


    
    private function check_member_allow_inform() {
        
        $model_member = Model('member');
        if(!$model_member->isMemberAllowInform($_SESSION['member_id'])) {
            showMessage(Language::get('deny_inform'),'','html','error');
        }
    }
    
   
    private function inform_upload_pic($inform_pic) {

        $pic_name = array();
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'inform'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        $count = 1;
        foreach($inform_pic as $pic) {
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

   
    private function inform_delete_pic($pic_name) {

        $pic = ATTACH_PATH.DS.'inform'.DS.$pic_name;
        if(file_exists($pic)) {
            @unlink($pic);
        }
        
    }


    
    private function get_pic_filename() {
        return date('Ymd').substr(implode(NULL,array_map('ord',str_split(substr(uniqid(),7,13),1))) , -8 , 8);
    }
    

   
    public function get_subject_by_typeidOp() {
        
        $inform_subject_type_id = trim($_POST['typeid']);
        if(empty($inform_subject_type_id)) {
            echo '';
        }
        else {
          
            $model_inform_subject = Model('inform_subject') ;

            $condition = array();
            $condition['order'] = 'inform_subject_id asc';
            $condition['inform_subject_type_id'] = $inform_subject_type_id;
            $condition['inform_subject_state'] = 1;
            $inform_subject_list = $model_inform_subject->getInformSubject($condition,$page,'inform_subject_id,inform_subject_content') ;
            if (strtoupper(CHARSET) == 'GBK'){
                $inform_subject_list = Language::getUTF8($inform_subject_list);
		        }
            echo json_encode($inform_subject_list);

        }
    }



	private function profile_menu($menu_key='') {
		$menu_array = array(
			1=>array('menu_key'=>'inform_list','menu_name'=>Language::get('nc_myinform'),'menu_url'=>'index.php?act=member_inform&op=inform_list'),
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
    }	

}
