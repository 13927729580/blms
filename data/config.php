<?php
/**
 * $seevia$
 * $Id$
*/
	define('debug', 1);
	define('Product', "AllInOne");
	define('Version', "v0.9");
	define('IMG_HOST', "");
	define('CDN_PATH', "");
	//项目版本
	define('ProjectVersion', '1.0.0');
		
		

	//配置文件密钥
	Configure::write('HR.md5key', md5(date('Y-m-d')));
	
	//课程数量限制
	Configure::write('HR.max_course_total', 10);
	Configure::write('HR.max_course_read', 10);
	
	//评测数量限制
	Configure::write('HR.max_evaluation_total', 10);
	Configure::write('HR.max_evaluation_examination', 10);
	
	//活动数量限制
	Configure::write('HR.max_activity_total', 10);
	Configure::write('HR.max_activity_user', 10);
	
	//域名限制
	$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
	$post=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'80';
	$server_host = $http_type.$host.($post!='80'&&$post!='443'?(":".$post):'');
	Configure::write('HR.server_host',$server_host);
	
	//域名限制
	Configure::write('HR.version','免费版');

?>