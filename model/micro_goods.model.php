<?php

defined('haipinlegou') or exit('Access Invalid!');
class micro_goodsModel extends Model{

    const TABLE_NAME = 'micro_goods';
    const PK = 'commend_id';

    public function __construct(){
        parent::__construct('micro_goods');
    }

	
	public function getList($condition,$page=null,$order='',$field='*',$limit=''){
        $result = $this->table(self::TABLE_NAME)->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $result;
	}

	
	public function getListWithUserInfo($condition,$page=null,$order='',$field='*',$limit=''){
        $on = 'micro_goods.commend_member_id = member.member_id';
        $result = $this->table('micro_goods,member')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $result;
	}


    
    public function getOne($param){
        $result = $this->table(self::TABLE_NAME)->where($param)->find();
        return $result;
    }

    
    public function getOneWithUserInfo($param){
        $on = 'micro_goods.commend_member_id = member.member_id';
        $result = $this->table('micro_goods,member')->join('left')->on($on)->where($param)->find();
        return $result;
    }

	
	public function isExist($param) {
        $result = $this->getOne($param);
        if(empty($result)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
	}

	
    public function save($param){
        return $this->table(self::TABLE_NAME)->insert($param);	
    }
	
	
    public function modify($update_array, $where_array){
        return $this->table(self::TABLE_NAME)->where($where_array)->update($update_array);
    }
	
	
    public function drop($param){
        return $this->table(self::TABLE_NAME)->where($param)->delete();
    }
	
}
