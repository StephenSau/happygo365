<?php 

defined('haipinlegou') or exit('Access Invalid!');
class complain_subjectModel{

	
	private function getCondition($condition){
		$condition_str = '' ;
        if(!empty($condition['complain_subject_type'])) {
            $condition_str .= " and complain_subject_type = '{$condition['complain_subject_type']}'";
        }
        if(!empty($condition['complain_subject_state'])) {
            $condition_str .= " and complain_subject_state = '{$condition['complain_subject_state']}'";
        }
        if(!empty($condition['in_complain_subject_id'])) {
            $condition_str .= " and complain_subject_id in (".$condition['in_complain_subject_id'].')';
        }
        return $condition_str;
    }

	
	public function saveComplainSubject($param){
	
		return Db::insert('complain_subject',$param) ;
	
	}
	
	
	public function updateComplainSubject($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update('complain_subject',$update_array,$where) ;
    
    }
	
	
	public function dropComplainSubject($param){

		$where = $this->getCondition($param) ;
		return Db::delete('complain_subject', $where) ;
	
	}

	
	public function getComplainSubject($condition='',$page=''){

        $param = array() ;
        $param['table'] = 'complain_subject' ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' complain_subject_id desc ';
        return Db::select($param,$page) ;
	
	}

	
	public function getActiveComplainSubject($condition='',$page='') {

        $condition['complain_subject_state'] = 1;
        $param['table'] = 'complain_subject' ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' complain_subject_id desc ';
        return Db::select($param,$page) ;
	
	}


}
