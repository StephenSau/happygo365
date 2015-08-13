<?php

defined('haipinlegou') or exit('Access Invalid!');

class pointprodModel extends Model {
	public function __construct(){
		parent::__construct();
	}
	
	public function addPointGoods($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('points_goods',$param);
		if($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	public function getPointProdList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'points_goods';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_goods.pgoods_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getPointProdListNew($field='*',$where='',$order='',$limit='',$page=''){
		if (empty($order)){
			$order = 'pgoods_sort asc';
		}
		$list = $this->table('points_goods')->field($field)->where($where)->order($order)->limit($limit)->page($page)->select();
		if (is_array($list) && count($list)>0){
			foreach ($list as $k=>$v){
				$v['pgoods_image'] = ATTACH_POINTPROD.DS.$v['pgoods_image'].'_small.'.get_image_type($v['pgoods_image']);
				$v['ex_state'] = $this->getPointProdExstate($v);
				$list[$k] = $v;
			}
		}
		return $list;
	}
	
	public function getPointProdInfo($condition,$field='*'){
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'points_goods';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$prod_info		= Db::select($array);
		return $prod_info[0];
	}
	
	public function getPointProdInfoNew($where = '',$field='*'){
		$prodinfo = $this->table('points_goods')->where($where)->find();
		if (!empty($prodinfo)){
			$prodinfo['pgoods_image_small'] = ATTACH_POINTPROD.DS.$prodinfo['pgoods_image'].'_small.'.get_image_type($prodinfo['pgoods_image']);
			$prodinfo['pgoods_image'] = ATTACH_POINTPROD.DS.$prodinfo['pgoods_image'];
			$prodinfo['ex_state'] = $this->getPointProdExstate($prodinfo);
		}
		return $prodinfo;
	}
	
	public function getPointProdExstate($prodinfo){
		$datetime = time();
		$ex_state = 'end';
		if ($prodinfo['pgoods_islimittime'] == 1){
			
			if ($prodinfo['pgoods_starttime']>$datetime && $prodinfo['pgoods_storage']>0){
				$ex_state = 'willbe';
			}
			
			if ($prodinfo['pgoods_starttime'] <= $datetime && $datetime < $prodinfo['pgoods_endtime'] && $prodinfo['pgoods_storage']>0){
				$ex_state = 'going';
			}
		}else {
			if ($prodinfo['pgoods_storage']>0){
				$ex_state = 'going';
			}
		}
		return $ex_state;
	}
	
	public function getPointProdExnum($prodinfo,$quantity){
		if ($quantity <= 0){
			$quantity = 1;
		}
		if ($prodinfo['pgoods_islimit'] == 1 && $prodinfo['pgoods_limitnum'] < $quantity ){
			$quantity = $prodinfo['pgoods_limitnum'];
		}
		if ($prodinfo['pgoods_storage'] < $quantity){
			$quantity = $prodinfo['pgoods_storage'];
		}
		return $quantity;
	}
	
	public function dropPointProdById($pg_id){
		if(empty($pg_id)) {
			return false;
		}
		$condition_str = ' 1=1 ';
		if (is_array($pg_id) && count($pg_id)>0){
			$pg_idStr = implode(',',$pg_id);
			$condition_str .= " and	pgoods_id in({$pg_idStr}) ";
		}else {
			$condition_str .= " and pgoods_id = '{$pg_id}' ";
		}
		$result = Db::delete('points_goods',$condition_str);
		if ($result){
			$upload_model = Model('upload');
			if (is_array($pg_id) && count($pg_id)>0){
				$pg_idStr = implode(',',$pg_id);
				$upload_list = $upload_model->getUploadList(array('upload_type_in' =>'5,6','item_id_in'=>$pg_idStr));
			}else {
				$upload_list = $upload_model->getUploadList(array('upload_type_in' =>'5,6','item_id'=>$pg_id));
			}			
			if (is_array($upload_list) && count($upload_list)>0){
				$upload_idarr = array();
				foreach ($upload_list as $v){
					@unlink(BasePath.DS.ATTACH_POINTPROD.DS.$v['file_name']);
					@unlink(BasePath.DS.ATTACH_POINTPROD.DS.$v['file_thumb']);
					$upload_idarr[] = $v['upload_id'];
				}
				$upload_model->dropUploadById($upload_idarr);
			}
		}
		return $result;
	}
	
	public function updatePointProd($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getCondition($condition);
		$result	= Db::update('points_goods',$param,$condition_str);
		return $result;
	}
	
	private function getCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['pgoods_name_like']) {
			$condition_sql	.= " and `points_goods`.pgoods_name like '%{$condition_array['pgoods_name_like']}%'";
		}
		if ($condition_array['pg_liststate']) {
			switch ($condition_array['pg_liststate']){
				case 'show':
					$condition_sql	.= " and `points_goods`.pgoods_show = 1 ";
					break;
				case 'nshow':
					$condition_sql	.= " and `points_goods`.pgoods_show = 0 ";
					break;
				case 'commend':
					$condition_sql	.= " and `points_goods`.pgoods_commend = 1 ";
					break;
				case 'forbid':
					$condition_sql	.= " and `points_goods`.pgoods_state = 1 ";
					break;
			}
		}
		if (isset($condition_array['pgoods_id_in'])) {
			if ($condition_array['pgoods_id_in'] == ''){
				$condition_sql	.= " and `points_goods`.pgoods_id in('') ";
			}else {
				$condition_sql	.= " and `points_goods`.pgoods_id in({$condition_array['pgoods_id_in']})";
			}
		}
		if (isset($condition_array['pgoods_id'])) {
			$condition_sql	.= " and `points_goods`.pgoods_id = '{$condition_array['pgoods_id']}'";
		}
		if (isset($condition_array['pgoods_show'])) {
			$condition_sql	.= " and `points_goods`.pgoods_show = '{$condition_array['pgoods_show']}'";
		}
		if (isset($condition_array['pgoods_state'])) {
			$condition_sql	.= " and `points_goods`.pgoods_state = '{$condition_array['pgoods_state']}'";
		}
		if (isset($condition_array['pgoods_commend'])) {
			$condition_sql	.= " and `points_goods`.pgoods_commend = '{$condition_array['pgoods_commend']}'";
		}
		return $condition_sql;
	}
}