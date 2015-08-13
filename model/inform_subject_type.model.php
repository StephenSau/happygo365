<?php 

defined('haipinlegou') or exit('Access Invalid!');
class inform_subject_typeModel{

	
	private function getCondition($condition){
		$condition_str = '' ;
        if(!empty($condition['inform_type_state'])) {
            $condition_str.= "and  inform_type_state = '{$condition['inform_type_state']}'";
        }
        if(!empty($condition['in_inform_type_id'])) {
            $condition_str .= " and inform_type_id in (".$condition['in_inform_type_id'].')';
        }
        return $condition_str;
    }

	
	public function saveInformSubjectType($param){
	
		return Db::insert('inform_subject_type',$param) ;
	
	}
	
	
	public function updateInformSubjectType($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update('inform_subject_type',$update_array,$where) ;
    
    }
	
	
	public function dropInformSubjectType($param){

		$where = $this->getCondition($param) ;
		return Db::delete('inform_subject_type', $where) ;
	
	}

	
	public function getInformSubjectType($condition='',$page=''){

        $param = array() ;
        $param['table'] = 'inform_subject_type' ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' inform_type_id desc ';
        return Db::select($param,$page) ;
	
	}

	
	public function getActiveInformSubjectType($page='') {

        $condition = array();
        $condition['order'] = 'inform_type_id asc';
        $condition['inform_type_state'] = 1;
        return $this->getInformSubjectType($condition,$page) ;
	
	}


}
