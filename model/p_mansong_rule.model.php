<?php

defined('haipinlegou') or exit('Access Invalid!');
class p_mansong_ruleModel{

    const TABLE_NAME = 'p_mansong_rule';
    const PK = 'rule_id';

	
	private function getCondition($condition){
		$condition_str = '';
        if (!empty($condition['mansong_id'])){
            $condition_str .= " and mansong_id = '".$condition['mansong_id'] ."'";
        }
        if (!empty($condition['quota_id'])){
            $condition_str .= " and quota_id = '".$condition['quota_id'] ."'";
        }
		return $condition_str;
	}

	
	public function getList($condition,$page='',$field='*'){
        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: self::PK.' desc';
        $param['limit'] = $condition['limit'];
        $param['field'] = $field;
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
	
		return Db::insert(self::TABLE_NAME,$param);
	
	}
	
	public function save_array($param_array){
	
		return Db::insertAll(self::TABLE_NAME,$param_array) ;
	
	}

	
	public function update($update_array, $where_array){
	
		$where = $this->getCondition($where_array);
		return Db::update(self::TABLE_NAME,$update_array,$where);
    
    }
	
	
	public function drop($param){

		$where = $this->getCondition($param);
		return Db::delete(self::TABLE_NAME, $where);
	}

   
    public function getCount($param) {
        
        $list = $this->getList($param,'','count(*) as count');
        return $list[0]['count'];
    }
	
}
