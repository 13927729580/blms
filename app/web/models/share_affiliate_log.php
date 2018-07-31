<?php

/*****************************************************************************
 * svoms  分享记录访问信息表
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
class ShareAffiliateLog extends AppModel
{
	/*
	 * @var $useDbConfig 数据库配置
	 */
	public $useDbConfig = 'oms';

	/*
	* @var $name 分享记录访问信息表
	*/
    	public $name = 'ShareAffiliateLog';
    	
    	function share_affiliate_log($controller=null){
    		if(isset($_REQUEST['share_from'])&&intval($_REQUEST['share_from'])>0){
    			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    			$server_host = 'http://'.$host;
    			$request_url=$server_host.(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
    			$ip_address=$this->real_ip();
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
    			$conditions['ShareAffiliateLog.identification']=$identification;
    			$conditions['ShareAffiliateLog.link_source']=$request_url;
    			$log_count=$this->find('first',array('conditions'=>$conditions));
    			if($log_count==0){
	    			$log_data=array(
	    				'id'=>0,
	    				'user_id'=>$_REQUEST['share_from'],
	    				'link_source'=>$request_url,
	    				'visitors_user_id'=>isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0,
	    				'identification'=>$identification,
	    				'ip_address'=>$ip_address
	    			);
	    			$this->save($log_data);
	    			//分享访问赠送积分
				if(isset($controller->configs['user_share_points'])&&intval($controller->configs['user_share_points'])>0){
					$user_id=$_REQUEST['share_from'];
					$UserModel = ClassRegistry::init('User');
					$user_info=$UserModel->findById($user_id);
					if(!empty($user_info)){
						$UserPointLogModel = ClassRegistry::init('UserPointLog');
						$UserModel->save(array('id'=>$user_id,'point'=>intval($user_info['User']['point'])+intval($controller->configs['user_share_points'])));
						$point_log_data = array(
							'id' => 0,
							'user_id' => $user_id,
							'log_type'=>'S',
							'point'=>$user_info['User']['point'],
							'point_change' =>$controller->configs['user_share_points'],
							'system_note' => $controller->ld['share']." ".$request_url
						);
        					$UserPointLogModel->save($point_log_data);
        					$UserPointLogModel->point_notify($point_log_data);
					}
				}
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
}