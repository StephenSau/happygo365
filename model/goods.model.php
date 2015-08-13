<?php



defined('haipinlegou') or exit('Access Invalid!');



class goodsModel extends Model {

 	public function __construct(){

		parent::__construct('goods');

	}
        
	

	public function countGoods($param,$type = ''){

		if (empty($param)) {

			return false;

		}
		$condition_str = $this->getCondition($param);

		$array	= array();

		switch ($type){

			case 'store':

				$array['table'] = 'goods,store';

				$array['join_type'] = 'LEFT JOIN';

				$array['join_on'] = array(

					'goods.store_id=store.store_id'

				);

				break;

			default :

				$array['table'] = 'goods';

				break;

		}

		$array['where'] = $condition_str;

		$array['field'] = 'count(*) as count';

		$goods_array	= Db::select($array);

		return $goods_array[0]["count"];

	}

	

	public function countGoodsByClass() {

		$array['table'] = 'goods';

		$array['field']	= 'count(goods_id) as count,gc_id';

		$array['group'] = 'gc_id';

		$goods_array	= Db::select($array);

		return $goods_array;

	}

	

	public function countStorageByGoodsSpec($param, $field) {

		$array	= array();

		$array['table']		= 'goods_spec';

		$array['group']		= 'goods_id';

		$array['where']		= $this->getCondition($param);

		$array['field']		= $field;

		$goods_array		= Db::select($array);

		return $goods_array;

	}

	

	public function saveGoods($param) {
        if(empty($param)) {

			return false;

		}

		$goods_array	= array();

		$goods_array['goods_name']				= $param['goods_name'];
		$goods_array['foreign_language']				= $param['foreign_language'];//商品外文

                //商品国家             

                $goods_array['country']				= $param['country'];

                //商品供应商

                $goods_array['provider']				= $param['provider'];                

		$goods_array['gc_id']					= $param['gc_id'];

		$goods_array['gc_name']					= $param['gc_name'];

		$goods_array['store_id']				= $_SESSION['store_id'];

		$goods_array['spec_open']				= $param['spec_open'];

		$goods_array['brand_id']				= $param['brand_id'];

		$goods_array['goods_image']				= $param['goods_image'];

		$goods_array['goods_image_more']		= $param['goods_image_more'];

		$goods_array['goods_state']				= $param['goods_state'];

		$goods_array['goods_store_price']		= $param['goods_store_price'];

		$goods_array['goods_store_price_interval']= $param['goods_store_price_interval'];

		$goods_array['goods_serial']			= $param['goods_serial'];

		$goods_array['goods_show']				= $param['goods_show'];

		$goods_array['goods_state'] 			= 0;

		$goods_array['goods_commend']			= $param['goods_commend'];

		$goods_array['goods_add_time']			= time();

		$goods_array['goods_body']				= $param['goods_body'];

		$goods_array['goods_store_state']		= $param['goods_store_state'] == 1 ? 1 : '0' ;

		$goods_array['goods_keywords'] 			= $param['goods_keywords'];

		$goods_array['goods_description']		= $param['goods_description'];

		

		$goods_array['goods_transfee_charge']	= $param['goods_transfee_charge'];

		$goods_array['type_id']					= $param['type_id'];

		$goods_array['goods_spec']				= serialize($param['goods_spec']);

		$goods_array['goods_attr']				= serialize($param['goods_attr']);

		$goods_array['spec_name']				= serialize($param['spec_name']);

		

		$goods_array['goods_starttime']			= $param['goods_starttime'];

		$goods_array['goods_endtime']			= $param['goods_endtime'];

		$goods_array['goods_form']				= $param['goods_form'];

		$goods_array['py_price']				= $param['py_price'];

		$goods_array['kd_price']				= $param['kd_price'];

		$goods_array['es_price']				= $param['es_price'];

		$goods_array['transport_id']			= $param['transport_id'];



		$goods_array['city_id']					= $param['city_id'];

		$goods_array['province_id']				= $param['province_id'];

		$goods_array['product_num']				= $param['product_num'];

		$goods_array['gross_weight']				= $param['gross_weight'];

		$goods_array['net_weight']				= $param['net_weight'];

		$goods_array['goods_custom_num']				= $param['goods_custom_num'];

		$goods_array['declaration_unit']				= $param['declaration_unit'];

		$goods_array['country']				= $param['country'];

		$goods_array['provider']				= $param['provider'];
        
        $goods_array['tax_num']				= $param['tax_num'];
        $goods_array['tax_money']				= $param['tax_money'];
        $goods_array['goods_mail']				= $param['goods_mail'];
        $goods_array['market_price']				= $param['market_price'];//市场价
        $goods_array['d_specvalue']				= $param['d_specvalue'];//市场价

		$result	= Db::insert('goods',$goods_array);

		return $result;

	}

		

	public function getGoods($param,$page = '',$field='*',$type = 'simple',$extend = null) {
      
        
		$condition_str = $this->getCondition($param);

		$array = array();

		$array['field'] = $field;
                 
		switch ($type){

            case 'groupbuy_goods_info':

				$array['table'] = 'goods,goods_spec';
                
                $array['join_type'] = empty($param['join_type'])?'LEFT JOIN':$param['join_type'];

				$array['join_on'] = array(

					'goods.goods_id=goods_spec.goods_id'

				);

				break;

           case 'groupbuy_goods_info_spec':

				$array['table'] = 'goods,goods_spec';

				$array['join_type'] = empty($param['join_type'])?'LEFT JOIN':$param['join_type'];

				$array['join_on'] = array(

					'goods.spec_id=goods_spec.spec_id'

				);

				break;  

			case 'store':

				$array['table'] = 'goods,store';

				$array['join_type'] = 'INNER JOIN';

				$array['join_on'] = array(

					'goods.store_id=store.store_id'

				);

				if (is_array($extend)){

					$array = array_merge($array,$extend);

				}

				break;

			case 'goods':

				$array['table'] = 'goods';

				if (is_array($extend)){

					$array = array_merge($array,$extend);

				}

				break;

			case 'goods_spec':

				$array['table'] = 'goods_spec';

				break;

			case 'brand':

				$array['table'] = 'goods,brand,goods_class';

				$array['field']	= '*';

				$array['join_type'] = 'LEFT JOIN';

				$array['join_on'] = array(

					'goods.brand_id=brand.brand_id',

					'goods.gc_id=goods_class.gc_id'

				);

				break;

			case 'stc':

				$array['table'] = 'goods,store_class_goods';

				$array['field'] = $field=='*' ? 'DISTINCT goods.*' : 'DISTINCT '.$field;

				$array['count'] = 'count(DISTINCT goods.goods_id)';

				$array['join_type'] = 'LEFT JOIN';

				$array['join_on'] = array(

					'goods.goods_id=store_class_goods.goods_id'

				);

				break;
            case 'guan':

                $array['table'] = 'goods,store_class_goods,goods_class';

                $array['field'] = $field=='*' ? 'DISTINCT goods.*' : 'DISTINCT '.$field;

                $array['count'] = 'count(DISTINCT goods.goods_id)';

                $array['join_type'] = 'LEFT JOIN';

                $array['join_on'] = array(

                    'goods.goods_id=store_class_goods.goods_id',
                    'goods.gc_id=goods_class.gc_id'
                );

                break;

			default:

				$array['table'] = 'goods,goods_spec';

				$array['join_type'] = empty($param['join_type'])?'LEFT JOIN':$param['join_type'];

				$array['join_on'] = array(

					'goods.spec_id=goods_spec.spec_id'

				);

		}

		$array['where'] = $condition_str;
               // $array['where'] = "goods.store_id= '19' and goods.goods_commend= '1' and goods.goods_show= '1'";

		$array['order'] = $param['order'] ? $param['order'] : 'goods.goods_id desc';
              

		$array['limit'] = $param['limit'];

		$goods_array	= Db::select($array,$page);
               

		return $goods_array;

	}

	

	public function listGoods($param,$page = '',$field='*') {

		if($param['stc_id'] != 0 || trim($param['stc_id_in'])) {

			$param	= $this->sublistGoods($param);

			if(empty($param)) return array();

		}

		$condition_str = $this->getCondition($param);

		$array	= array();

		$array['table']	= 'goods,goods_spec';

		$array['where']	= $condition_str;

		$array['field']	= $field;

		$array['order'] = $param['order'] ? $param['order'] : 'goods.goods_id desc';

		$array['join_type']= 'LEFT JOIN';

		$array['join_on']= array('goods.spec_id=goods_spec.spec_id');

		$array['limit'] = $param['limit'];

		$list_goods		= Db::select($array,$page);

		return $list_goods;

	}

		

	public function getGoodsForCache($param,$field='*') {

		$condition_str = $this->getCondition($param);

		$array	= array();

		$array['table']	= 'goods';

		$array['where']	= $condition_str;

		$array['field']	= $field;

		$array['order'] = $param['order'] ? $param['order'] : 'goods.goods_id desc';

		$array['limit']	= $param['limit'];

		$list_goods		= Db::select($array);

		return $list_goods;

	}



    

    public function getCommenGoods($param,$page = '',$field = '*') {

        $param['goods_commend'] = 1;

        $param['goods_state'] = 0;

        $param['goods_show'] = 1;

        $array = array();

        $array['table'] = 'goods';

        $array['where'] = $this->getCondition($param);

        $array['field'] = $field;

        $array['limit'] = $param['limit'];

        return Db::select($array,$page);

    }



	

	private function sublistGoods($param) {

		$condition_str = $this->getCondition($param);

		$array	= array();

		$array['table'] = 'store_class_goods,goods';

		$array['where']	= $condition_str;

		$array['field']	= 'DISTINCT goods.goods_id';

		$array['join_type']= 'INNER JOIN';

		$array['join_on']= array('store_class_goods.goods_id=goods.goods_id');

		$array['order'] = 'goods.goods_id desc';

		$sub_list_goods	= Db::select($array);

		$sub_goods_id	= '';

		if(is_array($sub_list_goods) and !empty($sub_list_goods)) {

			foreach ($sub_list_goods as $val) {

				$sub_goods_id .= $val['goods_id'].',';

			}

			$sub_goods_id	= substr($sub_goods_id,0,-1);

		}

		if($sub_goods_id == '') return '';

		

		unset($param['stc_id']);

		unset($param['stc_id_in']);

		$param['sub_goods_id'] = $sub_goods_id;

		return $param;

	}

	

	public function getGoodsList($param,$page = '',$field='*') {

		$condition_str = $this->getCondition($param);

		$array	= array();

		$array['table']	= 'goods,brand,store';

		$array['where']	= $condition_str;

		$array['field']	= $field;

		$array['order'] = $param['order'] ? $param['order'] : 'goods.goods_id desc';

		$array['join_type']= 'LEFT JOIN';

		$array['join_on']= array('goods.brand_id=brand.brand_id','goods.store_id=store.store_id');

		$array['limit'] = $param['limit'];

		$list_goods		= Db::select($array,$page);

		return $list_goods;

	}



    

	public function getOne($id,$fields = '*'){

		if (intval($id) > 0){

			$param = array();

			$param['table'] = 'goods';

			$param['field'] = 'goods_id';

			$param['value'] = intval($id);

			$result = Db::getRow($param,$fields);

			return $result;

		}else {

			return false;

		}

	}



	 	

	public function updateGoods($param,$goods_id) {

		if(empty($param)) {

			return false;

		}

		$update		= false;

		if(is_array($goods_id))$goods_id	= implode(',',$goods_id);

		$condition_str	= "WHERE goods_id in(".$goods_id.")";
		$update		= Db::update('goods',$param,$condition_str);
		return $update;

	}

		

	public function updateGoodsWhere($param, $condition_array) {

		if(empty($param)) {

			return false;

		}

		$update		= false;

		$condition_str	= $this->getCondition($condition_array);

		$update		= Db::update('goods',$param,$condition_str);

		return $update;

	}

	

	public function updateSpecGoods($condition,$param){

		$goods_id = $condition['spec_goods_id'];

		if (intval($goods_id) < 1){

			return false;

		}

		$condition_str = $this->getCondition($condition);

		$where = $condition_str;

		if (is_array($param)){

			$result = Db::update('goods_spec',$param,$where);

			return result;

		}else {

			return false;

		}

	}

		

	public function updateGoodsAllUser($param,$goods_id){

		if(empty($param)) {

			return false;

		}

		$update		= false;

		if(is_array($goods_id)){

			$goods_id = implode(',',$goods_id);

			$condition_str	= " WHERE goods_id in(".$goods_id.")";

		}else{

			$condition_str	= " WHERE goods_id = '$goods_id'";

		}

		$update	= Db::update('goods',$param,$condition_str);

		if ($update){

			return true;

		}

		return false;

	}

	

	public function dropGoodsByStore($store_id) {

		if(empty($store_id)) {

			return false;

		}

		$where = " store_id = '". intval($store_id) ."'";

		$result = Db::delete('goods',$where);

		return $result;

	}

	

	public function dropGoods($goods_id) {

		if(empty($goods_id)) {

			return false;

		}

		$goods_id_array	= explode(',',$goods_id);

		if(is_array($goods_id_array) and !empty($goods_id_array)) {

			foreach ($goods_id_array as $val) {

				$goods_id_one = intval($val);

				if($goods_id_one <= 0){

					continue;

				}

				$goods_array	= array();

				$goods_array	= Db::select(array('table'=>'goods','field'=>'*','where'=>"where goods_id='{$goods_id_one}'"));

				$goods_array	= $goods_array[0];

				

			

				$col_img	= unserialize($goods_array['goods_col_img']);

				if(is_array($col_img) && !empty($col_img)){

					$col_img	= array_unique($col_img);

					foreach ($col_img as $v){

						@unlink(BasePath.DS.ATTACH_SPEC.DS.$goods_array['store_id'].DS.$v);

						@unlink(BasePath.DS.ATTACH_SPEC.DS.$goods_array['store_id'].DS.str_replace('_tiny', '_mid', $v));

					}

				}

				

				$del_state		= Db::delete('goods','where goods_id='.$goods_array['goods_id']);

				if($del_state) {

					$image_more	= Db::select(array('table'=>'upload','field'=>'*','where'=>' where item_id='.$goods_array['goods_id']." and upload_type in ('2','3')"));



					Db::delete('upload','where item_id='.$goods_array['goods_id']." and upload_type in ('2','3')");



					Db::delete('goods_spec','where goods_id='.$goods_array['goods_id']);

					

					Db::delete('goods_attr_index','where goods_id='.$goods_array['goods_id']);

					

					Db::delete('goods_spec_index','where goods_id='.$goods_array['goods_id']);



					Db::delete('store_class_goods','where goods_id='.$goods_array['goods_id']);

					

					Db::delete('recommend_goods','where goods_id='.$goods_array['goods_id']);

					

					Db::delete('favorites','where fav_type="goods" and fav_id='.$goods_array['goods_id']);

					

					Db::delete('p_bundling_goods','where goods_id='.$goods_array['goods_id']);

				}

			}

		}

		return true;

	}

	 

	public function getStoreClassGoods($goods_id) {

		$class_list		= array();

		if(is_array($goods_id))$goods_id	= implode(',',$goods_id);

		$class_list		= Db::select(array('table'=>'store_class_goods','where'=>'where goods_id in ('.$goods_id.')','field'=>'*'));

		return $class_list;

	}

	 

	public function getSpecGoods($goods_id) {

		$spec_array		= array();

		$spec_array		= Db::select(array('table'=>'goods_spec','where'=>"where goods_id='$goods_id'",'field'=>'*'));

		return $spec_array;

	}

	

	public function getSpecGoodsWhere($condition,$fields = 'spec_id') {

		$spec_array		= array();

		$spec_array		= Db::select(array('table'=>'goods_spec','where'=>$this->getCondition($condition),'field'=>$fields,'order'=>'spec_id'));
               
		return $spec_array[0];

	}

	

	public function saveSpecGoods($spec,$goods_id,$spec_name='') {
            
		if (empty($spec)) {

			return false;

		}

		$default_spec_id = '0';

		foreach ($spec as $val) {

			$insert_array	= array();

			$insert_array['goods_id']			= $goods_id;

			$insert_array['spec_name']			= serialize($spec_name);

			$insert_array['spec_goods_spec']	= serialize($val['sp_value']);

			$insert_array['spec_goods_price']	= trim($val['price']);

			$insert_array['spec_goods_storage']	= intval($val['stock']);//库存
			$insert_array['store_base']	= intval($val['store_base']);//库存基数

			$insert_array['spec_goods_serial']	= trim($val['sku']);
                        
                        $insert_array['goods_tax']	= trim($val['goods_tax']);
                        
                        $insert_array['goods_tax_num']	= trim($val['goods_tax_num']);
                        
                        $insert_array['spec_market_price']	= trim($val['market_price']);
                        
                        $insert_array['spec_d_specvalue']	= trim($val['spec_d_specvalue']);
                       
			$insert_id = Db::insert('goods_spec',$insert_array);
                        
			if($default_spec_id == '0') {

				$default_spec_id = $insert_id;

			}

		}
                

		return $default_spec_id;

	}

	 

	public function updateSpecStorageGoods($param,$spec_id) {

		if(empty($param)) {

			return false;

		}

		$update		= false;

		if(is_array($spec_id))$spec_id	= implode(',',$spec_id);

		

		$condition_str	= "WHERE spec_id in(".$spec_id.")";

		$update		= Db::update('goods_spec',$param,$condition_str);

		return $update;

	}

	 

	public function saveStoreClassGoods($param,$goods_id) {

		if(empty($param)) {

			return false;

		}

		if(is_array($param)){

			foreach ($param as $val) {

				if(intval($val) == 0) {

					continue;

				}

				$insert_array	= array();

				$insert_array['stc_id']	= $val;

				$insert_array['goods_id']= $goods_id;

	

				Db::insert('store_class_goods',$insert_array);

			}

		}

	}

	 

	public function dropSpecGoods($goods_id) {

		if(is_array($goods_id))$goods_id	= implode(',',$goods_id);

		Db::delete('goods_spec','where goods_id in('.$goods_id.')');

	}

	

	public function dropSpecGoodsWhere($spec_id, $goods_id) {

		$where = '';

		if(is_array($spec_id))$spec_id	= implode(',',$spec_id);

		if(count($spec_id) > 0){

			$where = " and goods_id='".$goods_id."' and spec_id not in (".$spec_id.")";

		}

		Db::delete('goods_spec',$where);

	}

	

	public function dropStoreClassGoods($goods_id) {

		if(is_array($goods_id))$goods_id	= implode(',',$goods_id);

		Db::delete('store_class_goods','where goods_id in('.$goods_id.')');

	}

		

	public function getListImageGoods($param,$field='*') {

		if(empty($param)) {

			return false;

		}

		$condition_str	= $this->getCondition($param);

		$array	= array();

		$array['table']		= 'upload';

		$array['where']		= $condition_str;

		$array['field']		= $field;

		$list_image			= Db::select($array);

		return $list_image;

	}

	 

	public function dropImageGoods($param) {

		if(empty($param)) {

			return false;

		}

		$condition_str	= $this->getCondition($param);

		$image_info		= Db::select(array('table'=>'upload','where'=>$condition_str,'field'=>'*'));

		$state = Db::delete('upload',$condition_str);

		return $state;

	}

	 

	public function checkGoods($param,$field='*') {

		$goods_info		= array();

		$condition_str	= $this->getCondition($param);

		$array			= array();

		$array['table']	= 'goods_spec,goods';

		$array['where']	= $condition_str;

		$array['field']	= $field;

		$array['join_type']= 'INNER JOIN';

		$array['join_on']= array('goods_spec.goods_id=goods.goods_id');

		$goods_info		= Db::select($array);

		return $goods_info[0];

	}

	

	public function recGroup($id,$is_rec=true){

		return Db::update('goods_group',array('recommended'=>$is_rec?'1':'0'),'where group_id in('.$id.')');

	}

	

	 public function updateGoodsStoreStateByStoreId($store_id, $state = 'open'){

		if (intval($store_id) > 0){

			$state = $state == 'open' ? 1 : 0;

			return Db::update('goods',array('goods_show'=>$state),"where store_id = '$store_id'");

		} else {

			return false;

		}

	 }

	 

	private function _getRecursiveClass($class_id){

		$id_array = explode(',', $class_id);

		$temp_list = Db::select(array('table'=>'goods_class','where'=>'gc_parent_id>0 and gc_show=1','field'=>'gc_id,gc_parent_id','order'=>'gc_parent_id asc'));

		if(is_array($temp_list) && !empty($temp_list)) {

			foreach ($temp_list as $key => $val) {

				$gc_parent_id	= $val['gc_parent_id'];

				if(in_array($gc_parent_id,$id_array)) $id_array[] = $val['gc_id'];

			}

		}

		$id_array = array_unique($id_array);

		return "'".implode("','",$id_array)."'";

	}

	

	private function getCondition($condition_array){

		$condition_sql = '';

		if ($condition_array['price'] != ''){

			if (is_array($condition_array['price'])){

				if($condition_array['price'][0] == 0 && $condition_array['price'][1] != 0){

					$condition_sql	.= " and goods.goods_store_price <= '{$condition_array['price'][1]}'";

				}

			    if($condition_array['price'][0] != 0 && $condition_array['price'][1] == 0){

					$condition_sql	.= " and goods.goods_store_price >= '{$condition_array['price'][0]}'";

				}

			    if($condition_array['price'][0] != 0 && $condition_array['price'][1] != 0){

					$condition_sql	.= " and goods.goods_store_price >= '{$condition_array['price'][0]}'";

					$condition_sql	.= " and goods.goods_store_price <= '{$condition_array['price'][1]}'";

				}			

			}else {

				$condition_sql	.= " and goods.goods_store_price = '{$condition_array['price']}'";

			}

		}

		if ($condition_array['area_id'] != '') {

			$condition_sql	.= " and `store`.area_id= '{$condition_array['area_id']}'";

		}

		if ($condition_array['goods_group.recommended'] != '') {

			$condition_sql	.= " and `goods_group`.recommended= '{$condition_array['goods_group.recommended']}'";

		}

		if ($condition_array['goods_spec.goods_id'] != '') {

			$condition_sql	.= " and `goods_spec`.goods_id= '{$condition_array['goods_spec.goods_id']}'";

		}

		if ($condition_array['group_id'] != '') {

			$condition_sql	.= " and `goods_group`.group_id= '{$condition_array['group_id']}'";

		}

		if ($condition_array['goods_image']){

			$condition_sql	.= " and `goods`.goods_image = '".$condition_array['goods_image']."'";

		}

		if ($condition_array['group_name'] != '') {

			$condition_sql	.= " and `goods_group`.group_name like '%".$condition_array['group_name']."%'";

		}

		if ($condition_array['state'] != '') {

			$condition_sql	.= ' and goods_group.state IN ('.$condition_array['state'].')';

		}

		if ($condition_array['published'] != ''){

			$condition_sql	.= ' and goods_group.published IN ('.$condition_array['published'].')';

		}

		if ($condition_array['like_store_name'] != ''){

			$condition_sql	.= " and store.store_name like '%".$condition_array['like_store_name']."%'";

		}

		if($condition_array['image_store_id'] != '') {

			$condition_sql	.= " and store_id = '{$condition_array['image_store_id']}' and item_id='{$condition_array['item_id']}' and upload_type='{$condition_array['image_type']}'";

		}

		if ($condition_array['upload_id'] != '') {

			$condition_sql	.= " and upload_id= '{$condition_array['upload_id']}'";

		}

		if ($condition_array['no_store_id'] != ''){

			$condition_sql	.= " and goods.store_id <> {$condition_array['no_store_id']} ";

		}

		if ($condition_array['lt_goods_endtime'] != ''){						

			$condition_sql	.= " and goods.goods_endtime >".$condition_array['lt_goods_endtime'];

		}

		if ($condition_array['gt_goods_endtime'] != ''){						

			$condition_sql	.= " and goods.goods_endtime <".$condition_array['gt_goods_endtime'];

		}

		if ($condition_array['gt_goods_starttime'] != ''){						

			$condition_sql	.= " and goods.goods_starttime <".$condition_array['gt_goods_starttime'];

		}

		if ($condition_array['store_id'] != '') {

			$condition_sql	.= " and goods.store_id= '{$condition_array['store_id']}'";

		}

		if ($condition_array['store_id_in'] != '') {

			$condition_sql	.= " and goods.store_id in('{$condition_array['store_id_in']}')";

		}

		if ($condition_array['goods_group.store_id'] != '') {

			$condition_sql	.= " and `goods_group`.store_id= '{$condition_array['goods_group.store_id']}'";

		}

		if($condition_array['goods_id'] != '') {

			$condition_sql	.= " and `goods`.goods_id= '{$condition_array['goods_id']}'";

		}

		if(isset($condition_array['goods_id_in'])) {

			if ($condition_array['goods_id_in'] == ''){

				$condition_sql	.= " and `goods`.goods_id in ('') ";

			}else {

				$condition_sql	.= " and `goods`.goods_id in({$condition_array['goods_id_in']})";

			}

		}

		if($condition_array['group_id'] != '') {

			$condition_sql	.= " and `goods_group`.group_id= '{$condition_array['group_id']}'";

		}

		if ($condition_array['goods_commend'] != '') {

			$condition_sql	.= " and `goods`.goods_commend= '{$condition_array['goods_commend']}'";

		}

		if ($condition_array['goods_show'] != '') {
                

			$condition_sql	.= " and goods.goods_show= '{$condition_array['goods_show']}'";

		}

		if ($condition_array['goods_state'] == '0' || $condition_array['goods_state'] == '1') {

			$condition_sql	.= " and goods.goods_state= '{$condition_array['goods_state']}'";

		}

		if($condition_array['stc_id'] != 0) {

			$condition_sql	.= " and store_class_goods.stc_id = '{$condition_array['stc_id']}'";

		}

		if(isset($condition_array['stc_id_in'])) {

			if ($condition_array['stc_id_in'] == ''){

				$condition_sql	.= " and store_class_goods.stc_id in ('') ";

			}else{

				$condition_sql	.= " and store_class_goods.stc_id in ({$condition_array['stc_id_in']})";

			}

		}

		if($condition_array['goods_id_diff'] != 0) {

			$condition_sql  .= " and `goods`.goods_id<> '{$condition_array['goods_id_diff']}'";

		}

		if($condition_array['sub_goods_id'] != '') {

			$condition_sql	.= " and goods.goods_id IN (".$condition_array['sub_goods_id'].")";

		}

		if($condition_array['keyword'] != '') {

			$condition_sql	.= " and goods.goods_name LIKE '%".$condition_array['keyword']."%'";
                    
                       

		}
                
                
		if($condition_array['keyword2'] != '') {

			
                    
                        $condition_sql	.= $condition_array['keyword2'];

		}

		if($condition_array['goods_name'] != '') {

			$condition_sql	.= " and goods_name LIKE '%".$condition_array['goods_name']."%'";

		}

		if($condition_array['like_group_name']!=''){

			$condition_sql	.= " and goods_group.group_name like '%".$condition_array['like_group_name']."%'";

		}

		if($condition_array['brand_id'] != '') {

			$condition_sql	.= " and goods.brand_id = '{$condition_array['brand_id']}'";

		}

		if($condition_array['gc_id'] != '') {

			$condition_sql	.= " and `goods`.gc_id IN (".$this->_getRecursiveClass($condition_array['gc_id']).")";

		}

		if(isset($condition_array['gc_id_in'])) {

			if ($condition_array['gc_id_in'] != ''){

				$condition_sql	.= " and `goods`.gc_id IN ({$condition_array['gc_id_in']})";

			}else{

				$condition_sql	.= " and `goods`.gc_id IN ('')";

			}

		}

		if ($condition_array['spec_goods_id'] != '') {

			$condition_sql	.= " and goods_spec.goods_id= '{$condition_array['spec_goods_id']}'";

		}

		if ($condition_array['in_spec_goods_id'] != '') {

			$condition_sql	.= " and goods_spec.goods_id in (".$condition_array['in_spec_goods_id'].")";

		}

		if(isset($condition_array['spec_id_in'])) {

			if ($condition_array['spec_id_in'] == ''){

				$condition_sql	.= " and goods_spec.spec_id in('')";

			}else {

				$condition_sql	.= " and goods_spec.spec_id in ({$condition_array['spec_id_in']})";

			}

		}

		if($condition_array['spec_id'] != '') {

			$condition_sql	.= " and goods_spec.spec_id= '{$condition_array['spec_id']}'";

		}

		if($condition_array['no_goods_id'] != ''){

			$condition_sql	.= " and goods.goods_id not in(".$condition_array['no_goods_id'].")";

		}

		if($condition_array['no_group_id'] != ''){

			$condition_sql	.= " and goods_group.group_id not in(".$condition_array['no_group_id'].")";

		}

		if($condition_array['spec_storage_enough'] == 'yes'){

			$condition_sql	.= " and goods_spec.spec_goods_storage > 0";

		}



		if(isset($condition_array['goods_isztc'])){

			if ($condition_array['goods_isztc'] == 1){

				$condition_sql	.= " and goods.goods_isztc = 1 ";

			}else {

				$condition_sql	.= " and goods.goods_isztc = 0 ";

			}

		}

		if($condition_array['goods_ztcstate']){

			$condition_sql	.= " and goods.goods_ztcstate = '{$condition_array['goods_ztcstate']}' ";

		}

		if(isset($condition_array['goods_ztcopen'])){

			$ztc_dayprod = intval($GLOBALS['setting_config']['ztc_dayprod']);

			$datetime = date('Y-m-d',time());

			$datetime = strtotime($datetime);

			$condition_sql	.= " and goods.goods_isztc = 1 and goods.goods_ztcstartdate <= $datetime and (($datetime - goods.goods_ztclastdate)/(3600*24))*$ztc_dayprod <= goods_goldnum";

		}

		if ($condition_array['lesstime']){

			$condition_sql	.= " and goods.goods_ztclastdate < '{$condition_array['lesstime']}'";

		}

		if ($condition_array['spec_goods_spec'] != ''){

			$condition_sql	.= " and goods_spec.spec_goods_spec = '".$condition_array['spec_goods_spec']."'";

		}

		if ($condition_array['start_price'] > 0){

			$condition_sql	.= " and goods.goods_store_price > '".$condition_array['start_price']."'";

		}

		if ($condition_array['end_price'] > 0){

			$condition_sql	.= " and goods.goods_store_price < '".$condition_array['end_price']."'";

		}

        if (!empty($condition_array['group_flag'])){

            $condition_sql .= " and goods.group_flag = '".$condition_array['group_flag'] ."'";

        }

        if (!empty($condition_array['xianshi_flag'])){

            $condition_sql .= " and goods.group_flag <> 1 and goods.xianshi_flag = '".$condition_array['xianshi_flag'] ."'";

        }

        if ($condition_array['province_id'] != ''){

        	$condition_sql	.= " and province_id = '".$condition_array['province_id'] ."'";

        }

        if ($condition_array['goods_form'] != ''){

        	$condition_sql	.= " and goods_form = '".$condition_array['goods_form']."'";

        }

		if($condition_array['add_time_from'] != ''){

			$condition_sql	.= " and goods_add_time >= '".$condition_array['add_time_from']."'";

		}

		if($condition_array['add_time_to'] != ''){

			$condition_sql	.= " and goods_add_time <= '".$condition_array['add_time_to']."'";

		}
                
                

		//添加国家馆判断条件

		if($condition_array['country'] != ''){

			$condition_sql	.= " and country = '".$condition_array['country']."' and goods_show = 1";

		}
        if ($condition_array['place']['country'] !='' and $condition_array['place']['store_id'] !=''){
            $condition_sql .= " and (country = '".$condition_array['place']['country']."' or goods.store_id=".$condition_array['place']['store_id'].")";
        }

		return $condition_sql;

	}

	

	//回执文件上传

	public function goods_receipt()

	{

		if(!empty($_FILES['file']['name']))

		{

			$file = $_FILES['file'];

			if($file['size'] > 2*1024*1024)

			{

				echo "<script>alert('上传文件超过2M');location.back(-1);</script>";

			}

			

			

			if($file['type']!=='text/xml')

			{

				echo "<script>alert('上传文件类型不对');location.back(-1);</script>";

			}

			

			

			

			if(!file_exists(dirname(dirname(__FILE__)).'/tmp/warehouse_list'))

			{

				mkdir(dirname(dirname(__FILE__)).'/tmp/warehouse_list/','0777',true);

			}

				

			

			

			$newName = MD5(time().rand(000,9999)).strchr($file['name'],'.');

			

			if(move_uploaded_file($file['tmp_name'],dirname(dirname(__FILE__)).'/tmp/warehouse_list/'.$dir.$newName))

			{

				//$arr = dirname(dirname(__FILE__)).'/warehouse_list/'.$dir.$newName;

				$arr = 'tmp/warehouse_list/'.$dir.$newName;

				return $arr;

				

			}else

			{

				echo "<script>alert('上传失败');location.back(-1);</script>";

			}

		}

	}
    	//上传订单运单csv文件
	public function upload_shippingcode_csv()
	{
		$model = Model('store');
		$goods_class = Model('goods_class');
		if(!empty($_FILES['file']['name']))
		{
			$file = $_FILES['file'];
	
			//判断文件大小
			if($file['size'] > 2*1024*1024)
			{
				echo "<script>parent.error('上传文件超过2M')</script>";exit;
			}
			
			//判断文件类型
			$type = strchr($file['name'],'.');
			if($type !=='.csv')
			{
				echo "<script>parent.error('请上传csv格式文件');</script>";exit;
			}
						
			if(!file_exists('shoppingcode_list'))
				mkdir('shoppingcode_list/','0777',true);
			
			$newName = MD5(time().rand(1000,9999)).strchr($file['name'],'.');
			
			if(move_uploaded_file($file['tmp_name'],'shoppingcode_list/'.$newName))
			{
				$arr = 'shoppingcode_list/'.$newName;
			
				$file = fopen($arr,'r'); 
                
				
				while ($data = fgetcsv($file)) 
				{   
					$data[0] = mb_convert_encoding(trim($data[0]), "UTF-8", "GBK");
					$data[1] = mb_convert_encoding(trim($data[1]), "UTF-8", "GBK");
					
					$n = 0;
                                 
					foreach($data as $k=>$v)
					{
						if(!empty($v))
						{
							++$n;
						}
					}
					if($n<=1 || trim($data[0])=='订单号' || trim($data[0]) == '说明')continue;		
					
					$brr = array();
					
					//$brr['order_sn'] = $data[0];
					$brr['shipping_code'] = $data[1];

					$param['table']='order';
					$param['field']='order_sn';
					$param['value']=$data[0];
					$row = Db::getRow($param,"*" );

					if(!empty($row))
					{
						Db::update('order',$brr,"order_sn='".$data[0]."'");
						
					}
					 
				}
                               
				fclose($file);  

				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>parent.success('上传成功');</script>";
			}else{
			
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo "<script>parent.error('上传出错')</script>";
			}
		}
	}

}

