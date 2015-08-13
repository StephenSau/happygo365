<?php

defined('haipinlegou') or exit('Access Invalid!');
define('MYSQL_RESULT_TYPE',1);
class xianshiControl extends BaseHomeControl{
   const TEMPLATE_STATE_ACTIVE = 1;
   
	public function __construct() {
		parent::__construct();
		//读取语言包
		Language::read('store_goods_index');
		 Language::read('member_groupbuy,home_cart_index');
	}
   public function indexOp(){
       if (intval($GLOBALS['setting_config']['groupbuy_allow']) !== 1){
           showMessage(Language::get('groupbuy_unavailable'),'index.php','','error');
       }
	    Language::read('home_goods_class_index');
		$lang	= Language::getLangContent();
		//翻页
		// $model = Model();
		// $count = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where($condition)->order($param['order'])->count();
		// print_r($count);exit;
		
		
		//获取父级分类
		$goods = Model('goods_class');
		$param['gc_parent_id'] = 0;
		$father_class = $goods->getClassList($param ,$field='*');
		Tpl::output('father_class',$father_class);
		
		$xianshi = Model('p_xianshi_goods');
		
		$groupbuy_order_key = trim($_GET['groupbuy_order_key']);
        if(!empty($groupbuy_order_key)) {
            switch ($groupbuy_order_key) {
                case 'price':
                    $param['order'] = 'p_xianshi_goods.goods_store_price desc';
                    break;
                case 'discount':
                    $param['order'] = 'p_xianshi_goods.discount asc';
                    break;
            }
        }else
		{
            $param['order'] = 'p_xianshi_goods.goods_id desc';

		}
		
		$field = 'p_xianshi.xianshi_name,p_xianshi.store_id,p_xianshi.store_name,p_xianshi.start_time,p_xianshi.end_time,'.
		'p_xianshi_goods.goods_id,p_xianshi_goods.goods_name,p_xianshi_goods.goods_image,p_xianshi_goods.goods_store_price,p_xianshi_goods.discount';
		$promotion_time = time();
		$condition	= array(
			'start_time'=>array('lt',$promotion_time),
			'end_time'=>array('gt',$promotion_time),
			'p_xianshi.state'=>'2',
			'p_xianshi_goods.state'=>'1',
		);
		$model = Model();
		
		$goods = Model('goods');
		//分类活动
		$gc_id = intval($_GET['groupbuy_class']);
		// print_r();exit;
		if(!empty($gc_id)){
			
			$goods_class = Model('goods_class');			
			$goods_num = $goods_class->getChildClass($gc_id);
			foreach($goods_num as $k=>$v)
			{
				$arr[$k] = $v['gc_id'];
			}
			//获取对应分类的产品
			$array['gc_id'] = join(",",$arr);
			$goods_lists = $goods->getGoods($array);	
			
			//帅选goods_id
			if(!empty($goods_lists) && is_array($goods_lists)){
				foreach($goods_lists as $k=>$v)
				{
					$brr[] = $v['goods_id'];

				}
				$page	= new Page();
				$brr = join(",",$brr);
				
				$count1 = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where('start_time < '.$promotion_time.' and end_time > '.$promotion_time.' and p_xianshi.state = 2 and p_xianshi_goods.state = 1 and p_xianshi_goods.goods_id in('.$brr.')')->order($param['order'])->count();//防止出现全是一个店铺的使用随机
				$xianshi_item = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where('start_time < '.$promotion_time.' and end_time > '.$promotion_time.' and p_xianshi.state = 2 and p_xianshi_goods.state = 1 and p_xianshi_goods.goods_id in('.$brr.')')->order($param['order'])->page(5,$count1)->select();//防止出现全是一个店铺的使用随机
				
				$page->setEachNum(5);
				$page->setTotalNum($count1);
				$page->setStyle('admin');
			}
			
		}else{
			
			$page	= new Page();
			$count1 = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where($condition)->order($param['order'])->count();
			$page->setEachNum(10);
			$page->setTotalNum($count1);
			$page->setStyle('admin');

			$xianshi_item = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where($condition)->order($param['order'])->page(10,$count1)->select();//防止出现全是一个店铺的使用随机排序
		}
		
		F('promotion',$list,'cache/index');
		
		//S脚部内容输出
		$list = $this->_article();


		if(!empty($xianshi_item)){
			Tpl::output('show_page',$page->show());

		};
		Tpl::output('xianshi_item',$xianshi_item);
		Tpl::showpage('xianshi');

   }

    /**
     *
     */
    public function limtimeOp()
    {
        $nowTime=time();
        $modelActivity=Model('activity');
        //限时已到时间商品
        $limtimeGoodsStart=$modelActivity->getLimtGoodsList(array('table'=>'p_xianshi_goods,goods,p_xianshi',
            'join'=>'left join',
            'fields'=>'goods.market_price,goods.store_id,goods.goods_image_more,p_xianshi.xianshi_name,p_xianshi.store_id,p_xianshi.store_name,p_xianshi.start_time,p_xianshi.end_time,'.
            'p_xianshi_goods.goods_id,p_xianshi_goods.goods_name,p_xianshi_goods.goods_store_price,p_xianshi_goods.discount',
            'join_on'=>array('p_xianshi_goods.goods_id=goods.goods_id','p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id'),
            'where'=>"{$nowTime}>p_xianshi.start_time and {$nowTime}<p_xianshi.end_time and p_xianshi.state=2 and p_xianshi_goods.state=1",
            'order'=>mt_rand(1,5),
            'limit'=>3));
        foreach($limtimeGoodsStart as $k=>$v){
            if(empty($v['goods_image_more'])){

            }else{
                $image=explode(',',$v['goods_image_more']);
            }
            $limtimeGoodsStart[$k]['goods_xianshi_image']=$image[0];
            unset($image);
        }
        //限时未到时间商品
        $limtimeGoodsNoStart=$modelActivity->getLimtGoodsList(array('table'=>'p_xianshi_goods,goods,p_xianshi',
            'join'=>'left join',
            'fields'=>'goods.market_price,goods.store_id,goods.goods_image_more,goods.goods_collect,p_xianshi.xianshi_name,p_xianshi.store_id,p_xianshi.store_name,p_xianshi.start_time,p_xianshi.end_time,'.
                'p_xianshi_goods.goods_id,p_xianshi_goods.goods_name,p_xianshi_goods.goods_store_price,p_xianshi_goods.discount',
            'join_on'=>array('p_xianshi_goods.goods_id=goods.goods_id','p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id'),
            'where'=>"{$nowTime}<p_xianshi.start_time and p_xianshi.state=2 and p_xianshi_goods.state=1",
            'order'=>mt_rand(1,5),
            'limit'=>3));
        foreach($limtimeGoodsNoStart as $key=>$val){
            if(empty($val['goods_image_more'])){

            }else{
                $image=explode(',',$val['goods_image_more']);
            }
            $limtimeGoodsNoStart[$key]['goods_xianshi_image']=$image[0];
            unset($image);
        }
        Tpl::output('limbegin_goods',$limtimeGoodsStart);
        Tpl::output('limnobegin_goods',$limtimeGoodsNoStart);
        Tpl::showpage('limtime');
    }
   
   //限时这块
   private function _promotion(){
		$cache_file = BasePath.DS.'cache'.DS.'index'.DS.'promotion.php';
		// D:/wamp/www/haipinlegou/www/cache/index/promotion.php
		if (!file_exists($cache_file) || filemtime($cache_file) <= (time()-SESSION_EXPIRE)){
			$limit	= 5;
			$field = 'p_xianshi.xianshi_name,p_xianshi.store_id,p_xianshi.store_name,p_xianshi.start_time,p_xianshi.end_time,'.
			'p_xianshi_goods.goods_id,p_xianshi_goods.goods_name,p_xianshi_goods.goods_image,p_xianshi_goods.goods_store_price,p_xianshi_goods.discount';
			$promotion_time = time();
			$condition	= array(
				'start_time'=>array('lt',$promotion_time),
				'end_time'=>array('gt',$promotion_time),
				'p_xianshi.state'=>'2',
				'p_xianshi_goods.state'=>'1'
			);
			$model = Model();
			$list = $model->table('p_xianshi_goods,p_xianshi')->field($field)->on('p_xianshi_goods.xianshi_id=p_xianshi.xianshi_id')->where($condition)->order(rand(1,5))->limit($limit)->select();//防止出现全是一个店铺的使用随机排序
			F('promotion',$list,'cache/index');
		} else {
			$list = F('promotion','','cache/index');
		}
		return $list;
	}
	
	private function get_groupbuy_template_list($type,$page='',$param = array()) {

        $model_groupbuy_template = Model('groupbuy_template');
        $param['state'] = self::TEMPLATE_STATE_ACTIVE;
        switch ($type) {
            case 'in_progress':
                $param['in_progress'] = time();
                break;
            case 'soon':
                $param['less_than_start_time'] = time();
                $param['order'] = 'start_time asc';
                break;
            case 'history':
                $param['greater_than_end_time'] = time();
                break;
            default:
                $param['in_progress'] = time();
                break;
        }
        $template_list = $model_groupbuy_template->getList($param,$page);
        return $template_list;
    }
   /**
	 * 浏览过的商品
	 *
	 */

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
