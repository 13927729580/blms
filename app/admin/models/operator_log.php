<?php

/*****************************************************************************
 * svsys 管理员日志
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
class OperatorLog extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    public $name = 'OperatorLog';

     //重载log
    public function log($info, $adminid)
    {
        $conf = new Configure();
        $cookie = new CookieComponent();
        $cookie->key = $conf->read('Security.salt');
        $cookie_session = $cookie->read('session');
        $session = isset($cookie_session) && $cookie_session != '' ? $cookie_session : session_id();
        $remak = empty($_POST) ? '' : 'post|'.serialize($_POST);
        $remak .= empty($_GET) ? '' : 'get|'.serialize($_GET);
        $loginfo = $this->find('first', array('conditions' => array('OperatorLog.operator_id' => $adminid), 'order' => 'OperatorLog.created desc'));
        if ($loginfo['OperatorLog']['session_id'] == $session) {
            //session相同,在日志内容中追加日志
            $logdata['OperatorLog']['id'] = $loginfo['OperatorLog']['id'];
            $logdata['OperatorLog']['info'] = $info.'<br>'.$loginfo['OperatorLog']['info'];
            $logdata['OperatorLog']['action_url'] = $this->AbsoluteUrl().'<br>'.$loginfo['OperatorLog']['action_url'];
            $logdata['OperatorLog']['remark'] = $remak.'<br>'.$loginfo['OperatorLog']['remark'];
            $this->save($logdata);
        } else {
            $OperatorLogs = array(
                'operator_id' => $adminid,
                'session_id' => $session,
                'ipaddress' => $this->real_ip(),
                'browser'=>$this->real_browser(),
                'action_url' => $this->AbsoluteUrl(),
                'info' => $info,
                'type' => 1,
                'remark' => $remak,
            );
            $this->saveAll(array('OperatorLog' => $OperatorLogs));
        }
    }

    /**
     * 获得用户的真实IP地址.
     *
     * @return string
     */
    public function real_ip()
    {
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

    public function AbsoluteUrl()
    {
        global $HTTP_SERVER_VARS;
        $HTTPS = @$HTTP_SERVER_VARS['HTTPS'];
        $HTTP_HOST = $HTTP_SERVER_VARS['HTTP_HOST'];
        $SCRIPT_URL = @$HTTP_SERVER_VARS['SCRIPT_URL'];
        $PATH_INFO = @$HTTP_SERVER_VARS['PATH_INFO'];
        $REQUEST_URI = $HTTP_SERVER_VARS['REQUEST_URI'];
        $SCRIPT_NAME = $HTTP_SERVER_VARS['SCRIPT_NAME'];
        $QUERY_STRING = $HTTP_SERVER_VARS['QUERY_STRING'];

        $HTTPS = @$HTTP_SERVER_VARS['HTTPS'];
        $HTTP_HOST = $_SERVER['HTTP_HOST'];
        $SCRIPT_URL = $_SERVER['REQUEST_URI'];
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        $SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
        $QUERY_STRING = $_SERVER['HTTP_HOST'];

        if (get_magic_quotes_gpc() == 1) {
            $QUERY_STRING = stripslashes($QUERY_STRING);
        }
        if ($QUERY_STRING != '') {
            $QUERY_STRING = '?'.$QUERY_STRING;
        }
        $uri_http = (((strtolower($HTTPS) == 'off') or ($HTTPS == 0)) ? 'http' : 'https').'://'.$HTTP_HOST;
        $url = '';
        if (isset($SCRIPT_URL)) {
            $url = $SCRIPT_URL;
        } elseif (isset($PATH_INFO)) {
            $url = $PATH_INFO;
        } elseif (isset($REQUEST_URI)) {
            $url = $REQUEST_URI;
        } elseif (isset($SCRIPT_NAME)) {
            $url = $SCRIPT_NAME;
        }
        if (empty($url)) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = $uri_http.$url;
        }
       // $url=$_SERVER['HTTP_REFERER'];
        return $url;
    }
    
    public function real_browser(){
		$Agent=@$_SERVER['HTTP_USER_AGENT'];
		$browseragent="";   //浏览器
		$browserversion=""; //浏览器的版本
		if (preg_match('/QQBrowser\/([^\s]+)/i',$Agent,$version)){
			$browseragent = 'QQ';
            	$browserversion = isset($version[1])?$version[1]:'';
		}else if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $Agent, $version)) {
            	$browseragent = 'OmniWeb';
            	$browserversion = isset($version[2])?$version[2]:'';
        	}else if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $Agent, $version)) {
            	$browseragent = 'Netscape';
            	$browserversion = isset($version[2])?$version[2]:'';
        	}else if (preg_match('/Lynx\/([^\s]+)/i', $Agent, $version)) {
            	$browseragent = 'Lynx';
            	$browserversion = isset($version[1])?$version[1]:'';
        	}else if(ereg('Edge/([0-9.]{1,10})',$Agent,$version)){
        		$browserversion=$version[1];
			$browseragent="Edge";
        	}else if (ereg('MSIE ([0-9].[0-9]{1,2})',$Agent,$version)) {
			$browserversion=$version[1];
			$browseragent="IE";
		} else if(ereg('rv:([0-9.]{1,5})',$Agent,$version)){
			$browserversion=$version[1];
			$browseragent="IE";
		}else if (ereg( 'Opera/([0-9]{1,2}.[0-9]{1,2})',$Agent,$version)) {
			$browserversion=$version[1];
			$browseragent="Opera";
		} else if (ereg( 'Firefox/([0-9.]{1,20})',$Agent,$version)) {
			$browserversion=$version[1];
			$browseragent="Firefox";
		}else if (ereg( 'Chrome/([0-9.]{1,20})',$Agent,$version)) {
			$browserversion=$version[1];
			$browseragent="Chrome";
		}else if (ereg( 'Safari/([0-9.]{1,20})',$Agent,$version)) {
			$browseragent="Safari";
			ereg( 'Version/([0-9.]{1,20})',$Agent,$version);
			$browserversion=isset($version[1])?$version[1]:'';
		}else {
			$browserversion="";
			$browseragent="Unknown";
		}
		return $browseragent.$browserversion;
    } 
}
