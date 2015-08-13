<?php

defined('haipinlegou') or exit('Access Invalid!');

class activityControl extends BaseHomeControl {
	
	public function indexOp(){
		Language::read('home_activity_index');
		/*
		$nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0 ;
		Tpl::output('index_sign',$nav_id);
		$activity_id = intval($_GET['activity_id']);

		if($activity_id<=0){
			showMessage(Language::get('miss_argument'),'index.php','html','error');
		}
		*/
		//$activity	= Model('activity')->getOneById($activity_id);
		$page	= new Page();
		$page->setEachNum(1);
		$page->setStyle('admin');
		
		$activity	= Model('activity')->getList($condition,$page);
		// if(empty($activity) || $activity['activity_type'] != '1' || $activity['activity_state'] != 1 || $activity['activity_start_date']>time() || $activity['activity_end_date']<time()){
			// showMessage(Language::get('activity_index_activity_not_exists'),'index.php','html','error');
		// }
		
		//商店头部判断是否出现卖家中心的连接
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}

		Tpl::output('activity',$activity);
		Tpl::output('show_page',$page->show());
		$list	= array();
		$list	= Model('activity_detail')->getGoodsList(array('order'=>'activity_detail.activity_detail_sort asc','activity_id'=>"$activity_id",'goods_show'=>'1','activity_detail_state'=>'1'));
		
		//热门推荐活动
		$condition['order'] = 'activity_sort'; 
		$asort = Model('activity')->getList($condition);
		//S脚部文章输出
		$list=$this->_article();
		//E脚部文章输出
		Tpl::output('asort',$asort);
		
		Tpl::output('list',$list);
		Tpl::output('html_title',C('site_name').' - '.$activity['activity_title']);
		Tpl::showpage('activity_show');
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
	
	public function detailedOp(){
		Language::read('home_activity_index');
		// 分页
		$page	= new Page();
		$page->setEachNum(1);
		$page->setStyle('admin');
		
		$activity	= Model('activity')->getList($condition,$page);
		Tpl::output('activity',$activity);
		Tpl::output('show_page',$page->show());
		
		
		$id = intval($_GET['det_id']);
		$det_id = Model('activity') -> getOneById($id);
		// print_r($det_id);exit;
		Tpl::output('det_id',$det_id);
		
		//热门推荐活动
		$condition['order'] = 'activity_sort'; 
		$asort = Model('activity')->getList($condition);
		Tpl::output('asort',$asort);
		Tpl::output('html_title',C('site_name').' - '.$activity['activity_title']);
		Tpl::showpage('activity_detailed');
	}
}
