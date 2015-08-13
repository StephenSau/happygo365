<?php

defined('haipinlegou') or exit('Access Invalid!');

class articleControl extends BaseHomeControl {
	
	public function indexOp(){

		Language::read('home_article_index');
		if(!empty($_GET['article_id'])){
			$this->showOp();
			exit;
		}
		if(!empty($_GET['ac_id'])){
			$this->articleOp();
			exit;
		}
		
		showMessage(Language::get('article_article_not_found'),'','html','error');
	}
	
	public function articleOp(){

		Language::read('home_article_index');
		$lang	= Language::getLangContent();
		if(empty($_GET['ac_id'])){
			showMessage($lang['miss_argument'],'','html','error');
		}

		$nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0 ;
		Tpl::output('index_sign',$nav_id);
		
		$article_class_model	= Model('article_class');
		$condition	= array();
		if(!empty($_GET['ac_id'])){
			$condition['ac_id']	= intval($_GET['ac_id']);
		}
		$article_class	= $article_class_model->getOneClass(intval($_GET['ac_id']));
		if(empty($article_class) || !is_array($article_class)){
			showMessage($lang['article_article_class_not_exists'],'','html','error');
		}
		$default_count	= 5;
		
		$nav_link = array(
			array(
				'title'=>$lang['homepage'],
				'link'=>'index.php'
			),
			array(
				'title'=>$article_class['ac_name']
			)
		);
		Tpl::output('nav_link_list',$nav_link);

		
		$condition	= array();
		$condition['ac_parent_id']	= $article_class['ac_id'];
		$sub_class_list	= $article_class_model->getClassList($condition);
		if(empty($sub_class_list) || !is_array($sub_class_list)){
			$condition['ac_parent_id']	= $article_class['ac_parent_id'];
			$sub_class_list	= $article_class_model->getClassList($condition);
		}
		Tpl::output('sub_class_list',$sub_class_list);
		
		$child_class_list	= $article_class_model->getChildClass(intval($_GET['ac_id']));
		$ac_ids	= array();
		if(!empty($child_class_list) && is_array($child_class_list)){
			foreach ($child_class_list as $v){
				$ac_ids[]	= $v['ac_id'];
			}
		}
		$ac_ids	= implode(',',$ac_ids);
		$article_model	= Model('article');

		$condition 	= array();
		$condition['ac_ids']	= $ac_ids;
		$condition['article_show']	= '1';
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$article_list	= $article_model->getArticleList($condition,$page);
		Tpl::output('article_lists',$article_list);
		Tpl::output('show_page',$page->show());
	
		$count	= count($article_list);
		$new_article_list	= array();
		if(!empty($article_list) && is_array($article_list)){
			for ($i=0;$i<($count>$default_count?$default_count:$count);$i++){
				$new_article_list[]	= $article_list[$i];
			}
		}
		Tpl::output('new_article_list',$new_article_list);
		
		//文章输出
		$list = $this->_article();
		Tpl::output('list',$list);
		
		Model('seo')->type('article')->param(array('article_class'=>$article_class['ac_name']))->show();
		
		Tpl::showpage('article_list');
	}
	
	public function showOp(){

		Language::read('home_article_index');
		$lang	= Language::getLangContent();
		if(empty($_GET['article_id'])){
			showMessage($lang['miss_argument'],'','html','error');
		}

		
		$article_model	= Model('article');
		$article	= $article_model->getOneArticle(intval($_GET['article_id']));
		if(empty($article) || !is_array($article) || $article['article_show']=='0'){
			showMessage($lang['article_show_not_exists'],'','html','error');
		}
		Tpl::output('article',$article);

		
		$article_class_model	= Model('article_class');
		$condition	= array();
		$article_class	= $article_class_model->getOneClass($article['ac_id']);
		if(empty($article_class) || !is_array($article_class)){
			showMessage($lang['article_show_delete'],'','html','error');
		}
		$default_count	= 5;
		
		$nav_link = array(
			array(
				'title'=>$lang['homepage'],
				'link'=>'index.php'
			),
			array(
				'title'=>$article_class['ac_name'],
				'link'=>'index.php?act=article&ac_id='.$article_class['ac_id']
			),
			array(
				'title'=>$lang['article_show_article_content']
			)
		);
		Tpl::output('nav_link_list',$nav_link);
		
		$condition['is_show']	= 1;
		$condition['ac_parent_id']	= $article_class['ac_id'];
		$sub_class_list	= $article_class_model->getClassList($condition);
		if(empty($sub_class_list) || !is_array($sub_class_list)){
			$condition['ac_parent_id']	= $article_class['ac_parent_id'];
			$sub_class_list	= $article_class_model->getClassList($condition);
		}
		Tpl::output('sub_class_list',$sub_class_list);
		
		$child_class_list	= $article_class_model->getChildClass($article_class['ac_id']);
		$ac_ids	= array();
		if(!empty($child_class_list) && is_array($child_class_list)){
			foreach ($child_class_list as $v){
				$ac_ids[]	= $v['ac_id'];
			}
		}
		$ac_ids	= implode(',',$ac_ids);
		$article_model	= Model('article');
		$condition 	= array();
		$condition['ac_ids']	= $ac_ids;
		$condition['article_show']	= '1';
		$article_list	= $article_model->getArticleList($condition);
		
		$pre_article	= $next_article	= array();
		if(!empty($article_list) && is_array($article_list)){
			$pos	= 0;
			foreach ($article_list as $k=>$v){
				if($v['article_id'] == $article['article_id']){
					$pos	= $k;
					break;
				}
			}
			if($pos>0 && is_array($article_list[$pos-1])){
				$pre_article	= $article_list[$pos-1];
			}
			if($pos<count($article_list)-1 and is_array($article_list[$pos+1])){
				$next_article	= $article_list[$pos+1];
			}
		}
		Tpl::output('pre_article',$pre_article);
		Tpl::output('next_article',$next_article);
		
		$count	= count($article_list);
		$new_article_list	= array();
		if(!empty($article_list) && is_array($article_list)){
			for ($i=0;$i<($count>$default_count?$default_count:$count);$i++){
				$new_article_list[]	= $article_list[$i];
			}
		}
		Tpl::output('new_article_list',$new_article_list);
        $article_model	= Model('article');
        $catelist = $article_model->articleTree();

		//文章输出
		$list = $this->_article();
		
		$seo_param = array();
		$seo_param['name'] = $article['article_title'];
		$seo_param['article_class'] = $article_class['ac_name'];
		Model('seo')->type('article_content')->param($seo_param)->show();
        Tpl::output('catelist',$catelist);
		Tpl::output('list',$list);
		Tpl::showpage('article_show');
	}
	
	//文章输出
	private function _article() {

		if (file_exists(BasePath.'/cache/index/article.php')){
			include(BasePath.'/cache/index/article.php');
			Tpl::output('show_article',$show_article);
			Tpl::output('article_list',$article_list);
			return ;		
		}
		$model_article_class	= Model('article_class');
		$model_article	= Model('article');
		$show_article = array();//商城公告
		$article_list	= array();//下方文章
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
				$val['list']	= array();//文章
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
				if($ac_parent_id == 0) {//顶级分类
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

    /************通告类网页********************/
    public function qualityOp()
    {
        $this->_article();
        Tpl::showpage('quality');

    }


	
}
?>