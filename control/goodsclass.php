<?php



defined('haipinlegou') or exit('Access Invalid!');

class goodsclassControl extends BaseHomeControl {
	public function indexOp(){
        Language::read('home_goods_class_index');
        $lang	= Language::getLangContent();
        $modelGoodsClass=Model('goods_class');
        $this->_article();
        $class=intval($_GET['cate_id']);
        $goodsClassOne=$modelGoodsClass->getOneGoodsClass($class,'gc_id');

        //title
        Tpl::output('html_title','海品乐购_'.$goodsClassOne['gc_name']);
        $goodsClassInfo=$this->getClass(['class'=>$class]);
        //热销商品
        $modelTplBlock=Model('tpl_block');
        $modelGoods=Model('goods');
        $hotsale=$modelTplBlock->getTplBlocks([
            'field'=>'block_value',
            'table'=>'tpl_block',
            'where'=>"class_id={$class} and word_value='hotsale' and block_is_show=1",
            'order'=>'block_id desc',
            'limit'=>1,
        ]);
        //分类页顶部广告
        $topAdv=$modelTplBlock->getTplBlocks([
            'field'=>'*',
            'table'=>'tpl_block',
            'where'=>"class_id={$class} and word_value='class_top_image' and block_is_show=1",
            'order'=>'block_id desc',
        ]);
        Tpl::output('top_adv',$topAdv);
        //热销商品广告
        $hotAdv=$modelTplBlock->getTplBlocks([
            'field'=>'*',
            'table'=>'tpl_block',
            'where'=>"class_id={$class} and word_value='class_mid_image' and block_is_show=1",
            'order'=>'block_id desc',
        ]);
        Tpl::output('mid_adv',$hotAdv);

        if(!empty($hotsale[0]['block_value'])) {
            $hotgoods = $modelGoods->getGoods([
                'goods_id_in' => $hotsale[0]['block_value'],
            ]);
        }
        Tpl::output('hot_goods',$hotgoods);
        Tpl::output('goods_class_info',$goodsClassInfo);
		//新首页输出
		Tpl::showpage('goodsclass');

	}

    protected function getClass($param)
    {
        $modelTplBlock=Model('tpl_block');
        $modelGoods=Model('goods');
        $modelGoodsClass=Model('goods_class');
        $modelBrand=Model('brand');
        if(is_array($param)&&!empty($param)){
            $blockInfo=$modelTplBlock->getTplBlocks([
                'table'=>'tpl_block',
                'field'=>'class_id,key_num,block_value',
                'where'=>"class_id={$param['class']} and block_is_show=1 and word_value='class'",
                'order'=>"block_id asc"
            ]);
            $goodsClassInfo=[];
            if(is_array($blockInfo)&&!empty($blockInfo)) {
                foreach ($blockInfo as $k => $v) {
                    $condtions = json_decode($v['block_value']);
                    //推荐品牌
                    $brand = (is_array($condtions->brand) && !empty($condtions->brand)) ? implode(',', $condtions->brand) : '';
                    if(!empty($brand)) {
                        $goodsClassInfo[$v['key_num']]['brand'] = $modelGoodsClass->getClassList([
                            'in_gc_id' => $brand,
                        ]);
                    }
                    $typeId='';
                    if(is_array($goodsClassInfo[$v['key_num']]['brand'])&&!empty($goodsClassInfo[$v['key_num']]['brand'])){
                        foreach($goodsClassInfo[$v['key_num']]['brand'] as $item){
                            $typeId.=$item['type_id'].',';
                        }
                    }
                    $typeId=rtrim($typeId,',');
                    if(!empty($typeId)) {
                        $goodsClassInfo[$v['key_num']]['brand_list'] = $modelBrand->getBrands([
                            'table' => 'type_brand,brand',
                            'join_type' => 'inner join',
                            'join_on' => ["`type_brand`.brand_id=`brand`.brand_id"],
                            'where' => "`type_brand`.type_id in({$typeId})"
                        ], 'join');
                    }

                    $goodsClassInfo[$v['key_num']]['class_name']=$modelGoodsClass->getOneGoodsClass($v['key_num'],'gc_id');
                    //最新上架商品
                    $goodsClassInfo[$v['key_num']]['new_goods_info'] = $modelGoods->getGoods([
                        'order' => "`goods`.goods_starttime desc",
                        'limit' => 7,
                        'gc_id' => $v['key_num'],
                        'goods_show' =>1,
                    ]);
                    $goodsClassInfo[$v['key_num']]['adv_image']=isset($condtions->class_image)?$condtions->class_image:'';
                    $goodsClassInfo[$v['key_num']]['adv_url']=isset($condtions->url)?$condtions->url:'';
                }
            }
            return $goodsClassInfo;
        }
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

}

