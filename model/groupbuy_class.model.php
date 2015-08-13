<?php

defined('haipinlegou') or exit('Access Invalid!');
class groupbuy_classModel{

    const TABLE_NAME = 'groupbuy_class';
    const PK = 'class_id';

    
    private function getCondition($condition){
        $condition_str = '';
        if (!empty($condition['class_id'])){
        $condition_str .= "and class_id = '".$condition['class_id']."'";
        }
        if (!empty($condition['in_class_id'])){
        $condition_str .= "and class_id in (".$condition['in_class_id'].")";
        }
        if (!empty($condition['class_parent_id'])||$condition['class_parent_id'] == '0'){
        $condition_str .= "and class_parent_id = '".$condition['class_parent_id']."'";
        }
        if (!empty($condition['deep'])){
        $condition_str .= "and deep <= '".$condition['deep']."'";
        }
        return $condition_str;
    }

    
    public function getList($condition = '',$page = ''){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' '.self::PK.' desc ';
        return Db::select($param,$page);
    }

   
    public function getTreeList($condition='',$page='',$max_deep=1){

        $class_list = $this->getList($condition,$page);
        $tree_list = array();
        if(is_array($class_list)) {
            $tree_list = $this->_getTreeList($class_list,0,0,$max_deep);
        }
        return $tree_list;
    }

    private function _getTreeList($list,$parent_id,$deep=0,$max_deep) {

        $result = array();
        foreach($list as $node) {

            if($node['class_parent_id'] == $parent_id) {

                if($deep <= $max_deep) {
                    $temp = $this->_getTreeList($list,$node['class_id'],$deep+1,$max_deep);
                    if(!empty($temp)) {
                        $node['have_child'] = 1;
                    }
                    else {
                        $node['have_child'] = 0;
                    }
                    if($deep == $max_deep) {
                        $node['node'] = 1;
                    }
                    else {
                        $node['node'] = 0;
                    }

                    $node['deep'] = $deep;
                    $result[] = $node;
                    if(!empty($temp)) {
                        $result = array_merge($result,$temp);
                    }

                    unset($temp);
                }
            }
        }
        return $result;
    }

   
    public function getAllClassId($class_id_array) {

        $all_class_id_array = array();
        $class_list = $this->getList();
        foreach($class_id_array as $class_id) {
            $all_class_id_array[] = $class_id;
            foreach($class_list as $class) {
                if($class['class_parent_id'] == $class_id) {
                    $all_class_id_array[] = $class['class_id'];
                }
            }
        }
        return $all_class_id_array;
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
