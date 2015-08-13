<?php

defined('haipinlegou') or exit('Access Invalid!');
class micro_storeModel extends Model{

    const TABLE_NAME = 'micro_store';
    const PK = 'store_id';

    public function __construct(){
        parent::__construct('micro_store');
    }

	
    public function getList($condition,$page=null,$order='',$field='*',$limit=''){
        $result = $this->table(self::TABLE_NAME)->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $result;
	}


	
    public function getListWithStoreInfo($condition,$page=null,$order='',$field='*',$limit=''){
        $condition['store_state'] = 1;
        $on = 'micro_store.shop_store_id = store.store_id';
        $result = $this->table('micro_store,store')->field($field)->join('left')->on($on)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $result;
	}

    
    public function getOne($param){
        $result = $this->table(self::TABLE_NAME)->where($param)->find();
        return $result;
    }

  
    public function getOneWithStoreInfo($param){
        $param['store_state'] = 1;
        $on = 'micro_store.shop_store_id= store.store_id';
        $result = $this->table('micro_store,store')->join('left')->on($on)->where($param)->find();
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

    
    public function saveAll($param){
        return $this->table(self::TABLE_NAME)->insertAll($param);	
    }
	
	
    public function modify($update_array, $where_array){
        return $this->table(self::TABLE_NAME)->where($where_array)->update($update_array);
    }
	
	
    public function drop($param){
        return $this->table(self::TABLE_NAME)->where($param)->delete();
    }
	
}
