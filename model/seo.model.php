<?php

defined('haipinlegou') or exit('Access Invalid!');
class seoModel extends Model{
	
	private $seo;

	public function __construct(){
		parent::__construct('seo');
	}

	
	public function type($type){
		if (is_array($type)){	
			$this->seo['title'] = $type[1];
			$this->seo['keywords'] = $type[2];
			$this->seo['description'] = $type[3];
		}else{
			$this->seo = $this->getSeo($type);
		}
		if (!is_array($this->seo)) return $this;
		foreach ($this->seo as $key=>$value) {
			$this->seo[$key] = str_replace(array('{sitename}'),array(C('site_name')),$value);
		}
		return $this;
	}

	
	private function getSeo($type){
		if (!$list = F('seo')){
			$list = H('seo',true,'file');
		}
		return $list[$type];
	}

	
	public function param($array = null){
		if (!is_array($this->seo)) return $this;
		if (is_array($array)){
			$array_key = array_keys($array);
			array_walk($array_key,array(self,'addTag'));
			foreach ($this->seo as $key=>$value) {
				$this->seo[$key] = str_replace($array_key,array_values($array),$value);
			}
		}
		return $this;
	}

	
	public function show(){
		$this->seo['title'] = preg_replace("/{.*}/siU",'',$this->seo['title']);
		$this->seo['keywords'] = preg_replace("/{.*}/siU",'',$this->seo['keywords']);
		$this->seo['description'] = preg_replace("/{.*}/siU",'',$this->seo['description']);
		
		Tpl::output('html_title',$this->seo['title'] ? $this->seo['title'] : C('site_name'));
		Tpl::output('seo_keywords',$this->seo['keywords'] ? $this->seo['keywords'] : C('site_name'));
		Tpl::output('seo_description',$this->seo['description'] ? $this->seo['description'] : C('site_name'));
	}

    private function addTag(&$key){
       $key ='{'.$key.'}';
    }
}