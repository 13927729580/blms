<?php
class NotifyComponent{
	public $to_mobile = "";//手机号
	public $sms_content = "";//短信内容
	public $sms_params=array();//短信发送参数
	
	/**
	 *	发送邮件
	 *
	 *	@var $mailsendqueue	邮件发送内容对象
	 *	@var $mail_config	邮箱参数设置
	 *	@var $mailsendname	发送者信息(sendname;sendemail)
	 *	@var $debug	调试标志位,失败时返回原因
	 */
	public function send_email($mailsendqueue,$mail_config,$mailsendname=''){
		if(isset($mailsendqueue['receiver_email'])&&is_array($mailsendqueue['receiver_email'])){
			$receiver_email=implode(chr(13).chr(10),$mailsendqueue['receiver_email']);
			$receiver_email=trim($receiver_email);
			$mailsendqueue['receiver_email']=$receiver_email;
		}
		if(isset($mailsendqueue['cc_email'])&&is_array($mailsendqueue['cc_email'])){
			$cc_email=implode(chr(13).chr(10),$mailsendqueue['cc_email']);
			$cc_email=trim($cc_email);
			$mailsendqueue['cc_email']=$cc_email;
		}
		if(isset($mailsendqueue['bcc_email'])&&is_array($mailsendqueue['bcc_email'])){
			$bcc_email=implode(chr(13).chr(10),$mailsendqueue['bcc_email']);
			$bcc_email=trim($bcc_email);
			$mailsendqueue['bcc_email']=$bcc_email;
		}
		$mail_queue_model = new Model(false, 'mail_send_queues');
		$mail_queue_model->save($mailsendqueue);
		$send_mail_conditions=array(
			'flag <'=>5,
			'receiver_email <>'=>'',
			'or'=>array(
				'send_time'=>null,
				'send_time <'=>date('Y-m-d H:i:00')
			)
		);
		$mail_list=$mail_queue_model->find('all',array('conditions'=>$send_mail_conditions));
		foreach($mail_list as $v){
			//队列发送
			$mail_send_flag=$this->phpmailer_send($v['Model'],$mail_config,$mailsendname);
			if($mail_send_flag===true){
				$mail_queue_model->save(array('id'=>$v['Model']['id'],'flag'=>5));
			}else{
				$mail_queue_model->save(array('id'=>$v['Model']['id'],'flag'=>$v['Model']['flag']++));
			}
		}
	}
	
	public function phpmailer_send($mailsendqueue,$mail_config,$mailsendname=''){
		App::import('Vendor', 'Phpmailer', array('file' => 'phpmailer/class.phpmailer.php'));
		$mail = new PHPMailer();
		if (isset($mail_config['mail-debug'])&&$mail_config['mail-debug']==1) {
			$mail->SMTPDebug=true;
		}
		if (isset($mail_config['mail-service'])&&$mail_config['mail-service']== 1) {
			$mail->IsSMTP();
			$mail->Username = isset($mail_config['mail-account'])?trim($mail_config['mail-account']):'';
			$mail->Password = isset($mail_config['mail-password'])?trim($mail_config['mail-password']):'';
		} else {
			$mail->IsMail();// set mailer to use SMTP
		}
		$mail->SMTPAuth =isset($mail_config['mail-requires-authorization']) ? trim($mail_config['mail-requires-authorization']) : 1;// turn on SMTP authentication
		$mail->Port = isset($mail_config['mail-port'])?trim($mail_config['mail-port']):25;
		$mail->Host = isset($mail_config['mail-smtp'])?trim($mail_config['mail-smtp']):'';
		$mail->From = isset($mail_config['mail-address'])?trim($mail_config['mail-address']):$mail_config['mail-account'];
		$mail->FromName = isset($mail_config['sender_name'])?trim($mail_config['sender_name']):(isset($mail_config['shop_name'])?$mail_config['shop_name']:'No Name');
             if (isset($mail_config['mail-ssl'])&&$mail_config['mail-ssl']=="1") {
			$mail->SMTPSecure = 'ssl';
			$mail_account=isset($mail_config['mail-account'])?trim($mail_config['mail-account']):'';
			$mail_account_info=explode('@',$mail_account);
			$mail->Helo=isset($mail_account_info[1])?$mail_account_info[1]:'';
             }
        	if($mailsendname!=""){
        		$emailname = explode(';', $mailsendname);
			$mail->AddReplyTo(isset($emailname[1])?$emailname[1]:$emailname[0],$emailname[0]);
        	}else{
        		$mail->AddReplyTo($mail->From,$mail->FromName);
        	}
        	$receiver_email_list=explode(chr(13).chr(10),trim($mailsendqueue['receiver_email']));
        	foreach($receiver_email_list as $receiver_email){
        		if (strpos($receiver_email, ';')) {
				$to_email_and_name = explode(';', $receiver_email);
				$mail->AddAddress($to_email_and_name[1], $to_email_and_name[0]);
			}else{
				$mail->AddAddress($receiver_email, $receiver_email);
			}
        	}
        	$cc_email_list=explode(chr(13).chr(10),trim($mailsendqueue['cc_email']));
        	foreach($cc_email_list as $cc_email){
        		if(trim($cc_email)==''||trim($cc_email)==';')continue;
        		if (strpos($cc_email, ';')) {
				$addcc_to_email_and_name = explode(';', $v);
				$mail->AddCC($addcc_to_email_and_name[1], $addcc_to_email_and_name[0]);
			}else{
				$mail->AddCC($cc_email, $cc_email);
			}
        	}
        	$bcc_email_list=explode(chr(13).chr(10),trim($mailsendqueue['bcc_email']));
        	foreach($bcc_email_list as $bcc_email){
        		if(trim($bcc_email)==''||trim($bcc_email)==';')continue;
        		if (strpos($bcc_email, ';')) {
				$addbcc_to_email_and_name = explode(';', $bcc_email);
				$mail->AddAddress($addbcc_to_email_and_name[1], $addbcc_to_email_and_name[0]);
			}else{
				$mail->AddAddress($bcc_email, $bcc_email);
			}
        	}
        	$attachment_file="";//附件信息
        	if(isset($mailsendqueue['attachment'])&&!empty($mailsendqueue['attachment'])){
			$temp_file = $this->tmpfilewrite('mailattachment',stripslashes($mailsendqueue['attachment']));
			if(isset($mailsendqueue['attachment_name'])&&$mailsendqueue['attachment_name']!=''){
				$mail->AddAttachment($temp_file,$mailsendqueue['attachment_name']);
			}else{
				$mail->AddAttachment($temp_file);
			}
			$attachment_file=$temp_file;
        	}
        	$mail->CharSet = 'UTF-8';
		$mail->WordWrap = 50;
		
		$mail->IsHTML(true);
		$mail->Subject = $mailsendqueue['title'];
		$mail->Body = $mailsendqueue['html_body'];
		$mail->AltBody = $mailsendqueue['text_body'];
		$result = $mail->Send();
		if (is_file($attachment_file)) {@unlink($attachment_file);}
		if($result===true){
			$mailsendqueue['flag']=$result;
			$mailsendqueue['send_time']=date('Y-m-d H:i:s');
		}else{
			$mailsendqueue['flag']=0;
			$mailsendqueue['error_msg']=isset($mail->ErrorInfo)?$mail->ErrorInfo:$result;
			if(isset($mailsendqueue['send_time']))unset($mailsendqueue['send_time']);
		}
		if(isset($mailsendqueue['id']))unset($mailsendqueue['id']);
		if(isset($mailsendqueue['created']))unset($mailsendqueue['created']);
		if(isset($mailsendqueue['modified']))unset($mailsendqueue['modified']);
		$this_model = new Model(false, 'mail_send_histories');
		$this_model->saveAll($mailsendqueue);
		return $result===true?true:(isset($mail->ErrorInfo)?$mail->ErrorInfo:$result);
	}
	
	/*
		创建附件的临时文件
	*/
	private function tmpfilewrite($filename,$file_content){
		$tmpfname = tempnam("/tmp", $filename);
		$handle = fopen($tmpfname, "wb");
		fwrite($handle, $file_content);
		fclose($handle);
		return $tmpfname;
	}
	
	/**
	 *	短信发送
	 *
	 *	@var $to_mobile	手机号
	 *	@var $sms_content	短信内容
	 *	@var $sms_kanal	短信发送渠道
	 *	@var $sms_params	短信发送参数
	 *	@var $sending_limits	发送次数限制(3分钟)
	 */
	public function send_sms($to_mobile="",$sms_content="",$sms_kanal='0',$sms_params=array(),$sending_limits=true){
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$to_mobile=trim($to_mobile);
		$sms_content=trim($sms_content);
		if($to_mobile==""){
			$result['message']='手机号不能为空';
			return $result;
 		}else if($to_mobile!=""&&preg_match("/^1[34578]{1}\d{10}$/",$to_mobile)){ 
			$result['message']='手机号格式错误';
			return $result;
		}else if($sms_content==""){
			$result['message']='短信内容不能为空';
			return $result;
		}
		$this_model = new Model(false, 'sms_send_histories');
//		if($sending_limits==true){
//			$SmsSendQueue_info=$this_model->find('first',array('conditions'=>array('phone'=>$to_mobile,'created >='=>date("Y-m-d H:i:s",strtotime("-3 min")),'created <='=>date("Y-m-d H:i:s"))));
//			if(!empty($SmsSendQueue_info)){
//				$result['message']='不要重复发送';
//				return $result;
//			}
//		}
		$this->to_mobile=$to_mobile;
		$this->sms_content=$sms_content;
		$this->sms_params=$sms_params;
		
		$mailsendqueue=array(
			'id'=>0,
			'phone'=>$to_mobile,
			'content'=>$sms_content
		);
		switch($sms_kanal){
			case "0":
				$result=$this->juchn();
				break;
			case "1":
				$result=$this->lingkai();
				break;
			case "2":
				$result=$this->yuntongxun();
				break;
			case "3":
				$result=$this->qybor();
				break;
			case "4":
				$result=$this->sango();
				break;
			default:
				$result['message']='短信渠道不可用';
				break;
		}
		$mailsendqueue['send_date']=date("Y-m-d H:i:s");
		$mailsendqueue['flag']=$result['code'];
		$this_model->save($mailsendqueue);
		return $result;
	}
	
	/**
	 *	巨辰短信发送
	 */
	public function juchn(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$error_message=array(
			"0"=>"提交成功",
			"101"=>"无此用户",
			"102"=>"密码错",
			"103"=>"提交过快（提交速度超过流速限制）",
			"104"=>"系统忙（因平台侧原因，暂时无法处理提交的短信）",
			"105"=>"敏感短信（短信内容包含敏感词）",
			"106"=>"消息长度错（>536或<=0）",
			"107"=>"包含错误的手机号码",
			"108"=>"手机号码个数错（群发>50000或<=0;单发>200或<=0）",
			"109"=>"无发送额度（该用户可用短信数已使用完）",
			"110"=>"不在发送时间内",
			"111"=>"超出该账户当月发送额度限制",
			"112"=>"无此产品，用户没有订购该产品",
			"113"=>"extno格式错（非数字或者长度不对）",
			"115"=>"自动审核驳回",
			"116"=>"签名不合法，未带签名（用户必须带签名的前提下）",
			"117"=>"IP地址认证错,请求调用的IP地址不是系统登记的IP地址",
			"118"=>"用户没有相应的发送权限",
			"119"=>"用户已过期"
		);
		$signature = isset($sms_params['sms-signature'])?$sms_params['sms-signature']:"实玮网络";//短信签名
		$sms_content=$sms_content."【".$signature."】";
		$account=isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";//账号
		$pswd=isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";//密码
		
		$version_info=(float)(PHP_VERSION);
		if(function_exists('file_get_contents')&&($version_info<5.4||$version_info>6)){
			$request_data_format="http://120.24.167.205/msg/HttpSendSM?account=%s&pswd=%s&mobile=%s&msg=%s&needstatus=true";
			$request_url=sprintf($request_data_format,$account,$pswd,$to_mobile,urlencode($sms_content));
			$file_contents = file_get_contents($request_url); 
		}else{
			$post_url="http://120.24.167.205/msg/HttpSendSM";
			$post_data = "account=".$account."&pswd=".$pswd."&mobile=".$to_mobile."&msg=".urlencode($sms_content)."&needstatus=true";
			$timeout = 30;
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $post_url);
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
			$file_contents = curl_exec($ch);
			curl_close ($ch);
		}
		$sms_result_arr=split("\n",$file_contents);
		$sms_result_txt=isset($sms_result_arr[0])?$sms_result_arr[0]:'';
		$sms_result=split(",",$sms_result_txt);
		$sms_send_code=isset($sms_result[1])?$sms_result[1]:"-1";
		if($sms_send_code=="0"){
			$result['code']='1';
			$result['message']='发送成功';
		}else{
			$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
			$result['file_contents']=$file_contents;
		}
		return $result;
	}
	
	function qybor(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$error_message=array(
			"0"=>"提交成功",
			"101"=>"无此用户",
			"102"=>"密码错",
			"103"=>"提交过快（提交速度超过流速限制）",
			"104"=>"系统忙（因平台侧原因，暂时无法处理提交的短信）",
			"105"=>"敏感短信（短信内容包含敏感词）",
			"106"=>"消息长度错（>536或<=0）",
			"107"=>"包含错误的手机号码",
			"108"=>"手机号码个数错（群发>50000或<=0;单发>200或<=0）",
			"109"=>"无发送额度（该用户可用短信数已使用完）",
			"110"=>"不在发送时间内",
			"111"=>"超出该账户当月发送额度限制",
			"112"=>"无此产品，用户没有订购该产品",
			"113"=>"extno格式错（非数字或者长度不对）",
			"115"=>"自动审核驳回",
			"116"=>"签名不合法，未带签名（用户必须带签名的前提下）",
			"117"=>"IP地址认证错,请求调用的IP地址不是系统登记的IP地址",
			"118"=>"用户没有相应的发送权限",
			"119"=>"用户已过期"
		);
		$signature = isset($sms_params['sms-signature'])?$sms_params['sms-signature']:"实玮网络";//短信签名
		$sms_content=$sms_content."【".$signature."】";
		$account=isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";//账号
		$pswd=isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";//密码
		
		$version_info=(float)(PHP_VERSION);
		if(function_exists('file_get_contents')&&($version_info<5.4||$version_info>6)){
			$request_data_format="http://www.qybor.com:8500/shortMessage?username=%s&passwd=%s&phone=%s&msg=%s&needstatus=true&port=&sendtime=";
			$request_url=sprintf($request_data_format,$account,$pswd,$to_mobile,$sms_content);
			$file_contents = file_get_contents($request_url); 
		}else{
			$post_url="http://www.qybor.com:8500/shortMessage";
//			$post_data = array(
//				"username"=>$account,
//				"passwd"=>$pswd,
//				"phone"=>$to_mobile,
//				"msg"=>$sms_content,
//				"needstatus"=>true,
//				"port"=>'',
//				"sendtime"=>''
//			);
			$post_data = "username=".$account."&passwd=".$pswd."&phone=".$to_mobile."&msg=".urlencode($sms_content)."&needstatus=true&port=&sendtime=";
			$timeout = 30;
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $post_url);
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
			$file_contents = curl_exec($ch);
			curl_close ($ch);
		}
		$sms_result=json_decode($file_contents,true);
		$sms_send_code=isset($sms_result['respcode'])?$sms_result['respcode']:"-1";
		if($sms_send_code=="0"){
			$result['code']='1';
			$result['message']='发送成功';
		}else{
			$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
			$result['file_contents']=$file_contents;
		}
		return $result;
	}
	
	/**
	 *	凌凯短信发送
	 */
	public function lingkai(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$error_message=array(
			"-1"=>"账号未注册",
			"-2"=>"其他错误",
			"-3"=>"帐号或密码错误",
			"-5"=>"余额不足，请充值",
			"-6"=>"定时发送时间不是有效的时间格式",
			"-7"=>"提交信息末尾未签名，请添加中文的企业签名【 】",
			"-8"=>"发送内容需在1到300字之间",
			"-9"=>"发送号码为空",
			"-10"=>"定时时间不能小于系统当前时间",
			"-100"=>"IP黑名单",
			"-102"=>"账号黑名单",
			"-103"=>"IP未导白"
		);
		$signature = isset($sms_params['sms-signature'])?$sms_params['sms-signature']:"实玮网络";//短信签名
		$sms_content=$sms_content."【".$signature."】";
		$CorpID=isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";//账号
		$Pwd=isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";//密码
		try{
			$client = new SoapClient("http://mb345.com:999/ws/LinkWS.asmx?wsdl",array('encoding'=>'UTF-8'));
			$sendParam = array(
				'CorpID'=>$CorpID,
				'Pwd'=>$Pwd,
				'Mobile'=>$to_mobile,
				'Content'=>$sms_content,
				'Cell'=>'',
				'SendTime'=>''
			);
			$sms_result = $client->__call('BatchSend2',array("BatchSend2"=>$sendParam));
			$sms_send_code=isset($sms_result->BatchSend2Result)?intval($sms_result->BatchSend2Result):0;
			if($sms_send_code>0){
				$result['code']='1';
				$result['message']='发送成功';
			}else{
				$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
			}
		}catch(Exception $e){
			$result['message']='发送失败';
		}
		return $result;
	}
	
	public function yuntongxun(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		App::import('Vendor', 'Sms', array('file' => 'CCPRestSmsSDK.php'));
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		if(!class_exists("REST")){
			$result['message']='Yuntongxun SDK not found';
			return $result;
		}
		$error_message=array(
			"000000"=>"发送成功",
			"111141"=>"主账户不存在",
			"111109"=>"请求地址Sig校验失败",
			"111181"=>"应用不存在",
			"112300"=>"接收短信的手机号码为空",
			"112301"=>"短信正文为空",
			"112302"=>"群发短信已暂停",
			"112303"=>"应用未开通短信功能",
			"112304"=>"短信内容的编码转换有误",
			"112305"=>"应用未上线，短信接收号码外呼受限",
			"112306"=>"接收模板短信的手机号码为空",
			"112307"=>"模板短信模板ID为空",
			"112308"=>"模板短信模板data参数为空",
			"112309"=>"模板短信内容的编码转换有误",
			"112310"=>"应用未上线，模板短信接收号码外呼受限",
			"112311"=>"短信模板不存在",
			"160000"=>"系统错误",
			"160031"=>"参数解析失败",
			"160032"=>"短信模板无效",
			"160033"=>"短信存在黑词",
			"160034"=>"号码黑名单",
			"160035"=>"短信下发内容为空",
			"160036"=>"短信模板类型未知",
			"160037"=>"短信内容长度限制",
			"160038"=>"短信验证码发送过频繁",
			"160039"=>"超出同模板同号天发送次数上限",
			"160040"=>"验证码超出同模板同号码天发送上限",
			"160041"=>"通知超出同模板同号码天发送上限",
			"160042"=>"号码格式有误",
			"160043"=>"应用与模板id不匹配",
			"160050"=>"短信发送失败",
			"172006"=>"主帐号为空",
			"172007"=>"主帐号令牌为空",
			"172012"=>"应用ID为空"
		);
		
		//主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid= isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";
		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken= isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";
		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId=isset($sms_params['sms_parameter3'])?trim($sms_params['sms_parameter3']):"";
		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP='app.cloopen.com';
		//请求端口，生产环境和沙盒环境一致
		$serverPort='8883';
		//REST版本号，在官网文档REST介绍中获得。
		$softVersion='2013-12-26';
		$rest = new REST($serverIP,$serverPort,$softVersion);
		$rest->setAccount($accountSid,$accountToken);
		$rest->setAppId($appId);
		$templateId=isset($sms_params['templateId'])?trim($sms_params['templateId']):"1";
		$other_request=isset($sms_params['other_request'])?trim($sms_params['templateId']):array($to_mobile,'3');
		$sms_result = $rest->sendTemplateSMS($to_mobile,$other_request,$templateId);
		$sms_send_code=isset($sms_result->statusCode)?$sms_result->statusCode:'-1';
		if($sms_send_code=='0'){
			$result['code']='1';
			$result['message']='发送成功';
		}else{
			$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
		}
		return $result;
	}
	
	
	function sango(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		$signature = isset($sms_params['sms-signature'])?$sms_params['sms-signature']:"实玮网络";//短信签名
		
		$post_url="http://47.94.226.160:8007/sendsms";
		$post_data=array(
			'appid'=>isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"",
			'pubkey'=>isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"",
			'prikey'=>isset($sms_params['sms_parameter3'])?trim($sms_params['sms_parameter3']):"",
			'content'=>$sms_content,
			'mobile'=>is_array($to_mobile)?implode(',',$to_mobile):$to_mobile,
			'sign'=>$signature,
			'type'=>0,
			'smstype'=>'1'
		);
		$post_data=json_encode($post_data);
		$timeout = 30;
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $post_url);
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    	'Content-Type: application/json',
		    	'Content-Length: ' . strlen($post_data))
		);
		$file_contents = curl_exec($ch);
		curl_close ($ch);
		$sms_result=json_decode($file_contents,true);
		if(isset($sms_result['resultcode'])&&$sms_result['resultcode']=='1'){
			$result['code']='1';
			$result['message']='发送成功';
		}else{
			$result['message']=isset($sms_result['description'])?$sms_result['description']:'发送失败';
		}
		return $result;
	}
	
	/*
		微信模板消息
	*/
	function wechat_message($post_data=array()){
		$post_data=$this->to_josn($post_data);
		$model_settings = array(
			'class' => 'OpenModel',
			'alias' => 'OpenModel',
			'table' => 'open_models',
			'ds' => 'sns'
		);
		ClassRegistry::init($model_settings);
		$open_model =& ClassRegistry::getObject('OpenModel');
		$openmodelinfo = $open_model->find('first', array('conditions' => array('OpenModel.status' => 1,'OpenModel.verify_status' => 1,"OpenModel.open_type"=>'wechat')));
		if(!empty($openmodelinfo)){
			if (!$open_model->validateToken($openmodelinfo)) {
				$openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
				$appId = $openmodelinfo['OpenModel']['app_id'];
				$appSecret = $openmodelinfo['OpenModel']['app_secret']; 
				//无效重新获取
				$accessToken = $open_model->getAccessToken($appId, $appSecret, $openType);
				$openmodelinfo['OpenModel']['token'] = $accessToken;
				$open_model->save($openmodelinfo);
	             }
	             $access_token = $openmodelinfo['OpenModel']['token'];
	             $request_url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
	             $wechat_result=array();
	             $wechat_result=$this->https_request($request_url, $post_data);
			$settings = array(
				'class' => 'OpenUserMessage',
				'alias' => 'OpenUserMessage',
				'table' => 'open_user_messages',
				'ds' => 'sns'
			);
			ClassRegistry::init($settings);
			$open_user_message =& ClassRegistry::getObject('OpenUserMessage');
			$open_user_message->saveMsg(
				'send_TMsg', json_encode($post_data), 0,
				$openmodelinfo['OpenModel']['open_type_id'], 0,
				isset($data_result['errcode'])&&$data_result['errcode']=='0' ? 'ok' : 'no',
				json_encode($wechat_result)
			);
		}
	}
	
	
    	/*
        	$data   需要转换josn提交的数据
    	*/
    	public function to_josn($data)
    	{
        	$this->arrayRecursive($data, 'urlencode');
        	$json = json_encode($data);

        	return urldecode($json);
    	}

    	/************************************************************** 
    	* 对数组中所有元素做处理,保留中文 
    	* @param string &$array 要处理的数组
    	* @param string $function 要执行的函数 
    	* @return boolean $apply_to_keys_also 是否也应用到key上 
    	* @access public 
    	* 
    	*************************************************************/
    	public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    	{
	        static $recursive_counter = 0;
	        if (++$recursive_counter > 1000) {
	            die('possible deep recursion attack');
	        }
	        foreach ($array as $key => $value) {
	            if (is_array($value)) {
	                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
	            } else {
	                $array[$key] = $function($value);
	            }
	            if ($apply_to_keys_also && is_string($key)) {
	                $new_key = $function($key);
	                if ($new_key != $key) {
	                    $array[$new_key] = $array[$key];
	                    unset($array[$key]);
	                }
	            }
	        }
	        --$recursive_counter;
    	}
	
	
    	/*
        	调用接口
    	*/
    	public function https_request($url, $data = null)
    	{
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        if (!empty($data)) {
	            curl_setopt($curl, CURLOPT_POST, 1);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        }
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $output = curl_exec($curl);
	        curl_close($curl);
	        return json_decode($output, true);
    	}
}
?>