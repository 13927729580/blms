<?php

/*****************************************************************************
 * Seevia 用户中心验证码
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为AuthnumsController的用于获取布局控制器.
 */
class AuthnumsController extends AppController{
	/*
	*	@var $name
	*	@var $helpers
	*	@var $uses
	*/
	public $name = 'Authnums';
	public $helpers = array('Html');
	public $uses = array('NotifyTemplateType');
	public $components = array('Notify');
	
	/**
	*	函数 index 用于获取手机验证码
	*/
	public function index(){
		Configure::write('debug',0);
		$this->layout = 'ajax';
		
		$result=array();
		$result['code']='0';
		$result['message']='';
		if ($this->RequestHandler->isPost()) {
			$mobile=isset($_POST['mobile'])?$_POST['mobile']:'';
			$email=isset($_POST['email'])?$_POST['email']:'';
			if(trim($mobile)!=""){
				$phone_code_number=rand(1000,9999);
				$lifeTime = 60*60*3;
				$phone_code_key="phone_code_number{$mobile}";
				setcookie($phone_code_key,$phone_code_number, time() + $lifeTime, "/");
				$Notify_template=$this->NotifyTemplateType->typeformat('verification_code','sms');
				if(!empty($Notify_template)){
					$content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
					@eval("\$content = \"$content\";");
				}else{
					$content="本次短信验证码为：{$phone_code_number},有效期为3分钟";
				}
				$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
				$sms_result=$this->Notify->send_sms($mobile,$content,$sms_kanal,$this->configs);
				if(isset($sms_result['code'])&&$sms_result['code']=='1'){
					$result['code']='1';
					$result['message']=$this->ld['successfully'];
				}else{
					$result['message']=isset($sms_result['message'])&&trim($sms_result['message'])!=""?$sms_result['message']:$this->ld['send_failed'];
				}
			}else if($email!=""){
				$email_code_number=rand(1000,9999);
				$lifeTime = 60*60*3;
				$email_code_key="email_code_number{$email}";
				setcookie($email_code_key,$email_code_number, time() + $lifeTime, "/");
				$Notify_template=$this->NotifyTemplateType->typeformat('verification_code','email');
				if(!empty($Notify_template)){
					$subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
	    				@eval("\$subject = \"$subject\";");
	    				$html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
					@eval("\$html_body = \"$html_body\";");
					$text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
					@eval("\$text_body = \"$text_body\";");
				}else{
					$subject=$this->ld['verify_code'];
					$html_body = $text_body="本次邮件验证码为：{$email_code_number},有效期为3分钟";
				}
				$mail_send_queue = array(
			                'id' => '',
			                'sender_name' => $this->configs['shop_name'],
			                'receiver_email' => $email,//接收人姓名;接收人地址
			                'cc_email' => "",
			                'bcc_email' => "",
			                'title' => $subject,
			                'html_body' => $html_body,
			                'text_body' => $text_body,
			                'sendas' => 'html',
			                'flag' => 0,
			                'pri' => 0
		        	);
				$mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
				if($mail_result==true){
					$result['code']='1';
					$result['message']=$this->ld['successfully'];
				}else{
					$result['message']=isset($mail_result)&&is_string($mail_result)&&trim($mail_result)!=""?$mail_result:$this->ld['send_failed'];
				}
			}else{
				$result['message']=$this->ld['send_failed'];
			}
		}
		die(json_encode($result));
	}
    
	/**
	 *	函数 qrcode 用于生成二维码
	 */
	function qrcode(){
		App::import('Vendor', 'phpqrcode', array('file' => 'phpqrcode.php'));
		Configure::write('debug',0);
		$this->layout = 'ajax';
		$phpqrcode_content=isset($_REQUEST['qrcode_content'])?trim($_REQUEST['qrcode_content']):$this->server_host;
		$phpqrcode_size=isset($_REQUEST['size'])?intval($_REQUEST['size']):5;
		$phpqrcode_size=$phpqrcode_size>0?$phpqrcode_size:5;
		$level = 'L';
		QRcode::png($phpqrcode_content,false,$level,$phpqrcode_size);
		die();
	}
}