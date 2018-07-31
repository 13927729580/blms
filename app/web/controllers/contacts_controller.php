<?php

/*****************************************************************************
 * Seevia 专题管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为ContactsController的控制器
 *控制联系方式.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ContactsController extends AppController
{
    public $name = 'Contacts';
    public $helpers = array('Html');
    public $uses = array('MailTemplate','Contact','ContactConfig','MailSendQueue','Application','ApplicationConfig','ApplicationConfigI18n','InformationResourceI18n','Resource');
    public $components = array('RequestHandler','Notify');
    /**
     *公司管理页.
     */
    public function index()
    {
    	 $_GET=$this->clean_xss($_GET);
        $this->layout = 'default_full';
        $this->pageTitle = $this->ld['contact_us'].' - '.$this->configs['shop_title'];
        $params['industry'] = isset($this->configs['contacts-industry']) ? $this->configs['contacts-industry'] : '';
        $params['learn_us'] = isset($this->configs['contacts-learn-us']) ? $this->configs['contacts-learn-us'] : '';
        $this->page_init($params);
        
        //资源信息
        $contact_us_type = array();
        $contact_us_type_data = !empty($this->system_resources['contact_us_type']) && sizeof($this->system_resources['contact_us_type']) > 1 ? $this->system_resources['contact_us_type'] : array();
        if (isset($contact_us_type_data[''])) {
            unset($contact_us_type_data['']);
        }
        foreach ($contact_us_type_data as $k => $v) {
            $contact_us_type[] = $k;
        }
        if (isset($_GET['type']) && in_array($_GET['type'], $contact_us_type)) {
            $this->set('contact_us_type', $_GET['type']);
        }
        if (!empty($contact_us_type_data)) {
            $this->set('contact_us_type_data', $contact_us_type_data);
        }
        $this->ur_heres[] = array('name' => $this->ld['contact_us'],'url' => '/contacts/');
        $industry = $this->Config->find('first', array('conditions' => array('Config.code' => 'contacts-industry')));
        $learn_us = $this->Config->find('first', array('conditions' => array('Config.code' => 'contacts-learn-us')));
        $this->set('industry', explode(';', $industry['ConfigI18n']['value']));
        $this->set('learn_us', explode(';', $learn_us['ConfigI18n']['value']));
        if ($this->RequestHandler->isPost()) {
			Configure::write('debug', 1);
			$this->layout='ajax';
			$contact_type = isset($this->data['Contact']['type']) && isset($contact_us_type_data[$this->data['Contact']['type']]) ? $contact_us_type_data[$this->data['Contact']['type']] : '';
			$Contact_data=isset($this->data['Contact'])?$this->data['Contact']:array();
			$ContactConfig_data=isset($this->data['ContactConfig'])?$this->data['ContactConfig']:array();
			$contact_data_count=0;
			foreach($Contact_data as $v){
				if(empty($v)){
					$contact_data_count++;
				}else if(trim($v)==""){
					$contact_data_count++;
				}
			}
			if($contact_data_count>3){
				$result_arr['code'] = 0;
				$result_arr['msg'] = $this->ld['save_basic_info'];
				die(json_encode($result_arr));
			}
	            $Contact_data['ip_address'] = $this->real_ip();
	            $Contact_data['browser'] = $this->getbrowser();
	            $Contact_data['locale'] = LOCALE;
			$this->Contact->save($Contact_data);
			$contact_id=$this->Contact->id;
			if(isset($ContactConfig_data)&&!empty($ContactConfig_data)){
				foreach($ContactConfig_data as $k=>$v){
					$ContactConfig_info=array();
					$ContactConfig_info['id']='0';
					$ContactConfig_info['type']=isset($Contact_data['type'])?$Contact_data['type']:0;
					$ContactConfig_info['contact_id']=$contact_id;
					$ContactConfig_info['code']=$k;
					$ContactConfig_info['value']=$v;
					$ContactConfig_info['status']='1';
					$this->ContactConfig->save($ContactConfig_info);
				}
			}
			extract($Contact_data,EXTR_PREFIX_ALL,'Contact');
			if(!empty($ContactConfig_data)){
				extract($ContactConfig_data,EXTR_PREFIX_ALL,'ContactConfig');
			}
			$email_text = $this->ld['contact'].':'.$Contact_contact_name.'<br>';
			if(isset($Contact_email)&&trim($Contact_email)!=""){
				$email_text .= $this->ld['e-mail'].':'.trim($Contact_email).'<br>';
			}
			if(isset($Contact_mobile)&&trim($Contact_mobile)!=""){
				$email_text .= $this->ld['mobile'].':'.trim($Contact_mobile).'<br>';
			}
			if(isset($contact_type)&&trim($contact_type)!=""){
				$email_text .= $this->ld['type'].':'.trim($contact_type).'<br>';
			}
			if(isset($Contact_company)&&trim($Contact_company)!=""){
				$email_text .= $this->ld['company_name'].':'.trim($Contact_company).'<br>';
			}
			if(isset($Contact_company_url)&&trim($Contact_company_url)!=""){
				$email_text .= $this->ld['domain'].':'.trim($Contact_company_url).'<br>';
			}
			if(isset($Contact_qq)&&trim($Contact_qq)!=""){
				$email_text .= 'QQ:'.trim($Contact_qq).'<br>';
			}
			if(isset($Contact_skype)&&trim($Contact_skype)!=""){
				$email_text .= 'SKYPE:'.trim($Contact_skype).'<br>';
			}
			$other_contact_info="";
			$conditions=array();
			$conditions['ContactConfig.type']=isset($this->data['Contact']['type'])?$this->data['Contact']['type']:0;
			$conditions['ContactConfig.contact_id']=0;
			$conditions['ContactConfig.status']='1';
			$contact_config_data=$this->ContactConfig->find('all',array('conditions'=>$conditions,'order'=>'ContactConfig.orderby'));
			if(!empty($contact_config_data)){
				foreach($contact_config_data as $k=>$v){
					$contact_config_code=$v['ContactConfig']['code'];
					$contact_config_value="";
					if(isset($ContactConfig_data[$contact_config_code])&&trim($ContactConfig_data[$contact_config_code])!=""){
						$contact_config_value= trim($ContactConfig_data[$contact_config_code]);
						$config_option=array();
						$config_option_txt=trim($v['ContactConfigI18n']['contact_config_values']);
						if($config_option_txt!=""){
							$config_option_arr=split("\r\n",$config_option_txt);
							foreach($config_option_arr as $kk=>$vv){
								$config_option_info=split(":",$vv);
								$config_option[$config_option_info[0]]=isset($config_option_info[1])?$config_option_info[1]:$config_option_info[0];
							}
							if(isset($config_option[$contact_config_value])){
								$contact_config_value=$config_option[$contact_config_value];
							}
						}
					}
					if($contact_config_value!=""){
						$other_contact_info .= $v['ContactConfigI18n']['name'].':'.$contact_config_value.'<br>';
					}
				}
			}
			$email_text .= $other_contact_info;
			$email_text .= $this->ld['date'].':'.date("Y-m-d H:i:s");
			$receiver_email=array();
			$receiver_email_str = isset($this->configs['contacts-email']) ? $this->configs['contacts-email'] : '';
			$receiver_email_arr = explode(',', $receiver_email_str);
			foreach ($receiver_email_arr as $v) {
				if($v=="")continue;
				$receiver_email[] = $v.';'.$v;
			}
			if(!empty($receiver_email)){
				$shop_name = $this->configs['shop_name'];
				$email_template=$this->MailTemplate->find("first",array('conditions'=>array('MailTemplate.code'=>'contact_us',"MailTemplate.status"=>'1')));
				if(empty($email_template)){
			      	$mail_send_queue = array(
							'id' => '',
							'sender_name' => $shop_name,
							'receiver_email' => $receiver_email,
							'cc_email' => ';',
							'bcc_email' => ';',
							'title' => $this->ld['contact_email'],
							'html_body' => $email_text,
							'text_body' => $email_text,
							'sendas' => 'html',
							'flag' => 0,
							'pri' => 0,
					);
			      }else{
			      	$subject = $email_template['MailTemplateI18n']['title'];
			      	$html_body = $email_template['MailTemplateI18n']['html_body'];
			      	$text_body = $email_template['MailTemplateI18n']['text_body'];
			      	$shop_url = $this->server_host.$this->webroot;
			      	eval("\$subject = \"$subject\";");
			      	eval("\$html_body = \"$html_body\";");
			      	eval("\$text_body = \"$text_body\";");
			      	$mail_send_queue = array(
							'id' => '',
							'sender_name' => $shop_name,
							'receiver_email' => $receiver_email,
							'cc_email' => ';',
							'bcc_email' => ';',
							'title' => $subject,
							'html_body' => $html_body,
							'text_body' => $text_body,
							'sendas' => 'html',
							'flag' => 0,
							'pri' => 0,
						);
			      }
			      $result = $this->Notify->send_email($mail_send_queue, $this->configs);
			}
			$msg = isset($this->configs['contactus_conversion']) && $this->configs['contactus_conversion'] != '' ? $this->configs['contactus_conversion'] : $this->ld['information_submitted'];
			$result_arr['code'] = 1;
			$result_arr['msg'] = $msg;
			die(json_encode($result_arr));
		}
			$js_languages = array(
								   'company_name_not_empty' => $this->ld['company_name'].$this->ld['can_not_empty'],
								   'invalid_email' => $this->ld['email'].$this->ld['format'].$this->ld['not_correct'],
								'please_choose_company_type' => $this->ld['please_select'].$this->ld['industry'],
							//	"connect_person_can_not_empty" =>  $this->ld['connect_person'].$this->ld['can_not_empty'],
								'mobile_can_not_empty' => $this->ld['mobile'].$this->ld['can_not_empty'],
								'content_can_not_empty' => $this->ld['content'].$this->ld['can_not_empty'],
								);
			$this->set('js_languages', $js_languages);
		}
		
		/*
			加载联系我们配置
		*/
		function ajax_contact_config($contact_type=''){
			Configure::write('debug', 0);
        		$this->layout='ajax';
			
			$result=array();
			$result['code']='0';
			$result['data']='';
			$conditions=array();
			$conditions['ContactConfig.type']=$contact_type;
			$conditions['ContactConfig.contact_id']=0;
			$conditions['ContactConfig.status']='1';
			$contact_config_info=$this->ContactConfig->find('all',array('conditions'=>$conditions,'order'=>'ContactConfig.orderby'));
			if(!empty($contact_config_info)){
				$contact_config_data=array();
				foreach($contact_config_info as $v){
					$config_option=array();
					$config_option_txt=trim($v['ContactConfigI18n']['contact_config_values']);
					if($config_option_txt!=""){
						$config_option_arr=split("\r\n",$config_option_txt);
						foreach($config_option_arr as $kk=>$vv){
							$config_option_info=split(":",$vv);
							$config_option[$config_option_info[0]]=isset($config_option_info[1])?$config_option_info[1]:$config_option_info[0];
						}
					}
					$v['ContactConfigOption']=$config_option;
					$contact_config_data[]=$v;
				}
				
				$result['code']='1';
				$result['data']=$contact_config_data;
			}
			die(json_encode($result));
		}
		
		
        /**
         *实际id.
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
    /**
     *获得游览器.
     */
    public function getbrowser()
    {
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
		
        if ($browser != '') {
            return $browser.' '.$browser_ver;
        } else {
            return 'Unknow browser';
        }
    }
    
    function  ajax_uplad_contacts(){
		Configure::write('debug', 1);
		$this->layout='ajax';
		$result=array();
		$result['code']='0';
		$result['message']='上传失败';
		if(isset($_FILES['contact_file'])){
			$file_root = '/media/Contacts/';
            		$fileaddr = WWW_ROOT.$file_root;
            		$this->mkdirs($fileaddr);
            		if($_FILES['contact_file']['error']==0){
            			$userfile_tmp = $_FILES['contact_file']['tmp_name'];
            			$userfile_name = $_FILES['contact_file']['name'];
            			$filename = basename($userfile_name);
                    	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
                    	$file_location = $fileaddr.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                    	$file_name = $file_root.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                    	if (move_uploaded_file($userfile_tmp, $file_location)) {
                    		$result['code'] = '1';
                    		$result['message'] = '上传成功';
                            	$result['file_url'] = $file_name;
                    	}
            		}
		}
		die(json_encode($result));
    }
    
    
    public function mkdirs($path, $mode = 0777){
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
                chmod($thispath, $mode);
            } else {
                @chmod($thispath, $mode);
            }
        }
    }
}
