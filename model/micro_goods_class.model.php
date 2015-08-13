<?php

defined('haipinlegou') or exit('Access Invalid!');
class micro_goods_classModel extends Model{

    public function __construct(){

        parent::__construct('micro_goods_class');

    }

	
	public function getList($condition,$page=null,$order='',$field='*'){

        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;

	}

    
    public function getOne($condition,$order=''){

        $result = $this->where($condition)->order($order)->find();
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
	
	
    public function modify($update, $condition){

        return $this->where($condition)->update($update);

    }
	
	
    public function drop($condition){

        return $this->where($condition)->delete();

    }
	
}

