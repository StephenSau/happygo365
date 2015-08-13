<?php

defined('haipinlegou') or exit('Access Invalid!');
class groupbuy_price_rangeModel{

  
    const TABLE_NAME = 'groupbuy_price_range';
    const PK = 'range_id';

   
    private function getCondition($condition){
        $condition_str = '';
        if (!empty($condition['range_id'])){
            $condition_str .= " AND range_id = '".$condition['range_id']."'";
        }
        if (!empty($condition['in_range_id'])){
            $condition_str .= " AND range_id in (". $condition['in_range_id'] .")";
        }
        return $condition_str;
    }

   
    public function getList($condition = array(), $page = ''){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' '.self::PK.' desc ';
        return Db::select($param,$page);
    }


    
    public function getOne($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = self::TABLE_NAME;
            $param['field'] = self::PK;
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }

   
    public function isExist($condition='') {

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $list = Db::select($param);
        if(empty($list)) {
            return false;
        }
        else {
            return true;
        }
    }

    
    public function save($param){

        return Db::insert(self::TABLE_NAME,$param) ;

    }

    
    public function update($update_array, $where_array){

        $where = $this->getCondition($where_array) ;
        return Db::update(self::TABLE_NAME,$update_array,$where) ;

    }

    
    public function drop($param){

        $where = $this->getCondition($param) ;
        return Db::delete(self::TABLE_NAME, $where) ;
    }

}
