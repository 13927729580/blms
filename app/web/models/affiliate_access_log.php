<?php
/**
 * 	AffiliateAccessLog
 */
class AffiliateAccessLog extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';

	function access_log($controller){
		if(isset($_REQUEST['affiliate_from'])&&intval($_REQUEST['affiliate_from'])>0){
			$ip_address=$this->real_ip();
			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    			$server_host = 'http://'.$host;
    			$request_url=$server_host.(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
			if(isset($_COOKIE['share_identification'])&&$_COOKIE['share_identification']!=''){
				$identification=$_COOKIE['share_identification'];
			}else if(isset($_SESSION['share_identification'])&&$_SESSION['share_identification']!=''){
				$identification=$_SESSION['share_identification'];
			}else{
				$identification=$this->getGuid();
			}
			setcookie('share_identification',$identification, time() + 3600*24*365);
			$_SESSION['share_identification']=$identification;
			$conditions=array();
			$conditions['AffiliateAccessLog.identification']=$identification;
			$conditions['AffiliateAccessLog.affiliate_channel_id']=$_REQUEST['affiliate_from'];
			$access_info=$this->find('first',array('conditions'=>$conditions));
			if(empty($access_info)){
				$log_data=array(
	    				'id'=>0,
	    				'affiliate_channel_id'=>$_REQUEST['affiliate_from'],
	    				'link_source'=>$request_url,
	    				'visitors_user_id'=>isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0,
	    				'identification'=>$identification,
	    				'ip_address'=>$ip_address,
	    				'browser'=>$this->getbrowser()
	    			);
	    			$this->save($log_data);
			}
		}
	}
	
	function getGuid() {
		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-" 
		$uuid = substr($charid, 0, 8).$hyphen 
		.substr($charid, 8, 4).$hyphen 
		.substr($charid,12, 4).$hyphen 
		.substr($charid,16, 4).$hyphen 
		.substr($charid,20,12);
		return $uuid;
	}

	function real_ip(){
		static $realip = null;
		if ($realip !== null) {
		return $realip;
		}
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
				foreach ($arr as $ip) {
					$ip = trim($ip);
					if ($ip != 'unknown') {
						$realip = $ip;
						break;
					}
				}
			} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			} else {
				if (isset($_SERVER['REMOTE_ADDR'])) {
					$realip = $_SERVER['REMOTE_ADDR'];
				} else {
					$realip = '0.0.0.0';
				}
			}
		} else {
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			} elseif (getenv('HTTP_CLIENT_IP')) {
				$realip = getenv('HTTP_CLIENT_IP');
			} else {
				$realip = getenv('REMOTE_ADDR');
			}
		}
		preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
		$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
		return $realip;
	}
	
	public function getbrowser(){
	        global $_SERVER;
	        $agent = $_SERVER['HTTP_USER_AGENT'];
	        $browser = '';
	        $browser_ver = '';
	        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
	            $browser = 'OmniWeb';
	            $browser_ver = $regs[2];
	        }
	        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
	            $browser = 'Netscape';
	            $browser_ver = $regs[2];
	        }
	        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
	            $browser = 'Safari';
	            $browser_ver = $regs[1];
	        }
	        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
	            $browser = 'Internet Explorer';
	            $browser_ver = $regs[1];
	        }
	        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
	            $browser = 'Opera';
	            $browser_ver = $regs[1];
	        }
	        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
	            $browser = '(Internet Explorer '.$browser_ver.') NetCaptor';
	            $browser_ver = $regs[1];
	        }
	        if (preg_match('/Maxthon/i', $agent, $regs)) {
	            $browser = '(Internet Explorer '.$browser_ver.') Maxthon';
	            $browser_ver = '';
	        }
	        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
	            $browser = 'FireFox';
	            $browser_ver = $regs[1];
	        }
	        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
	            $browser = 'Lynx';
	            $browser_ver = $regs[1];
	        }
		if (preg_match('/QQBrowser\/([^\s]+)/i',$agent,$regs)){
			$browser = 'QQ';
			$browser_ver = $regs[1];
		}
		if (preg_match('/Chrome\/([^\s]+)/i',$agent,$regs)){
			$browser = 'Chrome';
			$browser_ver = $regs[1];
		}
		if (preg_match('/MicroMessenger\/([^\s]+)/i',$agent,$regs)){
			$browser = 'Wechat';
			$browser_ver = $regs[1];
		}
	        if ($browser != '') {
	            return $browser.' '.$browser_ver;
	        } else {
	            return 'Unknow browser';
	        }
    	}
}
