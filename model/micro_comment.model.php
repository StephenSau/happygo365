<?php

defined('haipinlegou') or exit('Access Invalid!');
class micro_commentModel extends Model{

    public function __construct(){
        parent::__construct('micro_comment');
    }

	
	public function getList($condition,$page='',$order='',$field='*'){
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
	}

	
	public function getListWithUserInfo($condition,$page='',$order='',$field='*'){
        $on = 'micro_comment.comment_member_id = member.member_id';
        $result = $this->table('micro_comment,member')->field($filed)->join('left')->on($on)->where($condition)->page($page)->order($order)->select();
        return $result;
	}



    
    public function getOne($condition){
        $result = $this->where($condition)->find();
        return $result;
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
	
	
    public function drop($condition){
        return $this->where($condition)->delete();
    }
	
}
