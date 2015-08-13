<?php

defined('haipinlegou') or exit('Access Invalid!');
class groupbuy_areaModel{

    const TABLE_NAME = 'groupbuy_area';
    const PK = 'area_id';

	
	private function getCondition($condition){
		$condition_str = '';
		if (!empty($condition['area_parent_id'])){
			$condition_str .= "and area_parent_id = '".$condition['area_parent_id']."'";
		}
		if (!empty($condition['area_id'])){
			$condition_str .= "and area_id = '".$condition['area_id']."'";
		}
		if (!empty($condition['in_area_id'])){
			$condition_str .= "and area_id in (". $condition['in_area_id'] .")";
		}
		return $condition_str;
	}

	
	public function getList($condition='',$page=''){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' '.self::PK.' desc ';
        return Db::select($param,$page);
	}

   
    public function getTreeList($condition='',$page='',$max_deep=2){

        $area_list = $this->getList($condition,$page);
        $tree_list = array();
        if(is_array($area_list)) {
            $tree_list = $this->_getTreeList($area_list,0,0,$max_deep);
        }
        return $tree_list;
    }

    private function _getTreeList($list,$parent_id,$deep=0,$max_deep) {

        $result = array();
        foreach($list as $node) {
            if($node['area_parent_id'] == $parent_id) {
                if($deep <= $max_deep) {
                    $temp = $this->_getTreeList($list,$node['area_id'],$deep+1,$max_deep);
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

  
    public function getAllAreaId($area_id_array) {

        $all_area_id_array = array();
        $area_list = $this->getList();
        foreach($area_id_array as $area_id) {
            $all_area_id_array[] = $area_id;
            foreach($area_list as $area) {
                if($area['area_parent_id'] == $area_id) {
                    $all_area_id_array[] = $area['area_id'];
                }
            }
        }
        return $all_area_id_array;
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
