<?php

defined('haipinlegou') or exit('Access Invalid!');
class member_consultControl extends BaseMemberControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_store_consult_index');
	}

	public function indexOp(){
		$this->my_consultOp();
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

	
	public function my_consultOp(){
		$consult	= Model('consult');
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$list_consult	= array();
		$search_array	= array();
		$search_array['type']		= $_GET['type'];
		$search_array['member_id']	= "{$_SESSION['member_id']}";
		$list_consult	= $consult->getConsultList($search_array,$page);
		Tpl::output('show_page',$page->show());
		Tpl::output('list_consult',$list_consult);
		$_GET['type']	= empty($_GET['type'])?'consult_list':$_GET['type'];
		$this->get_member_info();
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		self::profile_menu('my_consult',$_GET['type']);
		Tpl::output('menu_sign','consult');
		Tpl::output('menu_sign_url','index.php?act=member_consult&op=my_consult');
		Tpl::output('menu_sign1',$_GET['type']);
		Tpl::showpage('member_my_consult');
	}
	
	private function profile_menu($menu_type,$menu_key='',$array=array()) {		
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
			case 'my_consult':
				$menu_array	= array(
				1=>array('menu_key'=>'consult_list',	'menu_name'=>Language::get('nc_member_path_all_consult'),			'menu_url'=>'index.php?act=member_consult&op=my_consult'),
				2=>array('menu_key'=>'to_reply',	'menu_name'=>Language::get('nc_member_path_unreplied_consult'),			'menu_url'=>'index.php?act=member_consult&op=my_consult&type=to_reply'),
				3=>array('menu_key'=>'replied',	'menu_name'=>Language::get('nc_member_path_replied_consult'),			'menu_url'=>'index.php?act=member_consult&op=my_consult&type=replied'));
				break;
		}
		if(!empty($array)) {
			$menu_array[] = $array;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}