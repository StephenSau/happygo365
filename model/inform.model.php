<?php 

defined('haipinlegou') or exit('Access Invalid!');
class informModel{

	
	private function getCondition($condition){
		$condition_str = '' ;
        if(!empty($condition['inform_state'])) {
            $condition_str.= " and  inform_state = '{$condition['inform_state']}'";
        }
        if(!empty($condition['inform_goods_id'])) {
            $condition_str.= " and  inform_goods_id = '{$condition['inform_goods_id']}'";
        }
        if(!empty($condition['inform_id'])) {
            $condition_str.= " and  inform_id = '{$condition['inform_id']}'";
        }
        if(!empty($condition['inform_member_id'])) {
            $condition_str.= " and  inform_member_id = '{$condition['inform_member_id']}'";
        }
        if(!empty($condition['inform_store_id'])) {
            $condition_str.= " and  inform_store_id = '{$condition['inform_store_id']}'";
        }
        if(!empty($condition['inform_handle_type'])) {
            $condition_str.= " and  inform_handle_type = '{$condition['inform_handle_type']}'";
        }
        if(!empty($condition['inform_goods_name'])) {
            $condition_str.= " and  inform_goods_name like '%".$condition['inform_goods_name']."%'";
        }
        if(!empty($condition['inform_member_name'])) {
            $condition_str.= " and  inform_member_name like '%".$condition['inform_member_name']."%'";
        }
        if(!empty($condition['inform_type'])) {
            $condition_str.= " and  inform_subject_type_name like '%".$condition['inform_type']."%'";
        }
        if(!empty($condition['inform_subject'])) {
            $condition_str.= " and  inform_subject_content like '%".$condition['inform_subject']."%'";
        }
        if(!empty($condition['inform_datetime_start'])) {
            $condition_str.= " and  inform_datetime > '{$condition['inform_datetime_start']}'";
        }
        if(!empty($condition['inform_datetime_end'])) {
            $end = $condition['inform_datetime_end'] + 86400;
            $condition_str.= " and  inform_datetime < '$end'";
        }
		return $condition_str;
    }

	
	public function saveInform($param){
	
		return Db::insert('inform',$param) ;
	
	}
	
	
	public function updateInform($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update('inform',$update_array,$where) ;
    
    }
	
	
	public function dropInform($param){

		$where = $this->getCondition($param) ;
		return Db::delete('inform', $where) ;
	
	}

	
	public function getInform($condition='',$page='') {

        $param = array() ;
        $param['table'] = 'inform,inform_subject' ;
        $param['join_type'] = 'LEFT JOIN';
        $param['join_on'] = array('inform.inform_subject_id = inform_subject.inform_subject_id');
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' inform_id desc ';
        return Db::select($param,$page);
	}

    
    public function getoneInform($inform_id) {
        
        $param = array() ;
    	$param['table'] = 'inform';
    	$param['field'] = 'inform_id' ;
    	$param['value'] = intval($inform_id);
    	return Db::getRow($param) ;

    }

    
    public function isProcessOfInform($goods_id) {
        
        $condition = array();
        $condition['inform_goods_id'] = $goods_id;
        $condition['inform_state'] = 1;
        $inform = $this->getInform($condition);
        if(count($inform) !== 0) {
            return true;
        }
        else {
            return false;
        }

    }
	
	public function getCount($condition) {
		$condition_str	= $this->getCondition($condition);
		$count	= Db::getCount('inform',$condition_str);
		return $count;
	}

}
