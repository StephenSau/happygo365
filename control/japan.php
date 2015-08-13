<?php

defined('haipinlegou') or exit('Access Invalid!');
define('MYSQL_RESULT_TYPE',1);
class japanControl extends BaseHomeControl{
	
    
    //对应国家馆
	public function indexOp(){

		Language::read('home_index_index');
		
		Tpl::output('index_sign','korea');
		
	    //获取对应国家馆的产品
		$goods = Model('goods');
		$param['order'] = 'goods_add_time DESC';
		$param['place']['country'] = '日本';
	    $param['place']['store_id'] = 17;
        $param['goods_show'] = 1;
		$goods_list = $goods->getGoods($param,'','*','groupbuy_goods_info_spec');
        
        $classid=array();
        $topgoods=array();
        $i=0;//计算器
        //条件过滤 
        foreach($goods_list as $key=>$val){
            //爆款
            if($val['goods_commend']==1){
                if($i<3){
                $topgoods[]=$val;
                $i++;
                }
            }
            
            //新款
            if($key<5){
                $newgoods[]=$val;
            }
            
            //获取产品内的分类
            if(!in_array($val['gc_id'],$classid)){
               $classid[]=$val['gc_id'];
            }
            
            //每个分类显示8个产品
            if(count($classgoods[$val['gc_id']])<8){
            	$classgoods[$val['gc_id']][]=$val;
            }
        }
        
        //组装
        if(is_array($classid)){
            $parentclassid=array();
            $classarr=implode(',',$classid);
            $array['table']='goods_class';
            $array['where'] = "gc_id in(".$classarr.")";
            $goodsclass_array	= Db::select($array);//获取2级
            foreach($goodsclass_array as $val){
                if($val['gc_parent_id']>0){
                    $parentclassid[$val['gc_name']]=$classgoods[$val['gc_id']];
                }
            }
        }
		//爆款
		Tpl::output('topgoods',$topgoods);
        
        //新款
        Tpl::output('newgoods',$newgoods);
       
        //分类
         Tpl::output('parentclassid',$parentclassid);
        
        //文章
        $this->_article();
        Tpl::showpage('japan_index');
		
	}
    
    
    
	//对应国家馆
	public function listOp(){

		Language::read('home_index_index');
		
		Tpl::output('index_sign','korea');
		
		
		
		//获取对应国家馆的产品
		$goods = Model('goods');
		
		//分页
		$page	= new Page();
		$page->setEachNum(24);
		$page->setStyle('admin');

		$param['gc_id'] = @join(",",$v);

		$param['order'] = 'goods_starttime DESC ,goods_sorting ASC,goods_serial ASC';
		
        $param['place']['country'] = '日本';
	
        $param['place']['store_id'] = 17;
        $param['goods_show'] = 1;
		$goods_list = $goods->getGoods($param,$page,'*','stc');
              

		Tpl::output('page',$page->show());
		Tpl::output('goods_list',$goods_list);
		
		//商店头部判断是否出现卖家中心的连接
		if($_SESSION['is_login'] == '1'){
			$member_model	= Model('member');
			$member_info	= $member_model->infoMember(array('member_id'=>$_SESSION['member_id']),'member_name,member_points,available_predeposit,member_avatar,category');
			Tpl::output('member_info',$member_info);
		}


		//S脚部内容输出
		$list = $this->_article();
		//E脚部内容输出

		Tpl::output('show_flink',($link = F('link')) ? $link : H('link',true,'file'));
		
		Tpl::showpage('japan');
		
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
