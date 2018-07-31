<?php 
if(isset($ld_js)){ 
	foreach($ld_js as $k=>$v){
		echo "var ".$v['Dictionary']['name']."='".$v['Dictionary']['value']."';";
	}
}
if(isset($configs['price_format'])&&$configs['price_format']!=""){
		echo "var js_config_price_format='".$configs['price_format']."';";
}
if(isset($server_host)){
		echo "var j_server_host='".$server_host."';";
}
if(isset($webroot)){
		echo "var webroot='".$webroot."';";
}
?>