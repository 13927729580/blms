<?php
if(isset($ld_js)){ foreach($ld_js as $k=>$v){
	echo "var ".$v['LanguageDictionary']['name']."='".$v['LanguageDictionary']['value']."';";
}
if(isset($configs['price_format'])&&$configs['price_format']!=""){
	echo "var js_config_price_format='".$configs['price_format']."';";
}
if(isset($configs['detail_page_img_auto_scaling'])){
	echo "var j_config_detail_page_img_auto_scaling='".$configs['detail_page_img_auto_scaling']."';";
}
if(isset($this->base)){
	echo "var web_base='".$this->base."';";
}else{
	echo "var web_base='';";
}
if(isset($configs)&&!empty($configs)){
	echo "var js_login_user_data=null;";
}
echo "var js_configs=".json_encode($configs).";";
if(isset($_SESSION['User'])&&!empty($_SESSION['User'])){
	$session_user_data=array(
		'User'=>array(
			'id'=>$_SESSION['User']['User']['id'],
			'name'=>$_SESSION['User']['User']['name'],
			'first_name'=>$_SESSION['User']['User']['first_name'],
			'mobile'=>$_SESSION['User']['User']['mobile'],
			'email'=>$_SESSION['User']['User']['email'],
			'img01'=>$_SESSION['User']['User']['img01']
		)
	);
	echo "js_login_user_data=".json_encode($session_user_data).";";
}

$nextWeek = time() + (7 * 24 * 60 * 60);
$day_tmp=date('Y-m-d', $nextWeek);
$today=date('Y-m-d');
$year = substr($day_tmp, 0,4); 
$month = substr($day_tmp, 5, 2); 
$day = substr($day_tmp, 8, 2); 
$year1 = substr($today, 0,4); 
$month1 = substr($today, 5, 2); 
$day1 = substr($today, 8, 2); 
header("Last-Modified: ".gmdate("M d Y H:i:s", mktime (0,0,0,$day1,$month1,$year1)));
header("Expires: ".gmdate("M d Y H:i:s", mktime (0,0,0,$day,$month,$year)));
header("Cache-Control: max-age=3");
}?>