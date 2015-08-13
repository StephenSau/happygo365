<?php

defined('haipinlegou') or exit('Access Invalid!');

class categoryControl extends BaseHomeControl {
	
	public function indexOp(){
		Language::read('home_category_index');
		$lang	= Language::getLangContent();
		$type = trim($_GET['type']);
		switch ($type){
			case 'store':
				$model_sc = Model('store_class');
				$sc_list = $model_sc->getTreeList();;
				$nav_link = array(
					'0'=>array('title'=>$lang['homepage'],'link'=>SiteUrl.'/index.php'),
					'1'=>array('title'=>$lang['category_index_store_class'])
				);

				$model_store = Model('store');
				$recommend_store = $model_store->getRecommendStore(5);
				Tpl::output('recommend_store',$recommend_store);
				$new_store = $model_store->getNewStore(5);
				Tpl::output('new_store',$new_store);

				Tpl::output('nav_link_list',$nav_link);
				Tpl::output('sc_list',$sc_list);
				Tpl::showpage('category_store');
				break;
			default :

				$nav_link = array(
					'0'=>array('title'=>$lang['homepage'],'link'=>SiteUrl.'/index.php'),
					'1'=>array('title'=>$lang['category_index_goods_class'])
				);
					
				
				$show_goods_class = ($g = F('goods_class')) ? $g : H('goods_class',true,'file');

				foreach($show_goods_class as $classkey => $v){

					if($classkey % 2 == 0){
						$aa[$classkey] = $v;
					}else{
						$bb[$classkey] = $v;
					}
				}
                
                //S脚部内容输出
				$list = $this->_article();
				//E脚部内容输出
				
				Tpl::output('aa',$aa);
				Tpl::output('bb',$bb);
				Tpl::output('nav_link_list',$nav_link);
				Tpl::output('gc_list',$show_goods_class);
				Tpl::output('html_title',C('site_name').' - '.Language::get('category_index_goods_class'));
				Tpl::showpage('category_goods');
				break;
		}
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
	
}
