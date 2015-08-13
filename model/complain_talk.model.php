<?php 

defined('haipinlegou') or exit('Access Invalid!');
class complain_talkModel{

	private function getCondition($condition){
		$condition_str = '' ;
        if(!empty($condition['complain_id'])) {
            $condition_str .= " and complain_id = '{$condition['complain_id']}'";
        }
        if(!empty($condition['talk_id'])) {
            $condition_str .= " and talk_id = '{$condition['talk_id']}'";
        }
		return $condition_str;
    }

	
	public function saveComplainTalk($param){
	
		return Db::insert('complain_talk',$param) ;
	
	}
	
	
	public function updateComplainTalk($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update('complain_talk',$update_array,$where) ;
    
    }
	
	
	public function dropComplainTalk($param){

		$where = $this->getCondition($param) ;
		return Db::delete('complain_talk', $where) ;
	
	}

	
	public function getComplainTalk($condition='',$page='',$field='*'){

        $param = array() ;
        $param['table'] = 'complain_talk' ;
        $param['field'] = $field;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' talk_id desc ';
        return Db::select($param,$page) ;
	
	}

}
