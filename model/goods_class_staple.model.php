<?php



defined('haipinlegou') or exit('Access Invalid!');



class goods_class_stapleModel{

	

	public function getStapleList($condition, $page = '', $field='*'){

		$condition_str = $this->_condition($condition);

		$param = array();

		$param['table'] = 'goods_class_staple';

		$param['field'] = $field;

		$param['where'] = $condition_str;

		$param['order'] = $condition['order'] ? $condition['order'] : 'staple_id desc';

		$result = Db::select($param, $page);



		return $result;

	}

	

	public function getStapleOne($condition, $field='*'){

		$condition_str = $this->_condition($condition);

		$param = array();

		$param['table'] = 'goods_class_staple';

		$param['field'] = $field;

		$param['where'] = $condition_str;

		$param['order'] = $condition['order'] ? $condition['order'] : 'staple_id desc';

		$result = Db::select($param);



		return $result['0'];

	}

	

	

	public function addStaple($param){

		if (empty($param)){

			return false;

		}

		if (is_array($param)){

			$tmp = array();

			foreach ($param as $k => $v){

				$tmp[$k] = $v;

			}

			$result = Db::insert('goods_class_staple',$tmp);

			return $result;

		}else {

			return false;

		}

	}

	

	

	public function countStaple($id){

		$param = array();

		$param['table'] = 'goods_class_staple';

		$param['field'] = 'count(`staple_id`) as count';

		$param['where'] = ' and store_id = "'.$id.'"';

		$result = Db::select($param);

		return $result['0']['count'];

	}

	

	public function delStaple($staple_id, $store_id){

		return Db::delete('goods_class_staple', 'staple_id ="'.$staple_id.'" and store_id ="'.$store_id.'"');

	}

	

	

	private function _condition($condition){

		$condition_str = '';

		if($condition['store_id'] != '') {

			$condition_str .= ' and store_id = "'. $condition['store_id'] .'"';

		}

		if($condition['gc_id'] != '') {

			$condition_str .= ' and gc_id = "'. $condition['gc_id'] .'"';

		}

		if($condition['type_id'] != '') {

			$condition_str .= ' and type_id = "'. $condition['type_id'] .'"';

		}

		return $condition_str;

	}

}