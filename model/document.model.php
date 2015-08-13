<?php

defined('haipinlegou') or exit('Access Invalid!');

class documentModel{
	
	public function getList(){
		$param	= array(
			'table'	=> 'document'
		);
		return Db::select($param);
	}
	
	public function getOneById($id){
		$param	= array(
			'table'	=> 'document',
			'field'	=> 'doc_id',
			'value'	=> $id
		);
		return Db::getRow($param);
	}
	
	public function getOneByCode($code){
		$param	= array(
			'table'	=> 'document',
			'field'	=> 'doc_code',
			'value'	=> $code
		);
		return Db::getRow($param);
	}
	
	public function update($param){
		return Db::update('document',$param,"doc_id='{$param['doc_id']}'");
	}
}