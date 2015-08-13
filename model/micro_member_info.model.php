<?php

defined('haipinlegou') or exit('Access Invalid!');
class micro_member_infoModel extends Model{

    public function __construct(){
        parent::__construct('micro_member_info');
    }

	
	public function getList($condition,$page=null,$order='',$field='*'){
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
	}

	
	public function getListWithUserInfo($condition,$page=null,$order='',$field='*',$limit=''){
        $on = 'micro_member_info.member_id = member.member_id';
        $result = $this->table('micro_member_info,member')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $result;
	}



    
    public function getOne($condition){
        $result = $this->where($condition)->find();
        return $result;
    }

    
    public function getOneById($member_id){
        if(intval($member_id) > 0) {
            $result = $this->where(array('member_id'=>$member_id))->find();
            return $result;
        } else {
            return false;
        }
    }

	
	public function isExist($condition) {
        $result = $this->getOne($condition);
        if(empty($result)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
	}

	
    public function save($param){
        return $this->insert($param);	
    }
	
	
    public function saveAll($param){
        return $this->insertAll($param);	
    }
	
	
    public function modify($update, $condition){
        return $this->where($condition)->update($update);
    }

	
    public function updateMemberVisitCount($member_id, $type = '+', $step = 1){
        return $this->updateMemberInfo($member_id,'visit_count', $type, $step);
    }
    public function updateMemberPersonalCount($member_id, $type = '+', $step = 1){
        return $this->updateMemberInfo($member_id,'personal_count', $type, $step);
    }
    public function updateMemberGoodsCount($member_id, $type = '+', $step = 1){
        return $this->updateMemberInfo($member_id,'goods_count', $type, $step);
    }
    private function updateMemberInfo($member_id, $column, $type, $step = 1){
        if(intval($member_id) <= 0) {
            return 0;
        }
        $param = array();
        $param['member_id'] = $member_id;
        $micro_member_info = self::getOne($param);
        $new_count = 0;
        if(empty($micro_member_info)) {
            $new_count = 1;
            $param[$column] = $step;
            $this->save($param);
        } else {
            $update = array();
            if($type != '-') {
                $update[$column] = array('exp',$column.'+'.$step);
                $new_count = $micro_member_info[$column] + $step;
            } else {
                if($micro_member_info[$column] > $step) {
                    $update[$column] = array('exp',$column.'-'.$step);
                    $new_count = $micro_member_info[$column] - $step;
                } else {
                    $update[$column] = 0;
                    $new_count = 0; 
                }
            }
            $this->modify($update,$param);
        }
        return $new_count;
    }

	
    public function drop($condition){
        return $this->where($condition)->delete();
    }
	
}
