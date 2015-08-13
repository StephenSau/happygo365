<?php

defined('haipinlegou') or exit('Access Invalid!');
class goods_specModel{

    const TABLE_NAME = 'goods_spec';
    const PK = 'spec_id';

	
	private function getCondition($condition){
		$condition_str = '';
		if (!empty($condition['spec_id'])){
			$condition_str .= "and spec_id = '".$condition['spec_id']."'";
		}
		return $condition_str;
	}

	
	public function getList($condition='',$page=''){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' '.self::PK.' desc ';
        return Db::select($param,$page);
	}

  
	public function getOne($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = self::TABLE_NAME;
			$param['field'] = self::PK;
			$param['value'] = intval($id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}

	
	public function isExist($condition='') {

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $list = Db::select($param);
        if(empty($list)) {
            return false;
        }
        else {
            return true;
        }
	}

	
	public function save($param){
	
		return Db::insert(self::TABLE_NAME,$param) ;
	
	}
	
	
	public function update($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update(self::TABLE_NAME,$update_array,$where) ;
    
    }
	
	
	public function drop($param){

		$where = $this->getCondition($param) ;
		return Db::delete(self::TABLE_NAME, $where) ;
	}
	
}
