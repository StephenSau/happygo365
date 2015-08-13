<?php 

defined('haipinlegou') or exit('Access Invalid!');
class complain_goodsModel{

	
	private function getCondition($condition){
		$condition_str = '' ;
        if(!empty($condition['complain_id'])) {
            $condition_str.= " and  complain_id = '{$condition['complain_id']}'";
        }
		return $condition_str;
    }

	
	public function saveComplainGoods($param){
	
		return Db::insert('complain_goods',$param);
	
	}
	
	
	public function updateComplainGoods($update_array, $where_array){
	
		$where = $this->getCondition($where_array) ;
		return Db::update('complain_goods',$update_array,$where) ;
    
    }
	
	

	public function dropComplainGoods($param){

		$where = $this->getCondition($param) ;
		return Db::delete('complain_goods', $where) ;
	
	}

	
	public function getComplainGoods($condition='',$page='') {

        $param = array() ;
        $param['table'] = 'complain_goods' ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' complain_goods_id desc ';
        return Db::select($param,$page);
	}

   
    public function getoneComplainGoods($complain_goods_id) {
        
        $param = array() ;
    	$param['table'] = 'complain_goods';
    	$param['field'] = 'complain_goods_id' ;
    	$param['value'] = intval($complain_goods_id);
    	return Db::getRow($param) ;

    }

}
