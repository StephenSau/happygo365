<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/26 0026
 * Time: 下午 2:04
 */
class tpl_blockModel
{
    /**
     * @param $param=[]
     * @return null|bool|[]
     */
    public function insert($param)
    {
        if(is_array($param)&&!empty($param)){
            $condition=[];
            $condition['block_id']='';
            $condition['position_id']=$param['position_id'];
            $condition['block_is_show']=$param['block_is_show'];
            $condition['key_num']=$param['key_num'];
            $condition['img_url']=$param['img_url'];
            $condition['word_value']=$param['word_value'];
            $condition['block_value']=$param['block_value'];
            $condition['class_id']=$param['class_id'];
            return Db::insert('tpl_block',$condition);
        }
    }

    /**
     * @param $param=[]
     * @return null|bool|[]
     */
    public function getTplBlocks($param)
    {
        if(is_array($param)&&!empty($param)){
            $condition=[];
            $condition['table']=$param['table'];
            $condition['field']=$param['field'];
            $condition['where']=$param['where'];
            if(isset($param['order'])&&!empty($param['order'])){
                $condition['order']=$param['order'];
            }
            if(isset($param['limit'])&&!empty($param['limit'])){
                $condition['limit']=$param['limit'];
            }
            return Db::select($param);
        }
    }

    /**
     * @param $param=[]
     */
    public function updateTplBlocks($param)
    {
        if(is_array($param)&&!empty($param)){
            return Db::update('tpl_block',$param['value'],$param['where']);
        }
    }

    public function deleteTplBlocks($param)
    {
        return Db::delete('tpl_block',$param['where']);
    }
}