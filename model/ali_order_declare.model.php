<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/24 0024
 * Time: 上午 11:47
 */
defined('haipinlegou') or exit('Access Invalid!');

/**
 * Class ali_order_declare
 * 支付宝报关业务表
 */


class ali_order_declareModel
{
    /**
     * @param $param=[]
     * @return bool|null
     * 支付宝报关业务表添加数据
     */
    public function addAliDeclare($param)
    {
        if(!empty($param)&&is_array($param)){
            $insert=[];
            $insert['id']='';
            $insert['order_out_sn']=$param['order_out_sn'];
            $insert['declare_time']=$param['declare_time'];
            $insert['declare_status']=$param['declare_status'];
            $insert['declare_type']=$param['declare_type'];
            $insert['declare_no']=$param['declare_no'];
            $insert['custom_no']=$param['custom_no'];
            return Db::insert('order_declare',$insert);
        }
    }

    /**
     * @param $param=[]
     * @return bool|null
     */
    public function getAliDeclare($param,$page='')
    {
        $condition=[];
        $condition['field']=$param['field'];
        $condition['where']=$param['where'];
        $condition['table']='order_declare';
        return Db::select($condition,$page);
    }
}