<?php

/**
 * 公众平台.
 */
class OpenUserMessage extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenUser
     */
    public $name = 'OpenUserMessage';
    
    public function saveMsg($msgType, $msg, $openId, $openTypeId, $sendFrom, $return_code, $return_message, $openType = 'wechat'){
	        $userMsg = array();
	        $userMsg['OpenUserMessage']['open_type'] = $openType;
	        $userMsg['OpenUserMessage']['open_type_id'] = $openTypeId;
	        $userMsg['OpenUserMessage']['open_user_id'] = $openId;
	        $userMsg['OpenUserMessage']['send_from'] = $sendFrom;
	        $userMsg['OpenUserMessage']['msgtype'] = $msgType;
	        $userMsg['OpenUserMessage']['message'] = $msg;
	        $userMsg['OpenUserMessage']['return_code'] = $return_code;
	        $userMsg['OpenUserMessage']['return_message'] = $return_message;
	        $this->saveAll($userMsg);
    }
}
