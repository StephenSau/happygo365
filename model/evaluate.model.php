<?php

defined('haipinlegou') or exit('Access Invalid!');
class evaluateModel {
	
	public function addGoodsEval($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('evaluate_goods',$param);
		return $result;
	}
	
	public function getGoodsEvalList($condition,$page='',$field='*',$type = 'simple'){
		$condition_str	= $this->getGoodsEvalCondition($condition);
		$param	= array();
		
		switch ($type){
			case 'store':
				$param['table'] = 'evaluate_goods,store';
				$param['join_type'] = empty($param['join_type'])?'LEFT JOIN':$param['join_type'];
				$param['join_on'] = array(
					'evaluate_goods.geval_storeid=store.store_id'
				);
				break;
			case 'member':
				$param['table'] = 'evaluate_goods,member';
				$param['join_type'] = empty($param['join_type'])?'LEFT JOIN':$param['join_type'];
				$param['join_on'] = array(
					'evaluate_goods.geval_frommemberid=member.member_id'
				);
				break;
			default:
				$param['table']	= 'evaluate_goods';
				break;
		}
		$param['field'] = $field;
		$param['where']	= $condition_str;
		$param['order'] = $condition['order'] ? $condition['order'] : 'evaluate_goods.geval_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getGoodsEvalInfo($condition,$field='*'){
		$param = array();
		$param['table'] = 'evaluate_goods';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function editGoodsEval($param,$condition) {
		if(empty($param)) {
			return false;
		}
		$condition_str	= $this->getGoodsEvalCondition($condition);
		$result	= Db::update('evaluate_goods',$param,$condition_str);
		return $result;
	}
	
	public function delGoodsEval($condition){
		if(empty($condition)) {
			return false;
		}
		$condition_str = $this->getGoodsEvalCondition($condition);
		$result = Db::delete('evaluate_goods',$condition_str);
		return $result;
	}
	
	public function goodsEvalstat($store_id){
		if ($store_id <= 0){
			return false;
		}		
		
		$week_time = time()-3600*24*7;
		$month_time = time()-3600*24*30;
		$month6_time = time()-3600*24*30*6;
		$showfield = 'geval_storeid';
		$showfield .= ',geval_scores';
		$showfield .= ',SUM(IF(geval_showtime>='.$week_time.',1,0)) as weeknum';
		$showfield .= ',SUM(IF(geval_showtime>='.$month_time.',1,0)) as monthnum';
		$showfield .= ',SUM(IF(geval_showtime>='.$month6_time.',1,0)) as month6num';
		$showfield .= ',SUM(IF(geval_showtime<'.$month6_time.',1,0)) as month6agonum';
		$showfield .= ',count(geval_id) as countnum';
		$condition= array();
		$condition['geval_storeid']= "$store_id";
		$condition['geval_type']= "1";
		$condition['geval_showtime_lt']= time();
		$condition['group']= 'geval_scores';
		$goodsstat_list = $this->getGoodsEvalList($condition,'',$showfield);
		$goodsstat_listnew = array();
		$goodseval_countnum = 0;
		$goodseval_goodnum = 0;
		if (!empty($goodsstat_list)){
			foreach ($goodsstat_list as $k=>$v){
				$level_name = '';
				switch ($v['geval_scores']){
					case 1:
						$level_name = 'level1';
						$goodseval_goodnum = $v['countnum'];
						break;
					case -1:
						$level_name = 'level3';
						$badeval_goodnum = $v['countnum'];
						break;
					default:
						$level_name = 'level2';
						break;
				}
				$goodsstat_listnew['1'][$level_name] = $v['weeknum'];
				$goodsstat_listnew['2'][$level_name] = $v['monthnum'];
				$goodsstat_listnew['3'][$level_name] = $v['month6num'];
				$goodsstat_listnew['4'][$level_name] = $v['month6agonum'];
				$goodsstat_listnew['5'][$level_name] = $v['countnum'];
				$goodseval_countnum += $v['countnum'];
			}
		}
		unset($goodsstat_list);
		if (!empty($goodsstat_listnew)){
			$this->delGoodsEvalStat(array('del_statstoreid'=>"$store_id"));
			foreach ($goodsstat_listnew as $k=>$v){
				$insertstat_arr = array();
				$insertstat_arr['gevalstat_storeid'] = $store_id;
				$insertstat_arr['gevalstat_type'] = $k;
				$insertstat_arr['gevalstat_level1num'] = $v['level1']?$v['level1']:0;
				$insertstat_arr['gevalstat_level2num'] = $v['level2']?$v['level2']:0;
				$insertstat_arr['gevalstat_level3num'] = $v['level3']?$v['level3']:0;
				$this->addGoodsEvalStat($insertstat_arr);
				unset($insertstat_arr);
			}
		}
		unset($goodsstat_listnew);
		$store_model = Model('store');
		$store_model->storeUpdate(array(
			'store_id'=>$store_id,
			'store_credit'=>$goodseval_goodnum - $badeval_goodnum,
			'praise_rate'=>@round($goodseval_goodnum/$goodseval_countnum,4)*100
		));
	}
	
	public function memberEvalstat($member_id){
		if ($member_id <= 0){
			return false;
		}
		$showfield = 'geval_tomemberid';
		$showfield .= ',SUM(geval_scores) as sumscores';
		$condition= array();
		$condition['geval_type']= "2";
		$condition['geval_tomemberid']= "$member_id";
		$condition['geval_showtime_lt']= time();
		$condition['group']= 'geval_tomemberid';
		$goodsstat_list = $this->getGoodsEvalList($condition,'',$showfield);
		$member_model = Model('member');
		if (!empty($goodsstat_list[0])){
			$sumscores = intval($goodsstat_list[0]['sumscores']);
			$member_model->updateMember(array('member_credit'=>"$sumscores"),$member_id);
		}else{
			$member_model->updateMember(array('member_credit'=>"0"),$member_id);
		}
	}
	
	public function goodsEvalCountNum($goods_id){
		if ($goods_id <= 0){
			return false;
		}
		$condition= array();
		$condition['geval_goodsid']= "$goods_id";
		$condition['geval_type']= "1";
		$condition['geval_showtime_lt']= time();
		$countnum = $this->countGoodsEval($condition);
		intval($countnum)>0?$countnum:0;
		$goods_model = Model('goods');
		$goods_model->updateGoodsWhere(array('commentnum'=>"$countnum"),array('goods_id'=>"$goods_id"));
	}
	
	public function addGoodsEvalStat($param) {
		if(empty($param)){
			return false;
		}
		$result	= Db::insert('evaluate_goodsstat',$param);
		return $result;
	}
	
	public function delGoodsEvalStat($condition_arr) {
		$condition_str = $this->getGoodsEvalCondition($condition_arr);
		$result	= Db::delete('evaluate_goodsstat',$condition_str);
		return $result;
	}
	
	public function goodsEvalStatList($condition,$page='',$field='*'){
		$condition_str	= $this->getGoodsEvalCondition($condition);
		$param	= array();
		$param['table']	= 'evaluate_goodsstat';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'evaluate_goodsstat.gevalstat_type';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function countGoodsEval($condition){
		$condition_str	= $this->getGoodsEvalCondition($condition);
		return Db::getCount('evaluate_goods',$condition_str);
	}
	
	public function updateMemberStat($member_id=0,$store_id=0) {
		if ($member_id > 0){
			$this->memberEvalstat($member_id);
			if ($store_id > 0){
				$this->goodsEvalstat($store_id);
				$this->storeEvalstat($store_id);
			}
		}
	}
	
	public function updateGoodsStat($goods_id=0) {
		if ($goods_id > 0){
			$condition_arr = array();
			$condition_arr['geval_goodsid']= $goods_id;
			$condition_arr['geval_bothstate'] = '1';
			$condition_arr['geval_scores'] = '1';
			$condition_arr['geval_showtime_lt'] = time();
			$condition_arr['order'] = 'evaluate_goods.geval_id asc';
			$goodseval_list = $this->getGoodsEvalList($condition_arr);
			unset($condition_arr);
			if (!empty($goodseval_list)){
				foreach ($goodseval_list as $k=>$v){
					$insert_arr = array();
					foreach ($v as $skey => $sval){
						if (!is_numeric($skey) && $skey!='geval_id'){
							$insert_arr[$skey] = $sval;
						}
					}
					$insert_arr['geval_content'] = '';
					$insert_arr['geval_isanonymous'] = '0';
					$insert_arr['geval_addtime'] = time();
					$insert_arr['geval_frommemberid'] = $v['geval_tomemberid'];
					$insert_arr['geval_frommembername'] = $v['geval_tomembername'];
					$insert_arr['geval_tomemberid'] = $v['geval_frommemberid'];
					$insert_arr['geval_tomembername'] = $v['geval_frommembername'];
					$insert_arr['geval_state'] = 0;
					$insert_arr['geval_remark'] = '';
					$insert_arr['geval_explain'] = '';
					$insert_arr['geval_bothstate'] = '2';
					if ($v['geval_type'] == 1){
						$insert_arr['geval_type'] = '2';
					}else {
						$insert_arr['geval_type'] = '1';
					}
					$state = $this->addGoodsEval($insert_arr);
					unset($insert_arr);
					if ($state){
						
						$this->editGoodsEval(array('geval_bothstate'=>'2'),array('geval_id'=>"{$v['geval_id']}"));
						
						$order_model = Model('order');					
						$uparr = array();
						if ($v['geval_type'] == 1){
							$uparr['evalseller_status'] = 1;
							$uparr['evalseller_time'] = time();
						}else {
							$uparr['evaluation_status'] = 1;
							$uparr['evaluation_time'] = time();
						}
						$order_model->updateOrder($uparr,$v['geval_orderid']);
					}
				}
			}
			$this->goodsEvalCountNum($goods_id);
		}
	}
	
	private function getGoodsEvalCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['geval_id'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_id = '{$condition_array['geval_id']}'";
		}
		if ($condition_array['geval_id_del'] != '') {
			$condition_sql	.= " and geval_id = '{$condition_array['geval_id_del']}'";
		}
		if ($condition_array['geval_orderid'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_orderid = '{$condition_array['geval_orderid']}'";
		}
		if ($condition_array['geval_goodsid'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_goodsid = '{$condition_array['geval_goodsid']}'";
		}
		if ($condition_array['geval_storeid'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_storeid = '{$condition_array['geval_storeid']}'";
		}
		if ($condition_array['geval_type'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_type = '{$condition_array['geval_type']}'";
		}
		if ($condition_array['geval_tomemberid'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_tomemberid = '{$condition_array['geval_tomemberid']}'";
		}
		if ($condition_array['geval_tomembername'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_tomembername like '%{$condition_array['geval_tomembername']}%'";
		}
		if ($condition_array['geval_frommemberid'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_frommemberid = '{$condition_array['geval_frommemberid']}'";
		}
		if ($condition_array['geval_frommemberid_del'] != '') {
			$condition_sql	.= " and geval_frommemberid = '{$condition_array['geval_frommemberid_del']}'";
		}
		if ($condition_array['geval_bothstate'] != ''){
			$condition_sql	.= " and `evaluate_goods`.geval_bothstate = '{$condition_array['geval_bothstate']}'";
		}
		if ($condition_array['geval_scores'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_scores = '{$condition_array['geval_scores']}'";
		}
		if ($condition_array['geval_scores_no'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_scores != '{$condition_array['geval_scores_no']}'";
		}
		if ($condition_array['geval_state'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_state = '{$condition_array['geval_state']}'";
		}
		if ($condition_array['havecontent'] != '') {
			switch ($condition_array['havecontent']){
				case 'yes':
					$condition_sql	.= " and `evaluate_goods`.geval_content != ''";
					break;
				case 'no':
					$condition_sql	.= " and `evaluate_goods`.geval_content = ''";
					break;
			}
		}
		if ($condition_array['geval_addtime_gt'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_addtime >= '{$condition_array['geval_addtime_gt']}'";
		}
		if ($condition_array['geval_addtime_lt'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_addtime <= '{$condition_array['geval_addtime_lt']}'";
		}
		if ($condition_array['geval_showtime_lt'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_showtime <= '{$condition_array['geval_showtime_lt']}'";
		}
		if ($condition_array['geval_goodsname'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_goodsname like '%{$condition_array['geval_goodsname']}%'";
		}
		if ($condition_array['geval_storename'] != '') {
			$condition_sql	.= " and `evaluate_goods`.geval_storename like '%{$condition_array['geval_storename']}%'";
		}
		if ($condition_array['del_statstoreid'] != '') {
			$condition_sql	.= " and gevalstat_storeid = '{$condition_array['del_statstoreid']}'";
		}
		if ($condition_array['statstoreid'] != '') {
			$condition_sql	.= " and `evaluate_goodsstat`.gevalstat_storeid = '{$condition_array['statstoreid']}'";
		}		
		return $condition_sql;
	}
	
	public function addStoreEval($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('evaluate_store',$param);
		return $result;
	}
	
	public function getStoreEvalList($condition,$page='',$field='*'){
		$condition_str	= $this->getStoreEvalCondition($condition);
		$param	= array();
		$param['table']	= 'evaluate_store';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'evaluate_store.seval_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getStoreEvalInfo($condition,$field='*'){
		$param = array();
		$param['table'] = 'evaluate_store';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	public function delStoreEval($condition){
		if(empty($condition)) {
			return false;
		}
		$condition_str = $this->getStoreEvalCondition($condition);
		$result = Db::delete('evaluate_store',$condition_str);
		return $result;
	}
	
	public function storeEvalstat($store_id){
		if ($store_id <= 0){
			return false;
		}
		$showfield = 'seval_storeid';
		$showfield .= ',seval_type';
		$showfield .= ',AVG(seval_scores) as avgnum';
		$showfield .= ',count(seval_storeid) as countnum';
		$showfield .= ',SUM(IF(seval_scores=1,1,0)) as onenum';
		$showfield .= ',SUM(IF(seval_scores=2,1,0)) as twonum';
		$showfield .= ',SUM(IF(seval_scores=3,1,0)) as threenum';
		$showfield .= ',SUM(IF(seval_scores=4,1,0)) as fournum';
		$showfield .= ',SUM(IF(seval_scores=5,1,0)) as fivenum ';
		$condition = array();
		$condition['seval_storeid'] = "$store_id";
		$condition['seval_addtime_gt'] = time()-3600*24*30*6;//6个月前
		$condition['group']= 'seval_type';
		$statlist = $this->getStoreEvalList($condition,'',$showfield);
		if (!empty($statlist)){
			$this->delStoreEvalStat(array('del_statstoreid'=>"$store_id"));
			$store_uparr = array();
			$store_uparr['store_id'] = $store_id;		
			foreach ($statlist as $v){
				$insertstat_arr = array();
				$insertstat_arr['evalstat_storeid'] = $store_id;
				$insertstat_arr['evalstat_type'] = $v['seval_type'];
				$insertstat_arr['evalstat_average'] = round($v['avgnum'],1);
				$insertstat_arr['evalstat_timesnum'] = $v['countnum'];
				$insertstat_arr['evalstat_onenum'] = $v['onenum'];
				$insertstat_arr['evalstat_twonum'] = $v['twonum'];
				$insertstat_arr['evalstat_threenum'] = $v['threenum'];
				$insertstat_arr['evalstat_fournum'] = $v['fournum'];
				$insertstat_arr['evalstat_fivenum'] = $v['fivenum'];
				$this->addStoreEvalStat($insertstat_arr);
				switch ($v['seval_type']){
					case 1:
						$store_uparr['store_desccredit'] = round($v['avgnum'],1);
						break;
					case 2:
						$store_uparr['store_servicecredit'] = round($v['avgnum'],1);
						break;
					case 3:
						$store_uparr['store_deliverycredit'] = round($v['avgnum'],1);
						break;
				}
				unset($insertstat_arr);
			}
			$store_model = Model('store');
			$store_model->storeUpdate($store_uparr);
		}
	}
	
	public function addStoreEvalStat($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('evaluate_storestat',$param);
		return $result;
	}
	
	public function delStoreEvalStat($condition_arr) {
		$condition_str = $this->getStoreEvalCondition($condition_arr);
		$result	= Db::delete('evaluate_storestat',$condition_str);
		return $result;
	}
	
	public function storeEvalStatList($condition,$page='',$field='*'){
		$condition_str	= $this->getStoreEvalCondition($condition);
		$param	= array();
		$param['table']	= 'evaluate_storestat';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'evaluate_storestat.evalstat_type';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	
	public function getOneStoreEvalStat($store_id){
		$storestat_list = $this->storeEvalStatList(array('statstoreid'=>"{$store_id}"));
		$storestat_listnew = array();
		if (!empty($storestat_list)){
			foreach ($storestat_list as $k=>$v){
				$v['rate'] = @round($v['evalstat_average']/5*100,2);
				$storestat_listnew[$v['evalstat_type']] = $v;
			}
		}
		return $storestat_listnew;
	}
	
	private function getStoreEvalCondition($condition_array){
		$condition_sql = '';
		if ($condition_array['seval_id_del'] != '') {
			$condition_sql	.= " and seval_id = '{$condition_array['seval_id_del']}'";
		}
		if ($condition_array['seval_storeid'] != '') {
			$condition_sql	.= " and `evaluate_store`.seval_storeid = '{$condition_array['seval_storeid']}'";
		}
		if ($condition_array['seval_scores'] != '') {
			$condition_sql	.= " and `evaluate_store`.seval_scores = '{$condition_array['seval_scores']}'";
		}
		if ($condition_array['seval_storename'] != '') {
			$condition_sql	.= " and `evaluate_store`.seval_storename like '%{$condition_array['seval_storename']}%'";
		}
		if ($condition_array['seval_membername'] != '') {
			$condition_sql	.= " and `evaluate_store`.seval_membername like '%{$condition_array['seval_membername']}%'";
		}
		if ($condition_array['seval_addtime_gt'] != '') {
			$condition_sql	.= " and `evaluate_store`.seval_addtime >= '{$condition_array['seval_addtime_gt']}'";
		}
		if ($condition_array['seval_addtime_lt'] != '') {
			$condition_sql	.= " and `evaluate_store`.seval_addtime <= '{$condition_array['seval_addtime_lt']}'";
		}
		if ($condition_array['del_statstoreid'] != '') {
			$condition_sql	.= " and evalstat_storeid = '{$condition_array['del_statstoreid']}'";
		}
		if ($condition_array['statstoreid'] != '') {
			$condition_sql	.= " and evaluate_storestat.evalstat_storeid = '{$condition_array['statstoreid']}'";
		}
		return $condition_sql;
	}
}