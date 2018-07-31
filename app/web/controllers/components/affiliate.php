<?php
class AffiliateComponent extends Object{
	
	function shorturl($url){
		$short_link_model = new Model(false, 'short_links');
		$short_link_Info=$short_link_model->find('first',array('conditions'=>array('link_source'=>$url)));
		if(!empty($short_link_Info)){
			return $short_link_Info['Model']['link'];
		}else{
			$sUrl=$this->generate_shorturl($url);
			$short_link_data=array(
				'id'=>0,
				'link_source'=>$url,
				'link'=>$sUrl
			);
			$short_link_model->save($short_link_data);
			return $sUrl;
		}
	}
	
	/*
		产生短连接
	*/
	public function generate_shorturl($url){
		$url= crc32($url);
		$result= sprintf("%u", $url);
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$server_host=$http_type.$host;
		$webroot = isset($_SERVER['DOCUMENT_URI']) ? dirname(dirname($_SERVER['DOCUMENT_URI'])) : (isset($_SERVER['REQUEST_URI']) ? dirname(dirname($_SERVER['REQUEST_URI'])) : '/');
		$webroot = str_replace('//','/',$webroot);
		$sUrl= $webroot;
		while($result>0){
			$s= $result%62;
			if($s>35){
				$s= chr($s+61);
			} elseif($s>9 && $s<=35){
				$s= chr($s+ 55);
			}
			$sUrl.= $s;
			$result= floor($result/62);
		}
		return $server_host.$sUrl;
	}
	
	function QRImage($result=''){
		App::import('Vendor', 'Phpqcode', array('file' => 'phpqrcode.php'));
		QRcode::png($result,false,0,8,1);
	}
}
