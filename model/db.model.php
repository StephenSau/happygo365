<?php

defined('haipinlegou') or exit('Access Invalid!');

class dbModel{
	
	private $back_content = '';
	
	private $step = 1;
	
	public function backUp($step=1){
		$table_list = $_SESSION['db_backup']['backup_tables'];
		if ($step == 1){
			$this->back_content .= "\r\nSET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\r\n\r\n";
			$_SESSION['db_backup']['table_name'] = $_SESSION['db_backup']['backup_tables'][0];
		}
		$this->step = $step;
		if (!empty($_SESSION['db_backup']['table_name'])){
			$key = array_search($_SESSION['db_backup']['table_name'],$table_list);
			if ($key > 0){
				for ($i=0; $i<$key; $i++){
					unset($table_list[$i]);
				}
			}
		}
		
		if ($_SESSION['db_backup']['op'] == 'create'){
			foreach ($table_list as $k => $v){
			
				$_SESSION['db_backup']['table_name'] = $v;
				$result = $this->getCreateContent($v);
			
				if ($result === false){
					return true;
				}
				
				if (empty($table_list[$k+1])){
				
					$this->writeBackFile();
					
					$_SESSION['db_backup']['op'] = 'insert';
					$_SESSION['db_backup']['table_name'] = $_SESSION['db_backup']['backup_tables'][0];
					return true;
				}
			}
		}
		
		if ($_SESSION['db_backup']['op'] == 'insert'){
			foreach ($table_list as $k => $v){
				
				$_SESSION['db_backup']['table_name'] = $v;
				while (true) {
					$result = $this->getInsertContent($v);
					if ($result === 'succ'){
						break;
					}
					
					if ($result === false){
						return true;
					}
				}
			
				if (empty($table_list[$k+1]) && $_SESSION['db_backup']['limit'] == 0){
					
					$this->writeBackFile();
					return true;
				}
			}
		}
		return true;
	}
	
	
	public function getTableList($type='self'){
		$table_list = array();
		$tmp = Db::showTables();
		if ($type == 'all'){
			$table_list = $tmp;
		}
		if ($type == 'self'){
			$count = strlen(DBPRE);
			if (is_array($tmp)){
				foreach ($tmp as $k => $v){
					if (substr($v[0],0,$count) == DBPRE){
						$table_list[] = $v[0];
					}
				}
			}
		}
		return $table_list;
	}
	
	
	
	private function getCreateContent($table){
		
		$tmp_create = "DROP TABLE IF EXISTS `". $table ."`;\r\n";
		$tmp_create .= Db::showCreateTable(substr($table,strlen(DBPRE),strlen($table)-1));
		$tmp_create .= ";\r\n\r\n";
		
		if (strlen($this->back_content.$tmp_create) >= $_SESSION['db_backup']['size']){
			
			$this->writeBackFile();
			
			return false;
		}else {
			
			$this->back_content .= $tmp_create;
			
			return true;
		}
	}
	
	
	private function sqlAddslashes($a_string = '', $is_like = false, $crlf = false, $php_code = false){
    if ($is_like) {
        $a_string = str_replace('\\', '\\\\\\\\', $a_string);
    } else {
        $a_string = str_replace('\\', '\\\\', $a_string);
    }

    if ($crlf) {
        $a_string = str_replace("\n", '\n', $a_string);
        $a_string = str_replace("\r", '\r', $a_string);
        $a_string = str_replace("\t", '\t', $a_string);
    }

    if ($php_code) {
        $a_string = str_replace('\'', '\\\'', $a_string);
    } else {
        $a_string = str_replace('\'', '\'\'', $a_string);
    }

    return $a_string;
	}
	
	private function getInsertContent($table){
		
		$limit = $_SESSION['db_backup']['limit']?$_SESSION['db_backup']['limit']:0;
		
		$now_size = strlen($this->back_content);
		
		$param = array();
		$param['table'] = substr($table,strlen(DBPRE),strlen($table)-1);
		$param['limit'] = $limit.',300';
		$param['cache'] = false;
		$list = Db::select($param);

		
		if (empty($list)){
			
			$_SESSION['db_backup']['limit'] = 0;
			
			return 'succ';
		}
		
		$columns_array = Db::showColumns(substr($table,strlen(DBPRE),strlen($table)-1));
		
		$result = '';
		foreach ($list as $k => $v){
			$tmp_sql = '';
			$tmp_columns = '';
			$tmp_value = '';
			
			foreach ($columns_array as $k_col => $v_col){
				
				$tmp_columns .= "`". $k_col ."`,";

				if ($v_col['null'] == 'YES'){
					if (empty($v[$k_col])){
						$tmp_value .= "NULL,";
					}else {
						$tmp_value .= "'". $this->sqlAddslashes($v[$k_col]) ."',";
					}
				}else {
					$tmp_value .= "'". $this->sqlAddslashes($v[$k_col]) ."',";
				}
			}
			
			$tmp_sql .= "INSERT INTO `".$table."` ";
			$tmp_sql .= "(";
			$tmp_sql .= trim($tmp_columns,',');
			$tmp_sql .= ") VALUES(";
			$tmp_sql .= trim($tmp_value,',');
			$tmp_sql .= ")";
			$tmp_sql .= ";\r\n";
			
			if (strlen($this->back_content.$tmp_sql) >= $_SESSION['db_backup']['size']){
				
				$this->writeBackFile();
				
				return false;
			}else {
				
				$_SESSION['db_backup']['limit']++;
				$this->back_content .= $tmp_sql;
			}
		}
		
		
		if (count($list) < 10){
			$_SESSION['db_backup']['limit'] = 0;
			$this->back_content .= "\r\n";
			return 'succ';
		}
		
		return true;
	}
	
	
	public function getBackDir(){
		
		$dir_list = readDirList(BasePath.DS.'sql_back');
		$tmp = date('Ymd');
		$check_array = array();
		if (is_array($dir_list)){
			foreach ($dir_list as $k => $v){
				if (substr($v,0,strlen($tmp)) == $tmp){
					$check_array[] = substr($v,strlen($tmp)+1,strlen($v));
				}
			}
		}
		$return = $tmp.'_'.($check_array[count($check_array)-1]+1);
		return $return;
	}
	
	
	public function writeBackFile(){
		Language::read('model_lang_index');
		$lang	= Language::getLangContent();
		$step = $this->step;
		try {
			if (!is_dir(BasePath.DS.'sql_back'.DS.$_SESSION['db_backup']['back_file'])){
				if (!@mkdir(BasePath.DS.'sql_back'.DS.$_SESSION['db_backup']['back_file'],0755)){
					$error = $lang['db_backup_mkdir_fail'];
					throw new Exception($error);
				}else {
					$fp = @fopen(BasePath.DS.'sql_back'.DS.$_SESSION['db_backup']['back_file'].DS.'index.html','w+');
					@fclose($fp);
				}
			}
			$file_name = BasePath.DS.'sql_back'.DS.$_SESSION['db_backup']['back_file'].DS.$_SESSION['db_backup']['back_file'].'_'.$step.'_'.$_SESSION['db_backup']['md5'].'.sql';
			$fp = @fopen($file_name,'w+');
			if (@fwrite($fp,$this->back_content) === false){
				$error = $lang['db_backup_vi_file_fail'];
				throw new Exception($error);
			}
			@fclose($fp);
		}catch (Exception $e){
			showMessage($e->getMessage(),'','exception');
		}
		return true;
	}
	
	
	public function import($path,$step=1){
		$dir = BasePath.DS.'sql_back'.DS.$path;
		$file_list = array();
		readFileList($dir,$file_list);
		
		if (!empty($file_list) && is_array($file_list)){
			foreach ($file_list as $key=>$file_name){
				if (strtolower(substr($file_name,-4)) == '.sql'){
					$tmp_list[] = $file_name;
				}
			}
			$file_list = $tmp_list;
		}
		$file_name = $file_list[$step-1];
		if (is_file($file_name)){
			$handle = @fopen($file_name, "r");
			$tmp_sql = '';
			if ($handle) {
			    while (!feof($handle)) {
			        $buffer = fgets($handle);
			        if (trim($buffer) != ''){
			        	$tmp_sql .= $buffer;
				        if (substr(rtrim($buffer),-1) == ';'){
				        	if (preg_match('/^(CREATE|ALTER|DROP)\s+(VIEW|TABLE|DATABASE|SCHEMA)\s+/i', ltrim($tmp_sql))){
				        	}else if (preg_match('/^(INSERT)\s+(INTO)\s+/i', ltrim($tmp_sql)) && substr(rtrim($buffer),-2) == ');'){
				        	}else if (preg_match('/^(SET)\s+SQL_MODE=/i', ltrim($tmp_sql))){
				        	}else{
				        		continue;
				        	}
				        	if (!empty($tmp_sql)){
								
								if (strpos($tmp_sql,cookie('sess_id')) !== false){
									unset($tmp_sql);
									continue;
								}
				        		Db::query($tmp_sql);
				        		unset($tmp_sql);
				        	}
				        }
			        }
			    }
			    @fclose($handle);
			}
			
			if (empty($file_list[$step])){
				return 'succ';
			}else {
				return 'continue';
			}
		}else {
			return false;
		}
	}
}