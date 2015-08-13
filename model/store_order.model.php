<?php

defined('haipinlegou') or exit('Access Invalid!');

class store_orderModel
{

    public function storeOrderList($param, $obj_page = '', $field = '*')
    {
        $order_list = array();
        $condition_str = $this->getCondition($param);
        $array = array();
        $array['table'] = 'order,order_address,order_goods';
        $array['field'] = $field;
        $array['join_type'] = 'left join';
        $array['join_on'] = array('`order`.order_id=`order_address`.order_id', 'order.order_id=order_goods.order_id');
        $array['where'] = "where seller_id='{$_SESSION['member_id']}'" . $condition_str;
        $array['order'] = '`order`.add_time DESC';
        $order_list = Db::select($array, $obj_page);
        return $order_list;
    }

    public function carmanageOrderList($param, $obj_page = '', $field = '*')
    {
        $order_list = array();
        $condition_str = $this->getCondition($param);
        $array = array();
        $array['table'] = 'order,order_address,order_goods';
        $array['field'] = $field;
        $array['join_type'] = 'left join';
        $array['join_on'] = array('`order`.order_id=`order_address`.order_id', 'order.order_id=order_goods.order_id');
        $array['where'] = "where seller_id='206'" . $condition_str;
        $array['order'] = '`order`.add_time DESC';
        $order_list = Db::select($array, $obj_page);
        return $order_list;
    }

    public function carmanageOrderGoodsList($param, $obj_page = '', $field = '*')
    {
        $array = array();
        $condition_str = $this->getCondition($param);

        $array['table'] = 'order,order_goods';
        $array['field'] = '`order`.*,`order_goods`.*';
        $array['join_type'] = 'LEFT JOIN';
        $array['join_on'] = array('order.order_id=order_goods.order_id');
        $array['where'] = "where order.seller_id='206'" . $condition_str;
        $array['order'] = ' order.add_time DESC';
        $order_list = Db::select($array, $obj_page);
        return $order_list;
    }

    public function storeOrderListexcel($param, $obj_page = '', $field = '*')
    {
        $order_list = array();
        $condition_str = $this->getCondition($param);
        $array = array();
        $array['table'] = 'order,order_address,order_goods,store,member';
        $array['field'] = "*,order_address.area_info as rec_info";
        $array['join_type'] = 'left join';
        $array['join_on'] = array('`order`.order_id=`order_address`.order_id', 'order.order_id=order_goods.order_id', 'order.store_id=store.store_id', 'order.buyer_id=member.member_id');
        $array['where'] = "where seller_id='{$_SESSION['member_id']}'" . $condition_str;
        $array['group'] = 'order_sn';
        $array['order'] = '`order`.add_time DESC';
        $order_list = Db::select($array, $obj_page);
        return $order_list;
    }

    public function storeOrderGoodsList($param, $obj_page = '', $field = '*')
    {
        $array = array();
        $condition_str = $this->getCondition($param);

        $array['table'] = 'order,order_goods';
        $array['field'] = '`order`.*,`order_goods`.*';
        $array['join_type'] = 'LEFT JOIN';
        $array['join_on'] = array('order.order_id=order_goods.order_id');
        $array['where'] = "where order.seller_id='{$_SESSION['member_id']}'" . $condition_str;
        $array['order'] = ' order.add_time DESC';
        $order_list = Db::select($array, $obj_page);
        return $order_list;
    }

    private function getCondition($condition_array)
    {
        $condition_sql = '';
        if (!empty($condition_array['order_id'])) {
            $condition_sql .= ' and order.order_id IN (' . $condition_array['order_id'] . ')';
        }
        if ($condition_array['order_state'] == 'order_success') {
            $condition_sql .= ' and order.order_state=40 and order.refund_state=0';
        }
        if ($condition_array['order_state'] == 'order_cancel') {
            $condition_sql .= ' and order.order_state=0';
        }
        if ($condition_array['refund_state'] == 'no') {
            $condition_sql .= ' and order.refund_state=0';
        }
        if ($condition_array['return_state'] == 'no') {
            $condition_sql .= ' and order.return_state=0';
        }
        if ($condition_array['order_state'] == 'order_submit') {
            $condition_sql .= ' and order.order_state=50';
        }
        if ($condition_array['order_state'] == 'order_pay') {
            $condition_sql .= ' and order.order_state=10';
        }
        if ($condition_array['order_state'] == 'pay_confirm') {
            $condition_sql .= ' and order_state=11';
        }
        if ($condition_array['order_state'] == 'order_no_shipping') {
            $condition_sql .= ' and (order.order_state=20 or order.order_state=60)';
        }
        if ($condition_array['order_state'] == 'order_shipping') {
            $condition_sql .= ' and order.order_state=30';
        }
        if ($condition_array['order_state'] == 'order_finish') {
            $condition_sql .= ' and order.order_state=40';
        }
        if ($condition_array['order_state'] == 'order_refund') {
            $condition_sql .= ' and refund_state >0';
        }
        if ($condition_array['`order`.store_id'] > 0) {
            $condition_sql .= " and `order`.store_id='{$condition_array['`order`.store_id']}'";
        }
        if ($condition_array['`order`.evaluation_status'] > 0) {
            $condition_sql .= " and `order`.evaluation_status='{$condition_array['`order`.evaluation_status']}'";
        }
        if ($condition_array['`order_goods`.evaluation'] > 0) {
            $condition_sql .= " and `order_goods`.evaluation='{$condition_array['`order_goods`.evaluation']}'";
        }
        if ($condition_array['order_country'] != '') {
            $condition_sql .= " and `order_goods`.order_country LIKE '%" . $condition_array['order_country'] . "%'";
        }
        if ($condition_array['order_provider'] != '') {
            $condition_sql .= " and `order_goods`.order_provider LIKE '%" . $condition_array['order_provider'] . "%'";
        }
        if ($condition_array['goods_item_no'] != '') {
            $condition_sql .= " and `order_goods`.goods_item_no LIKE '%" . $condition_array['goods_item_no'] . "%'";
        }
        if ($condition_array['buyer_name'] != '') {
            $condition_sql .= " and order.buyer_name LIKE '%" . $condition_array['buyer_name'] . "%'";
        }
        if ($condition_array['order_sn'] != '') {
            $condition_sql .= " and order.order_sn LIKE '%" . $condition_array['order_sn'] . "%'";
        }

        $condition_sql .= !empty($condition_array['add_time_from']) ? " and order.add_time>= '{$condition_array['add_time_from']}'" : '';
        $condition_sql .= !empty($condition_array['add_time_to']) ? " and order.add_time<= '{$condition_array['add_time_to']}'" : '';

        if ($condition_array['payment_time_from'] != '' || $condition_array['payment_time_to'] != '') {
            $condition_sql .= ' and order_state > 10 ';
            if(!empty($condition_array['payment_time_from'])) $condition_sql .= " and order.payment_time>= '{$condition_array['payment_time_from']}' " ;
            if(!empty($condition_array['payment_time_to'])) $condition_sql .=" and order.payment_time<= '{$condition_array['payment_time_to']}' " ;
        }


        if ($condition_array['order_evalseller_able'] != '') {
            $condition_sql .= " and order.order_state=40 and order.refund_state<2 and order.evalseller_status = 0 and (((order.finnshed_time+60*60*24*15)>" . time() . ") or ((order.evaluation_time+60*60*24*15)>" . time() . ")) ";
        }
        return $condition_sql;
    }
}

?>