<?php

defined('haipinlegou') or exit('Access Invalid!');
class micro_likeModel extends Model{

    public function __construct(){
        parent::__construct('micro_like');
    }

	
	public function getList($condition,$page=null,$order='',$field='*'){
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
	}

   
    public function getGoodsList($condition,$page=null,$order='',$field='*') {
        $on = 'micro_goods.commend_id = micro_like.like_object_id,micro_goods.commend_member_id=member.member_id';
        $result = $this->table('micro_goods,micro_like,member')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    
    public function getPersonalList($condition,$page=null,$order='',$field='*') {
        $on = 'micro_personal.personal_id = micro_like.like_object_id,micro_personal.commend_member_id=member.member_id';
        $result = $this->table('micro_personal,micro_like,member')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    
    public function getStoreList($condition,$page=null,$order='',$field='*') {
        $on = 'micro_store.microshop_store_id = micro_like.like_object_id,micro_store.shop_store_id=store.store_id';
        $result = $this->table('micro_store,micro_like,store')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->select();
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
