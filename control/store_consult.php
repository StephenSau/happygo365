<?php

defined('haipinlegou') or exit('Access Invalid!');
class store_consultControl extends BaseMemberStoreControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_store_consult_index');
	}

	
	public function indexOp(){
		$this->consult_listOp();
	}

	
	public function consult_listOp(){
		$consult	= Model('consult');
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$list_consult	= array();
		$search_array	= array();
		$search_array['type']		= $_GET['type'];
		$search_array['seller_id']	= "{$_SESSION['member_id']}";
		$list_consult	= $consult->getConsultList($search_array,$page);
		Tpl::output('show_page',$page->show());
		Tpl::output('list_consult',$list_consult);
		$_GET['type']	= empty($_GET['type'])?'consult_list':$_GET['type'];
		self::profile_menu('consult',$_GET['type']);
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::output('menu_sign','consult_manage');
		Tpl::output('menu_sign_url','index.php?act=store_consult&op=consult_list');
		Tpl::output('menu_sign1',$_GET['type']);
		Tpl::showpage('store_consult_manage');
	}

	
	public function drop_consultOp(){
		$consult	= Model('consult');
		$id_array = explode(',',trim($_GET['id']));
		$consult_id = "'".implode("','",$id_array)."'";
		$state	= $consult->dropConsult($consult_id,$_SESSION['member_id']);
		if($state) {
			showDialog(Language::get('store_consult_drop_success'),'reload','succ');
		} else {
			showDialog(Language::get('store_consult_drop_fail'));
		}
	}


	public function reply_consultOp(){
		$consult	= Model('consult');
		$list_consult	= array();
		$search_array	= array();
		$search_array['consult_id']	= intval($_GET['id']);
		$search_array['seller_id']	= "{$_SESSION['member_id']}";
		$list_consult	= $consult->getConsultList($search_array);
		$consult_info	= $list_consult[0];
		Tpl::output('consult',$consult_info);
		//脚部文章输出
		$article = $this->_article();
		
		Tpl::showpage('store_consult_form','null_layout');
	}


	public function reply_saveOp(){
		$consult	= Model('consult');
		$consult_id = intval($_POST['consult_id']);
		$input	= array();
		$input['consult_reply']	= $_POST['content'];
		$condtion_arr = array();
		$condtion_arr['seller_id'] = "{$_SESSION['member_id']}";
		$condtion_arr['consult_id'] = "{$consult_id}";
		$state	= $consult->replyConsult($input,$condtion_arr);		
		if($state){
			showDialog(Language::get('nc_common_op_succ'),'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog(Language::get('nc_common_op_fail'));
		}
	}

	
	private function profile_menu($menu_type,$menu_key='',$array=array()) {		
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
			case 'consult':
				$menu_array	= array(
				1=>array('menu_key'=>'consult_list',	'menu_name'=>Language::get('nc_member_path_all_consult'),			'menu_url'=>'index.php?act=store_consult&op=consult_list'),
				2=>array('menu_key'=>'to_reply',	'menu_name'=>Language::get('nc_member_path_unreplied_consult'),			'menu_url'=>'index.php?act=store_consult&op=consult_list&type=to_reply'),
				3=>array('menu_key'=>'replied',	'menu_name'=>Language::get('nc_member_path_replied_consult'),			'menu_url'=>'index.php?act=store_consult&op=consult_list&type=replied'));
				break;
		}
		if(!empty($array)) {
			$menu_array[] = $array;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
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
}