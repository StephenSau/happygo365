<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_informControl extends BaseMemberStoreControl{
	
	public function __construct() {
	
		parent::__construct() ;
		
		Language::read('member_layout,member_inform');

	}
    	

	public function indexOp() {

        $this->inform_listOp() ;
    }

	
    public function inform_listOp() {

		
		$page = new Page() ;
		$page->setEachNum(10);
		$page->setStyle('admin') ;	
        
        	
		$model_inform = Model('inform') ;
        $condition = array();
        $condition['inform_state'] = 2;
        $condition['inform_store_id'] = $_SESSION['store_id'];
        $condition['inform_handle_type'] = 3;
        $condition['order']        = 'inform_id desc';
		$inform_list = $model_inform->getInform($condition, $page) ;
        $this->profile_menu('inform_list');
		//S脚部文章输出
		$list = $this->_article();
		//E脚部文章输出
        Tpl::output('inform_list', $inform_list) ;
        Tpl::output('show_page', $page->show()) ;
		Tpl::output('menu_sign','store_inform');
		Tpl::output('menu_sign_url','index.php?act=store_inform');
		Tpl::output('menu_sign1','store_inform');
        Tpl::showpage('store_inform.list');
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

	
	private function profile_menu($menu_key='') {
		$menu_array = array(
			1=>array('menu_key'=>'inform_list','menu_name'=>Language::get('nc_store_inform'),'menu_url'=>'index.php?act=store_inform&op=inform_list'),
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
    }	

}
