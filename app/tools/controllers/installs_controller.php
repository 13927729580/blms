<?php

class installsController extends AppController{
 	var $uses ="";

    function index($page=1){
		$flag=false;
		$lock=true;
		$this->set('flag', $flag);
		$path = WWW_ROOT. '/data/install.lock';
		if(!file_exists($path)){
			$lock=false;
		}
		$agreement_path = WWW_ROOT . '/data/tools/agreement.txt';
		if(file_exists($agreement_path)){
			$agreement=fopen($agreement_path, "r");
		   flock($agreement, LOCK_EX);
		   $content = fread($agreement, filesize($agreement_path));
		   flock($agreement, LOCK_UN);
			$this->set('agreement', $content);
			fclose ($agreement);
		}
		$this->set('lock', $lock);
		include_once(ROOT_PATH . 'tools/controllers/includes/lib_env_checker.php');
	    include_once(ROOT_PATH . 'tools/controllers/includes/checking_dirs.php');
	    $dir_checking = check_dirs_priv($checking_dirs,$this->_LANG);
	    $templates_root = array(
	        'temp' => dirname(ROOT_PATH).'/app/web/views/themed');
	    $this->set('templates_root',$templates_root);
	    $template_checking = check_templates_priv($templates_root);
	    $rename_priv = check_rename_priv();
	    $disabled = '""';
	    if ($dir_checking['result'] === 'ERROR'
	            || !empty($template_checking)
	            || !empty($rename_priv)
	            || !function_exists('mysqli_connect'))
	    {
	        $disabled = 'disabled="true"';
	    }
	    $has_unwritable_tpl = 'yes';
	    if (empty($template_checking)){
	        $template_checking = $this->_LANG['all_are_writable'];
	        $has_unwritable_tpl = 'no';
	    }
	    $ui = (!empty($_POST['user_interface']))?$_POST['user_interface'] : "seevia";
	    $ucapi = (!empty($_POST['ucapi']))?$_POST['ucapi'] : "seevia";
	    $ucfounderpw = (!empty($_POST['ucfounderpw']))?$_POST['ucfounderpw'] : "seevia";
	    $this->set('ucapi', $ucapi);
	    $this->set('ucfounderpw', $ucfounderpw);
	    $this->set('installer_lang', $this->installer_lang);
	    $this->set('system_info', get_system_info($this->_LANG));
		if(!empty(get_system_info($this->_LANG))){
			$flag=true;
			$this->set('flag', $flag);
		}
	    $this->set('dir_checking', $dir_checking['detail']);
	    $this->set('has_unwritable_tpl', $has_unwritable_tpl);
	    $this->set('template_checking', $template_checking);
	    $this->set('rename_priv', $rename_priv);
		if(isset($_REQUEST["is_ajax"])&&$_REQUEST["is_ajax"]==1){
			Configure::write('debug',0);
	    	$this->layout="ajax";
			$result['lock']=$lock;
			$result['system_info']=get_system_info($this->_LANG);
			$result['dir_checking']=$dir_checking['detail'];
			$result['template_checking']=$template_checking;
			$result['has_unwritable_tpl']=$has_unwritable_tpl;
			$result['disabled']=$disabled;
	    	die(json_encode($result));
		}
	    $this->set('disabled', $disabled);
	    $this->set('userinterface', $ui);
		$this->set("title_for_layout",'Seevia-O2O安装');
	}
	
	function welcome(){
		$ucapi = (!empty($_POST['ucapi']))?$_POST['ucapi'] : "seevia";
	    $ucfounderpw = (!empty($_POST['ucfounderpw']))?$_POST['ucfounderpw'] : "seevia";
		$this->set('ucapi', $ucapi);
	    $this->set('ucfounderpw', $ucfounderpw);
	    $this->set('installer_lang', $this->installer_lang);
	}
	
	function setting(){
		if (!has_supported_gd()){
	        $checked = 'checked="checked"';
	        $disabled = 'disabled="true"';
	    }else{
	        $checked = '';
	        $disabled = '';
	    }
	    $show_timezone = PHP_VERSION >= '5.1' ? 'yes' : 'no';
	    $ui = (!empty($_POST['user_interface']))?$_POST['user_interface'] : "seevia";
	    $ucapi = (!empty($_POST['ucapi']))?$_POST['ucapi'] : "seevia";
	    $ucfounderpw = (!empty($_POST['ucfounderpw']))?$_POST['ucfounderpw'] : "seevia";
	    $this->set('ucapi', $ucapi);
	    $this->set('ucfounderpw', $ucfounderpw);
	    $this->set('installer_lang', $this->installer_lang);
	    $this->set('checked', $checked);
	    $this->set('disabled', $disabled);
	    $this->set('show_timezone', $show_timezone);
	    $this->set('local_timezone', get_local_timezone());
	    $this->set('timezones', get_timezone_list($this->installer_lang));
	    $this->set('userinterface', $ui);
	}
	
	function get_db_list(){
		Configure::write('debug', 0);
		$this->layout=null;
		$db_host    = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
		$db_port    = isset($_POST['db_port']) ? trim($_POST['db_port']) : '';
		$db_user    = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
		$db_pass    = isset($_POST['db_pass']) ? trim($_POST['db_pass']) : '';
	    	$db_config=array(
			'driver' => 'mysqli',
			'persistent' => false,
			'host' => $db_host,
			'port'=>$db_port,
			'login' => $db_user,
			'password' => $db_pass,
			'database' => '',
			'prefix' => '',
			'encoding' => 'UTF8'
	    	);
	    	App::import('Core', 'ConnectionManager');
	    	include_once(ROOT_PATH . 'tools/controllers/includes/cls_json.php');
	    	$json = new JSON();
	    	try{
	    		$db =& ConnectionManager::create($db_user,$db_config);
	    		if(isset($db->connected)&&$db->connected=='1'){
	    			$databases_result = $db->query('SHOW DATABASES');
	    		}
	    	}catch(Exception $e){
	    		$result = array('msg'=>$e->getMessage());
	    		echo $json->encode($result);
	        	exit();
	    	}
	    	if(isset($databases_result)&&!empty($databases_result)){
	    		$databases=array();
	    		foreach($databases_result as $v){
	    			$databases[]=$v['SCHEMATA']['Database'];
	    		}
	    		$result = array('msg'=> 'OK', 'list'=>implode(',', $databases));
	    	}else{
	    		$result = array('msg'=> 'db_erro');
	    	}
	    	echo $json->encode($result);
	    	exit();
	}
	
	function create_config_file(){
		Configure::write('debug', 0);
		$this->layout=null;
		$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
		$db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
		$db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
		$db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
		$db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
		$timezone   = isset($_POST['timezone'])     ?   trim($_POST['timezone']) : 'Asia/Shanghai';
		if(defined('EC_DB_CHARSET')){
			$db_CHARACTER=EC_DB_CHARSET;
		}else{
			$db_CHARACTER='utf8';
		}
		$db_host2 =$db_host.":".$db_port;
		$content2 = '<?' ."php\n";
		$content2 .= "// database host\n";
		$content2 .= "\$db_host   = \"$db_host2\";\n\n";
		$content2 .= "// database name\n";
		$content2 .= "\$db_name   = \"$db_name\";\n\n";
		$content2 .= "// database username\n";
		$content2 .= "\$db_user   = \"$db_user\";\n\n";
		$content2 .= "// database password\n";
		$content2 .= "\$db_pass   = \"$db_pass\";\n\n";
		$content2 .= "// table prefix\n";
		$content2 .= "\$prefix    = \"\";\n\n";
		$content2 .= "\$timezone    = \"$timezone\";\n\n";
		$content2 .= "\$cookie_path    = \"/\";\n\n";
		$content2 .= "\$cookie_domain    = \"\";\n\n";
		$content2 .= "\$session = \"1440\";\n\n";
		$content2 .= "define('EC_CHARSET','".$db_CHARACTER."');\n\n";
		$content2 .= "define('ADMIN_PATH','admin');\n\n";
		$content2 .= "define('AUTH_KEY', 'this is a key');\n\n";
		$content2 .= "define('OLD_AUTH_KEY', '');\n\n";
		$content2 .= "define('API_TIME', '');\n\n";
		$content2 .= '?>';
		$tool_config=false;
		$db_config=false;
		$fp2 = @fopen(WWW_ROOT . '/data/tools/config.php', 'wb+');
		if ($fp2){
			if (@fwrite($fp2, trim($content2))){
				$tool_config=true;
				@fclose($fp2);
			}
			if($tool_config){
				$content = '<?' ."php\n";
				$content .= "	define('MYSQL_HOST',\"$db_host\");\n";
				$content .= "	define('MYSQL_DB',\"$db_name\");\n";
				$content .= "	define('MYSQL_LOGIN',\"$db_user\");\n";
				$content .= "	define('MYSQL_PASSWORD',\"$db_pass\");\n";
				$content .= '?>';
				$db_path=dirname(ROOT_PATH) . '/data/database.php';
				$fp = @fopen($db_path, 'wb+');
				if ($fp){
					if (@fwrite($fp, trim($content))){
						$db_config=true;
						@fclose($fp);
					}
				}
			}
		}
	    	if(!$db_config){
	    		echo "create_config_file error";
	    	}else{
	    		echo 'OK';
	    	}
		exit();
	}
	
	function create_database(){
		Configure::write('debug', 1);
		$this->layout=null;
		$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
		$db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
		$db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
		$db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
		$db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
		$timezone   = isset($_POST['timezone'])     ?   trim($_POST['timezone']) : 'Asia/Shanghai';
		$db_config=array(
			'driver' => 'mysqli',
			'persistent' => false,
			'host' => $db_host,
			'port'=>$db_port,
			'login' => $db_user,
			'password' => $db_pass,
			'database' => '',
			'prefix' => '',
			'encoding' => 'UTF8'
	    	);
		App::import('Core', 'ConnectionManager');
	    	try{
	    		$db =& ConnectionManager::create($db_user,$db_config);
	    		if(isset($db->connected)&&$db->connected=='1'){
	    			$mysql_version=(float)$db->connection->server_info;
	    			if(defined('EC_DB_CHARSET')){
	    				$db_CHARACTER=EC_DB_CHARSET;
	    			}else{
	    				$db_CHARACTER='utf8';
	    			}
	    			$db_result=$db->query('DROP DATABASE IF EXISTS '.$db_name);
	    			$sql_lasterror=$db->lastError();
	    			if($sql_lasterror==""){
		    			if($mysql_version>='4.1'){
		    				$result=$db->query('CREATE DATABASE '.$db_name.' DEFAULT CHARACTER SET '.$db_CHARACTER);
		    			}else{
		    				$result=$db->query('CREATE DATABASE '.$db_name);
					}
					$sql_lasterror=$db->lastError();
				}
	    		}else{
	    			$sql_lasterror="db_erro";
	    		}
	    	}catch(Exception $e){
	    		$result = array('msg'=>$e->getMessage());
	    		echo $json->encode($result);
	        	exit();
	    	}
		if(isset($sql_lasterror)&&$sql_lasterror!=""){
	    		echo "create_config_file error:".$sql_lasterror;
	    	}else{
	    		echo 'OK';
	    	}
	    	exit();
	}
	
	function install_base_data(){
		Configure::write('debug', 0);
		$this->layout=null;
        	$sql_files = array(
	        	WWW_ROOT . '/data/tools/o2o_CreateTable.sql',
	        	WWW_ROOT . '/data/tools/o2o_DefaultData.sql'
		);
		if(constant("Product")=="AllInOne"){
			$sql_files[] = WWW_ROOT . '/data/tools/o2o-allinone.sql';
	    	}
        	$sql_files[] = WWW_ROOT . '/data/tools/o2o_dictionaries.sql';
        	$sql_content_data=array();
        	foreach($sql_files as $v){
        		$sql_content=$this->parse_sql_file($v);
        		if(is_array($sql_content)&&!empty($sql_content))$sql_content_data=array_merge($sql_content_data,$sql_content);
        	}
        	if(empty($sql_content_data)){
        		echo "install_base_data error:sql file error";exit();
        	}
	    	$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
	    	$db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
	    	$db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
	   	$db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
	    	$db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
	    	$db_config=array(
			'driver' => 'mysqli',
			'persistent' => false,
			'host' => $db_host,
			'port'=>$db_port,
			'login' => $db_user,
			'password' => $db_pass,
			'database' => $db_name,
			'prefix' => '',
			'encoding' => 'UTF8'
	    	);
	    	App::import('Core', 'ConnectionManager');
	    	try{
	    		$db =& ConnectionManager::create($db_user,$db_config);
	    		if(isset($db->connected)&&$db->connected=='1'){
	    			foreach($sql_content_data as $v){
	    				$sql=trim($v);
	    				if($sql==''||$sql==';')continue;
	    				$db->query($sql);
	    				$sql_lasterror=$db->lastError();
	    				if($sql_lasterror!=""){
	    					break;
	    				}
	    			}
	    		}else{
	    			$sql_lasterror="db_erro";
	    		}
	    	}catch(Exception $e){
	    		$result = array('msg'=>$e->getMessage());
	    		echo $json->encode($result);
	        	exit();
	    	}
	       if(isset($sql_lasterror)&&$sql_lasterror!=""){
	        	echo "install_base_data error:".$sql_lasterror;
	    	}else{
	        	echo 'OK';
	    	}
	    	exit();
	}
	
	function create_admin_passport(){
		Configure::write('debug', 0);
		$this->layout=null;
		$admin_name         = isset($_POST['admin_name'])       ? json_str_iconv(trim($_POST['admin_name'])) : '';
		$admin_password     = isset($_POST['admin_password'])   ? trim($_POST['admin_password']) : '';
		$admin_password2    = isset($_POST['admin_password2'])  ? trim($_POST['admin_password2']) : '';
		try{
			$settings = array(
				'class' => 'Operator',
				'alias' => 'Operator',
				'table' => 'operators',
				'ds' => 'default'
			);
			ClassRegistry::init($settings);
			$OperatorModel =& ClassRegistry::getObject('Operator');
			$operator_data=array(
				'id'=>'1',
				'name'=>$admin_name,
				'password'=>md5($admin_password),
				'actions'=>'all',
				'status'=>'1'
			);
			$result=$OperatorModel->save($operator_data);
	    	}catch(Exception $e){
	    		$result = array('msg'=>$e->getMessage());
	    		echo $json->encode($result);
	        	exit();
	    	}
		if ($result === false){
			echo "create_admin_passport erro";
		}else{
			echo 'OK';
		}
	    	exit();
	}
	
	function do_others(){
		Configure::write('debug', 0);
		$this->layout=null;
		$db_host    = isset($_POST['db_host'])      ?   trim($_POST['db_host']) : '';
		$db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
		$db_user    = isset($_POST['db_user'])      ?   trim($_POST['db_user']) : '';
		$db_pass    = isset($_POST['db_pass'])      ?   trim($_POST['db_pass']) : '';
		$db_name    = isset($_POST['db_name'])      ?   trim($_POST['db_name']) : '';
		$install_demo = isset($_POST['install_demo'])   ? $_POST['install_demo'] : 0;
		$install_lang = isset($_POST['install_lang'])   ? $_POST['install_lang'] : 0;
		$sql_files=array();
		if($install_demo){
			$sql_files[]=WWW_ROOT . '/data/tools/o2o_DemoData.sql';
		}
		if($install_lang){
			$sql_files[]=WWW_ROOT . '/data/tools/lang.sql';
		}
		if(!empty($sql_files)){
			$sql_content_data=array();
	        	foreach($sql_files as $v){
	        		$sql_content=$this->parse_sql_file($v);
	        		if(is_array($sql_content)&&!empty($sql_content))$sql_content_data=array_merge($sql_content_data,$sql_content);
	        	}
	        	if(empty($sql_content_data)){
	        		echo "do_others error:sql file error";
	        		exit();
	        	}
			$db_config=array(
				'driver' => 'mysqli',
				'persistent' => false,
				'host' => $db_host,
				'port'=>$db_port,
				'login' => $db_user,
				'password' => $db_pass,
				'database' => $db_name,
				'prefix' => '',
				'encoding' => 'UTF8'
		    	);
		    	App::import('Core', 'ConnectionManager');
		    	try{
		    		$db =& ConnectionManager::create($db_user,$db_config);
		    		if(isset($db->connected)&&$db->connected=='1'){
		    			foreach($sql_content_data as $v){
		    				$sql=trim($v);
	    					if($sql==''||$sql==';')continue;
		    				$db->query($sql);
		    				$sql_lasterror=$db->lastError();
		    				if($sql_lasterror!=""){
		    					break;
		    				}
		    			}
		    		}else{
		    			$sql_lasterror="db_erro";
		    		}
		    	}catch(Exception $e){
		    		$result = array('msg'=>$e->getMessage());
		    		echo $json->encode($result);
		        	exit();
		    	}
		}
		if(isset($sql_lasterror)&&$sql_lasterror!=""){
			echo "do_others error:".$sql_lasterror;
		}else{
			//创建锁定文件
			$db_path=dirname(ROOT_PATH). '/data/install.lock';
			$fp = @fopen($db_path, 'wb+');
			if ($fp){
				if (@fwrite($fp, "install")){
					@fclose($fp);
				}
			}
			echo 'OK';
		}
	    	exit();
	}
	
	/**
	     * 获得分散的查询项
	     *
	     * @access  public
	     * @param   string      $file_path      文件的绝对路径
	     * @return  mixed       解析成功返回分散的查询项数组，失败返回false。
	     */
	function parse_sql_file($file_path){
	        /* 如果SQL文件不存在则返回false */
	        if (!file_exists($file_path))
	        {
	            return false;
	        }
	        
	        /* 记录当前正在运行的SQL文件 */
	        $this->current_file = $file_path;
		
	        /* 读取SQL文件 */
	        $sql = implode('', file($file_path));

	        /* 删除SQL注释，由于执行的是replace操作，所以不需要进行检测。下同。 */
	        $sql = $this->remove_comment($sql);

	        /* 删除SQL串首尾的空白符 */
	        $sql = trim($sql);

	        /* 如果SQL文件中没有查询语句则返回false */
	        if (!$sql)
	        {
	            return false;
	        }
		
	        /* 替换表前缀 */
	     //   $sql = $this->replace_prefix($sql);

	        /* 解析查询项 */
	        $sql = str_replace("\r", '', $sql);
	        $query_items = explode(";\n", $sql);
		
	        return $query_items;
    	} 
	
	/**
	* 过滤SQL查询串中的注释。该方法只过滤SQL文件中独占一行或一块的那些注释。
	*
	* @access  public
	* @param   string      $sql        SQL查询串
	* @return  string      返回已过滤掉注释的SQL查询串。
	*/
    	function remove_comment($sql){
		/* 删除SQL行注释，行注释不匹配换行符 */
		$sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);
		
		/* 删除SQL块注释，匹配换行符，且为非贪婪匹配 */
		//$sql = preg_replace('/^\s*\/\*(?:.|\n)*\*\//m', '', $sql);
		$sql = preg_replace('/^\s*\/\*.*?\*\//ms', '', $sql);
		
		return $sql;
    	}
}
?>