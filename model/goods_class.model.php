<?php



defined('haipinlegou') or exit('Access Invalid!');



class goods_classModel extends Model{

	public function __construct(){

		parent::__construct('goods_class');

	}

	

	public function getClassList($condition ,$field='*'){

		$condition_str = $this->_condition($condition);

		$param = array();

		$param['table'] = 'goods_class';

		$param['field'] = $field;

		$param['where'] = $condition_str;

		$param['order'] = $condition['order'] ? $condition['order'] : 'gc_parent_id asc,gc_sort asc,gc_id asc';

		$result = Db::select($param);



		return $result;

	}



	

	private function _condition($condition){

		$condition_str = '';

		/*

		if (!is_null($condition['gc_parent_id'])){

			$condition_str .= " and gc_parent_id = '". intval($condition['gc_parent_id']) ."'";

		}

		*/

		if(is_array($condition['gc_parent_id']))

		{

			foreach($condition['gc_parent_id'] as $k=>$v)

			{

				$arr .= "'".$v."',";

			}

			$arr = substr($arr,0,-1);



			$condition_str	.= " gc_parent_id in(".$arr.") ";

			

		}else if(!is_null($condition['gc_parent_id']))

		{

			$condition_str .= " and gc_parent_id = '". intval($condition['gc_parent_id']) ."'";

		}



		if (!is_null($condition['no_gc_id'])){

			$condition_str .= " and gc_id != '". intval($condition['no_gc_id']) ."'";

		}

		if ($condition['in_gc_id'] != ''){

			$condition_str .= " and gc_id in (". $condition['in_gc_id'] .")";

		}

		if ($condition['gc_name'] != ''){

			$condition_str .= " and gc_name = '". $condition['gc_name'] ."'";

		}

		if ($condition['gc_show'] != '') {

			$condition_str .= " and gc_show= '{$condition['gc_show']}'";

		}

		if (isset($condition['un_type_name'])) {

			$condition_str .= " and type_name <> ''";

		}

		if ($condition['un_type_id'] != '') {

			$condition_str .= " and type_id <> '". $condition['un_type_id'] ."'";

		}

		if ($condition['in_type_id'] != '') {

			$condition_str .= " and type_id in (".$condition['in_type_id'].")";

		}		

		if ($condition['store_id'] != '') {

			$condition_str .= " and store_id = '".$condition['store_id']."'";

		}		



		return $condition_str;

	}



	

	public function getOneGoodsClass($id,$field='gc_id'){
		if (intval($id) > 0){

			$param = array();

			$param['table'] = 'goods_class';

			$param['field'] = $field;

			$param['value'] = intval($id);

			$result = Db::getRow($param);
			return $result;

		}else {

			return false;

		}

	}



	

	public function add($param){

		if (empty($param)){

			return false;

		}

		if (is_array($param)){

			$tmp = array();

			foreach ($param as $k => $v){

				$tmp[$k] = $v;

			}

			$result = Db::insert('goods_class',$tmp);

			return $result;

		}else {

			return false;

		}

	}



	

	public function goodsClassUpdate($param){

		if (empty($param)){

			return false;

		}

		if (is_array($param)){

			$tmp = array();

			foreach ($param as $k => $v){

				$tmp[$k] = $v;

			}

			$where = " gc_id = '". $param['gc_id'] ."'";

			$result = Db::update('goods_class',$tmp,$where);

			return $result;

		}else {

			return false;

		}

	}

	

	

	public function updateWhere($param, $condition){

		if (empty($param)){

			return false;

		}

		if (is_array($param)){

			$tmp = array();

			foreach ($param as $k => $v){

				$tmp[$k] = $v;

			}

			$where = $this->_condition($condition);

			$result = Db::update('goods_class',$tmp,$where);

			return $result;

		}else {

			return false;

		}

	}



	

	public function del($id){

		if (intval($id) > 0){

			$where = " gc_id = '". intval($id) ."'";

			$result = Db::delete('goods_class',$where);

			return $result;

		}else {

			return false;

		}

	}



	

	public function getTreeClassList($show_deep='5',$condition=array()){

		$class_list = $this->getClassList($condition);

		$goods_class = array();

		if(is_array($class_list) && !empty($class_list)) {

			$show_deep = intval($show_deep);

			if ($show_deep == 1){

				foreach ($class_list as $val) {

					if($val['gc_parent_id'] == 0) {

						$val['deep'] = 1;

						$goods_class[] = $val;

					} else {

						break;

					}

				}

			} else {

				$goods_class = $this->_getTreeClassList($show_deep,$class_list);

			}

		}

		return $goods_class;

	}



	

	private function _getTreeClassList($show_deep,$class_list,$deep=1,$parent_id=0,$i=0){

		static $show_class = array();

		if(is_array($class_list) && !empty($class_list)) {

			$size = count($class_list);

			if($i == 0) $show_class = array();

			for ($i;$i < $size;$i++) {

				$val = $class_list[$i];

				$gc_id = $val['gc_id'];

				$gc_parent_id	= $val['gc_parent_id'];

				if($gc_parent_id == $parent_id) {

					$val['deep'] = $deep;

					$show_class[] = $val;

					if($deep < $show_deep && $deep < 5) {

						$this->_getTreeClassList($show_deep,$class_list,$deep+1,$gc_id,$i+1);

					}

				}

				if($gc_parent_id > $parent_id) break;

			}

		}

		return $show_class;

	}



	

	public function getChildClass($parent_id,$gc_show=''){

		$condition = array('order'=>'gc_parent_id asc,gc_sort asc,gc_id asc');

		if ($gc_show != '') {

			$condition['gc_show'] = intval($gc_show);

		}

		$all_class = $this->getClassList($condition);

		if (is_array($all_class)){

			if (!is_array($parent_id)){

				$parent_id = array($parent_id);

			}

			$result = array();

			foreach ($all_class as $k => $v){

				$gc_id	= $v['gc_id'];

				$gc_parent_id	= $v['gc_parent_id'];

				if (in_array($gc_id,$parent_id) || in_array($gc_parent_id,$parent_id)){

					$parent_id[] = $v['gc_id'];

					$result[] = $v;

				}

			}

			return $result;

		}else {

			return false;

		}

	}



	

	public function getGoodsClassNav($id = 0){

		if (intval($id) > 0){

			$data = ($g = H('goods_class')) ? $g : H('goods_class',true);



			

			$nav_link[] = array('title'=>$data[$id]['gc_name']);

			

			

			for($i=1;$i<5;$i++){

				if ($data[$id]['gc_parent_id'] == '0') break;

				$id = $data[$id]['gc_parent_id'];

				$nav_link[] = array(

					'title'=>$data[$id]['gc_name'],

					'link'=>ncUrl(array('act'=>'search','cate_id'=>$data[$id]['gc_id'])));

			}

			

			

		//	$nav_link[] = array('title'=>Language::get('goods_class_index_goods_class'),'link'=>ncUrl(array('act'=>'category')));
			$nav_link[] = array('title'=>Language::get('goods_class_index_goods_class'),'link'=>SiteUrl.'/index.php?act=search&keyword=');
			$nav_link[] = array('title'=>Language::get('homepage'),'link'=>SiteUrl.'/index.php');





		}else{

			

			$nav_link[] = array('title'=>Language::get('goods_class_index_search_results'));

			$nav_link[] = array('title'=>Language::get('homepage'),'link'=>SiteUrl.'/index.php');

		}

		krsort($nav_link);

		return $nav_link;

	}



	

	public function getGoodsClassLineForTag($id = 0){

		if (intval($id) > 0){

			$gc_line = array();

			

			$class = self::getOneGoodsClass(intval($id));

			

			if ($class['gc_parent_id'] != 0){

				$parent_1 = self::getOneGoodsClass($class['gc_parent_id']);

				if ($parent_1['gc_parent_id'] != 0){

					$parent_2 = self::getOneGoodsClass($parent_1['gc_parent_id']);

					$gc_line['gc_id']		= $parent_2['gc_id'];

					$gc_line['type_id']		= $parent_2['type_id'];

					$gc_line['gc_id_1']		= $parent_2['gc_id'];

					$gc_line['gc_tag_name']	= trim($parent_2['gc_name']).'&nbsp;&gt;&nbsp;';

					$gc_line['gc_tag_value']= trim($parent_2['gc_name']).',';

				}

				$gc_line['gc_id']		= $parent_1['gc_id'];

				$gc_line['type_id']		= $parent_1['type_id'];

				if(!isset($gc_line['gc_id_1'])){

					$gc_line['gc_id_1']	= $parent_1['gc_id'];

				}else{

					$gc_line['gc_id_2']	= $parent_1['gc_id'];

				}

				$gc_line['gc_tag_name']	.= trim($parent_1['gc_name']).'&nbsp;&gt;&nbsp;';

				$gc_line['gc_tag_value'].= trim($parent_1['gc_name']).',';

			}

			$gc_line['gc_id']		= $class['gc_id'];

			$gc_line['type_id']		= $class['type_id'];

			if(!isset($gc_line['gc_id_1'])){

				$gc_line['gc_id_1']	= $class['gc_id'];

			}else if(!isset($gc_line['gc_id_2'])){

				$gc_line['gc_id_2']	= $class['gc_id'];

			}else{

				$gc_line['gc_id_3']	= $class['gc_id'];

			}

			$gc_line['gc_tag_name']	.= trim($class['gc_name']).'&nbsp;&gt;&nbsp;';

			$gc_line['gc_tag_value'].= trim($class['gc_name']).',';

		}

		$gc_line['gc_tag_name']		= trim($gc_line['gc_tag_name'],'&nbsp;&gt;&nbsp;');

		$gc_line['gc_tag_value']	= trim($gc_line['gc_tag_value'],',');

		return $gc_line;

	}



   

    public function getGoodsCountById($gc_id) {

        $goods_model = Model('goods');

        $count = $goods_model->countGoods(array(

                'gc_id' => $gc_id,

                'goods_show' => 1,

            ));

        return $count;

    }

	

    public function getClassGoodsCount($gc_id) {

        $goods_model = Model('goods');

        $count = $goods_model->countGoods(array(

                'gc_id_in' => $gc_id,

                'goods_state' => 0,

                'goods_show' => 1,

        		'goods_store_state'=>'open'

            ));

        return $count;

    }



   

	public function getKeyWords($gc_id = null){

		if (is_null($gc_id)) return false;

		$keywrods = ($seo_info = H('goods_class_seo')) ? $seo_info : H('goods_class_seo',true);

		$seo_title = $keywrods[$gc_id]['title'];

		$seo_key = '';

		$seo_desc = '';

		if ($gc_id > 0){

			if (isset($keywrods[$gc_id])){

				$seo_key .= $keywrods[$gc_id]['key'].',';

				$seo_desc .= $keywrods[$gc_id]['desc'].',';

			}

			$goods_class = ($g = H('goods_class')) ? $g : H('goods_class',true);

			if(($gc_id = $goods_class[$gc_id]['gc_parent_id']) > 0){

				if (isset($keywrods[$gc_id])){

					$seo_key .= $keywrods[$gc_id]['key'].',';

					$seo_desc .= $keywrods[$gc_id]['desc'].',';

				}

			}

			if(($gc_id = $goods_class[$gc_id]['gc_parent_id']) > 0){

				if (isset($keywrods[$gc_id])){

					$seo_key .= $keywrods[$gc_id]['key'].',';

					$seo_desc .= $keywrods[$gc_id]['desc'].',';

				}

			}

		}

		return array(1=>$seo_title,2=>trim($seo_key,','),3=>trim($seo_desc,','));

	}

}